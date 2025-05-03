@extends('main/mainLayout')

@section('main_content')

    <div class="container" style="padding:0px">
        <div class="container d-flex p-0" id="playlistTrackCont" style="height: 60vh; width: 100%;">
            <div class="favoriteTracksCont mx-2" style="overflow-y: auto;">
                <div class="headCont d-flex">
                    <h3>Треки</h3>  
                    <button class="showAllTrackBTN mx-2" id="showTracks"><h6>Показать все...<h6></button>
                </div>
                
                <a href="#" class="trackLink">
                    <div class="track d-flex py-2 px-1" style="align-items: center;">
                        <h4 style="margin-right: 15px;">1.</h4>
                        <div class="trackInfo">
                            <h5 class="trackArtist">Испольнитель</h5>
                            <h4 class="trackName">Название</h3>
                        </div>
                        <h5 class="trackDuration">0:00</h5>
                    </div>
                </a>
                <a href="#" class="trackLink">
                    <div class="track d-flex py-2 px-1" style="align-items: center;">
                        <h4 style="margin-right: 15px;">1.</h4>
                        <div class="trackInfo">
                            <h5 class="trackArtist">Испольнитель</h5>
                            <h4 class="trackName">Название</h3>
                        </div>
                        <h5 class="trackDuration">0:00</h5>
                    </div>
                </a>
                <a href="#" class="trackLink">
                    <div class="track d-flex py-2 px-1" style="align-items: center;">
                        <h4 style="margin-right: 15px;">1.</h4>
                        <div class="trackInfo">
                            <h5 class="trackArtist">Испольнитель</h5>
                            <h4 class="trackName">Название</h3>
                        </div>
                        <h5 class="trackDuration">0:00</h5>
                    </div>
                </a>
                <a href="#" class="trackLink">
                    <div class="track d-flex py-2 px-1" style="align-items: center;">
                        <h4 style="margin-right: 15px;">1.</h4>
                        <div class="trackInfo">
                            <h5 class="trackArtist">Испольнитель</h5>
                            <h4 class="trackName">Название</h3>
                        </div>
                        <h5 class="trackDuration">0:00</h5>
                    </div>
                </a>
                <a href="#" class="trackLink">
                    <div class="track d-flex py-2 px-1" style="align-items: center;">
                        <h4 style="margin-right: 15px;">1.</h4>
                        <div class="trackInfo">
                            <h5 class="trackArtist">Испольнитель</h5>
                            <h4 class="trackName">Название</h3>
                        </div>
                        <h5 class="trackDuration">0:00</h5>
                    </div>
                </a>
            </div>
            <div class="playlistContainer mx-2" style="overflow-y: auto; overflow-x: none; width: 20vw;">
                <div class="headCont d-flex">
                    <h3>Плейлисты</h3>
                    <button class="showAllPlaylistBTN mx-2" id="showPlaylists"><h6>Показать все...<h6></button>
                </div>
                
                <div class="playlistUser d-flex" style="justify-content: center; flex-direction:column;">
                    <a href="#" class="playlistLink">
                        <div class="playlistCollection py-2 px-1">
                            <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                            <h5 class="text-center mt-2">Название</h5>
                        </div>
                    </a>
                    <a href="#" class="playlistLink">
                        <div class="playlistCollection py-2 px-1">
                            <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                            <h5 class="text-center mt-2">Название</h5>
                        </div>
                    </a>
                    <a href="#" class="playlistLink">
                        <div class="playlistCollection py-2 px-1">
                            <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                            <h5 class="text-center mt-2">Название</h5>
                        </div>
                    </a>
                    <a href="#" class="playlistLink">
                        <div class="playlistCollection py-2 px-1">
                            <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                            <h5 class="text-center mt-2">Название</h5>
                        </div>
                    </a>
                    <a href="#" class="playlistLink">
                        <div class="playlistCollection py-2 px-1">
                            <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                            <h5 class="text-center mt-2">Название</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="container mx-0" id="artistContainer">
            <h3>Исполнители</h3>
            <div class="artistsCollection mx-3 d-flex">
                <a href="#" class="artistLink">
                    <div class="artist px-2 py-2">
                        <img src="{{asset('images/artistsImages/4k.jpg')}}">
                        <h4 class="text-center">4К</h4>
                    </div>
                </a>
                <a href="#" class="artistLink">
                    <div class="artist px-2 py-2">
                        <img src="{{asset('images/artistsImages/4k.jpg')}}">
                        <h4 class="text-center">4К</h4>
                    </div>
                </a>
                <a href="#" class="artistLink">
                    <div class="artist px-2 py-2">
                        <img src="{{asset('images/artistsImages/4k.jpg')}}">
                        <h4 class="text-center">4К</h4>
                    </div>
                </a>
                <a href="#" class="artistLink">
                    <div class="artist px-2 py-2">
                        <img src="{{asset('images/artistsImages/4k.jpg')}}">
                        <h4 class="text-center">4К</h4>
                    </div>
                </a>
            </div>
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
            <div class="allPlaylistContainer d-none">
                <a href="#" class="playlistLink">
                    <div class="playlistCollection py-2 px-1">
                        <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                        <h5 class="text-center mt-2">Название</h5>
                    </div>
                </a>
                <a href="#" class="playlistLink">
                    <div class="playlistCollection py-2 px-1">
                        <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                        <h5 class="text-center mt-2">Название</h5>
                    </div>
                </a>
                <a href="#" class="playlistLink">
                    <div class="playlistCollection py-2 px-1">
                        <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                        <h5 class="text-center mt-2">Название</h5>
                    </div>
                </a>
                <a href="#" class="playlistLink">
                    <div class="playlistCollection py-2 px-1">
                        <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                        <h5 class="text-center mt-2">Название</h5>
                    </div>
                </a>
                <a href="#" class="playlistLink">
                    <div class="playlistCollection py-2 px-1">
                        <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                        <h5 class="text-center mt-2">Название</h5>
                    </div>
                </a>
                <a href="#" class="playlistLink">
                    <div class="playlistCollection py-2 px-1">
                        <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                        <h5 class="text-center mt-2">Название</h5>
                    </div>
                </a>
                <a href="#" class="playlistLink">
                    <div class="playlistCollection py-2 px-1">
                        <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                        <h5 class="text-center mt-2">Название</h5>
                    </div>
                </a>
            </div>

        </div>
    </div>
@endsection