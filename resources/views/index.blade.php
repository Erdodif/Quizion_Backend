@extends("layouts.app")

@section("title", "")

@section("content")
    <a class="button" href="{{ route('login') }}">Login</a>
    <a class="button" href="{{ route('register') }}">Register</a>
    <a class="button" href="{{ route('quizzes') }}">Quizzes</a>
@endsection
