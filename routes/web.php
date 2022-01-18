<?php

use App\Http\Controllers\UserController;
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
})->name("index");
/*
Route::get("/register", function () {
    return view("register", ["data" => null]);
})->name("register");

Route::get("/login", function () {
    return view("login");
})->name("login");
*/

//Route::resource("user", UserController::class);

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get("/quizzes", function () {
    $quizzes = Quiz::all();
    return view("quizzes", ["quizzes" => $quizzes]);
});

Route::get("/leaderboard/{quiz_id}", function (int $quiz_id) {
    return view("leaderboard_quiz", ["quiz_id" => $quiz_id]);
});

Route::get("/quiz/{quiz_id}/question/{question_id}", function (int $quiz_id, int $question_id) {
    $question = json_decode(Question::getByOrder($quiz_id, $question_id)->toJson());
    $answers = json_decode(Answer::getAllByQuiz($quiz_id, $question_id)->toJson());
    $count = json_decode(Question::getCountByQuiz($quiz_id)->toJson());
    if (empty($question->content)) {
        return redirect("/leaderboard/$quiz_id");
    }
    return view("quiz", ["question" => $question, "answers" => $answers, "count" => $count]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
