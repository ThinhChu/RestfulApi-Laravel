<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Webservice>
 */
class WebserviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'google-drive',
            'token' => ['access_token' => 'fake-token'],
            'user_id' => function () {
                return User::factory()->create()->id;
            },
        ];
    }
}
