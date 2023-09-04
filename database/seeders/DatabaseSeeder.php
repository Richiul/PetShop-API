<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\JwtToken;
use App\Models\Order;
use App\Models\PasswordReset;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{


    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(OrderStatusSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(CategorySeeder::class);

        User::factory(5)->create()->each(function ($user) {
            // Create 3 orders for each user
            Order::factory(3)->create(['user_id'=>$user->id]);

            JwtToken::factory()->create(['user_id'=>$user->id]);

            PasswordReset::factory()->create();

        });
    }
}
