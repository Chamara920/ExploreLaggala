@extends('layouts.public')

@section('title', (app()->getLocale() === 'si' ? 'ලග්ගල සුන්දරත්වය ගවේෂණය කරන්න' : (app()->getLocale() === 'ta' ? 'லக்கலவின் அழகை ஆராயுங்கள்' : 'Explore the Beauty of Laggala')) . ' — Explore Laggala')

@section('content')
@php
    if (request()->has('lang') && in_array(request('lang'), ['si', 'en', 'ta'])) {
        session(['locale' => request('lang')]);
        app()->setLocale(request('lang'));
    }
    $locale = session('locale', app()->getLocale() ?: 'si');

    // Fetch active Home Slides
    $homeSlides = \App\Models\HomeSlide::with('translations')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    // Fetch Home Page Settings
    $homeSetting = \App\Models\HomePageSetting::with('translations')->find(1);
    $settingTr = $homeSetting?->translationFor($locale) ?? $homeSetting?->translationFor('en');

    // Fetch published Destinations from DB (respecting Super Admin selection)
    if (!empty($homeSetting?->featured_destination_ids)) {
        $dbDestinations = \App\Models\Destination::with(['translations', 'images'])
            ->where('status', 'published')
            ->whereIn('id', $homeSetting->featured_destination_ids)
            ->get()
            ->sortBy(fn ($item) => array_search($item->id, $homeSetting->featured_destination_ids))
            ->values();
    } else {
        $dbDestinations = \App\Models\Destination::with(['translations', 'images'])
            ->where('status', 'published')
            ->orderBy('featured', 'desc')
            ->orderBy('sort_order')
            ->limit($homeSetting?->destinations_count ?? 6)
            ->get();
    }

    // Fetch published Blog Posts (respecting Super Admin selection)
    if (!empty($homeSetting?->featured_blog_post_ids)) {
        $latestBlogPosts = \App\Models\BlogPost::with(['translations', 'category', 'user'])
            ->where('status', 'published')
            ->whereIn('id', $homeSetting->featured_blog_post_ids)
            ->get()
            ->sortBy(fn ($item) => array_search($item->id, $homeSetting->featured_blog_post_ids))
            ->values();
    } else {
        $latestBlogPosts = \App\Models\BlogPost::with(['translations', 'category', 'user'])
            ->where('status', 'published')
            ->orderBy('featured', 'desc')
            ->latest('published_at')
            ->limit($homeSetting?->blog_posts_count ?? 3)
            ->get();
    }

    // Fetch published News (respecting Super Admin selection)
    if (!empty($homeSetting?->featured_news_post_ids)) {
        $latestNews = \App\Models\NewsPost::with(['translations', 'category'])
            ->where('status', 'published')
            ->whereIn('id', $homeSetting->featured_news_post_ids)
            ->get()
            ->sortBy(fn ($item) => array_search($item->id, $homeSetting->featured_news_post_ids))
            ->values();
    } else {
        $latestNews = \App\Models\NewsPost::with(['translations', 'category'])
            ->where('status', 'published')
            ->orderBy('featured', 'desc')
            ->latest('published_at')
            ->limit($homeSetting?->news_posts_count ?? 3)
            ->get();
    }

    // Fetch published Events (respecting Super Admin selection)
    if (!empty($homeSetting?->featured_event_ids)) {
        $latestEvents = \App\Models\Event::with(['translations', 'category'])
            ->where('status', 'published')
            ->whereIn('id', $homeSetting->featured_event_ids)
            ->get()
            ->sortBy(fn ($item) => array_search($item->id, $homeSetting->featured_event_ids))
            ->values();
    } else {
        $latestEvents = \App\Models\Event::with(['translations', 'category'])
            ->where('status', 'published')
            ->orderBy('featured', 'desc')
            ->where(function ($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '>=', now()->toDateString());
            })
            ->orderBy('start_date')
            ->limit($homeSetting?->events_count ?? 3)
            ->get();

        if ($latestEvents->isEmpty()) {
            $latestEvents = \App\Models\Event::with(['translations', 'category'])
                ->where('status', 'published')
                ->latest('start_date')
                ->limit($homeSetting?->events_count ?? 3)
                ->get();
        }
    }

    // Curated high-res destination items (displayed or merged if DB has fewer than 6)
    $curatedDestinations = [
        [
            'name' => $locale === 'si' ? 'සේර ඇල්ල' : ($locale === 'ta' ? 'சேர எல்ல நீர்வீழ்ச்சி' : 'Sera Ella Falls'),
            'type' => $locale === 'si' ? 'දියඇල්ල' : ($locale === 'ta' ? 'நீர்வீழ்ச்சி' : 'Waterfall'),
            'rating' => '4.9',
            'location' => $locale === 'si' ? 'පෝතටවෙල, ලග්ගල' : ($locale === 'ta' ? 'போதடவெல, லக்கல' : 'Pothatawela, Laggala'),
            'image' => 'https://images.unsplash.com/photo-1433086966358-54859d0ed716?auto=format&fit=crop&w=1000&q=85',
            'slug' => 'sera-ella',
            'tag' => $locale === 'si' ? 'නැරඹිය යුතුමයි' : ($locale === 'ta' ? 'பார்க்க வேண்டியவை' : 'Must Visit'),
        ],
        [
            'name' => $locale === 'si' ? 'රිවස්ටන් කඳු මුදුන සහ ගැප්' : ($locale === 'ta' ? 'ரிவர்ஸ்டன் சிகரம் & கேப்' : 'Riverston Peak & Gap'),
            'type' => $locale === 'si' ? 'කඳුකර දසුන' : ($locale === 'ta' ? 'மலைக் காட்சி' : 'Mountain Vista'),
            'rating' => '4.9',
            'location' => $locale === 'si' ? 'රිවස්ටන්, නකල්ස්' : ($locale === 'ta' ? 'ரிவர்ஸ்டன், நக்கிள்ஸ்' : 'Riverston, Knuckles Range'),
            'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1000&q=85',
            'slug' => 'riverston',
            'tag' => $locale === 'si' ? 'විශේෂ දසුන' : ($locale === 'ta' ? 'சிறப்புக் காட்சி' : 'Iconic View'),
        ],
        [
            'name' => $locale === 'si' ? 'පිටවල පතන සහ පුංචි ලෝකාන්තය' : ($locale === 'ta' ? 'பிடவல பத்தன & மினி உலக முடிவு' : 'Pitawala Pathana & Mini World\'s End'),
            'type' => $locale === 'si' ? 'තෘණ භූමි සහ ප්‍රපාත' : ($locale === 'ta' ? 'புல்வெளி & செங்குத்துப்பாறை' : 'Grassland & Escarpment'),
            'rating' => '4.8',
            'location' => $locale === 'si' ? 'පිටවල, ලග්ගල' : ($locale === 'ta' ? 'பிடவல, லக்கல' : 'Pitawala, Laggala'),
            'image' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1000&q=85',
            'slug' => 'pitawala-pathana',
            'tag' => $locale === 'si' ? 'දර්ශනීය' : ($locale === 'ta' ? 'அழகிய காட்சி' : 'Panoramic'),
        ],
        [
            'name' => $locale === 'si' ? 'මාණිගල ගල් මංපෙත' : ($locale === 'ta' ? 'மணிகல பாறைப் பாதை' : 'Manigala Rock Trail'),
            'type' => $locale === 'si' ? 'කඳු තරණ මංපෙත' : ($locale === 'ta' ? 'மலையேற்றம் & நடைபயணம்' : 'Trekking & Hiking'),
            'rating' => '4.8',
            'location' => $locale === 'si' ? 'ඇටන්වල, නකල්ස්' : ($locale === 'ta' ? 'அடன்வல, நக்கிள்ஸ்' : 'Atanwala, Knuckles'),
            'image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1000&q=85',
            'slug' => 'manigala',
            'tag' => $locale === 'si' ? 'වික්‍රමාන්විත' : ($locale === 'ta' ? 'சாகசம்' : 'Adventure'),
        ],
        [
            'name' => $locale === 'si' ? 'තෙල්ගමු ඔය ස්වභාවික නාන තටාක' : ($locale === 'ta' ? 'தெல்கமு ஓயா இயற்கை குளங்கள்' : 'Thelgamu Oya Natural Pools'),
            'type' => $locale === 'si' ? 'දිය නෑම සහ විවේකය' : ($locale === 'ta' ? 'ஆறு & இயற்கை குளியல்' : 'River & Nature Bath'),
            'rating' => '4.7',
            'location' => $locale === 'si' ? 'ඉලුක්කුඹුර, ලග්ගල' : ($locale === 'ta' ? 'இலுக்கும்புர, லக்கல' : 'Illukkumbura, Laggala'),
            'image' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=1000&q=85',
            'slug' => 'thelgamu-oya',
            'tag' => $locale === 'si' ? 'ශාන්ත පරිසරය' : ($locale === 'ta' ? 'அமைதியானது' : 'Serene'),
        ],
        [
            'name' => $locale === 'si' ? 'මීමුරේ පාරම්පරික ගම්මානය' : ($locale === 'ta' ? 'மீமுரே பாரம்பரிய கிராமம்' : 'Meemure Heritage Valley'),
            'type' => $locale === 'si' ? 'සංස්කෘතික ගම්මානය' : ($locale === 'ta' ? 'கலாச்சார கிராமம்' : 'Cultural Village'),
            'rating' => '4.9',
            'location' => $locale === 'si' ? 'නකල්ස් වනපෙත' : ($locale === 'ta' ? 'நக்கிள்ஸ் வனாந்தரம்' : 'Knuckles Wilderness'),
            'image' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=1000&q=85',
            'slug' => 'meemure',
            'tag' => $locale === 'si' ? 'සංස්කෘතිය' : ($locale === 'ta' ? 'கலாச்சாரம்' : 'Culture'),
        ],
    ];
