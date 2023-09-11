<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promotion>
 */
class PromotionFactory extends Factory
{

    protected $model = Promotion::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $metadata = ['valid_from'=>now()->format('Y-m-d m-i-s'),
        'valid_to'=>now()->tomorrow()->format('Y-m-d m-i-s'),
        'image'=>File::factory()->create()->uuid
    ];

        return [
            'uuid' => Uuid::uuid4()->toString(),
            'content'=>fake()->text(50),
            "metadata"=>json_encode($metadata),
            'created_at' => now(),
            'updated_at' =>now()
        ];
    }
}
