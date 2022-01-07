@extends("layouts.app")

@section("title", "Quiz")

@section("content")
    <div class="time_bar"></div>
    <div class="report">Report</div>
    <div class="quiz_question">{{ $question->content }}</div>

    @foreach($answers as $answer)
        <div class="quiz_answer">{{ $answer->content }}</div>
    @endforeach

    <a href="../../../quizzes">Kvízek Listája</a>

    <!--RÉGI-->
    {{--<div class="progress_bar">
        <div class="progress_bar_color" style="width: <?php //echo $count_questions / $questions_count * 100; ?>%;"></div>
        <div class="progress_bar_border"></div>
        <div class="progress_bar_text"><?php //echo $count_questions; ?>/<?php //echo $questions_count; ?></div>
    </div>--}}
@endsection
