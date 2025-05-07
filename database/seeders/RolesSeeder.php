<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
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
