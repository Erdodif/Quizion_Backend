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
| ADMIN Routes
|--------------------------------------------------------------------------
|
| Here is where you can register ADMIN routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "admin" middleware group. Enjoy building your API!
|
*/


Route::middleware(['auth.token','auth.admin'])->apiResource('questions', QuestionController::class);
Route::middleware(['auth.token','auth.admin'])->apiResource('answers', AnswerController::class);