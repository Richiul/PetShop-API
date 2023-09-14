<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserListingTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanListUsers()
    {
        // Assuming you have some non-admin users in your database
        $adminUser = $this->createAdminUser();
        $adminToken = $adminUser->json()['authorisation']['token'];
        $headers = ['Authorization' => 'Bearer ' . $adminToken];

        $response = $this->json('GET', '/api/v1/admin/user-listing', [
            'page' => 1,
            'limit' => 15,
        ],$headers);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Users listed successfully.',
                 ]);
                 
    }
}