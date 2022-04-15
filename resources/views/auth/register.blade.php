@extends("layouts.app")

@section("title", "Register")

@section("content")
    <script src="{{ mix('js/form.js') }}"></script>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="margin-top">
            <input type="text" name="name" value="{{ old('name') }}" placeholder="{{ __('Username') }}">
        </div>
        <div>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}">
        </div>
        <div class="password-div">
            <input id="password" type="password" name="password" placeholder="{{ __('Password') }}">
            <img id="show-password" src="{{ url('images/show-password.png') }}" alt="Show Password" title="Show Password">
        </div>
        <div>
            <input type="password" name="password_confirmation" placeholder="{{ __('Confirm Password') }}">
        </div>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error-message">{{ $error }}</div>
            @endforeach
        @endif
        @if ($userError ?? null)
            <div class="error-message">{{ $userError }}</div>
        @endif
        <input id="button_one_click" type="submit" value="{{ __('Register') }}">
    </form>
@endsection
