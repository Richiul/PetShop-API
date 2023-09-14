<?php

namespace Tests\Feature;

use App\Http\Requests\Main\PostRequest;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewPostTest extends TestCase
{
    public function testPostMethod()
    {
        $post = Post::factory()->create();

        $request = new PostRequest(['uuid' => $post->uuid]);

        $response = $this->json('GET', "/api/v1/main/blog/{$post->uuid}", [$request] );

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Post printed successfully.',
                'data' => [
                    'uuid' => $post->uuid,
                    'title' => $post->title,
                ],
            ]);
    }
}
