<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,WithFaker;

        public function createAdminUser()
    {
        $req = $this->json('POST',"api/v1/admin/create",[
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'is_admin' => 1,
            'is_marketing' => 0,
        ]);

        return $req;
        
    }
}
