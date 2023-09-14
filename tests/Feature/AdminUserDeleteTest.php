<?php

use App\Models\JwtToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminUserDeleteTest extends TestCase
{
    use RefreshDatabase,WithFaker;

    public function testAdminCanDeleteUser()
    {
        // Create an admin user and authenticate them
        $adminUser = $this->createAdminUser();
        
        $adminToken = $adminUser->json()['authorisation']['token'];

        $userToDelete = User::factory()->create();

        // Set the JWT token in the request headers for admin user
        $headers = ['Authorization' => 'Bearer ' . $adminToken];

        // Attempt to delete the user by making a DELETE request
        $response = $this->json('DELETE', "/api/v1/admin/user-delete/{$userToDelete->uuid}", [],$headers );

        $response->assertStatus(200)
            ->assertJson(['message' => "User {$userToDelete->email} deleted successfully"]);

        // Ensure the user is deleted from the database
        $this->assertDatabaseMissing('users', ['uuid' => $userToDelete->uuid]);
    }
}