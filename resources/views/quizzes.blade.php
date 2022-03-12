@extends("layouts.app")

@section("title", "Quizzes")

@section("content")
    <div class="user_div">
        <div id="username">{{ Auth::user()->name }}</div>
        <a class="button" href="{{ route('index') }}">{{ __('Index') }}</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <input type="submit" value="{{ __('Logout') }}">
        </form>
    </div>
    <div id="all_quizzes">
        @foreach($quizzes as $quiz)
            <div class="quiz_list_div">
                <div class="wrapper">
                    <h2 class="quiz_list_header">{{ $quiz->header }}</h2>
                    <p class="quiz_list_description">{{ $quiz->description }}</p>
                </div>
                <form method="POST" action="{{ route('gaming.store') }}">
                    @csrf
                    <div>
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        @error("user_id")
                            <p>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                        @error("quiz_id")
                            <p>{{ $message }}</p>
                        @enderror
                    </div>
                    <input id="play_button" type="submit" value="{{ __('Play') }}">
                </form>
            </div>
        @endforeach
    </div>
@endsection
