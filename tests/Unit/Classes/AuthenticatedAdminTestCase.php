<?php

namespace Tests\Unit\Classes;

class AuthenticatedAdminTestCase extends AuthenticatedTestCase
{
    protected $token;

    public function refreshToken()
    {
        $this->token = $this->post("/api/users/login", [
            "userID" => "testadmin",
            "password" => "test"
        ])->json("token");
    }
}
