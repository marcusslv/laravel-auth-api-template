<?php

namespace Tests\Feature\UserTest;

use App\Enums\RolesEnum;
use App\Events\User\UserUpdatedEvent;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->create();
        $this->user->assignRole(RolesEnum::USER_ADMINISTRATOR->value);
        $this->token = $this->user->createToken('token')->plainTextToken;
    }

    public function test_if_user_administrator_can_update_user(): void
    {
        Event::fake();
        $user = User::factory()->create([
            'name' => 'Test User',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->putJson("api/users/{$user->id}", [
                'name' => 'Updated User',
            ]);

        $response->assertStatus(200);

        $response->assertJson([
            'type' => 'success',
            'status' => 200,
            'show' => true,
            'message' => 'Operação realizada com sucesso',
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Updated User',
        ]);

        Event::assertDispatched(
            UserUpdatedEvent::class,
            function ($event) {
                return $event->user->name === 'Updated User';
            }
        );
    }

    public function test_if_user_administrator_cannot_update_user_with_invalid_data(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->putJson("api/users/{$user->id}", [
                'name' => '',
            ]);

        $response->assertStatus(422);

        $response->assertJson([
            'type' => 'error',
            'status' => 422,
            'show' => true,
            'message' => 'Ops',
        ]);
    }
}
