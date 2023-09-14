<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testAdminCanLoginWithValidCredentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'), 
            'is_admin' => 1
        ]);

        $loginData = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->json('POST', '/api/v1/admin/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'first_name',
                    'last_name',
                    'email',
                ],
                'authorization' => [
                    'token',
                    'type',
                ],
            ]);
    }

    public function testAdminCannotLoginWithInvalidCredentials()
    {
        $loginData = [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->json('POST', '/api/v1/admin/login', $loginData);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Invalid credentials']);
    }
}