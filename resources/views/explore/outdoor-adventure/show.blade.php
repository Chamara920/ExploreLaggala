@extends('layouts.public')

@section('title', ($translation?->title ?? 'Outdoor & Adventure') . ' — Explore Laggala')

@section('content')

{{-- Swiper CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
{{-- Leaflet CSS if coordinates exist --}}
@if($item->latitude && $item->longitude)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endif

<style>
    .itemThumbSwiper .swiper-slide-thumb-active {
        opacity: 1 !important;
        border-color: #059669 !important;
        box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.5);
    }
</style>

<div class="bg-white">

    {{-- ============================================================
         1. TOP GALLERY SECTION (Slider if > 1 image, max 5)
         ============================================================ --}}
    <section class="bg-slate-950 py-8 lg:py-10" style="background-color: #020617;">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb Navigation --}}
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-400 mb-6">
                <a href="{{ route('explore.index') }}" class="hover:text-emerald-400 transition">Explore</a>
                <span>/</span>
                <a href="{{ route('explore.outdoor-adventure.index') }}" class="hover:text-emerald-400 transition">{{ __('explore.adventure_title') }}</a>
                <span>/</span>
                <span class="text-white truncate max-w-xs sm:max-w-md">{{ $translation?->title }}</span>
            </nav>

            @if($item->images->count() > 1)
                {{-- Multiple Images: Swiper Slider with Thumbnail Strip (Max 5 images) --}}
                <div class="relative">
                    <div class="swiper itemGallerySwiper rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl bg-black">
                        <div class="swiper-wrapper">
                            @foreach($item->images as $image)
                                <div class="swiper-slide flex items-center justify-center bg-black/40">
                                    <div class="relative w-full h-[360px] sm:h-[480px] lg:h-[560px]">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                             alt="{{ $image->caption ?? $translation?->title }}"
                                             class="w-full h-full object-cover">

                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20 pointer-events-none"></div>

                                        @if($image->caption)
                                            <div class="absolute bottom-6 left-6 right-20 z-10">
                                                <p class="text-xs sm:text-sm font-medium text-white bg-black/50 backdrop-blur-md px-3.5 py-1.5 rounded-xl inline-block">
                                                    {{ $image->caption }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Counter Badge --}}
                        <div class="absolute top-4 right-4 z-20 flex items-center gap-2 pointer-events-none">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-black/60 backdrop-blur-md text-white text-xs font-semibold border border-white/20 shadow-lg">
                                <i class="fas fa-camera text-emerald-400"></i>
                                <span class="gallery-counter">1 / {{ $item->images->count() }}</span>
                            </span>
                        </div>

                        {{-- Nav Buttons --}}
                        <button type="button" class="dest-swiper-prev absolute top-1/2 left-4 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/50 hover:bg-emerald-600 text-white backdrop-blur-md border border-white/20 transition flex items-center justify-center shadow-lg" aria-label="Previous">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </button>
                        <button type="button" class="dest-swiper-next absolute top-1/2 right-4 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/50 hover:bg-emerald-600 text-white backdrop-blur-md border border-white/20 transition flex items-center justify-center shadow-lg" aria-label="Next">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </button>
                    </div>

                    {{-- Thumbnail Strip Slider --}}
                    <div class="swiper itemThumbSwiper mt-4 rounded-xl overflow-hidden">
                        <div class="swiper-wrapper">
                            @foreach($item->images as $image)
                                <div class="swiper-slide cursor-pointer opacity-50 hover:opacity-100 transition rounded-xl overflow-hidden border-2 border-transparent">
                                    <div class="h-16 sm:h-20 w-full overflow-hidden rounded-lg bg-gray-800">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                             alt="{{ $image->caption ?? $translation?->title }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            @elseif($item->images->count() === 1)
                @php $singleImage = $item->images->first(); @endphp
                <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl shadow-2xl h-[360px] sm:h-[480px] lg:h-[540px] bg-black">
                    <img src="{{ asset('storage/' . $singleImage->image_path) }}"
                         alt="{{ $singleImage->caption ?? $translation?->title }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent pointer-events-none"></div>

                    @if($singleImage->caption)
                        <div class="absolute bottom-6 left-6 right-6">
                            <p class="text-xs sm:text-sm font-medium text-white bg-black/50 backdrop-blur-md px-3.5 py-1.5 rounded-xl inline-block">
                                {{ $singleImage->caption }}
                            </p>
                        </div>
                    @endif
                </div>

            @else
                <div class="flex h-64 sm:h-80 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-950 via-teal-950 to-black border border-gray-800 text-gray-400"
                     style="background: linear-gradient(135deg, #022c22 0%, #042f2e 50%, #000000 100%); background-color: #022c22;">
                    <div class="text-center">
                        <i class="fas fa-mountain text-5xl mb-3 text-emerald-500/50"></i>
                        <p class="text-sm font-medium text-gray-300">{{ $translation?->title }}</p>
                    </div>
                </div>
            @endif

        </div>
    </section>

    {{-- ============================================================
         2. MAIN CONTENT & SIDEBAR
         ============================================================ --}}
    <section class="py-12 sm:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                {{-- Left / Main Column --}}
                <div class="lg:col-span-8">

                    {{-- Badges & Title --}}
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        @if($item->category)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-900">
                                <i class="fas fa-tag text-[10px]"></i>
                                {{ $item->category->name }}
                            </span>
                        @endif

                        @if($item->featured)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                ⭐ Featured
                            </span>
                        @endif

                        @if($item->review_count > 0)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                <i class="fas fa-star text-amber-500"></i>
                                {{ $item->average_rating }} ({{ $item->review_count }})
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        {{ $translation?->title }}
                    </h1>

                    @if($translation?->location_name)
                        <p class="mt-3 text-base sm:text-lg text-emerald-700 font-medium flex items-center gap-2">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $translation->location_name }}</span>
                        </p>
                    @endif

                    @if($translation?->short_description)
                        <div class="mt-6 p-5 rounded-2xl bg-emerald-50/60 border border-emerald-200/60 text-emerald-950 font-medium leading-relaxed">
                            {{ $translation->short_description }}
                        </div>
                    @endif

                    {{-- Full Rich Description --}}
                    @if($translation?->description)
                        <div class="prose prose-lg prose-emerald max-w-none mt-8 text-gray-700 leading-relaxed">
                            {!! $translation->description !!}
                        </div>
                    @endif

                    {{-- Interactive Map Section (Leaflet) if coordinates exist --}}
                    @if($item->latitude && $item->longitude)
                        <div class="mt-12 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                        <i class="fas fa-map-marked-alt text-base"></i>
                                    </div>
                                    <h2 class="text-xl font-bold text-gray-900">{{ __('explore.view_on_map') }}</h2>
                                </div>

                                <a href="https://www.google.com/maps/search/?api=1&query={{ $item->latitude }},{{ $item->longitude }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-semibold border border-emerald-200 transition">
                                    <span>{{ __('explore.open_in_maps') }}</span>
                                    <i class="fas fa-external-link-alt text-[10px]"></i>
                                </a>
                            </div>

                            {{-- Leaflet Map Container --}}
                            <div id="adventureMap" class="w-full h-72 sm:h-96 rounded-2xl overflow-hidden border border-gray-100 shadow-inner z-0"></div>

                            <p class="mt-3 text-xs text-gray-500 flex items-center gap-2">
                                <i class="fas fa-compass text-emerald-600"></i>
                                <span>{{ __('explore.coordinates') }}: {{ $item->latitude }}, {{ $item->longitude }}</span>
                            </p>
                        </div>
                    @endif

                    {{-- You May Also Like / Related Articles Section --}}
                    @if(isset($relatedItems) && $relatedItems->count() > 0)
                        <div class="mt-12 rounded-3xl border border-emerald-200/80 bg-gradient-to-b from-emerald-50/40 via-white to-white p-6 sm:p-7 shadow-sm">
                            <div class="flex items-center justify-between pb-4 border-b border-emerald-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-md">
                                        <i class="fas fa-hiking text-base"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 leading-tight">
                                            {{ __('explore.related_spots') }}
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ app()->getLocale() === 'si' ? 'ලග්ගල අවට තවත් සුන්දර වික්‍රමාන්විත සංචාරක ඉසව් ගවේෂණය කරන්න' : (app()->getLocale() === 'ta' ? 'லக்கலவைச் சுற்றியுள்ள பிற சாகச சுற்றுலா இடங்களை ஆராயுங்கள்' : 'Explore more outdoor and adventure spots around Laggala') }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('explore.outdoor-adventure.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 transition hidden sm:inline-flex items-center gap-1">
                                    <span>{{ __('explore.view_all_places') ?? 'View All' }}</span>
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>

                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($relatedItems as $rel)
                                    @php
                                        $relTrans = $rel->translationFor();
                                        $relCover = $rel->coverImage();
                                        $relSlug = $relTrans?->slug ?? $rel->id;
                                    @endphp
                                    <div class="group flex flex-col rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-sm hover:shadow-md hover:border-emerald-300 transition duration-200">
                                        <div class="relative h-44 w-full bg-slate-100 overflow-hidden" style="height: 176px;">
                                            @if($relCover)
                                                <img src="{{ asset('storage/' . $relCover->image_path) }}"
                                                     alt="{{ $relTrans?->title }}"
                                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                                     style="height: 176px; width: 100%; object-fit: cover;">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-emerald-50 text-emerald-500" style="height: 176px;">
                                                    <i class="fas fa-mountain text-3xl"></i>
                                                </div>
                                            @endif

                                            @if($rel->category)
                                                <div class="absolute top-2.5 right-2.5 z-10">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-black/60 backdrop-blur-sm text-white shadow">
                                                        {{ $rel->category->name }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="p-4 flex-1 flex flex-col justify-between">
                                            <div>
                                                <h4 class="font-bold text-gray-900 group-hover:text-emerald-700 transition line-clamp-1 text-base">
                                                    <a href="{{ route('explore.outdoor-adventure.show', $relSlug) }}">
                                                        {{ $relTrans?->title }}
                                                    </a>
                                                </h4>

                                                @if($relTrans?->location_name)
                                                    <p class="mt-1 text-xs text-emerald-700 font-medium flex items-center gap-1.5 line-clamp-1">
                                                        <i class="fas fa-map-marker-alt text-[11px]"></i>
                                                        <span>{{ $relTrans->location_name }}</span>
                                                    </p>
                                                @endif

                                                @if($relTrans?->short_description)
                                                    <p class="mt-2 text-xs text-gray-600 line-clamp-2 leading-relaxed">
                                                        {{ $relTrans->short_description }}
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                                                <a href="{{ route('explore.outdoor-adventure.show', $relSlug) }}"
                                                   class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-800 transition">
                                                    <span>{{ __('explore.view_details') ?? 'View Spot' }}</span>
                                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                                </a>

                                                @if($rel->latitude && $rel->longitude)
                                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $rel->latitude }},{{ $rel->longitude }}"
                                                       target="_blank"
                                                       rel="noopener noreferrer"
                                                       class="text-gray-400 hover:text-emerald-600 transition"
                                                       title="View on Map">
                                                        <i class="fas fa-map-pin text-xs"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                {{-- Right / Sidebar Column --}}
                <div class="lg:col-span-4 space-y-6">

                    {{-- Quick Details Card --}}
                    <div class="rounded-3xl border border-gray-200 bg-gray-50 p-6">
                        <h3 class="text-lg font-bold text-gray-900 pb-3 border-b border-gray-200">
                            Quick Information
                        </h3>

                        <dl class="mt-4 space-y-4 text-sm">
                            @if($translation?->location_name)
                                <div>
                                    <dt class="font-semibold text-gray-500 text-xs uppercase tracking-wider">{{ __('explore.location') }}</dt>
                                    <dd class="mt-1 font-medium text-gray-900">{{ $translation->location_name }}</dd>
                                </div>
                            @endif

                            @if($item->category)
                                <div>
                                    <dt class="font-semibold text-gray-500 text-xs uppercase tracking-wider">{{ __('explore.categories') }}</dt>
                                    <dd class="mt-1 font-medium text-gray-900">{{ $item->category->name }}</dd>
                                </div>
                            @endif

                            @if($item->latitude && $item->longitude)
                                <div>
                                    <dt class="font-semibold text-gray-500 text-xs uppercase tracking-wider">{{ __('explore.coordinates') }}</dt>
                                    <dd class="mt-1 font-mono text-xs text-gray-700">{{ $item->latitude }}, {{ $item->longitude }}</dd>
                                </div>
                            @endif

                            <div>
                                <dt class="font-semibold text-gray-500 text-xs uppercase tracking-wider">Overall Rating</dt>
                                <dd class="mt-1 flex items-center gap-2">
                                    <span class="text-amber-500 font-bold text-base">★ {{ $item->average_rating }}</span>
                                    <span class="text-gray-500 text-xs">({{ $item->review_count }} {{ $item->review_count === 1 ? 'review' : 'reviews' }})</span>
                                </dd>
                            </div>
                        </dl>

                        {{-- Action Button --}}
                        <div class="mt-6 pt-5 border-t border-gray-200 space-y-2.5">
                            @if($item->latitude && $item->longitude)
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $item->latitude }},{{ $item->longitude }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm transition shadow-sm">
                                    <i class="fas fa-directions"></i>
                                    <span>Get Directions</span>
                                </a>
                            @endif

                            <a href="{{ route('explore.outdoor-adventure.index') }}"
                               class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 font-semibold text-sm transition">
                                <i class="fas fa-arrow-left text-xs"></i>
                                <span>{{ __('explore.back_to_list') }}</span>
                            </a>
                        </div>
                    </div>

                    {{-- Related Spots --}}
                    @if(isset($relatedItems) && $relatedItems->count() > 0)
                        <div class="rounded-3xl border border-gray-200 bg-white p-6">
                            <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('explore.related_spots') }}</h3>
                            <div class="space-y-4">
                                @foreach($relatedItems as $rel)
                                    @php
                                        $relTrans = $rel->translationFor();
                                        $relCover = $rel->coverImage();
                                        $relSlug = $relTrans?->slug ?? $rel->id;
                                    @endphp
                                    <a href="{{ route('explore.outdoor-adventure.show', $relSlug) }}" class="flex items-center gap-3 group">
                                        <div class="rounded-xl overflow-hidden bg-gray-900 flex-shrink-0" style="width: 56px; height: 56px; min-width: 56px; max-width: 56px;">
                                            @if($relCover)
                                                <img src="{{ asset('storage/' . $relCover->image_path) }}" alt="{{ $relTrans?->title }}" class="w-full h-full object-cover group-hover:scale-105 transition" style="width: 56px; height: 56px; object-fit: cover;">
                                            @else
                                                <div class="w-full h-full bg-emerald-900/40 flex items-center justify-center text-emerald-400" style="width: 56px; height: 56px;">
                                                    <i class="fas fa-mountain text-xs"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-semibold text-gray-900 group-hover:text-emerald-600 transition truncate">
                                                {{ $relTrans?->title }}
                                            </h4>
                                            @if($relTrans?->location_name)
                                                <p class="text-xs text-gray-500 truncate">{{ $relTrans->location_name }}</p>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </section>

    {{-- ============================================================
         3. REVIEWS & RATINGS SECTION
         ============================================================ --}}
    <section class="border-t border-gray-200 bg-slate-50/70 py-14 lg:py-20" id="reviews-section">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if(session('review_success'))
                <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 text-sm">
                    <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                    <span>{{ session('review_success') }}</span>
                </div>
            @endif

            @if(session('review_deleted'))
                <div class="mb-8 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 flex items-center gap-3 text-sm">
                    <i class="fas fa-trash-alt text-red-500"></i>
                    <span>{{ session('review_deleted') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                {{-- Left: Approved Reviews List --}}
                <div class="lg:col-span-7">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">
                                {{ __('explore.reviews_title') }}
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $item->review_count }} {{ $item->review_count === 1 ? 'review' : 'reviews' }} published
                            </p>
                        </div>

                        @if($item->review_count > 0)
                            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-2xl border border-gray-200 shadow-sm">
                                <span class="text-2xl font-black text-amber-500">{{ $item->average_rating }}</span>
                                <div class="text-amber-400 text-xs">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= round($item->average_rating) ? '' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($item->approvedReviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($item->approvedReviews as $review)
                                <article class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-sm">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm">
                                                {{ strtoupper(substr($review->user?->name ?? 'V', 0, 1)) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900 text-sm sm:text-base">
                                                    {{ $review->user?->name ?? 'Visitor' }}
                                                </h4>
                                                <p class="text-xs text-gray-400">
                                                    {{ $review->created_at?->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center gap-1.5">
                                                <div class="flex items-center text-lg leading-none" title="{{ (int) $review->rating }} of 5 Stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= (int) $review->rating)
                                                            <span class="text-amber-400">★</span>
                                                        @else
                                                            <span class="text-gray-300">☆</span>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-xs font-bold text-amber-800 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full ml-1">
                                                    {{ (int) $review->rating }}/5
                                                </span>
                                            </div>

                                            {{-- Admin / Super Admin Delete Action --}}
                                            @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'super_admin']))
                                                <form method="POST" action="{{ route('explore.reviews.destroy', $review->id) }}" onsubmit="return confirm('{{ __('explore.confirm_delete_review') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-500 hover:text-red-700 text-xs px-2 py-1 rounded-md hover:bg-red-50 transition border border-red-200"
                                                            title="{{ __('explore.delete_review') }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                    @if($review->comment)
                                        <p class="mt-4 text-sm text-gray-700 leading-relaxed">
                                            {{ $review->comment }}
                                        </p>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center">
                            <i class="far fa-comments text-gray-300 text-4xl mb-3"></i>
                            <p class="text-sm text-gray-500">{{ __('explore.no_reviews') }}</p>
                        </div>
                    @endif
                </div>

                {{-- Right: Submit Review Form --}}
                <div class="lg:col-span-5">
                    <div class="rounded-3xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm">
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $userReview ? __('explore.your_review') : __('explore.write_review') }}
                        </h3>

                        @auth
                            {{-- User Review Status Banner --}}
                            @if($userReview)
                                <div class="mt-4 p-3 rounded-xl border text-xs {{ $userReview->status === 'approved' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
                                    @if($userReview->status === 'pending')
                                        <i class="fas fa-clock mr-1 text-amber-600"></i>
                                        <strong>{{ __('explore.pending_approval_badge') }}:</strong> Your review is currently awaiting moderation by an administrator. You can update it below.
                                    @elseif($userReview->status === 'approved')
                                        <i class="fas fa-check-circle mr-1 text-emerald-600"></i>
                                        <strong>{{ __('explore.published_badge') }}:</strong> Your review is published. Updating will submit for re-approval.
                                    @else
                                        <i class="fas fa-info-circle mr-1"></i>
                                        You can update your review below.
                                    @endif
                                </div>
                            @endif

                            <form method="POST" action="{{ route('explore.reviews.store', $item->id) }}" class="mt-6 space-y-5">
                                @csrf

                                {{-- Star Rating Selector --}}
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                        {{ __('explore.rate_experience') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center gap-2" id="starRatingContainer">
                                        @for($r = 1; $r <= 5; $r++)
                                            <button type="button"
                                                    onclick="selectRating({{ $r }})"
                                                    class="star-btn text-2xl transition-transform hover:scale-125 focus:outline-none {{ ($userReview?->rating ?? 5) >= $r ? 'text-amber-400' : 'text-gray-300' }}"
                                                    data-val="{{ $r }}">
                                                ★
                                            </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="selectedRatingInput" value="{{ old('rating', $userReview?->rating ?? 5) }}">
                                    @error('rating')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Comment Textarea --}}
                                <div>
                                    <label for="reviewComment" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                        Feedback & Comments
                                    </label>
                                    <textarea id="reviewComment"
                                              name="comment"
                                              rows="4"
                                              placeholder="{{ __('explore.review_comment_placeholder') }}"
                                              class="w-full rounded-2xl border border-gray-200 p-3 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition">{{ old('comment', $userReview?->comment ?? '') }}</textarea>
                                    @error('comment')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                        class="w-full py-3 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition shadow-md flex items-center justify-center gap-2">
                                    <i class="fas fa-paper-plane text-xs"></i>
                                    <span>{{ $userReview ? __('explore.update_review') : __('explore.submit_review') }}</span>
                                </button>
                            </form>
                        @else
                            {{-- Guest prompt --}}
                            <div class="mt-6 text-center py-6 px-4 bg-gray-50 rounded-2xl border border-gray-200">
                                <i class="fas fa-lock text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-600 mb-4">{{ __('explore.login_to_review') }}</p>
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition shadow-sm">
                                    <i class="fas fa-sign-in-alt text-xs"></i>
                                    <span>{{ __('explore.login_now') }}</span>
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>

            </div>

        </div>
    </section>

