<?php

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Testing\TestResponse;
use Tests\Unit\Classes\AuthenticatedTestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class GameTest extends AuthenticatedTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Game::addNew(["user_id" => 6, "quiz_id" => 1]);
    }

    public function test_game_created()
    {
        $response = $this->postWithToken("api/play/newgame/2");
        $this->testResponseAssertion($response, 201);
    }

    public function test_game_create_exist()
    {
        $response = $this->postWithToken("api/play/newgame/1");
        $this->testResponseAssertion($response, 500);
    }

    public function test_game_get_state()
    {
        $response = $this->getWithToken("api/play/1/state");
        $this->testResponseAssertion($response, 200, ["current" => 1]);
    }

    public function test_game_get_question()
    {
        $response = $this->getWithToken("api/play/1/question");
        $this->testResponseAssertion($response, 200);
        assertTrue($response->json()["id"] == 1);
    }

    public function test_game_get_answers()
    {
        $response = $this->getWithToken("api/play/1/answers");
        $this->testResponseAssertion($response, 200);
    }

    public function test_game_answer()
    {
        $response = $this->getWithToken("api/play/1/question");
        $response = $this->getWithToken("api/play/1/answers");
        $response = $this->postWithToken("api/play/1/choose", ["chosen" => [1]]);
        $this->testResponseAssertion($response, 200);
    }

    public function test_game_answer_delayed()
    {
        $response = $this->getWithToken("api/play/1/question");
        $this->testResponseAssertion($response, 200);
        sleep(11);
        $response = $this->postWithToken("api/play/1/choose", ["chosen" => [1]]);
        $this->testResponseAssertion($response, 408);
    }

    public function test_game_answer_conflict()
    {
        $response = $this->getWithToken("api/play/1/question");
        $response = $this->postWithToken("api/play/1/choose", ["chosen" => [1]]);
        $response = $this->postWithToken("api/play/1/choose", ["chosen" => [1]]);
        $this->testResponseAssertion($response, 404);
    }

    public function test_game_answer_previous()
    {
        $this->getWithToken("api/play/1/question");
        $this->postWithToken("api/play/1/choose", ["chosen" => [1]]);
        $this->getWithToken("api/play/1/question");
        $response = $this->postWithToken("api/play/1/choose", ["chosen" => [1]]);
        $this->testResponseAssertion($response, 400);
    }

    private function play_max_points():TestResponse{
        $this->getWithToken("api/play/1/question");
        $this->postWithToken("api/play/1/choose", ["chosen" => [1,2]]);
        $this->getWithToken("api/play/1/question");
        $this->postWithToken("api/play/1/choose", ["chosen" => [8]]);
        return $this->getWithToken("api/play/1/question");
    }

    private function play_half_points():TestResponse{
        $this->getWithToken("api/play/1/question");
        $this->postWithToken("api/play/1/choose", ["chosen" => [1]]);
        $this->getWithToken("api/play/1/question");
        $this->postWithToken("api/play/1/choose", ["chosen" => [9]]);
        return $this->getWithToken("api/play/1/question");
    }

    public function test_game_full_game_max_points()
    {
        $response = $this->play_max_points();
        $this->testResponseAssertion($response, 201);
        assertEquals($response->json("result"), 'First result by the users.');
        $response = $this->getWithToken("api/ranking/1");
        assertEquals($response->json("users")["points"], 300);
    }

    public function test_game_full_game_half_points()
    {
        $response = $this->play_half_points();
        $this->testResponseAssertion($response, 201);
        assertEquals($response->json("result"), 'First result by the users.');
        $response = $this->getWithToken("api/ranking/1");
        assertEquals($response->json("users")["points"], 50);
    }

    public function test_game_two_game_high_score()
    {
        $this->play_half_points();
        $response = $this->getWithToken("api/ranking/1");
        echo var_export($response->json());
        assertEquals($response->json("users")["points"], 50);
        $this->play_max_points();
        sleep(2);
        $response = $this->getWithToken("api/ranking/1");
        echo var_export($response->json());
        assertEquals($response->json("users")["points"], 300);
    }

    public function test_game_two_game_worse_score()
    {
        $this->play_max_points();
        $response = $this->getWithToken("api/ranking/1");
        assertEquals($response->json("users")["points"], 300);
        $this->play_half_points();
        $response = $this->getWithToken("api/ranking/1");
        assertEquals($response->json("users")["points"], 300);
    }

    public function test_game_two_game_same_score()
    {
        $this->play_half_points();
        $response = $this->getWithToken("api/ranking/1");
        assertEquals($response->json("users")["points"], 50);
        $this->play_half_points();
        $response = $this->getWithToken("api/ranking/1");
        assertEquals($response->json("users")["points"], 50);
    }
}
