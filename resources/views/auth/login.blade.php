@extends("layouts.layout")

@section("title", "Login")

@section("content")
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div>
            <label for="password">Jelszó</label>
            <input type="password" id="password" name="password" value="" required>
        </div>
        <div>
            <label for="remember_me">
                <input id="remember_me" type="checkbox" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>
        </div>
        <div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
            @endif
            <input type="submit" value="{{ __('Log in') }}">
        </div>
    </form>
    <a href="index">Vissza</a>
@endsection
