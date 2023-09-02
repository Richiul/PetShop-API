<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{


    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        OrderStatus::factory()->create();

        User::factory(5)->create()->each(function ($user) {
            // Create 3 orders for each user
            $user->order()->saveMany(Order::factory(3)->create([
                'order_status_id' => OrderStatus::inRandomOrder()->first()->id,
            ]));
        });
        
        

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
