@extends("layouts.app")

@section("title", "Confirm Password")

@section("content")
    <div>{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</div>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" value="{{--{{ __('Password') }}--}}" required>
        </div>
        <input type="submit" value="{{ __('Confirm') }}">
    </form>
    <a class="button" href="{{ route('index') }}">{{ __('Index') }}</a>
@endsection
