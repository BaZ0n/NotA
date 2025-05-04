{{-- 
    Заготовок для страницы логина
--}}

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
        <link href="css/loginSigninCss/loginSigninDesign.css" rel="stylesheet">
        <link href="css/animations.css" rel="stylesheet">
        <link href="css/loginSigninCss/adaptive.css" rel="stylesheet">
        <title>NotA</title>
    </head>
    <body> {{-- Контейнер на всю страницу чтобы сделать две колонки, бесполезную и бесполезную, но с полями для ввода данных --}}
        <div class="container-fluid ">
            <div class="row align-items-start" style="justify-content: space-between">
                <div class="col px-0"> {{-- Бесполезная номер один --}}
                    <div class="container-fixed" style="position:fixed; top: 15px; left: 15px;">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <img src="{{ asset('images/background/backgroundOne.jpg') }}" style="object-fit: cover; width:100%; min-height:100vh" class="welcomePhoto">
                </div>

                <div class="col-4 px-0"> {{-- Бесполезная, а нет, полезная --}}
                    @yield('main_content') 
                </div>
            </div>
        </div>
    </body>
</html>