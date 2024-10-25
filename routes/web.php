<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;


Route::get('/', [EventController::class, 'index'])->name('events.index');
Route::resource('event', EventController::class);


