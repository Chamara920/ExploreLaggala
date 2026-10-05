@extends('layouts.public')

@php
    $lang = $locale ?? session('locale', 'si');
    $title = match($lang) {
        'si' => 'රෝහල් සහ සායන (රජයේ සහ පෞද්ගලික) — Hospitals',
        'ta' => 'வைத்தியசாலைகள் (அரசு & தனியார்) — Hospitals',
        default => 'Hospitals & Medical Centers (Gov & Private) — Laggala',
    };
    $subtitle = match($lang) {
        'si' => 'ලග්ගල, රත්තොට සහ නකල්ස් කලාපයේ රජයේ මූලික හා ප්‍රාදේශීය රෝහල්, පෞද්ගලික සායන සහ හදිසි ප්‍රතිකාර ඒකක.',
        'ta' => 'லக்கல மற்றும் நக்கிள்ஸ் பகுதியின் அரசு மற்றும் தனியார் மருத்துவமனைகள், அவசர சிகிச்சை பிரிவுகள்.',
        default => 'Government divisional and base hospitals, 24/7 emergency treatment units (ETU), private clinics, and ambulances.',
    };
@endphp

@section('title', $title)

@section('content')
    @include('emergency._header', [
        'pageTitle' => $title,
        'pageSubtitle' => $subtitle,
        'locale' => $lang,
    ])

    <div class="max-w-7xl mx-auto px-4 pb-16">

        {{-- Type Filter & Stats --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mr-1">
                        <i class="bi bi-funnel"></i> Type:
                    </span>

                    <a href="{{ route('emergency.hospitals', ['lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ empty($type) || $type === 'all' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All Facilities
                    </a>
                    <a href="{{ route('emergency.hospitals', ['type' => 'government', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($type ?? '') === 'government' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Government Hospitals (රජයේ රෝහල්)
                    </a>
                    <a href="{{ route('emergency.hospitals', ['type' => 'private', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($type ?? '') === 'private' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Private Hospitals (පෞද්ගලික)
                    </a>
                    <a href="{{ route('emergency.hospitals', ['type' => 'clinic', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($type ?? '') === 'clinic' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Clinics & Dispensaries
                    </a>
                    <a href="{{ route('emergency.hospitals', ['type' => 'pharmacy', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($type ?? '') === 'pharmacy' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Pharmacies
                    </a>
                </div>

                <div class="text-xs text-gray-500 font-medium">
                    Found <span class="font-bold text-gray-800">{{ $hospitals->count() }}</span> healthcare locations
                </div>
            </div>
        </div>

        {{-- Hospitals Grid --}}
        @if($hospitals->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($hospitals as $hosp)
                    @php $t = $hosp->translationFor($lang); @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all overflow-hidden flex flex-col justify-between">
                        <div>
                            @if($hosp->image)
                                <img src="{{ asset('storage/' . $hosp->image) }}" alt="Hospital" class="w-full h-44 object-cover">
                            @endif

                            <div class="p-6">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider
                                        {{ $hosp->type === 'government' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $hosp->type === 'government' ? 'Government (රජයේ)' : 'Private (පෞද්ගලික)' }}
                                    </span>

                                    <div class="flex items-center gap-1.5">
                                        @if($hosp->is_24x7)
                                            <span class="bg-red-50 text-red-700 border border-red-200 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> 24/7 ETU
                                            </span>
                                        @else
                                            <span class="bg-gray-100 text-gray-600 text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                                {{ $hosp->operating_hours ?? 'Daytime' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <h3 class="text-xl font-bold text-gray-900 leading-snug">
                                    {{ $t?->name ?? 'Hospital / Medical Facility' }}
                                </h3>

                                @if($hosp->category)
                                    <div class="text-xs text-rose-600 font-semibold mt-1">
                                        {{ $hosp->category }}
                                    </div>
                                @endif

                                @if($t?->address)
                                    <div class="text-xs text-gray-500 mt-2 flex items-start gap-1.5">
                                        <i class="bi bi-geo-alt-fill text-gray-400 mt-0.5"></i>
                                        <span>{{ $t->address }}</span>
                                    </div>
                                @endif

                                @if($t?->available_facilities)
                                    <div class="mt-4 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                        <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1 flex items-center gap-1">
                                            <i class="bi bi-heart-pulse"></i> Facilities
                                        </div>
                                        <p class="text-xs text-gray-700 leading-relaxed">
                                            {{ $t->available_facilities }}
                                        </p>
                                    </div>
                                @endif

                                @if($t?->description)
                                    <p class="text-xs text-gray-500 mt-3 leading-relaxed">
                                        {{ $t->description }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <div class="pt-4 border-t border-gray-100 flex flex-col gap-2.5">
                                {{-- Phone Buttons --}}
                                <div class="flex items-center gap-2">
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $hosp->phone) }}"
                                       class="flex-1 inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-3 rounded-xl text-xs shadow-sm transition-all">
                                        <i class="bi bi-telephone-fill"></i>
                                        <span>Call {{ $hosp->phone }}</span>
                                    </a>

                                    @if($hosp->ambulance_phone)
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $hosp->ambulance_phone) }}"
                                           title="Ambulance Direct"
                                           class="inline-flex items-center justify-center gap-1.5 bg-rose-100 hover:bg-rose-200 text-rose-800 font-bold py-2.5 px-3 rounded-xl text-xs transition-colors">
                                            <i class="bi bi-hospital"></i> Ambulance
                                        </a>
                                    @endif
                                </div>

                                {{-- Google Map Button --}}
                                @if($hosp->google_maps_url || ($hosp->latitude && $hosp->longitude))
                                    @php
                                        $mapUrl = $hosp->google_maps_url ?: "https://maps.google.com/?q={$hosp->latitude},{$hosp->longitude}";
                                    @endphp
                                    <a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center justify-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-3 rounded-xl text-xs transition-colors">
                                        <i class="bi bi-map"></i> View on Google Maps
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
                <i class="bi bi-hospital text-5xl text-gray-300 mb-3"></i>
                <h3 class="text-lg font-bold text-gray-700">No medical facilities found</h3>
                <p class="text-sm text-gray-500 mt-1">Please try choosing another category filter.</p>
            </div>
        @endif

    </div>
@endsection
