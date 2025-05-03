<?php

namespace App\Http\Controllers;

use Request;


class MainController extends Controller
{
    public function mainPage() {
        return view('main/mainPage');
    }

    public function playlistPage() {
        return view('main/playlistPage');
    }

    public function userPage() {
        return view('main/userPage');
    }

    public function collectionPage() {
        return view("main/collectionPage");
    }
}
