@extends('layouts.public')

@section('title', __('institutions.title') . ' — Explore Laggala')

@section('content')

{{-- Leaflet CSS if institutions with coordinates exist --}}
@if($mapInstitutions->isNotEmpty())
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endif

<div class="bg-slate-50/70 min-h-screen">

    {{-- ============================================================
         1. HERO HEADER SECTION
         ============================================================ --}}
    <section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-white py-16 lg:py-20 overflow-hidden">
        {{-- Ambient glows --}}
        <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-[500px] h-[500px] bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-5 shadow-inner">
                    <span>🏛️</span>
                    <span>{{ __('institutions.explore_badge') }}</span>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-tight">
                    {{ __('institutions.title') }}
                </h1>

                <p class="mt-4 text-base sm:text-lg text-slate-300 leading-relaxed font-light">
                    {{ __('institutions.subtitle') }}
                </p>

                {{-- Search & Type Filter Form --}}
                <form method="GET" action="{{ route('services.institutions.index') }}" class="mt-8 max-w-2xl mx-auto">
                    <div class="flex flex-col sm:flex-row gap-2 bg-white/10 backdrop-blur-md p-2 rounded-2xl border border-white/20 shadow-xl">
                        <div class="relative flex-1">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text"
                                   name="q"
                                   value="{{ $search }}"
                                   placeholder="{{ __('institutions.search_placeholder') }}"
                                   class="w-full bg-white text-slate-900 placeholder-slate-400 text-sm rounded-xl pl-11 pr-4 py-3 border-0 focus:ring-2 focus:ring-emerald-400 outline-none">
                        </div>
                        @if($selectedType)
                            <input type="hidden" name="type" value="{{ $selectedType }}">
                        @endif
                        <button type="submit"
                                class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm transition shadow-md flex items-center justify-center gap-2">
                            <span>Search</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </form>

                {{-- Filter Pills by Institution Type --}}
                <div class="mt-6 flex flex-wrap justify-center gap-2">
                    <a href="{{ route('services.institutions.index', array_filter(['q' => $search])) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition border {{ empty($selectedType) ? 'bg-emerald-500 text-white border-emerald-400 shadow-md' : 'bg-white/10 text-slate-300 border-white/10 hover:bg-white/20' }}">
                        {{ __('institutions.all_types') }}
                    </a>
                    @foreach($types as $key => $label)
                        <a href="{{ route('services.institutions.index', array_filter(['type' => $key, 'q' => $search])) }}"
                           class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition border {{ $selectedType === $key ? 'bg-emerald-500 text-white border-emerald-400 shadow-md' : 'bg-white/10 text-slate-300 border-white/10 hover:bg-white/20' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         2. MAIN INSTITUTIONS DIRECTORY GRID
         ============================================================ --}}
    <section class="py-12 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if($institutions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($institutions as $inst)
                        @php
                            $locale = app()->getLocale();
                            $trans = $inst->translationFor($locale);
                            $instSlug = $trans?->slug ?? $inst->id;
                            $instName = $trans?->name ?? 'Government Institution';
                            $instLocation = $trans?->location_name ?? '';
                            $instDesc = $trans?->short_description ?? Str::limit(strip_tags($trans?->description ?? ''), 120);
                            $instHours = $trans?->office_hours;
                        @endphp
                        <article class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group">
                            
                            {{-- Image Banner (Single Cover Photo) --}}
                            <div class="relative h-52 sm:h-56 bg-slate-900 overflow-hidden">
                                @if($inst->image_path)
                                    <img src="{{ asset('storage/' . $inst->image_path) }}"
                                         alt="{{ $instName }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-tr from-slate-900 to-emerald-950 text-slate-400">
                                        <i class="fas fa-building text-5xl text-emerald-400/40 mb-2"></i>
                                        <span class="text-xs uppercase tracking-wider font-semibold text-slate-500">Official Government Office</span>
                                    </div>
                                @endif

                                {{-- Type Badge --}}
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase shadow-md bg-white/95 text-slate-800 backdrop-blur-md">
                                        {{ $inst->type_label }}
                                    </span>
                                </div>

                                {{-- Rating Star Badge --}}
                                @if($inst->review_count > 0)
                                    <div class="absolute top-4 right-4 bg-slate-900/90 backdrop-blur-md px-2.5 py-1 rounded-full text-xs font-bold text-amber-400 flex items-center gap-1 shadow-md">
                                        <span>★</span>
                                        <span class="text-white">{{ number_format($inst->average_rating, 1) }}</span>
                                        <span class="text-slate-400 text-[10px]">({{ $inst->review_count }})</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Card Body --}}
                            <div class="p-6 flex-1 flex flex-col">
                                <h2 class="text-xl font-bold text-slate-900 group-hover:text-emerald-700 transition line-clamp-2">
                                    <a href="{{ route('services.institutions.show', $instSlug) }}">
                                        {{ $instName }}
                                    </a>
                                </h2>

                                @if($instLocation)
                                    <p class="mt-2 text-xs sm:text-sm text-slate-500 flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-emerald-600 flex-shrink-0"></i>
                                        <span class="truncate">{{ $instLocation }}</span>
                                    </p>
                                @endif

                                @if($instDesc)
                                    <p class="mt-3 text-xs sm:text-sm text-slate-600 line-clamp-2 leading-relaxed">
                                        {{ $instDesc }}
                                    </p>
                                @endif

                                {{-- Quick Stats / Counts (Units, Services, Officers) --}}
                                <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-3 gap-2 text-center text-xs">
                                    <div class="bg-slate-50 p-2 rounded-xl">
                                        <span class="block font-bold text-slate-800 text-sm">{{ $inst->units->count() }}</span>
                                        <span class="text-[11px] text-slate-500">Units</span>
                                    </div>
                                    <div class="bg-slate-50 p-2 rounded-xl">
                                        <span class="block font-bold text-emerald-700 text-sm">{{ $inst->services->count() }}</span>
                                        <span class="text-[11px] text-slate-500">Services</span>
                                    </div>
                                    <div class="bg-slate-50 p-2 rounded-xl">
                                        <span class="block font-bold text-blue-700 text-sm">{{ $inst->officers->count() }}</span>
                                        <span class="text-[11px] text-slate-500">Officers</span>
                                    </div>
                                </div>

                                {{-- Contact info row --}}
                                <div class="mt-4 space-y-1.5 text-xs text-slate-600">
                                    @if($inst->phone)
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-phone-alt text-slate-400 w-4"></i>
                                            <a href="tel:{{ $inst->phone }}" class="hover:text-emerald-600 font-medium">{{ $inst->phone }}</a>
                                        </div>
                                    @endif
                                    @if($instHours)
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-clock text-slate-400 w-4"></i>
                                            <span class="truncate">{{ $instHours }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- View Details Button --}}
                                <div class="mt-6 pt-4 border-t border-slate-100">
                                    <a href="{{ route('services.institutions.show', $instSlug) }}"
                                       class="w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 shadow-sm group-hover:bg-emerald-600">
                                        <span>{{ __('institutions.view_details') }}</span>
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </a>
                                </div>

                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-10">
                    {{ $institutions->links() }}
                </div>
            @else
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center max-w-xl mx-auto shadow-sm">
                    <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl mb-4">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">
                        {{ __('institutions.no_institutions_found') }}
                    </h3>
                    <p class="mt-2 text-xs sm:text-sm text-slate-500">
                        Try modifying your search or resetting the institution type filters.
                    </p>
                    <a href="{{ route('services.institutions.index') }}"
                       class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold transition">
                        <span>{{ __('institutions.all_types') }}</span>
                    </a>
                </div>
            @endif

        </div>
    </section>

    {{-- ============================================================
         3. OVERVIEW MAP OF ALL GOVERNMENT INSTITUTIONS
         ============================================================ --}}
    @if($mapInstitutions->isNotEmpty())
        <section class="py-12 bg-white border-t border-slate-200">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Geographic Locations</span>
                        <h2 class="text-2xl font-black text-slate-900">
                            {{ __('institutions.interactive_map') }}
                        </h2>
                    </div>
                    <span class="text-xs text-slate-500">
                        Showing {{ $mapInstitutions->count() }} institutional coordinates in Laggala
                    </span>
                </div>

                <div class="rounded-3xl overflow-hidden border border-slate-200 shadow-md">
                    <div id="allInstitutionsMap" class="w-full h-[400px] sm:h-[480px] z-10"></div>
                </div>
            </div>
        </section>
    @endif

