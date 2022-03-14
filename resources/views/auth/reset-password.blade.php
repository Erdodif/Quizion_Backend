@extends("layouts.app")

@section("title", "Reset Password")

@section("content")
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div>
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" value="" required>
        </div>
        <div>
            <label for="password_confirmation_id">Confirm Password </label>
            <input type="password" id="password_confirmation_id" name="password_confirmation" value="" required>
        </div>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error_message">{{ $error }}</div>
            @endforeach
        @endif
        <input type="submit" value="{{ __('Reset Password') }}">
    </form>
@endsection
