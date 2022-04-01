<?php

namespace Tests\Feature;

use Tests\TestCase;

class PagesLoadTest extends TestCase
{
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
}
