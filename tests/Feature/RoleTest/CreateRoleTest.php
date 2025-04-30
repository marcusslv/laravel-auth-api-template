<?php

namespace Tests\Feature\RoleTest;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_administrator_role_can_create_role()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('api/roles', [
                'name' => 'test_role',
                'guard_name' => 'sanctum',
                'description' => 'Test role description',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'type' => 'success',
            'status' => 200,
            'message' => 'Operação realizada com sucesso',
            'show' => true,
        ]);
        $this->assertDatabaseHas('roles', [
            'name' => 'test_role',
            'guard_name' => 'sanctum',
            'description' => 'Test role description',
        ]);
    }

    public function test_if_administrator_role_cannot_create_role_with_invalid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('api/roles', [
                'name' => '',
                'guard_name' => '',
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'type' => 'error',
            'status' => 422,
            'show' => true,
            'errors' => [
                'name' => ['The name field is required.'],
                'guard_name' => ['The guard name field is required.'],
            ],
        ]);
    }

    public function test_if_administrator_role_cannot_create_role_without_authentication()
    {
        $response = $this->postJson('api/roles', [
            'name' => 'test_role',
            'guard_name' => 'sanctum',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'type' => 'error',
            'status' => 401,
            'message' => 'Unauthenticated.',
            'show' => false,
        ]);
    }
}
