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
        </div>
    @endforeach
        {{--
            {{ url('api/play/' . $quiz->id . '/question') }}
            {{ url('api/play/' . $quiz->id . '/answers') }}
            {{ url('api/play/' . $quiz->id . '/choose') }} name="chosen[]" POST

            quiz/{{ $quiz->id }}/question/1
        --}}
    <script>
        fetch('{{ url('api/play/1/question') }}')
        .then(response => response.json())
        .then(data => console.log(data));

        fetch('{{ url('api/play/1/answers') }}')
        .then(response => response.json())
        .then(data => console.log(data));

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
    </script>
@endsection
