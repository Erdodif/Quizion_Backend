<?php

use Illuminate\Support\Facades\Route;
use App\Models\Result;

Route::get('/results', function () {
    $result = Result::getAll();
    return $result->toResponse();
});
