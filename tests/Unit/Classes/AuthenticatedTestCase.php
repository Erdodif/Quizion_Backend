<?php

namespace Tests\Unit\Classes;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Testing\TestResponse;
use Tests\Unit\Classes\DataTestCase;

class AuthenticatedTestCase extends DataTestCase
{
    protected $token;
    public function setUp(): void
    {
        parent::setUp(DatabaseSeeder::class);
        $this->refreshToken();
    }

    public function refreshToken()
    {
        $this->token = $this->post("/api/users/login", [
            "userID" => "test",
            "password" => "test"
        ])->json("token");
    }

    public function postWithToken($uri, array $data = []): TestResponse
    {
        return $this->post($uri, $data, ["Authorization" => "Bearer " . $this->token, "Accept" => "application/json"]);
    }

    public function getWithToken($uri): TestResponse
    {
        return $this->get($uri, ["Authorization" => "Bearer " . $this->token, "Accept" => "application/json"]);
    }
}
