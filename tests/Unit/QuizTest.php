<?php

namespace Tests\Unit;

use App\Http\Controllers\API\QuizAnswerController;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\QuizQuestionController;
use App\Models\Quiz;
use Tests\Unit\Classes\AuthenticatedTestCase;

class QuizTest extends AuthenticatedTestCase
{
    public function test_first_quiz_count_existing()
    {
        $response = QuizQuestionController::getCountByQuiz(1);
        $this->responseAssertion($response, 200, '{"count":2}');
    }

    public function test_first_quiz_count_missing()
    {
        $response = QuizQuestionController::getCountByQuiz(100);
        $this->responseAssertion($response, 404, '{"message":"Quiz #100 not found!"}');
    }

    public function test_first_quiz_count_question_warning()
    {
        $count = QuizQuestionController::getCountByQuiz(1)->toResponse()->content();
        $this->assertNotEquals('{"count":3}', $count);
    }

    public function test_not_empty_quiz_class()
    {
        $this->assertNotEmpty(Quiz::class);
    }

    public function test_get_quizzes()
    {
        $response = $this->getWithToken("api/quizzes");
        $this->testResponseAssertion($response, 200);    
    }

    public function test_active_quizzes_count()
    {
        $count = count(QuizController::showActive()->getDataRaw());
        $this->assertEquals(3, $count);
    }

    public function test_all_quizzes_count()
    {
        $count = count(Quiz::all());
        $this->assertEquals(3, $count);
    }

    public function test_first_quiz_first_question_answer_count()
    {
        $answerCount = count(QuizAnswerController::getAllByQuiz(1, 1)->getDataRaw());
        $this->assertEquals(4, $answerCount);
    }

    public function test_invalid_question_count()
    {
        $response = QuizAnswerController::getAllByQuiz(1, 10);
        $this->responseAssertion($response, 404, '{"message":"Quiz #1 does not have 10. question!"}');
    }

    public function test_get_first_quiz_first_question_first_answer_content()
    {
        $answer = QuizAnswerController::getByQuiz(1, 1, 1)->getDataRaw();
        $this->assertEquals("Első kvíz első kérdés első válaszlehetőség", $answer->content);
    }

    public function test_invalid_answer_content()
    {
        $response = QuizAnswerController::getByQuiz(1, 1, 10);
        $this->responseAssertion($response, 404, '{"message":"The 1. question does not have 10. answer!"}');
    }
}
