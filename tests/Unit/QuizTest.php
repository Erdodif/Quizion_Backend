<?php

namespace Tests\Unit;

use App\Http\Controllers\API\QuizQuestionController;
use Tests\Unit\Classes\AuthenticatedTestCase;

class QuizTest extends AuthenticatedTestCase
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
        $this->assertNotEquals('{"count":3}', QuizQuestionController::getCountByQuiz(1)->toResponse()->content());
    }

    public function test_notEmptyQuizClass()
    {
        $this->assertNotEmpty(Quiz::class);
    }

}
