<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Notifications\PendingMessagesQueue;

Route::middleware(['auth'])->prefix('notifications')->group(function () {
    Route::get('/queue', PendingMessagesQueue::class)->name('notifications.queue');
});
