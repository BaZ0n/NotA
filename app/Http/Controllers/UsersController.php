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

class UsersController extends Controller
{
    public function usersGet() {
        $users = DB::table('users')
        ->where('users.id', '!=', Auth::user()->id)
        ->select('users.name as userName', 'users.id as userID', 'users.photo_path as photoPath')
        ->get();

        return response()->json([
            'users' => $users
        ]);
    }

    public function getUserWith($searchQuery) {

        $users = DB::table('users')
        ->where('users.name', 'like', '%'.$searchQuery.'%')
        ->select('users.name as userName', 'users.id as userID', 'users.photo_path as photoPath')
        ->get();

        return response()->json([
           'users' => $users 
        ]);
    }

    public function addUserLastUseInfo($playlistID, $trackID, $volumeLevel) {
        $userLastUse = DB::table('user_last_use')
        ->where('user_last_use.userId', '=', Auth::user()->id)
        ->select('*')
        ->first();

        if ($userLastUse) {
            $updateLastUse = DB::table('user_last_use')
            ->where('user_last_use.id', '=', $userLastUse->id)
            ->update(['trackID' => $trackID, 'playlistID' => $playlistID, 'volumeLevel' => $volumeLevel]);
            // $userLastUse->update(['trackID' => $trackID], ['playlistID' => $playlistID]);
        }
        else {
            $newUserLastUseInfo = user_last_use::create([
                'userID' => Auth::user()->id,
                'trackID' => $trackID,
                'playlistID' => $playlistID,
                'volumeLevel' => $volumeLevel
            ]);
        }
    }

    public function checkLastInfo() {

        Log::error('Пользователь', [
            'user_id' => Auth::user()->id,
        ]);

        $user_last_info = DB::table('user_last_use')
        ->where('user_last_use.userID', '=', Auth::user()->id)
        ->select('*')
        ->first();

       

        if (!$user_last_info) {
            return response()->json([
                'last_use_info' => $user_last_info
            ]);
        }

        $track = DB::table('track')
        ->where('track.id', '=', $user_last_info->trackID)
        ->join('album', 'track.albumID', '=', 'album.id')
        ->select('track.*', 'album.albumName as albumName', 'album.id as albumId', 'album.photo_path as albumPhoto')
        ->first();

        $track_authors = DB::table('track_authors')
        ->where('track_authors.trackID', '=', $user_last_info->trackID)
        ->join('artist', 'track_authors.artistID', '=', 'artist.id')
        ->select('track_authors.*', 'artist.artistName', 'artist.id as artistID', 'artist.music_path as music_path')
        ->first();

        return response()->json([
            'last_use_info' => $user_last_info,
            'track' => $track,
            'author' => $track_authors
        ]);

    }
}
