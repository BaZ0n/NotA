<?php

namespace App\Http\Controllers;

use App\Models\user;
use App\Models\Playlist;
use App\Models\track;
use App\Models\artist;
use App\Models\album;
use App\Models\playlist_tracks;
use App\Models\track_authors;
use App\Models\favorite_artist;
use App\Models\favorite_playlist;
use App\Models\playlist_moders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

use function Laravel\Prompts\select;
use function PHPUnit\Framework\isNull;

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

    public function artistPage(Artist $artist) {
        $artist = DB::table('artist')->find($artist->id);
        return view("main/artistPage", ['artist'=>$artist]);
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
            'photo_path' => 'resources/images/templates/playlistImage.svg'
        ]);

        $playlist_moders = Playlist_moders::create([
            'playlistID' => $playlist->id,
            'userID' => Auth::id()
        ]);

        $favorite_playlist = Favorite_playlist::create([
            'userID' => Auth::id(),
            'playlistID' => $playlist->id
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

        return view('main/playlistPage', ['playlist' => $playlist_inf]);

        // $tracks_playlist = DB::table('playlist_tracks')
        // ->where('playlist_tracks.playlistID', '=', $playlist->id)
        // ->join('track', 'playlist_tracks.trackID', '=', 'track.id')
        // ->select('track.*')
        // ->get();
        // return view('main/playlistPage', ['playlist' => $playlist_inf, 'tracks' => $tracks_playlist]);
    }

    public function playlistTracks($playlistID) {
        $playlist_tracks = DB::table('playlist_tracks') // Убедитесь, что имя таблицы правильное (playlist_tracks или playlist_track)
            ->where('playlist_tracks.playlistID', '=', $playlistID)
            ->join('track', 'track.id', '=', 'playlist_tracks.trackID') // Исправлено имя таблицы и связь
            ->join('album', 'album.id', '=', 'track.albumID')
            ->join('artist', 'artist.id', '=', 'album.artistID')
            ->select(
                'track.*', 
                'album.id as album.albumID', 
                'album.albumName as albumName',
                'album.photo_path as albumPhoto',
                'artist.id as artistID',
                'artist.artistName as artistName',
                'artist.photo_path as artistPhoto')
            ->get();

        return response()->json([
            'tracks' => $playlist_tracks // Исправлено 'track' на 'tracks' для согласованности
        ]);
    }

    public function trackUpload(Request $request) {
        $request->validate([
            'file' => 'required|file|mimes:mp3,wav,aac,ogg|max:20480'
        ]);

        $file = $request->file('file');
        $file_path = $file->getRealPath();

        $getID3 = new \getID3();
        $fileInfo = $getID3->analyze($file_path);
        //dd($fileInfo);
        if (isNull($request->input('trackArtist'))) {
            $trackArtist = $fileInfo['tags']['id3v2']['artist'][0] ?? $request->input('trackArtist');
        }
        if (isNull($request->input('trackName'))) {
            $trackName = $fileInfo['tags']['id3v2']['title'][0] ?? $request->input('trackName');
        }
        if (isNull($request->input('albumName'))) {
            $albumName = $fileInfo['tags']['id3v2']['album'][0] ?? $request->input('trackAlbum');
            if (isNull($albumName)) {
                // return back()->with('error', 'Введите название альбома');
                $albumName = $trackName;
            }
        }
        
        $artist = DB::table('artist')
        ->where(Str::lower('artist.artistName'), '=', Str::lower($trackArtist))
        ->select('*')
        ->first();
        

        if (empty($artist)) {
            $new_artist = Artist::create([
                'artistName' => $trackArtist,
                'is_confirmed' => false,
                'music_path' => NULL,
                'photo_path' => 'resources/images/templates/userImage.svg'
            ]);
            $artist = DB::table('artist')
                ->where(Str::lower('artist.artistName'), '=', Str::lower($new_artist->artistName))
                ->select('*')
                ->first();
            $directoryName = Str::random(20) . $artist->id;
            $artistPath = "audio/{$directoryName}";
            Storage::disk('public')->makeDirectory($artistPath);
    
            // Обновляем путь в базе
            DB::table('artist')
                ->where('id', $artist->id)
                ->update(['music_path' => $artistPath]);

            $artist = DB::table('artist')
            ->where(Str::lower('artist.artistName'), '=', Str::lower($trackArtist))
            ->select('*')
            ->first();
        }

        $album = DB::table('album')
        ->where(Str::lower('album.albumName'), '=', Str::lower($albumName))
        ->select('*')
        ->first();

        if (empty($album)) {
            $new_album = Album::create([
                'albumName' => $albumName,
                'is_confirmed' => false,
                'date_publish' => Carbon::today()->toDateString(),
                'photo_path' => 'resources/images/templates/playlistImage.svg',
                'artistID' => $artist->id
            ]);
            $album = DB::table('album')
                ->where(Str::lower('album.albumName'), '=', Str::lower($new_album->albumName))
                ->select('*')
                ->first();
        }

        $track = DB::table('track')
        ->where(Str::lower('track.trackName'), '=', Str::lower($trackName))
        ->where(Str::lower('track.albumID'), '=', Str::lower($album->id))
        ->first();
        

        if (empty($track)) {
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $artistPath = $artist->music_path;
            $track_path = $file->storeAs($artistPath, $filename, 'public');
            $track = Track::create([
                'trackName' => $trackName,
                'duration' => $this->getAudioDuration($file),
                'is_confirmed' => false,
                'path' => $track_path,
                'albumID' => $album->id
            ]);

            $track_authors = track_authors::create([
                'trackID' => $track->id,
                'artistID' => $artist->id
            ]);
        }
        
        $playlist_tracks = DB::table('playlist_tracks')
        ->where('playlistID', '=', $request->playlistID)
        ->where('trackID', '=', $track->id)
        ->first();

        if (empty($playlist_tracks)) {
            $playlist_tracks = Playlist_tracks::create([
                'playlistID' => $request->playlistID,
                'trackID' => $track->id
            ]);
        }
        else {
            return back()->with('success', 'Трек уже есть в плейлисте!');
        }

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

    public function favoriteArtist($userID) {
        $favorite_artist = DB::table('favorite_artist')
        ->where('favorite_artist', 'favorite_artist.userID', '=', $userID)
        ->join('artist', 'favorite_artist.artistID', '=', 'artist.id')
        ->get();

        // $favorite_artist = DB::table('artist')
        // ->select('*')
        // ->get();
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

    public function addToFavoritePlaylist($playlistID) {
        $userFavoritePlaylist = DB::table('favorite_playlist')
        ->where('favorite_playlist.userID', '=', Auth::user()->id)
        ->where('favorite_playlist.playlistID', '=', $playlistID)
        ->first();

        if (empty($userFavoritePlaylist)) {
            $newFavoritePlaylist = Favorite_playlist::create([
                'playlistID' => $playlistID,
                'userID' => Auth::id()
            ]);
        }
    }
    
    public function isFavoriteAndUserModer($playlistID) {
        $isFavorite = DB::table('favorite_playlist')
        ->where('favorite_playlist.userID', '=', Auth::user()->id)
        ->where('favorite_playlist.playlistID', '=', $playlistID)
        ->exists();

        $playlist_moders = DB::table('playlist_moders')
        ->where('playlist_moders.playlistID', '=', $playlistID)
        ->where('playlist_moders.userID', '=', Auth::user()->id)
        ->exists();

        return response()->json([
            'isFavorite' => $isFavorite,
            'isModer' => $playlist_moders    
        ]);
    }

    public function deleteFromFavoritePlaylist($playlistID) {
        $userFavoritePlaylist = DB::table('favorite_playlist')
        ->where('favorite_playlist.userID', '=', Auth::user()->id)
        ->where('favorite_playlist.playlistID', '=', $playlistID)
        ->join('playlist', 'playlist.id', '=', 'favorite_playlist.playlistID')
        ->select('favorite_playlist.*', 'playlist.userID as authorID')
        ->first();

        if (!(empty($userFavoritePlaylist)) && ($userFavoritePlaylist->authorID != $userFavoritePlaylist->userID)) {
            $deleted = DB::table('favorite_playlist')
            ->where('favorite_playlist.userID', '=', Auth::user()->id)
            ->where('favorite_playlist.playlistID', '=', $playlistID)
            ->delete();

            return response()->json([
                'isDeleted' => true
            ]);
        }
        return response()->json([
            'isDeleted' => false
        ]);
    }

    public function usersGet() {
        $users = DB::table('users')
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

    public function createModer($playlistID, $userID) {
        $playlist = DB::table('playlist')
        ->where('playlist.id', '=', $playlistID)
        ->where('playlist.userID', '=', $userID)
        ->select('playlist.*')
        ->first();

        if (empty($playlist)) {
            $newModer = Playlist_moders::create([
                'playlistID' => $playlistID,
                'userID' => $userID
            ]);
            return response()->json([
                'result' => "Модератор успешно добавлен."
            ]);
        }
        else {
            return response()->json([
                'result' => "Создатель плейлиста не может быть исключён из модераторов."
            ]);
        }
    }

    // public function isFavoriteTrack($trackID) {
    //     $
    // }

    public function artistTracks($artistID) {
        $tracks = DB::table('track_authors')
        ->where('track_authors.artistID', '=', $artistID)
        ->join('track', 'track_authors.trackID', '=', 'track.id')
        ->join('album', 'track.albumID', '=', 'album.id')
        ->select('track.*', 'album.*')
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

    public function getTracks() {
        $tracks = DB::table('track')
        ->join('track_authors', 'track_authors.trackID', '=', 'track.id')
        ->join('artist', 'artist.id', '=', 'track_authors.artistID')
        ->select('track.*', 'artist.artistName as artistName')
        ->get();

        return response()->json( [
            'tracks' => $tracks  
        ]);
    }

    public function getTrackWith($searchQueryTrack) {
        $tracks = DB::table('track')
        ->where('track.trackName', 'like', '%'.$searchQueryTrack.'%')
        ->select('track.*')
        ->get();

        return response()->json( [
            'tracks' => $tracks  
        ]);
    }

    public function addTrackToPlaylist($playlistID, $trackID) {
        $playlistTrack = DB::table('playlist_tracks')
        ->where('playlist_tracks.playlistID', '=', $playlistID)
        ->where('playlist_tracks.trackID', '=', $trackID)
        ->exists();

        if (!$playlistTrack) {
            $addTrack = playlist_tracks::create([
                'playlistID' => $playlistID,
                'trackID' => $trackID
            ]);

            return response()->json([
                "result" => "Успех!"
            ]);
        }
        else {
            return response()->json([
                "result" => "Трек уже есть в плейлисте"
            ]);
        }
    }
}
