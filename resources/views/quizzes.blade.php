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
@endsection
