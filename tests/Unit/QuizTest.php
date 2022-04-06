<?php

namespace Tests\Unit;

use App\Http\Controllers\API\QuizQuestionController;
use Database\Seeders\QuestionTableSeeder;
use Database\Seeders\QuizTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_quiz_count_question()
    {
        $this->seed([
            QuizTableSeeder::class,
            QuestionTableSeeder::class
        ]);
        $this->assertEquals('{"count":2}', QuizQuestionController::getCountByQuiz(1)->toResponse()->content());
    }
}
