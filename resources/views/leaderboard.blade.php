@extends("layouts.app")

@section("title", "Quizzes")

@section("content")
    <script>
        window.quizId = {{ $quiz_id }};
    </script>
    <script src="{{ mix('js/load_leaderboard.js') }}"></script>
    <h1>This is the {{ $quiz_id }}. quiz's leaderboard.</h1>
    <a class="button" href="{{ route('quizzes') }}">Quizzes</a>
    <div id="leaderboard"></div>
@endsection
