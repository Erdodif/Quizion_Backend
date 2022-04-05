<?php

namespace Tests\Feature;

use Tests\TestCase;

class PagesLoadTest extends TestCase
{
    public function test_load_index_page()
    {
        $response = $this->get("/index");

        $response->assertStatus(200);
    }

    public function test_load_login_page()
    {
        $response = $this->get("/login");

        $response->assertStatus(200);
    }

    public function test_load_register_page()
    {
        $response = $this->get("/register");

        $response->assertStatus(200);
    }

    public function test_load_quizzes_page()
    {
        $response = $this->get("/quizzes");

        $response->assertStatus(302);
    }

    public function test_load_quiz_page()
    {
        $response = $this->get("/quiz/1");

        $response->assertStatus(302);
    }

    public function test_load_leaderboard_page()
    {
        $response = $this->get("/leaderboard/1");

        $response->assertStatus(302);
    }

    public function test_load_password_request_page()
    {
        $response = $this->get("/forgot-password");

        $response->assertStatus(200);
    }

    public function test_load_password_reset_page()
    {
        $response = $this->get("/reset-password/randomtoken");

        $response->assertStatus(200);
    }

    public function test_load_verification_notice_page()
    {
        $response = $this->get("/verify-email");

        $response->assertStatus(302);
    }

    public function test_load_verification_verify_page()
    {
        $response = $this->get("/verify-email/1/randomhash");

        $response->assertStatus(302);
    }

    public function test_load_password_confirm_page()
    {
        $response = $this->get("/confirm-password");

        $response->assertStatus(302);
    }
}
