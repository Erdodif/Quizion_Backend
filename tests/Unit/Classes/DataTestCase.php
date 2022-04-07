<?php

namespace Tests\Unit\Classes;

use App\Companion\Data;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DataTestCase extends TestCase
{
    use RefreshDatabase;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function responseAssertion(Data $response,int $responseCode = 200, ?string $responseString = null){
        if($responseString !== null){
            $this->assertEquals($responseString,$response->toResponse()->content());
        }
        $this->assertEquals($responseCode,$response->getCode());
    }
    
    protected function testResponseAssertion(TestResponse $response,int $responseCode = 200, ?array $responseJson = null){
        if($responseJson !== null){
            $response->assertExactJson($responseJson);
        }
        $response->assertStatus($responseCode);
    }
}