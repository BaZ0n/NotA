<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AudioController;
use Illuminate\Support\Facades\Http;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/playlistPage/{playlistID}/tracks', [AudioController::class, 'getTracks']);

Route::get("/play-audio", [AudioController::class, 'playAudio'])->name('playaudio');