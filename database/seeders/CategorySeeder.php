<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Ramsey\Uuid\Uuid;



class CategorySeeder extends Seeder
{
    public function run()
    {
        // Define an array of status names
        $categoryNames = ['Food', 'Toys', 'Health', 'Vitamins','Pets'];

        // Iterate through the status names and create an OrderStatus with a unique UUID for each
        foreach ($categoryNames as $categoryName) {
            Category::create([
                'title' => $categoryName,
                'uuid' => Uuid::uuid4()->toString(),
                'slug'=>strtolower($categoryName)
            ]);
        }
    }
}
