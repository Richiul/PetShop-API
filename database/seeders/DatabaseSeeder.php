<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\JwtToken;
use App\Models\Order;
use App\Models\PasswordReset;
use App\Models\Post;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Factories\PostFactory;
use Database\Factories\PromotionFactory;
use Illuminate\Support\Facades\Hash;

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

        Post::factory(10)->create();
        Promotion::factory(5)->create();
        User::factory()->create(['email'=>'test@test.com','password'=>Hash::make('password'),'uuid' =>'00db0c68-8e21-4f93-880e-fd584ca4fa98']);
        User::factory()->create(['is_admin' => 1,'email' => 'admin@admin.com','password' =>Hash::make('password') ]);

    }
}
