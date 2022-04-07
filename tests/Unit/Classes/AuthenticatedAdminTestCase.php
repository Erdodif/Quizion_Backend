<?php

namespace Tests\Unit\Classes;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Testing\TestResponse;

class AuthenticatedAdminTestCase extends AuthenticatedTestCase
{
    protected $token;

    public function refreshToken()
    {
        $this->token = $this->post("/api/users/login", [
            "userID" => "test",
            "password" => "test"
        ])->json("token");
    }
}
