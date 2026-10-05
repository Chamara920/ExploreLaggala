@extends('layouts.public')

@section('title', __('weather_safety.page_title'))

@section('content')

{{-- ========= HERO ========= --}}
<section class="relative overflow-hidden bg-slate-900 py-12 lg:py-16 text-white">
    <div class="absolute inset-0 bg-cover bg-center opacity-25"
         style="background-image: url('https://images.unsplash.com/photo-1501594907352-04cda38ebc29?auto=format&fit=crop&w=1800&q=70');"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-sky-950/85 to-teal-950/70"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-sky-400/30 bg-sky-500/10 px-3.5 py-1 text-xs font-semibold text-sky-200 backdrop-blur-sm">
                <i class="bi bi-cloud-sun text-sky-300"></i> {{ __('weather_safety.badge_plan') }}
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                {{ __('weather_safety.hero_title') }}
            </h1>
            <p class="mt-4 text-base sm:text-lg text-sky-100/90 leading-relaxed">
                {{ __('weather_safety.hero_subtitle') }}
            </p>

            {{-- Quick section navigation jump links --}}
            <div class="mt-6 flex flex-wrap items-center gap-3">
                <a href="#weather-section" class="inline-flex items-center gap-2 rounded-xl bg-sky-600/90 hover:bg-sky-500 px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm transition">
                    <i class="bi bi-cloud-sun"></i> {{ __('weather_safety.tab_weather') }}
                </a>
                <a href="#safety-section" class="inline-flex items-center gap-2 rounded-xl bg-rose-600/90 hover:bg-rose-500 px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm transition">
                    <i class="bi bi-shield-exclamation"></i> {{ __('weather_safety.tab_safety') }}
                </a>
                <span class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-3.5 py-2 text-xs font-medium text-sky-200">
                    <i class="bi bi-telephone-fill text-amber-300"></i> DMC: <strong>117</strong> | 119
                </span>
            </div>
        </div>
    </div>
</section>

{{-- ========= FLASH ALERTS ========= --}}
<div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
    @if(session('success'))
    <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill text-xl text-emerald-600 flex-shrink-0"></i>
        <span class="text-sm font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 shadow-sm mb-4">
        <p class="mb-2 text-sm font-bold text-rose-800 flex items-center gap-1.5">
            <i class="bi bi-exclamation-triangle-fill text-rose-600"></i> Please check form inputs:
        </p>
        <ul class="list-disc list-inside space-y-1 text-xs sm:text-sm text-rose-700">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>

