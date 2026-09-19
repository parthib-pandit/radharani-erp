<?php
namespace App\Console\Commands;

use App\Services\RateFetchService;
use Illuminate\Console\Command;

class FetchDailyRate extends Command
{
    protected $signature = 'rates:fetch';
    protected $description = 'Fetch daily gold/silver rate from external API';

    public function handle(RateFetchService $service): void
    {
        $service->fetchAndStore();
        $this->info('Rate fetch attempted.');
    }
}
