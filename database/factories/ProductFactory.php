<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{

    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $metadata = ['brand'=>function(){return Brand::factory()->create()->uuid;},
        'image'=>function(){return Brand::factory()->create()->uuid;}];

        return [
            'uuid' => uniqid(),
            'category_id'=>function()
            {return Category::factory()->create()->uuid;},
            'title' => fake()->colorName(),
            'price' => fake()->numberBetween(200,2000),
            'description'=>fake()->text(50),
            "metadata"=>json_encode($metadata),
            'created_at' => now(),
            'updated_at' =>now()
        ];
    }
}
