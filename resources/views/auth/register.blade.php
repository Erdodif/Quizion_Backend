@extends("layouts.app")

@section("title", "Register")

@section("content")
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <label for="name_id">Username</label>
            <input type="text" id="name_id" name="name" value="{{ old('name') }}" placeholder="{{ __('Username') }}" required>
        </div>
        <div>
            <label for="email_id">Email</label>
            <input type="email" id="email_id" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required>
        </div>
        <div>
            <label for="password_id">Password</label>
            <input type="password" id="password_id" name="password" value="" placeholder="{{ __('Password') }}" required>
        </div>
        <div>
            <label for="password_confirmation_id">Confirm Password</label>
            <input type="password" id="password_confirmation_id" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" value="" required>
        </div>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error_message">{{ $error }}</div>
            @endforeach
        @endif
        <div id="already_registered"><a href="{{ route('login') }}">{{ __('Already registered?') }}</a></div>
        <input type="submit" value="{{ __('Register') }}">
        <a class="button" href="{{ route('index') }}">{{ __('Index') }}</a>
    </form>
@endsection
