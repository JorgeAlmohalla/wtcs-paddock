<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Ruta principal usando el controlador que acabamos de crear
Route::get('/', HomeController::class)->name('home');