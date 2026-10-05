@extends('layouts.public')

@section('title', ($translation?->name ?? 'Destination') . ' — Explore Laggala')

@section('content')

{{-- Swiper CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

{{-- Leaflet CSS if coordinates exist --}}
@if($destination->latitude && $destination->longitude)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endif

<style>
    .destinationThumbSwiper .swiper-slide-thumb-active {
        opacity: 1 !important;
        border-color: #10b981 !important;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.5);
    }

    /* Leaflet popup & pin styling */
    .leaflet-popup-content-wrapper {
        padding: 0 !important;
        overflow: hidden !important;
        border-radius: 1rem !important;
        box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.2) !important;
    }
    .leaflet-popup-content {
        margin: 0 !important;
        line-height: 1.4 !important;
    }
    .custom-pin-node {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .custom-pin-node:hover {
        transform: scale(1.15) translateY(-3px);
        z-index: 1000 !important;
    }
    .custom-pin-bubble {
        width: 36px;
        height: 36px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
        border: 2.5px solid #ffffff;
    }
    .custom-pin-bubble i {
        transform: rotate(45deg);
        color: white;
        font-size: 14px;
    }
    .custom-pin-secondary {
        width: 28px;
        height: 28px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        border: 2px solid #ffffff;
    }
    .custom-pin-secondary i {
        transform: rotate(45deg);
        color: white;
        font-size: 11px;
    }
</style>

<div class="bg-white">

    {{-- Image Gallery & Slider --}}
    <section class="bg-slate-950 py-8 lg:py-10" style="background-color: #020617;">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if($destination->images->count() > 1)

                {{-- Multiple Images: Swiper Slider with Thumbs --}}
                <div class="relative">
                    {{-- Main Swiper Slider --}}
                    <div class="swiper destinationGallerySwiper rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl bg-black">
                        <div class="swiper-wrapper">
                            @foreach($destination->images as $image)
                                <div class="swiper-slide flex items-center justify-center bg-black/40">
                                    <div class="relative w-full h-[360px] sm:h-[480px] lg:h-[560px]">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                             alt="{{ $image->caption ?? $translation?->name }}"
                                             class="w-full h-full object-cover">

                                        {{-- Subtle bottom gradient for caption --}}
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20 pointer-events-none"></div>

                                        {{-- Image Caption if available --}}
                                        @if($image->caption)
                                            <div class="absolute bottom-6 left-6 right-20 z-10">
                                                <p class="text-sm sm:text-base font-medium text-white drop-shadow-md bg-black/40 backdrop-blur-md px-4 py-2 rounded-xl inline-block">
                                                    {{ $image->caption }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Top Badges: Counter --}}
                        <div class="absolute top-4 right-4 z-20 flex items-center gap-2 pointer-events-none">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-black/60 backdrop-blur-md text-white text-xs font-semibold border border-white/20 shadow-lg">
                                <i class="fas fa-camera text-emerald-400"></i>
                                <span class="gallery-counter">1 / {{ $destination->images->count() }}</span>
                            </span>
                        </div>

                        {{-- Navigation Buttons --}}
                        <button type="button" class="dest-swiper-prev absolute top-1/2 left-4 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/50 hover:bg-emerald-600 text-white backdrop-blur-md border border-white/20 transition flex items-center justify-center shadow-lg" aria-label="Previous image">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </button>
                        <button type="button" class="dest-swiper-next absolute top-1/2 right-4 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/50 hover:bg-emerald-600 text-white backdrop-blur-md border border-white/20 transition flex items-center justify-center shadow-lg" aria-label="Next image">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </button>
                    </div>

                    {{-- Thumbnail Strip Slider --}}
                    <div class="swiper destinationThumbSwiper mt-4 rounded-xl overflow-hidden">
                        <div class="swiper-wrapper">
                            @foreach($destination->images as $image)
                                <div class="swiper-slide cursor-pointer opacity-50 hover:opacity-100 transition rounded-xl overflow-hidden border-2 border-transparent">
                                    <div class="h-16 sm:h-20 w-full overflow-hidden rounded-lg bg-gray-800">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                             alt="{{ $image->caption ?? $translation?->name }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            @elseif($destination->images->count() === 1)

                {{-- Single Image Presentation --}}
                @php $singleImage = $destination->images->first(); @endphp
                <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl shadow-2xl h-[360px] sm:h-[480px] lg:h-[560px] bg-black">
                    <img src="{{ asset('storage/' . $singleImage->image_path) }}"
                         alt="{{ $singleImage->caption ?? $translation?->name }}"
                         class="w-full h-full object-cover">

                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent pointer-events-none"></div>

                    @if($singleImage->caption)
                        <div class="absolute bottom-6 left-6 right-6">
                            <p class="text-sm sm:text-base font-medium text-white drop-shadow-md bg-black/40 backdrop-blur-md px-4 py-2 rounded-xl inline-block">
                                {{ $singleImage->caption }}
                            </p>
                        </div>
                    @endif
                </div>

            @else

                {{-- Empty Image Placeholder --}}
                <div class="flex h-72 sm:h-96 items-center justify-center rounded-2xl bg-gray-900 border border-gray-800 text-gray-400">
                    <div class="text-center">
                        <i class="fas fa-image text-4xl mb-2 text-gray-600"></i>
                        <p class="text-sm">No images available for this destination</p>
                    </div>
                </div>

            @endif

        </div>

    </section>


    {{-- Main Content --}}
    <section class="mx-auto max-w-7xl px-6 py-12 lg:px-8">

        <div class="grid gap-12 lg:grid-cols-3">

            {{-- Details --}}
            <div class="lg:col-span-2">

                {{-- Breadcrumb --}}
                <div class="mb-6 text-sm text-gray-500">

                    <a href="{{ route('explore.index') }}"
                       class="hover:text-green-700">
                        Explore
                    </a>

                    <span class="mx-2">/</span>

                    <a href="{{ route('explore.destinations.index') }}"
                       class="hover:text-green-700">
                        Destinations
                    </a>

                    <span class="mx-2">/</span>

                    <span class="text-gray-800">
                        {{ $translation?->name }}
                    </span>

                </div>


                {{-- Title --}}
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                    {{ $translation?->name }}
                </h1>


                {{-- Location --}}
                @if($translation?->location_name)

                    <p class="mt-4 text-lg text-gray-500">
                        📍 {{ $translation->location_name }}
                    </p>

                @endif


                {{-- Rating --}}
                @if($destination->average_rating > 0)

                    <div class="mt-5 flex items-center gap-3">

                        <span class="text-xl text-amber-500">
                            ★
                        </span>

                        <span class="font-semibold text-gray-900">
                            {{ number_format($destination->average_rating, 1) }}
                        </span>

                        <span class="text-gray-500">
                            {{ $destination->review_count }} reviews
                        </span>

                    </div>

                @endif


                {{-- Short Description --}}
                @if($translation?->short_description)

                    <p class="mt-8 text-lg leading-8 text-gray-600">
                        {{ $translation->short_description }}
                    </p>

                @endif


                {{-- Description --}}
                @if($translation?->description)

                    <div class="prose prose-lg mt-8 max-w-none">
                        {!! $translation->description !!}
                    </div>

                @endif


                {{-- Interactive OpenStreetMap Section (Leaflet) if coordinates exist --}}
                @if($destination->latitude && $destination->longitude)
                    <div class="mt-12 rounded-3xl border border-gray-200 bg-white p-6 sm:p-7 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shadow-inner">
                                    <i class="fas fa-map-marked-alt text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 leading-tight">
                                        {{ __('destinations.view_on_map') }}
                                    </h2>
                                    @if($translation?->location_name)
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            <i class="fas fa-map-pin text-emerald-500 mr-1"></i>{{ $translation->location_name }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <a href="https://www.google.com/maps/search/?api=1&query={{ $destination->latitude }},{{ $destination->longitude }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm transition">
                                <span>{{ __('destinations.open_in_google_maps') }}</span>
                                <i class="fas fa-external-link-alt text-[10px]"></i>
                            </a>
                        </div>

                        {{-- Leaflet Map Container --}}
                        <div id="destinationMap" class="w-full h-80 sm:h-96 rounded-2xl overflow-hidden border border-gray-200 shadow-inner z-0"></div>

                        <div class="mt-3.5 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-500">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-compass text-emerald-600"></i>
                                <span>{{ __('destinations.coordinates') }}: <strong class="font-mono text-gray-700">{{ $destination->latitude }}, {{ $destination->longitude }}</strong></span>
                            </div>
                            @if(isset($nearbyDestinations) && $nearbyDestinations->isNotEmpty())
                                <span class="text-emerald-700 font-medium">
                                    <i class="fas fa-layer-group mr-1"></i>{{ app()->getLocale() === 'si' ? 'කිට්ටුවම ස්ථාන සිතියම මත සලකුණු කර ඇත' : (app()->getLocale() === 'ta' ? 'அருகிலுள்ள இடங்கள் வரைபடத்தில் காட்டப்பட்டுள்ளன' : 'Nearby spots plotted on map') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- 5 Nearest Tourist Destinations (කිට්ටුවම සංචාරක ස්ථාන 5) --}}
                @if(isset($nearbyDestinations) && $nearbyDestinations->isNotEmpty())
                    <div class="mt-12 rounded-3xl border border-emerald-100 bg-gradient-to-b from-emerald-50/40 to-white p-6 sm:p-7 shadow-sm">
                        <div class="flex items-center justify-between pb-4 border-b border-emerald-100/80">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-md">
                                    <i class="fas fa-location-arrow text-base"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 leading-tight">
                                        {{ __('destinations.nearby_destinations') }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ __('destinations.nearby_destinations_desc') }}
                                    </p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                5 {{ app()->getLocale() === 'si' ? 'ස්ථාන' : (app()->getLocale() === 'ta' ? 'இடங்கள்' : 'Spots') }}
                            </span>
                        </div>

                        {{-- Cards Grid --}}
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($nearbyDestinations as $nearby)
                                @php
                                    $nearbyTrans = $nearby->translationFor(app()->getLocale()) ?? $nearby->translationFor('en') ?? $nearby->translations->first();
                                    $nearbyCover = $nearby->images->first();
                                    $nearbySlug = $nearbyTrans?->slug ?? $nearby->translations->first()?->slug ?? $nearby->id;
                                    $nearbyCategory = $nearby->interests->first()?->name;
                                @endphp
                                <div class="group flex flex-col rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-sm hover:shadow-md hover:border-emerald-300 transition duration-200">
                                    {{-- Thumbnail --}}
                                    <div class="relative h-36 w-full bg-slate-100 overflow-hidden">
                                        @if($nearbyCover)
                                            <img src="{{ asset('storage/' . $nearbyCover->image_path) }}"
                                                 alt="{{ $nearbyTrans?->name }}"
                                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-emerald-50 text-emerald-400">
                                                <i class="fas fa-mountain text-3xl"></i>
                                            </div>
                                        @endif

                                        {{-- Distance Pill --}}
                                        @if($nearby->distance_km !== null)
                                            <div class="absolute top-2.5 left-2.5 z-10">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-black/70 backdrop-blur-md text-emerald-300 border border-emerald-400/30 shadow-md">
                                                    <i class="fas fa-route text-[10px]"></i>
                                                    {{ __('destinations.distance_km', ['distance' => $nearby->distance_km]) }}
                                                </span>
                                            </div>
                                        @endif

                                        @if($nearbyCategory)
                                            <div class="absolute top-2.5 right-2.5 z-10">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-white/90 backdrop-blur-sm text-gray-800 shadow">
                                                    {{ $nearbyCategory }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Content --}}
                                    <div class="p-4 flex-1 flex flex-col justify-between">
                                        <div>
                                            <h4 class="font-bold text-gray-900 group-hover:text-emerald-700 transition line-clamp-1 text-sm">
                                                <a href="{{ route('explore.destinations.show', $nearbySlug) }}">
                                                    {{ $nearbyTrans?->name ?? 'Destination' }}
                                                </a>
                                            </h4>

                                            @if($nearbyTrans?->location_name)
                                                <p class="mt-1 text-xs text-gray-500 flex items-center gap-1 line-clamp-1">
                                                    <i class="fas fa-map-marker-alt text-emerald-600 text-[10px]"></i>
                                                    <span>{{ $nearbyTrans->location_name }}</span>
                                                </p>
                                            @endif

                                            @if($nearbyTrans?->short_description)
                                                <p class="mt-2 text-xs text-gray-600 line-clamp-2 leading-relaxed">
                                                    {{ $nearbyTrans->short_description }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                                            <a href="{{ route('explore.destinations.show', $nearbySlug) }}"
                                               class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-800 group-hover:translate-x-0.5 transition">
                                                <span>{{ __('destinations.view_destination') }}</span>
                                                <i class="fas fa-arrow-right text-[10px]"></i>
                                            </a>

                                            @if($nearby->latitude && $nearby->longitude)
                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $nearby->latitude }},{{ $nearby->longitude }}"
                                                   target="_blank"
                                                   rel="noopener noreferrer"
                                                   title="{{ __('destinations.open_in_google_maps') }}"
                                                   class="text-gray-400 hover:text-emerald-600 p-1 transition">
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


            {{-- Sidebar --}}
            <aside>

                <div class="sticky top-8 rounded-2xl border border-gray-200 bg-gray-50 p-6">

                    <h2 class="text-xl font-bold text-gray-900">
                        {{ __('destinations.destination_info') }}
                    </h2>

                    @if($destination->featured)

                        <div class="mt-4 rounded-lg bg-green-100 px-4 py-3 text-sm font-semibold text-green-800">
                            ⭐ {{ __('destinations.featured_destination') }}
                        </div>

                    @endif


                    <div class="mt-6 space-y-4 text-sm">

                        @if($translation?->location_name)

                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ __('destinations.location') }}
                                </p>

                                <p class="mt-1 text-gray-600">
                                    {{ $translation->location_name }}
                                </p>
                            </div>

                        @endif


                        @if($destination->latitude && $destination->longitude)

                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ __('destinations.coordinates') }}
                                </p>

                                <p class="mt-1 text-gray-600">
                                    {{ $destination->latitude }},
                                    {{ $destination->longitude }}
                                </p>
                            </div>

                        @endif

                    </div>

                    {{-- Public Transport & Accessibility Card --}}
                    @if($destination->plannerDetails && ($destination->plannerDetails->nearest_bus_stop || $destination->plannerDetails->bus_routes || $destination->plannerDetails->transport_accessibility))
                        <div class="mt-6 rounded-2xl border border-blue-200 bg-blue-50/60 p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="flex items-center gap-2 font-bold text-gray-900">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                        <i class="bi bi-bus-front text-sm"></i>
                                    </span>
                                    {{ app()->getLocale() === 'si' ? 'පොදු ප්‍රවාහනය සහ පිවිසුම' : (app()->getLocale() === 'ta' ? 'பொதுப் போக்குவரத்து மற்றும் அணுகல்' : 'Public Transport & Access') }}
                                </h3>
                                <a href="{{ route('plan.transport.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                                    {{ app()->getLocale() === 'si' ? 'බස් කාලසටහන' : (app()->getLocale() === 'ta' ? 'அட்டவணை' : 'Schedules') }} →
                                </a>
                            </div>

                            <div class="mt-4 space-y-3 text-xs">
                                @if($destination->plannerDetails->nearest_bus_stop)
                                    <div>
                                        <span class="font-semibold text-gray-700 block text-[11px] uppercase tracking-wide">
                                            {{ app()->getLocale() === 'si' ? 'ළඟම බස් නැවතුම' : (app()->getLocale() === 'ta' ? 'அருகிலுள்ள பேருந்து நிறுத்தம்' : 'Nearest Bus Stop') }}
                                        </span>
                                        <p class="text-gray-900 font-bold mt-0.5">{{ $destination->plannerDetails->nearest_bus_stop }}</p>
                                    </div>
                                @endif

                                @if($destination->plannerDetails->bus_routes)
                                    <div>
                                        <span class="font-semibold text-gray-700 block text-[11px] uppercase tracking-wide">
                                            {{ app()->getLocale() === 'si' ? 'බස් මාර්ග' : (app()->getLocale() === 'ta' ? 'பேருந்து பாதைகள்' : 'Bus Routes') }}
                                        </span>
                                        <p class="text-gray-800 mt-0.5">{{ $destination->plannerDetails->bus_routes }}</p>
                                    </div>
                                @endif

                                @if($destination->plannerDetails->transport_accessibility)
                                    @php
                                        $accessLabel = match($destination->plannerDetails->transport_accessibility) {
                                            'direct_bus' => (app()->getLocale() === 'si' ? 'සෘජු බස් ප්‍රවේශය (නැවතුම අසල)' : (app()->getLocale() === 'ta' ? 'நேரடி பேருந்து அணுகல்' : 'Direct Bus Access')),
                                            'short_walk' => (app()->getLocale() === 'si' ? 'බස් නැවතුමේ සිට කෙටි දුර පාගමනක් (500m - 1km)' : (app()->getLocale() === 'ta' ? 'குறுகிய தூர நடை' : 'Short Walk from Bus Stop (500m - 1km)')),
                                            'tuk_tuk_from_bus' => (app()->getLocale() === 'si' ? 'බස් නැවතුමේ සිට ත්‍රිරෝද රථ / කුලී රථ අවශ්‍යයි' : (app()->getLocale() === 'ta' ? 'ஆட்டோ / டாக்சி தேவை' : 'Tuk-Tuk / Taxi Needed from Bus Stop')),
                                            'four_wheel_only' => (app()->getLocale() === 'si' ? '4WD / ජීප් රථ පමණක් ළඟා විය හැක' : (app()->getLocale() === 'ta' ? '4WD வாகனங்கள் மட்டும்' : '4WD / Trail Vehicle Only')),
                                            'trail_walk_only' => (app()->getLocale() === 'si' ? 'වනගත පාගමන් මංපෙත් පමණි' : (app()->getLocale() === 'ta' ? 'நடைபயணம் மட்டுமே' : 'Hiking Trail Walk Only')),
                                            default => $destination->plannerDetails->transport_accessibility
                                        };
                                    @endphp
                                    <div>
                                        <span class="font-semibold text-gray-700 block text-[11px] uppercase tracking-wide">
                                            {{ app()->getLocale() === 'si' ? 'ප්‍රවේශ පහසුකම' : (app()->getLocale() === 'ta' ? 'அணுகல் வகை' : 'Accessibility') }}
                                        </span>
                                        <p class="text-gray-800 font-medium mt-0.5">{{ $accessLabel }}</p>
                                    </div>
                                @endif

                                @if($destination->plannerDetails->recommended_vehicle)
                                    <div>
                                        <span class="font-semibold text-gray-700 block text-[11px] uppercase tracking-wide">
                                            {{ app()->getLocale() === 'si' ? 'සුදුසු වාහන' : (app()->getLocale() === 'ta' ? 'பரிந்துரைக்கப்பட்ட வாகனம்' : 'Vehicle Access') }}
                                        </span>
                                        <p class="text-gray-800 font-medium mt-0.5">
                                            {{ ucfirst(str_replace('_', ' ', $destination->plannerDetails->recommended_vehicle)) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Mobile Coverage Card --}}
                    <div class="mt-6 rounded-2xl border border-orange-200 bg-orange-50/50 p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="flex items-center gap-2 font-bold text-gray-900">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                    <i class="bi bi-reception-4 text-sm"></i>
                                </span>
                                {{ app()->getLocale() === 'si' ? 'දුරකථන සිග්නල් ආවරණය' : (app()->getLocale() === 'ta' ? 'மொபைல் சிக்னல்' : 'Mobile Coverage') }}
                            </h3>
                            <a href="{{ route('plan.coverage.index') }}" class="text-xs font-semibold text-orange-600 hover:text-orange-700">
                                {{ app()->getLocale() === 'si' ? 'සිතියම බලන්න' : (app()->getLocale() === 'ta' ? 'வரைபடம்' : 'View Map') }} →
                            </a>
                        </div>

                        @if(!empty($destination->plannerDetails?->mobile_signal_level))
                            @php
                                $dSig = $destination->plannerDetails->mobile_signal_level;
                                $dSigBadge = match($dSig) {
                                    'good' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                    'partial' => 'bg-amber-100 text-amber-800 border-amber-300',
                                    'poor' => 'bg-orange-100 text-orange-800 border-orange-300',
                                    'no_signal' => 'bg-red-100 text-red-800 border-red-300',
                                    default => 'bg-gray-100 text-gray-800 border-gray-300'
                                };
                                $dSigText = match($dSig) {
                                    'good' => (app()->getLocale() === 'si' ? 'ස්ථාවර 4G / 3G සිග්නල් ඇත' : (app()->getLocale() === 'ta' ? 'நல்ல 4G/3G சிக்னல்' : 'Good 4G/3G Coverage')),
                                    'partial' => (app()->getLocale() === 'si' ? 'සීමිත / දුර්වල සිග්නල් (ඇතැම් ස්ථාන වල පමණි)' : (app()->getLocale() === 'ta' ? 'பகுதி சிக்னல்' : 'Partial / Weak Signal (Selected spots)')),
                                    'poor' => (app()->getLocale() === 'si' ? 'ඉතා දුර්වල සිග්නල් (හදිසි ඇමතුම් පමණි)' : (app()->getLocale() === 'ta' ? 'மோசமான சிக்னல்' : 'Poor Signal (Emergency only)')),
                                    'no_signal' => (app()->getLocale() === 'si' ? 'සිග්නල් සම්පූර්ණයෙන්ම නොමැත' : (app()->getLocale() === 'ta' ? 'சிக்னல் இல்லை' : 'No Signal / Blackout Zone')),
                                    default => $dSig
                                };
                            @endphp
                            <div class="mt-3 rounded-xl border p-2.5 text-xs {{ $dSigBadge }}">
                                <div class="font-bold flex items-center gap-1.5">
                                    <i class="bi bi-broadcast"></i>
                                    <span>{{ $dSigText }}</span>
                                </div>
                                @if($destination->plannerDetails->best_mobile_networks)
                                    <p class="mt-1 text-[11px] opacity-90">
                                        <strong>{{ app()->getLocale() === 'si' ? 'ක්‍රියාකරුවන්' : 'Networks' }}:</strong> {{ $destination->plannerDetails->best_mobile_networks }}
                                    </p>
                                @endif
                                @if($destination->plannerDetails->connectivity_notes)
                                    <p class="mt-1 text-[11px] italic opacity-95">
                                        {{ $destination->plannerDetails->connectivity_notes }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        @if(isset($coverageReports) && $coverageReports->isNotEmpty())
                            <div class="mt-4 space-y-2.5">
                                @foreach($coverageReports->take(4) as $cov)
                                    @php
                                        $sigColors = [
                                            'excellent' => 'bg-green-100 text-green-700 border-green-200',
                                            'good'      => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'fair'      => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            'poor'      => 'bg-orange-100 text-orange-700 border-orange-200',
                                            'none'      => 'bg-red-100 text-red-700 border-red-200',
                                        ];
                                        $badgeClass = $sigColors[$cov->signal_strength] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                    @endphp
                                    <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-gray-800">{{ $cov->operator_label }}</span>
                                            <span class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[10px] font-semibold text-gray-600 uppercase">{{ $cov->coverage_type }}</span>
                                        </div>
                                        <span class="rounded-full border px-2 py-0.5 font-medium capitalize {{ $badgeClass }}">
                                            {{ $cov->signal_strength }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-3 text-xs text-gray-500">
                                No mobile coverage data reported for this location yet.
                            </p>
                        @endif

                        <div class="mt-4 border-t border-orange-100 pt-3">
                            <a href="{{ route('plan.coverage.index', ['destination' => $destination->id]) }}"
                               class="flex items-center justify-center gap-1.5 rounded-lg border border-orange-300 bg-white py-2 text-xs font-semibold text-orange-700 shadow-sm transition hover:bg-orange-50">
                                <i class="bi bi-plus-circle"></i>
                                Report Mobile Coverage Here
                            </a>
                        </div>
                    </div>

                    {{-- Nearby Destinations Sidebar Card --}}
                    @if(isset($nearbyDestinations) && $nearbyDestinations->isNotEmpty())
                        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5">
                            <div class="flex items-center justify-between pb-3 border-b border-emerald-200/70">
                                <h3 class="flex items-center gap-2 font-bold text-gray-900 text-sm">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                                        <i class="fas fa-compass text-xs"></i>
                                    </span>
                                    {{ __('destinations.nearby_destinations') }}
                                </h3>
                                <span class="rounded-full bg-emerald-100 text-emerald-800 px-2 py-0.5 text-[10px] font-bold">
                                    5 {{ app()->getLocale() === 'si' ? 'ස්ථාන' : (app()->getLocale() === 'ta' ? 'இடங்கள்' : 'Spots') }}
                                </span>
                            </div>

                            <div class="mt-3 divide-y divide-emerald-100">
                                @foreach($nearbyDestinations as $nearby)
                                    @php
                                        $sTrans = $nearby->translationFor(app()->getLocale()) ?? $nearby->translationFor('en') ?? $nearby->translations->first();
                                        $sCover = $nearby->images->first();
                                        $sSlug = $sTrans?->slug ?? $nearby->translations->first()?->slug ?? $nearby->id;
                                    @endphp
                                    <a href="{{ route('explore.destinations.show', $sSlug) }}"
                                       class="group flex items-center gap-3 py-2.5 hover:bg-emerald-100/50 rounded-xl px-2 transition">
                                        {{-- Mini thumb --}}
                                        <div class="h-11 w-11 flex-shrink-0 rounded-lg overflow-hidden bg-slate-100 border border-gray-200">
                                            @if($sCover)
                                                <img src="{{ asset('storage/' . $sCover->image_path) }}"
                                                     alt="{{ $sTrans?->name }}"
                                                     class="h-full w-full object-cover group-hover:scale-105 transition">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center bg-emerald-50 text-emerald-400">
                                                    <i class="fas fa-mountain text-xs"></i>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Info --}}
                                        <div class="min-w-0 flex-1">
                                            <p class="font-bold text-gray-900 text-xs truncate group-hover:text-emerald-700 transition">
                                                {{ $sTrans?->name ?? 'Destination' }}
                                            </p>
                                            <p class="text-[11px] text-gray-500 truncate">
                                                {{ $sTrans?->location_name ?? '' }}
                                            </p>
                                        </div>

                                        {{-- Distance Badge --}}
                                        @if($nearby->distance_km !== null)
                                            <span class="flex-shrink-0 rounded-md bg-emerald-100 text-emerald-800 border border-emerald-300/60 px-2 py-0.5 text-[10px] font-semibold">
                                                {{ $nearby->distance_km }} km
                                            </span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

            </aside>

        </div>

    </section>


    {{-- Reviews --}}
    <section class="border-t border-gray-200 bg-gray-50">

        <div class="mx-auto max-w-7xl px-6 py-14 lg:px-8">

            <div class="grid gap-10 lg:grid-cols-3">

                {{-- Reviews List --}}
                <div class="lg:col-span-2">

                    <h2 class="text-3xl font-bold text-gray-900">
                        Visitor Reviews
                    </h2>

                    @if($destination->approvedReviews->count())

                        <div class="mt-8 space-y-6">

                            @foreach($destination->approvedReviews as $review)

                                <article class="rounded-2xl border border-gray-200 bg-white p-6">

                                    <div class="flex items-start justify-between gap-4">

                                        <div>

                                            <p class="font-semibold text-gray-900">
                                                {{ $review->user?->name ?? 'Visitor' }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $review->created_at?->format('d M Y') }}
                                            </p>

                                        </div>

                                        <div class="text-amber-500">
                                            {{ str_repeat('★', $review->rating) }}
                                        </div>

                                    </div>

                                    @if($review->comment)

                                        <p class="mt-4 leading-7 text-gray-600">
                                            {{ $review->comment }}
                                        </p>

                                    @endif

                                </article>

                            @endforeach

                        </div>

                    @else

                        <div class="mt-8 rounded-2xl border border-dashed border-gray-300 bg-white p-8">

                            <p class="text-gray-500">
                                No reviews yet. Be the first to share your experience.
                            </p>

                        </div>

                    @endif

                </div>


                {{-- Write Review --}}
                <div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                        <h3 class="text-xl font-bold text-gray-900">
                            {{ ($userReview ?? null) ? 'Your Review' : 'Write a Review' }}
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            {{ ($userReview ?? null) ? 'You can update your rating or feedback for this destination.' : 'Share your experience about this destination.' }}
                        </p>

                        @if($userReview ?? null)
                            <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 p-3 text-xs text-blue-800">
                                @if($userReview->status === 'pending')
                                    Your review is currently <strong>pending approval</strong>. You can modify your review below at any time.
                                @elseif($userReview->status === 'approved')
                                    Your review is currently <strong>published</strong>. Any updates will be submitted for admin re-approval.
                                @elseif($userReview->status === 'rejected')
                                    Your review was <strong>rejected</strong>. You can update and resubmit it below.
                                @endif
                            </div>
                        @endif

                        {{-- Success Message --}}
                        @if(session('review_success'))

                            <div class="mt-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                                {{ session('review_success') }}
                            </div>

                        @endif


                        {{-- Validation Errors --}}
                        @if($errors->any())

                            <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4">

                                <p class="text-sm font-semibold text-red-800">
                                    Please correct the following:
                                </p>

                                <ul class="mt-2 list-disc pl-5 text-sm text-red-700">

                                    @foreach($errors->all() as $error)

                                        <li>{{ $error }}</li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        @auth

                            <form
                                method="POST"
                                action="{{ route('explore.destinations.reviews.store', $destination) }}"
                                class="mt-6 space-y-6"
                            >

                                @csrf



                                {{-- Rating --}}
                                <div
                                    x-data="{ rating: {{ old('rating', $userReview->rating ?? 0) }} }"
                                >

                                    <label class="block text-sm font-semibold text-gray-900">
                                        Your Rating
                                    </label>

                                    <div class="mt-3 flex items-center gap-1">

                                        @for($rating = 1; $rating <= 5; $rating++)

                                            <button
                                                type="button"
                                                @click="rating = {{ $rating }}"
                                                class="text-4xl leading-none transition focus:outline-none"
                                                :class="rating >= {{ $rating }}
                                                    ? 'text-amber-400'
                                                    : 'text-gray-300 hover:text-amber-300'"
                                                aria-label="Rate {{ $rating }} out of 5"
                                            >
                                                ★
                                            </button>

                                        @endfor

                                    </div>

                                    {{-- Actual value submitted to Laravel --}}
                                    <input
                                        type="hidden"
                                        name="rating"
                                        x-model="rating"
                                    >

                                    <div class="mt-2 text-sm text-gray-500">
                                        <span x-show="rating === 0">
                                            Select your rating
                                        </span>

                                        <span x-show="rating === 1">
                                            1 Star
                                        </span>

                                        <span x-show="rating === 2">
                                            2 Stars
                                        </span>

                                        <span x-show="rating === 3">
                                            3 Stars
                                        </span>

                                        <span x-show="rating === 4">
                                            4 Stars
                                        </span>

                                        <span x-show="rating === 5">
                                            5 Stars
                                        </span>
                                    </div>

                                    @error('rating')

                                        <p class="mt-2 text-sm text-red-600">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- Comment --}}
                                <div>

                                    <label
                                        for="comment"
                                        class="block text-sm font-semibold text-gray-900"
                                    >
                                        Your Review
                                    </label>

                                    <textarea
                                        id="comment"
                                        name="comment"
                                        rows="6"
                                        maxlength="2000"
                                        placeholder="Tell us about your experience..."
                                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    >{{ old('comment', $userReview->comment ?? '') }}</textarea>

                                    @error('comment')

                                        <p class="mt-2 text-sm text-red-600">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                    <p class="mt-2 text-xs text-gray-500">
                                        Maximum 2000 characters.
                                    </p>

                                </div>


                                {{-- Submit --}}
                                <button
                                    type="submit"
                                    class="w-full rounded-lg bg-green-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-green-700"
                                >
                                    {{ ($userReview ?? null) ? 'Update Review' : 'Submit Review' }}
                                </button>


                                <p class="text-xs leading-5 text-gray-500">
                                    Your review will be published after approval by the site administrator.
                                </p>

                            </form>

                        @else

                            {{-- Guest --}}
                            <div class="mt-6 rounded-xl bg-gray-50 p-5">

                                <p class="text-sm leading-6 text-gray-600">
                                    Please log in to share your review and rating.
                                </p>

                                <a
                                    href="{{ route('login') }}"
                                    class="mt-4 inline-flex rounded-lg bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700"
                                >
                                    Login to Review
                                </a>

                            </div>

                        @endauth

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>

