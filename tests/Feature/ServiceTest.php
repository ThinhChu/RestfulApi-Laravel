<?php

namespace Tests\Feature;

use Google\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    protected $user;

    public function setUp() :void {
        parent::setUp();
        $this->user = $this->authUser();
    }

    public function test_a_user_connect_service_google_drive(): void
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setScopes');
            $mock->shouldReceive('createAuthUrl')->andReturn('http://127.0.0.1:8000/');
        });

        $response = $this->getJson(route('service.connect', 'google-drive'))
                    ->assertOk()->json();

        $this->assertNotNull($response['url']);
    }

    public function test_a_user_callback_service_google_drive() {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchAccessTokenWithAuthCode')
            ->andReturn(['access_token' => 'fake-token']);
        });

        $res = $this->postJson(route('service.callback'), [
            'code' => 'hahaha'
        ])->assertCreated();

        $this->assertDatabaseHas('webservices', [
            'user_id' => $this->user->id, 
            'token' => json_encode(['access_token' => 'fake-token'])
        ]);
    }

    public function test_a_user_store_file_in_google_drive() {
        $this->createTask(['created_at' => now()->subDays(2)]);
        $this->createTask(['created_at' => now()->subDays(3)]);
        $this->createTask(['created_at' => now()->subDays(4)]);
        $this->createTask(['created_at' => now()->subDays(5)]);
        
        $this->createTask(['created_at' => now()->subDays(10)]);

        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setAccessToken');
            $mock->shouldReceive('getLogger->info');
            $mock->shouldReceive('shouldDefer');
            $mock->shouldReceive('execute');
        });
        $webservice = $this->createWebService();
        $this->postJson(route('service.store', $webservice->id))->assertCreated();
    }
}
