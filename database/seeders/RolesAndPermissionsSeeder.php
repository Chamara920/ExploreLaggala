<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // Blog
            'view_blog',
            'create_blog',
            'edit_blog',
            'delete_blog',
            'approve_blog',
            'publish_blog',

            // News
            'view_news',
            'create_news',
            'edit_news',
            'delete_news',
            'approve_news',
            'publish_news',

            // Events
            'view_events',
            'create_events',
            'edit_events',
            'delete_events',
            'approve_events',
            'publish_events',

            // Community Organizations
            'view_organizations',
            'create_organizations',
            'edit_organizations',
            'delete_organizations',
            'approve_organizations',
            'publish_organizations',

            // Forum
            'view_forum',
            'create_forum_topic',
            'edit_forum_topic',
            'delete_forum_topic',
            'moderate_forum',

            // Destinations
            'manage_destinations',

            // Institutions
            'manage_institutions',
            'manage_officers',

            // Emergency
            'manage_emergency_contacts',

            // Services
            'manage_services',

            // Homepage
            'manage_homepage',

            // Users
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Roles & Permissions
            'manage_roles',
            'manage_permissions',

            // System
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $communityUser = Role::firstOrCreate([
            'name' => 'community_user',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Community User Permissions
        |--------------------------------------------------------------------------
        */

        $communityUser->syncPermissions([
            'view_blog',
            'create_blog',
            'edit_blog',

            'view_news',
            'create_news',
            'edit_news',

            'view_events',
            'create_events',
            'edit_events',

            'view_organizations',
            'create_organizations',
            'edit_organizations',

            'view_forum',
            'create_forum_topic',
            'edit_forum_topic',
            'delete_forum_topic',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Admin Permissions
        |--------------------------------------------------------------------------
        */

        $admin->syncPermissions([

            // Blog
            'view_blog',
            'create_blog',
            'edit_blog',
            'delete_blog',
            'approve_blog',
            'publish_blog',

            // News
            'view_news',
            'create_news',
            'edit_news',
            'delete_news',
            'approve_news',
            'publish_news',

            // Events
            'view_events',
            'create_events',
            'edit_events',
            'delete_events',
            'approve_events',
            'publish_events',

            // Community Organizations
            'view_organizations',
            'create_organizations',
            'edit_organizations',
            'delete_organizations',
            'approve_organizations',
            'publish_organizations',

            // Forum
            'view_forum',
            'moderate_forum',

            // Main content
            'manage_destinations',
            'manage_institutions',
            'manage_officers',
            'manage_emergency_contacts',
            'manage_services',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        $superAdmin->syncPermissions(
            Permission::all()
        );

        // Clear cache again
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}