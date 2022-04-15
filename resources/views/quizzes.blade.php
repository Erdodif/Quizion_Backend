@extends("layouts.app")

@section("title", "Quizzes")

@section("content")
    <div id="all-quizzes">
        @foreach($quizzes as $quiz)
            <div class="quiz-list-div">
                <div class="wrapper-quizzes">
                    <h2 class="quiz-list-header">{{ $quiz->header }}</h2>
                    <p class="quiz-list-description">{{ $quiz->description }}</p>
                </div>
                <div class="quizzes-buttons">
                    <div class="leaderboard-button-div">
                        <a class="leaderboard-button" href="{{ route('leaderboard', ["quiz_id" => $quiz->id]) }}">{{ __('Leaderboard') }}</a>
                    </div>
                    <form method="POST" action="{{ route('gaming.store') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        @error("user_id")
                            <div>{{ $message }}</div>
                        @enderror
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                        @error("quiz_id")
                            <div>{{ $message }}</div>
                        @enderror
                        <input class="play-button" type="submit" value="{{ __('Play') }}">
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
