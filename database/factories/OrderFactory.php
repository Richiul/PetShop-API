<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Str;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = ['product'=> Product::factory()->create()->uuid,'quantity'=>fake()->numberBetween(1,15)];

        $address = ['billing'=>fake()->address(),'shipping'=>fake()->address()];
        
        return [
            'user_id' => function()
            {
                return User::factory()->create()->id;
            },
            'order_status_id' => OrderStatus::inRandomOrder()->first()->id,
            'payment_id' =>function()
            {
                return Payment::factory()->create()->id;
            },
            'uuid' => uniqid(),
            'products' => json_encode($products),
            'address'=>json_encode($address),
            'amount'=>fake()->numberBetween(1,15),
            'created_at' =>now(),
            'updated_at' =>now()
        ];
    }
}
