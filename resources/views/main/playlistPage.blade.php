@extends('main/mainLayout')

@section('main_content')
    <div class="container d-flex mt-5 mb-2">
        <div class="playlistInfo mx-3"> 
            <img src="{{asset('images/playlistImages/playlistImgTest.png')}}">
            <h3 class="text-center my-2 editable" id="playlist_title" data-original="{{ $playlist->playlistName}}" 
                data-url="{{ route('playlistName.update', $playlist->id )}}">
                {{$playlist->playlistName}}
            </h3>
            <h4 class="authorName text-center mb-3" style="color: var(--placeholder)">{{$playlist->userName}}</h4>
            <div class="buttonsCont mt-3">
                <button class="toolButton" id="playButton">
                    <img class="svg" src="{{ asset('images/icons/playIcon.svg') }}">
                </button>
                <button class="toolButton" id="likeButton">
                    <img class="svg" src="{{ asset('images/icons/likeIcon.svg') }}">
                </button>
            </div>
            <button class="button-primary my-3 px-3 py-3" style="background-color: var(--placeholder)" id="uploadTrackBTN">
                <h5 class="text my-0" style="color: white">Загрузить трек</h5>
            </button>
        </div>
    
        <div class="playlistTracks mx-3 py-3 px-3">
            @foreach ($tracks as $index => $track)
                <a href="#" class="trackLink">
                    <div class="track d-flex py-2 px-3" style="align-items: center;">
                        <h4 class="track-number me-3">{{ $index + 1 }}.</h4>
                        <div class="trackInfo">
                            <h5 class="trackArtist">Испольнитель</h5>
                            <h4 class="trackName">{{$track->trackName}}</h4>
                        </div>
                        <h5 class="trackDuration">{{ floor($track->duration / 60) }}:{{ sprintf("%02d", floor($track->duration % 60)) }} </h5>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="uploadBackground container-fluid d-none" id="uploadCont">
        <div class="uploadTrackContainer">
            <h4 class="text-center mb-5" style="color: white">Загрузка трека</h4>
            <form id="uploadTrackForm" method="post" enctype="multipart/form-data" action="{{route('playlist.upload-audio', ['playlistID' => $playlist->id]) }}">
                @csrf
                <div class="form-group ">
                    <input type="trackName" id="trackName" name="trackName" placeholder="Название" class="form-control fs-5">
                </div>
                <div class="form-group">
                    <input type="trackArtist" id="trackArtist" name="trackArtist" placeholder="Исполнитель" class="form-control fs-5">
                </div>
                <div class="form-group">
                    <input type="trackAlbum" id="trackAlbum" name="trackAlbum" placeholder="Альбом" class="form-control fs-5">
                </div>
                <label class="input-file form-group">
                    <input type="file" name="file" accept="audio/*">
                    <span class="input-file-btn px-3 py-2">Выберите файл</span>          
	   	            <span class="input-file-text">Не более 10МБ</span>
                </label>
                <div class="form-group my-5 d-flex" style="align-items: center; justify-content: center;">
                    <button class="button-primary py-2 px-4" type="submit">
                        <h3>Загрузить</h3>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset("js/playlistPage.js")}}"></script>
@endsection