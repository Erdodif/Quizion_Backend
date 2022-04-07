<?php

namespace Tests\Unit;

use Tests\Unit\Classes\AuthenticatedTestCase;

class GameTest extends AuthenticatedTestCase
{
    public function testGameCreated(){
        $response = $this->postWithToken("api/play/newgame/1");
        $this->testResponseAssertion($response,201);
    }
}