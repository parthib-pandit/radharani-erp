<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Stock\BoxManager;
use App\Livewire\Stock\BoxDetail;
use App\Livewire\Stock\PacketManager;
use App\Livewire\Stock\PacketDetail;
use App\Livewire\Stock\ItemManager;
use App\Livewire\Stock\ItemDetail;
use App\Livewire\Stock\AssignToContainer;
use App\Livewire\Stock\QrGenerator;
use App\Livewire\Stock\BulkImport;
use App\Livewire\Stock\HierarchyConfigurator;

// Stock module — behind auth + a permission gate.
// Wire up 'stock.manage' permission via Spatie once roles are seeded.
Route::middleware(['auth'])->prefix('stock')->group(function () {
    Route::get('/boxes', BoxManager::class)->name('stock.boxes');
    Route::get('/boxes/{box}', BoxDetail::class)->name('stock.boxes.show');

    Route::get('/packets', PacketManager::class)->name('stock.packets');
    Route::get('/packets/{packet}', PacketDetail::class)->name('stock.packets.show');

    Route::get('/items', ItemManager::class)->name('stock.items');
    Route::get('/items/{item}', ItemDetail::class)->name('stock.items.show');

    Route::get('/assign', AssignToContainer::class)->name('stock.assign');
    Route::get('/qr-codes', QrGenerator::class)->name('stock.qr-codes');
    Route::get('/import', BulkImport::class)->name('stock.import');
    Route::get('/configurator', HierarchyConfigurator::class)->name('stock.configurator');
});
