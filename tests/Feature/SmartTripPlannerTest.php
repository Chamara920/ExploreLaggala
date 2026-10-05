<?php

use App\Models\Interest;
use App\Models\PublicTransport;
use App\Models\SafetyAlert;
use App\Models\User;
use App\Models\WeatherObservation;

test('smart trip planner index page renders successfully in english with public and private bus options', function () {
    $response = $this->get(route('plan.smart-trip-planner.index', ['lang' => 'en']));

    $response->assertStatus(200);
    $response->assertSee('Smart Trip Planner');
    $response->assertSee('Regional Weather');
    $response->assertSee('Road &amp; Safety Status', false);
    $response->assertSee('1 Day Tour');
    $response->assertSee('2 Days Tour');
    $response->assertSee('3 Days Tour');
    $response->assertSee('Public Bus');
    $response->assertSee('Private Bus / Coach');
});

test('smart trip planner index page renders successfully in sinhala with public and private bus options', function () {
    $response = $this->get(route('plan.smart-trip-planner.index', ['lang' => 'si']));

    $response->assertStatus(200);
    $response->assertSee('ස්මාර්ට් චාරිකා සැලසුම්කරු');
    $response->assertSee('ප්‍රාදේශීය කාලගුණය');
    $response->assertSee('මාර්ග සහ ආරක්ෂණ තත්ත්වය');
    $response->assertSee('දින 1 චාරිකාව');
    $response->assertSee('දින 2 චාරිකාව');
    $response->assertSee('දින 3 චාරිකාව');
    $response->assertSee('පොදු බස් රථය');
    $response->assertSee('පුද්ගලික බස් රථය');
});

test('smart trip planner index page renders successfully in tamil with public and private bus options', function () {
    $response = $this->get(route('plan.smart-trip-planner.index', ['lang' => 'ta']));

    $response->assertStatus(200);
    $response->assertSee('ஸ்மார்ட் பயணத் திட்டமிடுபவர்');
    $response->assertSee('பிராந்திய வானிலை');
    $response->assertSee('1 நாள் பயணம்');
    $response->assertSee('2 நாட்கள் பயணம்');
    $response->assertSee('3 நாட்கள் பயணம்');
    $response->assertSee('பொதுப் பேருந்து');
    $response->assertSee('தனியார் பேருந்து');
});

test('smart trip planner legacy alias route also renders successfully', function () {
    $response = $this->get(route('plan.smart-planner.index'));

    $response->assertStatus(200);
});

test('smart trip planner generates 1-day route with distances, weather, and maps link', function () {
    $interest = Interest::first() ?? Interest::create([
        'name' => 'Nature',
        'slug' => 'nature',
        'is_active' => true,
    ]);

    $postData = [
        'trip_days' => 1,
        'travel_date' => now()->format('Y-m-d'),
        'start_time' => '08:30',
        'available_time' => '8',
        'interest_ids' => [$interest->id],
        'start_location_name' => 'Matale Bus Stand',
        'start_latitude' => 7.4675,
        'start_longitude' => 80.6234,
        'travel_mode' => 'private_vehicle',
        'include_meal_stop' => true,
        'include_accommodation' => true,
    ];

    $response = $this->post(route('plan.smart-trip-planner.generate'), $postData);

    $response->assertStatus(200);
    $response->assertSee('Total Distance');
    $response->assertSee('Google Maps');
    $response->assertSee('OpenStreetMap');
});

test('smart trip planner generates 2-day multi-day route with overnight stay', function () {
    $interest = Interest::first() ?? Interest::create([
        'name' => 'Nature',
        'slug' => 'nature',
        'is_active' => true,
    ]);

    $postData = [
        'trip_days' => 2,
        'travel_date' => now()->format('Y-m-d'),
        'start_time' => '08:00',
        'available_time' => 'full_day',
        'interest_ids' => [$interest->id],
        'start_location_name' => 'Matale Bus Stand',
        'start_latitude' => 7.4675,
        'start_longitude' => 80.6234,
        'travel_mode' => 'private_vehicle',
        'include_meal_stop' => true,
        'include_accommodation' => true,
    ];

    $response = $this->post(route('plan.smart-trip-planner.generate'), $postData);

    $response->assertStatus(200);
    $response->assertSee('Day 1');
    $response->assertSee('Day 2');
    $response->assertSee('Total Distance');
});

test('smart trip planner generates 3-day expedition route with public bus mode', function () {
    $interest = Interest::first() ?? Interest::create([
        'name' => 'Nature',
        'slug' => 'nature',
        'is_active' => true,
    ]);

    $postData = [
        'trip_days' => 3,
        'travel_date' => now()->format('Y-m-d'),
        'start_time' => '07:30',
        'available_time' => 'full_day',
        'interest_ids' => [$interest->id],
        'start_location_name' => 'Matale Bus Stand',
        'start_latitude' => 7.4675,
        'start_longitude' => 80.6234,
        'travel_mode' => 'public_transport',
        'include_meal_stop' => true,
        'include_accommodation' => true,
    ];

    $response = $this->post(route('plan.smart-trip-planner.generate'), $postData);

    $response->assertStatus(200);
    $response->assertSee('Day 1');
    $response->assertSee('Day 2');
    $response->assertSee('Day 3');
    $response->assertSee('Public Bus');
});

