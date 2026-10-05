<?php

use App\Models\ExploreItem;
use App\Models\ExploreItemReview;
use App\Models\User;
use Database\Seeders\ExploreDataSeeder;
use Database\Seeders\InitialUsersSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(InitialUsersSeeder::class);
    $this->seed(ExploreDataSeeder::class);
});

test('culture and heritage index page is accessible in English and Sinhala', function () {
    $responseEn = $this->get('/explore/culture-heritage');
    $responseEn->assertStatus(200);
    $responseEn->assertSee('Lakegala', false);

    $responseSi = $this->withSession(['locale' => 'si'])->get('/explore/culture-heritage');
    $responseSi->assertStatus(200);
    $responseSi->assertSee('ලකේගල', false);
});

test('culture and heritage detail page is accessible with multilingual translations', function () {
    $responseEn = $this->get('/explore/culture-heritage/lakegala-legend-and-rock');
    $responseEn->assertStatus(200);
    $responseEn->assertSee('Lakegala', false);

    $responseSi = $this->withSession(['locale' => 'si'])->get('/explore/culture-heritage/lakegala-legend-and-rock');
    $responseSi->assertStatus(200);
    $responseSi->assertSee('ලකේගල', false);
});

test('outdoor and adventure index page is accessible in English and Sinhala', function () {
    $responseEn = $this->get('/explore/outdoor-adventure');
    $responseEn->assertStatus(200);
    $responseEn->assertSee('Pitawala', false);

    $responseSi = $this->withSession(['locale' => 'si'])->get('/explore/outdoor-adventure');
    $responseSi->assertStatus(200);
    $responseSi->assertSee('පිටවල පතන', false);
});

test('outdoor and adventure detail page is accessible with multilingual translations', function () {
    $responseEn = $this->get('/explore/outdoor-adventure/pitawala-pathana-mini-worlds-end');
    $responseEn->assertStatus(200);
    $responseEn->assertSee('Pitawala', false);

    $responseSi = $this->withSession(['locale' => 'si'])->get('/explore/outdoor-adventure/pitawala-pathana-mini-worlds-end');
    $responseSi->assertStatus(200);
    $responseSi->assertSee('පිටවල පතන', false);
});

test('authenticated user can submit review for explore item with pending status', function () {
    $user = User::factory()->create();
    $item = ExploreItem::where('type', 'culture-heritage')->first();

    $response = $this->actingAs($user)->post(route('explore.reviews.store', $item->id), [
        'rating' => 4,
        'comment' => 'Beautiful cultural spot, highly recommended!',
    ]);

    $response->assertSessionHas('review_success');

    $this->assertDatabaseHas('explore_item_reviews', [
        'explore_item_id' => $item->id,
        'user_id' => $user->id,
        'rating' => 4,
        'status' => 'pending',
    ]);
});

test('admin can delete review', function () {
    $admin = User::first() ?? User::factory()->create();
    if (! $admin->hasRole('admin')) {
        $admin->assignRole('admin');
    }

    $review = ExploreItemReview::first();
    expect($review)->not->toBeNull();

    $response = $this->actingAs($admin)->delete(route('explore.reviews.destroy', $review->id));

    $response->assertSessionHas('review_deleted');
    $this->assertDatabaseMissing('explore_item_reviews', [
        'id' => $review->id,
    ]);
});
