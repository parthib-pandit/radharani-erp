<?php

use App\Livewire\Cash\CashBookPage;
use Illuminate\Support\Facades\Route;

// The cash book: the owner and manager (cashbook.manage). Every entry carries the person's name and nothing is edited.
Route::middleware(['auth', 'permission:cashbook.manage'])->get('/cash-book', CashBookPage::class)->name('cashbook');
