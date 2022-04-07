<?php

namespace Tests\Unit;

use App\Models\Question;
use Tests\Unit\Classes\AuthenticatedTestCase;

class QuestionTest extends AuthenticatedTestCase
{
    public function test_all_questions_count()
    {
        $count = count(Question::all());
        $this->assertEquals(6, $count);
    }

    public function test_not_empty_question_class()
    {
        $this->assertNotEmpty(Question::class);
    }
}
