@extends("layouts.app")

@section("title", "Quiz")

@section("content")
    @if(empty($question->content))
        @php
            // 1 csere -> {{ Request::segment(2) }}
            header("Location: ../../../leaderboard/1", true, 302);
            exit();
        @endphp
    @endif

    <div id="time_bar"></div>
    <div class="report">Report</div>
    <div class="quiz_question">{{ $question->content }}</div>

    @foreach($answers as $answer)
        <a href="../../../quiz/{{ Request::segment(2) }}/question/{{ Request::segment(4) + 1 }}">
            <div class="quiz_answer">{{ $answer->content }}</div>
        </a>
    @endforeach

    <div id="out_of_time"></div>

    <a href="../../../quizzes">Kvízek Listája</a>

    <div class="progress_bar">
        <div class="progress_bar_color" style="width: {{ Request::segment(4) / $count->count * 100 }}%"></div>
        <div class="progress_bar_border"></div>
        <div class="progress_bar_text">{{ Request::segment(4) }}/{{ $count->count }}</div>
    </div>

    <script>
        let maxTime = 1000;
        let timeLeft = 1000;
        let timer = setInterval(function() {
            if (timeLeft <= 0) {
                clearInterval(timer);
                document.getElementById("out_of_time").innerHTML = "Lejárt az idő!";
            }
            else {
                document.getElementById("time_bar").style.width = timeLeft / maxTime * 100 + "%";
            }
            timeLeft -= 1;
        }, 1);
    </script>
@endsection