@endphp

<style>
.laggala-slider-stage {
    position: relative;
    width: 100%;
    height: 75vh;
    min-height: 580px;
    max-height: 840px;
}
@media (min-width: 1200px) {
    .laggala-slider-stage {
        height: 78vh;
        min-height: 640px;
        max-height: 880px;
    }
}
@media (max-width: 768px) {
    .laggala-slider-stage {
        height: 520px;
        min-height: 480px;
    }
}
</style>

{{-- ============================================================
     HERO SLIDER (FULL WIDTH / සම්පුර්ණ පළල) + SMART TRIP PLANNER
     ============================================================ --}}
<section class="relative w-full overflow-hidden bg-slate-950 text-white">

    <div id="laggalaHeroSlider"
         class="carousel slide carousel-fade relative w-full"
         data-bs-ride="carousel"
         data-bs-interval="6000">

        {{-- Indicators --}}
        @if($homeSlides->count() > 1)
            <div class="carousel-indicators z-30 mb-8 sm:mb-12">
                @foreach($homeSlides as $index => $slide)
                    <button type="button"
                            data-bs-target="#laggalaHeroSlider"
                            data-bs-slide-to="{{ $index }}"
                            class="{{ $loop->first ? 'active !w-10 !h-2 !rounded-full !bg-emerald-400 !border-0 transition-all' : '!w-4 !h-2 !rounded-full !bg-white/50 !border-0 transition-all hover:!bg-white' }}"></button>
                @endforeach
            </div>
        @endif

        <div class="carousel-inner w-full">
            @forelse($homeSlides as $slide)
                @php
                    $str = $slide->translationFor($locale) ?? $slide->translationFor('en') ?? $slide->translations->first();
                @endphp
                <div class="carousel-item {{ $loop->first ? 'active' : '' }} w-full">
                    <div class="laggala-slider-stage w-full">
                        <img
                            src="{{ $slide->resolved_image_url }}"
                            alt="{{ $str?->title ?? 'Explore Laggala' }}"
                            class="absolute inset-0 h-full w-full object-cover object-center transform scale-100 transition-transform duration-1000"
                        >
                        {{-- Balanced Atmospheric Gradient Overlays --}}
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-950/35 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>

                        {{-- Hero Content Layer --}}
                        <div class="relative z-10 mx-auto flex h-full max-w-7xl flex-col justify-center py-8 sm:py-10 px-4 sm:px-6 lg:px-8">
                            <div class="max-w-3xl">
                                @if(!empty($str?->badge))
                                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-400/40 bg-emerald-500/20 px-4 py-1.5 text-xs font-extrabold uppercase tracking-widest text-emerald-300 backdrop-blur-md">
                                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        {{ $str->badge }}
                                    </span>
                                @endif

                                <h1 class="mt-4 text-4xl font-black tracking-tight leading-tight text-white sm:text-5xl lg:text-6xl drop-shadow-md">
                                    {{ $str?->title ?? 'Explore Laggala' }}
                                </h1>

                                @if(!empty($str?->subtitle))
                                    <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-200/95 sm:text-lg drop-shadow">
                                        {{ $str->subtitle }}
                                    </p>
                                @endif

                                {{-- Actions with Transparent Smart Trip Planner Button --}}
                                <div class="mt-8 flex flex-wrap items-center gap-4">

                                    {{-- SMART TRIP PLANNER TRANSPARENT BUTTON --}}
                                    @if(!empty($slide->planner_button_url))
                                        <a href="{{ url($slide->planner_button_url) }}"
                                           class="group inline-flex items-center gap-3.5 rounded-2xl border border-white/30 bg-white/15 px-6 py-3.5 text-white shadow-2xl backdrop-blur-md transition-all duration-300 hover:scale-105 hover:bg-white/25 hover:border-white/60 hover:shadow-emerald-500/25">
                                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-400 via-teal-400 to-cyan-400 text-slate-950 text-xl font-bold shadow-md transition-transform duration-300 group-hover:rotate-12">
                                                ✨
                                            </span>
                                            <div class="text-left">
                                                <span class="block text-[11px] font-bold uppercase tracking-wider text-emerald-300">
                                                    {{ $str?->planner_button_subtitle ?? ($locale === 'si' ? 'ඔබේ ගමන සැලසුම් කරන්න' : ($locale === 'ta' ? 'உங்கள் பயணத்தைத் திட்டமிடுங்கள்' : 'Plan Your Journey')) }}
                                                </span>
                                                <span class="flex items-center gap-1.5 text-base font-extrabold text-white">
                                                    {{ $str?->planner_button_text ?? ($locale === 'si' ? 'ස්මාර්ට් චාරිකා සැලසුම්කරු' : ($locale === 'ta' ? 'ஸ்மார்ட் பயணத் திட்டமிடுபவர்' : 'Smart Trip Planner')) }}
                                                    <i class="bi bi-arrow-right text-emerald-300 transition-transform duration-300 group-hover:translate-x-1.5"></i>
                                                </span>
                                            </div>
                                        </a>
                                    @endif

                                    {{-- PRIMARY BUTTON --}}
                                    @if(!empty($slide->primary_button_url))
                                        <a href="{{ url($slide->primary_button_url) }}"
                                           class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-6 py-4 text-sm font-extrabold text-white shadow-lg shadow-emerald-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:bg-emerald-400">
                                            <i class="bi bi-signpost-2-fill me-2"></i>
                                            {{ $str?->primary_button_text ?? ($locale === 'si' ? 'සංචාරක ස්ථාන ගවේෂණය' : ($locale === 'ta' ? 'சுற்றுலா தலங்களை ஆராயுங்கள்' : 'Explore Destinations')) }}
                                        </a>
                                    @endif

                                    {{-- SECONDARY BUTTON --}}
                                    @if(!empty($slide->secondary_button_url))
                                        <a href="{{ url($slide->secondary_button_url) }}"
                                           class="inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white/10 px-5 py-4 text-sm font-bold text-white backdrop-blur-md transition hover:bg-white/20">
                                            <i class="bi bi-map-fill me-2 text-cyan-300"></i>
                                            {{ $str?->secondary_button_text ?? ($locale === 'si' ? 'සිතියම බලන්න' : ($locale === 'ta' ? 'வரைபடம்' : 'Interactive Map')) }}
                                        </a>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="carousel-item active w-full">
                    <div class="relative h-[520px] sm:h-[600px] lg:h-[680px] w-full">
                        <img
                            src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=2200&q=90"
                            alt="Explore Laggala"
                            class="absolute inset-0 h-full w-full object-cover object-center"
                        >
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-950/60 to-transparent"></div>
                        <div class="relative z-10 mx-auto flex h-full max-w-7xl flex-col justify-center px-4 sm:px-6 lg:px-8">
                            <div class="max-w-3xl">
                                <h1 class="text-4xl font-black text-white sm:text-6xl">Discover Laggala</h1>
                                <p class="mt-4 text-slate-200">Central Highlands, Sri Lanka</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Next / Prev Controls --}}
        @if($homeSlides->count() > 1)
            <button class="carousel-control-prev !w-14 sm:!w-20 !opacity-70 hover:!opacity-100 transition-opacity"
                    type="button"
                    data-bs-target="#laggalaHeroSlider"
                    data-bs-slide="prev">
                <span class="flex h-12 w-12 items-center justify-center rounded-full border border-white/20 bg-slate-900/40 text-white backdrop-blur-md shadow-xl transition-all hover:bg-emerald-500 hover:scale-110">
                    <i class="bi bi-chevron-left text-lg"></i>
                </span>
                <span class="visually-hidden">Previous</span>
            </button>

            <button class="carousel-control-next !w-14 sm:!w-20 !opacity-70 hover:!opacity-100 transition-opacity"
                    type="button"
                    data-bs-target="#laggalaHeroSlider"
                    data-bs-slide="next">
                <span class="flex h-12 w-12 items-center justify-center rounded-full border border-white/20 bg-slate-900/40 text-white backdrop-blur-md shadow-xl transition-all hover:bg-emerald-500 hover:scale-110">
                    <i class="bi bi-chevron-right text-lg"></i>
                </span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif

    </div>

