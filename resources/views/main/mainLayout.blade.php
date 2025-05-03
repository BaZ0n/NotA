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
        <link href="css/MainDesignCss/mainDesign.css" rel="stylesheet">
        <link href="css/animations.css" rel="stylesheet">
        <link href="css/MainDesignCss/playlistDesign.css" rel="stylesheet">
        <link href="css/MainDesignCss/userPageDesign.css" rel="stylesheet">
        <link href="css/MainDesignCss/adaptive.css" rel="stylesheet">
        <link href="css/MainDesignCss/collectionDesign.css" rel="stylesheet">
        <title>NotA</title>
    </head>
    <body>
        <div class="sidebar">

            <a href="/userPage" class="user" style="margin-bottom: 30px;">
                {{-- <img class="userImg" src="{{ asset('images/icons/profileIcon.svg') }}" alt="Профиль"> --}}
                <img class="userImg" src="{{ asset('images/userImages/ayanami.jpg') }}" alt="Профиль">
                <span class="userName">Челикс</span>
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
            @yield('main_content') 
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{asset('js/imageAnimation.js')}}"></script>
    <script src="{{asset('js/showAll.js')}}"></script>
</html>