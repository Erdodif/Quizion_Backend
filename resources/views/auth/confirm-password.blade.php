@extends("layouts.app")

@section("title", "Confirm Password")

@section("content")
    <script src="{{ mix('js/form.js') }}"></script>
    <div id="info">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="margin_top">
            <input class="password" type="password" id="password" name="password" placeholder="{{ __('Password') }}">
        </div>
        <img id="show_password" src="{{ url('images/show_password.png') }}" alt="Show Password" title="Show Password">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error_message">{{ $error }}</div>
            @endforeach
        @endif
        <input id="button_one_click" type="submit" value="{{ __('Confirm') }}">
    </form>
@endsection