</section>

{{-- ============================================================
     QUICK ACCESS TILES (Modern floating category strip)
     ============================================================ --}}
<section class="relative z-20 mx-auto -mt-10 max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-6 lg:gap-4">

        @php
            $quickCards = [
                [
                    'title' => __('home.quick_destinations_title'),
                    'text' => __('home.quick_destinations_text'),
                    'icon' => 'bi-signpost-2',
                    'badge' => __('home.quick_destinations_badge'),
                    'image' => 'https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=700&q=85',
                    'url' => route('explore.destinations.index'),
                ],
                [
                    'title' => __('home.quick_map_title'),
                    'text' => __('home.quick_map_text'),
                    'icon' => 'bi-map',
                    'badge' => __('home.quick_map_badge'),
                    'image' => 'https://images.unsplash.com/photo-1524666041070-9e7b5f2b8c31?auto=format&fit=crop&w=700&q=85',
                    'url' => route('explore.map'),
                ],
                [
                    'title' => __('home.quick_culture_title'),
                    'text' => __('home.quick_culture_text'),
                    'icon' => 'bi-bank',
                    'badge' => __('home.quick_culture_badge'),
                    'image' => 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&w=700&q=85',
                    'url' => route('explore.culture-heritage.index'),
                ],
                [
                    'title' => __('home.quick_outdoor_title'),
                    'text' => __('home.quick_outdoor_text'),
                    'icon' => 'bi-tree',
                    'badge' => __('home.quick_outdoor_badge'),
                    'image' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=700&q=85',
                    'url' => route('explore.outdoor-adventure.index'),
                ],
                [
                    'title' => __('home.quick_safety_title'),
                    'text' => __('home.quick_safety_text'),
                    'icon' => 'bi-shield-check',
                    'badge' => __('home.quick_safety_badge'),
                    'image' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=700&q=85',
                    'url' => route('plan.weather.index'),
                ],
                [
                    'title' => __('home.quick_emergency_title'),
                    'text' => __('home.quick_emergency_text'),
                    'icon' => 'bi-telephone-fill',
                    'badge' => __('home.quick_emergency_badge'),
                    'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=700&q=85',
                    'url' => route('emergency.contacts'),
                ],
            ];
        @endphp

        @foreach($quickCards as $card)
            <a href="{{ $card['url'] }}"
               class="group relative h-44 overflow-hidden rounded-2xl shadow-lg ring-1 ring-black/10 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl">

                <img
                    src="{{ $card['image'] }}"
                    alt="{{ $card['title'] }}"
                    class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110"
                >

                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

                <div class="absolute inset-x-0 bottom-0 p-4 text-white">
                    <span class="inline-block rounded-md bg-white/20 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm text-emerald-300">
                        {{ $card['badge'] }}
                    </span>

                    <h3 class="mt-1 text-sm font-black leading-snug sm:text-base text-white">
                        {{ $card['title'] }}
                    </h3>

                    <p class="mt-0.5 text-[11px] text-slate-300">
                        {{ $card['text'] }}
                    </p>

                    <span class="mt-2 inline-flex items-center text-xs font-bold text-emerald-400 transition-transform group-hover:translate-x-1">
                        {{ __('home.explore') }} <i class="bi bi-arrow-right ms-1"></i>
                    </span>
                </div>
            </a>
        @endforeach

    </div>
