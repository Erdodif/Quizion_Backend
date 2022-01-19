@extends("layouts.app")

@section("title", "Forgot Password")

@section("content")
    {{--
    <!-- Session Status -->
    @if ($status)
        <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }}>
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
            <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <input type="submit" value="{{ __('Email Password Reset Link') }}">
    </form>
    <a href="index">Back</a>
@endsection
