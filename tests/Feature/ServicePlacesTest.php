<?php

use App\Models\ServicePlace;
use App\Models\ServicePlaceReview;
use App\Models\User;
use Database\Seeders\InitialUsersSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\ServicePlacesSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(InitialUsersSeeder::class);
    $this->seed(ServicePlacesSeeder::class);
});

test('all five service section directory pages are accessible in English and Sinhala', function (string $section, string $expectedEn, string $expectedSi) {
    // English
    $responseEn = $this->get("/services/{$section}");
    $responseEn->assertStatus(200);
    $responseEn->assertSee($expectedEn, false);

    // Sinhala
    $responseSi = $this->withSession(['locale' => 'si'])->get("/services/{$section}");
    $responseSi->assertStatus(200);
    $responseSi->assertSee($expectedSi, false);
})->with([
    ['health', 'Health Services', 'සෞඛ්‍ය සේවාවන්'],
    ['shops-businesses', 'Shops &amp; Businesses', 'වෙළඳසැල් සහ ව්‍යාපාර'],
    ['banks-atms', 'Banks &amp; ATMs', 'බැංකු සහ ස්වයංක්‍රීය ටෙලර් යන්ත්‍ර'],
    ['fuel-ev', 'Fuel &amp; EV Charging', 'ඉන්ධන සහ විදුලි ආරෝපණ'],
    ['education', 'Education', 'අධ්‍යාපන ආයතන'],
]);

test('service place single detail page renders profile, facilities, and map coordinates', function () {
    $place = ServicePlace::where('section', 'health')->first();
    expect($place)->not->toBeNull();

    $transSi = $place->translationFor('si');
    expect($transSi)->not->toBeNull();

    $response = $this->withSession(['locale' => 'si'])->get("/services/health/{$transSi->slug}");
    $response->assertStatus(200);

    // Profile details
    $response->assertSee($transSi->name, false);
    $response->assertSee($transSi->location_name, false);
    $response->assertSee('හදිසි ප්‍රතිකාර ඒකකය', false);
    $response->assertSee('singlePlaceMap', false); // Leaflet map container
});

test('logged in user can submit a review for a service place with pending approval', function () {
    $user = User::factory()->create();
    $place = ServicePlace::where('section', 'banks-atms')->first();

    $response = $this->actingAs($user)->post(route('services.places.reviews.store', $place->id), [
        'rating' => 4,
        'comment' => 'Fast cash withdrawals and smooth transactions at Pallegama.',
    ]);

    $response->assertSessionHas('review_success');

    $review = ServicePlaceReview::where('service_place_id', $place->id)
        ->where('user_id', $user->id)
        ->first();

    expect($review)->not->toBeNull();
    expect($review->rating)->toBe(4);
    expect($review->status)->toBe('pending'); // Admin moderation required
});

test('admin review is auto-approved and admin can delete review', function () {
    $admin = User::role('admin')->first() ?? User::factory()->create();
    if (! $admin->hasRole('admin')) {
        $admin->assignRole('admin');
    }

    $place = ServicePlace::where('section', 'fuel-ev')->first();

    // 1. Admin review auto-approved
    $this->actingAs($admin)->post(route('services.places.reviews.store', $place->id), [
        'rating' => 5,
        'comment' => '24 hours fuel available with generator backup.',
    ]);

    $review = ServicePlaceReview::where('service_place_id', $place->id)
        ->where('user_id', $admin->id)
        ->first();

    expect($review)->not->toBeNull();
    expect($review->status)->toBe('approved');

    // 2. Admin can delete
    $delResponse = $this->actingAs($admin)->delete(route('services.places.reviews.destroy', $review->id));
    $delResponse->assertSessionHas('review_deleted');
    expect(ServicePlaceReview::find($review->id))->toBeNull();
});

test('community user cannot delete a review and receives 403', function () {
    $regularUser = User::factory()->create();
    $review = ServicePlaceReview::first();
    expect($review)->not->toBeNull();

    $response = $this->actingAs($regularUser)->delete(route('services.places.reviews.destroy', $review->id));
    $response->assertStatus(403);
});

test('community user cannot access filament admin for service places or reviews', function () {
    $regularUser = User::factory()->create();

    $this->actingAs($regularUser)
        ->get('/admin/service-places')
        ->assertStatus(403);

    $this->actingAs($regularUser)
        ->get('/admin/service-place-reviews')
        ->assertStatus(403);
});
