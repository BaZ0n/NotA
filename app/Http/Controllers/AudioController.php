<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AudioController extends Controller
{
    public function playAudio(Request $request) {
        // $track = DB::table('track')
        // ->find($request->trackID);
        $track = DB::table('track')
        ->where('track.id', '=', $request->trackID)
        ->join('album', 'track.albumID', '=', 'album.id')
        ->select('track.*', 'album.albumName as albumName', 'album.id as albumId', 'album.photo_path as albumPhoto')
        ->first();

        $track_authors = DB::table('track_authors')
        ->where('track_authors.trackID', '=', $request->trackID)
        ->join('artist', 'track_authors.artistID', '=', 'artist.id')
        ->select('track_authors.*', 'artist.artistName', 'artist.id as artistID', 'artist.music_path as music_path')
        ->first();

        return response()->json([
            'track' => $track,
            'author' => $track_authors,
        ]);
    }

    public function playPlaylist(Request $request) {

        $playlistTrack = DB::table('playlist_tracks')
        ->where('playlist_tracks.playlistID', '=', $request->playlistID)
        ->select('playlist_tracks.trackID as trackID')
        ->first();

        $track = DB::table('track')
        ->where('track.id', '=', $playlistTrack->trackID)
        ->join('album', 'track.albumID', '=', 'album.id')
        ->select('track.*', 'album.albumName as albumName', 'album.id as albumId', 'album.photo_path as albumPhoto')
        ->first();

        $track_authors = DB::table('track_authors')
        ->where('track_authors.trackID', '=', $playlistTrack->trackID)
        ->join('artist', 'track_authors.artistID', '=', 'artist.id')
        ->select('track_authors.*', 'artist.artistName', 'artist.id as artistID', 'artist.music_path as music_path')
        ->first();

        return response()->json([
            'track' => $track,
            'author' => $track_authors,
        ]);
    }

    
}




