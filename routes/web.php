<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'welcome']);

Route::get('/registrationCode', function () {
    return view('loginSignIn/registrationCode');
});

Route::get('/loginSignIn/registrationCode/EmailCheck', [LoginController::class, 'registrationCodeEmail']);

Route::get('/registrationProfile', [LoginController::class, 'registrationProfile']);

Route::post("/login/check", [LoginController::class, 'loginCheck']);

Route::get("/mainPage", [MainController::class, 'mainPage']);

Route::get("/playlistPage", [MainController::class, 'playlistPage']);

Route::get("/userPage", [MainController::class,'userPage']);