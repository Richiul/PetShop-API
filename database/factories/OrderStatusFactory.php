<?php

namespace Database\Factories;

use App\Models\OrderStatus;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class OrderStatusFactory extends Factory
{

    protected $model = OrderStatus::class;
    private $statusUuids = [];

    public function definition()
    {

        return [
            'title' => 'Cancelled',
            'uuid'=>Uuid::uuid4()->toString()
        ];
    }

}
