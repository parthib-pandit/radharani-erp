<?php
namespace App\Services;

use App\Models\Movement\Movement;
use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

/**
 * Builds the single "where has this been?" timeline shown on Item, Packet and
 * Box Detail. Read-only: it stitches together
 *   - movements (the tamper-evident physical trail),
 *   - activity_log entries for regrouping (packet_id / box_id changes), edits and QR scans,
 *   - sale_items (for items),
 * so all three detail pages present history the same way.
 *
 * Each event: at, kind, icon, tone (out|in|gold|neutral), title, meta[], note, user, photo, link.
 */
class StockHistoryService
{
    public const MOVEMENT_LABELS = [
        'vault_out' => 'Out of the vault',
        'vault_in' => 'Back in the vault',
        'karigar_out' => 'Sent to karigar',
        'karigar_in' => 'Returned from karigar',
        'hallmark_out' => 'Sent for hallmarking',
        'hallmark_in' => 'Returned from hallmarking',
        'photo_out' => 'Out for photography',
        'photo_in' => 'Back from photography',
        'custom_out' => 'Out for a custom purpose',
        'custom_in' => 'Back from a custom purpose',
        'melt_out' => 'Sent for melting',
        'melt_in' => 'Back from melting',
        'correction' => 'Correction entry',
    ];

    public function forItem(Item $item): Collection
    {
        $events = $this->movementEvents('item', $item->id);

        $activities = Activity::with('causer')
            ->where('subject_type', $item->getMorphClass())
            ->where('subject_id', $item->id)
            ->get();

        $packetCodes = $this->packetCodes($activities);

        foreach ($activities as $a) {
            $new = $a->attribute_changes['attributes'] ?? [];
            $old = $a->attribute_changes['old'] ?? [];

            if ($a->event === 'scanned') {
                $events->push($this->scanEvent($a));
                continue;
            }

            if ($a->event === 'created') {
                continue; // covered by the "entered into stock" event below
            }

            if (array_key_exists('packet_id', $new)) {
                $from = $old['packet_id'] ?? null;
                $to = $new['packet_id'];
                $events->push($this->event($a, 'regroup', 'package', 'gold',
                    $to ? 'Placed in packet ' . ($packetCodes[$to] ?? "#{$to}") : 'Removed from packet ' . ($packetCodes[$from] ?? "#{$from}"),
                    $from && $to ? ['From packet ' . ($packetCodes[$from] ?? "#{$from}")] : []));
            }

            // Ignore a "change" with no previous value (a DB default being logged for the first time).
            if (array_key_exists('status', $new) && isset($old['status']) && $old['status'] !== $new['status']) {
                $events->push($this->event($a, 'status', 'shield-check', 'neutral',
                    'Status changed to ' . str_replace('_', ' ', (string) $new['status']),
                    ['Was ' . str_replace('_', ' ', (string) $old['status'])]));
            }

            if (array_key_exists('pair_group_id', $new)) {
                $events->push($this->event($a, 'edit', $new['pair_group_id'] ? 'link' : 'unlink', 'neutral',
                    $new['pair_group_id'] ? 'Linked as a pair' : 'Pair removed'));
            }

            $edited = array_diff(array_keys($new), ['packet_id', 'status', 'pair_group_id']);
            if ($edited) {
                $events->push($this->event($a, 'edit', 'edit', 'neutral', 'Details edited',
                    [collect($edited)->map(fn ($f) => str_replace('_', ' ', $f))->implode(', ')]));
            }
        }

        foreach ($item->sales()->get() as $sale) {
            $events->push([
                'at' => $sale->created_at,
                'kind' => 'sale',
                'icon' => 'receipt',
                'tone' => 'gold',
                'title' => $sale->confirmed_by_accountant ? 'Sold (verified)' : 'Sale entered, awaiting verification',
                'meta' => array_filter([
                    'Invoice ' . $sale->invoice_number,
                    '₹' . number_format((float) $sale->pivot->price_at_sale, 2),
                ]),
                'note' => null,
                'user' => optional($sale->creator)->name,
                'photo' => null,
                'link' => \Route::has('sales.invoice') ? route('sales.invoice', $sale) : null,
            ]);
        }

        $creator = $activities->firstWhere('event', 'created')?->causer?->name;
        $origin = match (true) {
            (bool) $item->source_purchase_item_id => 'From a raw-material purchase line',
            (bool) $item->source_karigar_batch_id => 'From karigar raw-material batch #' . $item->source_karigar_batch_id,
            default => null,
        };
        $events->push([
            'at' => $item->created_at,
            'kind' => 'created',
            'icon' => 'plus',
            'tone' => 'in',
            'title' => 'Entered into stock',
            'meta' => array_values(array_filter([$origin])),
            'note' => null,
            'user' => $creator,
            'photo' => null,
            'link' => null,
        ]);

        return $this->sort($events);
    }

