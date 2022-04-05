<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{/*
    use RefreshDatabase;

    //JAVÍT
    public function test_login()
    {
        $this->seed(UsersTableSeeder::class);
        //$this->seed();
        $response = $this->post("/login", [
            "login" => "somass",
            "password" => "123456789",
            "remember" => false
        ]);
        //$this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }*/
}
