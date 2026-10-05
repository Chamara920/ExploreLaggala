@extends('layouts.public')

@section('title', __('pages.about_title') . ' — Explore Laggala')

@section('content')

{{-- ============================================================
     HERO BANNER
============================================================ --}}
<section class="relative bg-gradient-to-br from-emerald-950 via-slate-900 to-teal-950 text-white py-14 lg:py-20 overflow-hidden">
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute inset-0 opacity-10 bg-cover bg-center pointer-events-none" style="background-image: url('{{ asset('images/knuckles-bg.jpg') }}');"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold uppercase tracking-wider mb-5">
            <i class="fas fa-info-circle"></i>
            <span>{{ __('pages.about_hero_tag') }}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
            {{ __('pages.about_title') }}
        </h1>
        <p class="mt-4 text-base sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
            {{ __('pages.about_subtitle') }}
        </p>
    </div>
</section>

{{-- ============================================================
     STATS BAR
============================================================ --}}
<section class="bg-emerald-600">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 grid grid-cols-2 md:grid-cols-4 gap-6 text-white text-center">
        @php
            $statsItems = [
                ['icon' => 'fa-users', 'value' => \App\Models\User::count(), 'label' => __('pages.about_stat_contributors')],
                ['icon' => 'fa-map-marker-alt', 'value' => \App\Models\Destination::count() + \App\Models\ExploreItem::count(), 'label' => __('pages.about_stat_destinations')],
                ['icon' => 'fa-language', 'value' => 3, 'label' => __('pages.about_stat_languages')],
                ['icon' => 'fa-landmark', 'value' => \App\Models\Institution::count(), 'label' => __('pages.about_stat_services')],
            ];
        @endphp
        @foreach ($statsItems as $stat)
            <div class="flex flex-col items-center gap-1">
                <i class="fas {{ $stat['icon'] }} text-2xl text-emerald-200 mb-1"></i>
                <span class="text-3xl font-extrabold">{{ $stat['value'] }}</span>
                <span class="text-xs text-emerald-100 font-medium">{{ $stat['label'] }}</span>
            </div>
        @endforeach
    </div>
</section>

{{-- ============================================================
     MAIN CONTENT
============================================================ --}}
<section class="bg-gray-50 py-14 lg:py-20">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 space-y-16">

        {{-- INTRO --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div>
                <div class="inline-flex items-center gap-2 text-emerald-600 font-semibold text-sm uppercase tracking-wider mb-3">
                    <i class="fas fa-leaf"></i> Explore Laggala
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight mb-4">
                    {{ __('pages.about_intro_heading') }}
                </h2>
                <p class="text-gray-600 leading-relaxed text-base">
                    {{ __('pages.about_intro_body') }}
                </p>
            </div>
            <div class="relative rounded-2xl overflow-hidden shadow-xl aspect-video bg-emerald-900/10">
                <img src="{{ asset('images/knuckles-bg.jpg') }}" alt="Laggala Landscape" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/60 to-transparent"></div>
                <div class="absolute bottom-4 left-4 text-white text-sm font-semibold">Laggala — Knuckles Range</div>
            </div>
        </div>

        {{-- DIVIDER --}}
        <hr class="border-gray-200">

        {{-- ABOUT THE PLATFORM --}}
        <div class="flex gap-6 items-start">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                <i class="fas fa-globe text-emerald-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('pages.about_app_heading') }}</h3>
                <p class="text-gray-600 leading-relaxed">{{ __('pages.about_app_body') }}</p>
            </div>
        </div>

        {{-- ABOUT THE MEDIA UNIT --}}
        <div class="flex gap-6 items-start">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-teal-100 flex items-center justify-center">
                <i class="fas fa-building text-teal-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('pages.about_media_heading') }}</h3>
                <p class="text-gray-600 leading-relaxed">{{ __('pages.about_media_body') }}</p>
                <address class="mt-4 not-italic text-sm text-slate-500 space-y-1">
                    <div class="flex items-center gap-2"><i class="fas fa-map-marker-alt text-emerald-500 w-4"></i> Media Unit, Divisional Secretariat, Laggala</div>
                    <div class="flex items-center gap-2"><i class="fas fa-phone text-emerald-500 w-4"></i> <a href="tel:0662275200" class="hover:text-emerald-600 transition">066 227 5200</a></div>
                    <div class="flex items-center gap-2"><i class="fas fa-envelope text-emerald-500 w-4"></i> <a href="mailto:explorelaggala@gmail.com" class="hover:text-emerald-600 transition">explorelaggala@gmail.com</a></div>
                    <div class="flex items-center gap-2"><i class="fas fa-clock text-emerald-500 w-4"></i> Mon – Fri: 8.15 AM – 4.15 PM</div>
                </address>
            </div>
        </div>

        {{-- MISSION & VISION --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
                <div class="w-10 h-10 rounded-lg bg-emerald-600 flex items-center justify-center mb-4">
                    <i class="fas fa-bullseye text-white"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">{{ __('pages.about_mission_heading') }}</h3>
                <p class="text-gray-600 text-sm leading-relaxed">{{ __('pages.about_mission_body') }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
                <div class="w-10 h-10 rounded-lg bg-teal-600 flex items-center justify-center mb-4">
                    <i class="fas fa-eye text-white"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">{{ __('pages.about_vision_heading') }}</h3>
                <p class="text-gray-600 text-sm leading-relaxed">{{ __('pages.about_vision_body') }}</p>
            </div>
        </div>

        {{-- CTA --}}
        <div class="text-center pt-4">
            <a href="#" onclick="document.querySelector('#footer-contact').scrollIntoView({behavior:'smooth'}); return false;"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold px-6 py-3 rounded-xl shadow transition">
                <i class="fas fa-envelope"></i>
                {{ __('pages.about_contact_cta') }}
            </a>
        </div>

    </div>
</section>

@endsection
