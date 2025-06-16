<?php

namespace App\Http\Controllers;

use App\Models\favorite_artist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;
use function PHPUnit\Framework\isNull;

class ArtistController extends Controller
{
    public function artistTracks($artistID) {
        $tracks = DB::table('track_authors')
        ->where('track_authors.artistID', '=', $artistID)
        ->join('track', 'track_authors.trackID', '=', 'track.id')
        ->join('album', 'track.albumID', '=', 'album.id')
        ->select('track.*', 'album.photo_path as albumCover')
        ->get();

        return response()->json($tracks);
    }

    
    public function artistAlbums($artistID) {
        $albums = DB::table('album')
        ->where('album.artistID', '=', $artistID)
        ->select('album.*')
        ->get();

        return response()->json($albums);
    }

    public function favoriteArtist($userID) {
        $favorite_artist = DB::table('favorite_artist')
        ->where('favorite_artist', 'favorite_artist.userID', '=', $userID)
        ->join('artist', 'favorite_artist.artistID', '=', 'artist.id')
        ->get();
        return response()->json($favorite_artist);
    }

    public function addToFavoriteArtist(Request $request) {
        $userFavoriteArtist = DB::table('favorite_artist')
        ->where('favorite_artist', 'favorite_artist.userID', '=', Auth::user()->id)
        ->where('favorite_artist', 'favorite_artist.artistID', '=', $request->artistID)
        ->select('*')
        ->get();

        if (!empty($userFavoriteArtist)) {
            $newFavoriteArtist = Favorite_artist::create([
                'userID' => Auth::user()->id,
                'artistID' => $request->artistID
            ]);
        }
    }
}
