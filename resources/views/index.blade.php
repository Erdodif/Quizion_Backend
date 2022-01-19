@extends("layouts.app")

@section("title", "")

@section("content")
    <a href="{{ route('login') }}">Log In</a>
    <br />
    <a href="{{ route('register') }}">Register</a>
    <br />
    <a href="{{ route('quizzes') }}">Quizzes</a>
@endsection
