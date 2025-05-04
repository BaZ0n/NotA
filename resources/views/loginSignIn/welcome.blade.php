{{-- 
    А вот и колонка с вводом данных для входа,
    одно поле для ввода своей почты (а чьей же ещё)
    еще одно поле для ввода пароля
    и две кн0п04ки для регистрации и востановления пароля 
 --}}

@extends('loginSignIn/loginLayout')

@section("main_content")
    <div class="backgroundContainer">
        <h1 class="text-center mt-5" style="color:white; font-family:Lora">NotA</h1> {{-- Заголовок --}}
        <div style="flex-grow: 1;"></div>
        <form id="loginForm" method="POST" action="/login/check"> {{-- Форма для бэка --}}
            @csrf
            <div class="form-group px-3 mx-5 my-2"> {{-- Тут мы вводим почту --}}
                <label for="emailLabel" class="text fs-4" style="color:white">Электронная почта</label>
                <input type="email" id="email" name="email" class="form-control fs-5"  placeholder="example@mail.ru">
            </div>
            <div class="form-group px-3 mx-5 my-2"> {{-- Тут мы пишем пароль, который указан на обратной стороне карты --}}
                <label for="passwordInput" class="text fs-4" style="color:white">Пароль</label>
                <input type="password" id="password" name="password" class="form-control fs-5">
            </div>
            <div class="form-group px-5 mx-5 my-2"> {{-- Запонмите меня --}}
                <input type="checkbox" id="remember" name="remember" style="margin-right: 5px;">
                <label for="rememberLabel" class="text fs-6" style="color: white">Запомнить меня</label>
            </div>
            <div class="container d-flex justify-content-center align-items-center mt-4"> {{-- А тут книпка шоб данные передать --}}
                <button type="submit" class="button-primary px-5" style="text-decoration: none">
                    <p class="text-center mx-5 px-5 fs-4 my-2" style="color: black">Войти</p>
                </button>
            </div>
        </form>
        <a href="#" class="link-primary" style="text-decoration: none"> {{-- Если плохая память --}}
            <p class="text-center mt-1 mb-2">
                Забыли пароль.
            </p>
        </a>
        <div style="flex-grow: 1"></div>
        <div class="container d-flex justify-content-center align-items-center"> {{-- Регаться тут --}}
            <a href="/registrationProfile" class="button-primary px-5 mb-5" style="text-decoration: none">
                <p class="text-center my-2 mx-5 px-5 fs-4" style="color: black">Создать аккаунт</p>
            </a>
        </div>

    </div>
@endsection