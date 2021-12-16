<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Answer;

Route::get('/answers', function () {
    $result = Answer::getAll();
    return $result->toResponse();
});

Route::group(['prefix' => '/answer'], function(){
    Route::post('',function(Request $request){
        $result = Answer::addNew($request->getContent());
        return $result->toResponse();
    });
    Route::group(['prefix' => '/{id}'], function(){
        Route::get('',function(int $id){
            $result = Answer::getById($id);
            return $result->toResponse();
        });
        Route::put('',function(Request $request, int $id){
            $result = Answer::alterById($id,$request->getContent());
            return $result->toResponse();
        });
        Route::delete('',function(int $id){
            $result = Answer::deleteById($id);
            return $result->toResponse();
        });
    });
});
