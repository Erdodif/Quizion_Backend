<?php

use App\Http\Controllers\API\AnswerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Answer;

Route::resource('answers',AnswerController::class);
