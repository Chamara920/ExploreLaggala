@extends('layouts.public')

@section('title', __('trip_planner.page_title'))

@section('content')

<div class="bg-slate-50 py-10 lg:py-14">

    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        {{-- Header Section --}}
        <div class="text-center">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3.5 py-1 text-xs font-bold text-emerald-800 tracking-wide uppercase">
                <i class="bi bi-compass"></i> {{ __('trip_planner.badge_rule_based') }}
            </span>

            <h1 class="mt-3 text-3xl font-extrabold text-slate-900 sm:text-4xl">
                {{ __('trip_planner.title') }}
            </h1>

            <p class="mx-auto mt-2 max-w-2xl text-base text-slate-600">
                {{ __('trip_planner.subtitle') }}
            </p>
        </div>

        {{-- Live Regional Conditions Bar (Weather & Road Safety) --}}
        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">

            {{-- 1. Weather Snapshot --}}
            <div class="flex items-start gap-3.5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm hover:border-sky-300 transition">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-600 text-xl">
                    @if(str_contains(strtolower($weatherPreview['condition'] ?? ''), 'rain'))
                        <i class="bi bi-cloud-rain-fill"></i>
                    @elseif(str_contains(strtolower($weatherPreview['condition'] ?? ''), 'cloud'))
                        <i class="bi bi-cloud-sun-fill"></i>
                    @elseif(str_contains(strtolower($weatherPreview['condition'] ?? ''), 'mist') || str_contains(strtolower($weatherPreview['condition'] ?? ''), 'fog'))
                        <i class="bi bi-cloud-fog-fill text-slate-500"></i>
                    @elseif(str_contains(strtolower($weatherPreview['condition'] ?? ''), 'wind'))
                        <i class="bi bi-wind text-teal-600"></i>
                    @else
                        <i class="bi bi-sun-fill text-amber-500"></i>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-1 flex-wrap">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('trip_planner.regional_weather') }}</span>
                        <div class="flex items-center gap-1.5">
                            @if(!empty($weatherPreview['is_fresh_48h']))
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-extrabold text-emerald-800">
                                    <i class="bi bi-clock-history"></i> {{ __('trip_planner.weather_live_observations') }}
                                </span>
                            @endif
                            <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700">
                                {{ $weatherPreview['temperature'] }}°C
                            </span>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-slate-900 mt-0.5">
                        {{ ucfirst(str_replace('_', ' ', $weatherPreview['condition'] ?? 'Pleasant')) }} • {{ $weatherPreview['location_name'] ?? 'Laggala' }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500 line-clamp-2">
                        {{ $weatherPreview['advisory'] ?? __('trip_planner.favorable_conditions') }}
                    </p>
                    <a href="{{ route('plan.weather.index') }}" class="mt-2 inline-flex items-center gap-1 text-[11px] font-bold text-sky-600 hover:text-sky-700 hover:underline">
                        <i class="bi bi-cloud-sun"></i> {{ __('trip_planner.view_weather_safety_page') }} ➔
                    </a>
                </div>
            </div>

            {{-- 2. Road Safety & Natural Disaster Snapshot --}}
            <div class="flex items-start gap-3.5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm hover:border-rose-300 transition">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ ($roadSafetyPreview['has_critical_danger'] ?? false) || ($roadSafetyPreview['has_closures'] ?? false) ? 'bg-red-100 text-red-600' : (($roadSafetyPreview['has_alerts'] ?? false) ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-600') }} text-xl">
                    @if($roadSafetyPreview['has_critical_danger'] ?? false)
                        <i class="bi bi-exclamation-triangle-fill text-rose-600 animate-pulse"></i>
                    @elseif($roadSafetyPreview['has_closures'] ?? false)
                        <i class="bi bi-cone-striped"></i>
                    @elseif($roadSafetyPreview['has_alerts'] ?? false)
                        <i class="bi bi-shield-exclamation"></i>
                    @else
                        <i class="bi bi-shield-check"></i>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-1 flex-wrap">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('trip_planner.road_safety_status') }}</span>
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold {{ ($roadSafetyPreview['has_critical_danger'] ?? false) || ($roadSafetyPreview['has_closures'] ?? false) ? 'bg-red-100 text-red-700' : (($roadSafetyPreview['has_alerts'] ?? false) ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-700') }}">
                            {{ $roadSafetyPreview['summary_badge'] }}
                        </span>
                    </div>
                    <p class="text-sm font-bold text-slate-900 mt-0.5">
                        @if($roadSafetyPreview['has_alerts'] ?? false)
                            {{ $roadSafetyPreview['alerts']->first()['title'] ?? __('trip_planner.road_safety_status') }}
                        @else
                            {{ __('trip_planner.corridors_operational') }}
                        @endif
                    </p>
                    <p class="mt-1 text-xs text-slate-500 line-clamp-2">
                        @if(!empty($roadSafetyPreview['alerts']->first()['safety_instructions']))
                            <strong>{{ __('trip_planner.disaster_warning_instruction') }}:</strong> {{ $roadSafetyPreview['alerts']->first()['safety_instructions'] }}
                        @else
                            {{ $roadSafetyPreview['note'] }}
                        @endif
                    </p>
                    <a href="{{ route('plan.weather.index') }}#safety-section" class="mt-2 inline-flex items-center gap-1 text-[11px] font-bold text-rose-600 hover:text-rose-700 hover:underline">
                        <i class="bi bi-shield-exclamation"></i> {{ __('trip_planner.active_safety_alerts') }} ➔
                    </a>
                </div>
            </div>

        </div>

        {{-- Form Validation Errors --}}
        @if($errors->any())
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 shadow-sm">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ __('trip_planner.please_correct_errors') }}
                </div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Planner Form --}}
        <form
            method="POST"
            action="{{ route('plan.smart-trip-planner.generate') }}"
            class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-9"
        >
            @csrf

            <div class="space-y-8">

                {{-- 1. Trip Duration (1 Day, 2 Days, 3 Days) --}}
                <div>
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-bold text-slate-800">
                            <i class="bi bi-calendar-range me-1.5 text-emerald-600"></i> {{ __('trip_planner.trip_duration_label') }}
                        </label>
                        <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                            1 – 3 Days
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs text-slate-500">{{ __('trip_planner.trip_duration_hint') }}</p>

                    <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="trip_days"
                                value="1"
                                class="trip-days-radio peer sr-only"
                                @checked(old('trip_days', '1') == '1')
                            >
                            <div class="rounded-2xl border-2 border-slate-200 p-4 text-center transition-all duration-150 hover:border-slate-300 peer-checked:border-emerald-600 peer-checked:bg-emerald-50/70 peer-checked:text-emerald-900 peer-checked:shadow-sm">
                                <div class="text-2xl">🌟</div>
                                <div class="mt-1.5 text-sm font-extrabold text-slate-900 peer-checked:text-emerald-900">{{ __('trip_planner.duration_1_day') }}</div>
                                <div class="mt-0.5 text-xs text-slate-500 peer-checked:text-emerald-700">{{ __('trip_planner.duration_1_day_desc') }}</div>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="trip_days"
                                value="2"
                                class="trip-days-radio peer sr-only"
                                @checked(old('trip_days') == '2')
                            >
                            <div class="rounded-2xl border-2 border-slate-200 p-4 text-center transition-all duration-150 hover:border-slate-300 peer-checked:border-emerald-600 peer-checked:bg-emerald-50/70 peer-checked:text-emerald-900 peer-checked:shadow-sm">
                                <div class="text-2xl">🌄</div>
                                <div class="mt-1.5 text-sm font-extrabold text-slate-900 peer-checked:text-emerald-900">{{ __('trip_planner.duration_2_days') }}</div>
                                <div class="mt-0.5 text-xs text-slate-500 peer-checked:text-emerald-700">{{ __('trip_planner.duration_2_days_desc') }}</div>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="trip_days"
                                value="3"
                                class="trip-days-radio peer sr-only"
                                @checked(old('trip_days') == '3')
                            >
                            <div class="rounded-2xl border-2 border-slate-200 p-4 text-center transition-all duration-150 hover:border-slate-300 peer-checked:border-emerald-600 peer-checked:bg-emerald-50/70 peer-checked:text-emerald-900 peer-checked:shadow-sm">
                                <div class="text-2xl">🏕️</div>
                                <div class="mt-1.5 text-sm font-extrabold text-slate-900 peer-checked:text-emerald-900">{{ __('trip_planner.duration_3_days') }}</div>
                                <div class="mt-0.5 text-xs text-slate-500 peer-checked:text-emerald-700">{{ __('trip_planner.duration_3_days_desc') }}</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- 2. Date & Start Time --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="travel_date" class="block text-sm font-bold text-slate-800">
                            <i class="bi bi-calendar-event me-1.5 text-emerald-600"></i> {{ __('trip_planner.travel_date') }}
                        </label>
                        <input
                            type="date"
                            id="travel_date"
                            name="travel_date"
                            min="{{ now()->format('Y-m-d') }}"
                            value="{{ old('travel_date', now()->format('Y-m-d')) }}"
                            required
                            class="form-control mt-2 rounded-xl border-slate-300 py-2.5 px-3 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >
                        <p class="mt-1.5 text-xs text-slate-400">{{ __('trip_planner.travel_date_hint') }}</p>
                    </div>

                    <div>
                        <label for="start_time" class="block text-sm font-bold text-slate-800">
                            <i class="bi bi-clock me-1.5 text-emerald-600"></i> {{ __('trip_planner.start_time') }}
                        </label>
                        <input
                            type="time"
                            id="start_time"
                            name="start_time"
                            value="{{ old('start_time', '08:00') }}"
                            required
                            class="form-control mt-2 rounded-xl border-slate-300 py-2.5 px-3 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >
                        <p class="mt-1.5 text-xs text-slate-400">{{ __('trip_planner.start_time_hint') }}</p>
                    </div>
                </div>

                {{-- 3. Available Duration (shown dynamically for 1-day tours) --}}
                <div id="availableTimeSection" class="{{ old('trip_days', '1') != '1' ? 'hidden' : '' }}">
                    <label class="block text-sm font-bold text-slate-800">
                        <i class="bi bi-hourglass-split me-1.5 text-emerald-600"></i> {{ __('trip_planner.available_time') }}
                    </label>

                    <div class="mt-2.5 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @foreach([
                            '4' => [__('trip_planner.hours_4'), __('trip_planner.hours_4_desc')],
                            '6' => [__('trip_planner.hours_6'), __('trip_planner.hours_6_desc')],
                            '8' => [__('trip_planner.hours_8'), __('trip_planner.hours_8_desc')],
                            'full_day' => [__('trip_planner.full_day'), __('trip_planner.full_day_desc')],
                        ] as $value => $meta)
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="available_time"
                                    value="{{ $value }}"
                                    class="peer sr-only"
                                    @checked(old('available_time', '8') === $value)
                                >
                                <div class="rounded-2xl border-2 border-slate-200 p-3.5 text-center transition-all duration-150 hover:border-slate-300 peer-checked:border-emerald-600 peer-checked:bg-emerald-50/70 peer-checked:text-emerald-900 peer-checked:shadow-sm">
                                    <div class="text-sm font-extrabold text-slate-900 peer-checked:text-emerald-900">{{ $meta[0] }}</div>
                                    <div class="mt-0.5 text-xs text-slate-500 peer-checked:text-emerald-700">{{ $meta[1] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- 4. Starting Point --}}
                <div>
                    <label for="start_location_name" class="block text-sm font-bold text-slate-800">
                        <i class="bi bi-geo-alt-fill me-1.5 text-emerald-600"></i> {{ __('trip_planner.origin_label') }}
                    </label>

                    <div class="mt-2 flex gap-2">
                        <input
                            id="start_location_name"
                            name="start_location_name"
                            type="text"
                            value="{{ old('start_location_name', 'Matale Bus Stand') }}"
                            required
                            class="form-control rounded-xl border-slate-300 py-2.5 px-3 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                            placeholder="{{ __('trip_planner.origin_placeholder') }}"
                        >
                        <button
                            type="button"
                            id="useLocation"
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-100"
                        >
                            <i class="bi bi-crosshair"></i> {{ __('trip_planner.use_my_gps') }}
                        </button>
                    </div>

                    {{-- Popular Presets Chips (Includes major bus stands for travelers) --}}
                    <div class="mt-2.5 flex flex-wrap items-center gap-1.5 text-xs">
                        <span class="text-slate-400 font-medium">{{ __('trip_planner.quick_hubs') }}</span>
                        <button type="button" class="preset-btn rounded-lg bg-emerald-50 border border-emerald-200 px-2.5 py-1 text-emerald-900 hover:bg-emerald-100 transition font-semibold"
                            data-name="Matale Bus Stand" data-lat="7.4675" data-lng="80.6234">
                            🚌 {{ __('trip_planner.hub_matale_stand') }}
                        </button>
                        <button type="button" class="preset-btn rounded-lg bg-slate-100 px-2.5 py-1 text-slate-700 hover:bg-emerald-100 hover:text-emerald-800 transition"
                            data-name="Pallegama Town" data-lat="7.5452" data-lng="80.7912">
                            🏙️ {{ __('trip_planner.hub_pallegama') }}
                        </button>
                        <button type="button" class="preset-btn rounded-lg bg-slate-100 px-2.5 py-1 text-slate-700 hover:bg-emerald-100 hover:text-emerald-800 transition"
                            data-name="Riverston Gap" data-lat="7.5885" data-lng="80.7555">
                            ⛰️ {{ __('trip_planner.hub_riverston') }}
                        </button>
                        <button type="button" class="preset-btn rounded-lg bg-slate-100 px-2.5 py-1 text-slate-700 hover:bg-emerald-100 hover:text-emerald-800 transition"
                            data-name="Illukkumbura Bridge" data-lat="7.5140" data-lng="80.7480">
                            🌉 {{ __('trip_planner.hub_illukkumbura') }}
                        </button>
                        <button type="button" class="preset-btn rounded-lg bg-slate-100 px-2.5 py-1 text-slate-700 hover:bg-emerald-100 hover:text-emerald-800 transition"
                            data-name="Rattota Bus Stand" data-lat="7.5160" data-lng="80.6720">
                            🚌 {{ __('trip_planner.hub_rattota') }}
                        </button>
                        <button type="button" class="preset-btn rounded-lg bg-slate-100 px-2.5 py-1 text-slate-700 hover:bg-emerald-100 hover:text-emerald-800 transition"
                            data-name="Laggala DS Office" data-lat="7.7286" data-lng="80.7839">
                            🏛️ {{ __('trip_planner.hub_laggala_ds') }}
                        </button>
                    </div>

                    <input type="hidden" id="start_latitude" name="start_latitude" value="{{ old('start_latitude', '7.4675') }}">
                    <input type="hidden" id="start_longitude" name="start_longitude" value="{{ old('start_longitude', '80.6234') }}">
                </div>

                {{-- 5. Travel Method (Highlighted Public Bus for Local & Foreign Backpackers) --}}
                <div>
                    <label for="travel_mode" class="block text-sm font-bold text-slate-800">
                        <i class="bi bi-car-front-fill me-1.5 text-emerald-600"></i> {{ __('trip_planner.travel_mode_label') }}
                    </label>

                    <div class="mt-2.5 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="travel_mode"
                                value="private_vehicle"
                                class="peer sr-only"
                                @checked(old('travel_mode', 'private_vehicle') === 'private_vehicle')
                            >
                            <div class="rounded-2xl border-2 border-slate-200 p-3.5 transition hover:border-slate-300 peer-checked:border-emerald-600 peer-checked:bg-emerald-50/70 peer-checked:text-emerald-900 h-full flex flex-col justify-between">
                                <div>
                                    <div class="text-xl">🚗</div>
                                    <div class="mt-1 font-bold text-slate-900 peer-checked:text-emerald-900 text-sm">{{ __('trip_planner.mode_private') }}</div>
                                </div>
                                <div class="text-xs text-slate-500 peer-checked:text-emerald-700 mt-1">{{ __('trip_planner.mode_private_desc') }}</div>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="travel_mode"
                                value="motorbike"
                                class="peer sr-only"
                                @checked(old('travel_mode') === 'motorbike')
                            >
                            <div class="rounded-2xl border-2 border-slate-200 p-3.5 transition hover:border-slate-300 peer-checked:border-emerald-600 peer-checked:bg-emerald-50/70 peer-checked:text-emerald-900 h-full flex flex-col justify-between">
                                <div>
                                    <div class="text-xl">🏍️</div>
                                    <div class="mt-1 font-bold text-slate-900 peer-checked:text-emerald-900 text-sm">{{ __('trip_planner.mode_bike') }}</div>
                                </div>
                                <div class="text-xs text-slate-500 peer-checked:text-emerald-700 mt-1">{{ __('trip_planner.mode_bike_desc') }}</div>
                            </div>
                        </label>

                        <label class="cursor-pointer relative">
                            <input
                                type="radio"
                                name="travel_mode"
                                value="public_transport"
                                class="peer sr-only"
                                @checked(old('travel_mode') === 'public_transport')
                            >
                            <div class="rounded-2xl border-2 border-slate-200 p-3.5 transition hover:border-slate-300 peer-checked:border-emerald-600 peer-checked:bg-emerald-50/70 peer-checked:text-emerald-900 h-full flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <div class="text-xl">🚌</div>
                                        <span class="rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5">
                                            {{ __('trip_planner.mode_public_bus_badge') }}
                                        </span>
                                    </div>
                                    <div class="mt-1 font-bold text-slate-900 peer-checked:text-emerald-900 text-sm">{{ __('trip_planner.mode_public_bus') }}</div>
                                </div>
                                <div class="text-xs text-slate-500 peer-checked:text-emerald-700 mt-1">{{ __('trip_planner.mode_public_bus_desc') }}</div>
                            </div>
                        </label>

                        <label class="cursor-pointer relative">
                            <input
                                type="radio"
                                name="travel_mode"
                                value="private_bus"
                                class="peer sr-only"
                                @checked(old('travel_mode') === 'private_bus')
                            >
                            <div class="rounded-2xl border-2 border-slate-200 p-3.5 transition hover:border-slate-300 peer-checked:border-emerald-600 peer-checked:bg-emerald-50/70 peer-checked:text-emerald-900 h-full flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <div class="text-xl">🚍</div>
                                        <span class="rounded-full bg-purple-100 text-purple-800 text-[10px] font-bold px-2 py-0.5">
                                            {{ __('trip_planner.mode_private_bus_badge') }}
                                        </span>
                                    </div>
                                    <div class="mt-1 font-bold text-slate-900 peer-checked:text-emerald-900 text-sm">{{ __('trip_planner.mode_private_bus') }}</div>
                                </div>
                                <div class="text-xs text-slate-500 peer-checked:text-emerald-700 mt-1">{{ __('trip_planner.mode_private_bus_desc') }}</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- 6. Interests --}}
                <div>
                    <label class="block text-sm font-bold text-slate-800">
                        <i class="bi bi-stars me-1.5 text-emerald-600"></i> {{ __('trip_planner.interests_label') }}
                    </label>
                    <p class="mt-0.5 text-xs text-slate-500">{{ __('trip_planner.interests_hint') }}</p>

                    <div class="mt-2.5 grid grid-cols-2 gap-2.5 sm:grid-cols-3" id="interestGrid">
                        @foreach($interests as $interest)
                            @php
                                $defaultChecked = in_array($interest->id, old('interest_ids', [1, 2, 3]));
                            @endphp
                            <label
                                class="interest-option cursor-pointer flex items-center gap-2 rounded-xl border p-3 text-sm font-semibold transition
                                    {{ $defaultChecked
                                        ? 'border-emerald-600 bg-emerald-50 text-emerald-800'
                                        : 'border-slate-200 bg-slate-50/60 text-slate-700 hover:bg-slate-100' }}"
                                data-interest-id="{{ $interest->id }}"
                            >
                                <input
                                    type="checkbox"
                                    name="interest_ids[]"
                                    value="{{ $interest->id }}"
                                    class="interest-checkbox sr-only"
                                    @checked($defaultChecked)
                                >
                                <span class="interest-checkmark flex h-5 w-5 shrink-0 items-center justify-center rounded-md border
                                    {{ $defaultChecked
                                        ? 'border-emerald-600 bg-emerald-600'
                                        : 'border-slate-300 bg-white' }}">
                                    <i class="bi bi-check text-white text-xs {{ $defaultChecked ? '' : 'opacity-0' }}"></i>
                                </span>
                                <span>{{ $interest->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- 7. Dining & Accommodation Stops --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        <i class="bi bi-cup-hot me-1 text-amber-500"></i> {{ __('trip_planner.extensible_services') }}
                    </span>
                    <h3 class="text-sm font-bold text-slate-800 mt-1">{{ __('trip_planner.meal_lodging_heading') }}</h3>

                    <div class="mt-3 space-y-3">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                name="include_meal_stop"
                                value="1"
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                @checked(old('include_meal_stop', '1') == '1')
                            >
                            <div>
                                <span class="text-sm font-bold text-slate-800">🍽️ {{ __('trip_planner.include_meal') }}</span>
                                <p class="text-xs text-slate-500">{{ __('trip_planner.include_meal_desc') }}</p>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                name="include_accommodation"
                                value="1"
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                @checked(old('include_accommodation', '1') == '1')
                            >
                            <div>
                                <span class="text-sm font-bold text-slate-800">🏨 {{ __('trip_planner.include_stay') }}</span>
                                <p class="text-xs text-slate-500">{{ __('trip_planner.include_stay_desc') }}</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Submit CTA --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        id="submitPlannerBtn"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-6 py-4 text-base font-bold text-white shadow-lg shadow-emerald-600/25 transition duration-150 hover:bg-emerald-700 hover:shadow-emerald-600/35 active:scale-[0.99]"
                    >
                        <i class="bi bi-compass-fill"></i>
                        <span id="submitBtnText">{{ __('trip_planner.btn_generate') }}</span>
                    </button>
                    <p class="mt-2 text-center text-xs text-slate-400">
                        ⚡ {{ __('trip_planner.rule_based_footer_note') }}
                    </p>
                </div>

            </div>
        </form>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Preset Buttons Click Handler
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const name = this.getAttribute('data-name');
            const lat = this.getAttribute('data-lat');
            const lng = this.getAttribute('data-lng');

            document.getElementById('start_location_name').value = name;
            document.getElementById('start_latitude').value = lat;
            document.getElementById('start_longitude').value = lng;
        });
    });

    // 2. Geolocation Button Handler
    const useLocationBtn = document.getElementById('useLocation');
    if (useLocationBtn) {
        useLocationBtn.addEventListener('click', function () {
            const btn = this;
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                return;
            }

            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> {{ __("trip_planner.locating") }}';

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    document.getElementById('start_latitude').value = position.coords.latitude;
                    document.getElementById('start_longitude').value = position.coords.longitude;
                    document.getElementById('start_location_name').value = 'GPS: ' + position.coords.latitude.toFixed(4) + ', ' + position.coords.longitude.toFixed(4);
                    btn.innerHTML = '<i class="bi bi-check-circle-fill text-emerald-600"></i> {{ __("trip_planner.location_set") }}';
                },
                function (err) {
                    btn.innerHTML = '<i class="bi bi-crosshair"></i> {{ __("trip_planner.use_my_gps") }}';
                    alert('Unable to retrieve location: ' + err.message + '. Default hub selected.');
                }
            );
        });
    }

    // 3. Trip Duration Toggle (1-day vs Multi-day)
    const durationRadios = document.querySelectorAll('.trip-days-radio');
    const availableTimeSection = document.getElementById('availableTimeSection');
    const submitBtnText = document.getElementById('submitBtnText');

    function updateDurationUI() {
        const selectedRadio = document.querySelector('.trip-days-radio:checked');
        const days = selectedRadio ? parseInt(selectedRadio.value) : 1;

        if (days > 1) {
            if (availableTimeSection) availableTimeSection.classList.add('hidden');
            if (submitBtnText) submitBtnText.textContent = '{{ __("trip_planner.btn_generate_multiday") }} (' + days + ' {{ app()->getLocale() === "si" ? "දින" : (app()->getLocale() === "ta" ? "நாட்கள்" : "Days") }})';
        } else {
            if (availableTimeSection) availableTimeSection.classList.remove('hidden');
            if (submitBtnText) submitBtnText.textContent = '{{ __("trip_planner.btn_generate_1day") }}';
        }
    }

    durationRadios.forEach(r => r.addEventListener('change', updateDurationUI));
    updateDurationUI();

    // 4. Interest Checkbox JS toggle
    document.querySelectorAll('.interest-option').forEach(label => {
        label.addEventListener('click', function (e) {
            const checkbox = this.querySelector('.interest-checkbox');
            const checkmark = this.querySelector('.interest-checkmark');
            const icon = checkmark ? checkmark.querySelector('i') : null;

            requestAnimationFrame(() => {
                const isChecked = checkbox && checkbox.checked;

                if (isChecked) {
                    this.classList.remove('border-slate-200', 'bg-slate-50/60', 'text-slate-700', 'hover:bg-slate-100');
                    this.classList.add('border-emerald-600', 'bg-emerald-50', 'text-emerald-800');
                    if (checkmark) {
                        checkmark.classList.remove('border-slate-300', 'bg-white');
                        checkmark.classList.add('border-emerald-600', 'bg-emerald-600');
                    }
                    if (icon) icon.classList.remove('opacity-0');
                } else {
                    this.classList.add('border-slate-200', 'bg-slate-50/60', 'text-slate-700', 'hover:bg-slate-100');
                    this.classList.remove('border-emerald-600', 'bg-emerald-50', 'text-emerald-800');
                    if (checkmark) {
                        checkmark.classList.add('border-slate-300', 'bg-white');
                        checkmark.classList.remove('border-emerald-600', 'bg-emerald-600');
                    }
                    if (icon) icon.classList.add('opacity-0');
                }
            });
        });
    });
});
</script>

@endsection