{{-- Swiper JS Script for Destinations Show --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('.destinationGallerySwiper')) {
            const thumbSwiper = new Swiper('.destinationThumbSwiper', {
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
                breakpoints: {
                    640: { slidesPerView: 6, spaceBetween: 12 },
                    1024: { slidesPerView: 8, spaceBetween: 14 }
                }
            });

            const mainSwiper = new Swiper('.destinationGallerySwiper', {
                spaceBetween: 10,
                speed: 600,
                loop: false,
                navigation: {
                    nextEl: '.dest-swiper-next',
                    prevEl: '.dest-swiper-prev',
                },
                thumbs: {
                    swiper: thumbSwiper,
                },
                on: {
                    slideChange: function() {
                        const counter = document.querySelector('.gallery-counter');
                        if (counter) {
                            counter.textContent = (this.activeIndex + 1) + ' / ' + this.slides.length;
                        }
                    }
                }
            });
        }
    });
</script>

{{-- Leaflet JS if coordinates exist --}}
@if($destination->latitude && $destination->longitude)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                const mapEl = document.getElementById('destinationMap');
                if (!mapEl) return;

                const lat = {{ (float) $destination->latitude }};
                const lng = {{ (float) $destination->longitude }};

                const destMap = L.map('destinationMap', {
                    scrollWheelZoom: false,
                    zoomControl: true,
                }).setView([lat, lng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors'
                }).addTo(destMap);

                // Custom Primary Pin for Current Destination (Emerald Star)
                const primaryPin = L.divIcon({
                    className: 'custom-pin-container',
                    html: `
                        <div class="custom-pin-node">
                            <div class="custom-pin-bubble" style="background-color: #059669;">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    `,
                    iconSize: [36, 46],
                    iconAnchor: [18, 42],
                    popupAnchor: [0, -38]
                });

                const mainMarker = L.marker([lat, lng], { icon: primaryPin }).addTo(destMap);
                const popupContent = `
                    <div style="padding: 12px; font-family: inherit; min-width: 190px;">
                        <span style="display: inline-block; font-size: 10px; font-weight: 700; text-transform: uppercase; color: #059669; letter-spacing: 0.5px; margin-bottom: 3px;">
                            {{ __('destinations.destination_single') }}
                        </span>
                        <h4 style="font-weight: 700; font-size: 14px; margin: 0; color: #111827; line-height: 1.3;">
                            {{ addslashes($translation?->name ?? 'Destination') }}
                        </h4>
                        @if($translation?->location_name)
                            <p style="font-size: 11px; color: #6b7280; margin: 4px 0 8px;">
                                <i class="fas fa-map-marker-alt" style="color: #059669; margin-right: 4px;"></i>{{ addslashes($translation->location_name) }}
                            </p>
                        @endif
                        <div style="margin-top: 8px;">
                            <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}"
                               target="_blank"
                               rel="noopener noreferrer"
                               style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; font-size: 11px; font-weight: 600; color: #ffffff; background-color: #059669; border-radius: 8px; text-decoration: none;">
                                <span>{{ __('destinations.open_in_google_maps') }}</span>
                                <i class="fas fa-external-link-alt" style="font-size: 9px;"></i>
                            </a>
                        </div>
                    </div>
                `;
                mainMarker.bindPopup(popupContent).openPopup();

                // Secondary Pins for Nearby Destinations
                @if(isset($nearbyDestinations) && $nearbyDestinations->isNotEmpty())
                    @foreach($nearbyDestinations as $nearby)
                        @if($nearby->latitude && $nearby->longitude)
                            (function() {
                                const nLat = {{ (float) $nearby->latitude }};
                                const nLng = {{ (float) $nearby->longitude }};

                                const secondaryPin = L.divIcon({
                                    className: 'custom-pin-container',
                                    html: `
                                        <div class="custom-pin-node">
                                            <div class="custom-pin-secondary" style="background-color: #2563eb;">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                        </div>
                                    `,
                                    iconSize: [28, 36],
                                    iconAnchor: [14, 32],
                                    popupAnchor: [0, -28]
                                });

                                @php
                                    $nTrans = $nearby->translationFor(app()->getLocale()) ?? $nearby->translationFor('en') ?? $nearby->translations->first();
                                    $nSlug = $nTrans?->slug ?? $nearby->translations->first()?->slug ?? $nearby->id;
                                @endphp

                                const nMarker = L.marker([nLat, nLng], { icon: secondaryPin }).addTo(destMap);
                                const nPopup = `
                                    <div style="padding: 10px; font-family: inherit; min-width: 180px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; gap: 6px;">
                                            <span style="font-size: 10px; font-weight: 700; color: #2563eb;">{{ __('destinations.nearby_destinations') }}</span>
                                            @if($nearby->distance_km !== null)
                                                <span style="font-size: 10px; background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; padding: 1px 6px; border-radius: 4px; font-weight: 600; white-space: nowrap;">
                                                    {{ $nearby->distance_km }} km
                                                </span>
                                            @endif
                                        </div>
                                        <h5 style="font-weight: 700; font-size: 13px; margin: 0; color: #111827;">{{ addslashes($nTrans?->name ?? 'Destination') }}</h5>
                                        @if($nTrans?->location_name)
                                            <p style="font-size: 11px; color: #6b7280; margin: 3px 0 6px;">{{ addslashes($nTrans->location_name) }}</p>
                                        @endif
                                        <div style="margin-top: 8px;">
                                            <a href="{{ route('explore.destinations.show', $nSlug) }}"
                                               style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 8px; font-size: 11px; font-weight: 600; color: #1e40af; background: #dbeafe; border-radius: 6px; text-decoration: none;">
                                                <span>{{ __('destinations.view_destination') }}</span> →
                                            </a>
                                        </div>
                                    </div>
                                `;
                                nMarker.bindPopup(nPopup);
                            })();
                        @endif
                    @endforeach
                @endif

                setTimeout(function() {
                    destMap.invalidateSize();
                }, 300);
            } catch(e) {
                console.warn('Destination Map initialization error:', e);
            }
        });
    </script>
@endif

@endsection
