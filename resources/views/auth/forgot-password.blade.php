@extends("layouts.app")

@section("title", "Forgot Password")

@section("content")
    <script src="{{ mix('js/form.js') }}"></script>
    @if (session('status'))
        <div id="info">
            {{ session('status') }}
        </div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="margin_top">
            <input type="text" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}">
        </div>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error_message">{{ $error }}</div>
            @endforeach
        @endif
        <input id="button_one_click" type="submit" value="{{ __('Email Password Reset Link') }}">
    </form>
@endsection
