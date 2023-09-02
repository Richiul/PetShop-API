<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{

    protected $model = Brand::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->colorName(),
            'uuid' => uniqid(),
            'slug' => fake()->slug(),
            'created_at' => now(),
            'updated_at' =>now()
        ];
    }
}
