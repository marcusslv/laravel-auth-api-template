<?php

namespace Tests\Feature\UserTest;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_user_administrator_can_lists_all_users_with_pagination(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('users.index'));

        $response->assertStatus(200);
//        dd($response->json());
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
                "first_page_url",
                "from",
                "last_page",
                "last_page_url",
                "links" => [
                    "*" => [
                    "url",
                    "label",
                    "active",
                  ]
                ],
                "next_page_url",
                "path",
                "per_page",
                "prev_page_url",
                "to",
                "total",
            ],
            'show'
        ]);
    }

    public function test_if_user_administrator_can_lists_all_users_without_pagination(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('users.index', ['without_pagination' => true,]));

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
            'show'
        ]);
    }

    public function test_it_shows_a_single_user()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('users.show', ['user' => $user->id]));

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
            'show'
        ]);
    }
}
