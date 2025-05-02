<?php

namespace Tests\Feature\RoleTest;

use App\Enums\RolesEnum;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UpdateRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesSeeder::class);
        $this->user = User::factory()->create();
        $this->user->assignRole(RolesEnum::ROLE_ADMINISTRATOR->value);
        $this->token = $this->user->createToken('token')->plainTextToken;
    }

    public function test_administrator_role_can_update_role()
    {
        $role = Role::create([
            'name' => 'test_role',
            'guard_name' => 'sanctum',
            'description' => 'Test role description',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->putJson('api/roles/'.$role->id, [
                'name' => 'updated_role',
                'guard_name' => 'api',
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
            'guard_name' => 'api',
            'description' => 'Updated role description',
        ]);
    }

    public function test_administrator_role_cannot_update_role_with_invalid_data()
    {
        $role = Role::create([
            'name' => 'test_role',
            'guard_name' => 'sanctum',
            'description' => 'Test role description',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
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
