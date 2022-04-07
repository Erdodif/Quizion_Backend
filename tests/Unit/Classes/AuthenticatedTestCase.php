<?php

namespace Tests\Unit\Classes;

use Tests\Unit\Classes\DataTestCase;

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

    public function postWithToken($uri, array $data = [], array $headers = [])
    {
        return $this->post($uri,$data,array_merge($headers,["Authentication"=>"bearer "+$this->token]));
    }
}