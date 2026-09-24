<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pricing\DailyRateEntry;
use App\Livewire\Pricing\RateHistoryLog;
use App\Livewire\Pricing\MakingChargeConfig;
use App\Livewire\Pricing\DiscountRulesManager;
use App\Livewire\Pricing\AdditionalChargesConfig;

Route::middleware(['auth'])->prefix('pricing')->name('pricing.')->group(function () {
    Route::get('/rates', DailyRateEntry::class)->name('rates');
    Route::get('/rates/history', RateHistoryLog::class)->name('rates.history');
    Route::get('/making-charges', MakingChargeConfig::class)->name('making-charges');
    Route::get('/discounts', DiscountRulesManager::class)->name('discounts');
    Route::get('/additional-charges', AdditionalChargesConfig::class)->name('additional-charges');
});
