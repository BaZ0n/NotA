@extends('main/mainLayout')

@section('main_content')
    <div class="container d-flex mt-5 mb-2">
        <div class="playlistInfo mx-3"> 
            <img src="{{asset('images/playlistImages/playlistImgTest.png')}}">
            <h3 class="text-center my-2">{{ $playlist->playlistName}}</h3>
            <h4 class="authorName text-center mb-3">{{$playlist->userName}}</h4>

            <div class="buttonsCont">
                <button class="toolButton" id="playButton">
                    <img class="svg" src="{{ asset('images/icons/playIcon.svg') }}">
                </button>
                <button class="toolButton" id="likeButton">
                    <img class="svg" src="{{ asset('images/icons/likeIcon.svg') }}">
                </button>
                <button class="toolButton" id="editButton">
                    <img class="svg" src="{{ asset('images/icons/editIcon.svg') }}">
                </button>
            </div>
            <button class="button-primary my-3 px-3 py-3" style="background-color: var(--placeholder)">
                <h5 class="text my-0" style="color: white">Загрузить трек</h5>
            </button>
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