<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

Route::group(['prefix' => '/quizes'], function () {
    Route::get('', function () {
        $result = Quiz::getAll();
        return $result->toResponse();
    });
    Route::get('/actives', function () {
        $result = Quiz::getActives();
        return $result->toResponse();
    });
});
Route::group(['prefix' => '/quiz'], function () {
    Route::post('', function (Request $request) {
        $result = Quiz::addNew($request->getContent());
        return $result->toResponse();
    });
    Route::group(['prefix' => '/{id}'], function () {
        Route::get('', function (int $id) {
            $result = Quiz::getById($id);
            return $result->toResponse();
        });
        Route::put('', function (Request $request, int $id) {
            $result = Quiz::alterById($id, $request->getContent());
            return $result->toResponse();
        });
        Route::delete('', function (int $id) {
            $result = Quiz::deleteById($id);
            return $result->toResponse();
        });
        Route::get('/questions', function (int $id) {
            $result = Question::getAllByQuiz($id);
            return $result->toResponse();
        });
        Route::group(['prefix' => '/question/{question_order}'], function () {
            Route::get('', function (int $id, int $question_order) {
                $result = Question::getByOrder($id,$question_order);
                return $result->toResponse();
            });
            Route::get('/answers', function (int $id, int $question_order) {
                $result = Answer::getAllByQuiz($id,$question_order);
                return $result->toResponse();
            });
            Route::get('/answer/{answer_order}', function (int $id, int $question_order,int $answer_order) {
                $result = Answer::getByQuiz($id,$question_order,$answer_order);
                return $result->toResponse();
            });
        });
    });
});
