<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_a_user_can_register()
    {
        //  preperation

        // action
        $this->postJson(route('user.register'),[
            'name' => 'thinhchuquang',
            'email' => 'thinhchuquang@gmail.com',
            'password' => 'Thinh@123',
            'password_confirmation' => 'Thinh@123',
        ])->json();
        
        // assertor
        $this->assertDatabaseHas('users', ['name' => 'thinhchuquang']);
    }
}