<?php

namespace Tests\Feature\RoleTest;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReadRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_role_administrator_can_lists_all_roles_with_pagination(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        Role::create([
            'name' => 'test_role',
            'guard_name' => 'sanctum',
            'description' => 'Test role description',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('roles.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'type',
            'status',
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'guard_name',
                        'description',
                        'created_at',
                    ],
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'active',
                    ],
                ],
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ],
            'show',
        ]);
    }

    public function test_if_role_administrator_can_lists_all_roles_without_pagination(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        Role::create([
            'name' => 'test_role',
            'guard_name' => 'sanctum',
            'description' => 'Test role description',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('roles.index', ['without_pagination' => true]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'type',
            'status',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'guard_name',
                    'description',
                    'created_at',
                ],
            ],
            'show',
        ]);
    }

    public function test_it_shows_a_single_role()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'test_role',
            'guard_name' => 'sanctum',
            'description' => 'Test role description',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('roles.show', ['role' => $role->id]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'type',
            'status',
            'data' => [
                'id',
                'name',
                'guard_name',
                'description',
                'updated_at',
                'created_at',
            ],
            'show',
        ]);
    }
}
