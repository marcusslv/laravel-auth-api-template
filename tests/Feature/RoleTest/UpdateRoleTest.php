<?php

namespace Tests\Feature\RoleTest;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UpdateRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_role_can_update_role()
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'test_role',
            'guard_name' => 'sanctum',
            'description' => 'Test role description',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson('api/roles/'.$role->id, [
                'name' => 'updated_role',
                'guard_name' => 'sanctum',
                'description' => 'Updated role description',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'type' => 'success',
            'status' => 200,
            'message' => 'Operação realizada com sucesso',
            'show' => true,
        ]);
        $this->assertDatabaseHas('roles', [
            'name' => 'updated_role',
            'guard_name' => 'sanctum',
            'description' => 'Updated role description',
        ]);
    }

    public function test_administrator_role_cannot_update_role_with_invalid_data()
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'test_role',
            'guard_name' => 'sanctum',
            'description' => 'Test role description',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson('api/roles/'.$role->id, [
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

    public function test_administrator_role_cannot_update_role_without_authentication()
    {
        $role = Role::create([
            'name' => 'test_role',
            'guard_name' => 'sanctum',
            'description' => 'Test role description',
        ]);

        $response = $this->putJson('api/roles/'.$role->id, [
            'name' => 'updated_role',
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
