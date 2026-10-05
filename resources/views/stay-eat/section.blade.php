@extends('layouts.public')

@section('title', __($sectionMeta['name_key']) . ' — Explore Laggala')

@section('content')

{{-- Swiper CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<div class="bg-slate-50/50 min-h-screen">

    {{-- ============================================================
         1. HERO HEADER SECTION
         ============================================================ --}}
    @php
        $gradientClass = match($section) {
            'accommodation' => 'from-blue-950 via-slate-900 to-stone-900',
            'restaurants-cafes' => 'from-orange-950 via-slate-900 to-stone-900',
            'local-food' => 'from-amber-950 via-slate-900 to-stone-900',
            'outdoor-dining' => 'from-teal-950 via-slate-900 to-stone-900',
            default => 'from-emerald-950 via-slate-900 to-stone-900',
        };

        $accentColor = match($section) {
            'accommodation' => 'blue',
            'restaurants-cafes' => 'orange',
            'local-food' => 'amber',
            'outdoor-dining' => 'teal',
            default => 'emerald',
        };
    @endphp

    <section class="relative bg-gradient-to-br {{ $gradientClass }} text-white py-14 lg:py-20 overflow-hidden">
        {{-- Ambient decorative glows --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-{{ $accentColor }}-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-{{ $accentColor }}-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-{{ $accentColor }}-500/20 border border-{{ $accentColor }}-400/30 text-{{ $accentColor }}-300 text-xs font-semibold uppercase tracking-wider mb-4 shadow-inner">
                        <span>{{ $sectionMeta['icon'] }}</span>
                        <span>{{ __('stay_eat.badge') }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                        {{ __($sectionMeta['name_key']) }}
                    </h1>

                    <p class="mt-4 text-base sm:text-lg text-slate-300 leading-relaxed max-w-2xl font-light">
                        {{ __('stay_eat.section_subtitles.' . str_replace('-', '_', $section)) }}
                    </p>
                </div>

                {{-- Stats Badge --}}
                <div class="flex items-center gap-4 bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 self-start md:self-end shadow-lg">
                    <div class="w-12 h-12 rounded-xl bg-{{ $accentColor }}-500/20 border border-{{ $accentColor }}-400/30 flex items-center justify-center text-2xl">
                        {{ $sectionMeta['icon'] }}
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white leading-none">{{ $totalCount }}</div>
                        <div class="text-xs text-slate-300 mt-1">{{ __('stay_eat.total_places') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         2. FEATURED HERO SLIDER (If available)
         ============================================================ --}}
    @if(isset($featuredItems) && $featuredItems->count() > 0)
        <section class="relative -mt-6 sm:-mt-8 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 z-10 mb-12">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-4 sm:p-6 overflow-hidden">
                <div class="flex items-center justify-between mb-4 px-2">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-{{ $accentColor }}-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-{{ $accentColor }}-500"></span>
                        </span>
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">Featured Highlights</h2>
                        <span class="text-xs font-semibold text-{{ $accentColor }}-800 bg-{{ $accentColor }}-50 border border-{{ $accentColor }}-200 px-3 py-0.5 rounded-full">
                            Handpicked
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" id="featPrevBtn" class="w-9 h-9 rounded-full border border-gray-200 bg-white text-gray-700 hover:bg-{{ $accentColor }}-600 hover:text-white transition flex items-center justify-center shadow-sm" aria-label="Previous">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </button>
                        <button type="button" id="featNextBtn" class="w-9 h-9 rounded-full border border-gray-200 bg-white text-gray-700 hover:bg-{{ $accentColor }}-600 hover:text-white transition flex items-center justify-center shadow-sm" aria-label="Next">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                </div>

                {{-- Swiper Container --}}
                <div class="swiper featuredSlider rounded-2xl overflow-hidden">
                    <div class="swiper-wrapper">
                        @foreach($featuredItems as $fItem)
                            @php
                                $fTrans = $fItem->translationFor($locale);
                                $fCover = $fItem->coverImage();
                                $fSlug = $fTrans?->slug ?? $fItem->id;
                            @endphp

                            <div class="swiper-slide">
                                <div class="relative group overflow-hidden rounded-2xl bg-gray-900 h-[360px] sm:h-[420px] flex items-end">
                                    @if($fCover)
                                        <img src="{{ asset('storage/' . $fCover->image_path) }}"
                                             alt="{{ $fTrans?->title }}"
                                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    @else
                                        <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900"></div>
                                    @endif

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                                    <div class="relative z-10 p-6 sm:p-8 w-full">
                                        <div class="flex flex-wrap items-center gap-2 mb-3">
                                            @if($fItem->category)
                                                <span class="px-3 py-1 rounded-full bg-{{ $accentColor }}-600 text-white text-xs font-bold shadow-md">
                                                    {{ $fItem->category->name }}
                                                </span>
                                            @endif
                                            @if($fItem->price_range)
                                                <span class="px-3 py-1 rounded-full bg-black/60 backdrop-blur-md text-white text-xs font-medium border border-white/20">
                                                    {{ $fItem->price_range }}
                                                </span>
                                            @endif
                                            @if($fItem->review_count > 0)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-500/90 text-white text-xs font-bold">
                                                    <i class="fas fa-star text-[10px]"></i>
                                                    <span>{{ $fItem->average_rating }}</span>
                                                </span>
                                            @endif
                                        </div>

                                        <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white mb-2 leading-snug group-hover:text-{{ $accentColor }}-300 transition">
                                            {{ $fTrans?->title }}
                                        </h3>

                                        @if($fTrans?->location_name)
                                            <p class="text-xs sm:text-sm text-slate-300 flex items-center gap-1.5 mb-4">
                                                <i class="fas fa-map-marker-alt text-{{ $accentColor }}-400"></i>
                                                <span>{{ $fTrans->location_name }}</span>
                                            </p>
                                        @endif

                                        <div class="flex items-center justify-between pt-2 border-t border-white/10">
                                            <p class="text-xs sm:text-sm text-slate-300 line-clamp-1 max-w-xl font-light">
                                                {{ $fTrans?->short_description }}
                                            </p>

                                            <a href="{{ route('stay-eat.show', [$section, $fSlug]) }}"
                                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white hover:bg-{{ $accentColor }}-50 text-gray-900 text-xs font-bold shadow-lg transition flex-shrink-0">
                                                <span>{{ __('stay_eat.view_details') }}</span>
                                                <i class="fas fa-arrow-right text-[10px]"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         3. SEARCH, FILTERS & SUBCATEGORY PILLS
         ============================================================ --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-10">
        <div class="bg-white rounded-3xl p-5 sm:p-7 shadow-sm border border-gray-200/80">

            {{-- Search & Sort Row --}}
            <form method="GET" action="{{ route('stay-eat.section', $section) }}" class="flex flex-col md:flex-row items-center justify-between gap-4">
                @if($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif

                {{-- Search Input --}}
                <div class="relative w-full md:w-96">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="{{ __('stay_eat.search_placeholder') }}"
                           class="w-full pl-11 pr-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-{{ $accentColor }}-500 focus:ring-2 focus:ring-{{ $accentColor }}-200 transition outline-none">
                </div>

                {{-- Sort Dropdown & Action Buttons --}}
                <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                    <div class="flex items-center gap-2">
                        <label for="sortSelect" class="text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            {{ __('stay_eat.sort_by') }}:
                        </label>
                        <select id="sortSelect"
                                name="sort"
                                onchange="this.form.submit()"
                                class="rounded-xl border border-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-{{ $accentColor }}-500 focus:ring-2 focus:ring-{{ $accentColor }}-200 outline-none text-gray-700 bg-white">
                            <option value="default" {{ $sort === 'default' ? 'selected' : '' }}>{{ __('stay_eat.sort_default') }}</option>
                            <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>{{ __('stay_eat.sort_rating') }}</option>
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>{{ __('stay_eat.sort_newest') }}</option>
                        </select>
                    </div>

                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-{{ $accentColor }}-600 hover:bg-{{ $accentColor }}-700 text-white text-xs sm:text-sm font-bold transition shadow-sm">
                        Filter
                    </button>

                    @if($search !== '' || $selectedCategory || $sort !== 'default')
                        <a href="{{ route('stay-eat.section', $section) }}"
                           class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs sm:text-sm font-semibold transition"
                           title="{{ __('stay_eat.reset_filters') }}">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>

            {{-- Subcategory Filter Pills --}}
            @if($categories->count() > 0)
                <div class="mt-6 pt-5 border-t border-gray-100">
                    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                        <a href="{{ route('stay-eat.section', array_merge(['section' => $section], array_filter(['search' => $search, 'sort' => $sort !== 'default' ? $sort : null]))) }}"
                           class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ empty($selectedCategory) ? 'bg-gray-900 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ __('stay_eat.all_types') }}
                        </a>

                        @foreach($categories as $category)
                            @php
                                $isActive = ($selectedCategory === $category->slug || $selectedCategory == $category->id);
                            @endphp
                            <a href="{{ route('stay-eat.section', array_merge(['section' => $section, 'category' => $category->slug], array_filter(['search' => $search, 'sort' => $sort !== 'default' ? $sort : null]))) }}"
                               class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap flex items-center gap-1.5 {{ $isActive ? 'bg-' . $accentColor . '-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                <span>{{ $category->name }}</span>
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full {{ $isActive ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700' }}">
                                    {{ $category->items_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>

    {{-- ============================================================
         4. PLACES LISTING GRID
         ============================================================ --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
        @if($items->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($items as $place)
                    @php
                        $trans = $place->translationFor($locale);
                        $cover = $place->coverImage();
                        $slug = $trans?->slug ?? $place->id;
                    @endphp

                    <article class="group rounded-3xl border border-gray-200 bg-white overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 flex flex-col justify-between">
                        {{-- Cover Image --}}
                        <div class="relative h-60 overflow-hidden bg-slate-900">
                            @if($cover)
                                <img src="{{ asset('storage/' . $cover->image_path) }}"
                                     alt="{{ $trans?->title }}"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center text-gray-400">
                                    <span class="text-4xl">{{ $sectionMeta['icon'] }}</span>
                                </div>
                            @endif

                            {{-- Badges --}}
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                @if($place->category)
                                    <span class="px-3 py-1 rounded-full bg-black/60 backdrop-blur-md text-white text-xs font-bold border border-white/10 shadow-sm">
                                        {{ $place->category->name }}
                                    </span>
                                @endif
                                @if($place->featured)
                                    <span class="px-2.5 py-1 rounded-full bg-{{ $accentColor }}-600 text-white text-xs font-bold shadow-md">
                                        ★ Featured
                                    </span>
                                @endif
                            </div>

                            @if($place->price_range)
                                <div class="absolute bottom-3 right-3">
                                    <span class="px-3 py-1 rounded-xl bg-black/70 backdrop-blur-md text-white text-xs font-bold border border-white/20 shadow-md">
                                        {{ $place->price_range }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Card Body --}}
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-{{ $accentColor }}-600 transition line-clamp-1">
                                    {{ $trans?->title }}
                                </h3>

                                @if($trans?->location_name)
                                    <p class="mt-1 text-xs text-gray-500 flex items-center gap-1.5">
                                        <i class="fas fa-map-marker-alt text-{{ $accentColor }}-500"></i>
                                        <span class="truncate">{{ $trans->location_name }}</span>
                                    </p>
                                @endif

                                @if($trans?->short_description)
                                    <p class="mt-3 text-xs sm:text-sm text-gray-600 line-clamp-2 leading-relaxed">
                                        {{ $trans->short_description }}
                                    </p>
                                @endif
                            </div>

                            {{-- Card Footer --}}
                            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-1.5 text-amber-500 text-xs">
                                    <i class="fas fa-star"></i>
                                    <span class="font-bold text-gray-900">{{ $place->average_rating > 0 ? $place->average_rating : 'New' }}</span>
                                    @if($place->review_count > 0)
                                        <span class="text-gray-400">({{ $place->review_count }})</span>
                                    @endif
                                </div>

                                <a href="{{ route('stay-eat.show', [$section, $slug]) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-{{ $accentColor }}-50 hover:bg-{{ $accentColor }}-600 text-{{ $accentColor }}-700 hover:text-white text-xs font-bold transition">
                                    <span>{{ __('stay_eat.view_details') }}</span>
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            <div class="mt-12">
                {{ $items->links() }}
            </div>

        @else
            {{-- Empty State --}}
            <div class="rounded-3xl border border-gray-200 bg-white p-12 text-center max-w-xl mx-auto shadow-sm">
                <div class="w-16 h-16 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center text-3xl mx-auto mb-4">
                    {{ $sectionMeta['icon'] }}
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">
                    {{ __('stay_eat.no_places_found') }}
                </h3>
                <p class="text-sm text-gray-500 mb-6">
                    {{ __('stay_eat.try_adjusting') }}
                </p>
                <a href="{{ route('stay-eat.section', $section) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-{{ $accentColor }}-600 hover:bg-{{ $accentColor }}-700 text-white text-xs sm:text-sm font-bold transition">
                    <span>{{ __('stay_eat.reset_filters') }}</span>
                </a>
            </div>
        @endif
    </section>

</div>

{{-- Swiper JS Script --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.querySelector('.featuredSlider')) {
            const swiper = new Swiper('.featuredSlider', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: {{ isset($featuredItems) && $featuredItems->count() > 1 ? 'true' : 'false' }},
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    prevEl: '#featPrevBtn',
                    nextEl: '#featNextBtn',
                },
                breakpoints: {
                    768: {
                        slidesPerView: 1,
                    },
                },
            });
        }
    });
</script>

@endsection
