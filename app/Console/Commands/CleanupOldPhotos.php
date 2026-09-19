<?php
namespace App\Console\Commands;

use App\Models\Movement\Movement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupOldPhotos extends Command
{
    protected $signature = 'photos:cleanup';
    protected $description = 'Delete movement photos older than 90 days (not ecommerce images)';

    public function handle(): void
    {
        $cutoff = now()->subDays(90);

        Movement::whereNotNull('photo_path')
            ->where('created_at', '<', $cutoff)
            ->chunkById(100, function ($movements) {
                foreach ($movements as $movement) {
                    Storage::disk('public')->delete($movement->photo_path);
                    $movement->update(['photo_path' => null]);
                }
            });

        $this->info('Old movement photos cleaned up.');
    }
}
