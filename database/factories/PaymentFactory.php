<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{

    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $details = ['first_name'=>fake()->firstName(),'last_name'=>fake()->lastName(),'address'=>fake()->address()];
        return [
            'uuid'=>uniqid(),
            'type'=>fake()->randomElement(['credit_card','cash_on_delivery','bank_transfer']),
            'details'=>json_encode($details),
            'created_at' =>now(),
            'updated_at' =>now()
        ];
    }
}
