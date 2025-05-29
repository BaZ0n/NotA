<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


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

    public function getPlaylistTracks($playlistID) {
        $tracks_playlist = DB::table('playlist_tracks')
        ->where('playlist_tracks.playlistID', '=', $playlistID)
        ->join('users', 'users.id', '=', 'playlist_tracks.userID')
        ->join('track', 'playlist_tracks.trackID', '=', 'track.id')
        ->join('track_authors', 'playlist_tracks.trackID', '=', 'track_authors.trackID')
        ->join('artist', 'artist.id', '=', 'track_authors.artistID')
        ->join('album', 'album.id', '=', 'track.albumID')
        ->select('track.*', 'artist.artistName as artistName', 'artist.id as artistID', 'album.id as albumID', 'album.albumName as albumName', 'album.photo_path as albumCover', 'users.id as userID', 'users.photo_path as userPhoto', 'users.name as userName')
        ->get();


        return response()->json($tracks_playlist);
        //return view('main/playlistPage', ['playlist' => $playlistID, 'tracks' => $tracks_playlist]);
    }
}
