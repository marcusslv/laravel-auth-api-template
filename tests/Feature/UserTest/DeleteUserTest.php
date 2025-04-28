<?php

namespace Tests\Feature\UserTest;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_administrator_can_delete_user()
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson('api/users/' . $user->id);

        $response->assertStatus(200);
        $response->assertJson([
            'type' => 'success',
            'status' => 200,
            'message' => 'Operação realizada com sucesso',
            'show' => true
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => now(),
        ]);
    }

    public function test_user_administrator_cannot_delete_himself()
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson('api/users/' . $admin->id);

        $response->assertStatus(403);
        $response->assertJson([
            'type' => 'error',
            'status' => 403,
            'message' => 'Você não pode excluir a si mesmo',
            'show' => true
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $admin->id,
            'deleted_at' => now(),
        ]);
    }

    public function test_user_administrator_cannot_delete_nonexistent_user()
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson('api/users/999999');

        $response->assertStatus(404);
        $response->assertJson([
            'type' => 'error',
            'status' => 404,
            'message' => 'Objeto não encontrado na base de dados',
            'show' => true
        ]);
    }
}
