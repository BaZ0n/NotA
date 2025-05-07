<?php

namespace App\Http\Controllers;

use App\Models\user;
use App\Models\Playlist;
use App\Models\track;
use App\Models\artist;
use App\Models\album;
use App\Models\playlist_tracks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;


class MainController extends Controller
{

    private function getAudioDuration($file)
    {
        $getID3 = new \getID3;
        $info = $getID3->analyze($file->getPathname());
        return $info['playtime_seconds'] ?? 0;
    }

    public function mainPage() {
        $playlists = DB::table('playlist')
        ->join('users','playlist.userID','=','users.id')
        ->select('playlist.*', 'users.name as userName')
        ->get();
        $user_playlists = DB::table('playlist')
        ->where('playlist.userID', '=', Auth::user()->id)
        ->select('playlist.*')
        ->get();
        $artists = DB::table('artist')
        ->select('artist.*')
        ->get();
        return view('main/mainPage', ['playlists' => $playlists, 'user_playlists' => $user_playlists, 'artists' => $artists]);
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
    public function show_playlist(Playlist $playlist)
    {
        $playlist_inf = DB::table('playlist')
        ->where('playlist.id', '=', $playlist->id)
        ->join('users','playlist.userID','=','users.id')
        ->select('playlist.*', 'users.name as userName')
        ->first();

        $tracks_playlist = DB::table('playlist_tracks')
        ->where('playlist_tracks.playlistID', '=', $playlist->id)
        ->join('track', 'playlist_tracks.trackID', '=', 'track.id')
        ->select('track.*')
        ->get();
        return view('main/playlistPage', ['playlist' => $playlist_inf, 'tracks' => $tracks_playlist]);
    }

    public function trackUpload(Request $request) {
        $request->validate([
            'file' => 'required|file|mimes:mp3,wav,aac,ogg|max:10240'
        ]);

        $artist = DB::table('artist')
        ->where(Str::lower('artist.artistName'), '=', Str::lower($request->input('trackArtist')))
        ->select('*')
        ->first();

        if (empty($artist)) {
            $new_artist = Artist::create([
                'artistName' => $request->input('trackArtist'),
                'is_confirmed' => false,
                'music_path' => NULL,
                'photo_path' => NULL
            ]);
            $artist = DB::table('artist')
                ->where(Str::lower('artist.artistName'), '=', Str::lower($new_artist->artistName))
                ->select('*')
                ->first();
            $directoryName = Str::random(20) . $artist->id;
            $path = "public/audio/{$directoryName}";
            Storage::makeDirectory($path);
    
            // Обновляем путь в базе
            DB::table('artist')
                ->where('id', $artist->id)
                ->update(['music_path' => $path]);
        }


        $album = DB::table('album')
        ->where(Str::lower('album.albumName'), '=', Str::lower($request->input('trackAlbum')))
        ->select('*')
        ->first();

        if (empty($album)) {
            $new_album = Album::create([
                'albumName' => $request->input('trackAlbum'),
                'is_confirmed' => false,
                'date_publish' => Carbon::today()->toDateString(),
                'photo_path' => null,
                'artistID' => $artist->id
            ]);
            $album = DB::table('album')
                ->where(Str::lower('album.albumName'), '=', Str::lower($new_album->albumName))
                ->select('*')
                ->first();
        }

        $file = $request->file('file');
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $artistPath = $artist->music_path;
        $track_path = $file->storeAs($artistPath, $filename);
        $track = Track::create([
            'trackName' => $request->input('trackName'),
            'duration' => $this->getAudioDuration($file),
            'is_confirmed' => false,
            'path' => $track_path,
            'albumID' => $album->id
        ]);

        $playlist_tracks = Playlist_tracks::create([
            'playlistID' => $request->playlistID,
            'trackID' => $track->id
        ]);

        return back()->with('success', 'Аудио успешно загружено!');
    }

    public function playlistNameUpdate(Request $request, Playlist $playlist) {
        $validated = $request->validate([
            'playlistName' => 'required|string|max:255|min:2'
        ]);
        
        // 2. Поиск и обновление
        $playlist->update(['playlistName' => $validated['playlistName']]);
        
        // 3. Ответ
        return response()->json([
            'playlistName' => $playlist->playlistName,
            'message' => 'Название обновлено'
        ]);
    }
    
}
