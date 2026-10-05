<?php

use App\Models\Institution;
use App\Models\InstitutionReview;
use App\Models\User;
use Database\Seeders\GovernmentInstitutionSeeder;
use Database\Seeders\InitialUsersSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(InitialUsersSeeder::class);
    $this->seed(GovernmentInstitutionSeeder::class);
});

test('government institutions index directory is accessible in English and Sinhala', function () {
    $responseEn = $this->get('/services/government-institutions');
    $responseEn->assertStatus(200);
    $responseEn->assertSee('Government Institutions', false);

    $responseSi = $this->withSession(['locale' => 'si'])->get('/services/government-institutions');
    $responseSi->assertStatus(200);
    $responseSi->assertSee('රාජ්‍ය ආයතන හා සේවාවන්', false);
    $responseSi->assertSee('ලග්ගල - පල්ලේගම ප්‍රාදේශීය ලේකම් කාර්යාලය', false);
});

test('government institutions shortcut URL /services/institutions is also working', function () {
    $response = $this->get('/services/institutions');
    $response->assertStatus(200);
    $response->assertSee('Government Institutions', false);
});

test('government institutions single profile page renders hybrid sections, units, services and officers', function () {
    $inst = Institution::where('type', 'divisional_secretariat')->first();
    expect($inst)->not->toBeNull();

    $transSi = $inst->translationFor('si');
    expect($transSi)->not->toBeNull();

    $response = $this->withSession(['locale' => 'si'])->get("/services/government-institutions/{$transSi->slug}");
    $response->assertStatus(200);

    // Profile info
    $response->assertSee($transSi->name, false);
    $response->assertSee($transSi->office_hours, false);

    // Units
    $response->assertSee('පරිපාලන අංශය', false);
    $response->assertSee('ඉඩම් අංශය', false);

    // Services
    $response->assertSee('ජාතික හැඳුනුම්පත් (NIC) සඳහා අයදුම්පත් සහතික කිරීම', false);

    // Officers
    $response->assertSee('කේ. එම්. බණ්ඩාරනායක මයා', false);
    $response->assertSee('ප්‍රාදේශීය ලේකම්', false);

    // Custom Citizen Charter section table
    $response->assertSee('ප්‍රධාන මහජන සේවා ප්‍රඥප්තිය (Citizen Charter)', false);

    // Leaflet Map element exists
    $response->assertSee('singleInstitutionMap', false);
});

test('logged in citizen user can submit a review and rating which remains pending approval', function () {
    $user = User::factory()->create();
    $inst = Institution::first();

    $response = $this->actingAs($user)->post(route('services.institutions.reviews.store', $inst->id), [
        'rating' => 4,
        'comment' => 'Very responsive staff and helpful public counters.',
    ]);

    $response->assertSessionHas('review_success');

    $review = InstitutionReview::where('institution_id', $inst->id)
        ->where('user_id', $user->id)
        ->first();

    expect($review)->not->toBeNull();
    expect($review->rating)->toBe(4);
    expect($review->status)->toBe('pending'); // Requires admin moderation
});

test('admin user review is automatically approved and visible publicly', function () {
    $admin = User::role('admin')->first() ?? User::factory()->create();
    if (! $admin->hasRole('admin')) {
        $admin->assignRole('admin');
    }

    $inst = Institution::first();

    $this->actingAs($admin)->post(route('services.institutions.reviews.store', $inst->id), [
        'rating' => 5,
        'comment' => 'Official administrative feedback verified.',
    ]);

    $review = InstitutionReview::where('institution_id', $inst->id)
        ->where('user_id', $admin->id)
        ->first();

    expect($review->status)->toBe('approved');
});

test('admin can delete review directly from the public page', function () {
    $admin = User::role('admin')->first() ?? User::factory()->create();
    if (! $admin->hasRole('admin')) {
        $admin->assignRole('admin');
    }

    $review = InstitutionReview::first();
    expect($review)->not->toBeNull();

    $response = $this->actingAs($admin)->delete(route('services.institutions.reviews.destroy', $review->id));
    $response->assertSessionHas('review_deleted');

    expect(InstitutionReview::find($review->id))->toBeNull();
});

test('regular community user cannot delete a review', function () {
    $regularUser = User::factory()->create();
    $review = InstitutionReview::first();

    $response = $this->actingAs($regularUser)->delete(route('services.institutions.reviews.destroy', $review->id));
    $response->assertStatus(403);
});

test('community user cannot access filament admin for institutions or reviews', function () {
    $regularUser = User::factory()->create();

    $this->actingAs($regularUser)
        ->get('/admin/institutions')
        ->assertStatus(403);

    $this->actingAs($regularUser)
        ->get('/admin/institution-reviews')
        ->assertStatus(403);
});
