<?php
namespace App\Support;

use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;

/**
 * The typed search inside the camera scanner: the same pieces, packets and boxes the Vault to Counter search finds, by
 * HUID, internal code, category, description, or a packet or box's code or label. Each result carries the `code` to hand
 * to the field as if it had just been scanned, so the page does with it exactly what it does with a scan.
 */
class ScanSearch
{
    /** @return list<array{type: string, code: string, label: string, detail: string}> */
    public static function run(string $term, int $limit = 12): array
    {
        $term = trim($term);
        // A scanned sticker link searches by the code in it.
        $term = preg_match('~/q/([A-Za-z0-9]+)/?$~', $term, $m) ? $m[1] : $term;
        if (mb_strlen($term) < 2) {
            return [];
        }
        $like = '%' . $term . '%';

        $boxes = Box::query()->where(fn ($w) => $w->where('code', 'like', $like)->orWhere('label', 'like', $like))
            ->orderBy('code')->limit(4)->get()
            ->map(fn ($b) => ['type' => 'box', 'code' => $b->code, 'label' => $b->code, 'detail' => 'Box' . ($b->label ? ' · ' . $b->label : '')]);
        $packets = Packet::query()->where(fn ($w) => $w->where('code', 'like', $like)->orWhere('label', 'like', $like))
            ->orderBy('code')->limit(4)->get()
            ->map(fn ($p) => ['type' => 'packet', 'code' => $p->code, 'label' => $p->code, 'detail' => 'Packet' . ($p->label ? ' · ' . $p->label : '')]);
        $items = Item::query()->where('status', '!=', 'sold')
            ->where(fn ($w) => $w->where('huid_code', 'like', $like)->orWhere('internal_code', 'like', $like)->orWhere('category', 'like', $like)->orWhere('description', 'like', $like))
            // A code that starts with what was typed comes first.
            ->orderByRaw('(huid_code like ? or internal_code like ?) desc', [$term . '%', $term . '%'])->orderBy('category')->limit($limit)->get()
            ->map(fn ($i) => ['type' => 'item', 'code' => $i->label, 'label' => $i->label, 'detail' => trim($i->category . ' · ' . number_format((float) $i->weight, 3) . ' g' . ($i->status !== 'in_stock' ? ' · ' . str_replace('_', ' ', $i->status) : ''))]);

        return $boxes->concat($packets)->concat($items)->take($limit)->values()->all();
    }
}
