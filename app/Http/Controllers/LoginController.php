<?php

namespace App\Http\Controllers;

use Request;


class LoginController extends Controller
{
    public function welcome() {
        return view('loginSignIn/welcome');
    }

    public function registrationCode(){
        return view('loginSignIn/registrationCode');
    }

    public function loginCheck() {
        return redirect()->action([MainController::class, 'mainPage']);
    }

    public function registrationProfile() {
        return view('loginSignIn/registrationProfile');
    }
}
