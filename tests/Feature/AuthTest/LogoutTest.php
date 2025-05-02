<?php

namespace Tests\Feature\AuthTest;

use App\Events\Auth\UserLoggedOutEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_user_can_logout()
    {
        Event::fake();
        $user = User::factory()->create();
        $token = $user->createToken('apiToken')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('api/auth/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'type' => 'success',
            'status' => 200,
            'show' => true,
            'message' => 'Logout realizado com sucesso!',
        ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'token',
        ]);

        Event::assertDispatched(UserLoggedOutEvent::class, function ($event) use ($user) {
            return $event->user->email === $user->email;
        });
    }
}
