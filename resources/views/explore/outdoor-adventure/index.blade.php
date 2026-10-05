@extends('layouts.public')

@section('title', __('explore.adventure_title') . ' — Explore Laggala')

@section('content')

{{-- Swiper CSS for Sliders --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<div class="bg-slate-50/50 min-h-screen">

    {{-- ============================================================
         1. HERO HEADER SECTION - Outdoor & Adventure
         ============================================================ --}}
    <section class="relative bg-gradient-to-br from-emerald-950 via-teal-950 to-slate-900 text-white py-14 lg:py-20 overflow-hidden"
             style="background: linear-gradient(135deg, #022c22 0%, #042f2e 50%, #0f172a 100%); background-color: #022c22;">
        {{-- Decorative glow & mountain atmosphere --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold uppercase tracking-wider mb-4 shadow-inner">
                        <i class="fas fa-hiking text-emerald-400"></i>
                        <span>{{ __('explore.adventure_badge') }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                        {{ __('explore.adventure_title') }}
                    </h1>

                    <p class="mt-4 text-base sm:text-lg text-slate-300 leading-relaxed max-w-2xl font-light">
                        {{ __('explore.adventure_subtitle') }}
                    </p>
                </div>

                {{-- Stats Badge --}}
                <div class="flex items-center gap-4 bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 self-start md:self-end shadow-lg">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-300 text-xl">
                        <i class="fas fa-mountain"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white leading-none">{{ $totalCount }}</div>
                        <div class="text-xs text-slate-300 mt-1">{{ __('explore.adventure_spots') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         2. FEATURED HIGHLIGHTS SLIDER
         ============================================================ --}}
    @if(isset($featuredItems) && $featuredItems->count() > 0)
        <section class="relative -mt-6 sm:-mt-8 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 z-10 mb-12">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-4 sm:p-6 overflow-hidden">
                <div class="flex items-center justify-between mb-4 px-2">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">{{ __('explore.featured_highlights') }}</h2>
                        <span class="text-xs font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-0.5 rounded-full">
                            {{ __('explore.handpicked') }}
                        </span>
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="flex items-center gap-2">
                        <button type="button" id="advPrevBtn" class="w-9 h-9 rounded-full border border-gray-200 bg-white text-gray-700 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition flex items-center justify-center shadow-sm" aria-label="Previous">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </button>
                        <button type="button" id="advNextBtn" class="w-9 h-9 rounded-full border border-gray-200 bg-white text-gray-700 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition flex items-center justify-center shadow-sm" aria-label="Next">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                </div>

                {{-- Swiper Container --}}
                <div class="swiper adventureFeaturedSlider rounded-2xl overflow-hidden">
                    <div class="swiper-wrapper">
                        @foreach($featuredItems as $fItem)
                            @php
                                $fTrans = $fItem->translationFor();
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
                                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 to-slate-900"></div>
                                    @endif

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                                    <div class="relative z-10 p-6 sm:p-8 w-full max-w-3xl">
                                        @if($fItem->category)
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-emerald-600/80 text-white backdrop-blur-md mb-3">
                                                {{ $fItem->category->name }}
                                            </span>
                                        @endif

                                        <h3 class="text-2xl sm:text-3xl font-extrabold text-white group-hover:text-emerald-300 transition line-clamp-1">
                                            {{ $fTrans?->title }}
                                        </h3>

                                        @if($fTrans?->short_description)
                                            <p class="mt-2 text-sm sm:text-base text-gray-200 line-clamp-2">
                                                {{ $fTrans->short_description }}
                                            </p>
                                        @endif

                                        <div class="mt-4 flex flex-wrap items-center gap-4">
                                            @if($fTrans?->location_name)
                                                <span class="text-xs sm:text-sm text-gray-300 flex items-center gap-1.5">
                                                    <i class="fas fa-map-marker-alt text-emerald-400"></i>
                                                    {{ $fTrans->location_name }}
                                                </span>
                                            @endif

                                            @if($fItem->review_count > 0)
                                                <span class="text-xs sm:text-sm text-amber-300 flex items-center gap-1 bg-white/10 backdrop-blur-md px-2.5 py-1 rounded-lg">
                                                    <i class="fas fa-star text-amber-400"></i>
                                                    {{ $fItem->average_rating }} ({{ $fItem->review_count }})
                                                </span>
                                            @endif

                                            <a href="{{ route('explore.outdoor-adventure.show', $fSlug) }}"
                                               class="ml-auto inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-semibold transition shadow-md">
                                                <span>{{ __('explore.read_more') }}</span>
                                                <i class="fas fa-arrow-right text-xs"></i>
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
         3. SEARCH, FILTER & CATEGORIES BAR
         ============================================================ --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-6">
            <form method="GET" action="{{ route('explore.outdoor-adventure.index') }}" class="space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <div class="md:col-span-8 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="{{ __('explore.search_placeholder') }}"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm transition">
                    </div>

                    <div class="md:col-span-3">
                        <select name="sort"
                                onchange="this.form.submit()"
                                class="w-full py-2.5 px-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm transition">
                            <option value="default" {{ $sort === 'default' ? 'selected' : '' }}>{{ __('explore.sort_default') }}</option>
                            <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>{{ __('explore.sort_rating') }}</option>
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>{{ __('explore.sort_newest') }}</option>
                        </select>
                    </div>

                    <div class="md:col-span-1">
                        <button type="submit"
                                class="w-full h-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition flex items-center justify-center">
                            <i class="fas fa-search sm:hidden"></i>
                            <span class="hidden sm:inline">Go</span>
                        </button>
                    </div>
                </div>

                {{-- Category Pill Filters --}}
                @if($categories->count() > 0)
                    <div class="flex items-center gap-2 overflow-x-auto pt-2 pb-1 text-xs sm:text-sm no-scrollbar">
                        <a href="{{ route('explore.outdoor-adventure.index', array_filter(['search' => $search, 'sort' => $sort])) }}"
                           class="whitespace-nowrap px-4 py-1.5 rounded-full font-medium transition {{ empty($selectedCategory) ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ __('explore.all_categories') }} ({{ $totalCount }})
                        </a>

                        @foreach($categories as $cat)
                            <a href="{{ route('explore.outdoor-adventure.index', array_filter(['category' => $cat->slug, 'search' => $search, 'sort' => $sort])) }}"
                               class="whitespace-nowrap px-4 py-1.5 rounded-full font-medium transition {{ $selectedCategory == $cat->slug ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                {{ $cat->name }} ({{ $cat->items_count }})
                            </a>
                        @endforeach
                    </div>
                @endif
            </form>
        </div>
    </section>

    {{-- ============================================================
         4. ITEMS GRID SECTION
         ============================================================ --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-16">
        @if($items->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($items as $item)
                    @php
                        $trans = $item->translationFor();
                        $cover = $item->coverImage();
                        $slug = $trans?->slug ?? $item->id;
                    @endphp

                    <article class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col group">
                        {{-- Image Container --}}
                        <div class="relative h-60 w-full overflow-hidden bg-gray-900">
                            @if($cover)
                                <img src="{{ asset('storage/' . $cover->image_path) }}"
                                     alt="{{ $trans?->title }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-emerald-900 via-teal-900 to-slate-900 flex items-center justify-center text-emerald-200">
                                    <i class="fas fa-mountain text-4xl opacity-50"></i>
                                </div>
                            @endif

                            {{-- Badges --}}
                            <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                                @if($item->category)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-black/60 backdrop-blur-md text-emerald-300 border border-white/10">
                                        {{ $item->category->name }}
                                    </span>
                                @endif
                                @if($item->featured)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-500 text-white shadow-sm">
                                        ⭐ Featured
                                    </span>
                                @endif
                            </div>

                            @if($item->images->count() > 1)
                                <div class="absolute bottom-3 right-3 px-2 py-1 rounded-md bg-black/60 backdrop-blur-md text-white text-[11px] font-medium flex items-center gap-1">
                                    <i class="fas fa-camera text-emerald-400"></i>
                                    <span>{{ $item->images->count() }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Body Content --}}
                        <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
                            <div>
                                @if($trans?->location_name)
                                    <p class="text-xs text-emerald-700 font-semibold mb-1.5 flex items-center gap-1.5">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $trans->location_name }}</span>
                                    </p>
                                @endif

                                <h3 class="text-lg sm:text-xl font-bold text-gray-900 group-hover:text-emerald-600 transition line-clamp-1">
                                    <a href="{{ route('explore.outdoor-adventure.show', $slug) }}">
                                        {{ $trans?->title }}
                                    </a>
                                </h3>

                                <p class="mt-2 text-sm text-gray-600 line-clamp-3 leading-relaxed">
                                    {{ $trans?->short_description ?? Str::limit(strip_tags($trans?->description ?? ''), 120) }}
                                </p>
                            </div>

                            {{-- Bottom Info & CTA --}}
                            <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-1 text-sm font-semibold text-gray-800">
                                    @if($item->review_count > 0)
                                        <i class="fas fa-star text-amber-400 text-xs"></i>
                                        <span>{{ $item->average_rating }}</span>
                                        <span class="text-xs text-gray-400 font-normal">({{ $item->review_count }})</span>
                                    @else
                                        <span class="text-xs text-gray-400 font-normal">No reviews yet</span>
                                    @endif
                                </div>

                                <a href="{{ route('explore.outdoor-adventure.show', $slug) }}"
                                   class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 transition group-hover:translate-x-1 duration-200">
                                    <span>{{ __('explore.read_more') }}</span>
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $items->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-3xl border border-gray-200 p-12 text-center max-w-lg mx-auto shadow-sm">
                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto text-2xl mb-4">
                    <i class="fas fa-compass"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">{{ __('explore.no_items_found') }}</h3>
                <p class="mt-2 text-sm text-gray-500">{{ __('explore.try_adjusting') }}</p>
                <a href="{{ route('explore.outdoor-adventure.index') }}"
                   class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                    {{ __('explore.reset_filters') }}
                </a>
            </div>
        @endif
    </section>

</div>

{{-- Swiper JS --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.querySelector('.adventureFeaturedSlider')) {
            new Swiper('.adventureFeaturedSlider', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '#advNextBtn',
                    prevEl: '#advPrevBtn',
                },
                slidesPerView: 1,
                spaceBetween: 16,
            });
        }
    });
</script>

@endsection
