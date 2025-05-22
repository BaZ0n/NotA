@extends ('main/mainLayout')

@section('main_content')

    <div class="artistBodyContainer">
        <div class="artistInfoCont">
            <img class="artistAvatar" src="{{Vite::asset('resources/images/templates/userImage.svg')}}">
            <h4 class="artistName">{{$artist->artistName}}</h4>
        </div>
        <div class="tracksList" id="artistTracks" data-artist="{{$artist->id}}"></div>
        <div class="albumList" id="artistAlbums" data-artist="{{$artist->id}}"></div>
    </div>

@endsection