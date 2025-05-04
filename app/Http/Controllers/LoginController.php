<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function welcome() {
        return view('loginSignIn/welcome');
    }

    public function registrationCode(){
        return view('loginSignIn/registrationCode');
    }

    public function loginCheck(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required',]
        ]);
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('mainPage')->with('Успешно', 'Добро пожаловать, ' . Auth::user()->name);
        }

        return back()->withErrors([
            'email' => 'Неправильная почта или пароль.',
        ]);
    }

    public function registrationProfile() {
        return view('loginSignIn/registrationProfile');
    }

    public function registrationConfirm(Request $request) {
        $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed']
        ]);
       //dd($request->only(['name', 'email', 'password']));
        $user = User::create($request->only(['name', 'email', 'password']));
        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function logout() {
        Auth::logout();
        return redirect()->action([LoginController::class, 'welcome']);
    }
}
