<?php

namespace Tests\Unit;

use Tests\Unit\Classes\AuthenticatedAdminTestCase;

class AdminTest extends AuthenticatedAdminTestCase
{
    public function test_get_all_questions() 
    {
        $response = $this->getWithToken('admin/questions');
        $this->testResponseAssertion($response, 200);
    }

    public function test_get_all_answers() 
    {
        $response = $this->getWithToken('admin/answers');
        $this->testResponseAssertion($response, 200);
    }

    public function test_get_all_admins() 
    {
        $response = $this->getWithToken('admin/admins');
        $this->testResponseAssertion($response, 200);
    }

    public function test_get_all_users() 
    {
        $response = $this->getWithToken('admin/users');
        $this->testResponseAssertion($response, 200);
    }
    public function test_get_questions_with_bad_url() 
    {
        $response = $this->getWithToken('admin/questionsz');
        $this->testResponseAssertion($response, 404);
    }

    public function test_grant_user() 
    {
        $response = $this->postWithToken('admin/users/grant/3');
        $this->testResponseAssertion($response, 204);
    }

    public function test_revoke_user() 
    {
        $response = $this->postWithToken('admin/users/revoke/5');
        $this->testResponseAssertion($response, 204);
    }

    public function test_revoke_own()
    {
        $response = $this->postWithToken('admin/users/revoke/7');
        $this->testResponseAssertion($response, 403);
    }

    public function test_get_second_quiz()
    {
        $response = $this->getWithToken('admin/quizzes/2');
        $this->testResponseAssertion($response, 200);
    }

    public function test_get_invalid_admin()
    {
        $response = $this->getWithToken('admin/admins/8');
        $this->testResponseAssertion($response, 404);
    }

}
