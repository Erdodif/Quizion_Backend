<?php

namespace Tests\Unit;

<<<<<<< HEAD
use App\Http\Controllers\API\QuizAnswerController;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\QuizQuestionController;
use CreateQuizTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class QuizTest extends TestCase
=======
use App\Companion\Data;
use App\Companion\Message;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\QuizQuestionController;
use Database\Seeders\QuestionTableSeeder;
use Database\Seeders\QuizTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizTest extends DataTestCase
>>>>>>> 2c1da35749c8e731aa4ce7e640086798be42237d
{
    public function test_first_quiz_count_existing()
    {
        $response =  QuizQuestionController::getCountByQuiz(1);
        $this->responseAssertion($response,200,'{"count":2}');
    }

    public function test_first_quiz_count_missing()
    {
        $response =  QuizQuestionController::getCountByQuiz(100);
        $this->responseAssertion($response,404,'{"message":"Quiz #100 not found!"}');
    }

    public function test_first_quiz_count_question_warning()
    {
        $this->seed();
        $this->assertNotEquals('{"count":3}', QuizQuestionController::getCountByQuiz(1)->toResponse()->content());
    }

    /*public function test_quiz()
    {
        $this->seed();
        $this->assertEmpty(Quiz::class);
    }
    */
}
