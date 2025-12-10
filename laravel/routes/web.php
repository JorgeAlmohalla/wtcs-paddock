<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StandingsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\TeamsController;
use App\Models\Post;
use App\Http\Controllers\NewsController;

// Ruta principal usando el controlador que acabamos de crear
Route::get('/', HomeController::class)->name('home');
Route::get('/standings', StandingsController::class)->name('standings');
Route::get('/calendar', CalendarController::class)->name('calendar');
Route::get('/drivers', DriversController::class)->name('drivers');
Route::get('/teams', TeamsController::class)->name('teams');
Route::get('/news/{post:slug}', function (Post $post) {
    return view('post', ['post' => $post]);
})->name('post.show');
Route::get('/news', NewsController::class)->name('news.index');