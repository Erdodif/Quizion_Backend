<?php

use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\GamingController;
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

Route::get("/documentation/{page}", function (string $page) {
    return view("index", ["page" => $page]);
})->name("documentation");

Route::get("/quizzes", function () {
    $quizzes = QuizController::showActive()->getDataRaw();
    return view("quizzes", ["quizzes" => $quizzes]);
})->middleware(["auth", "verified"])->name("quizzes");

Route::get("/quiz/{quiz_id}", function () {
    return view("quiz");
})->middleware(["auth", "verified"])->name("quiz");

Route::get("/leaderboard/{quiz_id}", function () {
    return view("leaderboard");
})->middleware(["auth", "verified"])->name("leaderboard");

require __DIR__."/auth.php";
