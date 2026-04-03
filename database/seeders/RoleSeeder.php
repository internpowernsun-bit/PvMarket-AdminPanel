<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'role'       => 'Super Admin',
                'slug'       => 'super-admin',
                'guard_name' => 'web',
            ],
            [
                'role'       => 'Admin',
                'slug'       => 'admin',
                'guard_name' => 'web',
            ],
        ];

        foreach ($roles as $role) {
            if (! Role::where('slug', $role['slug'])->exists()) {
                Role::create($role);
                $this->command->info("Role '{$role['role']}' created.");
            } else {
                $this->command->warn("Role '{$role['role']}' already exists — skipped.");
            }
        }
    }
}