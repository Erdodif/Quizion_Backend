@extends("layouts.app")

@section("title", "Quizzes")

@section("content")
    @foreach($quizzes as $quiz)
        <div class="quiz_list_div">
            <h2 class="quiz_list_header">{{ $quiz->header }}</h2>
            <p class="quiz_list_description">{{ $quiz->description }}</p>
            <a href="quiz/{{ $quiz->id }}/question/1">Játék</a>
        </div>
    @endforeach
@endsection
