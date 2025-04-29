@extends('main/mainLayout')

@section('main_content')
    <div class="container d-flex mt-5 mb-2">
        <div class="playlistInfo mx-3"> 
            <img src="{{asset('images/playlistImages/playlistImgTest.png')}}">
            <h3 class="text-center my-4">Плейлист йоу</h3>

            <div class="buttonsCont">
                <button class="playButton">
                    <img class="svg" src="{{ asset('images/icons/playIcon.svg') }}">
                </button>
                <button class="likeButton">
                    <img class="svg" src="{{ asset('images/icons/likeIcon.svg') }}">
                </button>
            </div>
        </div>
    
        <div class="playlistTracks mx-5 my-1 py-5 px-4">
            <a href="#" class="trackLink">
                <div class="track d-flex py-2 px-1" style="align-items: center;">
                    <h2 style="margin-right: 15px;">1.</h4>
                    <div class="trackInfo">
                        <h5 class="trackArtist">Испольнитель</h5>
                        <h3 class="trackName">Название</h3>
                        <div class="trackDuration">
                        </div>
                    </div>
                </div>
            </a>
            <a href="#" class="trackLink">
                <div class="track d-flex py-2 px-1" style="align-items: center;">
                    <h2 style="margin-right: 15px;">1.</h4>
                    <div class="trackInfo">
                        <h5 class="trackArtist">Испольнитель</h5>
                        <h3 class="trackName">Название</h3>
                    </div>
                </div>
            </a>
            <a href="#" class="trackLink">
                <div class="track d-flex py-2 px-1" style="align-items: center;">
                    <h2 style="margin-right: 15px;">1.</h4>
                    <div class="trackInfo">
                        <h5 class="trackArtist">Испольнитель</h5>
                        <h3 class="trackName">Название</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection