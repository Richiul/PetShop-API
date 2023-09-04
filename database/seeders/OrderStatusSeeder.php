<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderStatus;
use Ramsey\Uuid\Uuid;



class OrderStatusSeeder extends Seeder
{
    public function run()
    {
        // Define an array of status names
        $statusNames = ['Pending', 'Processing', 'Shipped', 'Completed','Cancelled'];

        // Iterate through the status names and create an OrderStatus with a unique UUID for each
        foreach ($statusNames as $statusName) {
            OrderStatus::create([
                'title' => $statusName,
                'uuid' => Uuid::uuid4()->toString(),
            ]);
        }
    }
}
