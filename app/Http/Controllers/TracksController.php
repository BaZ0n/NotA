<?php

namespace App\Http\Controllers;

use App\Models\user;
use App\Models\Playlist;
use App\Models\track;
use App\Models\artist;
use App\Models\album;
use App\Models\playlist_tracks;
use App\Models\track_authors;
use App\Models\user_last_use;
use App\Models\favorite_artist;
use App\Models\favorite_playlist;
use App\Models\playlist_moders;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\select;
use function PHPUnit\Framework\isNull;

class TracksController extends Controller
{
    public function addTrackToFavorite($trackID) {
        // $favorite_tracks = DB::table('playlist')
        // ->where("playlist.userID", '=', Auth::user()->id)
        // ->where("playlist.playlistName", '=', "Моё любимое")
        // ->join("playlist_tracks", 'playlist_tracks.playlistID', '=', 'playlist.id')
        // ->select('playlist.*')
        // ->get();

        $favorite_tracks_playlist = DB::table('playlist')
        ->where("playlist.userID", '=', Auth::user()->id)
        ->where("playlist.playlistName", '=', "Моё любимое")
        ->select('playlist.*')
        ->first();

        $favorite_tracks = DB::table('playlist_tracks')
        ->where('playlist_tracks.playlistID', '=', $favorite_tracks_playlist->id)
        ->where('playlist_tracks.trackID', '=', $trackID)
        ->select('*')
        ->get();

        if ($favorite_tracks->isEmpty()) {
            $addTrack = playlist_tracks::create([
                'playlistID' => $favorite_tracks_playlist->id,
                'trackID' => $trackID,
                'userID' => Auth::user()->id
            ]); 
        } else {
            echo "Трек не найден.";
        }
    }

    public function getAllTracks() {
        $tracks = DB::table('track')
        ->join('track_authors', 'track_authors.trackID', '=', 'track.id')
        ->join('artist', 'artist.id', '=', 'track_authors.artistID')
        ->join('album', 'album.id', '=', 'track.albumID')
        ->select('track.*', 'artist.artistName as artistName', 'artist.id as artistID', 'album.id as albumID', 'album.photo_path as albumCover', 'album.albumName as albumName')
        ->get();

        return response()->json( [
            'tracks' => $tracks  
        ]);
    }

    public function getTrackWith($searchQueryTrack) {
        $tracks = DB::table('track')
        ->where('track.trackName', 'like', '%'.$searchQueryTrack.'%')
        ->join('track_authors', 'track_authors.trackID', '=', 'track.id')
        ->join('artist', 'artist.id', '=', 'track_authors.artistID')
        ->join('album', 'album.id', '=', 'track.albumID')
        ->select('track.*', 'artist.artistName as artistName', 'artist.id as artistID', 'album.id as albumID', 'album.photo_path as albumCover', 'album.albumName as albumName')
        ->get();

        return response()->json( [
            'tracks' => $tracks  
        ]);
    }

    public function isFavoriteTrack($trackID) {
        $favorite_tracks_playlist = DB::table('playlist')
        ->where("playlist.userID", '=', Auth::user()->id)
        ->where("playlist.playlistName", '=', "Моё любимое")
        ->select('playlist.*')
        ->first();

        $favorite_tracks = DB::table('playlist_tracks')
        ->where('playlist_tracks.playlistID', '=', $favorite_tracks_playlist->id)
        ->where('playlist_tracks.trackID', '=', $trackID)
        ->select('*')
        ->get();

        if (!$favorite_tracks->isEmpty()) {
            return response()->json([
                "isTrackFavorite" => true
            ]);
        }
        else {
            return response()->json([
                "isTrackFavorite" => false
            ]);
        }
    }

}
