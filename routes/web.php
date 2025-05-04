<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


Route::get('/', [LoginController::class, 'welcome']);
Route::get('/login', [LoginController::class, 'welcome']);

Route::get('/registrationProfile', [LoginController::class, 'registrationProfile']);
Route::post('/registrationProfile/confirm', [LoginController::class, 'registrationConfirm']);

Route::post("/login/check", [LoginController::class, 'loginCheck']);

Route::get("/mainPage", [MainController::class, 'mainPage']);

Route::get("/playlistPage", [MainController::class, 'playlistPage']);

Route::get("/userPage", [MainController::class,'userPage']);

Route::get("/collectionPage", [MainController::class, 'collectionPage']);

Route::get('/verify-email', function () {
    return view('loginSignIn/verify-email');
})->middleware('auth')->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/mainPage');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Ссылка для подтверждения отправлена!');
})->middleware(['auth', 'throttle:3,1'])->name('verification.send');

Route::get("/logout", [LoginController::class, 'logout']);