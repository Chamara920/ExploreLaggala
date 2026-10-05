@extends('layouts.public')

@section('title', __('public_transport.page_title'))

@section('content')

{{-- ========= HERO ========= --}}
<section class="relative overflow-hidden" style="background:linear-gradient(135deg,#0f172a 0%,#1e3a8a 50%,#2563eb 100%);">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:16px_16px]"></div>
    <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        <div class="max-w-3xl">
            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-sky-400/30 bg-sky-400/10 px-3 py-1 text-xs font-semibold text-sky-200 backdrop-blur-sm">
                <i class="bi bi-bus-front"></i> {{ __('public_transport.badge_plan') }}
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-5xl">
                {{ __('public_transport.hero_title') }}
            </h1>
            <p class="mt-4 max-w-2xl text-base text-blue-100 sm:text-lg leading-relaxed">
                {{ __('public_transport.hero_subtitle') }}
            </p>
        </div>

        {{-- Language switcher --}}
        <div class="mt-6 flex flex-wrap items-center gap-2">
            <span class="text-xs text-blue-200 font-medium me-1"><i class="bi bi-translate me-1"></i> Language:</span>
            @foreach(['si' => 'සිංහල', 'en' => 'English', 'ta' => 'தமிழ்'] as $code => $label)
            <a href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}"
               class="rounded-full border px-3.5 py-1 text-xs font-semibold transition shadow-sm
                      {{ ($locale ?? 'si') === $code
                         ? 'border-sky-300 bg-sky-500 text-white shadow-sky-500/20'
                         : 'border-white/20 bg-white/10 text-white/80 hover:bg-white/20 hover:text-white' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ========= FLASH MESSAGES ========= --}}
@if(session('success'))
<div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
    <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800 shadow-sm">
        <i class="bi bi-check-circle-fill text-xl text-emerald-600"></i>
        <span class="text-sm font-semibold">{{ session('success') }}</span>
    </div>
</div>
@endif

