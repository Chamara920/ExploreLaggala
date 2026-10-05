@extends('layouts.public')

@php
    $locale = app()->getLocale();
    $name = $translation?->name ?? 'Service Place';
    $address = $translation?->location_name ?? '';
    $hours = $translation?->operating_hours ?? '';
    $shortDesc = $translation?->short_description ?? '';
    $desc = $translation?->description ?? '';
    $facilities = $translation?->key_facilities ?? '';
    $sectionTitle = $sectionMeta["title_{$locale}"] ?? $sectionMeta['title_en'];
    $icon = $sectionMeta['icon'];
    $subCategories = $sectionMeta['sub_categories'] ?? [];
@endphp

@section('title', $name . ' — ' . $sectionTitle . ' Laggala')

@section('content')

{{-- Leaflet CSS if coordinates exist --}}
@if($place->latitude && $place->longitude)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endif

<div class="bg-slate-50 min-h-screen">

    {{-- ============================================================
         1. HERO COVER & PROFILE HEADER
         ============================================================ --}}
    <section class="relative bg-slate-950 text-white overflow-hidden">
        {{-- Background single photo blur overlay if available --}}
        @if($place->image_path)
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('storage/' . $place->image_path) }}"
                     alt="{{ $name }}"
                     class="w-full h-full object-cover opacity-20 filter blur-[2px] scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-slate-900/60"></div>
            </div>
        @else
            <div class="absolute inset-0 z-0 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950">
                <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            </div>
        @endif

        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
            
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-400 mb-6 flex-wrap">
                <a href="{{ route('services.institutions.index') }}" class="hover:text-emerald-400 transition">Services</a>
                <span>/</span>
                <a href="{{ route('services.places.section', $section) }}" class="hover:text-emerald-400 transition">{{ $sectionTitle }}</a>
                <span>/</span>
                <span class="text-white truncate max-w-xs sm:max-w-md font-medium">{{ $name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                {{-- Single Photo Preview (Left) --}}
                <div class="lg:col-span-5">
                    <div class="rounded-3xl overflow-hidden border-2 border-white/20 shadow-2xl bg-slate-900 aspect-video lg:aspect-[4/3] relative group">
                        @if($place->image_path)
                            <img src="{{ asset('storage/' . $place->image_path) }}"
                                 alt="{{ $name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center text-slate-400">
                                <i class="bi {{ $icon }} text-6xl text-emerald-400/50 mb-3"></i>
                                <span class="text-xs uppercase tracking-wider font-semibold text-slate-400">{{ $sectionTitle }}</span>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3 flex flex-col gap-1">
                            @if($place->sub_category)
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-black/75 text-emerald-300 backdrop-blur-md border border-white/10 shadow">
                                    {{ $subCategories[$place->sub_category] ?? ucfirst($place->sub_category) }}
                                </span>
                            @endif
                            @if($place->is_24_hours)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider shadow bg-emerald-600 text-white">
                                    24/7 OPEN
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Profile Details (Right) --}}
                <div class="lg:col-span-7">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                            {{ $sectionMeta['badge'] }}
                        </span>
                        @if($place->featured)
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-400/30">
                                ⭐ Featured Place
                            </span>
                        @endif
                    </div>

                    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                        {{ $name }}
                    </h1>

                    @if($shortDesc)
                        <p class="mt-4 text-sm sm:text-base text-slate-300 leading-relaxed font-light">
                            {{ $shortDesc }}
                        </p>
                    @endif

                    {{-- Contact & Location Grid --}}
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                        @if($address)
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10 flex items-start gap-2.5">
                                <i class="fas fa-map-marker-alt text-emerald-400 mt-1 flex-shrink-0"></i>
                                <div>
                                    <span class="block text-[11px] uppercase tracking-wider text-slate-400 font-semibold">{{ __('services.address') }}</span>
                                    <span class="text-white font-medium">{{ $address }}</span>
                                </div>
                            </div>
                        @endif

                        @if($hours)
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10 flex items-start gap-2.5">
                                <i class="fas fa-clock text-amber-400 mt-1 flex-shrink-0"></i>
                                <div>
                                    <span class="block text-[11px] uppercase tracking-wider text-slate-400 font-semibold">{{ __('services.hours') }}</span>
                                    <span class="text-white font-medium">{{ $hours }}</span>
                                </div>
                            </div>
                        @endif

                        @if($place->phone)
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10 flex items-start gap-2.5">
                                <i class="fas fa-phone-alt text-blue-400 mt-1 flex-shrink-0"></i>
                                <div>
                                    <span class="block text-[11px] uppercase tracking-wider text-slate-400 font-semibold">{{ __('services.phone') }}</span>
                                    <a href="tel:{{ $place->phone }}" class="text-white font-medium hover:text-emerald-300">{{ $place->phone }}</a>
                                </div>
                            </div>
                        @endif

                        @if($place->emergency_hotline)
                            <div class="bg-red-500/20 backdrop-blur-md rounded-2xl p-3 border border-red-500/30 flex items-start gap-2.5">
                                <i class="fas fa-ambulance text-red-400 mt-1 flex-shrink-0"></i>
                                <div>
                                    <span class="block text-[11px] uppercase tracking-wider text-red-200 font-semibold">{{ __('services.emergency_hotline') }}</span>
                                    <a href="tel:{{ $place->emergency_hotline }}" class="text-white font-bold hover:underline">{{ $place->emergency_hotline }}</a>
                                </div>
                            </div>
                        @endif

                        @if($place->email)
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10 flex items-start gap-2.5">
                                <i class="fas fa-envelope text-purple-400 mt-1 flex-shrink-0"></i>
                                <div>
                                    <span class="block text-[11px] uppercase tracking-wider text-slate-400 font-semibold">{{ __('services.email') }}</span>
                                    <a href="mailto:{{ $place->email }}" class="text-white font-medium hover:text-emerald-300 truncate max-w-[180px] inline-block">{{ $place->email }}</a>
                                </div>
                            </div>
                        @endif

                        @if($place->website)
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10 flex items-start gap-2.5">
                                <i class="fas fa-globe text-teal-400 mt-1 flex-shrink-0"></i>
                                <div>
                                    <span class="block text-[11px] uppercase tracking-wider text-slate-400 font-semibold">{{ __('services.website') }}</span>
                                    <a href="{{ $place->website }}" target="_blank" rel="noopener" class="text-emerald-300 hover:underline font-medium">Visit Website &rarr;</a>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Rating summary --}}
                    @if($place->review_count > 0)
                        <div class="mt-6 flex items-center gap-3 bg-white/5 border border-white/10 px-4 py-2 rounded-2xl inline-flex">
                            <div class="text-amber-400 text-lg">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= round($place->average_rating) ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            <span class="font-bold text-white text-sm">{{ number_format($place->average_rating, 1) }} / 5.0</span>
                            <span class="text-slate-400 text-xs">({{ $place->review_count }} reviews)</span>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </section>

    {{-- ============================================================
         2. DETAILS & FACILITIES BODY
         ============================================================ --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 space-y-10">

        {{-- Description & Facilities Box --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-4">About {{ $name }}</h2>
                @if($desc)
                    <div class="prose prose-slate max-w-none text-sm sm:text-base text-slate-700 leading-relaxed">
                        {!! $desc !!}
                    </div>
                @else
                    <p class="text-sm text-slate-600 leading-relaxed">
                        {{ $shortDesc ?: 'Essential service provider located in the Laggala region providing reliable support to local residents, travelers, and the public.' }}
                    </p>
                @endif

                @if($facilities)
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <h3 class="text-base font-bold text-slate-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-600"></i>
                            <span>{{ __('services.facilities') }}</span>
                        </h3>
                        <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl text-xs sm:text-sm text-slate-700 whitespace-pre-line leading-relaxed">
                            {{ $facilities }}
                        </div>
                    </div>
                @endif
            </div>

            {{-- Quick Info Card (Right) --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-base font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">
                        Quick Information
                    </h3>
                    <dl class="space-y-3 text-xs sm:text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Service Category:</dt>
                            <dd class="font-bold text-slate-800">{{ $sectionTitle }}</dd>
                        </div>
                        @if($place->sub_category)
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Facility Type:</dt>
                                <dd class="font-bold text-slate-800">{{ $subCategories[$place->sub_category] ?? ucfirst($place->sub_category) }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Status:</dt>
                            <dd class="font-bold text-emerald-600">Active &amp; Operational</dd>
                        </div>
                        @if($place->is_24_hours)
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Availability:</dt>
                                <dd class="font-bold text-emerald-600">24/7 Service</dd>
                            </div>
                        @endif
                    </dl>

                    @if($place->latitude && $place->longitude)
                        <div class="mt-6 pt-4 border-t border-slate-100">
                            <a href="https://maps.google.com/?q={{ $place->latitude }},{{ $place->longitude }}"
                               target="_blank" rel="noopener"
                               class="w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white font-bold text-xs transition shadow-sm flex items-center justify-center gap-2">
                                <i class="fas fa-directions"></i>
                                <span>{{ __('services.open_in_google_maps') }}</span>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Related places if any --}}
                @if($relatedPlaces->isNotEmpty())
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                        <h3 class="text-sm font-bold text-slate-900 mb-3 uppercase tracking-wider text-slate-500">
                            {{ __('services.related_places') }}
                        </h3>
                        <div class="space-y-3">
                            @foreach($relatedPlaces as $rel)
                                @php
                                    $relTrans = $rel->translationFor($locale);
                                    $relSlug = $relTrans?->slug ?? $rel->id;
                                @endphp
                                <a href="{{ route('services.places.show', [$section, $relSlug]) }}"
                                   class="p-2.5 rounded-xl border border-slate-100 hover:border-emerald-300 hover:bg-slate-50 transition flex items-center gap-3 group">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0">
                                        @if($rel->image_path)
                                            <img src="{{ asset('storage/' . $rel->image_path) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400"><i class="bi {{ $icon }}"></i></div>
                                        @endif
                                    </div>
                                    <div class="truncate">
                                        <h4 class="font-bold text-xs text-slate-800 group-hover:text-emerald-700 truncate">{{ $relTrans?->name ?? 'Service' }}</h4>
                                        <span class="text-[11px] text-slate-400 truncate block">{{ $relTrans?->location_name }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ============================================================
             3. INTERACTIVE MAP SECTION
             ============================================================ --}}
        @if($place->latitude && $place->longitude)
            <section class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Premises Location</span>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ __('services.interactive_map') }}</h2>
                    </div>
                    <a href="https://maps.google.com/?q={{ $place->latitude }},{{ $place->longitude }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-bold transition">
                        <i class="fas fa-directions"></i>
                        <span>{{ __('services.open_in_google_maps') }}</span>
                    </a>
                </div>

                <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-2xs">
                    <div id="singlePlaceMap" class="w-full h-[360px] sm:h-[420px] z-10"></div>
                </div>
            </section>
        @endif

        {{-- ============================================================
             4. RATINGS & REVIEWS WITH ADMIN MODERATION & DELETION
             ============================================================ --}}
        <section class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between flex-wrap gap-4 mb-8 pb-6 border-b border-slate-100">
                <div>
                    <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Public Feedback</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ __('services.ratings_reviews') }}</h2>
                </div>

                {{-- Rating Summary Badge --}}
                <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 px-4 py-2 rounded-2xl">
                    <div class="text-amber-400 text-lg flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <span>{{ $i <= round($place->average_rating) ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                    <span class="font-extrabold text-slate-900 text-sm">{{ number_format($place->average_rating, 1) }} / 5.0</span>
                    <span class="text-slate-500 text-xs">({{ $place->review_count }} reviews)</span>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('review_success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                    <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
                    <span>{{ session('review_success') }}</span>
                </div>
            @endif

            @if(session('review_deleted'))
                <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center gap-3">
                    <i class="fas fa-trash-alt text-red-600 text-lg"></i>
                    <span>{{ session('review_deleted') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- Left: Approved Reviews --}}
                <div class="lg:col-span-7 space-y-4">
                    @if($place->approvedReviews->isNotEmpty())
                        @foreach($place->approvedReviews as $review)
                            <div class="border border-slate-200/80 rounded-2xl p-5 bg-slate-50/50 hover:bg-slate-50 transition">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm">
                                            {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900 text-sm">{{ $review->user->name ?? 'Citizen' }}</h4>
                                            <span class="text-[11px] text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    {{-- Star rating --}}
                                    <div class="flex items-center gap-1">
                                        <div class="text-sm">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="{{ $i <= $review->rating ? 'text-amber-400 font-bold' : 'text-gray-300' }}">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                            @endfor
                                        </div>
                                        <span class="text-xs font-bold text-slate-700 ml-1">({{ $review->rating }}/5)</span>
                                    </div>
                                </div>

                                @if($review->comment)
                                    <p class="mt-3 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                                        {{ $review->comment }}
                                    </p>
                                @endif

                                {{-- Admin Delete Button --}}
                                @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'super_admin']))
                                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-end">
                                        <form method="POST" action="{{ route('services.places.reviews.destroy', $review->id) }}" onsubmit="return confirm('{{ __('services.confirm_delete_review') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700 text-xs font-semibold flex items-center gap-1.5 transition">
                                                <i class="fas fa-trash-alt text-[10px]"></i>
                                                <span>{{ __('services.delete_review') }}</span>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center bg-slate-50/50">
                            <i class="far fa-comments text-3xl text-slate-300 mb-2"></i>
                            <h4 class="font-bold text-slate-700 text-sm">{{ __('services.no_reviews_yet') }}</h4>
                            <p class="text-xs text-slate-500 mt-1">{{ __('services.be_first_to_review') }}</p>
                        </div>
                    @endif
                </div>

                {{-- Right: Submit Review Form --}}
                <div class="lg:col-span-5">
                    <div class="rounded-2xl border border-slate-200 p-6 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-base mb-1">
                            {{ __('services.leave_feedback') }}
                        </h3>
                        <p class="text-xs text-slate-500 mb-5">
                            {{ __('services.pending_notice') }}
                        </p>

                        @auth
                            @if($userReview)
                                <div class="mb-4 p-3 rounded-xl border text-xs {{ $userReview->status === 'approved' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
                                    @if($userReview->status === 'pending')
                                        <i class="fas fa-clock mr-1 text-amber-600"></i>
                                        <strong>Pending Moderation:</strong> Your review is currently awaiting administrator review. You may update it below.
                                    @elseif($userReview->status === 'approved')
                                        <i class="fas fa-check-circle mr-1 text-emerald-600"></i>
                                        <strong>Approved:</strong> Your review is published publicly.
                                    @endif
                                </div>
                            @endif

                            <form method="POST" action="{{ route('services.places.reviews.store', $place->id) }}" class="space-y-4">
                                @csrf

                                {{-- Star Rating Selector --}}
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                        {{ __('services.your_rating') }}
                                    </label>
                                    @php
                                        $selectedRating = old('rating', $userReview?->rating ?? 5);
                                    @endphp
                                    <div class="flex items-center gap-2" id="placeStarContainer">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button"
                                                    class="place-star-btn text-2xl transition hover:scale-110 {{ $i <= $selectedRating ? 'text-amber-400' : 'text-gray-300' }}"
                                                    data-rating="{{ $i }}"
                                                    aria-label="{{ $i }} Stars">
                                                ★
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
                                    <label for="reviewComment" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                        {{ __('services.your_comment') }}
                                    </label>
                                    <textarea id="reviewComment"
                                              name="comment"
                                              rows="4"
                                              placeholder="{{ __('services.comment_placeholder') }}"
                                              class="w-full rounded-xl border border-slate-200 p-3.5 text-xs sm:text-sm bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition">{{ old('comment', $userReview?->comment ?? '') }}</textarea>
                                    @error('comment')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                        class="w-full py-3 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm transition shadow-md flex items-center justify-center gap-2">
                                    <i class="fas fa-paper-plane text-xs"></i>
                                    <span>{{ $userReview ? 'Update Feedback' : __('services.submit_feedback') }}</span>
                                </button>
                            </form>
                        @else
                            <div class="rounded-xl bg-white border border-slate-200 p-5 text-center">
                                <i class="fas fa-lock text-slate-400 text-2xl mb-2"></i>
                                <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                                    {{ __('services.login_to_review') }}
                                </p>
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-slate-900 hover:bg-black text-white text-xs font-bold transition shadow-sm">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <span>{{ __('services.sign_in') }}</span>
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>

            </div>
        </section>

    </div>

</div>

{{-- Leaflet JS --}}
@if($place->latitude && $place->longitude)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var lat = {{ (float) $place->latitude }};
            var lng = {{ (float) $place->longitude }};
            var map = L.map('singlePlaceMap').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup(`<strong>{{ addslashes($name) }}</strong><br>{{ addslashes($address) }}`).openPopup();
        });
    </script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var starButtons = document.querySelectorAll('.place-star-btn');
        var ratingInput = document.getElementById('ratingInput');

        starButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var rating = parseInt(this.getAttribute('data-rating'), 10);
                if (ratingInput) ratingInput.value = rating;

                starButtons.forEach(function(b) {
                    var bRating = parseInt(b.getAttribute('data-rating'), 10);
                    if (bRating <= rating) {
                        b.classList.remove('text-gray-300');
                        b.classList.add('text-amber-400');
                    } else {
                        b.classList.remove('text-amber-400');
                        b.classList.add('text-gray-300');
                    }
                });
            });
        });
    });
</script>

@endsection
