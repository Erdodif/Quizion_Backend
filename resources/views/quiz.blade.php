@extends("layouts.app")

@section("title", "Quiz")

@section("content")
    <script>
        window.quizId = {{ Request::segment(2) }};
    </script>
    <script src="{{ mix('js/quiz.js') }}"></script>

    <div id="time-bar">
        <span id="time-bar-span-width-100">
            <span id="time-bar-progress"></span>
        </span>
    </div>

    <div class="report">Report</div>

    <div id="question" class="quiz-question"></div>
    <div id="answers"></div>

    <div class="playing-button" id="next-question-button">Next Question</div>
    <div class="playing-button" id="send-answer-button">Send Answer</div>

    <div id="out-of-time"></div>
    <div id="error" class="error-message"></div>

    <div id="progress-bar">
        <div id="progress-bar-color"></div>
        <div id="progress-bar-border"></div>
        <div id="progress-bar-text"></div>
    </div>
@endsection