</div>

{{-- Swiper JS --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

{{-- Leaflet JS if coordinates exist --}}
@if($item->latitude && $item->longitude)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Swiper Gallery Initialization
        var thumbSwiper = new Swiper('.itemThumbSwiper', {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });

        var mainSwiper = new Swiper('.itemGallerySwiper', {
            spaceBetween: 10,
            navigation: {
                nextEl: '.dest-swiper-next',
                prevEl: '.dest-swiper-prev',
            },
            thumbs: {
                swiper: thumbSwiper,
            },
        });

        if (mainSwiper && document.querySelector('.gallery-counter')) {
            mainSwiper.on('slideChange', function () {
                var current = mainSwiper.realIndex + 1;
                var total = {{ $item->images->count() }};
                document.querySelector('.gallery-counter').textContent = current + ' / ' + total;
            });
        }

        // 2. Leaflet Map Initialization
        @if($item->latitude && $item->longitude)
            try {
                var lat = {{ $item->latitude }};
                var lng = {{ $item->longitude }};
                var map = L.map('adventureMap', {
                    scrollWheelZoom: false,
                }).setView([lat, lng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                var marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup("<b>{{ addslashes($translation?->title ?? 'Location') }}</b><br>{{ addslashes($translation?->location_name ?? '') }}").openPopup();
            } catch (err) {
                console.warn('Map initialization failed:', err);
            }
        @endif
    });

    // 3. Star Rating Interaction
    function selectRating(val) {
        document.getElementById('selectedRatingInput').value = val;
        var buttons = document.querySelectorAll('.star-btn');
        buttons.forEach(function (btn) {
            var btnVal = parseInt(btn.getAttribute('data-val'));
            if (btnVal <= val) {
                btn.classList.remove('text-gray-300');
                btn.classList.add('text-amber-400');
            } else {
                btn.classList.remove('text-amber-400');
                btn.classList.add('text-gray-300');
            }
        });
    }
</script>

@endsection
