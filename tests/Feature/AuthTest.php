<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login()
    {
        $this->seed(UsersTableSeeder::class);
        $response = $this->post("/login", [
            "login" => "test",
            "password" => "test",
            "remember" => false
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_register()
    {
        $response = $this->post('/register', [
            'name' => 'RegisterTestUser',
            'email' => 'registertest@test.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
