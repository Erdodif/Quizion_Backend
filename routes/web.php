<?php

use App\Http\Controllers\GamingController;
use App\Models\Quiz;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource("gaming", GamingController::class);

Route::get("/", function () {
    return redirect("index");
});

Route::get("/index", function () {
    return view("index");
})->name("index");

Route::get("/quizzes", function () {
    $quizzes = Quiz::all();
    return view("quizzes", ["quizzes" => $quizzes]);
})->middleware(["auth"])->name("quizzes");

Route::get("/quiz/{quiz_id}", function () {
    return view("quiz");
})->middleware(["auth"]);

Route::get("/leaderboard/{quiz_id}", function () {
    return view("leaderboard");
})->middleware(["auth"]);

require __DIR__."/auth.php";
