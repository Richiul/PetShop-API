<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DeleteCategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        Artisan::call('db:seed --class=CategorySeeder');

        $adminUser = $this->createAdminUser();
        
        $adminToken = $adminUser->json()['authorisation']['token'];

        $categoryDelete = Category::first();

        // Set the JWT token in the request headers for admin user
        $headers = ['Authorization' => 'Bearer ' . $adminToken];
        $response = $this->json('DELETE', "/api/v1/category/{$categoryDelete->uuid}", [],$headers );

        $response->assertStatus(200)
        ->assertJson(['message' => "Category deleted successfully."]);

    // Ensure the user is deleted from the database
    $this->assertDatabaseMissing('categories', ['uuid' => $categoryDelete->uuid]);

    }
}
