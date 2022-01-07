<?php

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
    return redirect("index");
});

Route::get("/index", function () {
    return view("index");
});

Route::get("/register", function () {
    return view("register");
});

Route::get("/login", function () {
    return view("login");
});

Route::get("/quizzes", function () {
    $quizzes = Quiz::all();
    return view("quizzes", ["quizzes" => $quizzes]);
});

Route::get("/quiz/{quiz_id}/question/{question_id}", function (int $quiz_id, int $question_id) {
    // JAVÍTANI
    $question = json_decode(Question::getByOrder($quiz_id, $question_id)->toJson());
    $answers = json_decode(Answer::getAllByQuiz($quiz_id, $question_id)->toJson());
    return view("quiz", [ "question" => $question, "answers" => $answers]);
});
