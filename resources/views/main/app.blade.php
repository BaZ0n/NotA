<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title inertia>NotA</title>

    <!-- Шрифты и стили -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&family=Vollkorn:wght@400;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/MainDesignCss/mainDesign.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animations.css') }}" rel="stylesheet">
    <link href="{{ asset('css/MainDesignCss/playlistDesign.css') }}" rel="stylesheet">
    <link href="{{ asset('css/MainDesignCss/userPageDesign.css')}}" rel="stylesheet">
    <link href="{{ asset('css/MainDesignCss/adaptive.css')}}" rel="stylesheet">
    <link href="{{ asset('css/MainDesignCss/collectionDesign.css')}}" rel="stylesheet">
    <!-- Bootstrap и кастомные стили -->
    @vite(['resources/js/app.js', 'resources/sass/includeAll.scss'])
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body style="font-family: Vollkorn; background-color: #222222;">
    @inertia
</body>
</html>
