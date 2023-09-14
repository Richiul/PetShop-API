<?php

namespace Tests\Feature;

use App\Http\Requests\Main\PromotionsRequest;
use App\Models\Promotion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewPromotionsTest extends TestCase
{
    public function testPromotionsMethod()
    {
        $post = Promotion::factory()->create();

        $request = new PromotionsRequest(['valid' => true,'page'=>1]);

        $response = $this->json('GET', "/api/v1/main/promotions",[$request] );

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Promotions listed successfully.'
            ]);
    }
}
