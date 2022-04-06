<?php

namespace Tests\Unit;

use App\Http\Controllers\API\QuizAnswerController;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\QuizQuestionController;
use CreateQuizTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class QuizTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_quiz_count_question()
    {
        $this->seed();
        $this->assertEquals('{"count":2}', QuizQuestionController::getCountByQuiz(1)->toResponse()->content());
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
