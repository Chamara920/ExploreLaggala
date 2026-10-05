<?php

use App\Models\SafetyAlert;
use App\Models\User;
use App\Models\WeatherLocation;
use App\Models\WeatherLocationTranslation;
use App\Models\WeatherObservation;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    // Create a regional weather location
    $this->location = WeatherLocation::create([
        'name' => 'Pallegama Town',
        'slug' => 'pallegama-town-test',
        'latitude' => 7.5583,
        'longitude' => 80.7306,
        'elevation_m' => 280,
        'description' => 'Regional hub for Laggala valley.',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    WeatherLocationTranslation::create([
        'weather_location_id' => $this->location->id,
        'locale' => 'en',
        'name' => 'Pallegama Town',
        'description' => 'Regional hub for Laggala valley.',
    ]);

    WeatherLocationTranslation::create([
        'weather_location_id' => $this->location->id,
        'locale' => 'si',
        'name' => 'පල්ලේගම නගරය',
        'description' => 'ලග්ගල ප්‍රධාන නගර කලාපය.',
    ]);

    // Create a community user
    $this->communityUser = User::factory()->create([
        'name' => 'Sunil Perera',
        'email' => 'sunil.test@example.com',
    ]);
    $this->communityUser->assignRole('community_user');

    // Create an admin user
    $this->adminUser = User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin.test@example.com',
    ]);
    $this->adminUser->assignRole('admin');
});

test('public weather safety page displays with 200 OK', function () {
    $response = $this->get('/plan/weather-safety');
    $response->assertStatus(200);
    $response->assertSee('Pallegama Town');
});

test('weather safety page supports multilingual translations in en, si, and ta', function () {
    // Sinhala
    $resSi = $this->get('/plan/weather-safety?lang=si');
    $resSi->assertStatus(200);
    $resSi->assertSee('කාලගුණික දත්ත');
    $resSi->assertSee('ආරක්ෂාව සහ ආපදා ඇඟවීම්');

    // English
    $resEn = $this->get('/plan/weather-safety?lang=en');
    $resEn->assertStatus(200);
    $resEn->assertSee('Regional Weather Data');
    $resEn->assertSee('Safety & Disaster Alerts');

    // Tamil
    $resTa = $this->get('/plan/weather-safety?lang=ta');
    $resTa->assertStatus(200);
    $resTa->assertSee('பிராந்திய வானிலை தரவு');
    $resTa->assertSee('பாதுகாப்பு & இடர் எச்சரிக்கைகள்');
});

test('weather observations older than 48 hours are NOT displayed', function () {
    // 1. Fresh observation (observed 2 hours ago)
    $freshObs = WeatherObservation::create([
        'user_id' => $this->communityUser->id,
        'weather_location_id' => $this->location->id,
        'location_name' => 'Pallegama Fresh Area',
        'condition' => 'sunny',
        'temperature' => 27.5,
        'observed_at' => now()->subHours(2),
        'status' => 'approved',
    ]);

    // 2. Stale observation (observed 60 hours ago - older than 48 hours)
    $staleObs = WeatherObservation::create([
        'user_id' => $this->communityUser->id,
        'weather_location_id' => $this->location->id,
        'location_name' => 'Pallegama Stale 60h Old',
        'condition' => 'rain',
        'temperature' => 22.0,
        'observed_at' => now()->subHours(60),
        'status' => 'approved',
    ]);

    $response = $this->get('/plan/weather-safety');
    $response->assertStatus(200);
    $response->assertSee('Pallegama Fresh Area');
    $response->assertDontSee('Pallegama Stale 60h Old');
});

test('guest visitor sees the compact call-to-action card prompting to sign in', function () {
    $response = $this->get('/plan/weather-safety?lang=en');
    $response->assertStatus(200);
    $response->assertSee('Contribute Weather & Safety Updates');
    $response->assertSee(route('login'));
    $response->assertSee(route('register'));
});

