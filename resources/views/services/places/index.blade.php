@extends('layouts.public')

@php
    $locale = app()->getLocale();
    $title = $sectionMeta["title_{$locale}"] ?? $sectionMeta['title_en'];
    $badge = $sectionMeta['badge'];
    $icon = $sectionMeta['icon'];
    $subCategories = $sectionMeta['sub_categories'] ?? [];
@endphp

@section('title', $title . ' — Explore Laggala')

@section('content')

{{-- Leaflet CSS if coordinates exist --}}
@if($mapPlaces->isNotEmpty())
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endif

<div class="bg-slate-50/70 min-h-screen">

    {{-- ============================================================
         1. HERO HEADER SECTION
         ============================================================ --}}
    <section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-white py-16 lg:py-20 overflow-hidden">
        {{-- Ambient glows --}}
        <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-[500px] h-[500px] bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-5 shadow-inner">
                    <span>{{ $badge }}</span>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-tight">
                    {{ $title }}
                </h1>

                <p class="mt-4 text-base sm:text-lg text-slate-300 leading-relaxed font-light">
                    {{ __('services.services_badge') }} in Laggala and Knuckles Mountain Range buffer zone.
                </p>

                {{-- Fast Category Jump Pills --}}
                <div class="mt-6 flex flex-wrap justify-center gap-2">
                    <a href="{{ route('services.places.section', 'health') }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition border {{ $section === 'health' ? 'bg-red-500 text-white border-red-400 shadow-md' : 'bg-white/10 text-slate-300 border-white/10 hover:bg-white/20' }}">
                        <i class="bi bi-hospital mr-1"></i> Health Services
                    </a>
                    <a href="{{ route('services.places.section', 'shops-businesses') }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition border {{ $section === 'shops-businesses' ? 'bg-emerald-500 text-white border-emerald-400 shadow-md' : 'bg-white/10 text-slate-300 border-white/10 hover:bg-white/20' }}">
                        <i class="bi bi-shop mr-1"></i> Shops &amp; Businesses
                    </a>
                    <a href="{{ route('services.places.section', 'banks-atms') }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition border {{ $section === 'banks-atms' ? 'bg-blue-500 text-white border-blue-400 shadow-md' : 'bg-white/10 text-slate-300 border-white/10 hover:bg-white/20' }}">
                        <i class="bi bi-bank2 mr-1"></i> Banks &amp; ATMs
                    </a>
                    <a href="{{ route('services.places.section', 'fuel-ev') }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition border {{ $section === 'fuel-ev' ? 'bg-amber-500 text-white border-amber-400 shadow-md' : 'bg-white/10 text-slate-300 border-white/10 hover:bg-white/20' }}">
                        <i class="bi bi-fuel-pump mr-1"></i> Fuel &amp; EV
                    </a>
                    <a href="{{ route('services.places.section', 'education') }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition border {{ $section === 'education' ? 'bg-purple-500 text-white border-purple-400 shadow-md' : 'bg-white/10 text-slate-300 border-white/10 hover:bg-white/20' }}">
                        <i class="bi bi-mortarboard mr-1"></i> Education
                    </a>
                </div>

                {{-- Search Bar --}}
                <form method="GET" action="{{ route('services.places.section', $section) }}" class="mt-8 max-w-2xl mx-auto">
                    <div class="flex flex-col sm:flex-row gap-2 bg-white/10 backdrop-blur-md p-2 rounded-2xl border border-white/20 shadow-xl">
                        <div class="relative flex-1">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text"
                                   name="q"
                                   value="{{ $search }}"
                                   placeholder="{{ __('services.search_placeholder') }}"
                                   class="w-full bg-white text-slate-900 placeholder-slate-400 text-sm rounded-xl pl-11 pr-4 py-3 border-0 focus:ring-2 focus:ring-emerald-400 outline-none">
                        </div>
                        @if($subCategory)
                            <input type="hidden" name="category" value="{{ $subCategory }}">
                        @endif
                        <button type="submit"
                                class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm transition shadow-md flex items-center justify-center gap-2">
                            <span>Search</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </form>

                {{-- Sub-category Filter Pills (if any defined) --}}
                @if(!empty($subCategories))
                    <div class="mt-5 flex flex-wrap justify-center gap-2">
                        <a href="{{ route('services.places.section', array_filter(['section' => $section, 'q' => $search])) }}"
                           class="px-3 py-1 rounded-full text-xs font-medium transition border {{ empty($subCategory) ? 'bg-emerald-500 text-white border-emerald-400' : 'bg-slate-800/80 text-slate-300 border-slate-700 hover:bg-slate-700' }}">
                            {{ __('services.all_categories') }}
                        </a>
                        @foreach($subCategories as $subKey => $subLabel)
                            <a href="{{ route('services.places.section', array_filter(['section' => $section, 'category' => $subKey, 'q' => $search])) }}"
                               class="px-3 py-1 rounded-full text-xs font-medium transition border {{ $subCategory === $subKey ? 'bg-emerald-500 text-white border-emerald-400' : 'bg-slate-800/80 text-slate-300 border-slate-700 hover:bg-slate-700' }}">
                                {{ $subLabel }}
                            </a>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </section>

    {{-- ============================================================
         2. MAIN SERVICES DIRECTORY GRID
         ============================================================ --}}
    <section class="py-12 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if($places->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($places as $place)
                        @php
                            $trans = $place->translationFor($locale);
                            $pSlug = $trans?->slug ?? $place->id;
                            $pName = $trans?->name ?? 'Service Place';
                            $pAddress = $trans?->location_name ?? '';
                            $pDesc = $trans?->short_description ?? Str::limit(strip_tags($trans?->description ?? ''), 120);
                            $pHours = $trans?->operating_hours;
                            $pFacilities = $trans?->key_facilities;
                        @endphp
                        <article class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group">
                            
                            {{-- Single Photo Preview --}}
                            <div class="relative h-52 sm:h-56 bg-slate-900 overflow-hidden">
                                @if($place->image_path)
                                    <img src="{{ asset('storage/' . $place->image_path) }}"
                                         alt="{{ $pName }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-tr from-slate-900 to-emerald-950 text-slate-400">
                                        <i class="bi {{ $icon }} text-5xl text-emerald-400/40 mb-2"></i>
                                        <span class="text-xs uppercase tracking-wider font-semibold text-slate-500">{{ $title }}</span>
                                    </div>
                                @endif

                                {{-- Badges --}}
                                <div class="absolute top-4 left-4 flex flex-col gap-1.5">
                                    @if($place->sub_category)
                                        <span class="px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase shadow-md bg-white/95 text-slate-800 backdrop-blur-md">
                                            {{ $subCategories[$place->sub_category] ?? ucfirst($place->sub_category) }}
                                        </span>
                                    @endif
                                    @if($place->is_24_hours)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider shadow bg-emerald-600 text-white">
                                            24/7 OPEN
                                        </span>
                                    @endif
                                </div>

                                {{-- Rating Star Badge --}}
                                @if($place->review_count > 0)
                                    <div class="absolute top-4 right-4 bg-slate-900/90 backdrop-blur-md px-2.5 py-1 rounded-full text-xs font-bold text-amber-400 flex items-center gap-1 shadow-md">
                                        <span>★</span>
                                        <span class="text-white">{{ number_format($place->average_rating, 1) }}</span>
                                        <span class="text-slate-400 text-[10px]">({{ $place->review_count }})</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Card Body --}}
                            <div class="p-6 flex-1 flex flex-col">
                                <h2 class="text-xl font-bold text-slate-900 group-hover:text-emerald-700 transition line-clamp-2">
                                    <a href="{{ route('services.places.show', [$section, $pSlug]) }}">
                                        {{ $pName }}
                                    </a>
                                </h2>

                                @if($pAddress)
                                    <p class="mt-2 text-xs sm:text-sm text-slate-500 flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-emerald-600 flex-shrink-0"></i>
                                        <span class="truncate">{{ $pAddress }}</span>
                                    </p>
                                @endif

                                @if($pDesc)
                                    <p class="mt-3 text-xs sm:text-sm text-slate-600 line-clamp-2 leading-relaxed">
                                        {{ $pDesc }}
                                    </p>
                                @endif

                                {{-- Facilities snippet if available --}}
                                @if($pFacilities)
                                    <div class="mt-3 bg-slate-50 rounded-xl p-2.5 text-[11px] text-slate-600 line-clamp-1 border border-slate-100">
                                        <i class="fas fa-check-circle text-emerald-600 mr-1"></i>
                                        <span>{{ $pFacilities }}</span>
                                    </div>
                                @endif

                                {{-- Contact Info & Hours --}}
                                <div class="mt-4 pt-4 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                                    @if($place->phone)
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-phone-alt text-slate-400 w-4"></i>
                                            <a href="tel:{{ $place->phone }}" class="hover:text-emerald-600 font-medium">{{ $place->phone }}</a>
                                        </div>
                                    @endif

                                    @if($place->emergency_hotline)
                                        <div class="flex items-center gap-2 text-red-600 font-semibold">
                                            <i class="fas fa-ambulance w-4"></i>
                                            <span>Hotline: {{ $place->emergency_hotline }}</span>
                                        </div>
                                    @endif

                                    @if($pHours)
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-clock text-slate-400 w-4"></i>
                                            <span class="truncate">{{ $pHours }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- View Details Action Button --}}
                                <div class="mt-6 pt-4 border-t border-slate-100">
                                    <a href="{{ route('services.places.show', [$section, $pSlug]) }}"
                                       class="w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 shadow-sm group-hover:bg-emerald-600">
                                        <span>{{ __('services.view_details') }}</span>
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </a>
                                </div>

                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-10">
                    {{ $places->links() }}
                </div>
            @else
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center max-w-xl mx-auto shadow-sm">
                    <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl mb-4">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">
                        {{ __('services.no_places_found') }}
                    </h3>
                    <p class="mt-2 text-xs sm:text-sm text-slate-500">
                        Try modifying your search or resetting category filters.
                    </p>
                    <a href="{{ route('services.places.section', $section) }}"
                       class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold transition">
                        <span>{{ __('services.all_categories') }}</span>
                    </a>
                </div>
            @endif

        </div>
    </section>

    {{-- ============================================================
         3. INTERACTIVE MAP OF PLACES IN THIS SECTION
         ============================================================ --}}
    @if($mapPlaces->isNotEmpty())
        <section class="py-12 bg-white border-t border-slate-200">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Geographic Map</span>
                        <h2 class="text-2xl font-black text-slate-900">
                            {{ __('services.interactive_map') }}
                        </h2>
                    </div>
                    <span class="text-xs text-slate-500">
                        Showing {{ $mapPlaces->count() }} locations in {{ $title }}
                    </span>
                </div>

                <div class="rounded-3xl overflow-hidden border border-slate-200 shadow-md">
                    <div id="servicePlacesMap" class="w-full h-[400px] sm:h-[460px] z-10"></div>
                </div>
            </div>
        </section>
    @endif

</div>

{{-- Leaflet JS --}}
@if($mapPlaces->isNotEmpty())
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var placesData = @json($mapPlaces);
            if (!placesData || placesData.length === 0) return;

            var map = L.map('servicePlacesMap').setView([7.525, 80.742], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var bounds = [];

            placesData.forEach(function(item) {
                if (item.lat && item.lng) {
                    var marker = L.marker([item.lat, item.lng]).addTo(map);
                    bounds.push([item.lat, item.lng]);

                    var popupContent = `
                        <div style="min-width: 200px; font-family: inherit;">
                            <h4 style="margin: 0 0 6px 0; font-size: 14px; font-weight: bold; color: #0f172a;">${item.name}</h4>
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
