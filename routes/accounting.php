<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Accounting\LedgerView;
use App\Livewire\Accounting\AccountsList;
use App\Livewire\Accounting\TallyExport;

Route::middleware(['auth'])->prefix('accounting')->group(function () {
    Route::get('/ledger', LedgerView::class)
        ->middleware('permission:ledger.view')->name('accounting.ledger');

    Route::get('/accounts', AccountsList::class)
        ->middleware('permission:ledger.view')->name('accounting.accounts');

    Route::get('/tally-export', TallyExport::class)
        ->middleware('permission:ledger.view')->name('accounting.tally-export');
});
