<?php
namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PhotoCompressionService
{
    // Never store an uncompressed original. Resize + webp at upload time,
    // so there's no separate "compress later" step to forget.
    public function store(UploadedFile $file, string $directory): string
    {
        $filename = $directory . '/' . Str::uuid() . '.webp';

        $encoded = Image::read($file)
            ->scaleDown(width: 1200)
            ->toWebp(quality: 70);

        \Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }
}
