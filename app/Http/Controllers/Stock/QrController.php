<?php
namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\QrCode;
use App\Services\StockHistoryService;
use Illuminate\Http\Request;

class QrController extends Controller
{
    // What every printed sticker points at. Logs the scan against the
    // target's history, then opens its detail page.
    public function resolve(string $code)
    {
        $qr = QrCode::where('code', strtoupper($code))->firstOrFail();
        $target = $qr->target();

        abort_unless($target, 404, 'This QR code points to a record that no longer exists.');

        StockHistoryService::logScan($target, $qr->code);

        return redirect()->to($qr->detailUrl());
    }

    // Printable sticker sheet: /stock/qr-codes/print?ids=1,2,3
    public function print(Request $request)
    {
        $ids = collect(explode(',', (string) $request->query('ids')))
            ->map(fn ($id) => (int) $id)->filter()->unique()->take(300);

        $codes = QrCode::whereIn('id', $ids)->get()
            ->sortBy(fn ($qr) => $ids->search($qr->id))
            ->map(function (QrCode $qr) {
                $target = $qr->target();

                return [
                    'qr' => $qr,
                    'label' => QrCode::labelFor($target),
                    'sub' => match ($qr->target_type) {
                        'item' => $target ? trim($target->category . ' · ' . number_format((float) $target->weight, 3) . ' g') : '',
                        'packet' => $target?->label ?: 'Packet',
                        'box' => $target?->label ?: 'Box',
                    },
                    'type' => $qr->target_type,
                ];
            })->values();

        abort_if($codes->isEmpty(), 404, 'No QR codes selected for printing.');

        return view('stock.qr-print', [
            'codes' => $codes,
            'size' => in_array($request->query('size'), ['sm', 'md', 'lg'], true) ? $request->query('size') : 'md',
        ]);
    }
}
