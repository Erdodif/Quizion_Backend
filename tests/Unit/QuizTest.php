<?php

namespace Tests\Unit;

use App\Companion\Data;
use App\Companion\Message;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\QuizQuestionController;
use Database\Seeders\QuestionTableSeeder;
use Database\Seeders\QuizTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizTest extends DataTestCase
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
}
