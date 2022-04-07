<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\Unit\Classes\AuthenticatedTestCase;

class UserTest extends AuthenticatedTestCase
{
    public function test_all_users_count()
    {
        $count = count(User::all());
        $this->assertEquals(7, $count);
    }

    public function test_not_empty_user_class()
    {
        $this->assertNotEmpty(User::class);
    }
}
