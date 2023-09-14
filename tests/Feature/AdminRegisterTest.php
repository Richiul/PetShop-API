<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminRegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testAdminCanRegister()
    {
        $userData = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'is_admin' => 1,
            'is_marketing' => 0,
        ];

        $response = $this->json('POST', '/api/v1/admin/create', $userData);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Admin created successfully',
                 ])
                 ->assertJsonStructure([
                     'data' => [
                         'first_name',
                         'last_name',
                         'email',
                         'address',
                         'phone_number',
                         'avatar',
                         'created_at',
                         'updated_at',
                     ],
                     'authorisation' => [
                         'token',
                         'type',
                     ],
                 ]);
    }
}