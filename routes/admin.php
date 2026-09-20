<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\EmployeeManager;
use App\Livewire\Admin\UserManager;
use App\Livewire\Admin\RoleManager;
use App\Livewire\Admin\LoyaltySettingsManager;
use App\Livewire\Admin\ReferralOverview;

// Owner/manager only — gated by Spatie permissions, seeded via RolePermissionSeeder.
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/employees', EmployeeManager::class)
        ->middleware('permission:employee.manage')->name('admin.employees');

    Route::get('/users', UserManager::class)
        ->middleware('permission:user.manage')->name('admin.users');

    Route::get('/roles', RoleManager::class)
        ->middleware('permission:role.manage')->name('admin.roles');

    Route::get('/loyalty-settings', LoyaltySettingsManager::class)
        ->middleware('permission:loyalty.manage')->name('admin.loyalty-settings');

    Route::get('/referrals', ReferralOverview::class)
        ->middleware('permission:loyalty.manage')->name('admin.referrals');
});
