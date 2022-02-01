<?php

use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\QuizQuestionController;
use App\Http\Controllers\API\QuizAnswerController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\AnswerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/quizzes/{quiz_id}/questions/count', function (int $quiz_id) {
    return redirect()->action(
        [QuizQuestionController::class, 'count'],
        ['quiz_id' => $quiz_id]
    );
});
Route::resource('quizzes', QuizController::class);
Route::resource('quizzes.questions', QuizQuestionController::class);
Route::resource('quizzes.questions.answers', QuizAnswerController::class);
Route::resource('questions', QuestionController::class);
Route::resource('answers', AnswerController::class);
