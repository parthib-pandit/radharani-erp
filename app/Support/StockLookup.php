<?php
namespace App\Support;

use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use App\Models\Stock\QrCode;

// Resolves whatever a barcode/QR scanner (or a person) types into a record.
// Accepts HUIDs, internal codes, packet/box codes, printed QR sticker codes,
// and the full scan URL a QR sticker encodes (…/stock/q/CODE).
class StockLookup
{
    public static function normalize(?string $raw): string
    {
        $value = trim((string) $raw);

        if (preg_match('~/q/([A-Za-z0-9]+)/?$~', $value, $m)) {
            $value = $m[1];
        }

        return strtoupper($value);
    }

    public static function item(?string $raw): ?Item
    {
        $code = self::normalize($raw);
        if ($code === '') {
            return null;
        }

        return Item::where('huid_code', $code)->orWhere('internal_code', $code)->first()
            ?? self::fromQr($code, 'item');
    }

    /** @return array{type: 'packet'|'box', model: Packet|Box}|null */
    public static function container(?string $raw): ?array
    {
        $code = self::normalize($raw);
        if ($code === '') {
            return null;
        }

        if ($packet = Packet::where('code', $code)->first()) {
            return ['type' => 'packet', 'model' => $packet];
        }
        if ($box = Box::where('code', $code)->first()) {
            return ['type' => 'box', 'model' => $box];
        }

        $qr = QrCode::where('code', $code)->whereIn('target_type', ['packet', 'box'])->first();
        $target = $qr?->target();

        return $target ? ['type' => $qr->target_type, 'model' => $target] : null;
    }

    public static function packet(?string $raw): ?Packet
    {
        $code = self::normalize($raw);

        return $code === '' ? null : (Packet::where('code', $code)->first() ?? self::fromQr($code, 'packet'));
    }

    private static function fromQr(string $code, string $type)
    {
        $qr = QrCode::where('code', $code)->where('target_type', $type)->first();

        return $qr?->target();
    }
}
