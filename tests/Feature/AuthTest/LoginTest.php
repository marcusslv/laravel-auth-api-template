<?php

namespace Tests\Feature\AuthTest;

use App\Events\Auth\UserLoggedInEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_user_can_login(): void
    {
        Event::fake();
        $user = User::factory()->create([
            'email' => 'exemplo@email.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('api/auth/login', [
            'email' => 'exemplo@email.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'type' => 'success',
            'status' => 200,
            'show' => false,
            'data' => [
                'access_token' => data_get($response, 'data.access_token'),
                'token_type' => 'Bearer',
                'expires_in' => config('sanctum.expiration'),
            ],
        ]);

        Event::assertDispatched(UserLoggedInEvent::class, function ($event) use ($user) {
            return $event->user->email === $user->email;
        });
    }

    public function test_if_user_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'exemplo@email.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('api/auth/login', [
            'email' => 'exemplo@email.com',
            'password' => 'password_invalid',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'type' => 'error',
            'status' => 401,
            'show' => true,
            'message' => 'Credenciais inválidas!',
            'errors' => [
                'message' => 'Credenciais inválidas!',
            ],
        ]);

        $lastActivity = Activity::all()->last();

        $this->assertEquals('authentication', $lastActivity->log_name);
        $this->assertEquals('login_failed', $lastActivity->event);
    }

    public function test_if_user_cannot_login_with_empty_data(): void
    {
        $response = $this->postJson('api/auth/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'type' => 'error',
            'status' => 422,
            'show' => false,
            'message' => 'The email field is required.',
            'errors' => [
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ],
        ]);
    }
}
