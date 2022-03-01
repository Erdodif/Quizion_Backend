<?php

use App\Http\Controllers\API\QuizAnswerController;
use App\Http\Controllers\API\QuizQuestionController;
use App\Http\Controllers\GamingController;
use App\Models\Quiz;
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

Route::resource("gaming", GamingController::class);

Route::get("/", function () {
    return redirect("index");
});

Route::get("/index", function () {
    return view("index");
})->name("index");

Route::get("/leaderboard/{quiz_id}", function (int $quiz_id) {
    return view("leaderboard_quiz", ["quiz_id" => $quiz_id]);
})->middleware(["auth"]);

Route::get("/quiz/{quiz_id}/question/{question_order}", function () {
    return view("quiz");
})->middleware(["auth"]);

Route::get("/quizzes", function () {
    $quizzes = Quiz::all();
    return view("quizzes", ["quizzes" => $quizzes]);
})->middleware(["auth"])->name("quizzes");

require __DIR__."/auth.php";
