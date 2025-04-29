@extends('loginSignIn/loginLayout')

@section("main_content")

<div class="backgroundContainer">
    <h1 class="text-center my-5 py-5" style="color:white; font-family:Lora">NotA</h1>
    <div class="container my-5"></div>
    {{-- action="/registrationCode/EmailCheck" --}}
    <form id="emailForm" class="emailForm"> 
        <div class="form-group px-3 mx-5 mt-5 mb-3">
            <label for="emailLabel" class="text fs-4 my-3" style="color:white">Электронная почта</label>
            <input type="email" id="emailLogin" name="emailLogin" class="form-control fs-5"  placeholder="example@mail.ru">
        </div>
        
        <div class="container d-flex justify-content-center align-items-center">
            <button type="submit" class="button-primary px-5 mt-5" style="text-decoration: none">
                <p class="text-center my-2 mx-5 px-5 py-1 fs-4" style="color: black">Отправить код</p>
            </button>
        </div>

        <div class="container d-flex justify-content-center align-items-center d-none">
            <button type="submit" class="button-primary px-5 mt-5" style="text-decoration: none">
                <p class="text-center my-2 mx-5 px-5 py-1 fs-4" style="color: black">Отправить код</p>
            </button>
        </div>
    </form>

    <form id="codeForm" class="codeForm d-none" action="/loginSignIn/registrationProfile">
        @csrf
        <h2 class="text-center my-5" style="color: white;">Код подтверждения</h2>
        <div class="codeContainer mx-5">
            <div class="row align-items-start" style="justify-content: space-between">
                <div class="col">
                    <input type="number" id="codeOne" name="codeOne" class="form-control fs-5">
                </div>
                <div class="col">
                    <input type="number" id="codeOne" name="codeOne" class="form-control fs-5">
                </div>
                <div class="col">
                    <input type="number" id="codeOne" name="codeOne" class="form-control fs-5">
                </div>
                <div class="col">
                    <input type="number" id="codeOne" name="codeOne" class="form-control fs-5">
                </div>
            </div>
        </div>
        <div class="container d-flex justify-content-center align-items-center">
            <button type="submit" class="button-primary px-5 mt-5" style="text-decoration: none">
                <p class="text-center my-2 mx-5 px-5 py-1 fs-4" style="color: black">Подтвердить</p>
            </button>
        </div>
    </form>
    <a href="/registrationProfile">Хуй</a>
</div>

@endsection