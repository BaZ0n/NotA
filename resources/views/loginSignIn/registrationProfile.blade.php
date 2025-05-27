@extends('loginSignIn/registrationLayout')

@section("main_content")

<form action="/registrationProfile/confirm" method="POST" style="display: flex; flex-direction: column; height: 100%">
    @csrf
    <div style="flex-grow: 1;"></div>
    <div class="form-group my-2">
        <label for="nicknameLabel" class="text fs-4 my-2 mx-4" style="color: white">Имя пользователя</label>
        <input type="text" id="name" name="name" class="form-control fs-5 @error('name') is-invalid @enderror" style="background-color: black; border: 1px solid rgba(255, 255, 255, 0.1); color: white" placeholder="Chelik" value="{{old('name')}}">
        @error('name')
        <div class="invalid-feedback">{{$message}}</div>
        @enderror
    </div>
    <div class="form-group my-2">
        <label for="emailLabel" class="text fs-4 my-2 mx-4" style="color: white">Почта</label>
        <input type="email" id="email" name="email" class="form-control fs-5" style="background-color: black; border: 1px solid rgba(255, 255, 255, 0.1); color: white" placeholder="example@example.example"  value="{{old('email')}}">
    </div>
    <div class="form-group my-2">
        <label for="passwordLabel" class="text fs-4 my-2 mx-4" style="color: white">Пароль</label>
        <input type="password" id="password" name="password" class="form-control fs-5" style="background-color: black; border: 1px solid rgba(255, 255, 255, 0.1); color: white">
    </div>
    <div class="form-group my-2">
        <label for="password_confirmation_label" class="text fs-4 my-2 mx-4" style="color: white">Повторите пароль</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control fs-5" style="background-color: black; border: 1px solid rgba(255, 255, 255, 0.1); color: white">
    </div>
    <div style="flex-grow: 1;"></div>
    <div class="container d-flex justify-content-center align-items-center mb-5">
        <button type="submit" class="button-primary px-5 mt-5" style="text-decoration:none; ">
            <p class="text-center my-2 mx-5 px-5 py-2 fs-4" style="color: black;">Зарегистрироваться</p>
        </button>
    </div>
</form>
@endsection