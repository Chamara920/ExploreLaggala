<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialUsersSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        $superAdmin = User::firstOrCreate(
            [
                'email' => env(
                    'SUPER_ADMIN_EMAIL',
                    'superadmin@example.com'
                ),
            ],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make(
                    env('SUPER_ADMIN_PASSWORD', 'ChangeThisPassword123!')
                ),
                'email_verified_at' => now(),
            ]
        );

        $superAdmin->syncRoles(['super_admin']);

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        $admin = User::firstOrCreate(
            [
                'email' => env(
                    'ADMIN_EMAIL',
                    'admin@example.com'
                ),
            ],
            [
                'name' => 'System Administrator',
                'password' => Hash::make(
                    env('ADMIN_PASSWORD', 'ChangeThisPassword123!')
                ),
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['admin']);

        /*
        |--------------------------------------------------------------------------
        | Community User
        |--------------------------------------------------------------------------
        */

        $communityUser = User::firstOrCreate(
            [
                'email' => env(
                    'COMMUNITY_EMAIL',
                    'community@example.com'
                ),
            ],
            [
                'name' => 'Community User',
                'password' => Hash::make(
                    env('COMMUNITY_PASSWORD', 'ChangeThisPassword123!')
                ),
                'email_verified_at' => now(),
            ]
        );

        $communityUser->syncRoles(['community_user']);
    }
}