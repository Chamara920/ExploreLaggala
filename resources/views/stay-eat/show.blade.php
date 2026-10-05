@extends('layouts.public')

@section('title', ($translation?->title ?? __($sectionMeta['name_key'])) . ' — Explore Laggala')

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

@php
    $accentColor = match($section) {
        'accommodation' => 'blue',
        'restaurants-cafes' => 'orange',
        'local-food' => 'amber',
        'outdoor-dining' => 'teal',
        default => 'emerald',
    };
@endphp

<div class="bg-white">

    {{-- ============================================================
         1. TOP GALLERY SLIDER (If > 1 image, max 5)
         ============================================================ --}}
    <section class="bg-slate-950 py-8 lg:py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb Navigation --}}
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-400 mb-6 flex-wrap">
                <a href="{{ route('stay-eat.index') }}" class="hover:text-emerald-400 transition">Stay &amp; Eat</a>
                <span>/</span>
                <a href="{{ route('stay-eat.section', $section) }}" class="hover:text-emerald-400 transition">{{ __($sectionMeta['name_key']) }}</a>
                <span>/</span>
                <span class="text-white truncate max-w-xs sm:max-w-md font-medium">{{ $translation?->title }}</span>
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
                                                <p class="text-xs sm:text-sm font-medium text-white bg-black/60 backdrop-blur-md px-3.5 py-1.5 rounded-xl inline-block">
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

                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20 pointer-events-none"></div>

                    @if($singleImage->caption)
                        <div class="absolute bottom-6 left-6 z-10">
                            <p class="text-xs sm:text-sm font-medium text-white bg-black/60 backdrop-blur-md px-3.5 py-1.5 rounded-xl inline-block">
                                {{ $singleImage->caption }}
                            </p>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </section>

    {{-- ============================================================
         2. PLACE DETAILS & CONTENT SECTION
         ============================================================ --}}
    <section class="py-12 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12">

                {{-- Left: Main Info & Description --}}
                <div class="lg:col-span-8">

                    {{-- Badges & Title Header --}}
                    <div class="mb-6">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            @if($item->category)
                                <span class="px-3.5 py-1 rounded-full bg-{{ $accentColor }}-50 text-{{ $accentColor }}-800 border border-{{ $accentColor }}-200 text-xs font-bold shadow-sm">
                                    {{ $item->category->name }}
                                </span>
                            @endif

                            @if($item->featured)
                                <span class="px-3 py-1 rounded-full bg-emerald-600 text-white text-xs font-bold shadow-sm">
                                    ★ Featured
                                </span>
                            @endif

                            @if($item->price_range)
                                <span class="px-3 py-1 rounded-full bg-slate-900 text-white text-xs font-semibold">
                                    {{ $item->price_range }}
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight leading-tight">
                            {{ $translation?->title }}
                        </h1>

                        @if($translation?->location_name)
                            <p class="mt-2 text-sm sm:text-base text-gray-500 flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-{{ $accentColor }}-600"></i>
                                <span>{{ $translation->location_name }}</span>
                            </p>
                        @endif
                    </div>

                    {{-- Quick Spec Highlights Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-8">
                        @if($translation?->opening_hours)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-gray-200/80">
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">
                                    <i class="far fa-clock text-{{ $accentColor }}-500 mr-1"></i> {{ __('stay_eat.opening_hours') }}
                                </span>
                                <span class="text-xs sm:text-sm font-semibold text-gray-900">
                                    {{ $translation->opening_hours }}
                                </span>
                            </div>
                        @endif

                        @if($item->price_range)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-gray-200/80">
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">
                                    <i class="fas fa-tag text-emerald-500 mr-1"></i> {{ __('stay_eat.price_range') }}
                                </span>
                                <span class="text-xs sm:text-sm font-semibold text-gray-900">
                                    {{ $item->price_range }}
                                </span>
                            </div>
                        @endif

                        <div class="p-4 rounded-2xl bg-slate-50 border border-gray-200/80">
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">
                                <i class="fas fa-star text-amber-500 mr-1"></i> Ratings
                            </span>
                            <span class="text-xs sm:text-sm font-semibold text-gray-900 flex items-center gap-1">
                                <span class="text-amber-500">{{ $item->average_rating > 0 ? $item->average_rating : 'New' }}</span>
                                <span class="text-gray-400 text-xs">({{ $item->review_count }} {{ $item->review_count === 1 ? 'review' : 'reviews' }})</span>
                            </span>
                        </div>
                    </div>

                    {{-- Short Summary / Lead --}}
                    @if($translation?->short_description)
                        <div class="p-5 sm:p-6 rounded-2xl bg-{{ $accentColor }}-50/50 border border-{{ $accentColor }}-100 text-slate-800 text-sm sm:text-base leading-relaxed mb-8">
                            {{ $translation->short_description }}
                        </div>
                    @endif

                    {{-- Rich Detailed Description & Amenities --}}
                    @if($translation?->description)
                        <div class="prose max-w-none text-gray-700 leading-relaxed text-sm sm:text-base mb-12">
                            {!! $translation->description !!}
                        </div>
                    @endif

                </div>

                {{-- Right: Sidebar (Contact, Booking, Interactive Leaflet Map, Related) --}}
                <div class="lg:col-span-4 space-y-6">

                    {{-- Contact & Booking Card --}}
                    <div class="rounded-3xl border border-gray-200/80 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-phone-volume text-{{ $accentColor }}-600"></i>
                            <span>{{ __('stay_eat.contact') }}</span>
                        </h3>

                        <div class="space-y-3">
                            @if($item->phone)
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $item->phone) }}"
                                   class="flex items-center gap-3 p-3.5 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 transition font-bold text-sm">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm shadow-sm flex-shrink-0">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-xs text-emerald-600 font-normal block">{{ __('stay_eat.call_now') }}</span>
                                        <span class="truncate">{{ $item->phone }}</span>
                                    </div>
                                </a>
                            @endif

                            @if($item->website)
                                <a href="{{ $item->website }}" target="_blank" rel="noopener noreferrer"
                                   class="flex items-center gap-3 p-3.5 rounded-2xl bg-blue-50 hover:bg-blue-100 text-blue-800 transition font-bold text-sm">
                                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shadow-sm flex-shrink-0">
                                        <i class="fas fa-globe"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-xs text-blue-600 font-normal block">{{ __('stay_eat.website') }}</span>
                                        <span class="truncate">{{ __('stay_eat.visit_website') }}</span>
                                    </div>
                                </a>
                            @endif

                            @if($item->email)
                                <a href="mailto:{{ $item->email }}"
                                   class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 hover:bg-slate-100 text-slate-800 transition font-semibold text-sm">
                                    <div class="w-9 h-9 rounded-xl bg-slate-700 text-white flex items-center justify-center text-sm shadow-sm flex-shrink-0">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-xs text-slate-500 font-normal block">{{ __('stay_eat.email') }}</span>
                                        <span class="truncate text-xs">{{ $item->email }}</span>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Interactive Leaflet Map Card (Requirement 3) --}}
                    @if($item->latitude && $item->longitude)
                        <div class="rounded-3xl border border-gray-200/80 bg-white p-6 shadow-sm overflow-hidden">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-map-marked-alt text-{{ $accentColor }}-600"></i>
                                    <span>{{ __('stay_eat.view_on_map') }}</span>
                                </h3>
                                <span class="text-xs text-gray-400 font-mono">
                                    {{ number_format($item->latitude, 4) }}, {{ number_format($item->longitude, 4) }}
                                </span>
                            </div>

                            {{-- Leaflet Map Container --}}
                            <div id="placeMap" class="w-full h-56 rounded-2xl border border-gray-200 shadow-inner z-0"></div>

                            <div class="mt-4 pt-3 border-t border-gray-100">
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $item->latitude }},{{ $item->longitude }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-black text-white text-xs font-bold transition shadow-sm">
                                    <i class="fab fa-google text-red-400"></i>
                                    <span>{{ __('stay_eat.open_in_maps') }}</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Related Places in Same Section --}}
                    @if(isset($relatedItems) && $relatedItems->count() > 0)
                        <div class="rounded-3xl border border-gray-200/80 bg-white p-6 shadow-sm">
                            <h3 class="text-base font-bold text-gray-900 mb-4">
                                {{ __('stay_eat.related_places') }}
                            </h3>

                            <div class="space-y-4">
                                @foreach($relatedItems as $rel)
                                    @php
                                        $relTrans = $rel->translationFor($locale);
                                        $relCover = $rel->coverImage();
                                        $relSlug = $relTrans?->slug ?? $rel->id;
                                    @endphp
                                    <a href="{{ route('stay-eat.show', [$rel->section, $relSlug]) }}" class="flex items-center gap-3 group">
                                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-900 flex-shrink-0">
                                            @if($relCover)
                                                <img src="{{ asset('storage/' . $relCover->image_path) }}" alt="{{ $relTrans?->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                                            @else
                                                <div class="w-full h-full bg-slate-800 flex items-center justify-center text-gray-400">
                                                    <span>{{ $sectionMeta['icon'] }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-semibold text-gray-900 group-hover:text-{{ $accentColor }}-600 transition truncate">
                                                {{ $relTrans?->title }}
                                            </h4>
                                            @if($relTrans?->location_name)
                                                <p class="text-xs text-gray-500 truncate">{{ $relTrans->location_name }}</p>
                                            @endif
                                            @if($rel->price_range)
                                                <span class="text-[10px] text-emerald-600 font-bold block">{{ $rel->price_range }}</span>
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
         3. REVIEWS & RATINGS SECTION (Requirement 5)
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
                                {{ __('stay_eat.reviews_title') }}
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $item->review_count }} {{ $item->review_count === 1 ? 'review' : 'reviews' }} published
                            </p>
                        </div>

                        @if($item->review_count > 0)
                            <div class="flex items-center gap-2.5 bg-white px-4 py-2 rounded-2xl border border-gray-200 shadow-sm">
                                <span class="text-2xl font-black text-amber-500">{{ $item->average_rating }}</span>
                                <div class="flex items-center text-base leading-none">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($item->average_rating))
                                            <span class="text-amber-400">★</span>
                                        @else
                                            <span class="text-gray-300">☆</span>
                                        @endif
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
                                                {{ strtoupper(substr($review->user?->name ?? 'G', 0, 1)) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900 text-sm sm:text-base">
                                                    {{ $review->user?->name ?? 'Guest' }}
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

                                            {{-- Admin / Super Admin Delete Action (Requirement 5) --}}
                                            @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'super_admin']))
                                                <form method="POST" action="{{ route('stay-eat.reviews.destroy', $review->id) }}" onsubmit="return confirm('{{ __('stay_eat.confirm_delete_review') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-500 hover:text-red-700 text-xs px-2 py-1 rounded-md hover:bg-red-50 transition border border-red-200"
                                                            title="{{ __('stay_eat.delete_review') }}">
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
                            <p class="text-sm text-gray-500">{{ __('stay_eat.no_reviews') }}</p>
                        </div>
                    @endif
                </div>

                {{-- Right: Submit / Update Review Form --}}
                <div class="lg:col-span-5">
                    <div class="rounded-3xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm">
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $userReview ? __('stay_eat.your_review') : __('stay_eat.write_review') }}
                        </h3>

                        @auth
                            {{-- User Review Status Banner --}}
                            @if($userReview)
                                <div class="mt-4 p-3 rounded-xl border text-xs {{ $userReview->status === 'approved' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
                                    @if($userReview->status === 'pending')
                                        <i class="fas fa-clock mr-1 text-amber-600"></i>
                                        <strong>{{ __('stay_eat.pending_approval_badge') }}:</strong> Your review is currently awaiting administrator approval. You can update it below.
                                    @elseif($userReview->status === 'approved')
                                        <i class="fas fa-check-circle mr-1 text-emerald-600"></i>
                                        <strong>{{ __('stay_eat.approved_badge') }}:</strong> Your review is live and verified.
                                    @endif
                                </div>
                            @endif

                            <form method="POST" action="{{ route('stay-eat.reviews.store', $item->id) }}" class="mt-6 space-y-5">
                                @csrf

                                {{-- Star Rating Selector --}}
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                        {{ __('stay_eat.rate_place') }}
                                    </label>
                                    <div class="flex items-center gap-2" id="starRatingContainer">
                                        @php
                                            $selectedRating = old('rating', $userReview?->rating ?? 5);
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button"
                                                    class="star-btn text-2xl transition hover:scale-110 {{ $i <= $selectedRating ? 'text-amber-400' : 'text-gray-300' }}"
                                                    data-rating="{{ $i }}"
                                                    aria-label="{{ $i }} Stars">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        @endfor
                                        <input type="hidden" name="rating" id="ratingInput" value="{{ $selectedRating }}">
                                    </div>
                                    @error('rating')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Comment Textarea --}}
                                <div>
                                    <label for="reviewComment" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                        {{ __('stay_eat.your_review') }}
                                    </label>
                                    <textarea id="reviewComment"
                                              name="comment"
                                              rows="4"
                                              placeholder="{{ __('stay_eat.review_comment_placeholder') }}"
                                              class="w-full rounded-2xl border border-gray-200 p-4 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition">{{ old('comment', $userReview?->comment ?? '') }}</textarea>
                                    @error('comment')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                        class="w-full py-3.5 px-6 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                    <i class="fas fa-paper-plane text-xs"></i>
                                    <span>{{ $userReview ? __('stay_eat.update_review') : __('stay_eat.submit_review') }}</span>
                                </button>
                            </form>
                        @else
                            <div class="mt-6 rounded-2xl bg-gray-50 border border-gray-200 p-6 text-center">
                                <i class="fas fa-lock text-gray-400 text-3xl mb-3"></i>
                                <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                                    {{ __('stay_eat.login_to_review') }}
                                </p>
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-black text-white text-xs font-bold transition shadow-sm">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <span>{{ __('stay_eat.login_now') }}</span>
                                </a>
                            </div>
                        @endauth

                    </div>
                </div>

            </div>
        </div>
    </section>

