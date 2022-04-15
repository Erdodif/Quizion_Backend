@extends("layouts.app")

@section("title", "Reset Password")

@section("content")
    <script src="{{ mix('js/form.js') }}"></script>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="margin-top">
            <input type="text" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}">
        </div>
        <div>
            <input class="password" type="password" name="password" placeholder="{{ __('Password') }}">
        </div>
        <div>
            <input class="password" type="password" name="password_confirmation" placeholder="{{ __('Confirm Password') }}">
        </div>
        <img id="show-password" src="{{ url('images/show-password.png') }}" alt="Show Password" title="Show Password">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error-message">{{ $error }}</div>
            @endforeach
        @endif
        <input id="button_one_click" type="submit" value="{{ __('Reset Password') }}">
    </form>
@endsection
