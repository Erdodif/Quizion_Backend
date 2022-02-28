@extends("layouts.app")

@section("title", "Quiz")

@section("content")
    <script>
        //JAVÍT
        let count = {{ $count->count - 1 }};
        let dataLength = 0;
    </script>
    <div id="time_bar"></div>
    <div class="report">Report</div>

    <div id="question" class="quiz_question"></div>
    <div id="answers"></div>
    <div class="button next_question" onclick="play(count);">Next</div>

    <h1 id="out_of_time"></h1>
    {{--
    <div class="progress_bar">
        <div class="progress_bar_color" style="width: {{ Request::segment(4) / $count->count * 100 }}%"></div>
        <div class="progress_bar_border"></div>
        <div class="progress_bar_text">{{ Request::segment(4) }}/{{ $count->count }}</div>
    </div>
    --}}
    <script>
        async function play(id) {
            let answerIds = "";
            for (let i = 0; i < dataLength; i++) {
                try {
                    answerIds += document.getElementsByClassName("selected")[i].id + " ";
                }
                catch (error) {}
            }
            answerIds = answerIds.trim();
            let array = answerIds.split(" ").map(item => item.trim());
            for (let i = array.length - 1; i >= 0; i--) {
                if (array[i].includes("answer")) {
                    array.splice(i, 1);
                }
            }
            array = array.map(Number);
            const data = { "chosen": array };
            await fetch(`http://127.0.0.1:8000/api/play/${id}/choose`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                if (data.message == "Game haven't started yet or have already ended!") {
                    window.location = `http://127.0.0.1:8000/leaderboard/${id}`;
                }
                else {
                    window.location = `http://127.0.0.1:8000/quiz/${id}/question/${count}`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function answerOnClick(number)
        {
            document.getElementById(number).classList.toggle("selected");
        }
    </script>
    <script src="{{ mix('js/load_quiz.js') }}"></script>
    <script src="{{ mix('js/progress_bar.js') }}"></script>
@endsection
