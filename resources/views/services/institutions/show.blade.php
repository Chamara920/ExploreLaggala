@extends('layouts.public')

@php
    $locale = app()->getLocale();
    $name = $translation?->name ?? 'Government Institution';
    $address = $translation?->location_name ?? '';
    $hours = $translation?->office_hours ?? '';
    $shortDesc = $translation?->short_description ?? '';
    $desc = $translation?->description ?? '';
@endphp

@section('title', $name . ' — Government Institutions Laggala')

@section('content')

{{-- Leaflet CSS if coordinates exist --}}
@if($institution->latitude && $institution->longitude)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endif

<div class="bg-slate-50 min-h-screen">

    {{-- ============================================================
         1. HERO COVER / BASIC PROFILE HEADER
         ============================================================ --}}
    <section class="relative bg-slate-950 text-white overflow-hidden">
        {{-- Single Cover Photo Background if available --}}
        @if($institution->image_path)
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('storage/' . $institution->image_path) }}"
                     alt="{{ $name }}"
                     class="w-full h-full object-cover opacity-25 filter blur-[2px] scale-105">
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
                <a href="{{ route('services.institutions.index') }}" class="hover:text-emerald-400 transition">{{ __('institutions.title') }}</a>
                <span>/</span>
                <span class="text-white truncate max-w-xs sm:max-w-md font-medium">{{ $name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                {{-- Single Photo Preview (Left) --}}
                <div class="lg:col-span-4">
                    <div class="rounded-3xl overflow-hidden border-2 border-white/20 shadow-2xl bg-slate-900 aspect-video lg:aspect-[4/3] relative group">
                        @if($institution->image_path)
                            <img src="{{ asset('storage/' . $institution->image_path) }}"
                                 alt="{{ $name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center text-slate-400">
                                <i class="fas fa-landmark text-6xl text-emerald-400/50 mb-3"></i>
                                <span class="text-xs uppercase tracking-wider font-semibold text-slate-400">Public Institution</span>
                            </div>
                        @endif
                        
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-black/75 text-emerald-300 backdrop-blur-md border border-white/10 shadow">
                                {{ $institution->type_label }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Basic Profile Info (Right) --}}
                <div class="lg:col-span-8">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                            🏛️ Official Portal
                        </span>
                        @if($institution->featured)
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-400/30">
                                ⭐ Primary Institution
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

                    {{-- Contact & Office Hours Grid --}}
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-xs sm:text-sm">
                        @if($address)
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10 flex items-start gap-2.5">
                                <i class="fas fa-map-marker-alt text-emerald-400 mt-1 flex-shrink-0"></i>
                                <div>
                                    <span class="block text-[11px] uppercase tracking-wider text-slate-400 font-semibold">{{ __('institutions.address') }}</span>
                                    <span class="text-white font-medium">{{ $address }}</span>
                                </div>
                            </div>
                        @endif

                        @if($hours)
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10 flex items-start gap-2.5">
                                <i class="fas fa-clock text-amber-400 mt-1 flex-shrink-0"></i>
                                <div>
                                    <span class="block text-[11px] uppercase tracking-wider text-slate-400 font-semibold">{{ __('institutions.hours') }}</span>
                                    <span class="text-white font-medium">{{ $hours }}</span>
                                </div>
                            </div>
                        @endif

                        @if($institution->phone)
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10 flex items-start gap-2.5">
                                <i class="fas fa-phone-alt text-blue-400 mt-1 flex-shrink-0"></i>
                                <div>
                                    <span class="block text-[11px] uppercase tracking-wider text-slate-400 font-semibold">{{ __('institutions.hotline') }}</span>
                                    <a href="tel:{{ $institution->phone }}" class="text-white font-medium hover:text-emerald-300">{{ $institution->phone }}</a>
                                </div>
                            </div>
                        @endif

                        @if($institution->email)
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10 flex items-start gap-2.5">
                                <i class="fas fa-envelope text-purple-400 mt-1 flex-shrink-0"></i>
                                <div>
                                    <span class="block text-[11px] uppercase tracking-wider text-slate-400 font-semibold">{{ __('institutions.email') }}</span>
                                    <a href="mailto:{{ $institution->email }}" class="text-white font-medium hover:text-emerald-300 truncate max-w-[180px] inline-block">{{ $institution->email }}</a>
                                </div>
                            </div>
                        @endif

                        @if($institution->website)
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10 flex items-start gap-2.5">
                                <i class="fas fa-globe text-teal-400 mt-1 flex-shrink-0"></i>
                                <div>
                                    <span class="block text-[11px] uppercase tracking-wider text-slate-400 font-semibold">{{ __('institutions.website') }}</span>
                                    <a href="{{ $institution->website }}" target="_blank" rel="noopener" class="text-emerald-300 hover:underline truncate max-w-[180px] inline-block font-medium">Visit Website &rarr;</a>
                                </div>
                            </div>
                        @endif

                        @if($institution->latitude && $institution->longitude)
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 border border-white/10 flex items-start gap-2.5">
                                <i class="fas fa-map text-red-400 mt-1 flex-shrink-0"></i>
                                <div>
                                    <span class="block text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Location Pin</span>
                                    <a href="https://maps.google.com/?q={{ $institution->latitude }},{{ $institution->longitude }}" target="_blank" rel="noopener" class="text-amber-300 hover:underline font-medium">Google Maps &rarr;</a>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Public Rating Overview --}}
                    @if($institution->review_count > 0)
                        <div class="mt-6 flex items-center gap-3 bg-white/5 border border-white/10 px-4 py-2 rounded-2xl inline-flex">
                            <div class="text-amber-400 text-lg">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= round($institution->average_rating) ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            <span class="font-bold text-white text-sm">{{ number_format($institution->average_rating, 1) }} / 5.0</span>
                            <span class="text-slate-400 text-xs">({{ $institution->review_count }} {{ __('institutions.reviews_count') }})</span>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </section>

    {{-- ============================================================
         2. DYNAMIC TABS NAVIGATION (HYBRID PAGE BUILDER)
         Overview | Departments / Units | Public Services | Officers | Documents | Location & Reviews
         ============================================================ --}}
    <section class="sticky top-16 z-30 bg-white border-b border-slate-200 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 overflow-x-auto py-3 no-scrollbar" id="institutionTabs">
                
                <a href="#overview-section"
                   class="tab-link px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition whitespace-nowrap bg-emerald-600 text-white shadow-sm"
                   data-target="overview-section">
                    <i class="fas fa-info-circle mr-1.5"></i>
                    <span>{{ __('institutions.overview') }}</span>
                </a>

                @if($rootUnits->isNotEmpty())
                    <a href="#departments-section"
                       class="tab-link px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200"
                       data-target="departments-section">
                        <i class="fas fa-sitemap mr-1.5 text-emerald-600"></i>
                        <span>{{ __('institutions.departments_units') }} ({{ $institution->units->count() }})</span>
                    </a>
                @endif

                @if($institution->services->isNotEmpty())
                    <a href="#services-section"
                       class="tab-link px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200"
                       data-target="services-section">
                        <i class="fas fa-concierge-bell mr-1.5 text-blue-600"></i>
                        <span>{{ __('institutions.public_services') }} ({{ $institution->services->count() }})</span>
                    </a>
                @endif

                @if($institution->officers->isNotEmpty())
                    <a href="#officers-section"
                       class="tab-link px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200"
                       data-target="officers-section">
                        <i class="fas fa-user-tie mr-1.5 text-purple-600"></i>
                        <span>{{ __('institutions.officers_directory') }} ({{ $institution->officers->count() }})</span>
                    </a>
                @endif

                @if($institution->customSections->isNotEmpty())
                    <a href="#custom-sections"
                       class="tab-link px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200"
                       data-target="custom-sections">
                        <i class="fas fa-cubes mr-1.5 text-amber-600"></i>
                        <span>Features &amp; Charters</span>
                    </a>
                @endif

                @if($institution->documents->isNotEmpty())
                    <a href="#documents-section"
                       class="tab-link px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200"
                       data-target="documents-section">
                        <i class="fas fa-file-download mr-1.5 text-red-600"></i>
                        <span>{{ __('institutions.documents_forms') }} ({{ $institution->documents->count() }})</span>
                    </a>
                @endif

                @if($institution->latitude && $institution->longitude)
                    <a href="#location-map-section"
                       class="tab-link px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200"
                       data-target="location-map-section">
                        <i class="fas fa-map-marked-alt mr-1.5 text-emerald-600"></i>
                        <span>{{ __('institutions.interactive_map') }}</span>
                    </a>
                @endif

                <a href="#reviews-section"
                   class="tab-link px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200"
                   data-target="reviews-section">
                    <i class="fas fa-star mr-1.5 text-amber-500"></i>
                    <span>{{ __('institutions.ratings_feedback') }} ({{ $institution->review_count }})</span>
                </a>

            </div>
        </div>
    </section>

    {{-- ============================================================
         3. TAB CONTENT / SECTIONS BODY
         ============================================================ --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 space-y-12">

        {{-- SECTION A: OVERVIEW & MANDATE --}}
        <section id="overview-section" class="scroll-mt-32 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">
                    <i class="fas fa-landmark"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ __('institutions.overview') }}</h2>
                    <p class="text-xs text-slate-500">Vision, Mandate &amp; Institutional Scope</p>
                </div>
            </div>

            @if($desc)
                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-sm sm:text-base">
                    {!! $desc !!}
                </div>
            @else
                <p class="text-sm text-slate-600 leading-relaxed">
                    {{ $shortDesc ?: 'Official public administration office in Laggala providing governance, citizen services, developmental activities, and regulatory functions.' }}
                </p>
            @endif
        </section>

        {{-- SECTION B: DEPARTMENTS & ORGANIZATIONAL STRUCTURE --}}
        @if($rootUnits->isNotEmpty())
            <section id="departments-section" class="scroll-mt-32 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-lg">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ __('institutions.departments_units') }}</h2>
                        <p class="text-xs text-slate-500">Organizational units &amp; specialized administrative divisions</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($rootUnits as $unit)
                        @php
                            $uTrans = $unit->translationFor($locale);
                            $uName = $uTrans?->name ?? $unit->slug;
                            $uDesc = $uTrans?->description;
                        @endphp
                        <div class="border border-slate-200 rounded-2xl p-5 bg-slate-50/50 hover:bg-slate-50 transition">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                                        <i class="fas fa-folder text-emerald-600 text-sm"></i>
                                        <span>{{ $uName }}</span>
                                    </h3>
                                    @if($uDesc)
                                        <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed">
                                            {{ $uDesc }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Sub-units if any --}}
                            @if($unit->children->isNotEmpty())
                                <div class="mt-4 pt-3 border-t border-slate-200">
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Sub-divisions / Sections</span>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($unit->children as $child)
                                            @php
                                                $childTrans = $child->translationFor($locale);
                                            @endphp
                                            <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 shadow-2xs">
                                                {{ $childTrans?->name ?? $child->slug }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Services count & Officers linked --}}
                            <div class="mt-4 flex items-center gap-4 text-xs text-slate-500">
                                @if($unit->services->count() > 0)
                                    <span><i class="fas fa-check-circle text-emerald-600 mr-1"></i>{{ $unit->services->count() }} services</span>
                                @endif
                                @if($unit->officers->count() > 0)
                                    <span><i class="fas fa-user text-blue-600 mr-1"></i>{{ $unit->officers->count() }} officers</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- SECTION C: PUBLIC SERVICES DIRECTORY --}}
        @if($institution->services->isNotEmpty())
            <section id="services-section" class="scroll-mt-32 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ __('institutions.public_services') }}</h2>
                        <p class="text-xs text-slate-500">Requirements, processing timeline, fees, and procedures</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($institution->services as $service)
                        @php
                            $sTrans = $service->translationFor($locale);
                            $sTitle = $sTrans?->title ?? 'Service';
                            $sDesc = $sTrans?->description;
                            $sReq = $sTrans?->requirements;
                            $uTrans = $service->unit?->translationFor($locale);
                        @endphp
                        <div class="border border-slate-200 rounded-2xl p-5 bg-white shadow-2xs hover:shadow-md transition">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    @if($uTrans?->name)
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 mb-2">
                                            {{ $uTrans->name }}
                                        </span>
                                    @endif
                                    <h3 class="font-bold text-slate-900 text-base">
                                        {{ $sTitle }}
                                    </h3>
                                </div>
                            </div>

                            @if($sDesc)
                                <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    {{ $sDesc }}
                                </p>
                            @endif

                            {{-- Fees and Processing Time Badges --}}
                            <div class="mt-4 flex flex-wrap gap-2 text-xs">
                                <div class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center gap-1.5 font-semibold text-slate-700">
                                    <i class="fas fa-tag text-emerald-600"></i>
                                    <span>{{ __('institutions.service_fee') }}: {{ $service->fee ?: __('institutions.free_service') }}</span>
                                </div>
                                @if($service->processing_time)
                                    <div class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center gap-1.5 font-semibold text-slate-700">
                                        <i class="fas fa-stopwatch text-amber-600"></i>
                                        <span>{{ __('institutions.processing_time') }}: {{ $service->processing_time }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Requirements Accordion / Info --}}
                            @if($sReq)
                                <div class="mt-4 pt-3 border-t border-slate-100">
                                    <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider block mb-1">
                                        <i class="fas fa-clipboard-check text-emerald-600 mr-1"></i>
                                        {{ __('institutions.requirements') }}:
                                    </span>
                                    <div class="text-xs text-slate-600 whitespace-pre-line leading-relaxed bg-slate-50/70 p-3 rounded-xl border border-slate-100">
                                        {{ $sReq }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- SECTION D: OFFICERS DIRECTORY --}}
        @if($institution->officers->isNotEmpty())
            <section id="officers-section" class="scroll-mt-32 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-lg">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ __('institutions.officers_directory') }}</h2>
                        <p class="text-xs text-slate-500">Key administrative heads, branch officers, and field personnel</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($institution->officers as $officer)
                        @php
                            $oTrans = $officer->translationFor($locale);
                            $oName = $oTrans?->name ?? 'Officer';
                            $oDesig = $oTrans?->designation ?? '';
                            $oResp = $oTrans?->responsibilities;
                            $uTrans = $officer->unit?->translationFor($locale);
                        @endphp
                        <div class="border border-slate-200 rounded-2xl p-5 bg-white shadow-2xs hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-14 h-14 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0 flex items-center justify-center text-slate-400">
                                        @if($officer->photo)
                                            <img src="{{ asset('storage/' . $officer->photo) }}"
                                                 alt="{{ $oName }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user text-2xl text-slate-300"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-900 text-sm sm:text-base leading-snug">
                                            {{ $oName }}
                                        </h3>
                                        @if($oDesig)
                                            <p class="text-xs font-semibold text-emerald-700 mt-0.5">{{ $oDesig }}</p>
                                        @endif
                                        @if($uTrans?->name)
                                            <span class="inline-block text-[10px] text-slate-500 font-medium bg-slate-100 px-2 py-0.5 rounded-full mt-1">
                                                {{ $uTrans->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if($oResp)
                                    <p class="text-xs text-slate-600 line-clamp-3 mb-4 leading-relaxed bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                        {{ $oResp }}
                                    </p>
                                @endif
                            </div>

                            {{-- Contact actions --}}
                            <div class="pt-3 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                                @if($officer->phone)
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-phone-alt text-emerald-600 w-3.5"></i>
                                        <a href="tel:{{ $officer->phone }}" class="hover:text-emerald-700 font-medium">{{ $officer->phone }}</a>
                                        @if($officer->extension)
                                            <span class="text-slate-400">({{ __('institutions.extension') }} {{ $officer->extension }})</span>
                                        @endif
                                    </div>
                                @endif

                                @if($officer->email)
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-envelope text-blue-600 w-3.5"></i>
                                        <a href="mailto:{{ $officer->email }}" class="hover:text-blue-700 truncate max-w-[200px]">{{ $officer->email }}</a>
                                    </div>
                                @endif

                                @if($officer->working_hours)
                                    <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                        <i class="fas fa-clock text-amber-600 w-3.5"></i>
                                        <span>{{ $officer->working_hours }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- SECTION E: CUSTOM CONTENT SECTIONS (HYBRID PAGE BUILDER BLOCKS) --}}
        @if($institution->customSections->isNotEmpty())
            <div id="custom-sections" class="scroll-mt-32 space-y-8">
                @foreach($institution->customSections as $sectionBlock)
                    @php
                        $cTrans = $sectionBlock->translationFor($locale);
                        $cTitle = $cTrans?->title ?? '';
                        $cContent = $cTrans?->content ?? '';
                        $cData = $sectionBlock->data ?? [];
                    @endphp
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                        
                        @if($cTitle)
                            <div class="flex items-center gap-3 mb-5 border-b border-slate-100 pb-4">
                                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                                    @if($sectionBlock->type === 'notice_alert')
                                        <i class="fas fa-bell text-red-500"></i>
                                    @elseif($sectionBlock->type === 'faq')
                                        <i class="fas fa-question-circle text-blue-500"></i>
                                    @elseif($sectionBlock->type === 'table')
                                        <i class="fas fa-table text-emerald-600"></i>
                                    @elseif($sectionBlock->type === 'important_links')
                                        <i class="fas fa-link text-indigo-600"></i>
                                    @else
                                        <i class="fas fa-cube text-slate-700"></i>
                                    @endif
                                </div>
                                <h2 class="text-xl font-bold text-slate-900">{{ $cTitle }}</h2>
                            </div>
                        @endif

                        {{-- Section Block Type Renderers --}}
                        @if($sectionBlock->type === 'notice_alert')
                            <div class="p-4 sm:p-5 rounded-2xl bg-amber-50/80 border-l-4 border-amber-500 text-amber-900 text-sm leading-relaxed">
                                {!! $cContent !!}
                            </div>
                        @elseif($sectionBlock->type === 'faq')
                            @if($cContent)
                                <div class="prose prose-slate max-w-none text-sm text-slate-700 mb-4">{!! $cContent !!}</div>
                            @endif
                            @if(isset($cData['items']) && is_array($cData['items']))
                                <div class="space-y-3">
                                    @foreach($cData['items'] as $item)
                                        <details class="group bg-slate-50 border border-slate-200 rounded-2xl p-4 transition [&_summary::-webkit-details-marker]:hidden">
                                            <summary class="flex cursor-pointer items-center justify-between font-bold text-slate-900 text-sm">
                                                <span>{{ $item['q'] ?? ($item['question'] ?? 'Question') }}</span>
                                                <span class="transition group-open:-rotate-180">
                                                    <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                                                </span>
                                            </summary>
                                            <p class="mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-200/60 pt-3">
                                                {{ $item['a'] ?? ($item['answer'] ?? '') }}
                                            </p>
                                        </details>
                                    @endforeach
                                </div>
                            @endif
                        @elseif($sectionBlock->type === 'important_links')
                            @if($cContent)
                                <div class="prose prose-slate max-w-none text-sm text-slate-700 mb-4">{!! $cContent !!}</div>
                            @endif
                            @if(isset($cData['links']) && is_array($cData['links']))
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($cData['links'] as $link)
                                        <a href="{{ $link['url'] ?? '#' }}" target="_blank" rel="noopener"
                                           class="p-3.5 rounded-2xl border border-slate-200 hover:border-emerald-500 bg-slate-50/60 hover:bg-emerald-50/40 transition flex items-center justify-between group">
                                            <span class="font-bold text-xs sm:text-sm text-slate-800 group-hover:text-emerald-700">{{ $link['title'] ?? 'Portal Link' }}</span>
                                            <i class="fas fa-external-link-alt text-xs text-slate-400 group-hover:text-emerald-600"></i>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        @elseif($sectionBlock->type === 'table')
                            @if($cContent)
                                <div class="prose prose-slate max-w-none text-sm text-slate-700 mb-4">{!! $cContent !!}</div>
                            @endif
                            @if(isset($cData['headers'], $cData['rows']) && is_array($cData['headers']) && is_array($cData['rows']))
                                <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-2xs">
                                    <table class="w-full text-left text-xs sm:text-sm text-slate-700">
                                        <thead class="bg-slate-100 text-slate-900 font-bold uppercase tracking-wider text-[11px]">
                                            <tr>
                                                @foreach($cData['headers'] as $header)
                                                    <th class="p-3.5 border-b border-slate-200">{{ $header }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($cData['rows'] as $row)
                                                <tr class="hover:bg-slate-50">
                                                    @foreach($row as $cell)
                                                        <td class="p-3.5">{{ $cell }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @else
                            {{-- Default Text / Rich content block --}}
                            <div class="prose prose-slate max-w-none text-sm sm:text-base text-slate-700 leading-relaxed">
                                {!! $cContent !!}
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
        @endif

        {{-- SECTION F: OFFICIAL DOCUMENTS & DOWNLOADS --}}
        @if($institution->documents->isNotEmpty())
            <section id="documents-section" class="scroll-mt-32 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-red-50 text-red-700 flex items-center justify-center font-bold text-lg">
                        <i class="fas fa-file-download"></i>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ __('institutions.documents_forms') }}</h2>
                        <p class="text-xs text-slate-500">Citizen charter, official circulars, and downloadable application forms</p>
                    </div>
                </div>

                <div class="divide-y divide-slate-100 rounded-2xl border border-slate-200 overflow-hidden">
                    @foreach($institution->documents as $doc)
                        @php
                            $dTrans = $doc->translationFor($locale);
                            $dTitle = $dTrans?->title ?? 'Document';
                            $dDesc = $dTrans?->description;
                        @endphp
                        <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50 transition bg-white">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-red-100/70 text-red-700 flex items-center justify-center flex-shrink-0 font-bold text-sm">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm sm:text-base">{{ $dTitle }}</h4>
                                    @if($dDesc)
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $dDesc }}</p>
                                    @endif
                                    @if($doc->unit)
                                        <span class="inline-block mt-1 text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded">
                                            {{ $doc->unit->translationFor($locale)?->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" download
                               class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white font-bold text-xs transition shadow-sm self-start sm:self-auto">
                                <i class="fas fa-download text-xs"></i>
                                <span>{{ __('institutions.downloads') }} ({{ $doc->file_type ?? 'FILE' }})</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- SECTION G: INTERACTIVE MAP LOCATION --}}
        @if($institution->latitude && $institution->longitude)
            <section id="location-map-section" class="scroll-mt-32 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ __('institutions.interactive_map') }}</h2>
                            <p class="text-xs text-slate-500">Official geographical premises in Laggala</p>
                        </div>
                    </div>

                    <a href="https://maps.google.com/?q={{ $institution->latitude }},{{ $institution->longitude }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-bold transition">
                        <i class="fas fa-directions"></i>
                        <span>{{ __('institutions.open_in_google_maps') }}</span>
                    </a>
                </div>

                <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-2xs">
                    <div id="singleInstitutionMap" class="w-full h-[360px] sm:h-[420px] z-10"></div>
                </div>
            </section>
        @endif

        {{-- SECTION H: RATINGS, FEEDBACK & REVIEWS (WITH ADMIN MODERATION & DELETION) --}}
        <section id="reviews-section" class="scroll-mt-32 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between flex-wrap gap-4 mb-8 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-lg">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ __('institutions.ratings_feedback') }}</h2>
                        <p class="text-xs text-slate-500">Citizen opinions, transparency, and public service ratings</p>
                    </div>
                </div>

                {{-- Rating Summary Badge --}}
                <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 px-4 py-2 rounded-2xl">
                    <div class="text-amber-400 text-lg flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <span>{{ $i <= round($institution->average_rating) ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                    <span class="font-extrabold text-slate-900 text-sm">{{ number_format($institution->average_rating, 1) }} / 5.0</span>
                    <span class="text-slate-500 text-xs">({{ $institution->review_count }} {{ __('institutions.reviews_count') }})</span>
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
                
                {{-- Left: Approved Reviews List --}}
                <div class="lg:col-span-7 space-y-4">
                    @if($institution->approvedReviews->isNotEmpty())
                        @foreach($institution->approvedReviews as $review)
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

                                    {{-- Star rating display --}}
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

                                {{-- Admin / Super Admin Delete Action --}}
                                @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'super_admin']))
                                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-end">
                                        <form method="POST" action="{{ route('services.institutions.reviews.destroy', $review->id) }}" onsubmit="return confirm('{{ __('institutions.confirm_delete_review') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700 text-xs font-semibold flex items-center gap-1.5 transition">
                                                <i class="fas fa-trash-alt text-[10px]"></i>
                                                <span>{{ __('institutions.delete_review') }}</span>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center bg-slate-50/50">
                            <i class="far fa-comments text-3xl text-slate-300 mb-2"></i>
                            <h4 class="font-bold text-slate-700 text-sm">{{ __('institutions.no_reviews_yet') }}</h4>
                            <p class="text-xs text-slate-500 mt-1">{{ __('institutions.be_first_to_review') }}</p>
                        </div>
                    @endif
                </div>

                {{-- Right: Submit Review Form (Logged-in users) --}}
                <div class="lg:col-span-5">
                    <div class="rounded-2xl border border-slate-200 p-6 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-base mb-1">
                            {{ __('institutions.leave_feedback') }}
                        </h3>
                        <p class="text-xs text-slate-500 mb-5">
                            {{ __('institutions.pending_approval_notice') }}
                        </p>

                        @auth
                            {{-- User Review Pending Banner if user already reviewed --}}
                            @if($userReview)
                                <div class="mb-4 p-3 rounded-xl border text-xs {{ $userReview->status === 'approved' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
                                    @if($userReview->status === 'pending')
                                        <i class="fas fa-clock mr-1 text-amber-600"></i>
                                        <strong>Pending Moderation:</strong> Your feedback is currently awaiting administrator review. You may update it below.
                                    @elseif($userReview->status === 'approved')
                                        <i class="fas fa-check-circle mr-1 text-emerald-600"></i>
                                        <strong>Approved:</strong> Your feedback is published publicly.
                                    @endif
                                </div>
                            @endif

                            <form method="POST" action="{{ route('services.institutions.reviews.store', $institution->id) }}" class="space-y-4">
                                @csrf

                                {{-- Star Rating Interactive Selector --}}
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                        {{ __('institutions.your_rating') }}
                                    </label>
                                    @php
                                        $selectedRating = old('rating', $userReview?->rating ?? 5);
                                    @endphp
                                    <div class="flex items-center gap-2" id="starRatingContainer">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button"
                                                    class="inst-star-btn text-2xl transition hover:scale-110 {{ $i <= $selectedRating ? 'text-amber-400' : 'text-gray-300' }}"
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
                                        {{ __('institutions.your_comment') }}
                                    </label>
                                    <textarea id="reviewComment"
                                              name="comment"
                                              rows="4"
                                              placeholder="{{ __('institutions.comment_placeholder') }}"
                                              class="w-full rounded-xl border border-slate-200 p-3.5 text-xs sm:text-sm bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition">{{ old('comment', $userReview?->comment ?? '') }}</textarea>
                                    @error('comment')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                        class="w-full py-3 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm transition shadow-md flex items-center justify-center gap-2">
                                    <i class="fas fa-paper-plane text-xs"></i>
                                    <span>{{ $userReview ? 'Update Feedback' : __('institutions.submit_feedback') }}</span>
                                </button>
                            </form>
                        @else
                            <div class="rounded-xl bg-white border border-slate-200 p-5 text-center">
                                <i class="fas fa-lock text-slate-400 text-2xl mb-2"></i>
                                <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                                    {{ __('institutions.login_to_review') }}
                                </p>
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-slate-900 hover:bg-black text-white text-xs font-bold transition shadow-sm">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <span>{{ __('institutions.sign_in') }}</span>
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>

            </div>
        </section>

    </div>

</div>

{{-- Leaflet JS & Tab Anchor Script --}}
@if($institution->latitude && $institution->longitude)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var lat = {{ (float) $institution->latitude }};
            var lng = {{ (float) $institution->longitude }};
            var map = L.map('singleInstitutionMap').setView([lat, lng], 15);

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
        // Star Rating Selection
        var starButtons = document.querySelectorAll('.inst-star-btn');
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

        // Tabs active styling on click
        var tabLinks = document.querySelectorAll('.tab-link');
        tabLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                tabLinks.forEach(function(l) {
                    l.classList.remove('bg-emerald-600', 'text-white', 'shadow-sm');
                    l.classList.add('bg-slate-100', 'text-slate-700');
                });
                this.classList.remove('bg-slate-100', 'text-slate-700');
                this.classList.add('bg-emerald-600', 'text-white', 'shadow-sm');
            });
        });
    });
</script>

@endsection
