<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserEditTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanEditUser()
    {
        // Assuming you have an existing user with UUID in the database
        $adminUser = $this->createAdminUser();
        $adminToken = $adminUser->json()['authorisation']['token'];

        $user = User::factory()->create();
        $currentData = $user->email;
        $newUserData = [
            "first_name"=> "test",
            "last_name"=>"test1",
            "email"=>"test33@yahoo.com",
            "password"=>"password1",
            "password_confirmation"=>"password1",
            "address"=>"test",
            "phone_number"=>"12321"
        ];
        $headers = ['Authorization' => 'Bearer ' . $adminToken];

        $response = $this->json('PUT', "/api/v1/admin/user-edit/{$user->uuid}", $newUserData,$headers);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => "User {$newUserData['email']} updated successfully",
                     'status' => 'success'
                 ]);

        
    }
}