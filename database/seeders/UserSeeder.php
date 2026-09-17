<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        $adminRole      = Role::where('slug', 'admin')->first();
        $managerRole    = Role::where('slug', 'manager')->first();

        User::firstOrCreate(
            ['email' => 'superadmin@nexaerp.com'],
            [
                'name'      => 'Super Admin',
                'password'  => Hash::make('Admin@123'),
                'role_id'   => $superAdminRole->id,
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name'      => 'Demo User',
                'password'  => Hash::make('Demo@123'),
                'role_id'   => $adminRole->id,
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'manager@nexaerp.com'],
            [
                'name'      => 'John Manager',
                'password'  => Hash::make('Admin@123'),
                'role_id'   => $managerRole->id,
                'is_active' => true,
            ]
        );
    }
}