</div>

{{-- Leaflet JS --}}
@if($mapInstitutions->isNotEmpty())
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var institutionsData = @json($mapInstitutions);
            if (!institutionsData || institutionsData.length === 0) return;

            var map = L.map('allInstitutionsMap').setView([7.525, 80.742], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var bounds = [];

            institutionsData.forEach(function(item) {
                if (item.lat && item.lng) {
                    var marker = L.marker([item.lat, item.lng]).addTo(map);
                    bounds.push([item.lat, item.lng]);

                    var popupContent = `
                        <div style="min-width: 200px; font-family: inherit;">
                            <span style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #047857;">${item.type}</span>
                            <h4 style="margin: 4px 0 6px 0; font-size: 14px; font-weight: bold; color: #0f172a;">${item.name}</h4>
                            ${item.address ? `<p style="margin: 0 0 6px 0; font-size: 12px; color: #64748b;"><i class="fas fa-map-marker-alt"></i> ${item.address}</p>` : ''}
                            ${item.phone ? `<p style="margin: 0 0 8px 0; font-size: 12px; color: #64748b;"><i class="fas fa-phone-alt"></i> ${item.phone}</p>` : ''}
                            <a href="${item.url}" style="display: inline-block; padding: 4px 10px; background: #0f172a; color: #ffffff; text-decoration: none; border-radius: 6px; font-size: 11px; font-weight: bold;">View Details</a>
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                }
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [40, 40] });
            }
        });
    </script>
@endif

@endsection
