<?php

use Illuminate\Support\Facades\Route;
use App\Models\Result;

Route::get('/leaderboard/{quiz_id}', function (int $quiz_id) {
    $result = Result::getByQuiz($quiz_id);
    return $result->toResponse();
});
