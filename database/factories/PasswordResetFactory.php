<?php

namespace Database\Factories;

use App\Models\JwtToken;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PasswordReset>
 */
class PasswordResetFactory extends Factory
{
    protected $model = PasswordReset::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email'=>function()
            {
                return User::factory()->create()->id;
            },
            'token'=> function()
            {
                return JwtToken::factory()->create()->unique_id;
            },
            'created_at'=>now()
        ];
    }
}
