@extends("layouts.app")

@section("title", "Register")

@section("content")
    <script src="{{ mix('js/form.js') }}"></script>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="margin_top">
            <input type="text" name="name" value="{{ old('name') }}" placeholder="{{ __('Username') }}" required>
        </div>
        <div>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required>
        </div>
        <div>
            <input type="password" name="password" placeholder="{{ __('Password') }}" required>
        </div>
        <div>
            <input type="password" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required>
        </div>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error_message">{{ $error }}</div>
            @endforeach
        @endif
        @if ($userError ?? null)
            <div class="error_message">{{ $userError }}</div>
        @endif
        <input id="button_one_click" type="submit" value="{{ __('Register') }}">
        <a class="button" href="{{ route('index') }}">{{ __('Index') }}</a>
        <a class="button" href="{{ route('login') }}">{{ __('Login') }}</a>
    </form>
@endsection
