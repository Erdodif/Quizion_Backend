@extends("layouts.app")

@section("title", "Register")

@section("content")
    <form method="POST" action="{{ route('users.store') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div>
            <label for="name_id">Felhasználónév</label>
            <input type="text" id="name_id" name="name" value="">
            <div class="error_message"></div>
        </div>
        <div>
            <label for="email_id">Email cím</label>
            <input type="email" id="email_id" name="email" value="">
            <div class="error_message"></div>
        </div>
        <div>
            <label for="password_id">Jelszó</label>
            <input type="password" id="password_id" name="password" value="">
            <div class="error_message"></div>
        </div>
        <!--<div>
            <label for="password2_id">Jelszó még egyszer</label>
            <input type="password" id="password2_id" name="password2" value="">
            <div class="error_message"></div>
        </div>-->
        <input type="submit" name="click" value="Regisztráció">
    </form>
    <!-- IDEIGLENES -->
    @if($error !== null)
        <div>{{ $error->getCode() }}</div>
    @endif
    <a href="index">Vissza</a>
@endsection
