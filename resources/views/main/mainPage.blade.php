@extends('main/mainLayout')

@section('main_content')
    <h2 class="text mt-5 mb-2 mx-4">Может понравится</h2>
    <div class="playlistsCollection mx-3 d-flex">
        @foreach ($playlists as $playlist)
            @if($loop->iteration > 5)
                @break
            @endif
            <a href="/playlist/{{$playlist->id}}" class="playlistLink">
                <div class="playlist px-2 py-2">
                    <img class="playlistImage" src="{{asset('storage/templates/playlistImage.svg')}}">
                    <h4>{{$playlist->playlistName}}</h4>
                    <h6>{{$playlist->userName}}</h6>
                </div>
            </a>
        @endforeach 
    </div>

    <h2 class="text mt-5 mb-2 mx-4">Последнее из любимого</h2>
    <div class="playlistsCollection mx-3 d-flex">
        @foreach ($user_playlists as $playlist)
            @if($loop->iteration > 5)
                @break
            @endif
            <a href="/playlist/{{$playlist->id}}" class="playlistLink">
                <div class="playlist px-2 py-2">
                    <img class="playlistImage" src="{{asset('storage/templates/playlistImage.svg')}}">
                    <h4>{{$playlist->playlistName}}</h4>
                </div>
            </a>
        @endforeach 
    </div>

    <h3 class="text mt-5 mb-2 mx-4">Похожи на любимых</h3>
    <div class="artistsCollection mx-3 d-flex">
        @foreach ($artists as $artist)
            @if($loop->iteration > 5)
                @break
            @endif
            <a href="/artist/{{$artist->id}}" class="artistLink">
                <div class="artist px-2 py-2">
                    <img src="{{asset('storage/templates/userImage.svg')}}">
                    <h4 class="text-center">{{$artist->artistName}}</h4>
                </div>
            </a>
        @endforeach
        
    </div>
@endsection