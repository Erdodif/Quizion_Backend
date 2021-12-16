<?php

use App\Http\Middleware\TokenIsValid;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Token;
use App\Models\Game;
/**
 * TODO Middleware
 */

Route::prefix('/play')->middleware('auth.token')->group(function(){
    Route::post('/newgame/{quiz_id}',function(Request $request, int $quiz_id){
        $UID = $request->attributes->get("userID");
        echo $UID."(kint)\n";
        $result = Game::addNew(["user_id"=>$UID,"quiz_id"=>$quiz_id]);
        return $result->toResponse();
    });
    Route::group(['prefix' => '/{quiz_id}'], function(){
        Route::get('/current/question',function(Request $request, int $quiz_id){
            $game = Game::getGame($quiz_id,$request->attributes->get("userID"));
            return $game->getCurrentQuestion()->toResponse();
        });
        Route::get('/current/answers',function(Request $request,int $quiz_id){
            $game = Game::getGame($quiz_id,$request->attributes->get("userID"));
            return $game->getCurrentAnswers()->toResponse();
        });
        Route::post('',function(Request $request,int $quiz_id){
            $game = Game::getGame($quiz_id,$request->attributes->get("userID"));
            if ($game){
                $game->pickAnswers([1,2]);
            }
        });
        Route::post('',function(Request $request, $quiz_id){
            //TODO pickAnswers(); Nem fogad el stringet (Data::castArray())!!!
        });
    });
});
