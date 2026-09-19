<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckDiskUsage extends Command
{
    protected $signature = 'disk:check';
    protected $description = 'Alert owner if disk usage exceeds 80%';

    public function handle(): void
    {
        $total = disk_total_space(storage_path());
        $free = disk_free_space(storage_path());
        $usedPercent = round((($total - $free) / $total) * 100, 1);

        if ($usedPercent > 80) {
            // TODO: send WhatsApp/email alert to owner
            $this->warn("Disk usage at {$usedPercent}% — alert owner.");
        } else {
            $this->info("Disk usage at {$usedPercent}%.");
        }
    }
}
