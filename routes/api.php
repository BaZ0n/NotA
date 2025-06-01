<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Http;
use App\Models\Playlist;
use Illuminate\Support\Facades\Storage;

Route::get('/playlist/{playlistID}/tracks', [AudioController::class, 'getPlaylistTracks']);

Route::get("/play-audio", [AudioController::class, 'playAudio'])->name('playaudio');

// Route::prefix('player')->group(function () {
//     Route::post('/play', [PlayerController::class, 'play']);
//     Route::post('/pause', [PlayerController::class, 'pause']);
//     Route::post('/next', [PlayerController::class, 'next']);
//     Route::post('/previous', [PlayerController::class, 'previous']);
//     Route::post('/seek', [PlayerController::class, 'seek']);
//     Route::post('/queue/add', [PlayerController::class, 'addToQueue']);
// });

Route::post('/player/play', [PlayerController::class, 'play']);
Route::post('/player/pause', [PlayerController::class, 'pause']);
Route::post('/player/seek', [PlayerController::class, 'seek']);
Route::post('/player/next', [PlayerController::class, 'next']);
Route::post('/player/previous', [PlayerController::class, 'previous']);
Route::post('/player/add-to-queue', [PlayerController::class, 'addToQueue']);

Route::get('/user/lastInfo', [AudioController::class, 'checkLastInfo']);