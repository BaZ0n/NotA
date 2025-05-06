@extends('main/mainLayout')

@section('main_content')
    <div class="pageContainer">
        <div class="container">
            <div class="userInfContainer">
                <div class="imageContainer">
                    <img class="profileImage" src="{{asset('images/icons/profileIcon.svg')}}">
                </div>
                <a href="/logout"><h6 class="text-center" style="color: white">Выйти из аккаунта</h6></a>
            </div>
            <div class="trackContainer">
                <h4 class="text-center">Избранные треки</h4>
                <div class="playlistTracks">
                    <a href="#" class="trackLink">
                        <div class="track d-flex py-2 px-1" style="align-items: center;">
                            <h5 style="margin-right: 15px;">1.</h5>
                            <div class="trackInfo">
                                <h6 class="trackArtist">Испольнитель</h6>
                                <h4 class="trackName">Название</h4>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="trackLink">
                        <div class="track d-flex py-2 px-1" style="align-items: center;">
                            <h5 style="margin-right: 15px;">1.</h5>
                            <div class="trackInfo">
                                <h6 class="trackArtist">Испольнитель</h6>
                                <h4 class="trackName">Название</h4>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="trackLink">
                        <div class="track d-flex py-2 px-1" style="align-items: center;">
                            <h5 style="margin-right: 15px;">1.</h5>
                            <div class="trackInfo">
                                <h6 class="trackArtist">Испольнитель</h6>
                                <h4 class="trackName">Название</h4>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="trackLink">
                        <div class="track d-flex py-2 px-1" style="align-items: center;">
                            <h5 style="margin-right: 15px;">1.</h5>
                            <div class="trackInfo">
                                <h6 class="trackArtist">Испольнитель</h6>
                                <h4 class="trackName">Название</h4>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="trackLink">
                        <div class="track d-flex py-2 px-1" style="align-items: center;">
                            <h5 style="margin-right: 15px;">1.</h5>
                            <div class="trackInfo">
                                <h6 class="trackArtist">Испольнитель</h6>
                                <h4 class="trackName">Название</h4>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="line"></div>
            <h4 class="text-center">Избранные плейлисты</h4>
            <div class="playlistContainer d-flex">
                <div class="playlistUser d-flex" style="justify-content: center">
                    <a href="#" class="playlistLink">
                        <div class="playlistCollection py-2 px-1">
                            <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                            <h5 class="text-center mt-2">Название</h5>
                        </div>
                    </a>
                </div>
                <div class="playlistUser d-flex" style="justify-content: center">
                    <a href="#" class="playlistLink">
                        <div class="playlistCollection py-2 px-1">
                            <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                            <h5 class="text-center mt-2">Название</h5>
                        </div>
                    </a>
                </div>
                <div class="playlistUser d-flex" style="justify-content: center">
                    <a href="#" class="playlistLink">
                        <div class="playlistCollection py-2 px-1">
                            <img class="playlistImage" src="{{asset('images/playlistImages/playlistImgTest.png')}}">
                            <h5 class="text-center mt-2">Название</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- <a href="/logout"><h6 class="text-center" style="color: white">Выйти из аккаунта</h6></a> --}}