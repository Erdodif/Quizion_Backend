@extends("layouts.app")

@section("title", "Quiz")

@section("content")
    <script>
        window.quizId = {{ Request::segment(2) }};
    </script>
    <script src="{{ mix('js/quiz.js') }}"></script>

    <div id="time_bar">
        <span id="time_bar_span_width_100">
            <span id="time_bar_progress"></span>
        </span>
    </div>

    <div class="report">Report</div>

    <div id="question" class="quiz_question"></div>
    <div id="answers"></div>
    <div class="button next_question" id="next_question_button">Next Question</div>
    <div class="button next_question" id="send_answer_button">Send Answer</div>

    <div id="progress_bar">
        <div id="progress_bar_color"></div>
        <div id="progress_bar_border"></div>
        <div id="progress_bar_text"></div>
    </div>

    <h1 id="out_of_time"></h1>
    <div id="error"></div>
@endsection
