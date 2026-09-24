<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Exchange\NewEntry;
use App\Livewire\Exchange\StatusTracker;
use App\Livewire\Exchange\AccountsValuation;
use App\Livewire\Exchange\RefineryBatchSend;
use App\Livewire\Exchange\RefineryBatchReturn;

// Old Gold/Silver Exchange & Refinery — frontend only for now.
// No exchange_transactions / refinery_batches tables exist in the schema
// yet; see the note atop App\Livewire\Exchange\NewEntry.
Route::middleware(['auth'])->prefix('exchange')->name('exchange.')->group(function () {
    Route::get('/new', NewEntry::class)->name('new');
    Route::get('/tracker', StatusTracker::class)->name('tracker');
    Route::get('/valuation', AccountsValuation::class)->name('valuation');
    Route::get('/refinery/send', RefineryBatchSend::class)->name('refinery.send');
    Route::get('/refinery/return', RefineryBatchReturn::class)->name('refinery.return');
});
