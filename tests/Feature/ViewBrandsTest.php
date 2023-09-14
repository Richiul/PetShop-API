<?php

namespace Tests\Feature;

use App\Http\Requests\Brand\ViewBrandsRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ViewBrandsTest extends TestCase
{
    public function testIndexMethod()
    {
        // Create some brands for testing
        Artisan::call('db:seed --class=BrandSeeder');

        // Define a mock request
        $request = new ViewBrandsRequest([
            'page' => 1,
            'limit' => 5,
            'sortBy' => 'id',
            'desc' => 'false',
        ]);


        // Call the index method with the mock request
        $response = $this->json('GET', "/api/v1/brands",[$request]);

        // Assertions
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Brands listed successfully.',
            ]);
    }
}
