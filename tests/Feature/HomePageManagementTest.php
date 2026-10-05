<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\HomePageSetting;
use App\Models\HomeSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HomePageManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'community_user', 'guard_name' => 'web']);
    }

    public function test_guests_cannot_access_home_management_admin_routes(): void
    {
        $this->get('/admin/home-slides')
            ->assertRedirect('/admin/login');

        $this->get('/admin/home-page-settings')
            ->assertRedirect('/admin/login');
    }

    public function test_regular_admin_is_forbidden_from_home_management(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->get('/admin/home-slides')
            ->assertForbidden();

        $this->actingAs($admin)
            ->get('/admin/home-page-settings')
            ->assertForbidden();
    }

    public function test_super_admin_can_access_home_slides_and_settings(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $this->actingAs($superAdmin)
            ->get('/admin/home-slides')
            ->assertSuccessful();

        $setting = HomePageSetting::firstOrCreate(['id' => 1]);

        $this->actingAs($superAdmin)
            ->get('/admin/home-page-settings/'.$setting->id.'/edit')
            ->assertSuccessful();
    }

    public function test_welcome_page_renders_with_multilingual_home_slides(): void
    {
        $slide = HomeSlide::create([
            'image_url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb',
            'is_active' => true,
            'sort_order' => 1,
            'primary_button_url' => '/explore/destinations',
        ]);

        $slide->translations()->create([
            'locale' => 'si',
            'badge' => 'ලග්ගල අසිරිය',
            'title' => 'ලග්ගල වෙත ඔබව සාදරයෙන් පිළිගනිමු',
            'subtitle' => 'සුන්දර කඳුකර පරිසරය',
            'primary_button_text' => 'ස්ථාන බලන්න',
        ]);

        $slide->translations()->create([
            'locale' => 'en',
            'badge' => 'Laggala Beauty',
            'title' => 'Welcome to Scenic Laggala',
            'subtitle' => 'Breathtaking central highlands',
            'primary_button_text' => 'Explore Spots',
        ]);

        // Test English locale
        $responseEn = $this->withSession(['locale' => 'en'])->get('/');
        $responseEn->assertSuccessful();
        $responseEn->assertSee('Welcome to Scenic Laggala');
        $responseEn->assertSee('Laggala Beauty');

        // Test Sinhala locale
        $responseSi = $this->withSession(['locale' => 'si'])->get('/');
        $responseSi->assertSuccessful();
        $responseSi->assertSee('ලග්ගල වෙත ඔබව සාදරයෙන් පිළිගනිමු');
        $responseSi->assertSee('ලග්ගල අසිරිය');
    }

    public function test_welcome_page_respects_featured_settings_selection(): void
    {
        $user = User::factory()->create();

        $dest1 = Destination::create(['status' => 'published', 'featured' => false, 'sort_order' => 1]);
        $dest1->translations()->create(['locale' => 'en', 'name' => 'Selected Destination Alpha', 'slug' => 'alpha']);

        $dest2 = Destination::create(['status' => 'published', 'featured' => false, 'sort_order' => 2]);
        $dest2->translations()->create(['locale' => 'en', 'name' => 'Selected Destination Beta', 'slug' => 'beta']);

        $setting = HomePageSetting::updateOrCreate(
            ['id' => 1],
            [
                'featured_destination_ids' => [$dest2->id, $dest1->id],
                'destinations_count' => 6,
            ]
        );

        $setting->translations()->create([
            'locale' => 'en',
            'destinations_title' => 'Curated Wonders of Laggala',
        ]);

        $response = $this->withSession(['locale' => 'en'])->get('/');
        $response->assertSuccessful();
        $response->assertSee('Curated Wonders of Laggala');
        $response->assertSee('Selected Destination Beta');
        $response->assertSee('Selected Destination Alpha');
    }

    public function test_home_page_multilingual_translations_and_slide_image(): void
    {
        $slide = HomeSlide::create([
            'image_path' => 'home-slides/sample-banner.jpg',
            'is_active' => true,
            'sort_order' => 1,
            'primary_button_url' => '/explore/destinations',
        ]);
        $slide->translations()->create([
            'locale' => 'si',
            'title' => 'ලග්ගල සංචාරක අසිරිය',
            'badge' => 'නකල්ස් කලාපය',
        ]);
        $slide->translations()->create([
            'locale' => 'en',
            'title' => 'Explore Scenic Laggala',
            'badge' => 'Knuckles Range',
        ]);
        $slide->translations()->create([
            'locale' => 'ta',
            'title' => 'இயற்கை எழில் கொஞ்சும் லக்கலா',
            'badge' => 'நக்கிள்ஸ் பகுதி',
        ]);

        // Test Sinhala
        $resSi = $this->get('/?lang=si');
        $resSi->assertSuccessful();
        $resSi->assertSee('ලග්ගල සංචාරක අසිරිය');
        $resSi->assertSee('සංචාරක ස්ථාන');
        $resSi->assertSee('ගවේෂණය');
        $resSi->assertSee('home-slides/sample-banner.jpg');

        // Test English
        $resEn = $this->get('/?lang=en');
        $resEn->assertSuccessful();
        $resEn->assertSee('Explore Scenic Laggala');
        $resEn->assertSee('Destinations');
        $resEn->assertSee('Explore');

        // Test Tamil
        $resTa = $this->get('/?lang=ta');
        $resTa->assertSuccessful();
        $resTa->assertSee('இயற்கை எழில் கொஞ்சும் லக்கலா');
        $resTa->assertSee('சுற்றுலா தலங்கள்');
        $resTa->assertSee('ஆராயுங்கள்');
    }
}
