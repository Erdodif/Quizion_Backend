<?php

namespace Tests\Unit;

use App\Companion\Data;
use App\Companion\Message;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\QuizQuestionController;
use Database\Seeders\QuestionTableSeeder;
use Database\Seeders\QuizTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AuthenticatedTestCase extends DataTestCase
{
    protected $token;
    public function setUp(): void
    {
        parent::setUp();
        $this->token = $this->post("/api/users/login", [
            "userID" => "test",
            "password" => "test",
            "remember" => false
        ])->json("token");
    }

    public function post($uri, array $data = [], array $headers = [])
    {
        return parent::post($uri,$data,array_merge($headers,["Authentication"=>"bearer "+$this->token]));
    }
}