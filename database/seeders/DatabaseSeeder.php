<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
        ]);

        $user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'super.user@exemplo.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $roles = array_map(
            fn ($role) => data_get($role, 'value'),
            RolesEnum::labels()
        );

        $user->assignRole($roles);
    }
}
