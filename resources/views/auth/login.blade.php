@extends("layouts.app")

@section("title", "Login")

@section("content")
    <script src="{{ mix('js/form.js') }}"></script>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="margin-top">
            <input type="text" name="login" value="{{ old('login') }}" placeholder="{{ __('Username or Email') }}">
        </div>
        <div class="password-div">
            <input id="password" type="password" name="password" placeholder="{{ __('Password') }}">
            <img id="show-password" src="{{ url('images/show-password.png') }}" alt="Show Password" title="Show Password">
        </div>
        @if ($errors->any())
            <div id="error-message-margin-remember-me">
                @foreach ($errors->all() as $error)
                    <div class="error-message">{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <label id="remember-me-label" for="remember-me">
            <input id="remember-me" type="checkbox" name="remember">
            <span>{{ __('Remember me') }}</span>
        </label>
        <a id="forgot-your-password" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
        <input id="button_one_click" type="submit" value="{{ __('Login') }}">
    </form>
@endsection
