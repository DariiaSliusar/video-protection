<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/', function () {
    if (!Session::has('video_allowed')) {
        Session::put('video_allowed', true);
    }
    return view('video');
})->name('video.watch');

// Плейлист
Route::get('/video/stream.m3u8', [VideoController::class, 'getPlaylist'])
    ->name('video.playlist');

// Сегменти
Route::get('/video/segments/{filename}', [VideoController::class, 'getSegment'])
    ->name('video.segment')
    ->middleware('signed');
