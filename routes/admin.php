<?php

use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\AnswerController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UserSetAdminController;
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

Route::middleware(['auth.token', 'auth.admin'])->post('/users/grant/{user}', [UserSetAdminController::class, 'grantPrivilege']);
Route::middleware(['auth.token', 'auth.admin'])->post('/users/revoke/{user}', [UserSetAdminController::class, 'revokePrivilege']);
Route::middleware(['auth.token', 'auth.admin'])->get('/quizzes/all', [QuizController::class, 'all']);
Route::middleware(['auth.token', 'auth.admin'])->apiResource('quizzes', QuizController::class);
Route::middleware(['auth.token', 'auth.admin'])->apiResource('questions', QuestionController::class);
Route::middleware(['auth.token', 'auth.admin'])->apiResource('answers', AnswerController::class);
Route::middleware(['auth.token', 'auth.admin'])->apiResource('users', UserController::class);
