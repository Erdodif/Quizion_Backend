@extends("layouts.app")

@section("title", "Quiz")

@section("content")
    <script>
        window.quizCount = {{ Request::segment(2) }};
    </script>
    <script src="{{ mix('js/quiz.js') }}"></script>

    <div id="time_bar">
        <span style="width: 100%;">
            <span id="time_progress"></span>
        </span>
    </div>

    <div class="report">Report</div>

    <div id="question" class="quiz_question"></div>
    <div id="answers"></div>
    <div class="button next_question" id="quiz_next_button" data-quiz-id="{{ Request::segment(2) }}">Next</div>

    <div id="progress_bar">
        <div id="progress_bar_color"></div>
        <div id="progress_bar_border"></div>
        <div id="progress_bar_text"></div>
    </div>

    <h1 id="out_of_time"></h1>
    <div id="error"></div>
@endsection
