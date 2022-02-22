@extends("layouts.app")

@section("title", "Quizzes")

@section("content")
    <a class="button" href="{{ route('index') }}">{{ __('Index') }}</a>
    <p>Username: {{ Auth::user()->name }}</p>
    <p>Email: {{ Auth::user()->email }}</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <input type="submit" value="{{ __('Log Out') }}">
    </form>
    @foreach($quizzes as $quiz)
        <div class="quiz_list_div">
            <h2 class="quiz_list_header">{{ $quiz->header }}</h2>
            <p class="quiz_list_description">{{ $quiz->description }}</p>
            <form method="POST" action="{{ url('api/play/newgame/' . $quiz->id) }}">
                @csrf
                <input type="submit" value="{{ __('Play') }}">
            </form>
            <p id="a" onclick="loadDataQuestion(@php echo $quiz->id; @endphp)">loadDataQuestion</p>
            <p id="b" onclick="loadDataAnswers(@php echo $quiz->id; @endphp)">loadDataAnswers</p>
            <p onclick="newGame(@php echo $quiz->id; @endphp)">nextQuestion</p>
        </div>
    @endforeach
    <input type="number" id="pickAnswer">
        {{--quiz/{{ $quiz->id }}/question/1--}}
    <script>
        async function loadDataQuestion(id) {
            //let Response = await fetch({!! '\'' . url('api/play/' . $quiz->id . '/question') . '\'' !!});
            let Response = await fetch(`http://127.0.0.1:8000/api/play/${id}/question`);
            let data = await Response.json();
            console.log("Question");
            console.log(data);
        }

        async function loadDataAnswers(id) {
            //let Response = await fetch({!! '\'' . url('api/play/' . $quiz->id . '/answers') . '\'' !!});
            let Response = await fetch(`http://127.0.0.1:8000/api/play/${id}/answers`);
            let data = await Response.json();
            console.log("Answers");
            console.log(data);
        }

        async function newGame(id) {
            let answerId = document.getElementById("pickAnswer").value;
            const data = { "chosen": [answerId] };
            console.log(data["chosen"]);
            //let data = await fetch({!! '\'' . url('api/play/1/choose') . '\'' !!}, {
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
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
@endsection
