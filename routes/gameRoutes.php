<?php

use App\Companion\Data;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Companion\Message;
use App\Models\Game;

require_once "../app/Companion/responseCodes.php";

/**
 * TODO Middleware
 */

Route::prefix('/play')->middleware('auth.token')->group(function () {
    Route::post('/newgame/{quiz_id}', function (Request $request, int $quiz_id) {
        $UID = $request->attributes->get("userID");
        $result = Game::addNew(["user_id" => $UID, "quiz_id" => $quiz_id]);
        return $result->toResponse();
    });
    Route::group(['prefix' => '/{quiz_id}'], function () {
        Route::get('/state', function (Request $request, int $quiz_id) {
            $game = Game::getGame($quiz_id, $request->attributes->get("userID"));
            return $game->getCurrentState()->toResponse();
        });
        Route::get('/question', function (Request $request, int $quiz_id) {
            $game = Game::getGame($quiz_id, $request->attributes->get("userID"));
            if ($game !== false) {
                $game = $game->getCurrentQuestion();
                if ($game->getCode() % 300 < 100) {
                    return redirect("/api/leaderboard/$quiz_id", REDIRECT_TEMPORARY);
                } else {
                    return $game->toResponse();
                }
            } else {
                return redirect("/api/leaderboard/$quiz_id", REDIRECT_TEMPORARY);
            }
        });
        Route::get('/answers', function (Request $request, int $quiz_id) {
            $game = Game::getGame($quiz_id, $request->attributes->get("userID"));
            if ($game !== false) {
                $game = $game->getCurrentAnswers();
                if ($game->getCode() % 300 < 100) {
                    return redirect("/api/leaderboard/$quiz_id", REDIRECT_TEMPORARY);
                } else {
                    return $game->toResponse();
                }
            } else {
                return redirect("/api/leaderboard/$quiz_id", REDIRECT_TEMPORARY);
            }
        });
        Route::post('/choose', function (Request $request, int $quiz_id) {
            $game = Game::getGame($quiz_id, $request->attributes->get("userID"));
            $chosen = $request->all()["chosen"];
            if ($game) {
                $result = $game->pickAnswers($chosen);
            } else {
                $result = new Data(
                    ERROR_NOT_FOUND,
                    new Message("Game haven't started yet or have already ended!")
                );
            }
            return $result->toResponse();
        });
    });
});
