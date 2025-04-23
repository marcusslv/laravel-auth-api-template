<?php

namespace Tests\Feature\UserTest;

use App\Events\User\UserCreatedEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_user_administrator_can_create_user()
    {
        Event::fake();
        $loggedUser = User::factory()->create();

        $response = $this->actingAs($loggedUser, 'sanctum')
            ->postJson('api/users', [
                'name' => 'Test User',
                'email' => 'test.user@exemplo.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'type' => 'success',
            'status' => 200,
            'show' => true,
            'response' => [
                'name' => 'Test User',
                'email' => 'test.user@exemplo.com',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test.user@exemplo.com',
        ]);

        Event::assertDispatched(
            UserCreatedEvent::class,
            function ($event) {
                return $event->user->email === 'test.user@exemplo.com';
            }
        );
    }

    public function test_if_user_administrator_cannot_create_user_with_invalid_data()
    {
        $loggedUser = User::factory()->create();

        $response = $this->actingAs($loggedUser, 'sanctum')
            ->postJson('api/users', [
                'name' => '',
                'email' => '',
                'password' => '',
            ]);

        $response->assertStatus(422);

        $response->assertJson([
            'type' => 'error',
            'status' => 422,
            'show' => true,
            'message' => 'Ops',
            'errors' => [
                'name' => ['The name field is required.'],
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ],
        ]);
    }
}
