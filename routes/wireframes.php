<?php

use Illuminate\Support\Facades\Route;

// Static wireframes — view-only, no controllers/logic yet.
// Kept separate from real app routes so they're easy to remove later.
Route::prefix('wireframes')->name('wireframes.')->group(function () {
    Route::view('/', 'wireframes.index')->name('index');
    Route::view('/main', 'wireframes.main')->name('main');
    Route::view('/inventory', 'wireframes.inventory')->name('inventory');
    Route::view('/item-detail', 'wireframes.item-detail')->name('item-detail');
    Route::view('/scan-stock', 'wireframes.scan-stock')->name('scan-stock');
    Route::view('/move-stock', 'wireframes.move-stock')->name('move-stock');
    Route::view('/karigar-dispatch', 'wireframes.karigar-dispatch')->name('karigar-dispatch');
    Route::view('/karigar-return', 'wireframes.karigar-return')->name('karigar-return');
    Route::view('/external-movement', 'wireframes.external-movement')->name('external-movement');
    Route::view('/box-packet', 'wireframes.box-packet')->name('box-packet');
    Route::view('/history', 'wireframes.history')->name('history');
    Route::view('/logbook', 'wireframes.logbook')->name('logbook');
    Route::view('/location-report', 'wireframes.location-report')->name('location-report');
});
