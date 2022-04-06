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

    public function test_login_invalid()
    {
        $response = $this->post("/api/users/login", [
            "userID" => ".",
            "password" => "...",
            "remember" => false
        ]);
        $this->testResponseAssertion($response,400);
    }
    
    public function test_register_number()
    {
        $response = $this->post('/api/users/register', [
            'name' => 'RegisterTestUser',
            'email' => 'registertest@test.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);
        $this->testResponseAssertion($response,201);
    }

    public function test_register_special()
    {
        $response = $this->post('/api/users/register', [
            'name' => 'RegisterTestUser',
            'email' => 'registertest@test.com',
            'password' => 'Password-_-*',
            'password_confirmation' => 'Password-_-*',
        ],['accept'=>'application/json']);
        $this->testResponseAssertion($response,201);
    }

    public function test_register_invalid_fields()
    {
        $response = $this->post('/api/users/register', [
            'name' => '.',
            'email' => '.',
            'password' => '.',
            'password_confirmation' => '.',
        ],['accept'=>'application/json']);
        $this->testResponseAssertion($response,422);
    }

    public function test_register_invalid_password_different()
    {
        $response = $this->post('/api/users/register', [
            'name' => 'RegisterTestUser',
            'email' => 'registertest@test.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password1234',
        ],['accept'=>'application/json']);
        $this->testResponseAssertion($response,422);
    }

    public function test_register_invalid_name_special()
    {
        $response = $this->post('/api/users/register', [
            'name' => 'RegisterTestUser1.',
            'email' => 'registertest@test.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ],['accept'=>'application/json']);
        $this->testResponseAssertion($response,422);
    }

    public function test_register_invalid_email()
    {
        $response = $this->post('/api/users/register', [
            'name' => 'RegisterTestUser',
            'email' => 'registertest',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ],['accept'=>'application/json']);
        $this->testResponseAssertion($response,422);
    }

    public function test_register_invalid_password_upper_missing()
    {
        $response = $this->post('/api/users/register', [
            'name' => 'RegisterTestUser',
            'email' => 'registertest@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ],['accept'=>'application/json']);
        $this->testResponseAssertion($response,422);
    }

    public function test_register_invalid_password_lower_missing()
    {
        $response = $this->post('/api/users/register', [
            'name' => 'RegisterTestUser',
            'email' => 'registertest@test.com',
            'password' => 'PASSWORD123',
            'password_confirmation' => 'PASSWORD123',
        ],['accept'=>'application/json']);
        $this->testResponseAssertion($response,422);
    }

    public function test_register_invalid_password_special_missing()
    {
        $response = $this->post('/api/users/register', [
            'name' => 'RegisterTestUser',
            'email' => 'registertest@test.com',
            'password' => 'PASSWORD123',
            'password_confirmation' => 'PASSWORDpassword',
        ],['accept'=>'application/json']);
        $this->testResponseAssertion($response,422);
    }

    public function test_user_check_validated_email()
    {
        $response = $this->post('/api/users/verified', [
            'userID' => 'test',
            'password' => 'test'
        ],['accept'=>'application/json']);
        $this->testResponseAssertion($response,200,["verified"=> true]);
    }

    public function test_user_check_non_validated_email()
    {
        $this->post('/api/users/register', [
            'name' => 'RegisterTestUser',
            'email' => 'registertest@test.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);
        $response = $this->post('/api/users/verified', [
            'userID' => 'RegisterTestUser',
            'password' => 'Password123'
        ],['accept'=>'application/json']);
        $this->testResponseAssertion($response,200,["verified"=> false]);
    }

    public function test_user_check_invalid_login()
    {
        $response = $this->post('/api/users/verified', [
            'userID' => '.',
            'password' => '.'
        ],['accept'=>'application/json']);
        $this->testResponseAssertion($response,400);
    }
}