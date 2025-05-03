@extends('main/mainLayout')

@section('main_content')
    <div class="container d-flex mt-5 mb-2">
        <div class="playlistInfo mx-3"> 
            <img src="{{asset('images/playlistImages/playlistImgTest.png')}}">
            <h3 class="text-center my-2">Плейлист йоу</h3>
            <h4 class="authorName text-center">Автор</h4>

            <div class="buttonsCont">
                <button class="playButton">
                    <img class="svg" src="{{ asset('images/icons/playIcon.svg') }}">
                </button>
                <button class="likeButton">
                    <img class="svg" src="{{ asset('images/icons/likeIcon.svg') }}">
                </button>
            </div>
        </div>
    
        <div class="playlistTracks mx-5 py-5 px-5">
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
    </div>
@endsection