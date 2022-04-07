<?php

namespace Tests\Unit;

use App\Models\Game;
use Tests\Unit\Classes\AuthenticatedTestCase;

class GameTest extends AuthenticatedTestCase
{
    public function setUp():void{
        parent::setUp();
        Game::addNew(["user_id"=>6,"quiz_id"=>1]);
    }

    public function test_game_created(){
        $response = $this->postWithToken("api/play/newgame/2");
        $this->testResponseAssertion($response,201);
    }

    public function test_game_create_exist(){
        $response = $this->postWithToken("api/play/newgame/1");
        $this->testResponseAssertion($response,500);
    }
}