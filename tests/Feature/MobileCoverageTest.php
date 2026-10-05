<?php

use App\Models\MobileCoverageReport;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->user = User::factory()->create();
    $this->user->assignRole('community_user');

    // Create a regular coverage report
    $this->report1 = MobileCoverageReport::create([
        'location_name' => 'Riverston Gap Summit',
        'latitude' => 7.5285,
        'longitude' => 80.7315,
        'network_operator' => 'dialog',
        'coverage_type' => '4g',
        'signal_strength' => 'good',
        'description' => 'Tested on summit.',
        'status' => 'approved',
        'reported_by' => $this->user->id,
        'reported_at' => now(),
    ]);

    // Create a dead zone report
    $this->report2 = MobileCoverageReport::create([
        'location_name' => 'Lakegala Deep Jungle',
        'latitude' => 7.4812,
        'longitude' => 80.8354,
        'network_operator' => 'multiple',
        'coverage_type' => 'no_signal',
        'signal_strength' => 'none',
        'description' => 'Dead zone warning.',
        'status' => 'approved',
        'reported_by' => $this->user->id,
        'reported_at' => now(),
    ]);
});

test('mobile coverage index page renders in english without latitude array error', function () {
    $response = $this->get(route('plan.coverage.index', ['lang' => 'en']));

    $response->assertStatus(200);
    $response->assertSee('Mobile Network Coverage in Laggala');
    $response->assertSee('Interactive Signal Coverage Map');
    $response->assertSee('Riverston Gap Summit');
    $response->assertSee('coverageMap');
    $response->assertDontSee('Attempt to read property "latitude" on array');
});

test('mobile coverage index page renders in sinhala with full translations', function () {
    $response = $this->get(route('plan.coverage.index', ['lang' => 'si']));

    $response->assertStatus(200);
    $response->assertSee('ලග්ගල ජංගම දුරකථන ආවරණ තත්ත්වය');
    $response->assertSee('අන්තර්ක්‍රියාකාරී සිග්නල් ආවරණ සිතියම');
    $response->assertSee('චාරිකාව සැලසුම් කරන්න');
});

test('mobile coverage index page renders in tamil with full translations', function () {
    $response = $this->get(route('plan.coverage.index', ['lang' => 'ta']));

    $response->assertStatus(200);
    $response->assertSee('லக்கலவில் மொபைல் நெட்வொர்க் கவரேஜ்');
    $response->assertSee('ஊடாடும் சிக்னல் கவரேஜ் வரைபடம்');
});

test('mobile coverage map data includes coordinates, operators, and dead zone flags', function () {
    $response = $this->get(route('plan.coverage.index', ['lang' => 'en']));

    $response->assertStatus(200);
    $response->assertViewHas('mapData', function ($mapData) {
        return $mapData->isNotEmpty() &&
            $mapData->first(fn ($item) => isset($item['latitude']) && isset($item['longitude'])) !== null &&
            $mapData->contains(fn ($item) => $item['is_dead_zone'] === true);
    });
});

test('community user can submit mobile coverage report and it appears immediately approved', function () {
    $user = User::factory()->create();
    $user->assignRole('community_user');

    $postData = [
        'location_name' => 'Pitawala Pathana North Ridge',
        'network_operator' => 'dialog',
        'coverage_type' => '4g',
        'signal_strength' => 'good',
        'description' => 'Tested near the high viewpoint; good streaming signal.',
        'latitude' => 7.5890,
        'longitude' => 80.7560,
    ];

    $response = $this->actingAs($user)->post(route('plan.coverage.store'), $postData);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $report = MobileCoverageReport::where('location_name', 'Pitawala Pathana North Ridge')->first();
    expect($report)->not->toBeNull();
    expect($report->status)->toBe('approved');
    expect((float) $report->latitude)->toBe(7.5890);
    expect($report->reported_by)->toBe($user->id);

    // Verify it is visible on the public page
    $publicPage = $this->get(route('plan.coverage.index', ['lang' => 'en']));
    $publicPage->assertStatus(200);
    $publicPage->assertSee('Pitawala Pathana North Ridge');
});

test('community user can update their mobile coverage report', function () {
    $user = User::factory()->create();
    $user->assignRole('community_user');

    $report = MobileCoverageReport::create([
        'location_name' => 'Initial Spot',
        'network_operator' => 'mobitel',
        'coverage_type' => '2g',
        'signal_strength' => 'poor',
        'description' => 'Original report',
        'latitude' => 7.5500,
        'longitude' => 80.7300,
        'reported_by' => $user->id,
        'status' => 'approved',
        'reported_at' => now(),
    ]);

    $updateData = [
        'location_name' => 'Updated Spot with 4G Tower',
        'network_operator' => 'mobitel',
        'coverage_type' => '4g',
        'signal_strength' => 'good',
        'description' => 'New antenna installed here recently.',
        'latitude' => 7.5505,
        'longitude' => 80.7305,
    ];

    $response = $this->actingAs($user)->put(route('plan.coverage.update', $report), $updateData);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $report->refresh();
    expect($report->location_name)->toBe('Updated Spot with 4G Tower');
    expect($report->coverage_type)->toBe('4g');
    expect($report->signal_strength)->toBe('good');
});

test('community user can delete their mobile coverage report', function () {
    $user = User::factory()->create();
    $user->assignRole('community_user');

    $report = MobileCoverageReport::create([
        'location_name' => 'Temporary Obsolete Report',
        'network_operator' => 'hutch',
        'coverage_type' => '2g',
        'signal_strength' => 'fair',
        'latitude' => 7.5600,
        'longitude' => 80.7400,
        'reported_by' => $user->id,
        'status' => 'approved',
        'reported_at' => now(),
    ]);

    $response = $this->actingAs($user)->delete(route('plan.coverage.destroy', $report));

    $response->assertRedirect();
    $response->assertSessionHas('success');
    expect(MobileCoverageReport::find($report->id))->toBeNull();
});

test('guest cannot submit coverage report', function () {
    $postData = [
        'location_name' => 'Unauthorized Spot',
        'network_operator' => 'dialog',
        'coverage_type' => '4g',
        'signal_strength' => 'good',
    ];

    $response = $this->post(route('plan.coverage.store'), $postData);
    $response->assertRedirect(route('login'));
});
