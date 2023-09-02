<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JwtToken>
 */
class JwtTokenFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        return [
            'unique_id' => uniqid(),
            'user_id' => function()
            {
                return User::factory()->create()->id;
            },
            'token_title' => fake()->title(),
            'created_at' =>now(),
            'updated_at' =>now()
        ];
    }
}
