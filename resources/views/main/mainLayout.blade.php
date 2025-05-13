<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Vollkorn:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="{{ asset('css/MainDesignCss/mainDesign.css') }}" rel="stylesheet">
        <link href="{{ asset('css/animations.css') }}" rel="stylesheet">
        <link href="{{ asset('css/MainDesignCss/playlistDesign.css') }}" rel="stylesheet">
        <link href="{{ asset('css/MainDesignCss/userPageDesign.css')}}" rel="stylesheet">
        <link href="{{ asset('css/MainDesignCss/adaptive.css')}}" rel="stylesheet">
        <link href="{{ asset('css/MainDesignCss/collectionDesign.css')}}" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>NotA</title>
    </head>
    <body style="font-family: Vollkorn">
        <button class="sidebarBTN" id="sidebarBTN"><img class="svg" src="{{asset('images/icons/sidebar.svg')}}"></button>
        <div class="sidebar" id="sidebar">
            
            <a href="/userPage" class="user" style="margin-bottom: 30px;">
                <img class="userImg" src="{{ asset('images/icons/profileIcon.svg') }}" alt="Профиль">
                <span class="userName">{{Auth::user()->name}}</span>
            </a>    

            <div style="flex-grow: 1;"></div>
            
            <!-- Основные иконки -->
            <a href="/mainPage" class="sidebar-item">
                <img class="svg" src="{{ asset('images/icons/mainPageIcon.svg') }}" alt="Главная">
                <span>Главная</span>
            </a>
            
            <a href="/collectionPage" class="sidebar-item">
                <img class="svg" src="{{ asset('images/icons/collectionIcon.svg') }}" alt="Коллекция">
                <span>Коллекция</span>
            </a>
            
            <a href="#" class="sidebar-item">
                <img class="svg" src="{{ asset('images/icons/settingsIcon.svg') }}" alt="Настройки">
                <span>Настройки</span>
            </a>

            <div style="flex-grow: 1;"></div>

            <a href="#" class="sidebar-item">
                <img class="svg" src="{{ asset('images/icons/searchIcon.svg') }}" alt="Поиск">
                <span>Поиск</span>
            </a>
        </div>
        <div class="bodyContainer">            
            @if ($errors->any() || session('error'))
            <div class="alert alert-danger" style="position: fixed; top: 0; right: 0; z-index: 2000;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    {{session('error')}}
                </ul>
            </div>
            @endif
            @yield('main_content') 
        </div>    
        <div id="audioplayer"></div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{asset('js/imageAnimation.js')}}"></script>
    <script src="{{asset('js/showAll.js')}}"></script>
    <script src="{{asset("js/sidebarHideShow.js")}}"></script>
    @vite(['resources/js/app.js', 'resources/sass/audioplayer.scss'])
</html>