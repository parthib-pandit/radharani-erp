<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Stock\BoxManager;
use App\Livewire\Stock\PacketManager;
use App\Livewire\Stock\ItemManager;

// Stock module — behind auth + a permission gate.
// Wire up 'stock.manage' permission via Spatie once roles are seeded.
Route::middleware(['auth'])->prefix('stock')->group(function () {
    Route::get('/boxes', BoxManager::class)->name('stock.boxes');
    Route::get('/packets', PacketManager::class)->name('stock.packets');
    Route::get('/items', ItemManager::class)->name('stock.items');
});
