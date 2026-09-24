<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Loyalty\LoyaltyAward;
use App\Livewire\Loyalty\LoyaltyLedger;

Route::middleware(['auth'])->prefix('loyalty')->group(function () {
    Route::get('/award', LoyaltyAward::class)
        ->middleware('permission:loyalty.manage')->name('loyalty.award');

    Route::get('/ledger', LoyaltyLedger::class)
        ->middleware('permission:loyalty.manage')->name('loyalty.ledger');
});
