<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_post(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/posts', [
            'title' => 'My First Post',
            'content' => 'This is the content of my first post.',
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.title', 'My First Post')
            ->assertJsonPath('data.content', 'This is the content of my first post.');

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'title' => 'My First Post',
            'content' => 'This is the content of my first post.',
        ]);
    }

    public function test_create_post_requires_title_and_content(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/posts', []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title',
                'content',
            ]);
    }
}
