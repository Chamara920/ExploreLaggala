@extends('layouts.public')

@section('title', __('stay_eat.main_title') . ' — Explore Laggala')

@section('content')

{{-- Swiper CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<div class="bg-slate-50/60 min-h-screen">

    {{-- ============================================================
         1. HERO HEADER SECTION - Stay & Eat Hub
         ============================================================ --}}
    <section class="relative bg-gradient-to-br from-emerald-950 via-slate-900 to-stone-900 text-white py-16 lg:py-24 overflow-hidden">
        {{-- Ambient glows --}}
        <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-[500px] h-[500px] bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-5 shadow-inner">
                    <span>☕</span>
                    <span>{{ __('stay_eat.badge') }}</span>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-tight">
                    {{ __('stay_eat.main_title') }}
                </h1>

                <p class="mt-5 text-base sm:text-xl text-slate-300 leading-relaxed font-light">
                    {{ __('stay_eat.main_subtitle') }}
                </p>

                {{-- Fast-Jump Nav Pills --}}
                <div class="mt-8 flex flex-wrap justify-center gap-2.5 sm:gap-3">
                    <a href="{{ route('stay-eat.section', 'accommodation') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-emerald-600/80 text-white text-xs sm:text-sm font-semibold backdrop-blur-md border border-white/10 transition shadow-sm">
                        <span>🏨</span>
                        <span>{{ __('stay_eat.sections.accommodation') }}</span>
                    </a>
                    <a href="{{ route('stay-eat.section', 'restaurants-cafes') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-orange-600/80 text-white text-xs sm:text-sm font-semibold backdrop-blur-md border border-white/10 transition shadow-sm">
                        <span>🍽️</span>
                        <span>{{ __('stay_eat.sections.restaurants_cafes') }}</span>
                    </a>
                    <a href="{{ route('stay-eat.section', 'local-food') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-amber-600/80 text-white text-xs sm:text-sm font-semibold backdrop-blur-md border border-white/10 transition shadow-sm">
                        <span>🍛</span>
                        <span>{{ __('stay_eat.sections.local_food') }}</span>
                    </a>
                    <a href="{{ route('stay-eat.section', 'outdoor-dining') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-teal-600/80 text-white text-xs sm:text-sm font-semibold backdrop-blur-md border border-white/10 transition shadow-sm">
                        <span>🏕️</span>
                        <span>{{ __('stay_eat.sections.outdoor_dining') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         2. FOUR CORE SECTIONS SHOWCASE
         ============================================================ --}}
    <section class="py-14 sm:py-20 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">
                Accommodation & Food Hierarchy
            </h2>
            <p class="mt-2 text-sm sm:text-base text-gray-500">
                Browse through our 4 curated culinary & lodging departments designed for every traveler.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- 1. Accommodation --}}
            <div class="rounded-3xl bg-white border border-gray-100 p-7 sm:p-8 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl pointer-events-none group-hover:scale-150 transition duration-700"></div>

                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-3xl shadow-sm">
                            🏨
                        </div>
                        <span class="text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 px-3 py-1 rounded-full">
                            {{ $sectionsData['accommodation']['count'] }} Places Listed
                        </span>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 group-hover:text-blue-600 transition">
                        {{ __('stay_eat.sections.accommodation') }}
                    </h3>

                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        {{ __('stay_eat.section_subtitles.accommodation') }}
                    </p>

                    {{-- Subcategory Badges --}}
                    <div class="mt-5">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5">Available Types:</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($sectionsData['accommodation']['categories'] as $cat)
                                <a href="{{ route('stay-eat.section', ['accommodation', 'category' => $cat->slug]) }}"
                                   class="text-xs font-medium text-slate-700 bg-slate-100 hover:bg-blue-600 hover:text-white px-3 py-1 rounded-lg transition">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-400">Resorts, Guest Houses, Homestays &amp; Camps</span>
                    <a href="{{ route('stay-eat.section', 'accommodation') }}"
                       class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 transition">
                        <span>Explore Accommodation</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            {{-- 2. Restaurants & Cafes --}}
            <div class="rounded-3xl bg-white border border-gray-100 p-7 sm:p-8 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-orange-500/10 rounded-full blur-2xl pointer-events-none group-hover:scale-150 transition duration-700"></div>

                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center text-3xl shadow-sm">
                            🍽️
                        </div>
                        <span class="text-xs font-bold text-orange-700 bg-orange-50 border border-orange-200 px-3 py-1 rounded-full">
                            {{ $sectionsData['restaurants-cafes']['count'] }} Dining Spots
                        </span>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 group-hover:text-orange-600 transition">
                        {{ __('stay_eat.sections.restaurants_cafes') }}
                    </h3>

                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        {{ __('stay_eat.section_subtitles.restaurants_cafes') }}
                    </p>

                    {{-- Subcategory Badges --}}
                    <div class="mt-5">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5">Available Types:</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($sectionsData['restaurants-cafes']['categories'] as $cat)
                                <a href="{{ route('stay-eat.section', ['restaurants-cafes', 'category' => $cat->slug]) }}"
                                   class="text-xs font-medium text-slate-700 bg-slate-100 hover:bg-orange-600 hover:text-white px-3 py-1 rounded-lg transition">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-400">Cafés, Family Dining &amp; Deliveries</span>
                    <a href="{{ route('stay-eat.section', 'restaurants-cafes') }}"
                       class="inline-flex items-center gap-2 text-sm font-bold text-orange-600 hover:text-orange-800 transition">
                        <span>Explore Dining</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            {{-- 3. Local Food --}}
            <div class="rounded-3xl bg-white border border-gray-100 p-7 sm:p-8 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl pointer-events-none group-hover:scale-150 transition duration-700"></div>

                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-3xl shadow-sm">
                            🍛
                        </div>
                        <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">
                            {{ $sectionsData['local-food']['count'] }} Flavours
                        </span>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 group-hover:text-amber-600 transition">
                        {{ __('stay_eat.sections.local_food') }}
                    </h3>

                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        {{ __('stay_eat.section_subtitles.local_food') }}
                    </p>

                    {{-- Subcategory Badges --}}
                    <div class="mt-5">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5">Available Types:</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($sectionsData['local-food']['categories'] as $cat)
                                <a href="{{ route('stay-eat.section', ['local-food', 'category' => $cat->slug]) }}"
                                   class="text-xs font-medium text-slate-700 bg-slate-100 hover:bg-amber-600 hover:text-white px-3 py-1 rounded-lg transition">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-400">Village Meals, Herbal Drinks &amp; Sweetmeats</span>
                    <a href="{{ route('stay-eat.section', 'local-food') }}"
                       class="inline-flex items-center gap-2 text-sm font-bold text-amber-600 hover:text-amber-800 transition">
                        <span>Explore Local Food</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            {{-- 4. Outdoor Dining & Catering --}}
            <div class="rounded-3xl bg-white border border-gray-100 p-7 sm:p-8 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-teal-500/10 rounded-full blur-2xl pointer-events-none group-hover:scale-150 transition duration-700"></div>

                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-teal-50 border border-teal-100 flex items-center justify-center text-3xl shadow-sm">
                            🏕️
                        </div>
                        <span class="text-xs font-bold text-teal-700 bg-teal-50 border border-teal-200 px-3 py-1 rounded-full">
                            {{ $sectionsData['outdoor-dining']['count'] }} Services
                        </span>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 group-hover:text-teal-600 transition">
                        {{ __('stay_eat.sections.outdoor_dining') }}
                    </h3>

                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        {{ __('stay_eat.section_subtitles.outdoor_dining') }}
                    </p>

                    {{-- Subcategory Badges --}}
                    <div class="mt-5">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5">Available Types:</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($sectionsData['outdoor-dining']['categories'] as $cat)
                                <a href="{{ route('stay-eat.section', ['outdoor-dining', 'category' => $cat->slug]) }}"
                                   class="text-xs font-medium text-slate-700 bg-slate-100 hover:bg-teal-600 hover:text-white px-3 py-1 rounded-lg transition">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-400">Picnics, Camp Feasts &amp; Group Catering</span>
                    <a href="{{ route('stay-eat.section', 'outdoor-dining') }}"
                       class="inline-flex items-center gap-2 text-sm font-bold text-teal-600 hover:text-teal-800 transition">
                        <span>Explore Outdoor Dining</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

        </div>
    </section>

    {{-- ============================================================
         3. FEATURED HANDPICKED PLACES
         ============================================================ --}}
    @if(isset($allFeatured) && $allFeatured->count() > 0)
        <section class="py-12 bg-white border-y border-gray-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">Top Recommendations</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">Handpicked Mountain Stays &amp; Dining</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($allFeatured as $fPlace)
                        @php
                            $fTrans = $fPlace->translationFor($locale);
                            $fCover = $fPlace->coverImage();
                            $fSlug = $fTrans?->slug ?? $fPlace->id;
                        @endphp
                        <article class="group rounded-3xl border border-gray-200 bg-white overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                            <div class="relative h-56 overflow-hidden bg-slate-900">
                                @if($fCover)
                                    <img src="{{ asset('storage/' . $fCover->image_path) }}"
                                         alt="{{ $fTrans?->title }}"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-emerald-900 to-slate-900 flex items-center justify-center text-emerald-400">
                                        <i class="fas fa-hotel text-4xl"></i>
                                    </div>
                                @endif

                                <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                    @if($fPlace->category)
                                        <span class="px-2.5 py-1 rounded-full bg-black/60 backdrop-blur-md text-white text-xs font-semibold">
                                            {{ $fPlace->category->name }}
                                        </span>
                                    @endif
                                </div>

                                @if($fPlace->price_range)
                                    <div class="absolute bottom-3 right-3">
                                        <span class="px-3 py-1 rounded-xl bg-emerald-600 text-white text-xs font-bold shadow-md">
                                            {{ $fPlace->price_range }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-emerald-600 transition line-clamp-1">
                                        {{ $fTrans?->title }}
                                    </h3>

                                    @if($fTrans?->location_name)
                                        <p class="mt-1 text-xs text-gray-500 flex items-center gap-1.5">
                                            <i class="fas fa-map-marker-alt text-emerald-500"></i>
                                            <span class="truncate">{{ $fTrans->location_name }}</span>
                                        </p>
                                    @endif

                                    @if($fTrans?->short_description)
                                        <p class="mt-3 text-xs sm:text-sm text-gray-600 line-clamp-2 leading-relaxed">
                                            {{ $fTrans->short_description }}
                                        </p>
                                    @endif
                                </div>

                                <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                                    <div class="flex items-center gap-1 text-amber-500 text-xs">
                                        <i class="fas fa-star"></i>
                                        <span class="font-bold text-gray-900">{{ $fPlace->average_rating > 0 ? $fPlace->average_rating : 'New' }}</span>
                                        @if($fPlace->review_count > 0)
                                            <span class="text-gray-400">({{ $fPlace->review_count }})</span>
                                        @endif
                                    </div>

                                    <a href="{{ route('stay-eat.show', [$fPlace->section, $fSlug]) }}"
                                       class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-800 transition">
                                        <span>{{ __('stay_eat.view_details') }}</span>
                                        <i class="fas fa-chevron-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</div>

@endsection
