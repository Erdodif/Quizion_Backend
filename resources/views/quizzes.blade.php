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
                <a id="a" onclick="loadDataQuestion()">loadDataQuestion</a>
                <a id="b" onclick="loadDataAnswers()">loadDataAnswers</a>
                <p onclick="newGame()">newGame</p>
            </form>
        </div>
    @endforeach
        {{--
            {{ url('api/play/' . $quiz->id . '/question') }}
            {{ url('api/play/' . $quiz->id . '/answers') }}
            {{ url('api/play/' . $quiz->id . '/choose') }} name="chosen[]" POST

            quiz/{{ $quiz->id }}/question/1
        --}}
    <script>
        async function loadDataQuestion() {
            //URL::to('admin/posts/edit/' . $post->id)

            //let url = "{{!! url() }}";
            //let a = document.getElementById("a");
            //a.setAttribute("href", "api/play/" + id + "/question");

            //let id = {{ $quiz->id }};
            //console.log(id);

            let Response = await fetch('{{ url('api/play/' . $quiz->id . '/question') }}');
            let data = await Response.json();
            console.log("Question");
            console.log(data);
        }

        async function loadDataAnswers(id) {
            console.log(id);
            let Response = await fetch('{{ url('api/play/id/answers') }}');
            let data = await Response.json();
            console.log("Answers");
            console.log(data);
        }

        /*const data = { "chosen": [1] }; // 1 8
        fetch('{{ url('api/play/1/choose') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });*/

        async function newGame() {
            let data = await fetch('{{ url('api/play/1/choose') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            console.log(data);
            //await this.loadData()
        }

    </script>
@endsection
