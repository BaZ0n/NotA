@extends('loginSignIn/registrationLayout')

@section('main_content')
    <div class="container my-5" style="min-height: 80%; justify-content: space-between; display:flex; flex-direction: column;">
        <div class="alerts" style="display:flex; flex-direction: column; align-items:center;">
            <div class="alert alert-primary" style="width: max-content">
                <h4 class="text" style="color: black">На вашу почту отправлено письмо для подтверждения.</h4>
            </div>
            
            <div class="alert alert-warning my-5 px-3 py-3">
                <h5>Если на почту ничего не пришло, вы можете повторно запросить письмо с подтверждением</h5>
            </div>
        </div>
        
        <form action="/email/verification-notification" method="POST" class="form d-flex justify-content-center">
            @csrf
            <button class="button-primary px-5 py-4" type="submit"><h5 class="text-center">Отправить снова<h5></button>
        </form>
    </div>

@endsection