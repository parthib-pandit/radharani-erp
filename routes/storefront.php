<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Storefront\CatalogListing;
use App\Livewire\Storefront\ProductDetail;

// Public — no auth. Anyone can browse the catalog and see live pricing.
Route::prefix('shop')->name('storefront.')->group(function () {
    Route::get('/', CatalogListing::class)->name('catalog');
    Route::get('/{item}', ProductDetail::class)->name('product');
});
