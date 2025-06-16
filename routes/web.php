<?php

use App\Http\Controllers\AudioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PlaylistsController;
use App\Http\Controllers\TracksController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Middleware;

Route::get("/logout", [LoginController::class, 'logout']);

Route::middleware('guest')->group(function() {
    Route::get('/', [LoginController::class, 'welcome']);
    Route::get('/login', [LoginController::class, 'welcome']);

    Route::get('/registrationProfile', [LoginController::class, 'registrationProfile']);
    Route::post('/registrationProfile/confirm', [LoginController::class, 'registrationConfirm']);

    Route::post("/login/check", [LoginController::class, 'loginCheck']);
});

Route::middleware('auth')->group(function() {
    Route::get('/verify-email', function () {
        return view('loginSignIn/verify-email');
    })->middleware('auth')->name('verification.notice');


    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        
        return redirect('/main');
    })->middleware(['auth', 'signed'])->name('verification.verify');


    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Ссылка для подтверждения отправлена!');
    })->middleware(['auth', 'throttle:3,1'])->name('verification.send');
});

Route::middleware(['auth', 'verified'])->group(function() {

    Route::post('/channels/create', [SyncController::class, 'createChannel']);

    // Маршруты к основной странице
    Route::get("/home", [MainController::class, 'mainPage']);
    Route::get("/main", [MainController::class, 'mainPage']); 
    Route::get("/mainPage", [MainController::class, 'mainPage']);
    
    // Страница пользователя
    Route::get("/user", [MainController::class,'userPage']);

    // Маршрут к странице коллекции
    Route::get("/collection", [MainController::class, 'collectionPage']);

    // Маршрут к исполнителю
    Route::get("/artist/{artistID}", [MainController::class, 'artist']);

    // Плейлист
    Route::post('/playlist', [PlaylistsController::class, 'store'])->name('playlists.store'); // Создание плейлиста (AJAX)
    Route::get('/playlist/{playlistID}', [PlaylistsController::class, 'show_playlist'])->name('playlist.show'); // Просмотр плейлиста
    Route::post('/playlist/{playlistID}/upload-audio', [PlaylistsController::class, 'trackUpload'])->name('playlist.upload-audio'); // Загрузка трека в плейлист
    Route::put('/playlist/update/{playlist}', [PlaylistsController::class, 'playlistNameUpdate']) -> name('playlistName.update'); // Новое название плейлиста
    Route::post('/playlist/{playlistID}/upload-audio', [PlaylistsController::class, 'trackUpload'])->name('playlist.upload-audio'); // Загрузка трека в плейлист
    Route::put('/playlist/{playlistID}/favorite', [PlaylistsController::class, 'addToFavoritePlaylist']);
    Route::delete('/playlist/{playlistID}/deleteFavorite', [PlaylistsController::class, 'deleteFromFavoritePlaylist']);
    Route::put('/playlist/{playlistID}/moders/{userID}', [PlaylistsController::class, 'createModer']);
    Route::put('/playlist/{playlistID}/addTrack/{trackID}', [PlaylistsController::class, 'addTrackToPlaylist']);
    Route::get('/playlist/{playlistID}/isFavoriteAndUserModer', [PlaylistsController::class, 'isFavoriteAndUserModer']);
    // Route::put('/playlist/{playlistID}/track/{trackID}', [MainController::class, 'addTrackToPlaylist']);


    Route::put('/favorite/tracks/{trackID}', [TracksController::class, 'addTrackToFavorite']);
    Route::get('/tracks/isFavorite/{trackID}', [TracksController::class, 'isFavoriteTrack']);

    // Пользователи
    Route::put('/user/lastUse/{playlistID}/{trackID}/{volumeLevel}', [UsersController::class, 'addUserLastUseInfo']);
    Route::get('/user-lastInfo', [UsersController::class, 'checkLastInfo']);
    Route::get('/users/get', [UsersController::class, 'usersGet']);

    Route::post('/sync-track', [SyncController::class, 'syncTrack']);

    Route::post('/update-queue', [SyncController::class, 'syncQueue']);



});