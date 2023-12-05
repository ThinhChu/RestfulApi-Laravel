<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_a_user_can_login_email_and_password()
    {
        // preperation
        $user = User::factory()->create();
        
        // action
        $response = $this->postJson(route('user.login'),[
            'email' => $user->email,
            'password' => 'password'
        ])
        ->assertOk();

        // assert
        $this->assertArrayHasKey('token', $response->json());
    }

    public function test_a_user_email_is_not_availiable_return_error() {
        // preperation

        // action
        $this->postJson(route('user.login'),[
            'email' => 'thinhchuquang@gmail.com',
            'password' => 'Thinh@123'
        ])
        ->assertUnauthorized();

        // assert
    }

    public function test_a_user_password_is_not_availiable_return_error() {
        // preperation
        $user = User::factory()->create();

        // action
        $this->postJson(route('user.login'),[
            'email' => $user->email,
            'password' => 'random'
        ])
        ->assertUnauthorized();

        // assert
    }
}