    public function forPacket(Packet $packet): Collection
    {
        $events = $this->movementEvents('packet', $packet->id);

        // The packet's own changes: created, renamed, moved between boxes, scanned.
        $own = Activity::with('causer')
            ->where('subject_type', $packet->getMorphClass())
            ->where('subject_id', $packet->id)
            ->get();
        $boxCodes = Box::whereIn('id', $own->flatMap(fn ($a) => [
            $a->attribute_changes['attributes']['box_id'] ?? null,
            $a->attribute_changes['old']['box_id'] ?? null,
        ])->filter()->unique())->pluck('code', 'id');

        foreach ($own as $a) {
            $new = $a->attribute_changes['attributes'] ?? [];
            $old = $a->attribute_changes['old'] ?? [];

            if ($a->event === 'scanned') {
                $events->push($this->scanEvent($a));
                continue;
            }
            if ($a->event === 'created') {
                $events->push($this->event($a, 'created', 'plus', 'in', 'Packet created',
                    ! empty($new['box_id']) ? ['In box ' . ($boxCodes[$new['box_id']] ?? '#' . $new['box_id'])] : []));
                continue;
            }
            if (array_key_exists('box_id', $new)) {
                $to = $new['box_id'];
                $from = $old['box_id'] ?? null;
                $events->push($this->event($a, 'regroup', 'archive', 'gold',
                    $to ? 'Moved into box ' . ($boxCodes[$to] ?? "#{$to}") : 'Taken out of box ' . ($boxCodes[$from] ?? "#{$from}"),
                    $from && $to ? ['From box ' . ($boxCodes[$from] ?? "#{$from}")] : []));
            }
            if (array_key_exists('code', $new) || array_key_exists('label', $new)) {
                $events->push($this->event($a, 'edit', 'edit', 'neutral', 'Packet renamed',
                    array_filter([
                        isset($new['code']) ? 'Code ' . ($old['code'] ?? '') . ' → ' . $new['code'] : null,
                        array_key_exists('label', $new) ? 'Label "' . ($new['label'] ?? '') . '"' : null,
                    ])));
            }
        }

        // Items entering / leaving this packet.
        $itemActs = $this->containerActivities((new Item)->getMorphClass(), 'packet_id', $packet->id);
        $labels = Item::whereIn('id', $itemActs->pluck('subject_id')->unique())->get()->keyBy('id');
        foreach ($itemActs as $a) {
            $item = $labels[$a->subject_id] ?? null;
            $name = $item ? $item->label : "#{$a->subject_id}";
            $added = (int) ($a->attribute_changes['attributes']['packet_id'] ?? 0) === $packet->id;
            $events->push($this->event($a, 'regroup', $added ? 'corner-down-right' : 'unlink', $added ? 'in' : 'out',
                ($added ? 'Item added: ' : 'Item removed: ') . $name,
                $item ? [trim($item->category . ' · ' . number_format((float) $item->weight, 3) . ' g')] : [],
                $item ? route('stock.items.show', $item) : null));
        }

        return $this->sort($events);
    }

    public function forBox(Box $box): Collection
    {
        $events = $this->movementEvents('box', $box->id);

        $own = Activity::with('causer')
            ->where('subject_type', $box->getMorphClass())
            ->where('subject_id', $box->id)
            ->get();

        foreach ($own as $a) {
            $new = $a->attribute_changes['attributes'] ?? [];
            $old = $a->attribute_changes['old'] ?? [];
            match ($a->event) {
                'scanned' => $events->push($this->scanEvent($a)),
                'created' => $events->push($this->event($a, 'created', 'plus', 'in', 'Box created')),
                default => $events->push($this->event($a, 'edit', 'edit', 'neutral', 'Box renamed', array_filter([
                    isset($new['code']) ? 'Code ' . ($old['code'] ?? '') . ' → ' . $new['code'] : null,
                    array_key_exists('label', $new) ? 'Label "' . ($new['label'] ?? '') . '"' : null,
                ]))),
            };
        }

        $packetActs = $this->containerActivities((new Packet)->getMorphClass(), 'box_id', $box->id);
        $packets = Packet::whereIn('id', $packetActs->pluck('subject_id')->unique())->get()->keyBy('id');
        foreach ($packetActs as $a) {
            $p = $packets[$a->subject_id] ?? null;
            $added = (int) ($a->attribute_changes['attributes']['box_id'] ?? 0) === $box->id;
            $events->push($this->event($a, 'regroup', $added ? 'corner-down-right' : 'unlink', $added ? 'in' : 'out',
                ($added ? 'Packet added: ' : 'Packet removed: ') . ($p?->code ?? "#{$a->subject_id}"),
                [], $p ? route('stock.packets.show', $p) : null));
        }

        return $this->sort($events);
    }

