@extends("layouts.app")

@section("title", "Leaderboard")

@section("content")
    <script>
        window.quizId = {{ Request::segment(2) }};
    </script>
    <script src="{{ mix('js/load_leaderboard.js') }}"></script>
    <h1>This is the {{ Request::segment(2) }}. quiz's leaderboard.</h1>
    <a class="button" href="{{ route('quizzes') }}">Quizzes</a>
    <div id="leaderboard"></div>
@endsection
