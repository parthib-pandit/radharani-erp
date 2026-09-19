<?php
namespace App\Services;

use App\Models\Movement\RateLog;
use Illuminate\Support\Facades\Http;

class RateFetchService
{
    // Called by the daily FetchDailyRate command. Manual entry always
    // remains possible and always takes precedence if entered — never
    // make the shop dependent on this API being up.
    public function fetchAndStore(): void
    {
        // Replace with actual provider (GoldAPI.io / MetalpriceAPI) call.
        $response = Http::get(config('services.rate_api.url'));

        if (! $response->ok()) {
            return; // fail silently, manual entry stays available
        }

        $data = $response->json();

        RateLog::create([
            'metal' => 'gold',
            'rate' => $data['gold_rate'] ?? null,
            'source' => 'api',
        ]);

        RateLog::create([
            'metal' => 'silver',
            'rate' => $data['silver_rate'] ?? null,
            'source' => 'api',
        ]);
    }
}
