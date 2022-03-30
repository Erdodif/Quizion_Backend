<?php

use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\QuizQuestionController;
use App\Http\Controllers\API\QuizAnswerController;
use App\Http\Controllers\API\UserController;
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
Route::post('/users/resendemail', [UserController::class, 'resendEmailVerification']); //Mobile feature
Route::post('/users/login', [UserController::class, 'login']);
Route::post('/users/verified', [UserController::class, 'isEmailVerified']); //Mobile feature
Route::post('/users/register', [UserController::class, 'store']);
Route::middleware('auth.token')->get('/quizzes/{quiz}/questions/count', [QuizQuestionController::class, 'count']);
Route::middleware('auth.token')->get('/quizzes', [QuizController::class, 'index']);
Route::middleware('auth.token')->get('/quizzes/{quiz}', [QuizController::class, 'show']);
Route::middleware('auth.token')->apiResource('quizzes.questions', QuizQuestionController::class);
Route::middleware('auth.token')->apiResource('quizzes.questions.answers', QuizAnswerController::class);
