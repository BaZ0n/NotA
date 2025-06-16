<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Middleware;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PlaylistsController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\TracksController;
use App\Http\Controllers\UsersController;

Route::get("/play-audio", [AudioController::class, 'playAudio'])->name('playaudio');

Route::get("/play-playlist", [AudioController::class, 'playPlaylist'])->name('playplaylist');

// Плейлисты
Route::get('/playlist/{userID}', [PlaylistsController::class, 'getUserPlaylists']);
Route::get('/playlist/{playlistID}/tracks', [PlaylistsController::class, 'playlistTracks']);

// Исполнители
Route::get('/artist/{artistID}/tracks', [ArtistController::class, 'artistTracks']);
Route::get('/artist/{artistID}/albums', [ArtistController::class, 'artistAlbums']);
Route::get('/collection/{userID}/artists', [ArtistController::class, 'favoriteArtist']);

// Пользователи
Route::get('/users/get/{searchQuery}', [UsersController::class, 'getUserWith']);

// Треки
Route::get('/tracks/get/{searchQueryTrack}', [TracksController::class, 'getTrackWith']);
Route::get('/tracks/get', [TracksController::class, 'getAllTracks']);