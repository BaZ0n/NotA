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
        <link href="css/loginSigninCss/loginSignInDesign.css" rel="stylesheet">
        <link href="css/animations.css" rel="stylesheet">
        <title>NotA</title>
    </head>
    <body style="overflow-y:hidden; background-image: url({{ asset('images/background/backgroundTwo.jpg') }}); background-size: cover; width:100%; min-height:100vh; height:auto;">
        <div class="container-fluid">
            <div class="container w-50 px-5 py-3" style="background-color: #1E1E1E; border: 2px solid black; height:100vh; margin: auto; display: flex; flex-direction: column;">
                <h1 class="text-center my-4" style="color:white; font-family: Lora;">NotA</h1>
                @yield('main_content')
            </div>
        </div>
        <div class="container-fixed" style="position:fixed; top: 15px; right: 15px;">
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
    </body>
</html>