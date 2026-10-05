<?php

use App\Models\TravelGuide;
use App\Models\TravelGuideTranslation;
use App\Models\User;

beforeEach(function () {
    $user = User::factory()->create();

    $guide = TravelGuide::updateOrCreate(
        ['slug' => 'manigala-test-trail'],
        [
            'author_id' => $user->id,
            'title' => 'Manigala Trail Test Guide',
            'category' => 'getting_here',
            'summary' => 'Complete hiking guide for Manigala trail.',
            'content' => '<p>Detailed trail notes and safety advice.</p>',
            'cover_image' => 'travel-guides/01M44ZS9NQYJG7EPBVYKKQ6WN7.jpeg',
            'status' => 'published',
            'featured' => true,
        ]
    );

    TravelGuideTranslation::updateOrCreate(
        ['travel_guide_id' => $guide->id, 'locale' => 'en'],
        [
            'title' => 'Manigala Trail Test Guide',
            'summary' => 'Complete hiking guide for Manigala trail.',
            'content' => '<p>Detailed trail notes and safety advice.</p>',
        ]
    );

    TravelGuideTranslation::updateOrCreate(
        ['travel_guide_id' => $guide->id, 'locale' => 'si'],
        [
            'title' => 'මානිගල පරික්ෂණ මඟපෙන්වීම',
            'summary' => 'මානිගල තරණය සඳහා සම්පූර්ණ මඟපෙන්වීම.',
            'content' => '<p>මානිගල ගමන් මාර්ගය පිළිබඳ විස්තරය.</p>',
        ]
    );
});

it('renders travel guide index page with localized content', function () {
    $responseEn = $this->withSession(['locale' => 'en'])->get('/plan/travel-guide?lang=en');
    $responseEn->assertStatus(200);
    $responseEn->assertSee('Laggala Travel Guide');
    $responseEn->assertSee('Manigala Trail Test Guide', false);
    $responseEn->assertDontSee('මානිගල පරික්ෂණ මඟපෙන්වීම');

    $responseSi = $this->withSession(['locale' => 'si'])->get('/plan/travel-guide?lang=si');
    $responseSi->assertStatus(200);
    $responseSi->assertSee('මානිගල පරික්ෂණ මඟපෙන්වීම', false);
});

it('renders travel guide show page with localized content and cover image', function () {
    $guide = TravelGuide::where('slug', 'manigala-test-trail')->first();
    expect($guide)->not->toBeNull();
    expect($guide->cover_image_url)->not->toBeEmpty();

    $responseEn = $this->withSession(['locale' => 'en'])->get('/plan/travel-guide/manigala-test-trail?lang=en');
    $responseEn->assertStatus(200);
    $responseEn->assertSee('Manigala Trail Test Guide', false);
    $responseEn->assertSee('Detailed trail notes and safety advice.', false);
    $responseEn->assertDontSee('මානිගල පරික්ෂණ මඟපෙන්වීම');
    $responseEn->assertSee($guide->cover_image_url, false);

    $responseSi = $this->withSession(['locale' => 'si'])->get('/plan/travel-guide/manigala-test-trail?lang=si');
    $responseSi->assertStatus(200);
    $responseSi->assertSee('මානිගල පරික්ෂණ මඟපෙන්වීම', false);
    $responseSi->assertSee('මානිගල ගමන් මාර්ගය පිළිබඳ විස්තරය.', false);
});
