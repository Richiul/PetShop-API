<?php

namespace Tests\Feature;

use App\Http\Controllers\BrandsController;
use App\Http\Requests\Brand\BrandRequest;
use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ViewBrandTest extends TestCase
{
    public function testBrandMethod()
    {
        // Create a brand for testing
        Artisan::call('db:seed --class=BrandSeeder');
        $brand = Brand::first();
        $response = $this->json('GET', "/api/v1/brand/{$brand->uuid}");

        // Assertions
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Brand printed successfully.',
                'data' => [
                    'uuid' => $brand->uuid,
                    'title' => $brand->title,
                    'slug' => $brand->slug,
                    // Add other brand attributes here
                ],
            ]);
    }
}
