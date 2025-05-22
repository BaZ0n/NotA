@extends('main/mainLayout')

@section('main_content')
    <div class="container d-flex mt-5 mb-2">
        <div class="playlistInfo mx-3"> 
            <img class="playlistImage" src="{{asset('storage/templates/playlistImage.svg')}}">
            <h3 class="text-center my-2 editable" id="playlist_title" data-original="{{ $playlist->playlistName}}" 
                data-url="{{ route('playlistName.update', $playlist->id )}}">
                {{$playlist->playlistName}}
            </h3>
            <h4 class="authorName text-center mb-3" style="color: var(--placeholder)">{{$playlist->userName}}</h4>
            <div class="buttonsCont mt-3" id="playlistButtons" style="flex-direction: column;" data-playlist="{{ $playlist->id }}"></div>
        </div>
    
        <div class="tracksList mx-3 py-3 px-3" id="playlistTracks" data-playlist="{{ $playlist->id }}"></div>

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