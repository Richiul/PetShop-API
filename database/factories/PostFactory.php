<?php

namespace Database\Factories;

use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $metadata = ['author'=>fake()->name(),'image'=>function(){return File::factory()->create()->uuid;}];

        return [
            'uuid' => uniqid(),
            'title' => fake()->colorName(),
            'slug' => fake()->slug(),
            'content'=>fake()->text(50),
            "metadata"=>json_encode($metadata),
            'created_at' => now(),
            'updated_at' =>now()
        ];
    }
}
