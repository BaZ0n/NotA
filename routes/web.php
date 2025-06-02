<?php

use App\Http\Controllers\AudioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
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

    Route::post('/channels/create', [MainController::class, 'createChannel']);

    // Маршруты к основной странице
    Route::get("/home", [MainController::class, 'mainPage']);
    Route::get("/main", [MainController::class, 'mainPage']); 
    Route::get("/mainPage", [MainController::class, 'mainPage']);

    // Маршрут к странице коллекции
    Route::get("/collection", [MainController::class, 'collectionPage']);

    // Маршрут к странице плейлиста
    Route::post('/playlist', [MainController::class, 'store'])->name('playlists.store'); // Создание плейлиста (AJAX)
    // Route::get("/playlist", [MainController::class, 'playlistPage']); // Страница пустого плейлиста
    Route::get('/playlist/{playlistID}', [MainController::class, 'show_playlist'])->name('playlist.show'); // Просмотр плейлиста
    Route::post('/playlist/{playlistID}/upload-audio', [MainController::class, 'trackUpload'])->name('playlist.upload-audio'); // Загрузка трека в плейлист
    Route::put('/playlist/update/{playlist}', [MainController::class, 'playlistNameUpdate']) -> name('playlistName.update'); // Новое название плейлиста
    Route::get('/playlist/{userID}', [MainController::class, 'getUserPlaylists']);
    Route::post('/playlist/{playlistID}/upload-audio', [MainController::class, 'trackUpload'])->name('playlist.upload-audio'); // Загрузка трека в плейлист
    Route::get("/user", [MainController::class,'userPage']);
    Route::get("/artist/{artistID}", [MainController::class, 'artist']);


    Route::get('/collection/{userID}/artists', [MainController::class, 'favoriteArtist']);

    Route::get('/artist/{artistID}/tracks', [MainController::class, 'artistTracks']);

    Route::get('/artist/{artistID}/albums', [MainController::class, 'artistAlbums']);

    Route::put('/playlist/{playlistID}/favorite', [MainController::class, 'addToFavoritePlaylist']);

    Route::get('/playlist/{playlistID}/isFavoriteAndUserModer', [MainController::class, 'isFavoriteAndUserModer']);

    Route::delete('/playlist/{playlistID}/deleteFavorite', [MainController::class, 'deleteFromFavoritePlaylist']);

    Route::get('/playlist/{playlistID}/tracks', [MainController::class, 'playlistTracks']);

    Route::get('/users/get', [MainController::class, 'usersGet']);

    Route::get('users/get/{searchQuery}', [MainController::class, 'getUserWith']);

    Route::get('/tracks/get/{searchQueryTrack}', [MainController::class, 'getTrackWith']);

    Route::put('/playlist/{playlistID}/moders/{userID}', [MainController::class, 'createModer']);

    Route::put('/playlist/{playlistID}/addTrack/{trackID}', [MainController::class, 'addTrackToPlaylist']);

    Route::get('/tracks/isFavorite/{trackID}', [MainController::class, 'isFavoriteTrack']);

    Route::get('/tracks/get', [MainController::class, 'getAllTracks']);

    Route::put('/favorite/tracks/{trackID}', [MainController::class, 'addTrackToFavorite']);
    // Route::put('/playlist/{playlistID}/track/{trackID}', [MainController::class, 'addTrackToPlaylist']);


    Route::put('/user/lastUse/{playlistID}/{trackID}/{volumeLevel}', [MainController::class, 'addUserLastUseInfo']);
    Route::get('/user/lastInfo', [MainController::class, 'checkLastInfo']);

    Route::post('/sync-track', [MainController::class, 'syncTrack']);

    Route::post('/update-queue', [MainController::class, 'syncQueue']);

});