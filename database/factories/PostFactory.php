<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

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
        $metadata = [
            'author'=>fake()->name(),
            'image'=>File::factory()->create()->uuid
        ];
        
        $title = fake()->text(20);

        return [
            'uuid' => Uuid::uuid4()->toString(),
            'title' => $title,
            'slug' => Str::slug($title),
            'content'=>fake()->text(50),
            "metadata"=>json_encode($metadata),
            'created_at' => now(),
            'updated_at' =>now()
        ];
    }
}
