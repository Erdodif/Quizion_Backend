@extends("layouts.app")

@section("title", "Login")

@section("content")
    <script src="{{ mix('js/form.js') }}"></script>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="margin_top">
            <input type="text" name="login" value="{{ old('login') }}" placeholder="{{ __('Username or Email') }}" required>
        </div>
        <div>
            <input type="password" name="password" placeholder="{{ __('Password') }}" required>
        </div>
        @if ($errors->any())
            <div id="error_message_margin_remember_me">
                @foreach ($errors->all() as $error)
                    <div class="error_message">{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <label id="remember_me_label" for="remember_me">
            <input id="remember_me" type="checkbox" name="remember">
            <span>{{ __('Remember me') }}</span>
        </label>
        <input id="button_one_click" type="submit" value="{{ __('Login') }}">
        <a class="button" href="{{ route('index') }}">{{ __('Index') }}</a>
        <a class="button" href="{{ route('register') }}">{{ __('Register') }}</a>
        <a id="forgot_your_password" class="button" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
    </form>
@endsection
