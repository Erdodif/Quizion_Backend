@extends("layouts.app")

@section("title", "Login")

@section("content")
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label for="login">Username or Email</label>
            <input type="text" id="login" name="login" value="{{ old('login') }}" placeholder="{{ __('Username or Email') }}" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" value="" placeholder="{{ __('Password') }}" required>
        </div>
        {{--
        <div>
            <label id="remember_me_label" for="remember_me">
                <input id="remember_me" type="checkbox" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>
        </div>
        --}}
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error_message">{{ $error }}</div>
            @endforeach
        @endif
        <a id="forgot_your_password" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
        <input type="submit" value="{{ __('Login') }}">
        <a class="button" href="{{ route('index') }}">{{ __('Index') }}</a>
    </form>
@endsection
