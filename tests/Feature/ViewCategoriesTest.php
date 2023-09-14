<?php

namespace Tests\Feature;

use App\Http\Requests\Category\ViewCategoriesRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ViewCategoriesTest extends TestCase
{
    public function testIndexMethod()
    {
        // Create some brands for testing
        Artisan::call('db:seed --class=CategorySeeder');

        // Define a mock request
        $request = new ViewCategoriesRequest([
            'page' => 1,
            'limit' => 5,
            'sortBy' => 'id',
            'desc' => 'false',
        ]);


        // Call the index method with the mock request
        $response = $this->json('GET', "/api/v1/categories",[$request]);

        // Assertions
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Categories listed successfully.',
            ]);
    }
}
