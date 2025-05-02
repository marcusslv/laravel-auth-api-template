<?php

namespace App\Console\Commands;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class Setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the application';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Setting up the application...');

        $this->info('Creating roles...');
        $this->createRoles();

        $this->info('Creating super admin user...');
        $this->createSuperAdminUser();

        $this->info('Application setup completed successfully.');
    }

    public function createRoles(): void
    {
        $roles = RolesEnum::labels();

        foreach ($roles as $role) {
            if (Role::where('name', data_get($role, 'value'))->exists()) {
                $this->info("Role {$role['value']} already exists.");
                continue;
            }

            Role::create([
                'name' => data_get($role, 'value'),
                'guard_name' => 'api',
                'description' => data_get($role, 'description')
            ]);
        }
    }

    public function createSuperAdminUser(): void
    {
        $user = User::firstOrCreate([
            'name' => 'Super Admin',
            'email' => 'super.user@exemplo.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'deleted_at' => null,
        ]);

        $roles = array_map(
            fn($role) => data_get($role, 'value'),
            RolesEnum::labels()
        );

        $user->assignRole($roles);
    }
}
