<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Middleware;
use App\Http\Controllers\AudioController;

Route::get('/playlist/{playlistID}/tracks', [AudioController::class, 'getPlaylistTracks']);

Route::get("/play-audio", [AudioController::class, 'playAudio'])->name('playaudio');

Route::get('/user/lastInfo', [AudioController::class, 'checkLastInfo']);
