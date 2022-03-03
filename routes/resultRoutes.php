<?php

use App\Companion\Data;
use App\Companion\Message;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Result;
use PhpParser\Node\Stmt\Catch_;

Route::get('/leaderboard/{quiz_id}', function (int $quiz_id) {
    return Result::getRankingsAll($quiz_id)->toResponse();
});

Route::prefix('/ranking')->middleware('auth.token')->group(function () {
    Route::get('/{quiz_id}', function (Request $request, int $quiz_id) {
        $UID = $request->userID;
        return Result::getUserRanking($quiz_id,$UID)->toResponse();
    });
});
