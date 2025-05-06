<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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

    public function collectionPage(Request $request) {
        $playlists = DB::table("playlist")
        ->where("playlist.userID", "=", Auth::id())
        ->select('*')
        ->get();
        $tracks = DB::table("track")->select("*")->get();
        return view("main/collectionPage", ['playlists'=>$playlists, 'tracks'=>$tracks]);
    }

    // Создание нового плейлиста
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
        ]);

        $playlist = Playlist::create([
            'playlistName' => $request->input('name', 'Новый плейлист'),
            'userID' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'playlistId' => $playlist->id,
        ]);
    }

    // Просмотр плейлиста
    public function show(Playlist $playlist)
    {
        $playlist_inf = DB::table('playlist')
        ->where('playlist.id', '=', $playlist->id)
        ->join('users','playlist.userID','=','users.id')
        ->select('playlist.*', 'users.name as userName')
        ->first();
        return view('main/playlistPage', ['playlist' => $playlist_inf]);
    }
}
