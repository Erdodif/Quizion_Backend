@extends("layouts.app")

@section("title", "Forgot Password")

@section("content")
    {{--
    @if ($status)
        <div>
            {{ $status }}
        </div>
    @endif
    --}}
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div>
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        <input type="submit" value="{{ __('Email Password Reset Link') }}">
    </form>
@endsection
