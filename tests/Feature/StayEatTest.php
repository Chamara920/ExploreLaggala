<?php

use App\Models\Accommodation;
use App\Models\LocalFood;
use App\Models\OutdoorDining;
use App\Models\RestaurantCafe;
use App\Models\StayEatItem;
use App\Models\StayEatItemReview;
use App\Models\User;
use Database\Seeders\InitialUsersSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\StayEatDataSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(InitialUsersSeeder::class);
    $this->seed(StayEatDataSeeder::class);
});

test('stay and eat hub index page is accessible in English and Sinhala', function () {
    $responseEn = $this->get('/stay-eat');
    $responseEn->assertStatus(200);
    $responseEn->assertSee('Stay &amp; Eat in Laggala', false);

    $responseSi = $this->withSession(['locale' => 'si'])->get('/stay-eat');
    $responseSi->assertStatus(200);
    $responseSi->assertSee('ලග්ගල නවාතැන් සහ ආහාරපාන', false);
});

test('all four stay and eat section pages are accessible', function (string $section, string $expectedTextEn, string $expectedTextSi) {
    $responseEn = $this->get("/stay-eat/{$section}");
    $responseEn->assertStatus(200);
    $responseEn->assertSee($expectedTextEn, false);

    $responseSi = $this->withSession(['locale' => 'si'])->get("/stay-eat/{$section}");
    $responseSi->assertStatus(200);
    $responseSi->assertSee($expectedTextSi, false);
})->with([
    ['accommodation', 'Accommodation', 'නවාතැන් පහසුකම්'],
    ['restaurants-cafes', 'Restaurants &amp; Cafés', 'අවන්හල් සහ කැෆේ'],
    ['local-food', 'Local Food', 'දේශීය ආහාර'],
    ['outdoor-dining', 'Outdoor Dining &amp; Catering', 'එළිමහන් ආහාර'],
]);

test('stay and eat detail page is accessible with multilingual translations and map coordinates', function () {
    $item = StayEatItem::where('section', 'accommodation')->first();
    expect($item)->not->toBeNull();

    $transEn = $item->translationFor('en');
    $transSi = $item->translationFor('si');

    $responseEn = $this->get("/stay-eat/accommodation/{$transEn->slug}");
    $responseEn->assertStatus(200);
    $responseEn->assertSee($transEn->title, false);
    $responseEn->assertSee('placeMap', false); // Leaflet map container exists

    $responseSi = $this->withSession(['locale' => 'si'])->get("/stay-eat/accommodation/{$transSi->slug}");
    $responseSi->assertStatus(200);
    $responseSi->assertSee($transSi->title, false);
});

test('authenticated user can submit review for stay and eat place with pending status', function () {
    $user = User::factory()->create();
    $item = StayEatItem::first();

    $response = $this->actingAs($user)->post(route('stay-eat.reviews.store', $item->id), [
        'rating' => 5,
        'comment' => 'Wonderful place and hospitable hosts in Knuckles!',
    ]);

    $response->assertSessionHas('review_success');

    $this->assertDatabaseHas('stay_eat_item_reviews', [
        'stay_eat_item_id' => $item->id,
        'user_id' => $user->id,
        'rating' => 5,
        'status' => 'pending',
    ]);
});

test('admin can delete review but community user cannot', function () {
    $admin = User::role('admin')->first() ?? User::factory()->create();
    if (! $admin->hasRole('admin')) {
        $admin->assignRole('admin');
    }

    $communityUser = User::factory()->create();

    $review = StayEatItemReview::first();
    expect($review)->not->toBeNull();

    // 1. Community user attempt should fail with 403
    $unauthResponse = $this->actingAs($communityUser)->delete(route('stay-eat.reviews.destroy', $review->id));
    $unauthResponse->assertStatus(403);
    $this->assertDatabaseHas('stay_eat_item_reviews', ['id' => $review->id]);

    // 2. Admin can delete
    $adminResponse = $this->actingAs($admin)->delete(route('stay-eat.reviews.destroy', $review->id));
    $adminResponse->assertSessionHas('review_deleted');
    $this->assertDatabaseMissing('stay_eat_item_reviews', ['id' => $review->id]);
});

test('subclass models have correct relationships and can load images and reviews count', function () {
    $restaurantCount = RestaurantCafe::withCount(['images', 'reviews'])->get();
    expect($restaurantCount)->not->toBeEmpty();

    $accommodationCount = Accommodation::withCount(['images', 'reviews'])->get();
    expect($accommodationCount)->not->toBeEmpty();

    $localFoodCount = LocalFood::withCount(['images', 'reviews'])->get();
    expect($localFoodCount)->not->toBeEmpty();

    $outdoorDiningCount = OutdoorDining::withCount(['images', 'reviews'])->get();
    expect($outdoorDiningCount)->not->toBeEmpty();
});

test('admin can access filament stay and eat list pages without sql exceptions', function (string $url) {
    $admin = User::role('admin')->first() ?? User::factory()->create();
    if (! $admin->hasRole('admin')) {
        $admin->assignRole('admin');
    }

    $response = $this->actingAs($admin)->get($url);
    $response->assertStatus(200);
})->with([
    '/admin/accommodations',
    '/admin/restaurant-cafes',
    '/admin/local-foods',
    '/admin/outdoor-dinings',
    '/admin/stay-eat-reviews',
]);
