@extends('layouts.public')

@section('title', __('map.meta_title'))

@section('content')

{{-- Leaflet CSS CDN fallback --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    /* Force Map Container Dimensions */
    #osmInteractiveMap {
        height: 680px !important;
        min-height: 550px !important;
        width: 100% !important;
        display: block !important;
        z-index: 10;
        border-radius: 1.5rem;
    }

    @media (max-width: 768px) {
        #osmInteractiveMap {
            height: 500px !important;
            min-height: 420px !important;
        }
    }

    /* Leaflet Popup Styling */
    .leaflet-popup-content-wrapper {
        padding: 0 !important;
        overflow: hidden !important;
        border-radius: 1.25rem !important;
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.25), 0 10px 15px -5px rgba(0, 0, 0, 0.15) !important;
        border: 1px solid rgba(226, 232, 240, 0.95) !important;
    }
    .leaflet-popup-content {
        margin: 0 !important;
        line-height: 1.4 !important;
        width: 300px !important;
    }
    .leaflet-popup-tip {
        background: white !important;
    }

    /* Distinct Pin Markers for Categories */
    .custom-pin-node {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .custom-pin-node:hover {
        transform: scale(1.2) translateY(-4px);
        z-index: 1000 !important;
    }
    .custom-pin-bubble {
        width: 40px;
        height: 40px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        border: 2.5px solid #ffffff;
    }
    .custom-pin-bubble i {
        transform: rotate(45deg);
        color: #ffffff;
        font-size: 15px;
    }

    /* Mobile Signal Radar Marker */
    .coverage-signal-node {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        width: 30px;
        height: 30px;
    }
    .coverage-signal-center {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 8px;
        z-index: 2;
    }
    .coverage-signal-wave {
        position: absolute;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        opacity: 0.6;
        animation: radar-wave-anim 2s infinite cubic-bezier(0, 0, 0.2, 1);
        z-index: 1;
    }
    @keyframes radar-wave-anim {
        0% { transform: scale(0.5); opacity: 0.9; }
        80% { transform: scale(1.7); opacity: 0; }
        100% { transform: scale(1.7); opacity: 0; }
    }
</style>

<div class="bg-slate-50 min-h-screen">

    {{-- ============================================================
         1. HERO HEADER BANNER
         ============================================================ --}}
    <section class="relative bg-gradient-to-br from-emerald-950 via-slate-900 to-teal-950 text-white py-8 lg:py-10 overflow-hidden border-b border-emerald-900/30">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold uppercase tracking-wider mb-2">
                        <i class="fas fa-map-location-dot"></i>
                        <span>{{ __('map.explore_badge') }}</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white">
                        {{ __('map.title') }}
                    </h1>

                    <p class="mt-1 text-xs sm:text-sm text-slate-300 leading-relaxed max-w-2xl">
                        {{ __('map.subtitle') }}
                    </p>
                </div>

                {{-- Action / Quick links --}}
                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('explore.destinations.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 text-xs font-semibold text-white hover:bg-white/20 transition">
                        <i class="fas fa-compass"></i>
                        <span>{{ __('destinations.title') }}</span>
                    </a>

                    <a href="{{ route('plan.coverage.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-orange-600 px-4 py-2 text-xs font-bold text-white shadow-lg hover:bg-orange-500 transition">
                        <i class="fas fa-satellite-dish"></i>
                        <span>{{ __('map.add_coverage_report') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         2. MAP CONTROL BAR: CATEGORY FILTERS & TOGGLES
         ============================================================ --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-6 pb-3">
        <div class="bg-white rounded-2xl border border-gray-200/90 shadow-sm p-4 space-y-3.5">

            {{-- Row 1: Distinct Place Category Buttons --}}
            <div class="flex items-center justify-between flex-wrap gap-2 pb-3 border-b border-gray-100">
                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2" id="placeCategoryButtons">
                    {{-- 1. All Places --}}
                    <button type="button" data-filter="all"
                            onclick="selectCategoryFilter('all')"
                            class="cat-filter-btn inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold transition bg-emerald-600 text-white shadow-sm">
                        <span>🌟</span>
                        <span>{{ __('map.all_places') }}</span>
                        <span class="rounded-full bg-white/20 px-2 py-0.5 text-[10px]" id="countAllPlaces">0</span>
                    </button>

                    {{-- 2. Destinations (Green) --}}
                    <button type="button" data-filter="destinations"
                            onclick="selectCategoryFilter('destinations')"
                            class="cat-filter-btn inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                        <span>{{ __('map.destinations') }}</span>
                        <span class="rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 px-1.5 py-0.2 text-[10px]" id="countDest">0</span>
                    </button>

                    {{-- 3. Culture & Heritage (Amber) --}}
                    <button type="button" data-filter="culture_heritage"
                            onclick="selectCategoryFilter('culture_heritage')"
                            class="cat-filter-btn inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span>
                        <span>{{ __('map.culture_heritage') }}</span>
                        <span class="rounded-full bg-amber-50 text-amber-800 border border-amber-200 px-1.5 py-0.2 text-[10px]" id="countHeritage">0</span>
                    </button>

                    {{-- 4. Hotels & Stay (Purple) --}}
                    <button type="button" data-filter="stay_eat"
                            onclick="selectCategoryFilter('stay_eat')"
                            class="cat-filter-btn inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                        <span>{{ __('map.stay_eat') }}</span>
                        <span class="rounded-full bg-purple-50 text-purple-800 border border-purple-200 px-1.5 py-0.2 text-[10px]" id="countStay">0</span>
                    </button>

                    {{-- 5. Outdoor Adventure (Cyan) --}}
                    <button type="button" data-filter="adventure"
                            onclick="selectCategoryFilter('adventure')"
                            class="cat-filter-btn inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                        <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                        <span>{{ __('map.adventure') }}</span>
                        <span class="rounded-full bg-cyan-50 text-cyan-800 border border-cyan-200 px-1.5 py-0.2 text-[10px]" id="countAdventure">0</span>
                    </button>

                    {{-- 6. Services & Institutions (Blue) --}}
                    <button type="button" data-filter="services"
                            onclick="selectCategoryFilter('services')"
                            class="cat-filter-btn inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        <span>{{ __('map.services') }}</span>
                        <span class="rounded-full bg-blue-50 text-blue-800 border border-blue-200 px-1.5 py-0.2 text-[10px]" id="countServices">0</span>
                    </button>
                </div>

                {{-- USER REQUESTED MOBILE COVERAGE TOGGLE (Hidden by default!) --}}
                <div class="flex items-center gap-2">
                    <button type="button" id="toggleCoverageBtn"
                            onclick="toggleMobileCoverageLayer()"
                            class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-gray-50 px-3.5 py-2 text-xs font-bold text-gray-700 hover:bg-orange-50 hover:text-orange-700 hover:border-orange-300 transition shadow-sm">
                        <i class="fas fa-tower-broadcast text-orange-500"></i>
                        <span id="coverageToggleLabel">{{ __('map.toggle_mobile_coverage') }}</span>
                        <span class="rounded-full bg-orange-100 text-orange-800 px-1.5 py-0.2 text-[10px] font-bold" id="countCoverage">0</span>
                    </button>
                </div>
            </div>

            {{-- Row 2: Live Search & Secondary Controls --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                {{-- Search Box --}}
                <div class="w-full sm:w-80 relative">
                    <input type="text"
                           id="mapSearchInput"
                           placeholder="{{ __('map.search_placeholder') }}"
                           oninput="onSearchFilterChanged()"
                           class="w-full rounded-xl border border-gray-300 bg-gray-50/70 pl-9 pr-9 py-2 text-xs text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-emerald-500 transition">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-xs">
                        <i class="fas fa-search"></i>
                    </div>
                    <button type="button" id="clearSearchBtn"
                            onclick="clearMapSearch()"
                            class="hidden absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 text-xs">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Status & Quick Actions --}}
                <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
                    <span class="text-xs font-semibold text-gray-600" id="spotsCountStatusLabel">
                        {{ __('map.spots_found', ['count' => '...']) }}
                    </span>

                    <button type="button" id="toggleListDrawerBtn"
                            onclick="togglePlacesDrawer()"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-200 transition">
                        <i class="fas fa-list"></i>
                        <span id="drawerBtnLabel">{{ __('map.toggle_list') }}</span>
                    </button>
                </div>
            </div>

        </div>
    </section>

    {{-- ============================================================
         3. MAIN MAP CONTAINER (CENTRAL VISUAL EMPHASIS)
         ============================================================ --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-16">
        <div class="relative bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden">

            {{-- THE LEAFLET MAP ELEMENT --}}
            <div id="osmInteractiveMap"></div>

            {{-- FLOATING MAP CONTROLS (TOP RIGHT) --}}
            <div class="absolute top-4 right-4 z-[400] flex flex-col gap-2">
                {{-- Recenter / Fit All --}}
                <button type="button"
                        onclick="fitMapAllBounds()"
                        title="Recenter Map"
                        class="w-10 h-10 rounded-xl bg-white shadow-md border border-gray-200 flex items-center justify-center text-gray-700 hover:bg-emerald-600 hover:text-white transition">
                    <i class="fas fa-compress-arrows-alt text-xs"></i>
                </button>

                {{-- Locate User GPS --}}
                <button type="button"
                        onclick="locateUserPosition()"
                        title="Locate Me"
                        class="w-10 h-10 rounded-xl bg-white shadow-md border border-gray-200 flex items-center justify-center text-gray-700 hover:bg-blue-600 hover:text-white transition">
                    <i class="fas fa-crosshairs text-xs"></i>
                </button>

                {{-- Toggle Legend --}}
                <button type="button"
                        onclick="toggleMapLegend()"
                        title="{{ __('map.legend') }}"
                        class="w-10 h-10 rounded-xl bg-white shadow-md border border-gray-200 flex items-center justify-center text-gray-700 hover:bg-gray-800 hover:text-white transition">
                    <i class="fas fa-layer-group text-xs"></i>
                </button>
            </div>

            {{-- FLOATING MAP LEGEND (BOTTOM LEFT) --}}
            <div id="mapLegendBox" class="absolute bottom-5 left-5 z-[400] bg-white/95 backdrop-blur-md rounded-2xl p-4 border border-gray-200 shadow-xl max-w-xs transition-opacity duration-200">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100 mb-2.5">
                    <span class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                        <i class="fas fa-info-circle text-emerald-600"></i> {{ __('map.legend') }}
                    </span>
                    <button type="button" onclick="toggleMapLegend()" class="text-gray-400 hover:text-gray-600 text-xs">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-2 text-xs text-gray-700">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3.5 h-3.5 rounded-full bg-emerald-600 border-2 border-white shadow-sm inline-block"></span>
                        <span class="font-semibold">{{ __('map.destinations') }}</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-3.5 h-3.5 rounded-full bg-amber-600 border-2 border-white shadow-sm inline-block"></span>
                        <span class="font-semibold">{{ __('map.culture_heritage') }}</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-3.5 h-3.5 rounded-full bg-purple-600 border-2 border-white shadow-sm inline-block"></span>
                        <span class="font-semibold">{{ __('map.stay_eat') }}</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-3.5 h-3.5 rounded-full bg-cyan-600 border-2 border-white shadow-sm inline-block"></span>
                        <span class="font-semibold">{{ __('map.adventure') }}</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-3.5 h-3.5 rounded-full bg-blue-600 border-2 border-white shadow-sm inline-block"></span>
                        <span class="font-semibold">{{ __('map.services') }}</span>
                    </div>

                    <div id="coverageLegendSection" class="hidden pt-2 border-t border-gray-100">
                        <span class="block text-[10px] font-bold text-orange-600 uppercase tracking-wider mb-1">
                            {{ __('map.mobile_coverage') }} (Active)
                        </span>
                        <div class="grid grid-cols-2 gap-1 text-[10px]">
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> {{ __('map.signal_excellent') }}</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> {{ __('map.signal_good') }}</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-yellow-400"></span> {{ __('map.signal_fair') }}</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-orange-500"></span> {{ __('map.signal_poor') }}</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span> {{ __('map.signal_none') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SLIDING PLACES LIST DRAWER (OVERLAY ON MAP, DOES NOT BREAK MAP CANVAS) --}}
            <aside id="placesDrawer" class="hidden absolute top-0 right-0 bottom-0 w-full sm:w-96 bg-white/95 backdrop-blur-md z-[500] border-l border-gray-200 shadow-2xl flex flex-col transition-all duration-300">
                <div class="p-3.5 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-list-ul text-emerald-600"></i>
                        <span class="text-xs font-bold text-gray-900">{{ __('map.toggle_list') }}</span>
                        <span class="rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold px-1.5 py-0.2" id="drawerSpotsCount">0</span>
                    </div>

                    <button type="button" onclick="togglePlacesDrawer()" class="w-7 h-7 rounded-lg bg-gray-200 text-gray-600 hover:bg-gray-300 flex items-center justify-center text-xs">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div id="drawerScrollList" class="flex-1 overflow-y-auto p-3 space-y-2.5 divide-y divide-gray-100">
                    {{-- Dynamically populated via JS --}}
                </div>
            </aside>

        </div>
    </section>

</div>

@php
    $currentLocale = app()->getLocale();

    // Prepare serializable spots collection
    $mapSpots = collect();

    // 1. Destinations
    foreach($destinations as $d) {
        $trans = $d->translationFor($currentLocale);
        $cover = $d->coverImage();
        $slug = $trans?->slug ?? $d->translations->first()?->slug ?? $d->id;
        $categoryName = $d->interests->first()?->name ?? __('map.destinations');
        $catSlug = strtolower($d->interests->first()?->slug ?? 'destination');
        $catLabel = __('destinations.categories_map.' . $catSlug);
        if ($catLabel === 'destinations.categories_map.' . $catSlug) {
            $catLabel = $categoryName;
        }

        $planner = $d->plannerDetails;

        $mapSpots->push([
            'id' => 'dest_' . $d->id,
            'db_id' => $d->id,
            'category_type' => 'destinations',
            'type_badge' => __('map.badge_destinations'),
            'category' => $catLabel,
            'title' => $trans?->name ?? 'Destination #' . $d->id,
            'location' => $trans?->location_name ?? 'Laggala',
            'short_desc' => $trans?->short_description ?? '',
            'lat' => (float) $d->latitude,
            'lng' => (float) $d->longitude,
            'image' => $cover ? asset('storage/' . $cover->image_path) : null,
            'rating' => $d->average_rating > 0 ? number_format($d->average_rating, 1) : null,
            'reviews_count' => $d->review_count,
            'url' => route('explore.destinations.show', $slug),
            'google_maps_url' => "https://www.google.com/maps/search/?api=1&query={$d->latitude},{$d->longitude}",
            'nearest_bus_stop' => $planner?->nearest_bus_stop,
            'bus_routes' => $planner?->bus_routes,
            'recommended_vehicle' => $planner?->recommended_vehicle,
            'mobile_signal' => $planner?->mobile_signal_level,
            'best_networks' => $planner?->best_mobile_networks ? (is_array($planner->best_mobile_networks) ? implode(', ', $planner->best_mobile_networks) : $planner->best_mobile_networks) : null,
            'connectivity_notes' => $planner?->connectivity_notes,
            'icon' => 'fa-mountain-sun',
            'color' => '#059669', // Emerald
        ]);
    }

    // 2. Culture & Heritage
    foreach($exploreItems->where('type', 'culture-heritage') as $item) {
        $trans = $item->translationFor($currentLocale);
        $cover = $item->images->first();
        $slug = $trans?->slug ?? $item->translations->first()?->slug ?? $item->id;

        $mapSpots->push([
            'id' => 'exp_' . $item->id,
            'db_id' => $item->id,
            'category_type' => 'culture_heritage',
            'type_badge' => __('map.badge_heritage'),
            'category' => $item->category?->name ?? __('map.culture_heritage'),
            'title' => $trans?->name ?? 'Heritage #' . $item->id,
            'location' => $trans?->location_name ?? 'Laggala',
            'short_desc' => $trans?->short_description ?? '',
            'lat' => (float) $item->latitude,
            'lng' => (float) $item->longitude,
            'image' => $cover ? asset('storage/' . $cover->image_path) : null,
            'rating' => null,
            'reviews_count' => 0,
            'url' => route('explore.culture-heritage.show', $slug),
            'google_maps_url' => "https://www.google.com/maps/search/?api=1&query={$item->latitude},{$item->longitude}",
            'nearest_bus_stop' => null,
            'bus_routes' => null,
            'recommended_vehicle' => null,
            'mobile_signal' => null,
            'best_networks' => null,
            'connectivity_notes' => null,
            'icon' => 'fa-landmark',
            'color' => '#d97706', // Amber
        ]);
    }

    // 3. Outdoor Adventure
    foreach($exploreItems->where('type', 'outdoor-adventure') as $item) {
        $trans = $item->translationFor($currentLocale);
        $cover = $item->images->first();
        $slug = $trans?->slug ?? $item->translations->first()?->slug ?? $item->id;

        $mapSpots->push([
            'id' => 'adv_' . $item->id,
            'db_id' => $item->id,
            'category_type' => 'adventure',
            'type_badge' => __('map.badge_adventure'),
            'category' => $item->category?->name ?? __('map.adventure'),
            'title' => $trans?->name ?? 'Adventure #' . $item->id,
            'location' => $trans?->location_name ?? 'Laggala',
            'short_desc' => $trans?->short_description ?? '',
            'lat' => (float) $item->latitude,
            'lng' => (float) $item->longitude,
            'image' => $cover ? asset('storage/' . $cover->image_path) : null,
            'rating' => null,
            'reviews_count' => 0,
            'url' => route('explore.outdoor-adventure.show', $slug),
            'google_maps_url' => "https://www.google.com/maps/search/?api=1&query={$item->latitude},{$item->longitude}",
            'nearest_bus_stop' => null,
            'bus_routes' => null,
            'recommended_vehicle' => null,
            'mobile_signal' => null,
            'best_networks' => null,
            'connectivity_notes' => null,
            'icon' => 'fa-person-hiking',
            'color' => '#0891b2', // Cyan
        ]);
    }

    // 4. Hotels & Accommodations (Stay & Dining)
    foreach($stayEatItems as $se) {
        $trans = $se->translationFor($currentLocale);
        $cover = $se->images->first();
        $slug = $trans?->slug ?? $se->translations->first()?->slug ?? $se->id;
        $section = $se->type ?? 'stay';

        $mapSpots->push([
            'id' => 'stay_' . $se->id,
            'db_id' => $se->id,
            'category_type' => 'stay_eat',
            'type_badge' => __('map.badge_hotels'),
            'category' => ucfirst($se->type ?? 'Accommodation'),
            'title' => $trans?->name ?? 'Hotel #' . $se->id,
            'location' => $trans?->address ?? 'Laggala',
            'short_desc' => $trans?->description ? Str::limit(strip_tags($trans->description), 100) : '',
            'lat' => (float) $se->latitude,
            'lng' => (float) $se->longitude,
            'image' => $cover ? asset('storage/' . $cover->image_path) : null,
            'rating' => null,
            'reviews_count' => 0,
            'url' => route('stay-eat.show', ['section' => $section, 'slug' => $slug]),
            'google_maps_url' => "https://www.google.com/maps/search/?api=1&query={$se->latitude},{$se->longitude}",
            'nearest_bus_stop' => null,
            'bus_routes' => null,
            'recommended_vehicle' => null,
            'mobile_signal' => null,
            'best_networks' => null,
            'connectivity_notes' => null,
            'icon' => 'fa-hotel',
            'color' => '#7c3aed', // Purple
        ]);
    }

    // 5. Public Services & Institutions
    foreach($servicePlaces as $sp) {
        $trans = $sp->translationFor($currentLocale);
        $section = in_array($sp->category, ['health', 'shops-businesses', 'banks-atms', 'fuel-ev', 'education']) ? $sp->category : 'health';
        $slug = $trans?->slug ?? $sp->translations->first()?->slug ?? $sp->id;

        $mapSpots->push([
            'id' => 'serv_' . $sp->id,
            'db_id' => $sp->id,
            'category_type' => 'services',
            'type_badge' => __('map.badge_services'),
            'category' => ucfirst(str_replace('-', ' ', $sp->category ?? 'Public Service')),
            'title' => $trans?->name ?? 'Service #' . $sp->id,
            'location' => $trans?->address ?? 'Laggala',
            'short_desc' => $trans?->description ? Str::limit(strip_tags($trans->description), 100) : '',
            'lat' => (float) $sp->latitude,
            'lng' => (float) $sp->longitude,
            'image' => null,
            'rating' => null,
            'reviews_count' => 0,
            'url' => route('services.places.show', ['section' => $section, 'slug' => $slug]),
            'google_maps_url' => "https://www.google.com/maps/search/?api=1&query={$sp->latitude},{$sp->longitude}",
            'nearest_bus_stop' => null,
            'bus_routes' => null,
            'recommended_vehicle' => null,
            'mobile_signal' => null,
            'best_networks' => null,
            'connectivity_notes' => null,
            'icon' => 'fa-building-columns',
            'color' => '#2563eb', // Blue
        ]);
    }

    foreach($institutions as $inst) {
        $trans = $inst->translationFor($currentLocale);
        $slug = $trans?->slug ?? $inst->translations->first()?->slug ?? $inst->id;

        $mapSpots->push([
            'id' => 'inst_' . $inst->id,
            'db_id' => $inst->id,
            'category_type' => 'services',
            'type_badge' => __('map.badge_services'),
            'category' => 'Government Office',
            'title' => $trans?->name ?? 'Institution #' . $inst->id,
            'location' => $trans?->address ?? 'Laggala',
            'short_desc' => $trans?->description ? Str::limit(strip_tags($trans->description), 100) : '',
            'lat' => (float) $inst->latitude,
            'lng' => (float) $inst->longitude,
            'image' => null,
            'rating' => null,
            'reviews_count' => 0,
            'url' => route('services.institutions.show', $slug),
            'google_maps_url' => "https://www.google.com/maps/search/?api=1&query={$inst->latitude},{$inst->longitude}",
            'nearest_bus_stop' => null,
            'bus_routes' => null,
            'recommended_vehicle' => null,
            'mobile_signal' => null,
            'best_networks' => null,
            'connectivity_notes' => null,
            'icon' => 'fa-building-columns',
            'color' => '#1d4ed8', // Dark Blue
        ]);
    }

    // 6. Mobile Coverage Reports (Prepared for optional user toggle)
    $mapCoverage = collect();
    foreach($coverageReports as $cr) {
        $signalColor = match(strtolower($cr->signal_strength)) {
            'excellent' => '#22c55e',
            'good'      => '#10b981',
            'fair'      => '#eab308',
            'poor'      => '#f97316',
            'none'      => '#ef4444',
            default     => '#6b7280',
        };

        $signalLabel = match(strtolower($cr->signal_strength)) {
            'excellent' => __('map.signal_excellent'),
            'good'      => __('map.signal_good'),
            'fair'      => __('map.signal_fair'),
            'poor'      => __('map.signal_poor'),
            'none'      => __('map.signal_none'),
            default     => ucfirst($cr->signal_strength),
        };

        $mapCoverage->push([
            'id' => 'cov_' . $cr->id,
            'location_name' => $cr->location_name,
            'operator' => strtoupper($cr->network_operator),
            'operator_raw' => strtolower($cr->network_operator),
            'coverage_type' => strtoupper($cr->coverage_type),
            'signal_strength' => strtolower($cr->signal_strength),
            'signal_label' => $signalLabel,
            'signal_color' => $signalColor,
            'description' => $cr->description,
            'voice_call' => $cr->voice_call_quality ? ucfirst($cr->voice_call_quality) : null,
            'data_speed' => $cr->data_speed_rating ? ucfirst($cr->data_speed_rating) : null,
            'reporter' => $cr->reporter?->name ?? 'Verified Visitor',
            'reported_date' => $cr->reported_at ? $cr->reported_at->format('Y-m-d') : null,
            'lat' => (float) $cr->latitude,
            'lng' => (float) $cr->longitude,
            'google_maps_url' => "https://www.google.com/maps/search/?api=1&query={$cr->latitude},{$cr->longitude}",
        ]);
    }
@endphp

{{-- Leaflet JS CDN --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Server data injections
    const rawPlaces = @json($mapSpots);
    const rawCoverage = @json($mapCoverage);

    const i18n = {
        openInGoogleMaps: @json(__('map.open_in_google_maps')),
        viewDetails: @json(__('map.view_details')),
        category: @json(__('map.category')),
        operator: @json(__('map.operator')),
        signalQuality: @json(__('map.signal_quality')),
        voiceCalls: @json(__('map.voice_calls')),
        mobileData: @json(__('map.data_speed')),
        publicTransport: @json(__('map.public_transport')),
        nearestBusStop: @json(__('map.nearest_bus_stop')),
        busRoutes: @json(__('map.bus_routes')),
        mobileAdvisory: @json(__('map.mobile_advisory')),
        reportedBy: @json(__('map.reported_by')),
        reportedDate: @json(__('map.reported_date')),
        noPlacesFound: @json(__('map.no_places_found')),
        spotsFound: @json(__('map.spots_found', ['count' => ':count'])),
        showCoverage: @json(__('map.toggle_mobile_coverage')),
        hideCoverage: @json(__('map.mobile_coverage_on'))
    };

    let leafletMap = null;
    let placeMarkers = {};
    let coverageMarkers = {};
    let currentCategoryFilter = 'all';
    let isCoverageLayerActive = false; // USER REQUIREMENT: HIDDEN BY DEFAULT!
    let userLocationMarker = null;

    function initMapApp() {
        if (typeof L === 'undefined') {
            console.error('Leaflet is not loaded yet, retrying...');
            setTimeout(initMapApp, 100);
            return;
        }

        const mapEl = document.getElementById('osmInteractiveMap');
        if (!mapEl) return;

        // Initialize Map centered on Laggala Valley
        leafletMap = L.map('osmInteractiveMap', {
            zoomControl: false,
            attributionControl: true
        }).setView([7.545, 80.755], 11);

        // Add Zoom Control to Bottom-Right
        L.control.zoom({ position: 'bottomright' }).addTo(leafletMap);

        // OpenStreetMap Tile Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors'
        }).addTo(leafletMap);

        // Build all markers
        buildAllMarkers();

        // Update counts
        updateCountsBadges();

        // Initial render: show all places, coverage hidden
        applyActiveFilters();

        // Fit map bounds to places
        setTimeout(() => {
            leafletMap.invalidateSize();
            fitMapAllBounds();
        }, 150);

        setTimeout(() => {
            leafletMap.invalidateSize();
        }, 500);
    }

    // Run on load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMapApp);
    } else {
        initMapApp();
    }
    window.addEventListener('load', () => {
        if (leafletMap) leafletMap.invalidateSize();
    });

    // Create Distinct Pin Icon for Categories
    function createCategoryPinIcon(color, iconClass) {
        return L.divIcon({
            className: 'custom-pin-container',
            html: `
                <div class="custom-pin-node">
                    <div class="custom-pin-bubble" style="background-color: ${color};">
                        <i class="fas ${iconClass}"></i>
                    </div>
                </div>
            `,
            iconSize: [40, 50],
            iconAnchor: [20, 46],
            popupAnchor: [0, -42]
        });
    }

    // Create Radar Marker for Mobile Signal
    function createSignalRadarIcon(color) {
        return L.divIcon({
            className: 'custom-signal-container',
            html: `
                <div class="coverage-signal-node">
                    <div class="coverage-signal-wave" style="background-color: ${color};"></div>
                    <div class="coverage-signal-center" style="background-color: ${color};">
                        📶
                    </div>
                </div>
            `,
            iconSize: [30, 30],
            iconAnchor: [15, 15],
            popupAnchor: [0, -15]
        });
    }

    // Build all Leaflet markers in memory
    function buildAllMarkers() {
        placeMarkers = {};
        coverageMarkers = {};

        // 1. Build Places Markers (Destinations, Heritage, Hotels, Adventure, Services)
        rawPlaces.forEach(p => {
            if (!p.lat || !p.lng) return;

            const icon = createCategoryPinIcon(p.color, p.icon);
            const marker = L.marker([p.lat, p.lng], { icon: icon });

            const popupHtml = `
                <div class="text-gray-900 font-sans">
                    ${p.image ? `
                        <div class="relative w-full h-32 bg-gray-100 overflow-hidden">
                            <img src="${p.image}" alt="${p.title}" class="w-full h-full object-cover">
                            <span class="absolute top-2 left-2 rounded-full px-2 py-0.5 text-[10px] font-bold text-white shadow-sm" style="background-color: ${p.color};">
                                ${p.type_badge}
                            </span>
                        </div>
                    ` : `
                        <div class="p-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                            <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold text-white" style="background-color: ${p.color};">
                                ${p.type_badge}
                            </span>
                            <span class="text-[10px] text-gray-500 font-medium">${p.category}</span>
                        </div>
                    `}

                    <div class="p-3.5 space-y-2">
                        <h4 class="text-sm font-bold text-gray-900 leading-snug hover:text-emerald-700 transition">
                            ${p.title}
                        </h4>

                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                            <i class="fas fa-map-marker-alt text-emerald-600 text-[11px]"></i>
                            <span class="line-clamp-1">${p.location}</span>
                        </div>

                        ${p.rating ? `
                            <div class="flex items-center gap-1 text-xs">
                                <span class="text-amber-500 font-bold">★ ${p.rating}</span>
                                <span class="text-gray-400 text-[11px]">(${p.reviews_count || 0})</span>
                            </div>
                        ` : ''}

                        ${p.short_desc ? `
                            <p class="text-[11px] text-gray-600 line-clamp-2 leading-relaxed">
                                ${p.short_desc}
                            </p>
                        ` : ''}

                        ${(p.nearest_bus_stop || p.bus_routes) ? `
                            <div class="rounded-lg bg-emerald-50/70 p-2 border border-emerald-100/80 text-[10px] space-y-0.5">
                                <div class="font-bold text-emerald-900 flex items-center gap-1">
                                    <i class="fas fa-bus-alt text-emerald-600"></i> ${i18n.publicTransport}:
                                </div>
                                ${p.nearest_bus_stop ? `<div class="text-emerald-800">${i18n.nearestBusStop}: <strong>${p.nearest_bus_stop}</strong></div>` : ''}
                                ${p.bus_routes ? `<div class="text-emerald-700 font-mono">${p.bus_routes}</div>` : ''}
                            </div>
                        ` : ''}

                        ${(p.mobile_signal || p.best_networks) ? `
                            <div class="rounded-lg bg-orange-50/70 p-2 border border-orange-100/80 text-[10px] space-y-0.5">
                                <div class="font-bold text-orange-950 flex items-center gap-1">
                                    <i class="fas fa-signal text-orange-600"></i> ${i18n.mobileAdvisory}:
                                </div>
                                <div class="text-orange-900 capitalize font-semibold">${p.mobile_signal || ''} ${p.best_networks ? `(${p.best_networks})` : ''}</div>
                                ${p.connectivity_notes ? `<p class="text-[9px] text-orange-800/80 italic mt-0.5">${p.connectivity_notes}</p>` : ''}
                            </div>
                        ` : ''}

                        {{-- Action Buttons --}}
                        <div class="pt-2 border-t border-gray-100 flex flex-col gap-1.5">
                            {{-- OPEN IN GOOGLE MAPS BUTTON --}}
                            <a href="${p.google_maps_url}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 text-xs font-bold transition shadow-sm">
                                <i class="fab fa-google text-white"></i>
                                <span>${i18n.openInGoogleMaps}</span>
                                <i class="fas fa-external-link-alt text-[9px] opacity-75"></i>
                            </a>

                            ${p.url ? `
                                <a href="${p.url}"
                                   class="inline-flex items-center justify-center gap-1 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white py-1.5 px-3 text-xs font-bold transition shadow-sm">
                                    <span>${i18n.viewDetails}</span>
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;

            marker.bindPopup(popupHtml);
            placeMarkers[p.id] = marker;
        });

        // 2. Build Mobile Coverage Markers (Only shown when user requests!)
        rawCoverage.forEach(c => {
            if (!c.lat || !c.lng) return;

            const icon = createSignalRadarIcon(c.signal_color);
            const marker = L.marker([c.lat, c.lng], { icon: icon });

            const popupHtml = `
                <div class="text-gray-900 font-sans p-3.5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold text-white bg-slate-900">
                            ${c.operator}
                        </span>
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold text-white" style="background-color: ${c.signal_color};">
                            ${c.signal_label}
                        </span>
                    </div>

                    <h4 class="text-sm font-bold text-gray-900">
                        ${c.location_name}
                    </h4>

                    <div class="grid grid-cols-2 gap-1.5 bg-gray-50 rounded-xl p-2 text-[10px] text-gray-600">
                        <div>
                            <span class="text-gray-400 block">${i18n.operator}:</span>
                            <span class="font-bold text-gray-800">${c.operator}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block">Tech:</span>
                            <span class="font-bold text-gray-800">${c.coverage_type}</span>
                        </div>
                        ${c.voice_call ? `
                            <div>
                                <span class="text-gray-400 block">${i18n.voiceCalls}:</span>
                                <span class="font-bold text-emerald-700">${c.voice_call}</span>
                            </div>
                        ` : ''}
                        ${c.data_speed ? `
                            <div>
                                <span class="text-gray-400 block">${i18n.mobileData}:</span>
                                <span class="font-bold text-blue-700">${c.data_speed}</span>
                            </div>
                        ` : ''}
                    </div>

                    ${c.description ? `
                        <p class="text-[11px] text-gray-600 italic bg-amber-50/70 p-2 rounded-lg border border-amber-100">
                            "${c.description}"
                        </p>
                    ` : ''}

                    <div class="text-[10px] text-gray-400 flex items-center justify-between pt-1">
                        <span>${i18n.reportedBy}: ${c.reporter}</span>
                        <span>${c.reported_date || ''}</span>
                    </div>

                    <div class="pt-2 border-t border-gray-100 flex flex-col gap-1.5">
                        <a href="${c.google_maps_url}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 text-xs font-bold transition shadow-sm">
                            <i class="fab fa-google text-white"></i>
                            <span>${i18n.openInGoogleMaps}</span>
                            <i class="fas fa-external-link-alt text-[9px] opacity-75"></i>
                        </a>

                        <a href="{{ route('plan.coverage.index') }}"
                           class="inline-flex items-center justify-center gap-1 rounded-xl bg-orange-600 hover:bg-orange-700 text-white py-1.5 px-3 text-xs font-bold transition shadow-sm">
                            <span>+ Add Signal Report</span>
                        </a>
                    </div>
                </div>
            `;

            marker.bindPopup(popupHtml);
            coverageMarkers[c.id] = marker;
        });
    }

    function updateCountsBadges() {
        const destCount = rawPlaces.filter(p => p.category_type === 'destinations').length;
        const heritageCount = rawPlaces.filter(p => p.category_type === 'culture_heritage').length;
        const advCount = rawPlaces.filter(p => p.category_type === 'adventure').length;
        const stayCount = rawPlaces.filter(p => p.category_type === 'stay_eat').length;
        const servCount = rawPlaces.filter(p => p.category_type === 'services').length;
        const covCount = rawCoverage.length;

        document.getElementById('countAllPlaces').innerText = rawPlaces.length;
        document.getElementById('countDest').innerText = destCount;
        document.getElementById('countHeritage').innerText = heritageCount;
        document.getElementById('countAdventure').innerText = advCount;
        document.getElementById('countStay').innerText = stayCount;
        document.getElementById('countServices').innerText = servCount;
        document.getElementById('countCoverage').innerText = covCount;
    }

    // Category button clicked
    function selectCategoryFilter(cat) {
        currentCategoryFilter = cat;

        // Update button active styling
        document.querySelectorAll('.cat-filter-btn').forEach(btn => {
            const f = btn.getAttribute('data-filter');
            if (f === cat) {
                btn.className = 'cat-filter-btn inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold transition bg-emerald-600 text-white shadow-sm';
            } else {
                btn.className = 'cat-filter-btn inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition';
            }
        });

        applyActiveFilters();
    }

    // Toggle Mobile Coverage Layer (USER REQUIREMENT: ONLY WHEN REQUESTED!)
    function toggleMobileCoverageLayer() {
        isCoverageLayerActive = !isCoverageLayerActive;

        const btn = document.getElementById('toggleCoverageBtn');
        const label = document.getElementById('coverageToggleLabel');
        const legendSec = document.getElementById('coverageLegendSection');

        if (isCoverageLayerActive) {
            btn.className = 'inline-flex items-center gap-2 rounded-xl border border-orange-500 bg-orange-600 px-3.5 py-2 text-xs font-bold text-white shadow-md transition';
            label.innerText = i18n.hideCoverage;
            if (legendSec) legendSec.classList.remove('hidden');
        } else {
            btn.className = 'inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-gray-50 px-3.5 py-2 text-xs font-bold text-gray-700 hover:bg-orange-50 hover:text-orange-700 hover:border-orange-300 transition shadow-sm';
            label.innerText = i18n.showCoverage;
            if (legendSec) legendSec.classList.add('hidden');
        }

        applyActiveFilters();
    }

    function onSearchFilterChanged() {
        const query = document.getElementById('mapSearchInput').value.trim();
        const clearBtn = document.getElementById('clearSearchBtn');
        if (query) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        applyActiveFilters();
    }

    function clearMapSearch() {
        document.getElementById('mapSearchInput').value = '';
        document.getElementById('clearSearchBtn').classList.add('hidden');
        applyActiveFilters();
    }

    function getFilteredData() {
        const query = document.getElementById('mapSearchInput').value.toLowerCase().trim();

        // 1. Filter Places
        const places = rawPlaces.filter(p => {
            if (currentCategoryFilter !== 'all' && p.category_type !== currentCategoryFilter) {
                return false;
            }
            if (query) {
                const matchTitle = (p.title || '').toLowerCase().includes(query);
                const matchLoc = (p.location || '').toLowerCase().includes(query);
                const matchCat = (p.category || '').toLowerCase().includes(query);
                if (!matchTitle && !matchLoc && !matchCat) return false;
            }
            return true;
        });

        // 2. Filter Coverage (ONLY if layer is active!)
        let coverage = [];
        if (isCoverageLayerActive) {
            coverage = rawCoverage.filter(c => {
                if (query) {
                    const matchLoc = (c.location_name || '').toLowerCase().includes(query);
                    const matchOp = (c.operator || '').toLowerCase().includes(query);
                    if (!matchLoc && !matchOp) return false;
                }
                return true;
            });
        }

        return { places, coverage };
    }

    function applyActiveFilters() {
        if (!leafletMap) return;

        const { places, coverage } = getFilteredData();

        // Sync Place Markers on Map
        const visiblePlaceIds = new Set(places.map(p => p.id));
        rawPlaces.forEach(p => {
            const marker = placeMarkers[p.id];
            if (!marker) return;

            if (visiblePlaceIds.has(p.id)) {
                if (!leafletMap.hasLayer(marker)) leafletMap.addLayer(marker);
            } else {
                if (leafletMap.hasLayer(marker)) leafletMap.removeLayer(marker);
            }
        });

        // Sync Coverage Markers on Map (Only if active)
        const visibleCovIds = new Set(coverage.map(c => c.id));
        rawCoverage.forEach(c => {
            const marker = coverageMarkers[c.id];
            if (!marker) return;

            if (visibleCovIds.has(c.id)) {
                if (!leafletMap.hasLayer(marker)) leafletMap.addLayer(marker);
            } else {
                if (leafletMap.hasLayer(marker)) leafletMap.removeLayer(marker);
            }
        });

        // Update Status label
        const totalVisible = places.length + coverage.length;
        document.getElementById('spotsCountStatusLabel').innerText = i18n.spotsFound.replace(':count', totalVisible);

        // Update Drawer
        renderDrawerList({ places, coverage });
    }

    function renderDrawerList({ places, coverage }) {
        const listEl = document.getElementById('drawerScrollList');
        const countBadge = document.getElementById('drawerSpotsCount');
        const total = places.length + coverage.length;

        countBadge.innerText = total;

        if (total === 0) {
            listEl.innerHTML = `
                <div class="py-12 text-center text-gray-400">
                    <i class="fas fa-search-location text-3xl mb-2"></i>
                    <p class="text-xs text-gray-500 font-medium">${i18n.noPlacesFound}</p>
                </div>
            `;
            return;
        }

        let html = '';

        places.forEach(p => {
            html += `
                <div class="group p-3 rounded-2xl border border-gray-100 hover:border-emerald-200 bg-white hover:bg-emerald-50/40 transition cursor-pointer shadow-sm hover:shadow"
                     onclick="flyToMarker('${p.id}', ${p.lat}, ${p.lng})">
                    <div class="flex items-start gap-3">
                        ${p.image ? `
                            <img src="${p.image}" alt="${p.title}" class="w-14 h-14 rounded-xl object-cover shrink-0 bg-gray-100">
                        ` : `
                            <div class="w-14 h-14 rounded-xl shrink-0 flex items-center justify-center text-white text-base" style="background-color: ${p.color};">
                                <i class="fas ${p.icon}"></i>
                            </div>
                        `}

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-[10px] font-bold text-white px-2 py-0.2 rounded-md" style="background-color: ${p.color};">
                                    ${p.type_badge}
                                </span>
                                ${p.rating ? `<span class="text-[11px] font-bold text-amber-500">★ ${p.rating}</span>` : ''}
                            </div>

                            <h4 class="text-xs font-bold text-gray-900 group-hover:text-emerald-700 transition truncate mt-1">
                                ${p.title}
                            </h4>

                            <p class="text-[11px] text-gray-500 flex items-center gap-1 mt-0.5 truncate">
                                <i class="fas fa-map-marker-alt text-[10px] text-emerald-600"></i> ${p.location}
                            </p>

                            <div class="mt-2 flex items-center gap-2">
                                <a href="${p.google_maps_url}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   onclick="event.stopPropagation();"
                                   class="text-[10px] font-semibold text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                    <i class="fab fa-google text-red-500"></i> Google Maps
                                </a>

                                ${p.url ? `
                                    <span class="text-gray-300">•</span>
                                    <a href="${p.url}"
                                       onclick="event.stopPropagation();"
                                       class="text-[10px] font-semibold text-emerald-700 hover:text-emerald-900 hover:underline">
                                        ${i18n.viewDetails} →
                                    </a>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        coverage.forEach(c => {
            html += `
                <div class="group p-3 rounded-2xl border border-orange-100 hover:border-orange-300 bg-orange-50/20 hover:bg-orange-50/60 transition cursor-pointer shadow-sm hover:shadow"
                     onclick="flyToMarker('${c.id}', ${c.lat}, ${c.lng})">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl shrink-0 flex items-center justify-center text-white text-sm font-bold" style="background-color: ${c.signal_color};">
                            📶
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-[10px] font-bold text-white px-2 py-0.2 rounded-md bg-slate-900">
                                    ${c.operator}
                                </span>
                                <span class="text-[10px] font-bold text-emerald-700" style="color: ${c.signal_color};">
                                    ${c.signal_label}
                                </span>
                            </div>

                            <h4 class="text-xs font-bold text-gray-900 group-hover:text-orange-700 transition truncate mt-1">
                                ${c.location_name}
                            </h4>

                            <div class="mt-2 flex items-center gap-2">
                                <a href="${c.google_maps_url}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   onclick="event.stopPropagation();"
                                   class="text-[10px] font-semibold text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                    <i class="fab fa-google text-red-500"></i> Google Maps
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        listEl.innerHTML = html;
    }

    function flyToMarker(id, lat, lng) {
        if (!leafletMap) return;

        leafletMap.flyTo([lat, lng], 14, {
            animate: true,
            duration: 1.2
        });

        const marker = placeMarkers[id] || coverageMarkers[id];
        if (marker) {
            setTimeout(() => {
                marker.openPopup();
            }, 1250);
        }

        // Close drawer on small screen
        if (window.innerWidth < 640) {
            const drawer = document.getElementById('placesDrawer');
            if (drawer) drawer.classList.add('hidden');
        }
    }

    function fitMapAllBounds() {
        if (!leafletMap) return;

        const bounds = [];
        const { places, coverage } = getFilteredData();

        places.forEach(p => { if (p.lat && p.lng) bounds.push([p.lat, p.lng]); });
        coverage.forEach(c => { if (c.lat && c.lng) bounds.push([c.lat, c.lng]); });

        if (bounds.length > 0) {
            leafletMap.fitBounds(bounds, { padding: [50, 50] });
        } else {
            leafletMap.setView([7.545, 80.755], 11);
        }
    }

    function locateUserPosition() {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const userLat = pos.coords.latitude;
                const userLng = pos.coords.longitude;

                if (userLocationMarker && leafletMap) leafletMap.removeLayer(userLocationMarker);

                userLocationMarker = L.circleMarker([userLat, userLng], {
                    radius: 9,
                    fillColor: '#3b82f6',
                    color: '#ffffff',
                    weight: 3,
                    opacity: 1,
                    fillOpacity: 0.9
                }).addTo(leafletMap);

                userLocationMarker.bindPopup('<strong class="text-xs">Your Current Location</strong>').openPopup();
                leafletMap.flyTo([userLat, userLng], 14);
            },
            (err) => {
                alert('Could not obtain current location: ' + err.message);
            }
        );
    }

    function togglePlacesDrawer() {
        const drawer = document.getElementById('placesDrawer');
        drawer.classList.toggle('hidden');
    }

    function toggleMapLegend() {
        const box = document.getElementById('mapLegendBox');
        box.classList.toggle('hidden');
    }
</script>

@endsection