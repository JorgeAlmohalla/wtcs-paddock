<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StandingsController;
use App\Http\Controllers\TeamsController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\PdfController;

/*
|--------------------------------------------------------------------------
| Web Routes (Públicas)
|--------------------------------------------------------------------------
*/

// Home (Tu controlador personalizado)
Route::get('/', HomeController::class)->name('home');

// Páginas Públicas
Route::get('/standings', StandingsController::class)->name('standings');
Route::get('/calendar', CalendarController::class)->name('calendar');
Route::get('/races/{race}', [RaceController::class, 'show'])->name('races.show');
Route::get('/drivers', DriversController::class)->name('drivers');
Route::get('/teams', TeamsController::class)->name('teams');
Route::get('/news', NewsController::class)->name('news.index');
Route::get('/rounds/{round}', [RoundController::class, 'show'])->name('rounds.show');
Route::get('/rounds/{round}/pdf', [PdfController::class, 'downloadRound'])->name('rounds.pdf');

// Noticia individual
Route::get('/news/{post:slug}', function (Post $post) {
    return view('post', ['post' => $post]);
})->name('post.show');

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación (Breeze)
|--------------------------------------------------------------------------
*/

// Dashboard del Piloto (Solo si está logueado)
Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Perfil del Usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/driver-info', [ProfileController::class, 'updateDriverInfo'])->name('profile.driver.update');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

// Carga las rutas de login/registro (NO BORRAR)
require __DIR__.'/auth.php';