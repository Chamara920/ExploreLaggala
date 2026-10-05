<?php

use App\Models\PublicTransport;
use App\Models\PublicTransportTranslation;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $routeActive = PublicTransport::create([
        'route_number' => '704',
        'route_name' => 'Matale - Pallegama (Via Riverston)',
        'transport_type' => 'bus',
        'bus_category' => 'sltb',
        'from_location' => 'Matale Bus Stand',
        'to_location' => 'Pallegama Bus Stand',
        'key_stops' => 'Rattota, Riverston, Pitawala Pathana',
        'departure_time' => '06:30 AM',
        'arrival_time' => '09:30 AM',
        'frequency' => '5 trips daily',
        'fare' => 280.00,
        'fare_note' => 'Standard ticket',
        'operator_name' => 'SLTB Matale Depot',
        'contact_number' => '066-2222281',
        'notes' => 'Scenic Knuckles mountain route.',
        'is_suspended' => false,
        'status' => 'active',
        'community_submitted' => false,
    ]);

    PublicTransportTranslation::create([
        'public_transport_id' => $routeActive->id,
        'locale' => 'en',
        'route_name' => 'Matale - Pallegama (Via Riverston)',
        'from_location' => 'Matale Bus Stand',
        'to_location' => 'Pallegama Bus Stand',
        'key_stops' => 'Rattota, Riverston, Pitawala Pathana',
        'notes' => 'Scenic Knuckles mountain route.',
    ]);

    PublicTransportTranslation::create([
        'public_transport_id' => $routeActive->id,
        'locale' => 'si',
        'route_name' => 'මාතලේ - පල්ලේගම (රිවස්ටන් හරහා)',
        'from_location' => 'මාතලේ බස් නැවතුම',
        'to_location' => 'පල්ලේගම බස් නැවතුම',
        'key_stops' => 'රත්තොට, රිවස්ටන්, පිටවල පතන',
        'notes' => 'නකල්ස් කඳුකර මනරම් බස් මාර්ගය.',
    ]);

    PublicTransportTranslation::create([
        'public_transport_id' => $routeActive->id,
        'locale' => 'ta',
        'route_name' => 'மாத்தளை - பல்லேகம (ரிவர்ஸ்டன் வழியாக)',
        'from_location' => 'மாத்தளை பேருந்து நிலையம்',
        'to_location' => 'பல்லேகம பேருந்து நிலையம்',
        'key_stops' => 'ரத்தோட்டை, ரிவர்ஸ்டன், பிடவல பத்தன',
        'notes' => 'நக்கிள்ஸ் மலைப்பாதை பேருந்து.',
    ]);

    $routeSuspended = PublicTransport::create([
        'route_number' => '704-Spl',
        'route_name' => 'Rattota - Riverston Mountain Peak Shuttle',
        'transport_type' => 'bus',
        'bus_category' => 'sltb',
        'from_location' => 'Rattota Stand',
        'to_location' => 'Riverston Gap',
        'is_suspended' => true,
        'suspension_reason' => 'Temporarily suspended due to road works',
        'suspended_until' => 'Until Oct 20, 2026',
        'status' => 'active',
        'community_submitted' => false,
    ]);

    PublicTransportTranslation::create([
        'public_transport_id' => $routeSuspended->id,
        'locale' => 'en',
        'route_name' => 'Rattota - Riverston Mountain Peak Shuttle',
        'from_location' => 'Rattota Stand',
        'to_location' => 'Riverston Gap',
        'suspension_reason' => 'Temporarily suspended due to road works',
    ]);

    PublicTransportTranslation::create([
        'public_transport_id' => $routeSuspended->id,
        'locale' => 'si',
        'route_name' => 'රත්තොට - රිවස්ටන් කඳු මුදුන විශේෂ සේවාව',
        'from_location' => 'රත්තොට බස් නැවතුම',
        'to_location' => 'රිවස්ටන් කපොල්ල',
        'suspension_reason' => 'මාර්ග ප්‍රතිසංස්කරණ කටයුතු හේතුවෙන් ධාවනය තාවකාලිකව අත්හිටුවා ඇත',
    ]);
});

it('renders public transport page with full multilingual support', function () {
    // English
    $responseEn = $this->withSession(['locale' => 'en'])->get('/plan/public-transport?lang=en');
    $responseEn->assertStatus(200);
    $responseEn->assertSee('Public Transport &amp; Bus Schedules', false);
    $responseEn->assertSee('Essential Trip Planning Transport Tips', false);
    $responseEn->assertSee('Matale - Pallegama (Via Riverston)', false);

    // Sinhala
    $responseSi = $this->withSession(['locale' => 'si'])->get('/plan/public-transport?lang=si');
    $responseSi->assertStatus(200);
    $responseSi->assertSee('පොදු ප්‍රවාහන හා බස් රථ කාලසටහන්', false);
    $responseSi->assertSee('චාරිකා සැලසුම්කරණය සඳහා වැදගත් පොදු ප්‍රවාහන උපදෙස්', false);
    $responseSi->assertSee('මාතලේ - පල්ලේගම (රිවස්ටන් හරහා)', false);

    // Tamil
    $responseTa = $this->withSession(['locale' => 'ta'])->get('/plan/public-transport?lang=ta');
    $responseTa->assertStatus(200);
    $responseTa->assertSee('பொதுப் போக்குவரத்து &amp; பேருந்து நேர அட்டவணை', false);
    $responseTa->assertSee('மாத்தளை - பல்லேகம (ரிவர்ஸ்டன் வழியாக)', false);
});

it('displays temporary suspension notice and alert for suspended services', function () {
    $suspendedRoute = PublicTransport::where('is_suspended', true)->first();
    expect($suspendedRoute)->not->toBeNull();

    $responseEn = $this->withSession(['locale' => 'en'])->get('/plan/public-transport?lang=en');
    $responseEn->assertStatus(200);
    $responseEn->assertSee('Temporarily Suspended', false);
    $responseEn->assertSee('Service Suspension Notice', false);

    $responseSi = $this->withSession(['locale' => 'si'])->get('/plan/public-transport?lang=si');
    $responseSi->assertStatus(200);
    $responseSi->assertSee('ධාවනය තාවකාලිකව අත්හිටුවා ඇත', false);
    $responseSi->assertSee('ධාවනය තාවකාලිකව අත්හිටුවීමේ නිවේදනය', false);
});

it('shows guest call-to-action with login and register buttons for unauthenticated visitors', function () {
    $response = $this->get('/plan/public-transport?lang=si');
    $response->assertStatus(200);
    $response->assertSee('ප්‍රජා දායකයෙකු (Community Contributor) ලෙස එක්වන්න', false);
    $response->assertSee(route('login'), false);
    $response->assertSee(route('register'), false);
});

it('allows authenticated community user or admin to update transport status', function () {
    $user = User::factory()->create();
    $user->assignRole('community_user');

    $route = PublicTransport::first();
    expect($route)->not->toBeNull();

    $response = $this->actingAs($user)->patch("/plan/public-transport/{$route->id}/status", [
        'is_suspended' => '1',
        'suspension_reason' => 'Test landslide road block',
        'suspended_until' => 'Until tomorrow',
    ]);

    $response->assertSessionHas('success');
    $route->refresh();
    expect($route->is_suspended)->toBeTrue();
    expect($route->suspension_reason)->toBe('Test landslide road block');
});
