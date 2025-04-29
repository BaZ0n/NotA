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
        <link href="css/loginSigninDesign.css" rel="stylesheet">
        <link href="css/animations.css" rel="stylesheet">
        <title>NotA</title>
    </head>
    <body style="background-image: url({{ asset('images/background/backgroundTwo.jpg') }}); background-size: cover; width:100%; min-height:100vh">
        <div class="container-fluid">
            <div class="container w-50 px-5 py-3" style="background-color: #1E1E1E; border: 2px solid black; height:100vh;">
                <h1 class="text-center my-5" style="color:white; font-family: Lora;">NotA</h1>
                <form>
                    <div class="form-group mt-5 mb-3">
                        <label for="firstNameLabel" class="text fs-4 my-3 mx-4" style="color: white">Имя</label>
                        <input type="text" id="userFirstName" name="userFirstName" class="form-control fs-5" style="background-color: black; border: 1px solid rgba(255, 255, 255, 0.1); color: white" placeholder="Иван">
                    </div>
                    <div class="form-group my-3">
                        <label for="lastNameLabel" class="text fs-4 my-3 mx-4" style="color: white">Фамилия</label>
                        <input type="text" id="userLastName" name="userLastName" class="form-control fs-5" style="background-color: black; border: 1px solid rgba(255, 255, 255, 0.1); color: white" placeholder="Иванов">
                    </div>
                    <div class="form-group my-3">
                        <label for="passwordLabel" class="text fs-4 my-3 mx-4" style="color: white">Пароль</label>
                        <input type="password" id="password" name="password" class="form-control fs-5" style="background-color: black; border: 1px solid rgba(255, 255, 255, 0.1); color: white">
                    </div>
                    <div class="form-group my-3">
                        <label for="passwordRepeatLabel" class="text fs-4 my-3 mx-4" style="color: white">Повторите пароль</label>
                        <input type="password" id="passwordRepeat" name="passwordRepeat" class="form-control fs-5" style="background-color: black; border: 1px solid rgba(255, 255, 255, 0.1); color: white">
                    </div>
                    <div class="container d-flex justify-content-center align-items-center mb-5">
                        <button type="submit" class="button-primary px-5 mt-5" style="text-decoration:none; ">
                            <p class="text-center my-2 mx-5 px-5 py-2 fs-4" style="color: black;">Зарегистрироваться</p>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>
    <script src="{{asset('js/registrationCodeJS.js')}}"></script>
</html>