<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Full system access'],
            ['name' => 'Admin',       'slug' => 'admin',       'description' => 'Administrative access'],
            ['name' => 'Manager',     'slug' => 'manager',     'description' => 'Team management access'],
            ['name' => 'Employee',    'slug' => 'employee',    'description' => 'Basic access'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}