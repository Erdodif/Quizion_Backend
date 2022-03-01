@extends("layouts.app")

@section("title", "Quiz")

@section("content")
    <script>
        window.quizCount = {{ Request::segment(2) }};
    </script>
    <script src="{{ mix('js/load_quiz.js') }}"></script>
    <script src="{{ mix('js/progress_bar.js') }}"></script>

    <div id="time_bar"></div>
    <div class="report">Report</div>

    <div id="question" class="quiz_question"></div>
    <div id="answers"></div>
    <div class="button next_question" id="quiz_next_button" data-quiz-id="{{ Request::segment(2) }}">Next</div>

    <h1 id="out_of_time"></h1>
    {{--
    <div class="progress_bar">
        <div class="progress_bar_color" style="width: {{ Request::segment(4) / $count->count * 100 }}%"></div>
        <div class="progress_bar_border"></div>
        <div class="progress_bar_text">{{ Request::segment(4) }}/{{ $count->count }}</div>
    </div>
    --}}
    <div id="error"></div>
@endsection