    // Log a QR scan against whatever the code points to (called from the /q/{code} resolver).
    public static function logScan($target, string $code): void
    {
        activity('stock')
            ->performedOn($target)
            ->causedBy(auth()->user())
            ->event('scanned')
            ->withProperties(['qr_code' => $code])
            ->log('QR scanned');
    }

    // --- helpers -----------------------------------------------------------

    private function movementEvents(string $type, int $id): Collection
    {
        return Movement::with(['user', 'approver'])
            ->where('trackable_type', $type)
            ->where('trackable_id', $id)
            ->get()
            ->map(function (Movement $m) {
                $isOut = str_ends_with($m->movement_type, '_out');
                $isCorrection = $m->movement_type === 'correction';

                $meta = array_values(array_filter([
                    $m->purpose_label,
                    $m->counterparty ? 'With ' . $m->counterparty : null,
                    $m->weight_at_dispatch ? 'Out at ' . number_format((float) $m->weight_at_dispatch, 3) . ' g' : null,
                    $m->weight_at_return ? 'Back at ' . number_format((float) $m->weight_at_return, 3) . ' g' : null,
                    $m->weight_loss ? 'Loss ' . number_format((float) $m->weight_loss, 3) . ' g' : null,
                    $m->expected_return && $isOut ? 'Due ' . \Carbon\Carbon::parse($m->expected_return)->format('d M Y') : null,
                    $m->tagged_by ? 'Tagged by ' . $m->tagged_by : null,
                    $m->approver ? 'Confirmed by ' . $m->approver->name : null,
                    $isCorrection && $m->reverses_movement_id ? 'Reverses entry #' . $m->reverses_movement_id : null,
                ]));

                return [
                    'at' => $m->created_at,
                    'kind' => 'movement',
                    'icon' => $isCorrection ? 'refresh' : ($isOut ? 'arrow-up' : 'arrow-down'),
                    'tone' => $isCorrection ? 'neutral' : ($isOut ? 'out' : 'in'),
                    'title' => self::MOVEMENT_LABELS[$m->movement_type] ?? ucwords(str_replace('_', ' ', $m->movement_type)),
                    'meta' => $meta,
                    'note' => $m->note,
                    'user' => $m->user?->name,
                    'photo' => $m->photo_path ? Storage::disk('public')->url($m->photo_path) : null,
                    'link' => null,
                ];
            });
    }

    // Activity rows for children whose container column changed to or from $id.
    private function containerActivities(string $subjectType, string $column, int $id): Collection
    {
        return Activity::with('causer')
            ->where('subject_type', $subjectType)
            ->whereIn('event', ['created', 'updated'])
            ->where(function ($q) use ($column, $id) {
                $q->where("attribute_changes->attributes->{$column}", $id)
                    ->orWhere("attribute_changes->old->{$column}", $id);
            })
            ->get()
            // Only rows where the container actually changed (ignore unrelated edits that carry old values).
            ->filter(function ($a) use ($column) {
                $new = $a->attribute_changes['attributes'] ?? [];
                return array_key_exists($column, $new);
            });
    }

    private function packetCodes(Collection $activities): array
    {
        $ids = $activities->flatMap(fn ($a) => [
            $a->attribute_changes['attributes']['packet_id'] ?? null,
            $a->attribute_changes['old']['packet_id'] ?? null,
        ])->filter()->unique();

        return $ids->isEmpty() ? [] : Packet::whereIn('id', $ids)->pluck('code', 'id')->all();
    }

    private function scanEvent(Activity $a): array
    {
        return $this->event($a, 'scan', 'qr-code', 'neutral', 'QR code scanned',
            [($a->properties['qr_code'] ?? null) ? 'Code ' . $a->properties['qr_code'] : null]);
    }

    private function event(Activity $a, string $kind, string $icon, string $tone, string $title, array $meta = [], ?string $link = null): array
    {
        return [
            'at' => $a->created_at,
            'kind' => $kind,
            'icon' => $icon,
            'tone' => $tone,
            'title' => $title,
            'meta' => array_values(array_filter($meta)),
            'note' => null,
            'user' => $a->causer?->name,
            'photo' => null,
            'link' => $link,
        ];
    }

    private function sort(Collection $events): Collection
    {
        return $events->filter(fn ($e) => $e['at'])->sortByDesc(fn ($e) => $e['at']->getTimestamp())->values();
    }
}
