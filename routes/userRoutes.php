<?php

use App\Models\Token;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;


Route::group(['prefix' => '/users'], function(){
    Route::get('/', function () {
        $result = User::getAll();
        return $result->toResponse();
    });
    Route::post('/register',function(Request $request){
        $result = User::addNew($request->getContent());
        return $result->toResponse();
    });
    Route::post('/login',function(Request $request){
        $result = Token::addNewByLogin($request->getContent());
        return $result->toResponse()->cookie(cookie('token',$result->getDataRaw()['token'],secure: true));
    });
    Route::group(['prefix' => '/{id}'], function(){
        Route::get('',function(int $id){
            $result = User::getByAny($id);
            return $result->toResponse();
        });
        Route::put('',function(Request $request, int $id){
            $result = User::alterById($id,$request->getContent());
            return $result->toResponse();
        });
        Route::delete('',function(int $id){
            $result = User::deleteById($id);
            return $result->toResponse();
        });
    });
});
