@extends("layouts.app")

@section("title", "Register")

@section("content")
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <label for="name_id">Username</label>
            <input type="text" id="name_id" name="name" value="{{ old('name') }}" placeholder="Username" required autofocus>
        </div>
        <div>
            <label for="email_id">Email</label>
            <input type="email" id="email_id" name="email" value="{{ old('email') }}" placeholder="Email" required>
        </div>
        <div>
            <label for="password_id">Password</label>
            <input type="password" id="password_id" name="password" value="" placeholder="Password" required>
        </div>
        <div>
            <label for="password_confirmation_id">Confirm Password</label>
            <input type="password" id="password_confirmation_id" name="password_confirmation" placeholder="Confirm Password" value="{{--{{ __('Confirm Password') }}--}}" required>
        </div>
        <div class="already_registered"><a href="{{ route('login') }}">{{ __('Already registered?') }}</a></div>
        <input type="submit" value="{{ __('Register') }}">
        <a class="button" href="index">Back</a>
    </form>
@endsection
