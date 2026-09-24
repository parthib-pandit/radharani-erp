<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Portal\CustomerLogin;
use App\Livewire\Portal\CustomerDashboard;
use App\Livewire\Portal\ChangePassword;

// Customer-facing portal — entirely separate 'customer' guard from staff.
// See config/auth-additions.md for the guard/provider config this needs.
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/login', CustomerLogin::class)
        ->middleware('guest:customer')->name('login');

    Route::get('/dashboard', CustomerDashboard::class)
        ->middleware('auth:customer')->name('dashboard');

    Route::get('/change-password', ChangePassword::class)
        ->middleware('auth:customer')->name('change-password');
});
