<?php

namespace Tests\Feature;

use App\Models\CommunityOrganization;
use App\Models\OrganizationType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CommunityOrganizationAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_organization_type_relationship_exists_on_model(): void
    {
        $type = OrganizationType::create([
            'name' => 'Youth Club',
            'slug' => 'youth-club',
            'is_active' => true,
        ]);

        $user = User::factory()->create();

        $org = CommunityOrganization::create([
            'user_id' => $user->id,
            'type_id' => $type->id,
            'status' => 'draft',
        ]);

        $this->assertNotNull($org->organizationType);
        $this->assertEquals('Youth Club', $org->organizationType->name);
        $this->assertNotNull($org->type);
        $this->assertEquals('Youth Club', $org->type->name);
    }

    public function test_admin_can_access_create_community_organization_page(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $type = OrganizationType::create([
            'name' => 'Cooperative',
            'slug' => 'cooperative',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/community-organizations/create');

        $response->assertSuccessful();
    }

    public function test_admin_can_create_community_organization(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $type = OrganizationType::create([
            'name' => 'Welfare Society',
            'slug' => 'welfare-society',
            'is_active' => true,
        ]);

        \Livewire\Livewire::actingAs($admin)
            ->test(\App\Filament\Resources\CommunityOrganizations\Pages\CreateCommunityOrganization::class)
            ->fillForm([
                'type_id' => $type->id,
                'status' => 'draft',
                'phone' => '0712345678',
                'email' => 'info@welfare.org',
                'website' => 'https://welfare.org',
                'facebook_url' => 'https://facebook.com/welfare',
                'translations' => [
                    [
                        'locale' => 'si',
                        'name' => 'සුබසාධක සමිතිය',
                        'slug' => 'subasadhaka-samithiya',
                    ],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('community_organizations', [
            'type_id' => $type->id,
            'phone' => '0712345678',
            'email' => 'info@welfare.org',
            'facebook_url' => 'https://facebook.com/welfare',
        ]);
    }
}
