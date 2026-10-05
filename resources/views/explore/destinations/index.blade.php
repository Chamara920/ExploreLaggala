@extends('layouts.public')

@section('title', __('destinations.meta_title'))

@section('content')

{{-- Swiper CSS for Sliders --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<div class="bg-gray-50/50 min-h-screen">

    {{-- ============================================================
         1. HERO HEADER SECTION
         ============================================================ --}}
    <section class="relative bg-gradient-to-br from-emerald-950 via-slate-900 to-teal-950 text-white py-12 lg:py-16 overflow-hidden">
        {{-- Decorative background glow --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold uppercase tracking-wider mb-4">
                        <i class="fas fa-compass"></i>
                        <span>{{ __('destinations.badge_explore') }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white">
                        {{ __('destinations.hero_title_prefix') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">{{ __('destinations.hero_title_highlight') }}</span>
                    </h1>

                    <p class="mt-4 text-base sm:text-lg text-slate-300 leading-relaxed max-w-2xl">
                        {{ __('destinations.hero_description') }}
                    </p>
                </div>

                {{-- Quick Stats Badge --}}
                <div class="flex items-center gap-4 bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 self-start md:self-end">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-300 text-xl">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white leading-none">{{ $totalCount }}</div>
                        <div class="text-xs text-slate-300 mt-1">{{ __('destinations.published_spots') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================================
         2. FEATURED DESTINATIONS TOP SLIDER
         ============================================================ --}}
    @if(isset($featuredDestinations) && $featuredDestinations->count() > 0)
        <section class="relative -mt-6 sm:-mt-8 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 z-10 mb-12">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-4 sm:p-6 overflow-hidden">
                <div class="flex items-center justify-between mb-4 px-2">
                    <div class="flex items-center gap-2">
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">{{ __('destinations.featured_highlights') }}</h2>
                        <span class="text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                            {{ __('destinations.handpicked_for_you') }}
                        </span>
                    </div>

                    {{-- Slider Custom Navigation Controls --}}
                    <div class="flex items-center gap-2">
                        <button type="button" id="featPrevBtn" class="w-9 h-9 rounded-full border border-gray-200 bg-white text-gray-700 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition flex items-center justify-center shadow-sm" aria-label="{{ __('destinations.previous_slide') }}">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </button>
                        <button type="button" id="featNextBtn" class="w-9 h-9 rounded-full border border-gray-200 bg-white text-gray-700 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition flex items-center justify-center shadow-sm" aria-label="{{ __('destinations.next_slide') }}">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                </div>

                {{-- Swiper Container --}}
                <div class="swiper featuredSlider rounded-2xl overflow-hidden">
                    <div class="swiper-wrapper">
                        @foreach($featuredDestinations as $fDest)
                            @php
                                $fTrans = $fDest->translationFor(app()->getLocale());
                                $fCover = $fDest->coverImage();
                                $fSlug = $fTrans?->slug ?? $fDest->translations->first()?->slug ?? $fDest->id;
                            @endphp

                            <div class="swiper-slide">
                                <div class="relative group overflow-hidden rounded-2xl bg-gray-900 h-[380px] sm:h-[440px] flex items-end">
                                    {{-- Background Image --}}
                                    @if($fCover)
                                        <img src="{{ asset('storage/' . $fCover->image_path) }}"
                                             alt="{{ $fTrans?->name }}"
                                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    @else
                                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-800 to-slate-900"></div>
                                    @endif

                                    {{-- Dark gradient overlays for readability --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                                    <div class="absolute inset-0 bg-black/10"></div>

                                    {{-- Top Badges --}}
                                    <div class="absolute top-4 left-4 right-4 flex items-center justify-between pointer-events-none">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-600/90 backdrop-blur-md text-white text-xs font-semibold shadow">
                                            <i class="fas fa-star text-amber-300"></i> {{ __('destinations.featured') }}
                                        </span>

                                        @if($fDest->average_rating > 0)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-black/60 backdrop-blur-md text-white text-xs font-semibold border border-white/20">
                                                <i class="fas fa-star text-amber-400"></i> {{ number_format($fDest->average_rating, 1) }} ({{ $fDest->review_count }})
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Bottom Info Box --}}
                                    <div class="relative p-6 sm:p-8 w-full z-10">
                                        {{-- Interests tags --}}
                                        @if($fDest->interests->count() > 0)
                                            <div class="flex flex-wrap gap-1.5 mb-2">
                                                @foreach($fDest->interests->take(3) as $interest)
                                                    @php
                                                        $fCatTag = __('destinations.categories_map.' . strtolower($interest->slug));
                                                        $fTagLabel = ($fCatTag !== 'destinations.categories_map.' . strtolower($interest->slug)) ? $fCatTag : $interest->name;
                                                    @endphp
                                                    <span class="text-[11px] font-medium px-2.5 py-0.5 rounded-full bg-white/20 backdrop-blur-md text-emerald-200 border border-white/10">
                                                        {{ $fTagLabel }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif

                                        <h3 class="text-2xl sm:text-3xl font-extrabold text-white group-hover:text-emerald-300 transition line-clamp-1">
                                            {{ $fTrans?->name ?? __('destinations.destination_single') }}
                                        </h3>

                                        @if($fTrans?->location_name)
                                            <p class="text-xs sm:text-sm text-emerald-300 font-medium flex items-center gap-1.5 mt-1">
                                                <i class="fas fa-map-marker-alt"></i> {{ $fTrans->location_name }}
                                            </p>
                                        @endif

                                        @if($fTrans?->short_description)
                                            <p class="mt-2 text-xs sm:text-sm text-slate-200 line-clamp-2 max-w-3xl leading-relaxed">
                                                {{ $fTrans->short_description }}
                                            </p>
                                        @endif

                                        <div class="mt-4 pt-3 border-t border-white/20 flex items-center justify-between">
                                            <a href="{{ route('explore.destinations.show', $fSlug) }}"
                                               class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-lg hover:bg-emerald-500 hover:shadow-emerald-500/25 transition">
                                                <span>{{ __('destinations.explore_destination_btn') }}</span>
                                                <i class="fas fa-arrow-right text-xs"></i>
                                            </a>
                                            <span class="text-xs text-slate-300 hidden sm:inline-block">
                                                <i class="fas fa-images mr-1"></i> {{ $fDest->images->count() }} {{ __('destinations.photos') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination Dots --}}
                    <div class="swiper-pagination mt-4 !relative"></div>
                </div>
            </div>
        </section>
    @endif


    {{-- ============================================================
         3. MAIN SECTION: SIDEBAR (CLASSIFICATION) + DESTINATIONS GRID
         ============================================================ --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            {{-- ------------------------------------------------------
                 LEFT SIDEBAR: CATEGORIES & FILTERS
                 ------------------------------------------------------ --}}
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 lg:sticky lg:top-24 space-y-6">

                    {{-- Section Title --}}
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold">
                                <i class="fas fa-filter"></i>
                            </span>
                            <h3 class="text-base font-bold text-gray-900">{{ __('destinations.filter_spots') }}</h3>
                        </div>

                        @if(request()->filled('category') || request()->filled('search') || request()->filled('sort'))
                            <a href="{{ route('explore.destinations.index') }}"
                               class="text-xs font-semibold text-red-600 hover:text-red-700 flex items-center gap-1 hover:underline">
                                <i class="fas fa-times-circle"></i> {{ __('destinations.reset') }}
                            </a>
                        @endif
                    </div>

                    {{-- Search Form --}}
                    <div>
                        <label for="destinationSearch" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            {{ __('destinations.search_place') }}
                        </label>
                        <form method="GET" action="{{ route('explore.destinations.index') }}">
                            @if(request()->filled('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request()->filled('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif

                            <div class="relative">
                                <input type="text"
                                       id="destinationSearch"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="{{ __('destinations.search_placeholder') }}"
                                       class="w-full rounded-xl border border-gray-300 bg-gray-50/50 pl-9 pr-9 py-2.5 text-xs text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-emerald-500 transition">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-xs">
                                    <i class="fas fa-search"></i>
                                </div>
                                @if(request('search'))
                                    <a href="{{ route('explore.destinations.index', array_merge(request()->except('search'), ['page' => 1])) }}"
                                       class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 text-xs">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Categories / Classification (Interests) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">
                            {{ __('destinations.categories') }}
                        </label>

                        <div class="space-y-1">
                            {{-- "All Destinations" --}}
                            <a href="{{ route('explore.destinations.index', array_merge(request()->except(['category', 'page']))) }}"
                               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ !request()->filled('category') ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100' }}">
                                <div class="flex items-center gap-2.5">
                                    <i class="fas fa-th-large {{ !request()->filled('category') ? 'text-white' : 'text-gray-400' }}"></i>
                                    <span>{{ __('destinations.all_destinations') }}</span>
                                </div>
                                <span class="rounded-full px-2 py-0.5 text-[11px] font-bold {{ !request()->filled('category') ? 'bg-white/25 text-white' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $totalCount }}
                                </span>
                            </a>

                            {{-- Dynamic Categories / Interests --}}
                            @foreach($interests as $interest)
                                @php
                                    $isActive = request('category') == $interest->slug || request('category') == $interest->id;

                                    // Category icon assignment
                                    $catIcon = match(strtolower($interest->name)) {
                                        'nature' => 'fas fa-tree text-green-500',
                                        'hiking' => 'fas fa-hiking text-emerald-500',
                                        'photography' => 'fas fa-camera text-blue-500',
                                        'waterfalls', 'waterfall' => 'fas fa-water text-cyan-500',
                                        'camping' => 'fas fa-campground text-amber-500',
                                        'heritage', 'culture' => 'fas fa-landmark text-amber-600',
                                        default => 'fas fa-tag text-emerald-500'
                                    };

                                    $catLabel = __('destinations.categories_map.' . strtolower($interest->slug));
                                    if ($catLabel === 'destinations.categories_map.' . strtolower($interest->slug)) {
                                        $catLabel = $interest->name;
                                    }
                                @endphp

                                <a href="{{ route('explore.destinations.index', array_merge(request()->except('page'), ['category' => $interest->slug])) }}"
                                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ $isActive ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100' }}">
                                    <div class="flex items-center gap-2.5">
                                        <i class="{{ $isActive ? 'fas fa-check text-white' : $catIcon }}"></i>
                                        <span>{{ $catLabel }}</span>
                                    </div>
                                    <span class="rounded-full px-2 py-0.5 text-[11px] font-bold {{ $isActive ? 'bg-white/25 text-white' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $interest->destinations_count }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Travel Advice / Quick Card --}}
                    <div class="rounded-xl bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200/60 p-4">
                        <div class="flex items-start gap-3">
                            <span class="text-xl text-emerald-700">🧭</span>
                            <div>
                                <h4 class="text-xs font-bold text-emerald-950">{{ __('destinations.plan_before_title') }}</h4>
                                <p class="text-[11px] text-emerald-800/90 mt-1 leading-relaxed">
                                    {{ __('destinations.plan_before_desc') }}
                                </p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <a href="{{ route('explore.map') }}"
                                       class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-900 underline">
                                        <span>{{ __('destinations.interactive_map') }}</span> →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </aside>


            {{-- ------------------------------------------------------
                 RIGHT COLUMN: DESTINATIONS LIST & TOOLBAR
                 ------------------------------------------------------ --}}
            <main class="lg:col-span-3">

                {{-- Toolbar: Results Count, Active Tags, Sorting --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    {{-- Left: Count & Active Filter Tags --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-bold text-gray-800">
                            {{ __('destinations.showing') }} <span class="text-emerald-700">{{ $destinations->total() }}</span> {{ $destinations->total() == 1 ? __('destinations.destination_single') : __('destinations.destination_plural') }}
                        </span>

                        @if(request('category'))
                            @php
                                $currentInterest = $interests->first(fn($i) => $i->slug == request('category') || $i->id == request('category'));
                                $catDisplayName = $currentInterest ? (__('destinations.categories_map.' . strtolower($currentInterest->slug)) != 'destinations.categories_map.' . strtolower($currentInterest->slug) ? __('destinations.categories_map.' . strtolower($currentInterest->slug)) : $currentInterest->name) : request('category');
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-medium">
                                <span>{{ __('destinations.category_filter', ['name' => $catDisplayName]) }}</span>
                                <a href="{{ route('explore.destinations.index', array_merge(request()->except(['category', 'page']))) }}"
                                   class="text-emerald-700 hover:text-emerald-900" title="{{ __('destinations.remove_filter') }}">
                                    <i class="fas fa-times text-[10px]"></i>
                                </a>
                            </span>
                        @endif

                        @if(request('search'))
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-medium">
                                <span>{{ __('destinations.keyword_filter', ['keyword' => request('search')]) }}</span>
                                <a href="{{ route('explore.destinations.index', array_merge(request()->except(['search', 'page']))) }}"
                                   class="text-blue-700 hover:text-blue-900" title="{{ __('destinations.remove_search') }}">
                                    <i class="fas fa-times text-[10px]"></i>
                                </a>
                            </span>
                        @endif
                    </div>

                    {{-- Right: Sort Selector --}}
                    <div class="flex items-center gap-2 self-end sm:self-auto">
                        <label for="sortSelector" class="text-xs font-medium text-gray-500 whitespace-nowrap">{{ __('destinations.sort_by') }}</label>
                        <select id="sortSelector"
                                onchange="location.href = this.value;"
                                class="rounded-xl border border-gray-300 bg-white py-1.5 pl-3 pr-8 text-xs font-medium text-gray-700 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                            <option value="{{ route('explore.destinations.index', array_merge(request()->except('sort'), ['sort' => 'default'])) }}" {{ request('sort') == 'default' || !request('sort') ? 'selected' : '' }}>
                                {{ __('destinations.sort_recommended') }}
                            </option>
                            <option value="{{ route('explore.destinations.index', array_merge(request()->except('sort'), ['sort' => 'rating'])) }}" {{ request('sort') == 'rating' ? 'selected' : '' }}>
                                {{ __('destinations.sort_rating') }}
                            </option>
                            <option value="{{ route('explore.destinations.index', array_merge(request()->except('sort'), ['sort' => 'name'])) }}" {{ request('sort') == 'name' ? 'selected' : '' }}>
                                {{ __('destinations.sort_name') }}
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Destination Grid --}}
                @if($destinations->count())

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

                        @foreach($destinations as $destination)
                            @php
                                $translation = $destination->translationFor(app()->getLocale());
                                $coverImage = $destination->coverImage();
                                $slug = $translation?->slug ?? $destination->translations->first()?->slug ?? $destination->id;
                            @endphp

                            <article class="group flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                                {{-- Card Image with overlay badges --}}
                                <div class="relative overflow-hidden aspect-[4/3] bg-gray-100">
                                    <a href="{{ route('explore.destinations.show', $slug) }}" class="block w-full h-full">
                                        @if($coverImage)
                                            <img src="{{ asset('storage/' . $coverImage->image_path) }}"
                                                 alt="{{ $translation?->name }}"
                                                 loading="lazy"
                                                 class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                        @else
                                            <div class="flex h-full items-center justify-center bg-gray-100 text-gray-400 text-xs">
                                                <i class="fas fa-image mr-1"></i> {{ __('destinations.no_image') }}
                                            </div>
                                        @endif
                                    </a>

                                    {{-- Badges --}}
                                    <div class="absolute top-3 left-3 right-3 flex items-center justify-between pointer-events-none">
                                        @if($destination->featured)
                                            <span class="rounded-full bg-emerald-600/90 backdrop-blur-md px-2.5 py-0.5 text-[11px] font-bold text-white shadow-sm">
                                                <i class="fas fa-star text-amber-300 mr-0.5"></i> {{ __('destinations.featured') }}
                                            </span>
                                        @else
                                            <span></span>
                                        @endif

                                        @if($destination->average_rating > 0)
                                            <span class="rounded-full bg-black/60 backdrop-blur-md px-2.5 py-0.5 text-[11px] font-bold text-white border border-white/20">
                                                ★ {{ number_format($destination->average_rating, 1) }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Photo Count Overlay --}}
                                    @if($destination->images->count() > 1)
                                        <div class="absolute bottom-2.5 right-2.5 pointer-events-none">
                                            <span class="rounded-lg bg-black/60 backdrop-blur-md px-2 py-0.5 text-[10px] font-semibold text-white">
                                                <i class="fas fa-camera mr-1"></i> {{ $destination->images->count() }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Card Body --}}
                                <div class="flex flex-1 flex-col p-5">

                                    {{-- Interest Tags --}}
                                    @if($destination->interests->count() > 0)
                                        <div class="flex flex-wrap gap-1.5 mb-2.5">
                                            @foreach($destination->interests->take(2) as $interest)
                                                @php
                                                    $cardTag = __('destinations.categories_map.' . strtolower($interest->slug));
                                                    $cardTagLabel = ($cardTag !== 'destinations.categories_map.' . strtolower($interest->slug)) ? $cardTag : $interest->name;
                                                @endphp
                                                <a href="{{ route('explore.destinations.index', ['category' => $interest->slug]) }}"
                                                   class="text-[10px] font-semibold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md hover:bg-emerald-100 transition">
                                                    {{ $cardTagLabel }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- Title --}}
                                    <h3 class="text-base font-bold text-gray-900 group-hover:text-emerald-700 transition line-clamp-1">
                                        <a href="{{ route('explore.destinations.show', $slug) }}">
                                            {{ $translation?->name ?? __('destinations.destination_single') }}
                                        </a>
                                    </h3>

                                    {{-- Location --}}
                                    @if($translation?->location_name)
                                        <p class="mt-1 text-xs text-gray-500 flex items-center gap-1">
                                            <i class="fas fa-map-marker-alt text-emerald-600 text-[11px]"></i>
                                            <span class="line-clamp-1">{{ $translation->location_name }}</span>
                                        </p>
                                    @endif

                                    {{-- Snippet --}}
                                    @if($translation?->short_description)
                                        <p class="mt-3 text-xs leading-relaxed text-gray-600 line-clamp-2">
                                            {{ $translation->short_description }}
                                        </p>
                                    @endif

                                    {{-- Footer button --}}
                                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                                        <span class="text-xs text-gray-500 font-medium">
                                            {{ $destination->review_count ? __('destinations.reviews_count', ['count' => $destination->review_count]) : __('destinations.explore_spot') }}
                                        </span>

                                        <a href="{{ route('explore.destinations.show', $slug) }}"
                                           class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 group-hover:text-emerald-800 group-hover:translate-x-0.5 transition">
                                            <span>{{ __('destinations.view_details') }}</span>
                                            <i class="fas fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>

                                </div>

                            </article>
                        @endforeach

                    </div>

                    {{-- Pagination Controls --}}
                    <div class="mt-10">
                        {{ $destinations->links() }}
                    </div>

                @else

                    {{-- Empty State --}}
                    <div class="rounded-3xl border border-dashed border-gray-300 bg-white p-12 text-center shadow-sm">
                        <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center text-2xl mb-4">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900">
                            {{ __('destinations.no_destinations_found') }}
                        </h3>

                        <p class="mt-2 text-xs sm:text-sm text-gray-500 max-w-md mx-auto">
                            @if(request('category') || request('search'))
                                {{ __('destinations.no_destinations_filter_desc') }}
                            @else
                                {{ __('destinations.no_destinations_empty_desc') }}
                            @endif
                        </p>

                        @if(request('category') || request('search'))
                            <div class="mt-6">
                                <a href="{{ route('explore.destinations.index') }}"
                                   class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-emerald-500 transition shadow">
                                    <i class="fas fa-redo-alt"></i>
                                    <span>{{ __('destinations.clear_all_filters') }}</span>
                                </a>
                            </div>
                        @endif
                    </div>

                @endif

            </main>

        </div>

    </section>

</div>

{{-- Swiper JS Script --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.querySelector('.featuredSlider')) {
            const featuredSwiper = new Swiper('.featuredSlider', {
                loop: {{ $featuredDestinations->count() > 1 ? 'true' : 'false' }},
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                },
                speed: 800,
                slidesPerView: 1,
                spaceBetween: 20,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true
                },
                navigation: {
                    prevEl: '#featPrevBtn',
                    nextEl: '#featNextBtn'
                }
            });
        }
    });
</script>

@endsection