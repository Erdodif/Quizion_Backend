<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\QuizQuestionController;
use App\Http\Controllers\API\QuizAnswerController;

/*Route::group(['prefix' => '/quizzes'], function () {
    Route::get('', function () {
        $result = Quiz::getAll();
        return $result->toResponse();
    });
});*/
Route::get('/quizzes/{quiz_id}/questions/count', function (int $quiz_id) {
    return redirect()->action(
        [QuizQuestionController::class,'count'], ['quiz_id'=>$quiz_id]
    );
});
Route::resource('quizzes',QuizController::class);
Route::resource('quizzes.questions',QuizQuestionController::class);
Route::resource('quizzes.questions.answers',QuizAnswerController::class);
        
