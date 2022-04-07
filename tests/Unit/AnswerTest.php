<?php

namespace Tests\Unit;

use App\Models\Answer;
use Tests\Unit\Classes\AuthenticatedTestCase;

class AnswerTest extends AuthenticatedTestCase
{
    public function test_all_answer_count()
    {
        $count = count(Answer::all());
        $this->assertEquals(18, $count);
    }

    public function test_not_empty_answer_class()
    {
        $this->assertNotEmpty(Answer::class);
    }
}
