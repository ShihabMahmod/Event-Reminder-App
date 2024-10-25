<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ImportEventController;


Route::get('/', [EventController::class, 'index'])->name('events.index');

Route::resource('event', EventController::class);


Route::get('/import/index', [ImportEventController::class, 'index'])->name('event.import.index');
Route::post('/import/store', [ImportEventController::class, 'store'])->name('event.import.store');