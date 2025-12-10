<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StandingsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DriversController;

// Ruta principal usando el controlador que acabamos de crear
Route::get('/', HomeController::class)->name('home');
Route::get('/standings', StandingsController::class)->name('standings');
Route::get('/calendar', CalendarController::class)->name('calendar');
Route::get('/drivers', DriversController::class)->name('drivers');