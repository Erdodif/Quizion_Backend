<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;

Route::get('/questions', function () {
    $result = Question::getAll();
    return $result->toResponse();
});

Route::group(['prefix' => '/question'], function () {
    Route::post('', function (Request $request) {
        $result = Question::addNew($request->getContent());
        return $result->toResponse();
    });
    Route::group(['prefix' => '/{id}'], function () {
        Route::get('', function (int $id) {
            $result = Question::getById($id);
            return $result->toResponse();
        });
        Route::put('', function (Request $request, int $id) {
            $result = Question::alterById($id, $request->getContent());
            return $result->toResponse();
        });
        Route::delete('', function (int $id) {
            $result = Question::deleteById($id);
            return $result->toResponse();
        });
        Route::get('/answers', function (int $id) {
            $result = Answer::getAllByQuestion($id);
            return $result->toResponse();
        });
        Route::get('/answer/{answer_order}', function (int $id, int $answer_order) {
            $result = Answer::getByQuestion($id,$answer_order);
            return $result->toResponse();
        });
    });
});