test('smart trip planner generates route with private bus mode and coach advisory', function () {
    $interest = Interest::first() ?? Interest::create([
        'name' => 'Nature',
        'slug' => 'nature',
        'is_active' => true,
    ]);

    $postData = [
        'trip_days' => 2,
        'travel_date' => now()->format('Y-m-d'),
        'start_time' => '08:00',
        'available_time' => 'full_day',
        'interest_ids' => [$interest->id],
        'start_location_name' => 'Matale Bus Stand',
        'start_latitude' => 7.4675,
        'start_longitude' => 80.6234,
        'travel_mode' => 'private_bus',
        'include_meal_stop' => true,
        'include_accommodation' => true,
    ];

    $response = $this->post(route('plan.smart-trip-planner.generate'), $postData);

    $response->assertStatus(200);
    $response->assertSee('Private Bus / Coach');
    $response->assertSee('30 km/h');
    $response->assertSee('Mountain Road Advisory for Heavy Coaches');
});

test('smart trip planner integrates weather observations from past 48 hours and safety alerts', function () {
    $user = User::factory()->create();

    $interest = Interest::first() ?? Interest::create([
        'name' => 'Nature',
        'slug' => 'nature',
        'is_active' => true,
    ]);

    // Create fresh observation
    WeatherObservation::create([
        'user_id' => $user->id,
        'location_name' => 'Riverston Gap Summit',
        'condition' => 'mist',
        'temperature' => 19.5,
        'observed_at' => now()->subHours(3),
        'status' => 'approved',
    ]);

    // Create active SafetyAlert
    SafetyAlert::create([
        'user_id' => $user->id,
        'location_name' => 'Thelgamu Oya Crossing',
        'title' => 'Flash Flood Warning at Thelgamu River',
        'hazard_type' => 'flash_flood',
        'severity' => 'warning',
        'description' => 'Rapid water rise in stream. Avoid bathing.',
        'safety_instructions' => 'Stay away from river banks after rain.',
        'is_active' => true,
        'reported_at' => now(),
    ]);

    // 1. Check index page displays weather & safety
    $indexResponse = $this->get(route('plan.smart-trip-planner.index', ['lang' => 'en']));
    $indexResponse->assertStatus(200);
    $indexResponse->assertSee('Riverston Gap Summit');
    $indexResponse->assertSee('Flash Flood Warning at Thelgamu River');

    // 2. Check generate result page displays them
    $postData = [
        'trip_days' => 1,
        'travel_date' => now()->format('Y-m-d'),
        'start_time' => '08:00',
        'available_time' => '8',
        'interest_ids' => [$interest->id],
        'start_location_name' => 'Matale Bus Stand',
        'start_latitude' => 7.4675,
        'start_longitude' => 80.6234,
        'travel_mode' => 'private_vehicle',
    ];

    $resultResponse = $this->post(route('plan.smart-trip-planner.generate'), $postData);
    $resultResponse->assertStatus(200);
    $resultResponse->assertSee('Riverston Gap Summit');
    $resultResponse->assertSee('Flash Flood Warning at Thelgamu River');
    $resultResponse->assertSee('Stay away from river banks');
});

test('smart trip planner integrates public transport with sltb and private bus categorization and suspension alert', function () {
    $interest = Interest::first() ?? Interest::create([
        'name' => 'Nature',
        'slug' => 'nature',
        'is_active' => true,
    ]);

    // Create an active SLTB route and a suspended route
    PublicTransport::create([
        'route_number' => '704',
        'route_name' => 'Matale - Pallegama SLTB Express',
        'transport_type' => 'bus',
        'bus_category' => 'sltb',
        'from_location' => 'Matale Stand',
        'to_location' => 'Pallegama Stand',
        'fare' => 280.00,
        'is_suspended' => false,
        'status' => 'active',
    ]);

    PublicTransport::create([
        'route_number' => 'P-22',
        'route_name' => 'Pallegama - Meemure Safari Shuttle',
        'transport_type' => 'bus',
        'bus_category' => 'private',
        'from_location' => 'Pallegama',
        'to_location' => 'Meemure',
        'fare' => 350.00,
        'is_suspended' => true,
        'suspension_reason' => 'Road repair on mountain pass',
        'status' => 'active',
    ]);

    $postData = [
        'trip_days' => 1,
        'travel_date' => now()->format('Y-m-d'),
        'start_time' => '08:00',
        'available_time' => '8',
        'interest_ids' => [$interest->id],
        'start_location_name' => 'Matale Stand',
        'start_latitude' => 7.4675,
        'start_longitude' => 80.6234,
        'travel_mode' => 'public_transport',
    ];

    $resultResponse = $this->post(route('plan.smart-trip-planner.generate'), $postData);
    $resultResponse->assertStatus(200);
    $resultResponse->assertSee('Matale - Pallegama SLTB Express');
    $resultResponse->assertSee('SLTB (Public Bus)');
    $resultResponse->assertSee('Pallegama - Meemure Safari Shuttle');
    $resultResponse->assertSee('Private Bus');
    $resultResponse->assertSee('Temporarily Suspended');
    $resultResponse->assertSee('Road repair on mountain pass');
});
