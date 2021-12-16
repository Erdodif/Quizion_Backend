<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Token;
use App\Models\Game;
/**
 * TODO Middleware
 */

Route::group(['prefix' => '/play'], function(){
    Route::post('newgame/{quiz_id}',function(Request $request, int $quiz_id){
        //TODO USER ID beszerzése!!!
        /*$game = Game::newGame(array("quiz_id"=>$quiz_id,"user_id"=>UID));
        return $game->toResponse();*/
    });
    Route::group(['prefix' => '/{quiz_id}'], function(){
        Route::post('',function(Request $request){
            $UID = $request->attributes->get("userID");
            echo $UID;
            $result = Game::newGame(["quiz_id"=>$request->getContent()["quiz_id"],"user_id"=>$UID]);
            return $result->toResponse();
            //
        });
        Route::get('',function(int $id){
            // TODO getCurrentQuestion(); még csak kérdést ad vissza, nem Data-t!!!
        });
        Route::get('',function(int $id){
            // TODO getCurrentQuestion(); még csak kérdést ad vissza, nem Data-t!!!
        });
        Route::post('',function(Request $request){
            //TODO pickAnswers(); Nem fogad el stringet (Data::castArray())!!!
        });
    });
});
