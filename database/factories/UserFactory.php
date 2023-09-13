<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Uuid::uuid4()->toString(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'is_admin' => 0,
            'email'=>fake()->unique()->email(),
            'password' => fake()->password(),
            'avatar' => function () {
                return File::factory()->create()->uuid;
            },
            'address'=>fake()->address(),
            'phone_number'=>fake()->phoneNumber(),
            'is_marketing'=>0,
            'created_at' => now(),
            'updated_at' =>now()
        ];
    }

}
