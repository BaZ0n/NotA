<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\favorite_playlist;
use App\Models\playlist_moders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


class MainController extends Controller
{

    // Основная страница
    public function mainPage() {
        $playlists = DB::table('playlist')
        ->where('playlist.playlistName','!=', "Моё любимое")
        ->join('users','playlist.userID','=','users.id')
        ->select('playlist.*', 'users.name as userName')
        ->get();
        
        $user_playlists = DB::table('playlist')
            ->where('playlist.userID', '=', Auth::user()->id)
            ->select('playlist.*')
            ->get();

        if ($user_playlists->isEmpty()) {
            $playlist = playlist::create([
                'playlistName' => "Моё любимое",
                'userID' => Auth::id(),
                'photo_path' => 'templates/favorite_playlist.svg'
            ]);

            $playlist_moders = playlist_moders::create([
                'playlistID' => $playlist->id,
                'userID' => Auth::id()
            ]);

            $favorite_playlist = favorite_playlist::create([
                'playlistID' => $playlist->id,
                'userID' => Auth::id()
            ]);
        }

        $artists = DB::table('artist')
            ->select('artist.*')
            ->get();

        return Inertia::render('mainPage', [
            'playlists' => $playlists,
            'user_playlists' => $user_playlists,
            'artists' => $artists,
            'authUser' => Auth::user()
        ]);
    }

    public function userPage() {
        return view('main/userPage');
    }

    // Страница коллекции пользователя
    public function collectionPage() {
        $favorite_playlists = DB::table("favorite_playlist")
        ->where("favorite_playlist.userID", "=", Auth::id())
        ->join('playlist', 'playlist.id', '=', 'favorite_playlist.playlistID')
        ->select('playlist.*')
        ->get();

        $playlist_favoriteTracks = DB::table("playlist")
        ->where('playlist.userID', '=', Auth::id())
        ->first();

        return Inertia::render('collectionPage', [
            'playlistsFavorite' => $favorite_playlists,
            'playlistFavoriteTracks' => $playlist_favoriteTracks
        ]);
    }
    
    // Страница артиста
    public function artist($artistID)
    {
        $artist = DB::table('artist')->find($artistID);
        return Inertia::render('artistPage', [
            'artist' => $artist,
        ]);
    }

}

// Запрос на треки: с артистом
// $favorite_tracks = DB::table('playlist_tracks')
// ->where('playlist_tracks.playlistID', '=', $playlist_favoriteTracks->id)
// ->join('track', 'playlist_tracks.trackID', '=', 'track.id')
// ->join('track_authors', 'playlist_tracks.trackID', '=', 'track_authors.trackID')
// ->join('artist', 'artist.id', '=', 'track_authors.artistID')
// ->select('track.*', 'artist.artistName as artistName', 'artist.id as artistID')
// ->get();