@extends("layouts.app")

@section("title", "Quiz")

@section("content")
    <div id="time_bar"></div>
    <div class="report">Report</div>
    {{--<div class="quiz_question">{{ $question->content }}</div>--}}
    <div id="question" class="quiz_question"></div>

    {{--
    @foreach($answers as $answer)
        <a href="../../../quiz/{{ Request::segment(2) }}/question/{{ Request::segment(4) + 1 }}">
            <div class="quiz_answer">{{ $answer->content }}</div>
        </a>
    @endforeach
    --}}
    <div id="answers"></div>

    <h1 id="out_of_time"></h1>

    <div class="progress_bar">
        <div class="progress_bar_color" style="width: {{ Request::segment(4) / $count->count * 100 }}%"></div>
        <div class="progress_bar_border"></div>
        <div class="progress_bar_text">{{ Request::segment(4) }}/{{ $count->count }}</div>
    </div>

    <!--<input type="number" id="pickAnswer">-->

    <script>
        function answerOnClick(number)
        {
            document.getElementById("answer" + number).classList.toggle("selected");
        }

        let count = {{ Request::segment(4) + 1 }};
    </script>
    <script src="{{ mix('js/load_quiz.js') }}"></script>
    <script src="{{ mix('js/progress_bar.js') }}"></script>
@endsection
