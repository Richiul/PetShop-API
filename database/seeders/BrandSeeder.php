<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;


class BrandSeeder extends Seeder
{
    public function run()
    {
        // Define an array of status names
        $brandNames = ['Pedigree', 'Purina', 'Petfood', 'Briantos','Orijen'];

        // Iterate through the status names and create an OrderStatus with a unique UUID for each
        foreach ($brandNames as $brandName) {
            Brand::create([
                'title' => $brandName,
                'uuid' => Uuid::uuid4()->toString(),
                'slug'=>Str::slug($brandName)
            ]);
        }
    }
}
