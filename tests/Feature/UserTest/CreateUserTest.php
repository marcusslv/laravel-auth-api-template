<?php

namespace Tests\Feature\UserTest;

use App\Events\User\UserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_authentication_create_other_user()
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
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test.user@exemplo.com',
        ]);

        Event::assertDispatched(
            UserCreated::class,
            function ($event) {
                return $event->user->email === 'test.user@exemplo.com';
            }
        );
    }

    public function test_cannot_create_user_without_data()
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
            ]
        ]);
    }
}
