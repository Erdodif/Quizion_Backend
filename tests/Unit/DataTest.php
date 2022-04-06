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

class DataTest extends DataTestCase
{
    public function test_response_assertion(){
        $response = new Data(404,new Message("test"));
        $this->responseAssertion($response,404,'{"message":"test"}');
    }

    public function test_response_assertion_empty_string(){
        $response = new Data(300,new Message("test"));
        $this->responseAssertion($response,300);
    }

    public function test_response_assertion_empty(){
        $response = new Data(200,new Message("test"));
        $this->responseAssertion($response);
    }
}
