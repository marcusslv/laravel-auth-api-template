<?php

namespace Tests\Feature\UserTest;

use App\Enums\RolesEnum;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteUserTest extends TestCase
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

    public function test_user_administrator_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->deleteJson('api/users/'.$user->id);

        $response->assertStatus(200);
        $response->assertJson([
            'type' => 'success',
            'status' => 200,
            'message' => 'Operação realizada com sucesso',
            'show' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => now(),
        ]);
    }

    public function test_user_administrator_cannot_delete_himself()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->deleteJson('api/users/'.$this->user->id);

        $response->assertStatus(403);
        $response->assertJson([
            'type' => 'error',
            'status' => 403,
            'message' => 'Você não pode excluir a si mesmo',
            'show' => true,
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $this->user->id,
            'deleted_at' => now(),
        ]);
    }

    public function test_user_administrator_cannot_delete_nonexistent_user()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->deleteJson('api/users/999999');

        $response->assertStatus(404);
        $response->assertJson([
            'type' => 'error',
            'status' => 404,
            'message' => 'Objeto não encontrado na base de dados',
            'show' => true,
        ]);
    }
}
