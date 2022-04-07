<?php

namespace Tests\Unit\Classes;

use Database\Seeders\DatabaseSeeder;
use Tests\Unit\Classes\DataTestCase;

class AuthenticatedTestCase extends DataTestCase
{
    protected $token;
    public function setUp(): void
    {
        parent::setUp(DatabaseSeeder::class);
        $this->token = $this->post("/api/users/login", [
            "userID" => "test",
            "password" => "test"
        ])->json("token");
    }

    public function postWithToken($uri, array $data = [], array $headers = [])
    {
        return $this->post($uri,$data,array_merge($headers,["Authorization"=>"Bearer ".$this->token, "Accept"=>"application/json"]));
    }
}