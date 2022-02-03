@extends("layouts.app")

@section("title", "Quizzes")

@section("content")
    <h1>This is the {{ $quiz_id }}. quiz's leaderboard.</h1>
    <a class="button" href="{{ route('quizzes') }}">Quizzes</a>
@endsection
