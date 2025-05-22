@extends('main/mainLayout')

@section('main_content')

    <div class="container" style="padding:0px">
        <div class="container d-flex p-0" id="playlistTrackCont" style="height: 60vh; width: 100%;">
            <div class="favoriteTracksCont mx-2 my-3 px-1" style="overflow-y: auto;">
                <div class="headCont">
                    <h3>Треки</h3>  
                    <button class="showAllTrackBTN mx-2" id="showTracks"><h6 class="text">Показать все...<h6></button>
                </div>
                
                <div class="playlistTracks mx-3 py-3 px-3" id="playlistTracks" style="width:23vw;" data-playlist="{{ 1 }}"></div>
            </div>
            <div class="playlistContainer mx-2 my-3 px-1" style="overflow-y: auto; overflow-x: none; width: 15vw;">
                <div class="headCont d-flex">
                    <h3>Плейлисты</h3>
                    <button class="showAllPlaylistBTN mx-2" id="showPlaylists"><h6>Все...<h6></button>
                </div>
                
                <ol class="playlistUser d-flex" style="justify-content: center; flex-direction:column; align-items: center; list-style-type: none; padding:0;">
                    <li class="playlistCollection py-2 px-1">
                        <button class="createPlaylistBTN" id="createPlaylistBTN"><h1 style="color: white">+</h1></button>
                        <h6 class="text-center mt-2">Создать плейлист</h6>
                    </li>
                    @foreach ($playlists as $playlist)
                    @if($loop->iteration > 5)
                        @break
                    @endif
                    <li class="playlist-item">
                        <a href="/playlist/{{$playlist->id}}" class="playlistLink" style="margin:0">
                            <div class="playlistCollection py-2 px-1">
                                <img class="playlistImage" src="{{asset('storage/templates/playlistImage.svg')}}">
                                <h5 class="text-center mt-2">{{$playlist->playlistName}}</h5>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ol>
            </div>
        </div>
        <div class="container mx-0" id="artistContainer">
            <h3>Исполнители</h3>
            <div class="artistsCollection mx-3 d-flex" id="artistsCollection" data-user="{{ Auth::user()->id }}"></div>
        </div>
        <div class="container-fluid d-none">
            <div class="headCont d-flex" style="margin-bottom: 5vh">
                <button class="backBTN" id="backBTN"><img class="svg" src="{{asset('images/icons/backIcon.svg')}}"></button>
                <h2 class="text-center mb-2">Любимое</h2>
            </div>
            <div class="allTracksContainer d-none">
                <a href="#" class="trackLink">
                    <div class="track d-flex py-2 px-1" style="align-items: center;">
                        <h4 style="margin-right: 15px;">1.</h4>
                        <div class="trackInfo">
                            <h5 class="trackArtist">Испольнитель</h5>
                            <h4 class="trackName">Название</h3>
                        </div>
                        <h5 class="trackDuration" style="margin-left: auto; margin-right: 1vw;">0:00</h5>
                    </div>
                </a>
            </div>
            <ul class="allPlaylistContainer d-none" style="list-style-type: none;">
                @foreach ($playlists as $playlist)
                    <li class="playlist-item">
                        <a href="/playlist/{{$playlist->id}}" class="playlistLink" style="margin:0">
                            <div class="playlistCollection py-2 px-1">
                                <img class="playlistImage" src="{{asset('storage/templates/playlistImage.svg')}}" style="width:150px; height: 150px;">
                                <h5 class="text-center mt-2">{{$playlist->playlistName}}</h5>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <script src="{{asset("js/playlistCreate.js")}}"></script>
@endsection