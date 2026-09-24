<?php
namespace App\Models\Stock;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode as QRCodeRenderer;
use chillerlan\QRCode\QROptions;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    public $timestamps = false;

    protected $fillable = ['target_type', 'target_id', 'code', 'file_path'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Unique 8-character sticker code. Reuses the item-code alphabet so a code
    // printed under the QR is just as easy to read and type by hand.
    public static function generateCode(): string
    {
        do {
            $code = '';
            for ($i = 0; $i < 8; $i++) {
                $code .= Item::CODE_ALPHABET[random_int(0, strlen(Item::CODE_ALPHABET) - 1)];
            }
        } while (static::where('code', $code)->exists());

        return $code;
    }

    // One sticker per target: reuse the existing code rather than minting a
    // second one, so an already-printed label never goes stale.
    public static function forTarget(string $type, int $id): self
    {
        return static::where('target_type', $type)->where('target_id', $id)->latest('id')->first()
            ?? static::create(['target_type' => $type, 'target_id' => $id, 'code' => static::generateCode()]);
    }

    public function target()
    {
        return match ($this->target_type) {
            'item' => Item::find($this->target_id),
            'packet' => Packet::find($this->target_id),
            'box' => Box::find($this->target_id),
        };
    }

    // What the QR encodes: a staff-only URL that logs the scan and opens the detail page.
    public function scanUrl(): string
    {
        return route('stock.qr.resolve', $this->code);
    }

    public function detailUrl(): ?string
    {
        return match ($this->target_type) {
            'item' => route('stock.items.show', $this->target_id),
            'packet' => route('stock.packets.show', $this->target_id),
            'box' => route('stock.boxes.show', $this->target_id),
            default => null,
        };
    }

    public static function labelFor(?Model $target): string
    {
        return match (true) {
            $target instanceof Item => $target->label,
            $target instanceof Packet, $target instanceof Box => $target->code,
            default => 'Unknown',
        };
    }

    // Inline SVG markup. Vector output needs no GD/Imagick, which this hosting plan lacks.
    public function svg(): string
    {
        return static::renderSvg($this->scanUrl());
    }

    public static function renderSvg(string $data): string
    {
        $options = new QROptions([
            'outputType' => QROutputInterface::MARKUP_SVG,
            'outputBase64' => false,
            'svgAddXmlHeader' => false,
            'eccLevel' => EccLevel::M,
            'addQuietzone' => true,
            'quietzoneSize' => 1,
            'drawLightModules' => false,
            'connectPaths' => true,
        ]);

        $svg = (new QRCodeRenderer($options))->render($data);

        // Scale to the container and keep module edges sharp on small printed labels.
        return preg_replace('/<svg /', '<svg width="100%" height="100%" shape-rendering="crispEdges" ', $svg, 1);
    }
}