</section>

{{-- ============================================================
     MAIN SPLIT: FEATURED DESTINATIONS (2/3) + PARALLEL (1/3)
     DESKTOP: Featured Destinations = 2/3 (lg:col-span-8)
     DESKTOP: Latest Blog, Latest News, Latest Event = 1/3 (lg:col-span-4)
     ============================================================ --}}
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">

        {{-- ========================================================
             FEATURED DESTINATIONS (DESKTOP 2/3 COLUMN = lg:col-span-8)
             ======================================================== --}}
        <div class="lg:col-span-8">

            {{-- Section Title & Filter --}}
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between border-b border-slate-200/80 pb-5">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="h-2 w-10 rounded-full bg-gradient-to-r from-emerald-500 to-cyan-500"></span>
                        <h2 class="text-3xl font-black tracking-tight text-slate-900">
                            {{ $settingTr?->destinations_title ?: ($locale === 'si' ? 'ප්‍රමුඛ සංචාරක ස්ථාන' : ($locale === 'ta' ? 'முக்கிய சுற்றுலா தலங்கள்' : 'Featured Destinations')) }}
                        </h2>
                    </div>
                    <p class="mt-2 text-sm text-slate-600 max-w-xl">
                        {{ $settingTr?->destinations_subtitle ?: ($locale === 'si' ? 'ලග්ගල සුන්දර පරිසරයේ විහිදී ඇති නොඉඳුල් දියඇලි, මීදුමින් වැසුණු කඳු මුදුන් සහ ස්වභාවික වන මංපෙත් ගවේෂණය කරන්න.' : ($locale === 'ta' ? 'லக்கலவின் அழகிய நீர்வீழ்ச்சிகள், பனிமூட்டமான மலைகள் மற்றும் இயற்கை நடைபாதைகளை ஆராயுங்கள்.' : 'Explore the pristine waterfalls, majestic misty peaks, and awe-inspiring nature trails that define the breathtaking wilderness of Laggala.')) }}
                    </p>
                </div>

                <a href="{{ route('explore.destinations.index') }}"
                   class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/30 bg-emerald-50 px-5 py-2.5 text-xs font-bold text-emerald-700 shadow-sm transition hover:bg-emerald-600 hover:text-white hover:border-emerald-600 self-start sm:self-auto">
                    <span>{{ __('home.view_all_destinations') }}</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            {{-- 2 Columns Grid inside the 2/3 Desktop Column --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                @php
                    // Combine DB destinations with curated fallback to always show 6 rich cards
                    $displayDestinations = [];

                    foreach ($dbDestinations as $dest) {
                        $tr = $dest->translationFor($locale) ?? $dest->translationFor('en') ?? $dest->translations->first();
                        $cover = $dest->images->where('is_cover', true)->first() ?? $dest->images->first();
                        $imgUrl = $cover ? asset('storage/' . $cover->image_path) : 'https://images.unsplash.com/photo-1433086966358-54859d0ed716?auto=format&fit=crop&w=1000&q=85';

                        $displayDestinations[] = [
                            'name' => $tr?->name ?? 'Laggala Destination',
                            'type' => __('home.featured_spot'),
                            'rating' => '4.9',
                            'location' => $tr?->location_name ?? 'Laggala, Sri Lanka',
                            'image' => $imgUrl,
                            'url' => route('explore.destinations.show', $tr?->slug ?? $dest->id),
                            'tag' => __('home.official_site'),
                        ];
                    }

                    // Fill remaining slots with curated destinations
                    $curatedIndex = 0;
                    while (count($displayDestinations) < 6 && isset($curatedDestinations[$curatedIndex])) {
                        $cur = $curatedDestinations[$curatedIndex];
                        $cur['url'] = route('explore.destinations.index');
                        $displayDestinations[] = $cur;
                        $curatedIndex++;
                    }
                @endphp

                @foreach($displayDestinations as $destination)
                    <a href="{{ $destination['url'] }}"
                       class="group relative flex h-72 flex-col justify-end overflow-hidden rounded-3xl bg-slate-900 shadow-md ring-1 ring-black/5 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl">

                        {{-- Card Background Image --}}
                        <img
                            src="{{ $destination['image'] }}"
                            alt="{{ $destination['name'] }}"
                            class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110"
                        >

                        {{-- Dark Gradient Vignette --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

                        {{-- Glowing Hover Ring --}}
                        <div class="absolute inset-0 opacity-0 bg-gradient-to-tr from-emerald-500/20 via-transparent to-cyan-500/20 transition-opacity duration-300 group-hover:opacity-100"></div>

                        {{-- Top Badge --}}
                        <div class="absolute top-4 left-4 right-4 flex items-center justify-between">
                            <span class="inline-flex rounded-full bg-slate-900/60 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-emerald-300 backdrop-blur-md border border-white/10">
                                {{ $destination['type'] }}
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/90 px-2.5 py-0.5 text-xs font-bold text-slate-950 backdrop-blur-md">
                                ★ {{ $destination['rating'] }}
                            </span>
                        </div>

                        {{-- Bottom Info Box --}}
                        <div class="relative z-10 p-5">
                            <h3 class="text-xl font-black text-white group-hover:text-emerald-300 transition-colors">
                                {{ $destination['name'] }}
                            </h3>

                            <div class="mt-2 flex items-center justify-between pt-2 border-t border-white/15">
                                <span class="text-xs font-medium text-slate-300 flex items-center gap-1">
                                    <i class="bi bi-geo-alt-fill text-emerald-400"></i>
                                    {{ $destination['location'] }}
                                </span>

                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/15 text-white backdrop-blur-md transition-all duration-200 group-hover:bg-emerald-500 group-hover:text-white group-hover:scale-110">
                                    <i class="bi bi-arrow-right text-sm"></i>
                                </span>
                            </div>
                        </div>

                    </a>
                @endforeach

            </div>

            {{-- Explore More Banner --}}
            <div class="mt-8 rounded-3xl bg-gradient-to-r from-emerald-900/30 via-teal-900/20 to-transparent border border-emerald-500/20 p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/20 text-emerald-700 text-2xl">
                        🗺️
                    </span>
                    <div>
                        <h4 class="font-extrabold text-slate-900">{{ __('home.map_banner_heading') }}</h4>
                        <p class="text-xs text-slate-500">{{ __('home.map_banner_text') }}</p>
                    </div>
                </div>
                <a href="{{ route('explore.map') }}"
                   class="flex-shrink-0 rounded-2xl bg-emerald-600 px-5 py-3 text-xs font-bold text-white shadow-md hover:bg-emerald-500 transition">
                    {{ __('home.open_map_btn') }}
                </a>
            </div>

        </div>

        {{-- ========================================================
             PARALLEL SIDEBAR (DESKTOP 1/3 COLUMN = lg:col-span-4)
             ORDER: 1. Latest Blog  --> 2. Latest News --> 3. Latest Event
             ======================================================== --}}
        <div class="lg:col-span-4 space-y-7">

            {{-- 1. LATEST BLOG (ඉස්සෙල්ලම Latest Blog) --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm transition hover:shadow-md">

                <div class="flex items-center justify-between border-b border-slate-100 bg-gradient-to-r from-blue-50/80 to-transparent p-5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-blue-700 text-lg shadow-sm">
                            ✍️
                        </span>
                        <div>
                            <h3 class="font-black text-slate-900 text-base">
                                {{ $settingTr?->blog_title ?: ($locale === 'si' ? 'නවතම බ්ලොග් සටහන්' : ($locale === 'ta' ? 'சமீபத்திய வலைப்பதிவு' : 'Latest Blog')) }}
                            </h3>
                            <p class="text-[11px] text-slate-400 font-medium">
                                {{ $settingTr?->blog_subtitle ?: ($locale === 'si' ? 'සංචාරක කථා සහ මගපෙන්වීම්' : ($locale === 'ta' ? 'கதைகள் மற்றும் பயண வழிகாட்டிகள்' : 'Stories & traveler guides')) }}
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('community.blog.index') }}"
                       class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 transition hover:bg-blue-100">
                        {{ __('home.view') }} <i class="bi bi-chevron-right text-[10px]"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($latestBlogPosts as $post)
                        @php
                            $tb = $post->translationFor($locale) ?? $post->translationFor('en') ?? $post->translations->first();
                        @endphp
                        <a href="{{ route('community.blog.index') }}"
                           class="group block p-4 transition hover:bg-blue-50/40">
                            <span class="inline-block rounded-md bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700 uppercase tracking-wide">
                                {{ $post->category?->name ?? 'Story' }}
                            </span>
                            <h4 class="mt-1 line-clamp-2 text-sm font-bold leading-snug text-slate-800 group-hover:text-blue-700 transition-colors">
                                {{ $tb?->title ?? 'Stories from Laggala Heritage' }}
                            </h4>
                            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-400">
                                <span><i class="bi bi-clock me-1"></i> {{ $post->published_at?->diffForHumans() ?? __('home.published_recently') }}</span>
                                <span class="font-bold text-blue-600 group-hover:translate-x-0.5 transition-transform">{{ __('home.read_story') }} &rarr;</span>
                            </div>
                        </a>
                    @empty
                        {{-- Curated Fallback Blog Posts --}}
                        <a href="{{ route('community.blog.index') }}" class="group block p-4 transition hover:bg-blue-50/40">
                            <span class="inline-block rounded-md bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700 uppercase tracking-wide">
                                {{ __('home.fb_blog1_cat') }}
                            </span>
                            <h4 class="mt-1 line-clamp-2 text-sm font-bold leading-snug text-slate-800 group-hover:text-blue-700 transition-colors">
                                {{ __('home.fb_blog1_title') }}
                            </h4>
                            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-400">
                                <span><i class="bi bi-clock me-1"></i> 5 min read</span>
                                <span class="font-bold text-blue-600 group-hover:translate-x-0.5 transition-transform">{{ __('home.read_story') }} &rarr;</span>
                            </div>
                        </a>

                        <a href="{{ route('community.blog.index') }}" class="group block p-4 transition hover:bg-blue-50/40">
                            <span class="inline-block rounded-md bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 uppercase tracking-wide">
                                {{ __('home.fb_blog2_cat') }}
                            </span>
                            <h4 class="mt-1 line-clamp-2 text-sm font-bold leading-snug text-slate-800 group-hover:text-blue-700 transition-colors">
                                {{ __('home.fb_blog2_title') }}
                            </h4>
                            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-400">
                                <span><i class="bi bi-clock me-1"></i> 4 min read</span>
                                <span class="font-bold text-blue-600 group-hover:translate-x-0.5 transition-transform">{{ __('home.read_story') }} &rarr;</span>
                            </div>
                        </a>
                    @endforelse
                </div>

                <div class="p-3 bg-slate-50 border-t border-slate-100 text-center">
                    <a href="{{ route('community.blog.index') }}" class="text-xs font-bold text-blue-700 hover:underline">
                        {{ __('home.explore_all_stories') }} &rarr;
                    </a>
                </div>

            </div>

            {{-- 2. LATEST NEWS (ඊළඟට Latest News) --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm transition hover:shadow-md">

                <div class="flex items-center justify-between border-b border-slate-100 bg-gradient-to-r from-emerald-50/80 to-transparent p-5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 text-lg shadow-sm">
                            📰
                        </span>
                        <div>
                            <h3 class="font-black text-slate-900 text-base">
                                {{ $settingTr?->news_title ?: ($locale === 'si' ? 'නවතම පුවත්' : ($locale === 'ta' ? 'சமீபத்திய செய்திகள்' : 'Latest News')) }}
                            </h3>
                            <p class="text-[11px] text-slate-400 font-medium">
                                {{ $settingTr?->news_subtitle ?: ($locale === 'si' ? 'ලග්ගල ප්‍රජා තොරතුරු සහ පුවත්' : ($locale === 'ta' ? 'லக்கல சமூக செய்திகள்' : 'Laggala community updates')) }}
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('community.news.index') }}"
                       class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100">
                        {{ __('home.view') }} <i class="bi bi-chevron-right text-[10px]"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($latestNews as $news)
                        @php
                            $tn = $news->translationFor($locale) ?? $news->translationFor('en') ?? $news->translations->first();
                        @endphp
                        <a href="{{ url('/community/news/' . ($tn?->slug ?? $news->id)) }}"
                           class="group flex gap-3.5 p-4 transition hover:bg-emerald-50/40">
                            <div class="flex h-12 w-12 flex-shrink-0 flex-col items-center justify-center rounded-2xl bg-emerald-100 text-emerald-800 font-black shadow-sm">
                                <span class="text-base leading-none">{{ $news->published_at?->format('d') ?? '01' }}</span>
                                <span class="text-[9px] uppercase tracking-wider">{{ $news->published_at?->format('M') ?? 'SEP' }}</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="line-clamp-2 text-sm font-bold leading-snug text-slate-800 group-hover:text-emerald-700 transition-colors">
                                    {{ $tn?->title ?? 'Laggala Community Bulletin' }}
                                </h4>
                                <p class="mt-1 text-[11px] font-semibold text-emerald-600">
                                    {{ __('home.read_bulletin') }} &rarr;
                                </p>
                            </div>
                        </a>
                    @empty
                        {{-- Curated Fallback News Items --}}
                        <a href="{{ route('community.news.index') }}" class="group flex gap-3.5 p-4 transition hover:bg-emerald-50/40">
                            <div class="flex h-12 w-12 flex-shrink-0 flex-col items-center justify-center rounded-2xl bg-emerald-100 text-emerald-800 font-black shadow-sm">
                                <span class="text-base leading-none">28</span>
                                <span class="text-[9px] uppercase tracking-wider">SEP</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="line-clamp-2 text-sm font-bold leading-snug text-slate-800 group-hover:text-emerald-700 transition-colors">
                                    {{ __('home.fb_news1_title') }}
                                </h4>
                                <p class="mt-1 text-[11px] font-semibold text-emerald-600">
                                    {{ __('home.read_bulletin') }} &rarr;
                                </p>
                            </div>
                        </a>

                        <a href="{{ route('community.news.index') }}" class="group flex gap-3.5 p-4 transition hover:bg-emerald-50/40">
                            <div class="flex h-12 w-12 flex-shrink-0 flex-col items-center justify-center rounded-2xl bg-emerald-100 text-emerald-800 font-black shadow-sm">
                                <span class="text-base leading-none">22</span>
                                <span class="text-[9px] uppercase tracking-wider">SEP</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="line-clamp-2 text-sm font-bold leading-snug text-slate-800 group-hover:text-emerald-700 transition-colors">
                                    {{ __('home.fb_news2_title') }}
                                </h4>
                                <p class="mt-1 text-[11px] font-semibold text-emerald-600">
                                    {{ __('home.read_bulletin') }} &rarr;
                                </p>
                            </div>
                        </a>
                    @endforelse
                </div>

                <div class="p-3 bg-slate-50 border-t border-slate-100 text-center">
                    <a href="{{ route('community.news.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">
                        {{ __('home.view_all_news') }} &rarr;
                    </a>
                </div>

            </div>

            {{-- 3. LATEST EVENT (ඉන්පසු Latest Event) --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm transition hover:shadow-md">

                <div class="flex items-center justify-between border-b border-slate-100 bg-gradient-to-r from-cyan-50/80 to-transparent p-5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-cyan-100 text-cyan-700 text-lg shadow-sm">
                            📅
                        </span>
                        <div>
                            <h3 class="font-black text-slate-900 text-base">
                                {{ $settingTr?->events_title ?: ($locale === 'si' ? 'ඉදිරි සිදුවීම්' : ($locale === 'ta' ? 'வரவிருக்கும் நிகழ்வுகள்' : 'Latest Events')) }}
                            </h3>
                            <p class="text-[11px] text-slate-400 font-medium">
                                {{ $settingTr?->events_subtitle ?: ($locale === 'si' ? 'ලග්ගල ප්‍රදේශයේ ඉදිරි ක්‍රියාකාරකම්' : ($locale === 'ta' ? 'லக்கலவில் என்ன நடக்கிறது' : "What's happening in Laggala")) }}
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('community.events.index') }}"
                       class="inline-flex items-center gap-1 rounded-full bg-cyan-50 px-3 py-1 text-xs font-bold text-cyan-700 transition hover:bg-cyan-100">
                        {{ __('home.view') }} <i class="bi bi-chevron-right text-[10px]"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($latestEvents as $event)
                        @php
                            $te = $event->translationFor($locale) ?? $event->translationFor('en') ?? $event->translations->first();
                        @endphp
                        <a href="{{ route('community.events.index') }}"
                           class="group flex gap-3.5 p-4 transition hover:bg-cyan-50/40">
                            <div class="flex h-12 w-12 flex-shrink-0 flex-col items-center justify-center rounded-2xl bg-cyan-100 text-cyan-800 font-black shadow-sm">
                                <span class="text-base leading-none">{{ $event->start_date?->format('d') ?? '05' }}</span>
                                <span class="text-[9px] uppercase tracking-wider">{{ $event->start_date?->format('M') ?? 'OCT' }}</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="line-clamp-2 text-sm font-bold leading-snug text-slate-800 group-hover:text-cyan-700 transition-colors">
                                    {{ $te?->title ?? 'Upcoming Community Gathering' }}
                                </h4>
                                <p class="mt-1 text-[11px] font-semibold text-cyan-600">
                                    {{ __('home.event_details') }} &rarr;
                                </p>
                            </div>
                        </a>
                    @empty
                        {{-- Curated Fallback Event Items --}}
                        <a href="{{ route('community.events.index') }}" class="group flex gap-3.5 p-4 transition hover:bg-cyan-50/40">
                            <div class="flex h-12 w-12 flex-shrink-0 flex-col items-center justify-center rounded-2xl bg-cyan-100 text-cyan-800 font-black shadow-sm">
                                <span class="text-base leading-none">12</span>
                                <span class="text-[9px] uppercase tracking-wider">OCT</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="line-clamp-2 text-sm font-bold leading-snug text-slate-800 group-hover:text-cyan-700 transition-colors">
                                    {{ __('home.fb_event1_title') }}
                                </h4>
                                <p class="mt-1 text-[11px] text-slate-400">
                                    📍 {{ __('home.fb_event1_loc') }}
                                </p>
                            </div>
                        </a>

                        <a href="{{ route('community.events.index') }}" class="group flex gap-3.5 p-4 transition hover:bg-cyan-50/40">
                            <div class="flex h-12 w-12 flex-shrink-0 flex-col items-center justify-center rounded-2xl bg-cyan-100 text-cyan-800 font-black shadow-sm">
                                <span class="text-base leading-none">25</span>
                                <span class="text-[9px] uppercase tracking-wider">OCT</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="line-clamp-2 text-sm font-bold leading-snug text-slate-800 group-hover:text-cyan-700 transition-colors">
                                    {{ __('home.fb_event2_title') }}
                                </h4>
                                <p class="mt-1 text-[11px] text-slate-400">
                                    📍 {{ __('home.fb_event2_loc') }}
                                </p>
                            </div>
                        </a>
                    @endforelse
                </div>

                <div class="p-3 bg-slate-50 border-t border-slate-100 text-center">
                    <a href="{{ route('community.events.index') }}" class="text-xs font-bold text-cyan-700 hover:underline">
                        {{ __('home.explore_all_events') }} &rarr;
                    </a>
                </div>

            </div>

        </div>

    </div>
</section>

{{-- ============================================================
     WEATHER & TRAVEL SAFETY BANNER (Connecting to Weather Feature)
     ============================================================ --}}
<section class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 p-7 text-white shadow-xl sm:p-9 border border-emerald-500/20">

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-bold text-emerald-300 border border-emerald-500/30">
                    <i class="bi bi-cloud-sun-fill"></i> {{ __('home.live_conditions') }}
                </span>
                <h3 class="mt-3 text-2xl font-black sm:text-3xl text-white">
                    {{ $settingTr?->weather_title ?: ($locale === 'si' ? 'නකල්ස් කඳුවැටියේ සංචාරය කිරීමට සැලසුම් කරනවාද?' : ($locale === 'ta' ? 'நக்கிள்ஸ் மலைத்தொடரில் மலையேற்றம் செய்ய திட்டமிடுகிறீர்களா?' : 'Planning a Hike in the Knuckles Range?')) }}
                </h3>
                <p class="mt-2 text-sm text-slate-300">
                    {{ $settingTr?->weather_subtitle ?: ($locale === 'si' ? 'ලග්ගල කඳුකරයේ කාලගුණය ඉක්මනින් වෙනස් විය හැක. ගමන ආරම්භ කිරීමට පෙර කාලගුණය සහ මාර්ග ආරක්ෂාව පිළිබඳ පරීක්ෂා කරන්න.' : ($locale === 'ta' ? 'லக்கல மலைப்பகுதியின் வானிலை விரைவாக மாறக்கூடும். புறப்படுவதற்கு முன் சமீபத்திய வானிலை அவதானிப்புகள் மற்றும் பாதை பாதுகாப்பை சரிபார்க்கவும்.' : 'Mountain weather in Laggala can change rapidly. Check recent weather observations, rainfall levels, and trail safety before setting off.')) }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
                <a href="{{ route('plan.weather.index') }}"
                   class="rounded-2xl bg-emerald-500 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-emerald-500/30 transition hover:bg-emerald-400 hover:-translate-y-0.5">
                    <i class="bi bi-eye-fill me-2"></i> {{ __('home.view_weather_safety') }}
                </a>
                <a href="{{ route('plan.transport.index') }}"
                   class="rounded-2xl border border-white/20 bg-white/10 px-5 py-3.5 text-sm font-bold text-white backdrop-blur-md transition hover:bg-white/20">
                    <i class="bi bi-bus-front me-2 text-cyan-300"></i> {{ __('home.public_transport_info') }}
                </a>
            </div>
        </div>

    </div>
</section>

{{-- ============================================================
     COMMUNITY FORUM CTA
     ============================================================ --}}
<section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-700 via-teal-700 to-sky-800 px-7 py-10 text-white shadow-xl sm:px-10">

        <div class="absolute -right-10 -top-20 h-56 w-56 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-24 left-1/3 h-64 w-64 rounded-full bg-cyan-300/10"></div>

        <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <div class="mb-2 text-xs font-bold uppercase tracking-[0.2em] text-emerald-200">
                    {{ __('home.forum_tag') }}
                </div>
                <h2 class="text-2xl font-black sm:text-3xl">
                    {{ __('home.forum_heading') }}
                </h2>
                <p class="mt-2 max-w-2xl text-sm text-white/85">
                    {{ __('home.forum_body') }}
                </p>
            </div>

            <a href="{{ route('community.forum.index') }}"
               class="inline-flex flex-shrink-0 items-center justify-center rounded-2xl bg-white px-7 py-4 text-sm font-black text-emerald-700 shadow-xl transition hover:-translate-y-1 hover:bg-emerald-50">
                <i class="bi bi-chat-dots-fill me-2"></i>
                {{ __('home.visit_forum_btn') }}
                <span class="ml-2">&rarr;</span>
            </a>
        </div>

    </div>
</section>

@endsection