{{-- ========= MAIN CONTAINER ========= --}}
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- ========================================================================= --}}
        {{-- MAIN CONTENT: SECTION 1 (WEATHER) + SECTION 2 (SAFETY) --}}
        {{-- ========================================================================= --}}
        <div class="lg:col-span-8 space-y-10">

            {{-- ===================================================================== --}}
            {{-- 1. REGIONAL WEATHER DATA SECTION --}}
            {{-- ===================================================================== --}}
            <section id="weather-section" class="scroll-mt-24 space-y-6">
                {{-- Section Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-200 pb-4">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-lg bg-sky-100 px-2.5 py-1 text-xs font-bold text-sky-800 mb-1">
                            <i class="bi bi-cloud-rain"></i> {{ __('weather_safety.tab_weather') }}
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                            {{ __('weather_safety.sec_weather_title') }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                            {{ __('weather_safety.sec_weather_subtitle') }}
                        </p>
                    </div>

                    @auth
                        @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                        <button type="button" onclick="openModal('modal-add-location')"
                                class="inline-flex items-center gap-1.5 rounded-xl border border-sky-300 bg-sky-50 px-3 py-1.5 text-xs font-bold text-sky-700 hover:bg-sky-100 transition shadow-sm">
                            <i class="bi bi-plus-circle"></i> Add Location (Admin)
                        </button>
                        @endif
                    @endauth
                </div>

                {{-- Part 1A: Open-Meteo Multi-Location Live Forecast Widget --}}
                <div class="overflow-hidden rounded-2xl border border-sky-200/80 bg-gradient-to-br from-sky-50/70 via-white to-sky-50/40 shadow-sm">
                    {{-- Station Selector Strip --}}
                    <div class="border-b border-sky-100 bg-sky-100/50 px-5 py-3">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-sky-900 flex items-center gap-1.5">
                                <i class="bi bi-geo-alt-fill text-sky-600"></i> {{ __('weather_safety.select_station') }}
                            </span>
                            <span class="text-[11px] text-sky-700 italic">
                                {{ __('weather_safety.station_coverage_note') }}
                            </span>
                        </div>

                        {{-- Station Buttons / Pills --}}
                        <div class="mt-2.5 flex flex-wrap items-center gap-2" id="station-pills-container">
                            @foreach($locations as $index => $loc)
                            <button type="button"
                                    data-station-index="{{ $index }}"
                                    data-lat="{{ $loc->latitude }}"
                                    data-lon="{{ $loc->longitude }}"
                                    data-name="{{ $loc->translated_name }}"
                                    data-elevation="{{ $loc->elevation_m ?? 350 }}"
                                    class="station-pill inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition cursor-pointer {{ $index === 0 ? 'bg-sky-700 text-white shadow-sm ring-2 ring-sky-600' : 'bg-white text-slate-700 border border-slate-200 hover:bg-sky-50' }}">
                                <i class="bi bi-pin-map text-[11px]"></i>
                                <span>{{ $loc->translated_name }}</span>
                                @if($loc->elevation_m)
                                <span class="text-[10px] opacity-75">({{ $loc->elevation_m }}m)</span>
                                @endif
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Live Weather Content Container --}}
                    <div class="p-6">
                        {{-- Loading Skeleton --}}
                        <div id="weather-loading" class="animate-pulse space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-16 rounded-2xl bg-sky-200/60"></div>
                                <div class="space-y-2 flex-1">
                                    <div class="h-4 w-40 rounded bg-sky-200/60"></div>
                                    <div class="h-8 w-28 rounded bg-sky-200/60"></div>
                                    <div class="h-3 w-56 rounded bg-sky-200/60"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-5 gap-2 pt-4">
                                @for($i=0; $i<5; $i++)
                                <div class="h-20 rounded-xl bg-sky-200/40"></div>
                                @endfor
                            </div>
                        </div>

                        {{-- Weather Data View --}}
                        <div id="weather-data" class="hidden">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-sky-100 pb-6">
                                <div class="flex items-center gap-5">
                                    <div id="wx-icon" class="flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-2xl bg-sky-600 text-5xl shadow-md text-white">
                                        ☀️
                                    </div>
                                    <div>
                                        <div class="flex items-baseline gap-2">
                                            <span id="wx-temp" class="text-5xl font-black text-slate-900 tracking-tight">26</span>
                                            <span class="text-2xl font-bold text-slate-500">°C</span>
                                        </div>
                                        <p id="wx-desc" class="text-base font-bold text-slate-700 capitalize">Partly cloudy</p>
                                        <div class="mt-1 flex items-center gap-2 text-xs text-slate-500">
                                            <i class="bi bi-geo-alt text-sky-600"></i>
                                            <strong id="wx-location-name" class="text-slate-800">{{ $locations->first()?->translated_name ?? 'Pallegama (Laggala)' }}</strong>
                                            <span>·</span>
                                            <span>Elev: <strong id="wx-elevation">{{ $locations->first()?->elevation_m ?? 350 }}m</strong></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Metrics grid --}}
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="rounded-xl border border-sky-100 bg-white/80 p-3 text-center shadow-xs">
                                        <span class="block text-xs font-semibold text-slate-500 mb-1">
                                            <i class="bi bi-droplet-fill text-sky-500"></i> {{ __('weather_safety.rain_prob') }}
                                        </span>
                                        <span id="wx-rain" class="text-lg font-extrabold text-slate-800">20%</span>
                                    </div>
                                    <div class="rounded-xl border border-sky-100 bg-white/80 p-3 text-center shadow-xs">
                                        <span class="block text-xs font-semibold text-slate-500 mb-1">
                                            <i class="bi bi-wind text-teal-600"></i> {{ __('weather_safety.wind_speed') }}
                                        </span>
                                        <span id="wx-wind" class="text-lg font-extrabold text-slate-800">14 km/h</span>
                                    </div>
                                    <div class="rounded-xl border border-sky-100 bg-white/80 p-3 text-center shadow-xs">
                                        <span class="block text-xs font-semibold text-slate-500 mb-1">
                                            <i class="bi bi-moisture text-blue-500"></i> {{ __('weather_safety.humidity') }}
                                        </span>
                                        <span id="wx-humidity" class="text-lg font-extrabold text-slate-800">76%</span>
                                    </div>
                                </div>
                            </div>

                            {{-- 5-Day Forecast --}}
                            <div class="mt-6">
                                <div class="mb-3 flex items-center justify-between">
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center gap-1.5">
                                        <i class="bi bi-calendar3 text-sky-600"></i> {{ __('weather_safety.five_day_forecast') }}
                                    </h4>
                                    <span id="weather-updated" class="text-[11px] text-slate-400">Live Satellite Data (Open-Meteo)</span>
                                </div>
                                <div id="wx-forecast" class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                                    {{-- Dynamically populated via JS --}}
                                </div>
                            </div>
                        </div>

                        {{-- Error / Offline Fallback --}}
                        <div id="weather-error" class="hidden rounded-xl bg-amber-50 border border-amber-200 p-4 text-xs text-amber-800">
                            <p class="font-semibold flex items-center gap-1.5">
                                <i class="bi bi-info-circle-fill text-amber-600"></i> Live forecast feed is currently using regional climate average.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Part 1B: Real Ground Observations (Past 48 Hours) --}}
                <div class="space-y-4 pt-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-bold text-slate-900">
                                    <i class="bi bi-binoculars-fill text-emerald-600 me-1"></i>
                                    {{ __('weather_safety.ground_obs_title') }}
                                </h3>
                                <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-extrabold text-emerald-800">
                                    {{ $observations->count() }} active
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ __('weather_safety.ground_obs_subtitle') }}
                            </p>
                        </div>

                        @auth
                        <button type="button" onclick="openModal('modal-add-obs')"
                                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 px-4 py-2 text-xs sm:text-sm font-bold text-white shadow-sm transition">
                            <i class="bi bi-plus-lg"></i> {{ __('weather_safety.btn_report_weather') }}
                        </button>
                        @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-slate-800 hover:bg-slate-700 px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm transition">
                            <i class="bi bi-box-arrow-in-right"></i> {{ __('weather_safety.btn_report_weather') }}
                        </a>
                        @endauth
                    </div>

                    {{-- 48h filter notice badge --}}
                    <div class="flex items-center gap-2 rounded-xl bg-slate-100 border border-slate-200/80 px-4 py-2.5 text-xs text-slate-600">
                        <i class="bi bi-clock-history text-slate-500 text-sm"></i>
                        <span>{{ __('weather_safety.obs_notice_48h') }}</span>
                    </div>

                    {{-- Observations List --}}
                    @if($observations->isEmpty())
                    <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 py-12 px-4 text-center bg-slate-50/50">
                        <i class="bi bi-cloud-slash text-4xl text-slate-300 mb-2"></i>
                        <h4 class="text-sm font-bold text-slate-700">{{ __('weather_safety.no_obs_title') }}</h4>
                        <p class="text-xs text-slate-500 mt-1 max-w-sm">{{ __('weather_safety.no_obs_subtitle') }}</p>
                        @auth
                        <button type="button" onclick="openModal('modal-add-obs')" class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3.5 py-1.5 text-xs font-bold text-white shadow-xs">
                            <i class="bi bi-plus-circle"></i> {{ __('weather_safety.btn_report_weather') }}
                        </button>
                        @endauth
                    </div>
                    @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($observations as $obs)
                        @php
                            $condIcon = match($obs->condition) {
                                'sunny'        => '☀️',
                                'partly_cloudy'=> '⛅',
                                'cloudy'       => '☁️',
                                'rain'         => '🌧️',
                                'heavy_rain'   => '⛈️',
                                'mist'         => '🌫️',
                                'fog'          => '🌁',
                                'windy'        => '💨',
                                default        => '🌡️',
                            };
                            $condLabel = match($obs->condition) {
                                'sunny'        => __('weather_safety.cond_sunny'),
                                'partly_cloudy'=> __('weather_safety.cond_partly_cloudy'),
                                'cloudy'       => __('weather_safety.cond_cloudy'),
                                'rain'         => __('weather_safety.cond_rain'),
                                'heavy_rain'   => __('weather_safety.cond_heavy_rain'),
                                'mist'         => __('weather_safety.cond_mist'),
                                'fog'          => __('weather_safety.cond_fog'),
                                'windy'        => __('weather_safety.cond_windy'),
                                default        => $obs->condition,
                            };
                        @endphp
                        <div class="relative rounded-2xl border border-slate-200/90 bg-white p-4 shadow-xs hover:shadow-md transition">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-sky-50 text-2xl border border-sky-100">
                                        {{ $condIcon }}
                                    </span>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 leading-snug">
                                            {{ $obs->translated_location_name }}
                                        </h4>
                                        <p class="text-xs font-semibold text-sky-700">
                                            {{ $condLabel }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Admin Delete Button --}}
                                @auth
                                @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                                <form method="POST" action="{{ route('plan.weather.observation.destroy', $obs) }}"
                                      onsubmit="return confirm('Delete this weather observation?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="{{ __('weather_safety.btn_delete') }}"
                                            class="rounded-lg p-1.5 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition">
                                        <i class="bi bi-trash3 text-xs"></i>
                                    </button>
                                </form>
                                @endif
                                @endauth
                            </div>

                            {{-- Metrics row --}}
                            <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                                @if($obs->temperature)
                                <span class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-0.5 font-bold text-amber-800 border border-amber-200/60">
                                    <i class="bi bi-thermometer-half"></i> {{ $obs->temperature }}°C
                                </span>
                                @endif
                                @if($obs->rainfall)
                                <span class="inline-flex items-center gap-1 rounded-md bg-sky-50 px-2 py-0.5 font-bold text-sky-800 border border-sky-200/60">
                                    <i class="bi bi-droplet"></i> {{ $obs->rainfall }} mm
                                </span>
                                @endif
                                @if($obs->wind_condition)
                                <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2 py-0.5 font-medium text-slate-700">
                                    <i class="bi bi-wind"></i> {{ $obs->wind_condition }}
                                </span>
                                @endif
                            </div>

                            @if($obs->translated_description)
                            <p class="mt-2.5 text-xs text-slate-600 italic bg-slate-50/80 rounded-lg p-2.5 border border-slate-100">
                                "{{ $obs->translated_description }}"
                            </p>
                            @endif

                            {{-- Footer metadata --}}
                            <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-2 text-[11px] text-slate-400">
                                <span>
                                    <i class="bi bi-clock me-1"></i> {{ $obs->observed_at->diffForHumans() }}
                                </span>
                                <span>
                                    <i class="bi bi-person me-1"></i> {{ $obs->user->name ?? 'Community Member' }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </section>

            {{-- ===================================================================== --}}
            {{-- 2. SAFETY & NATURAL DISASTER ALERTS SECTION --}}
            {{-- ===================================================================== --}}
            <section id="safety-section" class="scroll-mt-24 space-y-6 pt-6 border-t-2 border-slate-200/80">
                {{-- Section Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-200 pb-4">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-lg bg-rose-100 px-2.5 py-1 text-xs font-bold text-rose-800 mb-1">
                            <i class="bi bi-shield-exclamation"></i> {{ __('weather_safety.tab_safety') }}
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                            {{ __('weather_safety.sec_safety_title') }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                            {{ __('weather_safety.sec_safety_subtitle') }}
                        </p>
                    </div>

                    @auth
                    <button type="button" onclick="openModal('modal-add-alert')"
                            class="inline-flex items-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-500 px-4 py-2 text-xs sm:text-sm font-bold text-white shadow-sm transition">
                        <i class="bi bi-megaphone-fill"></i> {{ __('weather_safety.btn_post_alert') }}
                    </button>
                    @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-slate-800 hover:bg-slate-700 px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm transition">
                        <i class="bi bi-box-arrow-in-right"></i> {{ __('weather_safety.btn_post_alert') }}
                    </a>
                    @endauth
                </div>

                {{-- Notice strip explaining immediate publication & admin removal --}}
                <div class="flex items-start gap-2.5 rounded-xl border border-rose-200/80 bg-rose-50/60 p-3.5 text-xs text-rose-900">
                    <i class="bi bi-broadcast text-rose-600 text-base flex-shrink-0 mt-0.5"></i>
                    <p class="leading-relaxed">
                        {{ __('weather_safety.alert_immediate_notice') }}
                    </p>
                </div>

                {{-- Alert Cards List --}}
                @if($safetyAlerts->isEmpty())
                <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-emerald-200 py-12 px-4 text-center bg-emerald-50/30">
                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 text-2xl mb-2">
                        <i class="bi bi-shield-check"></i>
                    </span>
                    <h4 class="text-sm font-bold text-slate-800">{{ __('weather_safety.no_alerts_title') }}</h4>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm">{{ __('weather_safety.no_alerts_subtitle') }}</p>
                </div>
                @else
                <div class="space-y-4">
                    @foreach($safetyAlerts as $alert)
                    @php
                        $borderTheme = match($alert->severity) {
                            'danger'  => 'border-rose-400 bg-rose-50/40 ring-1 ring-rose-300',
                            'warning' => 'border-amber-400 bg-amber-50/40 ring-1 ring-amber-300',
                            default   => 'border-sky-300 bg-sky-50/30',
                        };
                        $iconClass = match($alert->hazard_type) {
                            'landslide'    => 'bi-triangle-half text-rose-600',
                            'rockfall'     => 'bi-hexagon-fill text-amber-600',
                            'flash_flood'  => 'bi-water text-sky-600',
                            'high_wind'    => 'bi-wind text-teal-600',
                            'dense_mist'   => 'bi-cloud-fog-fill text-slate-600',
                            'road_closure' => 'bi-sign-stop-fill text-rose-600',
                            default        => 'bi-exclamation-octagon text-amber-600',
                        };
                    @endphp
                    <div class="rounded-2xl border {{ $borderTheme }} p-5 shadow-xs transition hover:shadow-md">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div class="flex items-start gap-3.5">
                                <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-white shadow-xs text-xl">
                                    <i class="bi {{ $iconClass }}"></i>
                                </span>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <span class="rounded-full px-2.5 py-0.5 text-[11px] font-extrabold uppercase tracking-wider {{ $alert->severity_badge_class }}">
                                            {{ $alert->severity_label }}
                                        </span>
                                        <span class="rounded-full bg-slate-800 text-white px-2.5 py-0.5 text-[11px] font-bold">
                                            {{ $alert->hazard_type_label }}
                                        </span>
                                        @if($alert->translated_location_name)
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-700">
                                            <i class="bi bi-geo-alt-fill text-rose-500"></i> {{ $alert->translated_location_name }}
                                        </span>
                                        @endif
                                    </div>
                                    <h3 class="text-base font-extrabold text-slate-900">
                                        {{ $alert->translated_title }}
                                    </h3>
                                </div>
                            </div>

                            {{-- Admin Delete / Dismiss Button --}}
                            @auth
                            @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                            <form method="POST" action="{{ route('plan.weather.safety-alert.destroy', $alert) }}"
                                  onsubmit="return confirm('Dismiss and delete this safety alert?');" class="self-end sm:self-start">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 rounded-lg border border-rose-300 bg-white px-2.5 py-1 text-xs font-bold text-rose-700 hover:bg-rose-50 shadow-2xs transition">
                                    <i class="bi bi-x-circle"></i> {{ __('weather_safety.btn_delete') }} (Admin)
                                </button>
                            </form>
                            @endif
                            @endauth
                        </div>

                        {{-- Alert Description --}}
                        <div class="mt-3.5 text-xs sm:text-sm text-slate-700 leading-relaxed pl-1 sm:pl-13">
                            <p>{{ $alert->translated_description }}</p>

                            @if($alert->translated_safety_instructions)
                            <div class="mt-3 rounded-xl border border-amber-200/90 bg-amber-50/80 p-3 text-xs text-amber-900">
                                <strong class="block font-bold text-amber-950 mb-1 flex items-center gap-1.5">
                                    <i class="bi bi-shield-check text-amber-600"></i> {{ __('weather_safety.instructions_title') }}:
                                </strong>
                                <p>{{ $alert->translated_safety_instructions }}</p>
                            </div>
                            @endif

                            {{-- Footer info --}}
                            <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-400 pt-2 border-t border-slate-200/60">
                                <span>
                                    <i class="bi bi-clock me-1"></i> Reported {{ $alert->reported_at?->diffForHumans() }}
                                </span>
                                <span>
                                    <i class="bi bi-person-circle me-1"></i> {{ $alert->reporter->name ?? 'Community Reporter' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </section>

        </div>

        {{-- ========================================================================= --}}
        {{-- SIDEBAR: GUEST CTA CARD + EMERGENCY HOTLINES + TIPS --}}
        {{-- ========================================================================= --}}
        <aside class="lg:col-span-4 space-y-6">

            {{-- 1. GUEST CALL-TO-ACTION CARD (Compact "podi card view" when unauthenticated) --}}
            @guest
            <div class="rounded-2xl border-2 border-sky-400/50 bg-gradient-to-br from-sky-50 via-white to-sky-100/50 p-5 shadow-sm">
                <div class="flex items-center gap-3 mb-2.5">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-600 text-white shadow-xs">
                        <i class="bi bi-cloud-arrow-up-fill text-lg"></i>
                    </span>
                    <div>
                        <span class="inline-block rounded bg-sky-200 px-1.5 py-0.5 text-[10px] font-extrabold text-sky-800 uppercase tracking-wider">
                            Community Updates
                        </span>
                        <h3 class="text-sm font-extrabold text-slate-900">
                            {{ __('weather_safety.guest_cta_title') }}
                        </h3>
                    </div>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed mb-4">
                    {{ __('weather_safety.guest_cta_desc') }}
                </p>

                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-sky-600 hover:bg-sky-700 px-3 py-2 text-xs font-bold text-white shadow-xs transition">
                        <i class="bi bi-box-arrow-in-right"></i> {{ __('weather_safety.btn_signin') }}
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-sky-300 bg-white hover:bg-sky-50 px-3 py-2 text-xs font-bold text-sky-700 shadow-xs transition">
                        <i class="bi bi-person-plus"></i> {{ __('weather_safety.btn_register') }}
                    </a>
                </div>
            </div>
            @endguest

            {{-- 2. AUTHENTICATED USER QUICK ACTIONS --}}
            @auth
            <div class="rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50/60 to-white p-5 shadow-xs space-y-3">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-white">
                        <i class="bi bi-person-badge"></i>
                    </span>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Community Contributor</h4>
                        <p class="text-[11px] text-slate-500">{{ auth()->user()->name }}</p>
                    </div>
                </div>

                <div class="space-y-2 pt-1">
                    <button type="button" onclick="openModal('modal-add-obs')"
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 px-3.5 py-2.5 text-xs font-bold text-white shadow-xs transition">
                        <i class="bi bi-cloud-plus-fill"></i> {{ __('weather_safety.btn_report_weather') }}
                    </button>
                    <button type="button" onclick="openModal('modal-add-alert')"
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-500 px-3.5 py-2.5 text-xs font-bold text-white shadow-xs transition">
                        <i class="bi bi-megaphone-fill"></i> {{ __('weather_safety.btn_post_alert') }}
                    </button>
                    @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                    <button type="button" onclick="openModal('modal-add-location')"
                            class="w-full flex items-center justify-center gap-2 rounded-xl border border-sky-300 bg-white hover:bg-sky-50 px-3.5 py-2 text-xs font-bold text-sky-700 transition">
                        <i class="bi bi-plus-circle"></i> Add Weather Station (Admin)
                    </button>
                    @endif
                </div>
            </div>
            @endauth

            {{-- 3. EMERGENCY RESPONSE HOTLINES CARD --}}
            <div class="rounded-2xl border border-rose-200 bg-white p-5 shadow-xs">
                <div class="mb-3.5 flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-100 text-rose-600">
                        <i class="bi bi-telephone-inbound-fill"></i>
                    </span>
                    <h3 class="text-sm font-extrabold text-slate-900">
                        {{ __('weather_safety.emergency_hotlines_title') }}
                    </h3>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between rounded-xl bg-rose-50/70 p-2.5 border border-rose-100">
                        <div>
                            <span class="font-bold text-slate-900">Disaster Management Centre</span>
                            <span class="block text-[11px] text-slate-500">24/7 National Emergency</span>
                        </div>
                        <a href="tel:117" class="rounded-lg bg-rose-600 px-3 py-1 font-extrabold text-white text-xs hover:bg-rose-500">
                            117
                        </a>
                    </div>

                    <div class="flex items-center justify-between rounded-xl bg-slate-50 p-2.5 border border-slate-200/80">
                        <div>
                            <span class="font-bold text-slate-900">Police Emergency</span>
                            <span class="block text-[11px] text-slate-500">National Police Hotline</span>
                        </div>
                        <a href="tel:119" class="rounded-lg bg-slate-800 px-3 py-1 font-extrabold text-white text-xs hover:bg-slate-700">
                            119
                        </a>
                    </div>

                    <div class="flex items-center justify-between rounded-xl bg-slate-50 p-2.5 border border-slate-200/80">
                        <div>
                            <span class="font-bold text-slate-900">Suwaseriya Ambulance</span>
                            <span class="block text-[11px] text-slate-500">Free Medical Service</span>
                        </div>
                        <a href="tel:1990" class="rounded-lg bg-emerald-600 px-3 py-1 font-extrabold text-white text-xs hover:bg-emerald-500">
                            1990
                        </a>
                    </div>

                    <div class="flex items-center justify-between rounded-xl bg-slate-50 p-2.5 border border-slate-200/80">
                        <div>
                            <span class="font-bold text-slate-900">Laggala Police Station</span>
                            <span class="block text-[11px] text-slate-500">Local Area Police</span>
                        </div>
                        <a href="tel:0662276222" class="rounded-lg bg-sky-700 px-2.5 py-1 font-bold text-white text-xs hover:bg-sky-600">
                            066-2276222
                        </a>
                    </div>
                </div>
            </div>

            {{-- 4. KNUCKLES / LAGGALA SAFETY PRECAUTIONS CARD --}}
            <div class="rounded-2xl border border-amber-200/90 bg-gradient-to-br from-amber-50/70 to-yellow-50/50 p-5 shadow-xs">
                <div class="mb-3 flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-200/70 text-amber-800">
                        <i class="bi bi-shield-shaded"></i>
                    </span>
                    <h3 class="text-sm font-extrabold text-amber-950">
                        Knuckles Mountain Precautions
                    </h3>
                </div>

                <ul class="space-y-2 text-xs text-amber-900">
                    <li class="flex items-start gap-2">
                        <i class="bi bi-check2-circle text-amber-600 flex-shrink-0 mt-0.5"></i>
                        <span><strong>Thelgamu Oya:</strong> Never bathe in streams when water turns reddish-brown or rises rapidly.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-check2-circle text-amber-600 flex-shrink-0 mt-0.5"></i>
                        <span><strong>Riverston Wind Gap:</strong> Expect sudden gale-force winds exceeding 60 km/h. Keep firm balance.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-check2-circle text-amber-600 flex-shrink-0 mt-0.5"></i>
                        <span><strong>Pitawala Pathana:</strong> Dense fog can reduce visibility to under 5 meters near sheer cliffs.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-check2-circle text-amber-600 flex-shrink-0 mt-0.5"></i>
                        <span><strong>Mountain Passes:</strong> Watch for loose rocks and mud-slips along Ilukkumbura-Laggala road.</span>
                    </li>
                </ul>
            </div>

        </aside>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- MODALS --}}
{{-- ========================================================================= --}}

{{-- Modal 1: Submit Ground Weather Observation --}}
@auth
<div id="modal-add-obs" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 p-4 backdrop-blur-xs sm:p-6">
    <div class="mx-auto my-8 max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                <i class="bi bi-cloud-upload text-emerald-600"></i> {{ __('weather_safety.modal_obs_title') }}
            </h3>
            <button type="button" onclick="closeModal('modal-add-obs')" class="text-slate-400 hover:text-slate-600">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('plan.weather.store') }}" class="mt-4 space-y-3.5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    {{ __('weather_safety.field_station') }}
                </label>
                <select name="weather_location_id" id="obs-location-select"
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs sm:text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    <option value="">-- Choose Nearest Station or Custom Below --</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->translated_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    {{ __('weather_safety.field_location_name') }}
                </label>
                <input type="text" name="location_name" id="obs-custom-location"
                       value="{{ old('location_name') }}" placeholder="e.g. Pallegama, Riverston, Ilukkumbura..."
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs sm:text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('weather_safety.field_condition') }}
                    </label>
                    <select name="condition" required
                            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs sm:text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                        <option value="sunny">☀️ {{ __('weather_safety.cond_sunny') }}</option>
                        <option value="partly_cloudy">⛅ {{ __('weather_safety.cond_partly_cloudy') }}</option>
                        <option value="cloudy">☁️ {{ __('weather_safety.cond_cloudy') }}</option>
                        <option value="rain">🌧️ {{ __('weather_safety.cond_rain') }}</option>
                        <option value="heavy_rain">⛈️ {{ __('weather_safety.cond_heavy_rain') }}</option>
                        <option value="mist">🌫️ {{ __('weather_safety.cond_mist') }}</option>
                        <option value="fog">🌁 {{ __('weather_safety.cond_fog') }}</option>
                        <option value="windy">💨 {{ __('weather_safety.cond_windy') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('weather_safety.field_temp') }}
                    </label>
                    <input type="number" step="0.1" name="temperature" value="{{ old('temperature') }}" placeholder="e.g. 24.5"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs sm:text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('weather_safety.field_rainfall') }}
                    </label>
                    <input type="number" step="0.1" name="rainfall" value="{{ old('rainfall') }}" placeholder="e.g. 15.0"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs sm:text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('weather_safety.field_wind') }}
                    </label>
                    <input type="text" name="wind_condition" value="{{ old('wind_condition') }}" placeholder="e.g. Strong gusts, Calm"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs sm:text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    {{ __('weather_safety.field_description') }}
                </label>
                <textarea name="description" rows="3" placeholder="Describe the current ground weather, cloud cover, water flow, or trail condition..."
                          class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs sm:text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-add-obs')"
                        class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    {{ __('weather_safety.btn_cancel') }}
                </button>
                <button type="submit"
                        class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-emerald-500 transition">
                    {{ __('weather_safety.btn_submit') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endauth

{{-- Modal 2: Submit Natural Hazard / Safety Alert --}}
@auth
<div id="modal-add-alert" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 p-4 backdrop-blur-xs sm:p-6">
    <div class="mx-auto my-8 max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                <i class="bi bi-megaphone-fill text-rose-600"></i> {{ __('weather_safety.modal_alert_title') }}
            </h3>
            <button type="button" onclick="closeModal('modal-add-alert')" class="text-slate-400 hover:text-slate-600">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('plan.weather.safety-alert.store') }}" class="mt-4 space-y-3.5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Alert Title *
                </label>
                <input type="text" name="title" required value="{{ old('title') }}"
                       placeholder="e.g. Riverston Road Slip / Knuckles Stream Spate"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs sm:text-sm focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-100">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('weather_safety.field_hazard_type') }}
                    </label>
                    <select name="hazard_type" required
                            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs sm:text-sm focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-100">
                        <option value="landslide">⚠️ {{ __('weather_safety.hazard_landslide') }}</option>
                        <option value="rockfall">🪨 {{ __('weather_safety.hazard_rockfall') }}</option>
                        <option value="flash_flood">🌊 {{ __('weather_safety.hazard_flash_flood') }}</option>
                        <option value="high_wind">💨 {{ __('weather_safety.hazard_high_wind') }}</option>
                        <option value="dense_mist">🌫️ {{ __('weather_safety.hazard_dense_mist') }}</option>
                        <option value="road_closure">⛔ {{ __('weather_safety.hazard_road_closure') }}</option>
                        <option value="other">ℹ️ {{ __('weather_safety.hazard_other') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('weather_safety.field_severity') }}
                    </label>
                    <select name="severity" required
                            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs sm:text-sm focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-100">
                        <option value="advisory">🔵 {{ __('weather_safety.severity_advisory') }}</option>
                        <option value="warning" selected>🟡 {{ __('weather_safety.severity_warning') }}</option>
                        <option value="danger">🔴 {{ __('weather_safety.severity_danger') }}</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('weather_safety.field_station') }}
                    </label>
                    <select name="weather_location_id"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs sm:text-sm focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-100">
                        <option value="">-- Nearest Location --</option>
                        @foreach($locations as $loc)
                        <option value="{{ $loc->id }}">{{ $loc->translated_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('weather_safety.field_location_name') }}
                    </label>
                    <input type="text" name="location_name" value="{{ old('location_name') }}" placeholder="Specific path or road..."
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs sm:text-sm focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-100">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    {{ __('weather_safety.field_description') }}
                </label>
                <textarea name="description" rows="3" required placeholder="Details about what happened or is developing (location, extent, road blockages)..."
                          class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs sm:text-sm focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-100">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    {{ __('weather_safety.field_instructions') }}
                </label>
                <textarea name="safety_instructions" rows="2" placeholder="e.g. Avoid bathing in streams, take alternate road via Rattota, drive under 20km/h..."
                          class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs sm:text-sm focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-100">{{ old('safety_instructions') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-add-alert')"
                        class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    {{ __('weather_safety.btn_cancel') }}
                </button>
                <button type="submit"
                        class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-rose-500 transition">
                    {{ __('weather_safety.btn_submit') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endauth

{{-- Modal 3: Add Weather Station (Admin Only) --}}
@auth
@if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
<div id="modal-add-location" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 p-4 backdrop-blur-xs sm:p-6">
    <div class="mx-auto my-8 max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                <i class="bi bi-pin-map-fill text-sky-600"></i> {{ __('weather_safety.modal_location_title') }}
            </h3>
            <button type="button" onclick="closeModal('modal-add-location')" class="text-slate-400 hover:text-slate-600">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('plan.weather.location.store') }}" class="mt-4 space-y-3.5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Station Name *
                </label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Deanston Conservation Centre"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('weather_safety.field_lat') }} *
                    </label>
                    <input type="number" step="any" name="latitude" required value="{{ old('latitude', '7.5583') }}"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('weather_safety.field_lng') }} *
                    </label>
                    <input type="number" step="any" name="longitude" required value="{{ old('longitude', '80.7306') }}"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('weather_safety.field_elevation') }}
                    </label>
                    <input type="number" name="elevation_m" value="{{ old('elevation_m', '400') }}" placeholder="e.g. 850"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Sort Order
                    </label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', '10') }}"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Description / Region Notes
                </label>
                <textarea name="description" rows="2" placeholder="Brief notes on microclimate or landscape..."
                          class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-add-location')"
                        class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    {{ __('weather_safety.btn_cancel') }}
                </button>
                <button type="submit"
                        class="rounded-xl bg-sky-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-sky-500 transition">
                    {{ __('weather_safety.btn_save_location') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endauth

{{-- ========= JAVASCRIPT ========= --}}
<script>
(function () {
    const LOCATIONS = @json($locationsData);

    const WEATHER_CODES = {
        0: 'Clear sky', 1: 'Mainly clear', 2: 'Partly cloudy', 3: 'Overcast',
        45: 'Fog', 48: 'Depositing rime fog', 51: 'Light drizzle', 53: 'Moderate drizzle', 55: 'Dense drizzle',
        61: 'Slight rain', 63: 'Moderate rain', 65: 'Heavy rain',
        71: 'Slight snow', 73: 'Moderate snow', 75: 'Heavy snow',
        80: 'Slight rain showers', 81: 'Moderate showers', 82: 'Violent downpour',
        95: 'Thunderstorm', 96: 'Thunderstorm w/ hail', 99: 'Severe thunderstorm'
    };

    const WX_ICONS = {
        0: '☀️', 1: '🌤️', 2: '⛅', 3: '☁️',
        45: '🌫️', 48: '🌫️', 51: '🌦️', 53: '🌦️', 55: '🌧️',
        61: '🌧️', 63: '🌧️', 65: '⛈️',
        71: '🌨️', 73: '❄️', 75: '❄️',
        80: '🌦️', 81: '🌧️', 82: '⛈️',
        95: '⛈️', 96: '⛈️', 99: '⛈️'
    };

    const DAY_NAMES = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    let activeStation = LOCATIONS.length > 0 ? LOCATIONS[0] : {
        id: 1,
        name: 'Pallegama (Laggala Town)',
        latitude: 7.5583,
        longitude: 80.7306,
        elevation_m: 280
    };

    async function loadStationWeather(station) {
        const loadingEl = document.getElementById('weather-loading');
        const dataEl = document.getElementById('weather-data');
        const errorEl = document.getElementById('weather-error');

        loadingEl.classList.remove('hidden');
        dataEl.classList.add('hidden');
        errorEl.classList.add('hidden');

        document.getElementById('wx-location-name').textContent = station.name;
        document.getElementById('wx-elevation').textContent = (station.elevation_m || 350) + 'm';

        const url = `https://api.open-meteo.com/v1/forecast?latitude=${station.latitude}&longitude=${station.longitude}` +
            `&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m,precipitation` +
            `&daily=weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max` +
            `&timezone=Asia%2FColombo&forecast_days=5`;

        try {
            const res = await fetch(url);
            if (!res.ok) throw new Error('API status: ' + res.status);
            const data = await res.json();

            const rainProb = data.daily?.precipitation_probability_max?.[0]
                ?? (data.current.precipitation > 0 ? 80 : 20);

            renderCurrent(data.current, rainProb);
            renderForecast(data.daily);

            loadingEl.classList.add('hidden');
            dataEl.classList.remove('hidden');
            document.getElementById('weather-updated').textContent = 'Live satellite data (Open-Meteo)';
        } catch (err) {
            console.warn('Open-Meteo live feed unavailable, using regional baseline:', err);
            // Render reliable mountain regional baseline
            const baseTemp = station.elevation_m > 1000 ? 19 : (station.elevation_m > 600 ? 23 : 27);
            renderCurrent({
                temperature_2m: baseTemp,
                weather_code: 2,
                wind_speed_10m: station.elevation_m > 800 ? 24 : 12,
                relative_humidity_2m: 78,
            }, 30);

            renderFallbackForecast(baseTemp);
            loadingEl.classList.add('hidden');
            dataEl.classList.remove('hidden');
            errorEl.classList.remove('hidden');
        }
    }

    function renderCurrent(c, rainProb) {
        document.getElementById('wx-icon').textContent = WX_ICONS[c.weather_code] || '⛅';
        document.getElementById('wx-temp').textContent = Math.round(c.temperature_2m);
        document.getElementById('wx-desc').textContent = WEATHER_CODES[c.weather_code] || 'Partly Cloudy';
        document.getElementById('wx-rain').textContent = rainProb + '%';
        document.getElementById('wx-wind').textContent = Math.round(c.wind_speed_10m) + ' km/h';
        document.getElementById('wx-humidity').textContent = c.relative_humidity_2m + '%';
    }

    function renderForecast(d) {
        const container = document.getElementById('wx-forecast');
        container.innerHTML = '';
        if (!d || !d.time) return;

        for (let i = 0; i < d.time.length; i++) {
            const dayName = i === 0 ? 'Today' : DAY_NAMES[new Date(d.time[i]).getDay()];
            const div = document.createElement('div');
            div.className = 'flex flex-col items-center gap-1 rounded-xl border border-sky-100 bg-white/90 py-3 px-2 text-center shadow-2xs';
            div.innerHTML = `
                <span class="text-[11px] font-bold text-slate-500">${dayName}</span>
                <span class="text-2xl">${WX_ICONS[d.weather_code[i]] || '⛅'}</span>
                <span class="text-sm font-extrabold text-slate-900">${Math.round(d.temperature_2m_max[i])}°</span>
                <span class="text-[11px] text-slate-400">${Math.round(d.temperature_2m_min[i])}°</span>
                <span class="text-[10px] font-bold text-sky-600">${d.precipitation_probability_max[i]}% rain</span>
            `;
            container.appendChild(div);
        }
    }

    function renderFallbackForecast(baseTemp) {
        const container = document.getElementById('wx-forecast');
        container.innerHTML = '';
        const today = new Date();
        for (let i = 0; i < 5; i++) {
            const date = new Date(today);
            date.setDate(today.getDate() + i);
            const dayName = i === 0 ? 'Today' : DAY_NAMES[date.getDay()];
            const div = document.createElement('div');
            div.className = 'flex flex-col items-center gap-1 rounded-xl border border-sky-100 bg-white/90 py-3 px-2 text-center shadow-2xs';
            div.innerHTML = `
                <span class="text-[11px] font-bold text-slate-500">${dayName}</span>
                <span class="text-2xl">⛅</span>
                <span class="text-sm font-extrabold text-slate-900">${baseTemp + (i%2)}°</span>
                <span class="text-[11px] text-slate-400">${baseTemp - 6}°</span>
                <span class="text-[10px] font-bold text-sky-600">${25 + (i*10)%40}% rain</span>
            `;
            container.appendChild(div);
        }
    }

    // Handle station switching pills
    document.querySelectorAll('.station-pill').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.station-pill').forEach(b => {
                b.className = 'station-pill inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition cursor-pointer bg-white text-slate-700 border border-slate-200 hover:bg-sky-50';
            });
            this.className = 'station-pill inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition cursor-pointer bg-sky-700 text-white shadow-sm ring-2 ring-sky-600';

            const idx = parseInt(this.getAttribute('data-station-index'), 10);
            if (LOCATIONS[idx]) {
                activeStation = LOCATIONS[idx];
                loadStationWeather(activeStation);
            }
        });
    });

    // Auto-fill custom location input if station is selected in modal
    const obsSelect = document.getElementById('obs-location-select');
    const obsCustom = document.getElementById('obs-custom-location');
    if (obsSelect && obsCustom) {
        obsSelect.addEventListener('change', function () {
            const selectedText = obsSelect.options[obsSelect.selectedIndex]?.text;
            if (obsSelect.value && selectedText && !selectedText.startsWith('--')) {
                obsCustom.value = selectedText;
            }
        });
    }

    // Initial load
    if (LOCATIONS.length > 0) {
        loadStationWeather(LOCATIONS[0]);
    }
})();

// Modal helper functions
function openModal(id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.add('hidden');
        document.body.style.overflow = '';
    }
}
</script>

@endsection