@if($errors->any())
<div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 shadow-sm">
        <div class="flex items-center gap-2 mb-2 text-rose-700">
            <i class="bi bi-exclamation-triangle-fill text-lg"></i>
            <strong class="text-sm">Please resolve the following:</strong>
        </div>
        <ul class="list-disc list-inside text-sm text-rose-600 space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    {{-- ========= PRE-COLLECTED TRIP PLANNING INSIGHTS CARD ========= --}}
    <div class="mb-10 overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-50/80 via-white to-sky-50/80 p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-blue-100">
            <div>
                <div class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-700 bg-blue-100 px-3 py-1 rounded-full mb-2">
                    <i class="bi bi-lightbulb-fill text-amber-500"></i> {{ __('public_transport.planning_tips_title') }}
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                    {{ __('public_transport.planning_tips_subtitle') }}
                </h2>
            </div>
            <a href="{{ route('plan.smart-planner.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-bold shadow-md shadow-blue-500/20 transition active:scale-95 whitespace-nowrap">
                <i class="bi bi-compass-fill"></i>
                <span>Smart Trip Planner</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {{-- Tip 1: Riverston Pass --}}
            <div class="rounded-2xl border border-blue-100/80 bg-white p-4 shadow-sm hover:shadow-md transition">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-600 text-lg">
                        <i class="bi bi-cloud-haze2"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Riverston Scenic Pass</h4>
                        <p class="mt-1 text-xs text-slate-600 leading-relaxed">
                            {{ __('public_transport.tip_mountain_pass') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tip 2: Last Bus Alert --}}
            <div class="rounded-2xl border border-amber-200/80 bg-amber-50/50 p-4 shadow-sm hover:shadow-md transition">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700 text-lg">
                        <i class="bi bi-alarm"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-amber-900">Last Bus Warning</h4>
                        <p class="mt-1 text-xs text-amber-800 leading-relaxed font-medium">
                            {{ __('public_transport.tip_last_bus') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tip 3: Meemure Village Connection --}}
            <div class="rounded-2xl border border-emerald-100/80 bg-white p-4 shadow-sm hover:shadow-md transition sm:col-span-2 lg:col-span-1">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 text-lg">
                        <i class="bi bi-signpost-2"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Meemure & Lakegala 4WD</h4>
                        <p class="mt-1 text-xs text-slate-600 leading-relaxed">
                            {{ __('public_transport.tip_meemure_connect') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Depot Contact Strip --}}
        <div class="mt-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-white/90 border border-slate-200/80 px-4 py-3 text-xs text-slate-700">
            <div class="flex items-center gap-2 font-bold text-slate-800">
                <i class="bi bi-telephone-outbound text-blue-600"></i>
                <span>{{ __('public_transport.depot_contacts_title') }}:</span>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <a href="tel:0662222281" class="hover:text-blue-600 transition font-medium">
                    <i class="bi bi-telephone me-1 text-slate-400"></i> {{ __('public_transport.depot_matale') }}
                </a>
                <span class="text-slate-300">|</span>
                <a href="tel:0662275200" class="hover:text-blue-600 transition font-medium">
                    <i class="bi bi-telephone me-1 text-slate-400"></i> {{ __('public_transport.depot_pallegama') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Main Layout Grid --}}
    <div class="lg:grid lg:grid-cols-3 lg:gap-10">

        {{-- ========= TRANSPORT LISTINGS (COL 1 & 2) ========= --}}
        <div class="lg:col-span-2">

            {{-- Filter Tabs and Search Bar --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-3 rounded-2xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-xl" id="statusFilterTabs">
                    <button type="button" data-filter="all"
                            class="filter-tab active rounded-lg px-3 py-1.5 text-xs font-bold transition shadow-sm bg-white text-slate-800">
                        {{ __('public_transport.filter_all') }}
                        <span class="ms-1 rounded-full bg-slate-200 px-1.5 py-0.2 text-[10px] text-slate-700">{{ $totalCount ?? 0 }}</span>
                    </button>
                    <button type="button" data-filter="active"
                            class="filter-tab rounded-lg px-3 py-1.5 text-xs font-bold transition text-slate-600 hover:text-slate-900">
                        {{ __('public_transport.filter_active') }}
                        <span class="ms-1 rounded-full bg-emerald-100 text-emerald-800 px-1.5 py-0.2 text-[10px]">{{ $activeCount ?? 0 }}</span>
                    </button>
                    <button type="button" data-filter="suspended"
                            class="filter-tab rounded-lg px-3 py-1.5 text-xs font-bold transition text-slate-600 hover:text-slate-900">
                        {{ __('public_transport.filter_suspended') }}
                        @if(($suspendedCount ?? 0) > 0)
                        <span class="ms-1 rounded-full bg-rose-500 text-white px-1.5 py-0.2 text-[10px] font-bold animate-pulse">{{ $suspendedCount }}</span>
                        @else
                        <span class="ms-1 rounded-full bg-slate-200 text-slate-600 px-1.5 py-0.2 text-[10px]">0</span>
                        @endif
                    </button>
                </div>

                {{-- Live Search Box --}}
                <div class="relative flex-1 sm:max-w-xs">
                    <i class="bi bi-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    <input type="text" id="transportSearch" placeholder="{{ __('public_transport.search_placeholder') }}"
                           class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-2 pl-8 pr-3 text-xs focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                </div>
            </div>

            @if($routes->isEmpty())
                <div class="flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-white py-20 text-center">
                    <i class="bi bi-bus-front-fill text-5xl text-slate-300 mb-4"></i>
                    <h3 class="text-lg font-bold text-slate-800">{{ __('public_transport.no_routes_title') }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ __('public_transport.no_routes_subtitle') }}</p>
                </div>
            @else
                @php
                $typeConfig = [
                    'bus'          => ['label'=>__('public_transport.type_bus'),          'icon'=>'bi-bus-front',   'bg'=>'bg-blue-50 text-blue-600',   'badge'=>'bg-blue-100 text-blue-700'],
                    'train'        => ['label'=>__('public_transport.type_train'),        'icon'=>'bi-train-front', 'bg'=>'bg-indigo-50 text-indigo-600', 'badge'=>'bg-indigo-100 text-indigo-700'],
                    'taxi'         => ['label'=>__('public_transport.type_taxi'),         'icon'=>'bi-taxi-front',  'bg'=>'bg-amber-50 text-amber-600',  'badge'=>'bg-amber-100 text-amber-700'],
                    'tuk_tuk'      => ['label'=>__('public_transport.type_tuk_tuk'),      'icon'=>'bi-scooter',     'bg'=>'bg-orange-50 text-orange-600', 'badge'=>'bg-orange-100 text-orange-700'],
                    'private_hire' => ['label'=>__('public_transport.type_private_hire'), 'icon'=>'bi-car-front',   'bg'=>'bg-violet-50 text-violet-600', 'badge'=>'bg-violet-100 text-violet-700'],
                    'other'        => ['label'=>__('public_transport.type_other'),        'icon'=>'bi-signpost-2',  'bg'=>'bg-slate-50 text-slate-600',   'badge'=>'bg-slate-100 text-slate-700'],
                ];
                @endphp

                @foreach($routes as $type => $typeRoutes)
                @php $cfg = $typeConfig[$type] ?? $typeConfig['other']; @endphp
                <div class="mb-8 transport-section" data-type="{{ $type }}">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-2xl {{ $cfg['bg'] }} shadow-sm">
                                <i class="bi {{ $cfg['icon'] }} text-lg"></i>
                            </span>
                            <div>
                                <h3 class="text-xl font-black text-slate-900">{{ $cfg['label'] }}</h3>
                                <p class="text-xs text-slate-500">Regular services connecting to Laggala</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">{{ $typeRoutes->count() }}</span>
                    </div>

                    <div class="space-y-4">
                        @foreach($typeRoutes as $route)
                        @php
                            $t = $route->translationFor($locale);
                            $routeName = $t?->route_name ?? $route->route_name;
                            $fromLoc = $t?->from_location ?? $route->from_location;
                            $toLoc = $t?->to_location ?? $route->to_location;
                            $keyStops = $t?->key_stops ?? $route->key_stops;
                            $notes = $t?->notes ?? $route->notes;
                            $fareNote = $t?->fare_note ?? $route->fare_note;
                            $operator = $t?->operator_name ?? $route->operator_name;
                            $suspensionReason = $t?->suspension_reason ?? $route->suspension_reason;
                        @endphp

                        <div class="route-card overflow-hidden rounded-3xl border transition duration-200
                                    {{ $route->is_suspended
                                       ? 'border-rose-200 bg-gradient-to-br from-rose-50/40 via-white to-rose-50/20 shadow-sm'
                                       : 'border-slate-200/90 bg-white hover:border-blue-300 hover:shadow-md' }}"
                             data-status="{{ $route->is_suspended ? 'suspended' : 'active' }}"
                             data-search="{{ strtolower($routeName . ' ' . $fromLoc . ' ' . $toLoc . ' ' . $keyStops . ' ' . $route->route_number) }}">

                            {{-- Route Card Header --}}
                            <div class="p-5 sm:p-6">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                    <div class="flex-1 min-w-0">

                                        {{-- Badges Strip --}}
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            @if($route->route_number)
                                            <span class="rounded-xl bg-blue-600 px-2.5 py-1 text-xs font-black tracking-wide text-white shadow-sm">
                                                Route {{ $route->route_number }}
                                            </span>
                                            @endif

                                            @if($route->bus_category)
                                            <span class="rounded-xl bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                                {{ $route->bus_category_label }}
                                            </span>
                                            @endif

                                            {{-- Operational vs Suspended Status Badge --}}
                                            @if($route->is_suspended)
                                            <span class="inline-flex items-center gap-1.5 rounded-xl bg-rose-100 border border-rose-200 px-3 py-1 text-xs font-bold text-rose-700">
                                                <i class="bi bi-slash-circle-fill text-rose-600 animate-pulse"></i>
                                                {{ __('public_transport.status_suspended') }}
                                            </span>
                                            @else
                                            <span class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-800">
                                                <i class="bi bi-check-circle-fill text-emerald-600"></i>
                                                {{ __('public_transport.status_operational') }}
                                            </span>
                                            @endif

                                            @if($route->community_submitted)
                                            <span class="inline-flex items-center gap-1 rounded-xl bg-sky-50 px-2 py-0.5 text-[10px] font-semibold text-sky-700 border border-sky-100">
                                                <i class="bi bi-people-fill"></i> {{ __('public_transport.community_verified') }}
                                            </span>
                                            @endif
                                        </div>

                                        {{-- Route Name Title --}}
                                        <h4 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                                            {{ $routeName }}
                                        </h4>

                                        {{-- Origin to Destination --}}
                                        <div class="mt-2 flex items-center gap-2 text-sm text-slate-600 font-medium">
                                            <i class="bi bi-geo-alt-fill text-emerald-600"></i>
                                            <span class="text-slate-800">{{ $fromLoc }}</span>
                                            <i class="bi bi-arrow-right text-slate-400"></i>
                                            <span class="text-slate-800">{{ $toLoc }}</span>
                                        </div>

                                        {{-- Key Stops list --}}
                                        @if($keyStops)
                                        <div class="mt-2.5 flex items-start gap-1.5 text-xs text-slate-500">
                                            <i class="bi bi-signpost-split text-blue-500 mt-0.5"></i>
                                            <span class="font-semibold text-slate-700">{{ __('public_transport.key_stops_label') }}:</span>
                                            <span class="text-slate-600">{{ $keyStops }}</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Right Column: Fare & Departure Info --}}
                                    <div class="flex flex-shrink-0 flex-col sm:items-end justify-between gap-2 sm:text-right pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                        @if($route->fare)
                                        <div>
                                            <span class="text-lg font-black text-emerald-700">LKR {{ number_format($route->fare, 2) }}</span>
                                            @if($fareNote)
                                            <span class="block text-[11px] text-slate-400 font-medium">{{ $fareNote }}</span>
                                            @endif
                                        </div>
                                        @endif

                                        @if($route->frequency)
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-blue-50 text-blue-700 px-2 py-1 text-xs font-semibold">
                                            <i class="bi bi-arrow-repeat"></i> {{ $route->frequency }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Departure / Timetable details --}}
                                @if($route->departure_time || $route->arrival_time)
                                <div class="mt-4 rounded-2xl bg-slate-50/80 border border-slate-100 p-3 flex flex-wrap items-center justify-between gap-3 text-xs">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-clock-fill text-blue-600"></i>
                                        <span class="font-bold text-slate-700">{{ __('public_transport.operating_hours') }}:</span>
                                        <span class="font-semibold text-slate-900">{{ $route->departure_time }}</span>
                                        @if($route->arrival_time)
                                        <span class="text-slate-400">({{ $route->arrival_time }})</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('plan.smart-planner.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition inline-flex items-center gap-1">
                                        <span>Plan with this route</span>
                                        <i class="bi bi-chevron-right text-[10px]"></i>
                                    </a>
                                </div>
                                @endif

                                {{-- TEMPORARY SUSPENSION NOTICE BOX --}}
                                @if($route->is_suspended)
                                <div class="mt-4 rounded-2xl border border-rose-300 bg-rose-50/90 p-4 shadow-sm">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-xl bg-rose-600 text-white text-sm">
                                            <i class="bi bi-exclamation-octagon-fill"></i>
                                        </div>
                                        <div class="flex-1 text-xs">
                                            <h5 class="font-bold text-rose-900 text-sm">
                                                {{ __('public_transport.suspension_alert_title') }}
                                            </h5>
                                            @if($suspensionReason)
                                            <p class="mt-1 text-rose-800 font-medium">
                                                <strong>{{ __('public_transport.reason') }}</strong> {{ $suspensionReason }}
                                            </p>
                                            @endif
                                            @if($route->suspended_until)
                                            <p class="mt-1 text-rose-700">
                                                <strong>{{ __('public_transport.resumes_on') }}</strong> {{ $route->suspended_until }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif

                                {{-- Notes / Operator Strip --}}
                                @if($notes || $operator || $route->contact_number)
                                <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500">
                                    <div class="flex flex-wrap items-center gap-3">
                                        @if($operator)
                                        <span class="font-medium text-slate-700">
                                            <i class="bi bi-building me-1 text-slate-400"></i>{{ $operator }}
                                        </span>
                                        @endif
                                        @if($route->contact_number)
                                        <a href="tel:{{ preg_replace('/[^0-9]/', '', $route->contact_number) }}" class="text-blue-600 hover:underline font-semibold flex items-center gap-1">
                                            <i class="bi bi-telephone-fill text-blue-500"></i> {{ $route->contact_number }}
                                        </a>
                                        @endif
                                    </div>

                                    {{-- Community / Admin Action Buttons --}}
                                    @if($canManageTransport)
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                                class="btn-status-toggle inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition shadow-sm"
                                                data-id="{{ $route->id }}"
                                                data-name="{{ $routeName }}"
                                                data-suspended="{{ $route->is_suspended ? '1' : '0' }}"
                                                data-reason="{{ $route->suspension_reason }}"
                                                data-until="{{ $route->suspended_until }}">
                                            <i class="bi bi-toggles text-blue-600"></i>
                                            <span>{{ __('public_transport.btn_update_status') }}</span>
                                        </button>

                                        <button type="button"
                                                class="btn-edit-route inline-flex items-center gap-1 rounded-xl bg-blue-50 border border-blue-200 px-3 py-1 text-xs font-bold text-blue-700 hover:bg-blue-100 transition shadow-sm"
                                                data-id="{{ $route->id }}"
                                                data-name="{{ $route->route_name }}"
                                                data-number="{{ $route->route_number }}"
                                                data-type="{{ $route->transport_type }}"
                                                data-category="{{ $route->bus_category }}"
                                                data-from="{{ $route->from_location }}"
                                                data-to="{{ $route->to_location }}"
                                                data-stops="{{ $route->key_stops }}"
                                                data-departure="{{ $route->departure_time }}"
                                                data-arrival="{{ $route->arrival_time }}"
                                                data-frequency="{{ $route->frequency }}"
                                                data-fare="{{ $route->fare }}"
                                                data-farenote="{{ $route->fare_note }}"
                                                data-operator="{{ $route->operator_name }}"
                                                data-contact="{{ $route->contact_number }}"
                                                data-notes="{{ $route->notes }}"
                                                data-suspended="{{ $route->is_suspended ? '1' : '0' }}"
                                                data-reason="{{ $route->suspension_reason }}"
                                                data-until="{{ $route->suspended_until }}">
                                            <i class="bi bi-pencil-square"></i>
                                            <span>{{ __('public_transport.btn_edit') }}</span>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                @if($notes)
                                <div class="mt-2 text-xs italic text-slate-500">
                                    <i class="bi bi-info-circle me-1 text-slate-400"></i> {{ $notes }}
                                </div>
                                @endif

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        {{-- ========= SIDEBAR: SUBMISSION FORM OR GUEST CALL TO ACTION ========= --}}
        <aside class="mt-10 lg:mt-0">
            @if($canManageTransport)
            {{-- Form for Authenticated Community Users / Admins --}}
            <div class="sticky top-24 rounded-3xl border border-blue-200/80 bg-gradient-to-br from-blue-50/60 via-white to-sky-50/40 p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-md shadow-blue-500/20">
                        <i class="bi bi-plus-lg text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900">{{ __('public_transport.add_transport_title') }}</h3>
                        <p class="text-xs text-slate-500">{{ __('public_transport.add_transport_subtitle') }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('plan.transport.store') }}" class="space-y-3.5">
                    @csrf

                    <div>
                        <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.route_name') }}</label>
                        <input type="text" name="route_name" value="{{ old('route_name') }}" required
                               placeholder="{{ __('public_transport.route_name_placeholder') }}"
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.route_number') }}</label>
                            <input type="text" name="route_number" value="{{ old('route_number') }}"
                                   placeholder="{{ __('public_transport.route_number_placeholder') }}"
                                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.transport_type') }}</label>
                            <select name="transport_type" required
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                                <option value="bus" {{ old('transport_type')=='bus'?'selected':'' }}>{{ __('public_transport.type_bus') }}</option>
                                <option value="train" {{ old('transport_type')=='train'?'selected':'' }}>{{ __('public_transport.type_train') }}</option>
                                <option value="taxi" {{ old('transport_type')=='taxi'?'selected':'' }}>{{ __('public_transport.type_taxi') }}</option>
                                <option value="tuk_tuk" {{ old('transport_type')=='tuk_tuk'?'selected':'' }}>{{ __('public_transport.type_tuk_tuk') }}</option>
                                <option value="private_hire" {{ old('transport_type')=='private_hire'?'selected':'' }}>{{ __('public_transport.type_private_hire') }}</option>
                                <option value="other" {{ old('transport_type')=='other'?'selected':'' }}>{{ __('public_transport.type_other') }}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.bus_category') }}</label>
                        <select name="bus_category"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                            <option value="sltb">{{ __('public_transport.cat_sltb') }}</option>
                            <option value="private">{{ __('public_transport.cat_private') }}</option>
                            <option value="village_shuttle">{{ __('public_transport.cat_village_shuttle') }}</option>
                            <option value="general">{{ __('public_transport.cat_general') }}</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.from_location') }}</label>
                            <input type="text" name="from_location" value="{{ old('from_location') }}" required
                                   placeholder="From"
                                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.to_location') }}</label>
                            <input type="text" name="to_location" value="{{ old('to_location') }}" required
                                   placeholder="To"
                                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.key_stops') }}</label>
                        <input type="text" name="key_stops" value="{{ old('key_stops') }}"
                               placeholder="{{ __('public_transport.key_stops_placeholder') }}"
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.departure_time') }}</label>
                            <input type="text" name="departure_time" value="{{ old('departure_time') }}"
                                   placeholder="{{ __('public_transport.departure_placeholder') }}"
                                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.arrival_time') }}</label>
                            <input type="text" name="arrival_time" value="{{ old('arrival_time') }}"
                                   placeholder="{{ __('public_transport.arrival_placeholder') }}"
                                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.frequency') }}</label>
                            <input type="text" name="frequency" value="{{ old('frequency') }}"
                                   placeholder="{{ __('public_transport.frequency_placeholder') }}"
                                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.fare') }}</label>
                            <input type="number" name="fare" value="{{ old('fare') }}" min="0" step="0.01"
                                   placeholder="0.00"
                                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.operator_name') }}</label>
                            <input type="text" name="operator_name" value="{{ old('operator_name') }}"
                                   placeholder="{{ __('public_transport.operator_placeholder') }}"
                                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.contact_number') }}</label>
                            <input type="text" name="contact_number" value="{{ old('contact_number') }}"
                                   placeholder="{{ __('public_transport.contact_placeholder') }}"
                                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                    </div>

                    {{-- Temporary Suspension Checkbox & Fields --}}
                    <div class="rounded-2xl border border-rose-200 bg-rose-50/60 p-3.5 space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-rose-900">
                            <input type="checkbox" name="is_suspended" value="1" id="formIsSuspended"
                                   class="rounded border-rose-300 text-rose-600 focus:ring-rose-500">
                            <span>{{ __('public_transport.is_suspended_checkbox') }}</span>
                        </label>

                        <div id="suspensionFields" class="hidden space-y-2 pt-2 border-t border-rose-200">
                            <div>
                                <label class="block text-[11px] font-bold text-rose-800">{{ __('public_transport.suspension_reason') }}</label>
                                <input type="text" name="suspension_reason"
                                       placeholder="{{ __('public_transport.suspension_placeholder') }}"
                                       class="w-full rounded-lg border border-rose-200 bg-white px-2.5 py-1.5 text-xs text-rose-900 focus:outline-none focus:ring-1 focus:ring-rose-400">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-rose-800">{{ __('public_transport.suspended_until') }}</label>
                                <input type="text" name="suspended_until"
                                       placeholder="{{ __('public_transport.suspended_until_placeholder') }}"
                                       class="w-full rounded-lg border border-rose-200 bg-white px-2.5 py-1.5 text-xs text-rose-900 focus:outline-none focus:ring-1 focus:ring-rose-400">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.notes') }}</label>
                        <textarea name="notes" rows="2" placeholder="{{ __('public_transport.notes_placeholder') }}"
                                  class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full rounded-2xl bg-blue-600 py-3 text-xs font-black text-white shadow-md shadow-blue-500/20 transition hover:bg-blue-700 active:scale-95">
                        <i class="bi bi-send-fill me-1"></i> {{ __('public_transport.submit_button') }}
                    </button>
                </form>
            </div>

            @else

            {{-- GUEST CALL TO ACTION CARD (COMMUNITY CONTRIBUTOR) --}}
            <div class="sticky top-24 overflow-hidden rounded-3xl border border-blue-200 bg-gradient-to-br from-blue-600 via-indigo-700 to-blue-900 p-6 text-white shadow-xl">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20 text-white text-2xl backdrop-blur-md mb-4 shadow-sm">
                    <i class="bi bi-people-fill"></i>
                </div>
                
                <h3 class="text-lg font-black tracking-tight leading-snug">
                    {{ __('public_transport.guest_cta_title') }}
                </h3>
                
                <p class="mt-2 text-xs text-blue-100 leading-relaxed font-normal">
                    {{ __('public_transport.guest_cta_desc') }}
                </p>

                <div class="my-5 space-y-2.5 border-t border-b border-white/10 py-4 text-xs text-blue-100 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check2-circle text-emerald-400 text-sm"></i>
                        <span>{{ __('public_transport.guest_benefit_1') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check2-circle text-emerald-400 text-sm"></i>
                        <span>{{ __('public_transport.guest_benefit_2') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check2-circle text-emerald-400 text-sm"></i>
                        <span>{{ __('public_transport.guest_benefit_3') }}</span>
                    </div>
                </div>

                <div class="flex flex-col gap-2.5">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white py-3 text-xs font-black text-blue-900 shadow-md transition hover:bg-blue-50 active:scale-95">
                        <i class="bi bi-box-arrow-in-right"></i> {{ __('public_transport.btn_signin') }}
                    </a>

                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/30 bg-white/10 py-3 text-xs font-black text-white backdrop-blur-md transition hover:bg-white/20 active:scale-95">
                        <i class="bi bi-person-plus-fill"></i> {{ __('public_transport.btn_register') }}
                    </a>
                </div>
            </div>
            @endif
        </aside>

    </div>
</div>

{{-- ========================================================================= --}}
{{-- MODAL 1: QUICK STATUS & SUSPENSION TOGGLE (FOR ADMINS / COMMUNITY USERS) --}}
{{-- ========================================================================= --}}
@if($canManageTransport)
<div id="statusModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm p-4 flex items-center justify-center">
    <div class="relative w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h4 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="bi bi-toggles text-blue-600"></i>
                <span>{{ __('public_transport.btn_update_status') }}</span>
            </h4>
            <button type="button" class="close-modal text-slate-400 hover:text-slate-600 text-lg">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="statusForm" method="POST" action="" class="mt-4 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <p id="statusRouteName" class="text-xs font-bold text-slate-700 bg-slate-100 p-2.5 rounded-xl"></p>
            </div>

            <div class="rounded-2xl border border-rose-200 bg-rose-50/70 p-4 space-y-3">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_suspended" value="1" id="modalIsSuspended"
                           class="h-4 w-4 rounded border-rose-300 text-rose-600 focus:ring-rose-500">
                    <span class="text-xs font-bold text-rose-900">{{ __('public_transport.is_suspended_checkbox') }}</span>
                </label>

                <div id="modalSuspensionFields" class="space-y-3 pt-2 border-t border-rose-200">
                    <div>
                        <label class="block text-xs font-bold text-rose-900 mb-1">{{ __('public_transport.suspension_reason') }}</label>
                        <input type="text" name="suspension_reason" id="modalSuspensionReason"
                               placeholder="{{ __('public_transport.suspension_placeholder') }}"
                               class="w-full rounded-xl border border-rose-200 bg-white px-3 py-2 text-xs text-rose-900 focus:outline-none focus:ring-2 focus:ring-rose-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-rose-900 mb-1">{{ __('public_transport.suspended_until') }}</label>
                        <input type="text" name="suspended_until" id="modalSuspendedUntil"
                               placeholder="{{ __('public_transport.suspended_until_placeholder') }}"
                               class="w-full rounded-xl border border-rose-200 bg-white px-3 py-2 text-xs text-rose-900 focus:outline-none focus:ring-2 focus:ring-rose-300">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" class="close-modal rounded-xl px-4 py-2 text-xs font-bold text-slate-500 hover:bg-slate-100 transition">
                    {{ __('public_transport.cancel_button') }}
                </button>
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2 text-xs font-black text-white hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                    {{ __('public_transport.update_button') }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- MODAL 2: EDIT FULL TRANSPORT ROUTE (FOR ADMINS / COMMUNITY USERS)        --}}
{{-- ========================================================================= --}}
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm p-4 flex items-center justify-center">
    <div class="relative w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl border border-slate-100 my-8">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h4 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="bi bi-pencil-square text-blue-600"></i>
                <span>{{ __('public_transport.edit_transport_title') }}</span>
            </h4>
            <button type="button" class="close-modal text-slate-400 hover:text-slate-600 text-lg">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="editForm" method="POST" action="" class="mt-4 space-y-3.5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.route_name') }}</label>
                    <input type="text" name="route_name" id="editRouteName" required
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.route_number') }}</label>
                    <input type="text" name="route_number" id="editRouteNumber"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.transport_type') }}</label>
                    <select name="transport_type" id="editTransportType" required
                            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="bus">{{ __('public_transport.type_bus') }}</option>
                        <option value="train">{{ __('public_transport.type_train') }}</option>
                        <option value="taxi">{{ __('public_transport.type_taxi') }}</option>
                        <option value="tuk_tuk">{{ __('public_transport.type_tuk_tuk') }}</option>
                        <option value="private_hire">{{ __('public_transport.type_private_hire') }}</option>
                        <option value="other">{{ __('public_transport.type_other') }}</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.bus_category') }}</label>
                    <select name="bus_category" id="editBusCategory"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="sltb">{{ __('public_transport.cat_sltb') }}</option>
                        <option value="private">{{ __('public_transport.cat_private') }}</option>
                        <option value="village_shuttle">{{ __('public_transport.cat_village_shuttle') }}</option>
                        <option value="general">{{ __('public_transport.cat_general') }}</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.from_location') }}</label>
                    <input type="text" name="from_location" id="editFromLocation" required
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.to_location') }}</label>
                    <input type="text" name="to_location" id="editToLocation" required
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            <div>
                <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.key_stops') }}</label>
                <input type="text" name="key_stops" id="editKeyStops"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.departure_time') }}</label>
                    <input type="text" name="departure_time" id="editDepartureTime"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.arrival_time') }}</label>
                    <input type="text" name="arrival_time" id="editArrivalTime"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.frequency') }}</label>
                    <input type="text" name="frequency" id="editFrequency"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.fare') }}</label>
                    <input type="number" name="fare" id="editFare" min="0" step="0.01"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.fare_note') }}</label>
                    <input type="text" name="fare_note" id="editFareNote"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.operator_name') }}</label>
                    <input type="text" name="operator_name" id="editOperatorName"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.contact_number') }}</label>
                    <input type="text" name="contact_number" id="editContactNumber"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            {{-- Suspension block inside edit modal --}}
            <div class="rounded-2xl border border-rose-200 bg-rose-50/70 p-3.5 space-y-2">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-rose-900">
                    <input type="checkbox" name="is_suspended" value="1" id="editIsSuspended"
                           class="rounded border-rose-300 text-rose-600 focus:ring-rose-500">
                    <span>{{ __('public_transport.is_suspended_checkbox') }}</span>
                </label>

                <div id="editSuspensionBlock" class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2 border-t border-rose-200">
                    <div>
                        <label class="block text-[11px] font-bold text-rose-800">{{ __('public_transport.suspension_reason') }}</label>
                        <input type="text" name="suspension_reason" id="editSuspensionReason"
                               class="w-full rounded-lg border border-rose-200 bg-white px-2.5 py-1.5 text-xs text-rose-900 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-rose-800">{{ __('public_transport.suspended_until') }}</label>
                        <input type="text" name="suspended_until" id="editSuspendedUntil"
                               class="w-full rounded-lg border border-rose-200 bg-white px-2.5 py-1.5 text-xs text-rose-900 focus:outline-none">
                    </div>
                </div>
            </div>

            <div>
                <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('public_transport.notes') }}</label>
                <textarea name="notes" id="editNotes" rows="2"
                          class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" class="close-modal rounded-xl px-4 py-2 text-xs font-bold text-slate-500 hover:bg-slate-100 transition">
                    {{ __('public_transport.cancel_button') }}
                </button>
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-xs font-black text-white hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                    {{ __('public_transport.update_button') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ========================================================================= --}}
{{-- CLIENT-SIDE JAVASCRIPT: FILTERING, SEARCH, SUSPENSION TOGGLES & MODALS   --}}
{{-- ========================================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Sidebar form suspension checkbox toggle
    const formIsSuspended = document.getElementById('formIsSuspended');
    const suspensionFields = document.getElementById('suspensionFields');
    if (formIsSuspended && suspensionFields) {
        formIsSuspended.addEventListener('change', function () {
            suspensionFields.classList.toggle('hidden', !this.checked);
        });
    }

    // 2. Filter Tabs (All / Active / Suspended)
    const tabButtons = document.querySelectorAll('#statusFilterTabs .filter-tab');
    const routeCards = document.querySelectorAll('.route-card');
    const searchInput = document.getElementById('transportSearch');

    function applyFilters() {
        const activeTab = document.querySelector('#statusFilterTabs .filter-tab.active');
        const filterVal = activeTab ? activeTab.getAttribute('data-filter') : 'all';
        const searchVal = searchInput ? searchInput.value.toLowerCase().trim() : '';

        routeCards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            const cardSearch = card.getAttribute('data-search') || '';

            const matchesStatus = (filterVal === 'all') || (filterVal === cardStatus);
            const matchesSearch = !searchVal || cardSearch.includes(searchVal);

            if (matchesStatus && matchesSearch) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });

        // Hide empty section headers if all cards in that category are hidden
        document.querySelectorAll('.transport-section').forEach(section => {
            const visibleCards = section.querySelectorAll('.route-card:not(.hidden)');
            section.classList.toggle('hidden', visibleCards.length === 0);
        });
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            tabButtons.forEach(b => {
                b.classList.remove('active', 'bg-white', 'text-slate-800', 'shadow-sm');
                b.classList.add('text-slate-600');
            });
            this.classList.add('active', 'bg-white', 'text-slate-800', 'shadow-sm');
            this.classList.remove('text-slate-600');
            applyFilters();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    // 3. Modal close handlers
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', function () {
            const m1 = document.getElementById('statusModal');
            const m2 = document.getElementById('editModal');
            if (m1) m1.classList.add('hidden');
            if (m2) m2.classList.add('hidden');
        });
    });

    // 4. Quick Status & Suspension Modal
    const statusModal = document.getElementById('statusModal');
    const statusForm = document.getElementById('statusForm');
    const statusRouteName = document.getElementById('statusRouteName');
    const modalIsSuspended = document.getElementById('modalIsSuspended');
    const modalSuspensionReason = document.getElementById('modalSuspensionReason');
    const modalSuspendedUntil = document.getElementById('modalSuspendedUntil');
    const modalSuspensionFields = document.getElementById('modalSuspensionFields');

    if (modalIsSuspended && modalSuspensionFields) {
        modalIsSuspended.addEventListener('change', function () {
            modalSuspensionFields.classList.toggle('hidden', !this.checked);
        });
    }

    document.querySelectorAll('.btn-status-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const isSuspended = this.getAttribute('data-suspended') === '1';
            const reason = this.getAttribute('data-reason') || '';
            const until = this.getAttribute('data-until') || '';

            statusRouteName.textContent = name;
            modalIsSuspended.checked = isSuspended;
            modalSuspensionReason.value = reason;
            modalSuspendedUntil.value = until;
            modalSuspensionFields.classList.toggle('hidden', !isSuspended);

            statusForm.action = `/plan/public-transport/${id}/status`;
            statusModal.classList.remove('hidden');
        });
    });

    // 5. Full Edit Route Modal
    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');
    const editIsSuspended = document.getElementById('editIsSuspended');
    const editSuspensionBlock = document.getElementById('editSuspensionBlock');

    if (editIsSuspended && editSuspensionBlock) {
        editIsSuspended.addEventListener('change', function () {
            editSuspensionBlock.classList.toggle('hidden', !this.checked);
        });
    }

    document.querySelectorAll('.btn-edit-route').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            document.getElementById('editRouteName').value = this.getAttribute('data-name') || '';
            document.getElementById('editRouteNumber').value = this.getAttribute('data-number') || '';
            document.getElementById('editTransportType').value = this.getAttribute('data-type') || 'bus';
            document.getElementById('editBusCategory').value = this.getAttribute('data-category') || 'sltb';
            document.getElementById('editFromLocation').value = this.getAttribute('data-from') || '';
            document.getElementById('editToLocation').value = this.getAttribute('data-to') || '';
            document.getElementById('editKeyStops').value = this.getAttribute('data-stops') || '';
            document.getElementById('editDepartureTime').value = this.getAttribute('data-departure') || '';
            document.getElementById('editArrivalTime').value = this.getAttribute('data-arrival') || '';
            document.getElementById('editFrequency').value = this.getAttribute('data-frequency') || '';
            document.getElementById('editFare').value = this.getAttribute('data-fare') || '';
            document.getElementById('editFareNote').value = this.getAttribute('data-farenote') || '';
            document.getElementById('editOperatorName').value = this.getAttribute('data-operator') || '';
            document.getElementById('editContactNumber').value = this.getAttribute('data-contact') || '';
            document.getElementById('editNotes').value = this.getAttribute('data-notes') || '';

            const isSuspended = this.getAttribute('data-suspended') === '1';
            editIsSuspended.checked = isSuspended;
            document.getElementById('editSuspensionReason').value = this.getAttribute('data-reason') || '';
            document.getElementById('editSuspendedUntil').value = this.getAttribute('data-until') || '';
            editSuspensionBlock.classList.toggle('hidden', !isSuspended);

            editForm.action = `/plan/public-transport/${id}`;
            editModal.classList.remove('hidden');
        });
    });
});
</script>

@endsection
