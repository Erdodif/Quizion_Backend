@extends("layouts.layout")

@section("title", "Register")

@section("content")
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <label for="name_id">Felhasználónév</label>
            <input type="text" id="name_id" name="name" value="{{ old('name') }}" required autofocus>
            <div class="error_message"></div>
        </div>
        <div>
            <label for="email_id">Email cím</label>
            <input type="email" id="email_id" name="email" value="{{ old('email') }}" required>
            <div class="error_message"></div>
        </div>
        <div>
            <label for="password_id">Jelszó</label>
            <input type="password" id="password_id" name="password" value="" required>
            <div class="error_message"></div>
        </div>
        <div>
            <label for="password_confirmation_id">Jelszó megerősítése</label>
            <input type="password" id="password_confirmation_id" name="password_confirmation" value="" required>
            <div class="error_message"></div>
        </div>
        <input type="submit" value="{{ __('Register') }}">
    </form>
    <a href="index">Vissza</a>
@endsection
