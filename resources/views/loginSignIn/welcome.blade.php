{{-- 
    А вот и колонка с вводом данных для входа,
    одно поле для ввода своей почты (а чьей же ещё)
    еще одно поле для ввода пароля
    и две кн0п04ки для регистрации и востановления пароля 
 --}}

@extends('loginSignIn/loginLayout')

@section("main_content")
    <div class="backgroundContainer">
        <h1 class="text-center my-5 py-5" style="color:white; font-family:Lora">NotA</h1> {{-- Заголовок --}}
        <form id="loginForm" method="POST" action="/login/check"> {{-- Форма для бэка --}}
            @csrf
            <div class="form-group px-3 mx-5 mt-5 mb-3"> {{-- Тут мы вводим почту --}}
                <label for="emailLabel" class="text fs-4 my-3" style="color:white">Электронная почта</label>
                <input type="email" id="emailLogin" name="emailLogin" class="form-control fs-5"  placeholder="example@mail.ru">
            </div>
            <div class="form-group px-3 mx-5 my-5"> {{-- Тут мы пишем пароль, который указан на обратной стороне карты --}}
                <label for="passwordInput" class="text fs-4 my-3" style="color:white">Пароль</label>
                <input type="password" id="passwordLogin" name="passwordLogin" class="form-control fs-5">
            </div>
            <div class="container d-flex justify-content-center align-items-center"> {{-- А тут книпка шоб данные передать --}}
                <button type="submit" class="button-primary px-5 mt-5" style="text-decoration: none">
                    <p class="text-center my-2 mx-5 px-5 py-1 fs-4" style="color: black">Войти</p>
                </button>
            </div>
        </form>
        <a href="#" class="link-primary" style="text-decoration: none"> {{-- Если плохая память --}}
            <p class="text-center mt-1 mb-5">
                Забыли пароль.
            </p>
        </a>
        <div class="container d-flex justify-content-center align-items-center"> {{-- Регаться тут --}}
            <a href="/registrationCode" class="button-primary px-5 mt-5" style="text-decoration: none">
                <p class="text-center my-2 mx-5 px-5 py-1 fs-4" style="color: black">Создать аккаунт</p>
            </a>
        </div>

    </div>
@endsection