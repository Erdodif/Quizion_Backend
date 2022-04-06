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

class QuizTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function responseAssertion(Data $response,int $responseCode = 200, string $responseString = null){
        if($responseString !== null){
            $this->assertEquals($responseString,$response->toResponse()->content());
        }
        $this->assertEquals($responseCode,$response->getCode());
    }

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
