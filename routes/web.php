<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ImportEventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegistrationController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('event', EventController::class)->middleware('auth');


Route::get('/import/index', [ImportEventController::class, 'index'])->name('event.import.index');
Route::post('/import/store', [ImportEventController::class, 'store'])->name('event.import.store');


Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/store/login', [LoginController::class, 'login'])->name('store.login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/register', [RegistrationController::class, 'index'])->name('register');
Route::post('/store/register', [RegistrationController::class, 'register'])->name('store.register');


