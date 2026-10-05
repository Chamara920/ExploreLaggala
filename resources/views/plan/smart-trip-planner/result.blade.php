@extends('layouts.public')

@section('title', __('trip_planner.result_page_title'))

@section('content')

<div class="bg-slate-50 py-10 lg:py-14">

    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- Top Bar & Navigation --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                @if(($plan['trip_days'] ?? 1) > 1)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-0.5 text-xs font-bold text-blue-800 uppercase tracking-wide">
                        <i class="bi bi-compass"></i> {{ __('trip_planner.itinerary_badge_multiday', ['days' => $plan['trip_days']]) }}
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-0.5 text-xs font-bold text-emerald-800 uppercase tracking-wide">
                        <i class="bi bi-compass"></i> {{ __('trip_planner.itinerary_badge_1day') }}
                    </span>
                @endif

                <h1 class="mt-2 text-2xl sm:text-3xl font-extrabold text-slate-900">
                    {{ __('trip_planner.itinerary_title') }}
                </h1>

                <p class="mt-1 text-sm text-slate-600">
                    <i class="bi bi-calendar3 me-1 text-slate-400"></i> {{ $plan['travel_date']->format('l, d M Y') }}
                    ·
                    <i class="bi bi-geo-alt-fill me-1 text-emerald-600"></i> {{ __('trip_planner.start') }}: <strong>{{ $plan['start_location_name'] }}</strong>
                    ·
                    <span class="capitalize">
                        @if($plan['travel_mode'] === 'private_vehicle')
                            🚗 {{ __('trip_planner.mode_private') }} ({{ $plan['speed_kmh'] }} km/h)
                        @elseif($plan['travel_mode'] === 'motorbike')
                            🏍️ {{ __('trip_planner.mode_bike') }} ({{ $plan['speed_kmh'] }} km/h)
                        @elseif($plan['travel_mode'] === 'private_bus')
                            🚍 {{ __('trip_planner.mode_private_bus') }} ({{ $plan['speed_kmh'] }} km/h)
                        @else
                            🚌 {{ __('trip_planner.mode_public_bus') }} ({{ $plan['speed_kmh'] }} km/h)
                        @endif
                    </span>
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a
                    href="{{ $plan['osm_url'] ?? 'https://www.openstreetmap.org' }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-emerald-700 transition"
                >
                    <i class="bi bi-map-fill"></i>
                    <span>{{ __('trip_planner.open_in_osm') }}</span>
                </a>

                <a
                    href="{{ $plan['google_maps_url'] }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition"
                >
                    <i class="bi bi-google"></i>
                    <span>{{ __('trip_planner.open_in_google_maps') }}</span>
                </a>

                <a
                    href="{{ route('plan.smart-trip-planner.index') }}"
                    class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition"
                >
                    <i class="bi bi-arrow-left"></i>
                    <span>{{ __('trip_planner.plan_again') }}</span>
                </a>
            </div>

        </div>

        {{-- Metrics Summary Grid --}}
        <div class="mt-8 grid grid-cols-2 gap-4 lg:grid-cols-4">

            {{-- 1. Destinations --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('trip_planner.metric_destinations') }}</span>
                    <span class="rounded-lg bg-emerald-100 p-1.5 text-emerald-700"><i class="bi bi-geo-alt-fill"></i></span>
                </div>
                <p class="mt-2 text-3xl font-extrabold text-slate-900">
                    {{ $plan['summary']['destination_count'] }}
                </p>
                <p class="mt-0.5 text-xs text-slate-500">{{ __('trip_planner.metric_destinations_desc') }}</p>
            </div>

            {{-- 2. Total Road Distance --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('trip_planner.metric_total_dist') }}</span>
                    <span class="rounded-lg bg-blue-100 p-1.5 text-blue-700"><i class="bi bi-signpost-split-fill"></i></span>
                </div>
                <p class="mt-2 text-3xl font-extrabold text-slate-900">
                    {{ $plan['summary']['total_distance_km'] }} <span class="text-lg font-semibold text-slate-500">km</span>
                </p>
                <p class="mt-0.5 text-xs text-slate-500">{{ __('trip_planner.metric_return_dist', ['km' => $plan['summary']['return_distance_km']]) }}</p>
            </div>

            {{-- 3. Travel Time --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('trip_planner.metric_travel_time') }}</span>
                    <span class="rounded-lg bg-amber-100 p-1.5 text-amber-700"><i class="bi bi-clock-history"></i></span>
                </div>
                <p class="mt-2 text-3xl font-extrabold text-slate-900">
                    {{ $plan['summary']['travel_minutes'] }} <span class="text-lg font-semibold text-slate-500">min</span>
                </p>
                <p class="mt-0.5 text-xs text-slate-500">{{ __('trip_planner.metric_travel_speed', ['speed' => $plan['speed_kmh']]) }}</p>
            </div>

            {{-- 4. Sightseeing / Free Time or Multi-day badge --}}
            @if(($plan['trip_days'] ?? 1) > 1)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('trip_planner.metric_trip_days') }}</span>
                        <span class="rounded-lg bg-purple-100 p-1.5 text-purple-700"><i class="bi bi-calendar2-week-fill"></i></span>
                    </div>
                    <p class="mt-2 text-3xl font-extrabold text-purple-700">
                        {{ $plan['trip_days'] }} <span class="text-lg font-semibold text-slate-500">{{ __('trip_planner.trip_duration_label') }}</span>
                    </p>
                    <p class="mt-0.5 text-xs text-slate-500">{{ __('trip_planner.metric_trip_days_desc') }}</p>
                </div>
            @else
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('trip_planner.metric_sightseeing') }}</span>
                        <span class="rounded-lg bg-purple-100 p-1.5 text-purple-700"><i class="bi bi-camera-fill"></i></span>
                    </div>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900">
                        {{ $plan['summary']['visit_minutes'] }} <span class="text-lg font-semibold text-slate-500">min</span>
                    </p>
                    <p class="mt-0.5 text-xs text-slate-500">{{ __('trip_planner.metric_free_time', ['min' => $plan['summary']['remaining_minutes']]) }}</p>
                </div>
            @endif

        </div>

        {{-- Weather & Safety Notices Bar --}}
        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">

            {{-- Weather Info --}}
            <div class="flex items-start gap-3.5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm hover:border-sky-300 transition">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-600 text-xl">
                    @if(str_contains(strtolower($plan['weather']['condition'] ?? ''), 'rain'))
                        <i class="bi bi-cloud-rain-fill"></i>
                    @elseif(str_contains(strtolower($plan['weather']['condition'] ?? ''), 'cloud'))
                        <i class="bi bi-cloud-sun-fill"></i>
                    @elseif(str_contains(strtolower($plan['weather']['condition'] ?? ''), 'mist') || str_contains(strtolower($plan['weather']['condition'] ?? ''), 'fog'))
                        <i class="bi bi-cloud-fog-fill text-slate-500"></i>
                    @elseif(str_contains(strtolower($plan['weather']['condition'] ?? ''), 'wind'))
                        <i class="bi bi-wind text-teal-600"></i>
                    @else
                        <i class="bi bi-sun-fill text-amber-500"></i>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-1 flex-wrap">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('trip_planner.regional_weather') }}</span>
                        <div class="flex items-center gap-1.5">
                            @if(!empty($plan['weather']['is_fresh_48h']))
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-extrabold text-emerald-800">
                                    <i class="bi bi-clock-history"></i> {{ __('trip_planner.weather_live_observations') }}
                                </span>
                            @endif
                            <span class="text-xs font-bold text-slate-700">{{ $plan['weather']['temperature'] }}°C</span>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-slate-900 mt-0.5">
                        {{ ucfirst(str_replace('_', ' ', $plan['weather']['condition'] ?? 'Pleasant')) }} • {{ $plan['weather']['location_name'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-600">
                        {{ $plan['weather']['advisory'] }}
                    </p>
                    <a href="{{ route('plan.weather.index') }}" class="mt-2 inline-flex items-center gap-1 text-[11px] font-bold text-sky-600 hover:text-sky-700 hover:underline">
                        <i class="bi bi-cloud-sun"></i> {{ __('trip_planner.view_weather_safety_page') }} ➔
                    </a>
                </div>
            </div>

            {{-- Road & Natural Hazard Alerts --}}
            <div class="flex items-start gap-3.5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm hover:border-rose-300 transition">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ ($plan['road_safety']['has_critical_danger'] ?? false) || ($plan['road_safety']['has_closures'] ?? false) ? 'bg-red-100 text-red-600' : (($plan['road_safety']['has_alerts'] ?? false) ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-600') }} text-xl">
                    @if($plan['road_safety']['has_critical_danger'] ?? false)
                        <i class="bi bi-exclamation-triangle-fill text-rose-600 animate-pulse"></i>
                    @elseif($plan['road_safety']['has_closures'] ?? false)
                        <i class="bi bi-cone-striped"></i>
                    @elseif($plan['road_safety']['has_alerts'] ?? false)
                        <i class="bi bi-shield-exclamation"></i>
                    @else
                        <i class="bi bi-shield-check"></i>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-1 flex-wrap">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('trip_planner.road_safety_status') }}</span>
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold {{ ($plan['road_safety']['has_critical_danger'] ?? false) || ($plan['road_safety']['has_closures'] ?? false) ? 'bg-red-100 text-red-700' : (($plan['road_safety']['has_alerts'] ?? false) ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-700') }}">
                            {{ $plan['road_safety']['summary_badge'] }}
                        </span>
                    </div>
                    <p class="text-sm font-bold text-slate-900 mt-0.5">
                        {{ $plan['road_safety']['note'] }}
                    </p>
                    @if($plan['road_safety']['has_alerts'])
                        <div class="mt-2 space-y-1.5">
                            @foreach($plan['road_safety']['alerts']->take(2) as $alert)
                                <div class="rounded-lg bg-slate-50 border border-slate-100 p-2 text-xs">
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="font-bold text-slate-900">{{ $alert['title'] }}</span>
                                        <span class="text-[10px] text-slate-400">({{ $alert['date'] }})</span>
                                    </div>
                                    @if(!empty($alert['safety_instructions']))
                                        <p class="mt-1 text-[11px] text-amber-900 bg-amber-50/80 rounded p-1 border border-amber-200/60">
                                            <strong>{{ __('trip_planner.disaster_warning_instruction') }}:</strong> {{ $alert['safety_instructions'] }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <a href="{{ route('plan.weather.index') }}#safety-section" class="mt-2 inline-flex items-center gap-1 text-[11px] font-bold text-rose-600 hover:text-rose-700 hover:underline">
                        <i class="bi bi-shield-exclamation"></i> {{ __('trip_planner.active_safety_alerts') }} ➔
                    </a>
                </div>
            </div>

        </div>

        {{-- Private Bus / Chartered Coach Advisory Notice --}}
        @if($plan['travel_mode'] === 'private_bus')
            <div class="mt-6 flex items-start gap-3.5 rounded-2xl border-2 border-purple-200 bg-purple-50/70 p-5 shadow-sm">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-purple-600 text-white text-2xl shadow-sm">
                    <i class="bi bi-bus-front-fill"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-purple-700">
                            {{ __('trip_planner.mode_private_bus') }} · {{ __('trip_planner.mode_private_bus_badge') }}
                        </span>
                        <span class="inline-flex rounded-full bg-purple-200 px-2.5 py-0.5 text-xs font-bold text-purple-800">
                            ~{{ $plan['speed_kmh'] }} km/h
                        </span>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900 mt-1">
                        {{ __('trip_planner.coach_access_note') }}
                    </h3>
                    <p class="mt-1 text-xs text-slate-700 leading-relaxed">
                        {{ __('trip_planner.coach_access_desc') }}
                    </p>
                </div>
            </div>
        @endif


        {{-- Dual Map Section: OpenStreetMap Live Route & Google Maps Navigation --}}
        <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                        <i class="bi bi-map-fill text-emerald-600"></i> {{ __('trip_planner.route_map_title') }}
                    </h2>
                    <p class="text-xs text-slate-500">
                        ⚡ {{ __('trip_planner.route_map_subtitle') }}
                    </p>
                </div>

                {{-- Map View Switcher Tabs --}}
                <div class="inline-flex rounded-xl bg-slate-100 p-1">
                    <button
                        type="button"
                        id="tabBtnOsm"
                        onclick="switchMapMode('osm')"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-slate-900 shadow-sm transition"
                    >
                        <i class="bi bi-map-fill text-emerald-600"></i> {{ __('trip_planner.osm_route_tab') }}
                    </button>
                    <button
                        type="button"
                        id="tabBtnGoogle"
                        onclick="switchMapMode('google')"
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 transition"
                    >
                        <i class="bi bi-google text-blue-600"></i> {{ __('trip_planner.google_maps_tab') }}
                    </button>
                </div>
            </div>

            {{-- 1. OpenStreetMap Interactive Tab (Active by default) --}}
            <div id="leafletMapWrap" class="w-full overflow-hidden rounded-2xl border border-slate-200 relative" style="height: 520px;">
                {{-- Quick action links floating on top right of OSM map --}}
                <div class="absolute top-3 right-3 z-[1000] flex flex-wrap gap-2">
                    <a
                        href="{{ $plan['osm_url'] ?? 'https://www.openstreetmap.org' }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-700/90 hover:bg-emerald-800 text-white px-3 py-1.5 text-xs font-bold shadow-md backdrop-blur-sm transition"
                    >
                        <i class="bi bi-map-fill"></i>
                        <span>OpenStreetMap.org</span>
                        <i class="bi bi-arrow-up-right text-[10px]"></i>
                    </a>
                    <a
                        href="{{ $plan['google_maps_url'] }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600/90 hover:bg-blue-700 text-white px-3 py-1.5 text-xs font-bold shadow-md backdrop-blur-sm transition"
                    >
                        <i class="bi bi-google"></i>
                        <span>Google Maps</span>
                        <i class="bi bi-arrow-up-right text-[10px]"></i>
                    </a>
                </div>

                <div id="tripPlanMap" style="height: 100%; width: 100%;"></div>
            </div>

            {{-- 2. Google Maps Navigation Card Tab --}}
            <div id="googleMapWrap" class="hidden w-full overflow-hidden rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50 via-slate-50 to-emerald-50" style="min-height: 360px;">
                <div class="flex flex-col items-center justify-center h-full py-12 px-6 text-center gap-6">

                    {{-- Route summary badges --}}
                    <div class="flex flex-wrap justify-center gap-2.5 text-xs">
                        @php
                            $destItems = $plan['itinerary']->where('type', 'destination');
                        @endphp
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white border border-blue-200 px-3 py-1 font-bold text-blue-800 shadow-sm">
                            <i class="bi bi-geo-alt-fill text-blue-500"></i>
                            {{ __('trip_planner.start') }}: {{ $plan['start_location_name'] }}
                        </span>
                        @foreach($destItems as $item)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white border border-emerald-200 px-3 py-1 font-bold text-emerald-800 shadow-sm">
                                <i class="bi bi-pin-map-fill text-emerald-500"></i>
                                @if(($plan['trip_days'] ?? 1) > 1)
                                    <span class="text-[10px] bg-slate-100 text-slate-600 px-1 rounded font-bold">D{{ $item['day_number'] ?? 1 }}</span>
                                @endif
                                {{ $item['translation']?->name ?? 'Stop #'.$item['order'] }}
                            </span>
                        @endforeach
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white border border-blue-200 px-3 py-1 font-bold text-blue-800 shadow-sm">
                            <i class="bi bi-flag-fill text-blue-500"></i>
                            {{ __('trip_planner.return') }}: {{ $plan['start_location_name'] }}
                        </span>
                    </div>

                    {{-- Map Icon Dual Brand --}}
                    <div class="flex items-center gap-3">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-600 text-white text-2xl shadow-lg shadow-blue-600/30">
                            <i class="bi bi-google"></i>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-200 text-slate-600 text-sm font-bold">
                            &amp;
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-600 text-white text-2xl shadow-lg shadow-emerald-600/30">
                            <i class="bi bi-map-fill"></i>
                        </div>
                    </div>

                    <div>
                        <p class="text-base sm:text-lg font-extrabold text-slate-900">{{ __('trip_planner.open_maps_heading') }}</p>
                        <p class="mt-1 text-sm text-slate-600 max-w-lg mx-auto">{{ __('trip_planner.open_maps_subtext') }}</p>
                    </div>

                    {{-- Both Google Maps & OpenStreetMap Action Buttons in the same place --}}
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <a
                            href="{{ $plan['google_maps_url'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2.5 rounded-2xl bg-blue-600 px-6 py-3 text-sm sm:text-base font-bold text-white shadow-lg shadow-blue-600/30 hover:bg-blue-700 transition hover:scale-105 active:scale-100"
                        >
                            <i class="bi bi-google text-lg"></i>
                            <span>{{ ($plan['trip_days'] ?? 1) > 1 ? __('trip_planner.open_all_google_maps') : __('trip_planner.open_in_google_maps') }}</span>
                            <i class="bi bi-arrow-up-right-square"></i>
                        </a>

                        <a
                            href="{{ $plan['osm_url'] ?? 'https://www.openstreetmap.org' }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2.5 rounded-2xl bg-emerald-600 px-6 py-3 text-sm sm:text-base font-bold text-white shadow-lg shadow-emerald-600/30 hover:bg-emerald-700 transition hover:scale-105 active:scale-100"
                        >
                            <i class="bi bi-map-fill text-lg"></i>
                            <span>{{ ($plan['trip_days'] ?? 1) > 1 ? __('trip_planner.open_all_osm') : __('trip_planner.open_in_osm') }}</span>
                            <i class="bi bi-arrow-up-right-square"></i>
                        </a>

                        <button
                            type="button"
                            onclick="switchMapMode('osm')"
                            class="inline-flex items-center gap-2 rounded-2xl border-2 border-emerald-600/30 bg-emerald-50 px-5 py-3 text-sm font-bold text-emerald-800 hover:bg-emerald-100 transition"
                        >
                            <i class="bi bi-compass text-base text-emerald-600"></i>
                            <span>{{ __('trip_planner.view_osm_map') }}</span>
                        </button>
                    </div>

                    {{-- Per-day route links for multi-day trips --}}
                    @if(($plan['trip_days'] ?? 1) > 1 && !empty($plan['days']))
                        <div class="w-full flex flex-col items-center gap-2 mt-2">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Per-Day Route Links:</span>
                            <div class="flex flex-wrap justify-center gap-2">
                                @foreach($plan['days'] as $dNum => $dData)
                                    <div class="inline-flex rounded-xl border border-slate-200 bg-white p-0.5 shadow-sm text-xs font-bold">
                                        <a
                                            href="{{ $dData['google_maps_url'] }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 text-blue-700 hover:bg-blue-50 rounded-lg transition"
                                            title="Google Maps"
                                        >
                                            <i class="bi bi-google"></i>
                                            <span>Day {{ $dNum }}</span>
                                        </a>
                                        <span class="text-slate-300 self-center">|</span>
                                        <a
                                            href="{{ $dData['osm_url'] ?? '#' }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 text-emerald-700 hover:bg-emerald-50 rounded-lg transition"
                                            title="OpenStreetMap"
                                        >
                                            <i class="bi bi-map-fill"></i>
                                            <span>OSM</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <p class="text-xs text-slate-500">
                        <i class="bi bi-info-circle me-1 text-slate-400"></i>{{ __('trip_planner.maps_tip') }}
                    </p>
                </div>
            </div>

            {{-- Map Footer Bar with Direct Google Maps & OpenStreetMap Links --}}
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-3 border-t border-slate-100 text-xs text-slate-500">
                <span class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1 font-bold text-slate-700">
                        <i class="bi bi-signpost-split text-blue-600"></i> {{ __('trip_planner.total_road_dist') }}: <strong>{{ $plan['summary']['total_distance_km'] }} km</strong>
                    </span>
                    <span>· {{ __('trip_planner.sequenced_note') }}</span>
                    @if(($plan['trip_days'] ?? 1) > 1)
                        <span class="inline-flex items-center gap-2 ms-2">
                            <span class="inline-flex items-center gap-1 font-semibold text-emerald-700"><span class="h-2.5 w-2.5 rounded-full bg-emerald-600"></span> {{ __('trip_planner.day_badge', ['number' => 1]) }}</span>
                            <span class="inline-flex items-center gap-1 font-semibold text-blue-700"><span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span> {{ __('trip_planner.day_badge', ['number' => 2]) }}</span>
                            @if($plan['trip_days'] == 3)
                                <span class="inline-flex items-center gap-1 font-semibold text-purple-700"><span class="h-2.5 w-2.5 rounded-full bg-purple-600"></span> {{ __('trip_planner.day_badge', ['number' => 3]) }}</span>
                            @endif
                        </span>
                    @endif
                </span>

                <div class="flex items-center gap-3">
                    <a
                        href="{{ $plan['osm_url'] ?? 'https://www.openstreetmap.org' }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-emerald-700 font-bold hover:underline inline-flex items-center gap-1.5"
                    >
                        <i class="bi bi-map-fill"></i> OpenStreetMap
                    </a>
                    <span class="text-slate-300">·</span>
                    <a
                        href="{{ $plan['google_maps_url'] }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-blue-600 font-bold hover:underline inline-flex items-center gap-1.5"
                    >
                        <i class="bi bi-arrow-up-right-square"></i> {{ __('trip_planner.open_live_gps') }}
                    </a>
                </div>
            </div>
        </div>



        @if($plan['itinerary']->isEmpty())

            {{-- Empty State --}}
            <div class="mt-8 rounded-3xl border border-amber-200 bg-amber-50 p-8 text-center">
                <div class="text-4xl">🧭</div>
                <h2 class="mt-2 text-lg font-bold text-amber-900">
                    {{ __('trip_planner.no_destinations_found') }}
                </h2>
                <p class="mx-auto mt-2 max-w-md text-sm text-amber-800">
                    {{ __('trip_planner.no_destinations_hint') }}
                </p>
                <a href="{{ route('plan.smart-trip-planner.index') }}" class="mt-4 inline-flex rounded-xl bg-amber-600 px-4 py-2 text-sm font-bold text-white hover:bg-amber-700">
                    {{ __('trip_planner.modify_trip_settings') }}
                </a>
            </div>

        @else

            {{-- Chronological Itinerary Timeline --}}
            <div class="mt-10">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="bi bi-list-ol text-emerald-600"></i> {{ __('trip_planner.schedule_title') }}
                        </h2>
                        <p class="text-xs text-slate-500">{{ __('trip_planner.schedule_subtitle') }}</p>
                    </div>

                    {{-- Multi-day Jump Navigation --}}
                    @if(($plan['trip_days'] ?? 1) > 1 && !empty($plan['days']))
                        <div class="inline-flex rounded-xl bg-slate-200/70 p-1">
                            @foreach($plan['days'] as $dNum => $dData)
                                <a
                                    href="#day-section-{{ $dNum }}"
                                    class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-white hover:text-slate-900 hover:shadow-sm transition"
                                >
                                    <i class="bi bi-calendar-event"></i>
                                    <span>{{ __('trip_planner.day_badge', ['number' => $dNum]) }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Render Multi-Day Sections OR Single Day Schedule --}}
                @if(($plan['trip_days'] ?? 1) > 1 && !empty($plan['days']))

                    {{-- Multi-Day Itinerary (Day 1, Day 2, Day 3) --}}
                    <div class="space-y-12">
                        @foreach($plan['days'] as $dayNumber => $day)
                            <div id="day-section-{{ $dayNumber }}" class="rounded-3xl border border-slate-200 bg-white/70 p-6 sm:p-8 shadow-sm">

                                {{-- Day Section Header --}}
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-200 gap-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            @php
                                                $badgeStyle = match($dayNumber) {
                                                    1 => 'bg-emerald-600 text-white',
                                                    2 => 'bg-blue-600 text-white',
                                                    3 => 'bg-purple-600 text-white',
                                                    default => 'bg-slate-700 text-white',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center rounded-xl {{ $badgeStyle }} px-3 py-1 text-xs font-extrabold uppercase tracking-wider shadow-sm">
                                                {{ __('trip_planner.day_badge', ['number' => $dayNumber]) }}
                                            </span>
                                            <span class="text-xs font-bold text-slate-500">
                                                <i class="bi bi-calendar3"></i> {{ $day['date']->format('l, d M Y') }}
                                            </span>
                                        </div>
                                        <h3 class="text-xl font-extrabold text-slate-900 mt-1">
                                            {{ __('trip_planner.day_title', ['number' => $dayNumber, 'date' => $day['date']->format('d M')]) }}
                                        </h3>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            <i class="bi bi-geo-alt text-emerald-600"></i> {{ __('trip_planner.timeline_start') }}: <strong>{{ $day['start_location'] }}</strong>
                                            ·
                                            <span>{{ __('trip_planner.day_destinations_count', ['count' => $day['summary']['destination_count']]) }}</span>
                                            ·
                                            <span>{{ $day['summary']['total_distance_km'] }} km</span>
                                        </p>
                                    </div>

                                    <a
                                        href="{{ $day['google_maps_url'] }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50 px-3.5 py-2 text-xs font-bold text-blue-700 hover:bg-blue-100 transition shrink-0"
                                    >
                                        <i class="bi bi-google"></i>
                                        <span>{{ __('trip_planner.open_day_google_maps', ['day' => $dayNumber]) }}</span>
                                        <i class="bi bi-arrow-up-right-square"></i>
                                    </a>
                                </div>

                                {{-- Day Steps Timeline --}}
                                <div class="mt-6 space-y-4">

                                    {{-- Day Start Point --}}
                                    <div class="relative flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $badgeStyle }} text-base font-extrabold shadow-sm">
                                            <i class="bi bi-house-door-fill"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">
                                                    {{ __('trip_planner.timeline_start') }} · {{ $day['itinerary']->first()['travel_start']?->format('h:i A') ?? $plan['start_time']->format('h:i A') }}
                                                </span>
                                                <span class="text-xs text-slate-400">{{ __('trip_planner.timeline_start_desc', ['location' => $day['start_location']]) }}</span>
                                            </div>
                                            <h4 class="text-base font-extrabold text-slate-900 mt-0.5">
                                                {{ $day['start_location'] }}
                                            </h4>
                                        </div>
                                    </div>

                                    {{-- Day Itinerary Items --}}
                                    @foreach($day['itinerary'] as $item)

                                        @if(($item['type'] ?? '') === 'meal_stop')
                                            {{-- Meal / Dining Stop --}}
                                            <div class="relative flex items-start gap-4 rounded-2xl border-2 border-amber-200 bg-amber-50/70 p-5 shadow-sm">
                                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-white font-extrabold text-base shadow-sm">
                                                    <i class="bi bi-cup-hot-fill"></i>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                                        <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">
                                                            {{ $item['visit_start']->format('h:i A') }} – {{ $item['visit_end']->format('h:i A') }} ({{ $item['visit_minutes'] }} min)
                                                        </span>
                                                        <span class="inline-flex rounded-full bg-amber-200/80 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                                                            {{ __('trip_planner.dining_break') }}
                                                        </span>
                                                    </div>
                                                    <h4 class="text-base font-extrabold text-slate-900 mt-0.5">
                                                        {{ $item['title'] }}
                                                    </h4>
                                                    <p class="text-xs text-amber-900 mt-1">
                                                        📍 {{ $item['location_name'] }} — {{ $item['note'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        @else
                                            @php
                                                $dest = $item['destination'];
                                                $trans = $item['translation'];
                                            @endphp

                                            {{-- Leg Distance Indicator --}}
                                            <div class="flex items-center gap-3 pl-6 text-xs font-semibold text-slate-500">
                                                <i class="bi bi-arrow-down text-emerald-600 font-bold"></i>
                                                <span>{{ __('trip_planner.timeline_travel_to', ['min' => $item['travel_minutes'], 'km' => $item['leg_distance_km']]) }}</span>
                                            </div>

                                            {{-- Destination Card --}}
                                            <div class="relative flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300 transition">
                                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $badgeStyle }} font-extrabold text-base shadow-sm">
                                                    {{ $item['order'] }}
                                                </div>

                                                <div class="min-w-0 flex-1">
                                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                                        <div>
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">
                                                                    {{ $item['visit_start']->format('h:i A') }} – {{ $item['visit_end']->format('h:i A') }}
                                                                </span>
                                                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600 font-semibold">
                                                                    {{ $item['visit_minutes'] }} min
                                                                </span>
                                                            </div>

                                                            <h4 class="text-lg font-extrabold text-slate-900 mt-1">
                                                                {{ $trans?->name ?? 'Destination' }}
                                                            </h4>

                                                            @if($trans?->location_name)
                                                                <p class="text-xs text-slate-500 mt-0.5">
                                                                    📍 {{ $trans->location_name }}
                                                                </p>
                                                            @endif
                                                        </div>

                                                        {{-- Google Maps direct link --}}
                                                        @if($dest->latitude && $dest->longitude)
                                                            <a
                                                                href="https://www.google.com/maps/search/?api=1&query={{ $dest->latitude }},{{ $dest->longitude }}"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                class="shrink-0 inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition"
                                                            >
                                                                <i class="bi bi-geo-alt"></i> {{ __('trip_planner.spot_on_maps') }}
                                                            </a>
                                                        @endif
                                                    </div>

                                                    @if($trans?->description)
                                                        <p class="mt-2 text-xs text-slate-600 line-clamp-2">
                                                            {{ Str::limit(strip_tags($trans->description), 160) }}
                                                        </p>
                                                    @endif

                                                    {{-- Meta Badges --}}
                                                    <div class="mt-3.5 flex flex-wrap items-center gap-2 text-xs">
                                                        <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-slate-700 font-medium">
                                                            <i class="bi bi-signpost-2 me-1"></i> {{ __('trip_planner.cumulative') }}: {{ $item['cumulative_distance_km'] }} km
                                                        </span>

                                                        @if($item['season'])
                                                            <span class="rounded-lg px-2.5 py-1 font-semibold {{ $item['season']->rating === 'best' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700' }}">
                                                                <i class="bi bi-calendar-check me-1"></i> {{ __('trip_planner.season_badge', ['rating' => ucfirst($item['season']->rating)]) }}
                                                            </span>
                                                        @endif

                                                        @foreach($item['matching_interests'] as $matchedInterest)
                                                            <span class="rounded-lg bg-blue-50 px-2 py-0.5 text-blue-700 font-medium">
                                                                #{{ $matchedInterest->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>

                                                    {{-- Public Transport & Bus Access Highlight --}}
                                                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 pt-3 border-t border-slate-100 text-xs">
                                                        {{-- Bus Accessibility --}}
                                                        <div class="flex items-start gap-2 bg-blue-50/60 rounded-xl p-2.5 border border-blue-100">
                                                            <span class="text-blue-600 text-sm mt-0.5"><i class="bi bi-bus-front-fill"></i></span>
                                                            <div class="min-w-0">
                                                                <span class="font-bold text-blue-900 block text-[11px] uppercase tracking-wide">
                                                                    {{ __('trip_planner.bus_access_title') }}
                                                                </span>
                                                                <p class="text-slate-700 text-[11px] mt-0.5">
                                                                    <strong>{{ __('trip_planner.nearest_bus_stop') }}:</strong> {{ $item['nearest_bus_stop'] }}
                                                                </p>
                                                                <p class="text-slate-600 text-[11px]">
                                                                    <strong>{{ __('trip_planner.connecting_bus_route') }}:</strong> {{ $item['bus_routes'] }}
                                                                </p>
                                                                @if($item['transport_accessibility'])
                                                                    <p class="text-slate-500 text-[10px] mt-0.5">
                                                                        <strong>{{ __('trip_planner.last_mile_access') }}:</strong> {{ ucfirst(str_replace('_', ' ', $item['transport_accessibility'])) }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        {{-- Mobile Coverage --}}
                                                        @if(!empty($dest->plannerDetails))
                                                            @php
                                                                $sigLevel = $dest->plannerDetails->mobile_signal_level;
                                                                $sigBadgeClass = match($sigLevel) {
                                                                    'good' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                                    'partial' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                                    'poor' => 'bg-orange-50 text-orange-700 border-orange-200',
                                                                    'no_signal' => 'bg-red-50 text-red-700 border-red-200',
                                                                    default => 'bg-slate-50 text-slate-700 border-slate-200'
                                                                };
                                                                $sigLabel = match($sigLevel) {
                                                                    'good' => (app()->getLocale() === 'si' ? 'යහපත් සිග්නල් (4G/3G)' : (app()->getLocale() === 'ta' ? 'நல்ல சிக்னல்' : 'Good Signal (4G/3G)')),
                                                                    'partial' => (app()->getLocale() === 'si' ? 'සීමිත සිග්නල්' : (app()->getLocale() === 'ta' ? 'பகுதி சிக்னல்' : 'Partial / Weak Signal')),
                                                                    'poor' => (app()->getLocale() === 'si' ? 'දුර්වල සිග්නල්' : (app()->getLocale() === 'ta' ? 'மோசமான சிக்னல்' : 'Poor Signal')),
                                                                    'no_signal' => (app()->getLocale() === 'si' ? 'සිග්නල් නොමැත' : (app()->getLocale() === 'ta' ? 'சிக்னல் இல்லை' : 'No Signal / Blackout')),
                                                                    default => (app()->getLocale() === 'si' ? 'සිග්නල් වාර්තා වී ඇත' : 'Reported Signal')
                                                                };
                                                            @endphp
                                                            <div class="flex items-start gap-2 bg-slate-50 rounded-xl p-2.5 border border-slate-100">
                                                                <span class="text-emerald-600 text-sm mt-0.5"><i class="bi bi-reception-4"></i></span>
                                                                <div class="min-w-0">
                                                                    <div class="flex items-center gap-1.5 flex-wrap">
                                                                        <span class="font-bold text-slate-800 text-[11px] uppercase tracking-wide">
                                                                            {{ app()->getLocale() === 'si' ? 'දුරකථන සිග්නල්' : (app()->getLocale() === 'ta' ? 'மொபைல் சிக்னல்' : 'Mobile Signal') }}
                                                                        </span>
                                                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold border {{ $sigBadgeClass }}">
                                                                            {{ $sigLabel }}
                                                                        </span>
                                                                    </div>
                                                                    @if($dest->plannerDetails->best_mobile_networks)
                                                                        <p class="text-slate-600 text-[11px] mt-0.5">
                                                                            <strong>{{ app()->getLocale() === 'si' ? 'ක්‍රියාකරුවන්' : (app()->getLocale() === 'ta' ? 'சேவை' : 'Networks') }}:</strong> {{ $dest->plannerDetails->best_mobile_networks }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                        @endif

                                    @endforeach

                                    {{-- End of Intermediate Day: Overnight Lodging Card --}}
                                    @if($dayNumber < $plan['trip_days'] && !empty($day['accommodation']))
                                        <div class="relative flex items-start gap-4 rounded-2xl border-2 border-purple-200 bg-purple-50/70 p-5 shadow-sm mt-6">
                                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-purple-600 text-white font-extrabold text-base shadow-sm">
                                                <i class="bi bi-moon-stars-fill"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                                    <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">
                                                        {{ __('trip_planner.overnight_stay_title') }}
                                                    </span>
                                                    <span class="inline-flex rounded-full bg-purple-200/80 px-2.5 py-0.5 text-xs font-bold text-purple-800">
                                                        {{ __('trip_planner.day_badge', ['number' => $dayNumber]) }} ➔ {{ __('trip_planner.day_badge', ['number' => $dayNumber + 1]) }}
                                                    </span>
                                                </div>
                                                <h4 class="text-base font-extrabold text-slate-900 mt-0.5">
                                                    {{ __('trip_planner.overnight_stay_subtitle', ['day' => $dayNumber, 'next' => $dayNumber + 1]) }}
                                                </h4>
                                                <p class="text-xs text-purple-900 mt-1">
                                                    {{ $day['accommodation']['note'] ?? '' }}
                                                </p>

                                                @if(!empty($day['accommodation']['stays']))
                                                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                                        @foreach($day['accommodation']['stays'] as $stay)
                                                            <div class="rounded-xl border border-purple-100 bg-white p-3 shadow-xs">
                                                                <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600">{{ $stay['type'] ?? 'Eco Stay' }}</span>
                                                                <h5 class="text-xs font-extrabold text-slate-900">{{ $stay['name'] }}</h5>
                                                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $stay['description'] }}</p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    {{-- End of Final Day: Return Journey --}}
                                    @if($dayNumber === $plan['trip_days'])
                                        <div class="flex items-center gap-3 pl-6 text-xs font-semibold text-slate-500">
                                            <i class="bi bi-arrow-down text-emerald-600 font-bold"></i>
                                            <span>{{ __('trip_planner.timeline_return_desc', ['location' => $plan['start_location_name'], 'min' => $day['summary']['return_minutes'], 'km' => $day['summary']['return_distance_km']]) }}</span>
                                        </div>

                                        <div class="relative flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 text-white font-extrabold text-base">
                                                <i class="bi bi-flag-fill"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">
                                                        {{ __('trip_planner.trip_concludes') }}
                                                    </span>
                                                    <span class="text-xs font-bold text-slate-700">~{{ $plan['start_time']->copy()->addMinutes($plan['summary']['used_minutes'])->format('h:i A') }}</span>
                                                </div>
                                                <h4 class="text-base font-extrabold text-slate-900 mt-0.5">
                                                    {{ __('trip_planner.timeline_return') }}: {{ $plan['start_location_name'] }}
                                                </h4>
                                                <p class="text-xs text-slate-500 mt-1">
                                                    {{ __('trip_planner.complete_round_trip') }}: <strong>{{ $plan['summary']['total_distance_km'] }} km</strong>.
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                </div>

                            </div>
                        @endforeach
                    </div>

                @else

                    {{-- Single Day Schedule --}}
                    <div class="space-y-4">

                        {{-- Step 0: Starting Hub --}}
                        <div class="relative flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 font-extrabold text-base">
                                <i class="bi bi-house-door-fill"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">
                                        {{ __('trip_planner.timeline_start') }} · {{ $plan['start_time']->format('h:i A') }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ __('trip_planner.timeline_start_desc', ['location' => $plan['start_location_name']]) }}</span>
                                </div>
                                <h3 class="text-base font-extrabold text-slate-900 mt-0.5">
                                    {{ $plan['start_location_name'] }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ __('trip_planner.departure_coords') }}: {{ number_format($plan['start_latitude'], 4) }}, {{ number_format($plan['start_longitude'], 4) }}
                                </p>
                            </div>
                        </div>

                        {{-- Itinerary Items --}}
                        @foreach($plan['itinerary'] as $item)

                            @if(($item['type'] ?? '') === 'meal_stop')

                                {{-- Meal / Lunch Stop Card --}}
                                <div class="relative flex items-start gap-4 rounded-2xl border-2 border-amber-200 bg-amber-50/60 p-5 shadow-sm">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-white font-extrabold text-base shadow-sm">
                                        <i class="bi bi-cup-hot-fill"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                            <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">
                                                {{ $item['visit_start']->format('h:i A') }} – {{ $item['visit_end']->format('h:i A') }} ({{ $item['visit_minutes'] }} min)
                                            </span>
                                            <span class="inline-flex rounded-full bg-amber-200/80 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                                                {{ __('trip_planner.dining_break') }}
                                            </span>
                                        </div>
                                        <h3 class="text-base font-extrabold text-slate-900 mt-0.5">
                                            {{ $item['title'] }}
                                        </h3>
                                        <p class="text-xs text-amber-900 mt-1">
                                            📍 {{ $item['location_name'] }} — {{ $item['note'] }}
                                        </p>
                                    </div>
                                </div>

                            @else

                                @php
                                    $dest = $item['destination'];
                                    $trans = $item['translation'];
                                @endphp

                                {{-- Travel Leg Distance Indicator --}}
                                <div class="flex items-center gap-3 pl-6 text-xs font-semibold text-slate-500">
                                    <i class="bi bi-arrow-down text-emerald-600 font-bold"></i>
                                    <span>{{ __('trip_planner.timeline_travel_to', ['min' => $item['travel_minutes'], 'km' => $item['leg_distance_km']]) }}</span>
                                </div>

                                {{-- Destination Card --}}
                                <div class="relative flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300 transition">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-blue-700 font-extrabold text-base shadow-sm">
                                        {{ $item['order'] }}
                                    </div>

                                    <div class="min-w-0 flex-1">

                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">
                                                        {{ $item['visit_start']->format('h:i A') }} – {{ $item['visit_end']->format('h:i A') }}
                                                    </span>
                                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600 font-semibold">
                                                        {{ $item['visit_minutes'] }} min
                                                    </span>
                                                </div>

                                                <h3 class="text-lg font-extrabold text-slate-900 mt-1">
                                                    {{ $trans?->name ?? 'Destination' }}
                                                </h3>

                                                @if($trans?->location_name)
                                                    <p class="text-xs text-slate-500 mt-0.5">
                                                        📍 {{ $trans->location_name }}
                                                    </p>
                                                @endif
                                            </div>

                                            {{-- Google Maps direct link --}}
                                            @if($dest->latitude && $dest->longitude)
                                                <a
                                                    href="https://www.google.com/maps/search/?api=1&query={{ $dest->latitude }},{{ $dest->longitude }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="shrink-0 inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition"
                                                >
                                                    <i class="bi bi-geo-alt"></i> {{ __('trip_planner.spot_on_maps') }}
                                                </a>
                                            @endif
                                        </div>

                                        @if($trans?->description)
                                            <p class="mt-2 text-xs text-slate-600 line-clamp-2">
                                                {{ Str::limit(strip_tags($trans->description), 160) }}
                                            </p>
                                        @endif

                                        {{-- Meta badges --}}
                                        <div class="mt-3.5 flex flex-wrap items-center gap-2 text-xs">
                                            <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-slate-700 font-medium">
                                                <i class="bi bi-signpost-2 me-1"></i> {{ __('trip_planner.cumulative') }}: {{ $item['cumulative_distance_km'] }} km
                                            </span>

                                            @if($item['season'])
                                                <span class="rounded-lg px-2.5 py-1 font-semibold {{ $item['season']->rating === 'best' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700' }}">
                                                    <i class="bi bi-calendar-check me-1"></i> {{ __('trip_planner.season_badge', ['rating' => ucfirst($item['season']->rating)]) }}
                                                </span>
                                            @endif

                                            @foreach($item['matching_interests'] as $matchedInterest)
                                                <span class="rounded-lg bg-blue-50 px-2 py-0.5 text-blue-700 font-medium">
                                                    #{{ $matchedInterest->name }}
                                                </span>
                                            @endforeach
                                        </div>

                                        {{-- Logistics & Accessibility Highlights (Public Bus & Mobile Signal) --}}
                                        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 pt-3 border-t border-slate-100 text-xs">
                                            {{-- Public Bus & Access Box --}}
                                            <div class="flex items-start gap-2 bg-blue-50/60 rounded-xl p-2.5 border border-blue-100">
                                                <span class="text-blue-600 text-sm mt-0.5"><i class="bi bi-bus-front-fill"></i></span>
                                                <div class="min-w-0">
                                                    <span class="font-bold text-blue-900 block text-[11px] uppercase tracking-wide">
                                                        {{ __('trip_planner.bus_access_title') }}
                                                    </span>
                                                    <p class="text-slate-700 text-[11px] mt-0.5">
                                                        <strong>{{ __('trip_planner.nearest_bus_stop') }}:</strong> {{ $item['nearest_bus_stop'] }}
                                                    </p>
                                                    <p class="text-slate-600 text-[11px]">
                                                        <strong>{{ __('trip_planner.connecting_bus_route') }}:</strong> {{ $item['bus_routes'] }}
                                                    </p>
                                                    @if($item['transport_accessibility'])
                                                        <p class="text-slate-500 text-[10px] mt-0.5">
                                                            <strong>{{ __('trip_planner.last_mile_access') }}:</strong> {{ ucfirst(str_replace('_', ' ', $item['transport_accessibility'])) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Mobile Coverage & Connectivity --}}
                                            @if(!empty($dest->plannerDetails))
                                                @php
                                                    $sigLevel = $dest->plannerDetails->mobile_signal_level;
                                                    $sigBadgeClass = match($sigLevel) {
                                                        'good' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                        'partial' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                        'poor' => 'bg-orange-50 text-orange-700 border-orange-200',
                                                        'no_signal' => 'bg-red-50 text-red-700 border-red-200',
                                                        default => 'bg-slate-50 text-slate-700 border-slate-200'
                                                    };
                                                    $sigLabel = match($sigLevel) {
                                                        'good' => (app()->getLocale() === 'si' ? 'යහපත් සිග්නල් (4G/3G)' : (app()->getLocale() === 'ta' ? 'நல்ல சிக்னல்' : 'Good Signal (4G/3G)')),
                                                        'partial' => (app()->getLocale() === 'si' ? 'සීමිත සිග්නල්' : (app()->getLocale() === 'ta' ? 'பகுதி சிக்னல்' : 'Partial / Weak Signal')),
                                                        'poor' => (app()->getLocale() === 'si' ? 'දුර්වල සිග්නල්' : (app()->getLocale() === 'ta' ? 'மோசமான சிக்னல்' : 'Poor Signal')),
                                                        'no_signal' => (app()->getLocale() === 'si' ? 'සිග්නල් නොමැත' : (app()->getLocale() === 'ta' ? 'சிக்னல் இல்லை' : 'No Signal / Blackout')),
                                                        default => (app()->getLocale() === 'si' ? 'සිග්නල් වාර්තා වී ඇත' : 'Reported Signal')
                                                    };
                                                @endphp
                                                <div class="flex items-start gap-2 bg-slate-50 rounded-xl p-2.5 border border-slate-100">
                                                    <span class="text-emerald-600 text-sm mt-0.5"><i class="bi bi-reception-4"></i></span>
                                                    <div class="min-w-0">
                                                        <div class="flex items-center gap-1.5 flex-wrap">
                                                            <span class="font-bold text-slate-800 text-[11px] uppercase tracking-wide">
                                                                {{ app()->getLocale() === 'si' ? 'දුරකථන සිග්නල්' : (app()->getLocale() === 'ta' ? 'மொபைல் சிக்னல்' : 'Mobile Signal') }}
                                                            </span>
                                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold border {{ $sigBadgeClass }}">
                                                                {{ $sigLabel }}
                                                            </span>
                                                        </div>
                                                        @if($dest->plannerDetails->best_mobile_networks)
                                                            <p class="text-slate-600 text-[11px] mt-0.5">
                                                                <strong>{{ app()->getLocale() === 'si' ? 'ක්‍රියාකරුවන්' : (app()->getLocale() === 'ta' ? 'சேவை' : 'Networks') }}:</strong> {{ $dest->plannerDetails->best_mobile_networks }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                            @endif

                        @endforeach

                        {{-- Return Journey Leg --}}
                        <div class="flex items-center gap-3 pl-6 text-xs font-semibold text-slate-500">
                            <i class="bi bi-arrow-down text-emerald-600 font-bold"></i>
                            <span>{{ __('trip_planner.timeline_return_desc', ['location' => $plan['start_location_name'], 'min' => $plan['summary']['return_minutes'], 'km' => $plan['summary']['return_distance_km']]) }}</span>
                        </div>

                        {{-- Step End: Return --}}
                        <div class="relative flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 text-white font-extrabold text-base">
                                <i class="bi bi-flag-fill"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">
                                        {{ __('trip_planner.trip_concludes') }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-700">Estimated ~{{ $plan['start_time']->copy()->addMinutes($plan['summary']['used_minutes'])->format('h:i A') }}</span>
                                </div>
                                <h3 class="text-base font-extrabold text-slate-900 mt-0.5">
                                    {{ __('trip_planner.timeline_return') }}: {{ $plan['start_location_name'] }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ __('trip_planner.complete_round_trip') }}: <strong>{{ $plan['summary']['total_distance_km'] }} km</strong>.
                                </p>
                            </div>
                        </div>

                    </div>

                @endif

            </div>

        @endif

        {{-- Dedicated Public Bus Travel Guide & Schedules Section --}}
        @if($plan['travel_mode'] === 'public_transport' || !empty($plan['public_transport']))
            <div class="mt-10 rounded-3xl border border-blue-200 bg-white p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-3">
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-white text-2xl shadow-md shadow-blue-500/20">
                            <i class="bi bi-bus-front"></i>
                        </span>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900">{{ __('trip_planner.bus_guide_heading') }}</h3>
                            <p class="text-xs text-slate-500">{{ __('trip_planner.bus_guide_subtext') }}</p>
                        </div>
                    </div>

                    <a
                        href="{{ route('plan.transport.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-blue-700 transition shrink-0"
                    >
                        <i class="bi bi-clock-history"></i>
                        <span>{{ __('trip_planner.view_bus_schedules') }}</span>
                    </a>
                </div>

                {{-- Bus Routes Grid --}}
                <div class="mt-5 grid grid-cols-1 gap-3.5 sm:grid-cols-2">
                    @forelse($plan['public_transport']['available_routes'] ?? [] as $route)
                        <div class="rounded-2xl border {{ !empty($route['is_suspended']) ? 'border-rose-200 bg-rose-50/30' : 'border-blue-100 bg-blue-50/50' }} p-4 transition hover:shadow-xs">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="inline-flex items-center gap-1 rounded-md bg-blue-600 px-2 py-0.5 text-[11px] font-bold text-white uppercase tracking-wider">
                                        {{ $route['route_number'] ?? 'Bus' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 rounded-md {{ ($route['bus_category'] ?? '') === 'private' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }} px-2 py-0.5 text-[10px] font-bold">
                                        <i class="bi {{ ($route['bus_category'] ?? '') === 'private' ? 'bi-bus-front' : 'bi-bank' }}"></i>
                                        {{ $route['bus_category_label'] }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-emerald-700">{{ $route['fare'] }}</span>
                                    @if(!empty($route['fare_note']))
                                        <span class="block text-[10px] text-slate-400">{{ $route['fare_note'] }}</span>
                                    @endif
                                </div>
                            </div>

                            @if(!empty($route['is_suspended']))
                                <div class="mt-2.5 rounded-xl border border-rose-300 bg-rose-50/90 p-2 text-xs text-rose-800">
                                    <span class="font-bold flex items-center gap-1 text-rose-900">
                                        <i class="bi bi-exclamation-triangle-fill text-rose-600"></i> {{ __('trip_planner.bus_suspended_badge') }}
                                    </span>
                                    @if(!empty($route['suspension_reason']))
                                        <p class="text-[11px] mt-0.5 text-rose-700">{{ $route['suspension_reason'] }}</p>
                                    @endif
                                    @if(!empty($route['suspended_until']))
                                        <p class="text-[10px] text-rose-500 mt-0.5">Until: {{ $route['suspended_until'] }}</p>
                                    @endif
                                </div>
                            @endif

                            <h4 class="text-sm font-extrabold text-slate-900 mt-2.5">
                                {{ $route['route_name'] }}
                            </h4>
                            <p class="text-xs font-semibold text-slate-700 mt-0.5">
                                {{ $route['from_location'] }} ➔ {{ $route['to_location'] }}
                            </p>

                            @if(!empty($route['key_stops']))
                                <p class="text-[11px] text-slate-500 mt-1 line-clamp-1">
                                    <strong>{{ __('trip_planner.bus_stops') }}:</strong> {{ $route['key_stops'] }}
                                </p>
                            @endif

                            <div class="mt-2.5 flex flex-wrap items-center gap-3 text-xs text-slate-600 pt-2 border-t border-slate-200/60">
                                @if(!empty($route['departure_time']))
                                    <span class="flex items-center gap-1">
                                        <i class="bi bi-clock text-blue-600"></i>
                                        <span>{{ $route['departure_time'] }} @if(!empty($route['arrival_time'])) ➔ {{ $route['arrival_time'] }} @endif</span>
                                    </span>
                                @endif
                                @if(!empty($route['frequency']))
                                    <span class="flex items-center gap-1 text-[11px] text-slate-500">
                                        <i class="bi bi-arrow-repeat text-slate-400"></i>
                                        <span>{{ $route['frequency'] }}</span>
                                    </span>
                                @endif
                                @if(!empty($route['contact_number']))
                                    <span class="flex items-center gap-1 text-[11px] text-slate-500">
                                        <i class="bi bi-telephone text-slate-400"></i>
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $route['contact_number']) }}" class="text-blue-600 hover:underline">{{ $route['contact_number'] }}</a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 col-span-2">{{ __('trip_planner.bus_route_empty_note') }}</p>
                    @endforelse
                </div>

                {{-- Bus Traveler Tips --}}
                <div class="mt-5 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                    <h5 class="text-xs font-extrabold uppercase tracking-wider text-slate-600 flex items-center gap-1.5">
                        <i class="bi bi-lightbulb-fill text-amber-500"></i> {{ __('trip_planner.bus_travel_tips') }}
                    </h5>
                    <ul class="mt-2 space-y-1.5 text-xs text-slate-600">
                        <li class="flex items-start gap-2">
                            <span class="text-blue-500 font-bold">•</span>
                            <span>{{ __('trip_planner.bus_tip_1') }}</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-500 font-bold">•</span>
                            <span>{{ __('trip_planner.bus_tip_2') }}</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-500 font-bold">•</span>
                            <span>{{ __('trip_planner.bus_tip_3') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        @endif

        {{-- Single-Day Accommodation Recommendation (if requested) --}}
        @if(($plan['trip_days'] ?? 1) == 1 && !empty($plan['accommodation']))
            <div class="mt-10 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-3">
                    <span class="rounded-xl bg-purple-100 p-2 text-purple-700 text-lg"><i class="bi bi-building"></i></span>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">{{ __('trip_planner.recommended_stays_title') }}</h3>
                        <p class="text-xs text-slate-500">{{ $plan['accommodation']['note'] }}</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    @foreach($plan['accommodation']['stays'] as $stay)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <span class="inline-block text-xs font-bold text-purple-700 uppercase">{{ $stay['type'] }}</span>
                            <h4 class="text-sm font-extrabold text-slate-900 mt-1">{{ $stay['name'] }}</h4>
                            <p class="text-xs text-slate-500 mt-1">{{ $stay['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

</div>

{{-- Leaflet Interactive Map Assets & Script --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<script>
let leafletMap = null;

function switchMapMode(mode) {
    const googleWrap = document.getElementById('googleMapWrap');
    const leafletWrap = document.getElementById('leafletMapWrap');
    const btnGoogle = document.getElementById('tabBtnGoogle');
    const btnOsm = document.getElementById('tabBtnOsm');

    if (mode === 'google') {
        googleWrap.classList.remove('hidden');
        leafletWrap.classList.add('hidden');
        btnGoogle.className = 'inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-slate-900 shadow-sm transition';
        if (btnOsm) {
            btnOsm.className = 'inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 transition';
        }
    } else {
        leafletWrap.classList.remove('hidden');
        googleWrap.classList.add('hidden');
        if (btnOsm) {
            btnOsm.className = 'inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-slate-900 shadow-sm transition';
        }
        btnGoogle.className = 'inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 transition';
        if (leafletMap) {
            setTimeout(() => leafletMap.invalidateSize(), 120);
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const mapPoints = @json($plan['map_points']);

    if (!mapPoints || mapPoints.length === 0) return;

    if (typeof L === 'undefined') {
        console.error('Leaflet is not loaded.');
        return;
    }

    // Init map
    const startPoint = mapPoints[0];
    leafletMap = L.map('tripPlanMap', { zoomControl: true, scrollWheelZoom: true })
        .setView([startPoint.latitude, startPoint.longitude], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(leafletMap);

    const latLngs = [];

    // Marker icon factory
    function createPin(point) {
        const bg = point.color || '#2563eb';
        return L.divIcon({
            className: 'custom-trip-marker',
            html: `<div style="background:${bg};color:#fff;width:34px;height:34px;border-radius:50%;border:3px solid #fff;box-shadow:0 4px 12px rgba(0,0,0,.30);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;font-family:inherit;">${point.order}</div>`,
            iconSize: [34, 34], iconAnchor: [17, 17], popupAnchor: [0, -20]
        });
    }

    // Add markers
    mapPoints.forEach(point => {
        const marker = L.marker([point.latitude, point.longitude], { icon: createPin(point) }).addTo(leafletMap);
        const distHtml = point.leg_distance
            ? `<div style="margin-top:6px;padding:3px 7px;background:#eff6ff;border-radius:6px;font-weight:700;color:#1d4ed8;font-size:11px;display:inline-block;">🛣️ Road leg: ${point.leg_distance} km</div>`
            : '';
        const busHtml = point.nearest_bus_stop
            ? `<div style="margin-top:4px;padding:3px 7px;background:#f0fdf4;border-radius:6px;font-weight:600;color:#166534;font-size:10px;display:block;">🚌 ${point.nearest_bus_stop}</div>`
            : '';
        marker.bindPopup(`<div style="font-family:inherit;font-size:13px;line-height:1.5;padding:4px;min-width:170px;"><div style="font-weight:800;color:#0f172a;font-size:14px;">${point.title}</div><div style="color:#64748b;font-size:11px;margin-top:2px;">${point.subtitle}</div>${distHtml}${busHtml}</div>`);
        latLngs.push([point.latitude, point.longitude]);
    });

    // Spin animation
    if (!document.getElementById('leafletSpinStyle')) {
        const s = document.createElement('style');
        s.id = 'leafletSpinStyle';
        s.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
        document.head.appendChild(s);
    }

    // Loading badge
    const loadingCtrl = L.control({ position: 'bottomleft' });
    loadingCtrl.onAdd = function () {
        const div = L.DomUtil.create('div', '');
        div.id = 'osrmLoadingBadge';
        div.innerHTML = `<div style="background:rgba(30,41,59,.88);color:#fff;padding:6px 12px;border-radius:10px;font-size:11px;font-weight:700;display:flex;align-items:center;gap:6px;backdrop-filter:blur(8px);"><span style="width:10px;height:10px;border:2px solid #94a3b8;border-top-color:#38bdf8;border-radius:50%;display:inline-block;animation:spin .8s linear infinite;"></span>Loading road routes…</div>`;
        return div;
    };
    loadingCtrl.addTo(leafletMap);

    // Per-segment colors
    const legColors = ['#059669','#2563eb','#7c3aed','#d97706','#dc2626','#0891b2','#be185d'];

    // Fetch one OSRM segment
    async function fetchSeg(from, to) {
        const url = `https://router.project-osrm.org/route/v1/driving/${from.longitude},${from.latitude};${to.longitude},${to.latitude}?overview=full&geometries=geojson`;
        const res = await fetch(url, { signal: AbortSignal.timeout(6000) });
        const data = await res.json();
        if (data.routes && data.routes[0]) {
            return {
                geometry: data.routes[0].geometry,
                distance_km: Math.round(data.routes[0].distance / 100) / 10,
            };
        }
        return null;
    }

    // Distance label marker
    function distLabel(latlng, text, color) {
        return L.marker(latlng, {
            icon: L.divIcon({
                className: '',
                html: `<div style="background:${color};color:#fff;padding:2px 7px;border-radius:999px;font-size:10px;font-weight:800;white-space:nowrap;box-shadow:0 2px 6px rgba(0,0,0,.22);border:1.5px solid rgba(255,255,255,.5);pointer-events:none;">${text}</div>`,
                iconAnchor: [0, 0]
            }),
            interactive: false, zIndexOffset: -100
        }).addTo(leafletMap);
    }

    async function buildRoute() {
        let osrmOk = true;

        for (let i = 0; i < mapPoints.length - 1; i++) {
            const from = mapPoints[i];
            const to   = mapPoints[i + 1];
            const color = legColors[i % legColors.length];

            try {
                const seg = await fetchSeg(from, to);
                if (seg && seg.geometry) {
                    const coords = seg.geometry.coordinates.map(c => [c[1], c[0]]);
                    L.polyline(coords, { color, weight: 5, opacity: 0.85, lineJoin: 'round', lineCap: 'round' }).addTo(leafletMap);
                    distLabel(coords[Math.floor(coords.length / 2)], `${seg.distance_km} km`, color);
                } else {
                    throw new Error('no route');
                }
            } catch {
                osrmOk = false;
                L.polyline([[from.latitude, from.longitude],[to.latitude, to.longitude]], {
                    color, weight: 4, opacity: 0.72, dashArray: '7 9'
                }).addTo(leafletMap);
                if (from.leg_distance) {
                    const mid = [(from.latitude+to.latitude)/2, (from.longitude+to.longitude)/2];
                    distLabel(mid, `~${from.leg_distance} km`, color);
                }
            }
        }

        // Remove loading badge
        document.getElementById('osrmLoadingBadge')?.remove();

        // Source badge
        const badge = L.control({ position: 'bottomleft' });
        badge.onAdd = function () {
            const div = L.DomUtil.create('div', '');
            div.innerHTML = osrmOk
                ? `<div style="background:rgba(5,150,105,.9);color:#fff;padding:5px 11px;border-radius:10px;font-size:10px;font-weight:700;backdrop-filter:blur(6px);">✅ Real road distances · OSRM routing</div>`
                : `<div style="background:rgba(245,158,11,.9);color:#fff;padding:5px 11px;border-radius:10px;font-size:10px;font-weight:700;backdrop-filter:blur(6px);">⚠️ Estimated distances · mountain road factor (×1.55)</div>`;
            return div;
        };
        badge.addTo(leafletMap);

        // Fit bounds
        if (latLngs.length > 1) {
            leafletMap.fitBounds(L.latLngBounds(latLngs), { padding: [50, 50] });
        }
    }

    buildRoute();
});
</script>

@endsection
