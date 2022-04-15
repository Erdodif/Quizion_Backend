@extends("layouts.app")

@section("title", "Leaderboard")

@section("content")
    <script>
        window.quizId = {{ Request::segment(2) }};
        window.username = "{{ Auth::user()->name }}";
    </script>
    <script src="{{ mix('js/leaderboard.js') }}"></script>
    <h1 id="title"></h1>
    <div id="result-user"></div>
    <div id="result"></div>
    <div id="leaderboard"></div>
@endsection
