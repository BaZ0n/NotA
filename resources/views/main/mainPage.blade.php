@extends('main/mainLayout')

@section('main_content')
    <h2 class="text mt-5 mb-2 mx-4">Может понравится</h2>
    <div class="playlistsCollection mx-3 d-flex"> 
        <a href="/playlistPage" class="playlistLink">
            <div class="playlist px-2 py-2">
                <img src="{{asset('images/playlistImages/playlistImgTest.png')}}"></img>
                <h4>Плейлист дня</h4>
                <h5>Что-то новое</h5>
            </div>
        </a>
        <a href="#" class="playlistLink">
            <div class="playlist px-2 py-2">
                <img src="{{asset('images/playlistImages/playlistImgTest.png')}}"></img>
                <h4>Плейлист дня</h4>
                <h5>Что-то новое</h5>
            </div>
        </a>
        <a href="#" class="playlistLink">
            <div class="playlist px-2 py-2">
                <img src="{{asset('images/playlistImages/playlistImgTest.png')}}"></img>
                <h4>Плейлист дня</h4>
                <h5>Что-то новое</h5>
            </div>
        </a>
        <a href="#" class="playlistLink">
            <div class="playlist px-2 py-2">
                <img src="{{asset('images/playlistImages/playlistImgTest.png')}}"></img>
                <h4>Плейлист дня</h4>
                <h5>Что-то новое</h5>
            </div>
        </a>
        <a href="#" class="playlistLink">
            <div class="playlist px-2 py-2">
                <img src="{{asset('images/playlistImages/playlistImgTest.png')}}"></img>
                <h4>Плейлист дня</h4>
                <h5>Что-то новое</h5>
            </div>
        </a>
    </div>

    <h2 class="text mt-5 mb-2 mx-4">Последнее из любимого</h2>
    <div class="playlistsCollection mx-3 d-flex">
        <a href="#" class="playlistLink">
            <div class="playlist px-2 py-2">
                <img src="{{asset('images/playlistImages/playlistImgTest.png')}}"></img>
                <h4>Плейлист дня</h4>
                <h5>Что-то новое</h5>
            </div>
        </a>
    </div>

    <h3 class="text mt-5 mb-2 mx-4">Похожи на любимых</h3>
    <div class="artistsCollection mx-3 d-flex">
        <a href="#" class="artistLink">
            <div class="artist px-2 py-2">
                <img src="{{asset('images/artistsImages/4k.jpg')}}">
                <h4 class="text-center">4К</h4>
            </div>
        </a>
    </div>
@endsection