<?php

namespace Tests\Feature;

use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'Registration successful.',
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        Mail::assertQueued(WelcomeUserMail::class);
    }

    public function test_user_registration_requires_required_fields(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/register', []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'email',
                'password',
            ]);

        $this->assertDatabaseCount('users', 0);

        Mail::assertNothingQueued();
    }
}
