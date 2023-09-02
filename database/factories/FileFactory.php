<?php

namespace Database\Factories;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => uniqid(),
            'name' => fake()->name(),
            'path' => fake()->colorName(),
            'size' => Str::random(10),
            'type' => 'mime',
            'created_at' =>now(),
            'updated_at' =>now()
        ];
    }
}
