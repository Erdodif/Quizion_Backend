<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Unit\DataTestCase;

class AuthTest extends DataTestCase
{

    public function test_login()
    {
        $response = $this->post("/api/users/login", [
            "userID" => "test",
            "password" => "test",
            "remember" => false
        ]);
        $this->testResponseAssertion($response,201);
    }
}