test('community user can submit ground weather observation and it displays immediately without approval', function () {
    $this->actingAs($this->communityUser);

    $payload = [
        'weather_location_id' => $this->location->id,
        'condition' => 'mist',
        'temperature' => 21.0,
        'rainfall' => 5.0,
        'wind_condition' => 'Chilly breeze',
        'description' => 'Heavy mist covering the river pass.',
    ];

    $response = $this->post('/plan/weather-safety', $payload);
    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('weather_observations', [
        'user_id' => $this->communityUser->id,
        'condition' => 'mist',
        'temperature' => 21.0,
        'status' => 'approved', // Immediately approved
    ]);

    // Verify it is visible on the page
    $pageResponse = $this->get('/plan/weather-safety');
    $pageResponse->assertSee('Heavy mist covering the river pass.');
});

test('community user can post a safety hazard alert and it displays immediately without approval', function () {
    $this->actingAs($this->communityUser);

    $payload = [
        'title' => 'Riverston Road Rockfall Warning',
        'hazard_type' => 'rockfall',
        'severity' => 'warning',
        'weather_location_id' => $this->location->id,
        'location_name' => 'Riverston 12th km post',
        'description' => 'Small boulders fallen onto the road curve after heavy rain.',
        'safety_instructions' => 'Proceed with low gear and avoid stopping near cut slopes.',
    ];

    $response = $this->post('/plan/weather-safety/safety-alert', $payload);
    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('safety_alerts', [
        'title' => 'Riverston Road Rockfall Warning',
        'hazard_type' => 'rockfall',
        'severity' => 'warning',
        'is_active' => true, // Immediately active
    ]);

    $pageResponse = $this->get('/plan/weather-safety');
    $pageResponse->assertSee('Riverston Road Rockfall Warning');
    $pageResponse->assertSee('Proceed with low gear');
});

test('admin can delete a weather observation while non-admin gets 403', function () {
    $obs = WeatherObservation::create([
        'user_id' => $this->communityUser->id,
        'location_name' => 'To be deleted location',
        'condition' => 'sunny',
        'observed_at' => now(),
        'status' => 'approved',
    ]);

    // Non-admin attempt -> 403
    $this->actingAs($this->communityUser);
    $failResponse = $this->delete('/plan/weather-safety/observation/'.$obs->id);
    $failResponse->assertStatus(403);

    // Admin attempt -> success
    $this->actingAs($this->adminUser);
    $successResponse = $this->delete('/plan/weather-safety/observation/'.$obs->id);
    $successResponse->assertRedirect();
    $this->assertDatabaseMissing('weather_observations', ['id' => $obs->id]);
});

test('admin can delete a safety alert while non-admin gets 403', function () {
    $alert = SafetyAlert::create([
        'user_id' => $this->communityUser->id,
        'location_name' => 'Riverston Pass',
        'title' => 'Old resolved alert',
        'hazard_type' => 'dense_mist',
        'severity' => 'advisory',
        'description' => 'Resolved mist condition.',
        'is_active' => true,
        'reported_at' => now(),
    ]);

    // Non-admin attempt -> 403
    $this->actingAs($this->communityUser);
    $failResponse = $this->delete('/plan/weather-safety/safety-alert/'.$alert->id);
    $failResponse->assertStatus(403);

    // Admin attempt -> success
    $this->actingAs($this->adminUser);
    $successResponse = $this->delete('/plan/weather-safety/safety-alert/'.$alert->id);
    $successResponse->assertRedirect();
    $this->assertDatabaseMissing('safety_alerts', ['id' => $alert->id]);
});

test('only admin can create a new weather station location', function () {
    // Non-admin attempt -> 403
    $this->actingAs($this->communityUser);
    $failResponse = $this->post('/plan/weather-safety/location', [
        'name' => 'Unauthorized Station',
        'latitude' => 7.50,
        'longitude' => 80.70,
    ]);
    $failResponse->assertStatus(403);

    // Admin attempt -> success
    $this->actingAs($this->adminUser);
    $successResponse = $this->post('/plan/weather-safety/location', [
        'name' => 'Deanston Forest Hub',
        'latitude' => 7.4200,
        'longitude' => 80.7800,
        'elevation_m' => 610,
        'description' => 'Gateway to eastern Knuckles range.',
    ]);
    $successResponse->assertRedirect();

    $this->assertDatabaseHas('weather_locations', [
        'name' => 'Deanston Forest Hub',
        'slug' => 'deanston-forest-hub',
    ]);
});
