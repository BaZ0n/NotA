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
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Events\TrackSynced;
use App\Events\QueueUpdated;

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

    // Создание нового плейлиста
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
        ]);

        $playlistId = DB::transaction(function () use ($request) {
            $playlist = Playlist::create([
                'playlistName' => $request->input('name', 'Новый плейлист'),
                'userID' => Auth::id(),
                'photo_path' => 'templates/playlistImage.svg', // корректный путь для публичных ресурсов
            ]);

            Playlist_moders::create([
                'playlistID' => $playlist->id,
                'userID' => Auth::id(),
            ]);

            favorite_playlist::create([
                'userID' => Auth::id(),
                'playlistID' => $playlist->id,
            ]);

            return $playlist;
        });

        return response()->json([
            'success' => true,
            'playlistId' => $playlistId,
            'redirect' => route('playlist.show', $playlistId->id),
        ]);
    }

    // Функция для открытия страницы плейлиста
    public function show_playlist($playlistID)
    {
        $playlist_inf = DB::table('playlist')
            ->where('playlist.id', $playlistID)
            ->join('users', 'playlist.userID', '=', 'users.id')
            ->select('playlist.*', 'users.name as userName')
            ->first();

        return Inertia::render('playlistPage', [
            'playlist' => $playlist_inf,
        ]);
    }

    public function playlistTracks($playlistID) {
        $playlist_tracks = DB::table('playlist_tracks') 
            ->where('playlist_tracks.playlistID', '=', $playlistID)
            ->join('track', 'track.id', '=', 'playlist_tracks.trackID') 
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

    public function trackUpload(Request $request, $playlistID) {
        $request->validate([
            'file' => 'required|file|mimes:mp3,wav,aac,ogg|max:51200'
        ]);

        $file = $request->file('file');
        $file_path = $file->getRealPath();

        $getID3 = new \getID3();
        $fileInfo = $getID3->analyze($file_path);

        if (isNull($request->input('trackName'))) {
            $trackName = $fileInfo['tags']['id3v2']['title'][0] ?? $request->input('trackName');
        }
        if (isNull($request->input('albumName'))) {
            $albumName = $fileInfo['tags']['id3v2']['album'][0] ?? $request->input('trackAlbum');
        }

        // Обработка артиста(ов) из запроса или тегов файла
        $trackArtistInput = $request->input('trackArtist');

        if (is_null($trackArtistInput)) {
            $artistsFromTags = $fileInfo['tags']['id3v2']['artist'][0] ?? null;
            
            // Нормализация строки с исполнителями (разделение по / или ,)
            $artists = $artistsFromTags 
                ? array_map('trim', preg_split('/[\/,]/', $artistsFromTags))
                : ['Неизвестный Исполнитель'];
        } else {
            $artists = [$trackArtistInput]; // Если артист указан вручную
        }

        // Обработка каждого исполнителя
        $artistIds = [];
        foreach ($artists as $artistName) {
            if (empty($artistName)) continue;
            
            // Поиск или создание артиста (оптимизированная версия)
            $artist = Artist::firstOrCreate(
                ['artistName' => Str::lower($artistName)],
                [
                    'artistName' => $artistName,
                    'is_confirmed' => false,
                    'photo_path' => 'resources/images/templates/userImage.svg'
                ]
            );
            
            // Создание директории, если это новый артист
            if (is_null($artist->music_path)) {
                $directoryName = Str::random(20) . $artist->id;
                $artistPath = "audio/{$directoryName}";
                Storage::disk('public')->makeDirectory($artistPath);
                
                $artist->update(['music_path' => $artistPath]);
            }
            
            $artistIds[] = $artist->id;
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
                'photo_path' => null,
                'artistID' => $artist->id
            ]);
            $album = DB::table('album')
                ->where(Str::lower('album.albumName'), '=', Str::lower($new_album->albumName))
                ->select('*')
                ->first();

            if (is_null($album->photo_path)) {
                $directoryName = Str::random(20) . $album->id;
                $albumPath = "images/{$directoryName}";
                Storage::disk('public')->makeDirectory($albumPath);
                if (isset($fileInfo['comments']['picture'][0])) {
                    $cover = $fileInfo['comments']['picture'][0];
                    $imageData = $cover['data'];  // Бинарные данные изображения
                    
                    // Сохранить в файл
                    // file_put_contents("{$album->photo_path}/{$album->albumName}.jpg", $imageData);
                    // $album_photoPath = $file->storeAs($albumPath, $album_name, 'public');

                    $album_name = Str::random(20) . '.jpg';
                    // $album_photoPath = Storage::disk('public')
                    // ->putFileAs($albumPath, $imageData, $album_name);

                    Storage::disk('public')->put(
                        $albumPath . '/' . $album_name,
                        $imageData
                    );

                    $album_photoPath = "{$albumPath}/{$album_name}";

                    // $album->update(['photo_path' => $album_photoPath]);
                    $album_update = DB::table('album')
                    ->where('album.id', '=', $album->id)
                    ->update(['album.photo_path' => $album_photoPath]);
                }
            }
            else {
                // $album->update(['photo_path' => 'resources/images/templates/playlistImage.svg']);
                $album_update = DB::table('album')
                    ->where('album.id', '=', $album->id)
                    ->update(['album.photo_path' => 'resources/images/templates/playlistImage.svg']);
            }

            
            //'resources/images/templates/playlistImage.svg'
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

            $trackAuthorsData = [];
            foreach ($artistIds as $artistId) {
                $trackAuthorsData[] = [
                    'trackID' => $track->id,
                    'artistID' => $artistId,
                    'created_at' => now(),  // Добавляем временные метки
                    'updated_at' => now()
                ];
            }
            // Массовая вставка
            DB::table('track_authors')->insert($trackAuthorsData);
        }
        
        $playlist_tracks = DB::table('playlist_tracks')
        ->where('playlistID', '=', $playlistID)
        ->where('trackID', '=', $track->id)
        ->first();

        if (empty($playlist_tracks)) {
            $playlist_tracks = Playlist_tracks::create([
                'playlistID' => $playlistID,
                'trackID' => $track->id,
                'userID' => Auth::user()->id
            ]);
        }
        else {
            return back()->with('success', 'Трек уже есть в плейлисте!');
        }

        $addedTrack = DB::table('track')
        ->where('track.id', '=', $track->id)
        ->join('album', 'album.id', '=', 'track.albumID')
        ->select('track.id as id', 'album.photo_path as photo_path')
        ->first();

        $playlist = DB::table('playlist')
        ->where('playlist.id', '=', $playlistID)
        ->update(['playlist.photo_path' => $addedTrack->photo_path ]);

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

            $newFavorite = Favorite_playlist::create([
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

    // Страница артиста
    public function artist($artistID)
    {
        $artist = DB::table('artist')->find($artistID);
        return Inertia::render('artistPage', [
            'artist' => $artist,
        ]);
    }

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
                'trackID' => $trackID,
                'userID' => Auth::id()
            ]);

            $track = DB::table('track')
            ->where('track.id', '=', $trackID)
            ->join('album', 'album.id', '=', 'track.albumID')
            ->select('track.id as id', 'album.photo_path as photo_path')
            ->first();

            $playlist = DB::table('playlist')
            ->where('playlist.id', '=', $playlistID)
            ->update(['playlist.photo_path' => $track->photo_path ]);

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

    public function getUserPlaylists($userID) {
        $userPlaylists = DB::table('playlist_moders')
        ->where('playlist_moders.userID', '=', $userID)
        ->join('playlist', 'playlist.id', '=', 'playlist_moders.id')
        ->select('playlist.id as playlistID', 'playlist.playlistName as playlistName', 'playlist.playlistImage as playlistImage')
        ->get();

        return response()->json([
            "playlists" => $userPlaylists
        ]);
    }

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

    public function syncTrack(Request $request)
    {

        broadcast(new TrackSynced(
            $request->trackId,
            $request->action,
            $request->time
        ))->toOthers();


        return response()->json(['status' => 'ok']);
    }

    public function syncQueue(Request $request) {
        broadcast(new QueueUpdated(
            $request->action,
            $request->track,
            $request->index,
            $request->queue
        ))->toOthers();

        return response()->json(['status' => 'ok']);
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