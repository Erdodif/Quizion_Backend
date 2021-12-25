<?php

use App\Companion\Data;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Companion\Message;
use App\Models\Game;
/**
 * TODO Middleware
 */

Route::prefix('/play')->middleware('auth.token')->group(function(){
    Route::post('/newgame/{quiz_id}',function(Request $request, int $quiz_id){
        $UID = $request->attributes->get("userID");
        $result = Game::addNew(["user_id"=>$UID,"quiz_id"=>$quiz_id]);
        return $result->toResponse();
    });
    Route::group(['prefix' => '/{quiz_id}'], function(){
        Route::get('/state',function(Request $request, int $quiz_id){
            $game = Game::getGame($quiz_id,$request->attributes->get("userID"));
            return $game->getCurrentState()->toResponse();
        });
        Route::get('/question',function(Request $request, int $quiz_id){
            $game = Game::getGame($quiz_id,$request->attributes->get("userID"));
            return $game->getCurrentQuestion()->toResponse();
        });
        Route::get('/answers',function(Request $request,int $quiz_id){
            $game = Game::getGame($quiz_id,$request->attributes->get("userID"));
            return $game->getCurrentAnswers()->toResponse();
        });
        Route::post('/choose',function(Request $request,int $quiz_id){
            $game = Game::getGame($quiz_id,$request->attributes->get("userID"));
            $chosen = $request->all()["chosen"];
            if ($game){
                $result = $game->pickAnswers($chosen);
            }
            else{
                $result = new Data(
                    ERROR_INTERNAL,
                    new Message("ez probléma")
                );
            }
            return $result->toResponse();
        });
    });
});