</div>

{{-- Swiper Bundle & Leaflet JS Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

@if($item->latitude && $item->longitude)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // 1. Swiper Gallery (Requirement 4)
        if (document.querySelector('.itemGallerySwiper')) {
            const thumbSwiper = new Swiper('.itemThumbSwiper', {
                spaceBetween: 10,
                slidesPerView: 'auto',
                freeMode: true,
                watchSlidesProgress: true,
            });

            const gallerySwiper = new Swiper('.itemGallerySwiper', {
                spaceBetween: 10,
                loop: {{ $item->images->count() > 1 ? 'true' : 'false' }},
                navigation: {
                    prevEl: '.dest-swiper-prev',
                    nextEl: '.dest-swiper-next',
                },
                thumbs: {
                    swiper: thumbSwiper,
                },
                on: {
                    slideChange: function () {
                        const counter = document.querySelector('.gallery-counter');
                        if (counter) {
                            counter.textContent = `${this.realIndex + 1} / {{ $item->images->count() }}`;
                        }
                    }
                }
            });
        }

        // 2. Leaflet OpenStreetMap Initialization (Requirement 3)
        @if($item->latitude && $item->longitude)
            try {
                const lat = {{ $item->latitude }};
                const lng = {{ $item->longitude }};
                const map = L.map('placeMap', { scrollWheelZoom: false }).setView([lat, lng], 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                const marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup(`<strong>{{ addslashes($translation?->title ?? 'Place') }}</strong><br><small>{{ addslashes($translation?->location_name ?? '') }}</small>`).openPopup();
            } catch (err) {
                console.warn('Leaflet map initialization skipped', err);
            }
        @endif

        // 3. Interactive Star Rating Selector
        const ratingInput = document.getElementById('ratingInput');
        const starBtns = document.querySelectorAll('.star-btn');

        if (starBtns.length > 0 && ratingInput) {
            starBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    ratingInput.value = rating;

                    starBtns.forEach((b, idx) => {
                        if (idx < rating) {
                            b.classList.remove('text-gray-300');
                            b.classList.add('text-amber-400');
                        } else {
                            b.classList.remove('text-amber-400');
                            b.classList.add('text-gray-300');
                        }
                    });
                });
            });
        }

    });
</script>

@endsection
