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
Route::get('/quizzes/{quiz}/questions/count', [QuizQuestionController::class, 'count']);
Route::get('/quizzes/all', function () {
    return redirect()->action([QuizController::class, 'all']);
});
Route::apiResource('quizzes', QuizController::class);
Route::apiResource('quizzes.questions', QuizQuestionController::class);
Route::apiResource('quizzes.questions.answers', QuizAnswerController::class);
Route::apiResource('questions', QuestionController::class);
Route::apiResource('answers', AnswerController::class);
