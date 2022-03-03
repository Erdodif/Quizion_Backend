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
Route::middleware('auth.token')->get('/quizzes/{quiz}/questions/count', [QuizQuestionController::class, 'count']);
Route::middleware('auth.token')->get('/quizzes/all', [QuizController::class, 'all']);
Route::middleware('auth.token')->apiResource('quizzes', QuizController::class);
Route::middleware('auth.token')->apiResource('quizzes.questions', QuizQuestionController::class);
Route::middleware('auth.token')->apiResource('quizzes.questions.answers', QuizAnswerController::class);
