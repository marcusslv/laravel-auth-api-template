<?php

namespace Tests\Feature\UserTest;

use App\Enums\RolesEnum;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadUserTest extends TestCase
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

    public function test_if_user_administrator_can_lists_all_users_with_pagination(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('api/users');

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
                        'email',
                        'email_verified_at',
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

    public function test_if_user_administrator_can_lists_all_users_without_pagination(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('api/users?without_pagination=true');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'type',
            'status',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                ],
            ],
            'show',
        ]);
    }

    public function test_it_shows_a_single_user()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('api/users/'.$this->user->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'type',
            'status',
            'data' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
            ],
            'show',
        ]);
    }
}
