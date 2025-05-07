<?php

namespace Tests;

use App\Enums\RolesEnum;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $roles = RolesEnum::labels();

        foreach ($roles as $role) {
            Role::create([
                'name' => data_get($role, 'value'),
                'guard_name' => 'api',
                'description' => data_get($role, 'description'),
            ]);
        }
    }
}
