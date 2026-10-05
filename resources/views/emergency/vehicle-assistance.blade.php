@extends('layouts.public')

@php
    $lang = $locale ?? session('locale', 'si');
    $title = match($lang) {
        'si' => 'වාහන ආධාර සහ කඳුකර මුදවාගැනීම් සේවා — Vehicle Assistance',
        'ta' => 'வாகன உதவி & இழுவை சேவை — Vehicle Assistance',
        default => 'Vehicle Breakdown, Towing & 4x4 Mountain Recovery — Laggala',
    };
    $subtitle = match($lang) {
        'si' => 'රිවස්ටන්, නකල්ස් සහ ලග්ගල කඳුකර මාර්ගවල වාහන කාර්මික දෝෂ, ඇදගෙන යාමේ රථ (Towing), 4x4 වින්ච් සහ ජංගම ටයර් අලුත්වැඩියා සේවා.',
        'ta' => 'ரிவர்ஸ்டன் மற்றும் நக்கிள்ஸ் மலைப்பாதைகளில் வாகன பழுதுபார்ப்பு, இழுவை (Towing) மற்றும் 24/7 அவசர உதவி.',
        default => '24/7 mountain winch recovery, flatbed towing, mobile roadside tyre vulcanizing, and emergency mechanics for Knuckles passes.',
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

        {{-- Mountain Driving Caution Alert --}}
        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-xl mb-8 flex items-start gap-3">
            <i class="bi bi-exclamation-triangle-fill text-amber-600 text-xl flex-shrink-0 mt-0.5"></i>
            <div class="text-xs md:text-sm text-amber-900 leading-relaxed">
                <strong>
                    @if($lang === 'si') කඳුකර මාර්ග ධාවන අවවාදයයි: @elseif($lang === 'ta') மலைப்பாதை பயண எச்சரிக்கை: @else Mountain Driving Safety Notice: @endif
                </strong>
                @if($lang === 'si')
                    රිවස්ටන් සහ රත්තොට-ලග්ගල මාර්ගවල දැඩි බෑවුම් සහ තියුණු වංගු සහිත බැවින් අඩු ගියර් (Low Gear) භාවිත කරන්න. තිරිංග (Brake) රත්වීම වැළැක්වීමට නිතර තිරිංග පෑගීමෙන් වළකින්න. මඟ ඇනහිටීමකදී හෝ ලිස්සායෑමකදී පහත සහන කණ්ඩායම් අමතන්න.
                @elseif($lang === 'ta')
                    ரிவர்ஸ்டன் மற்றும் லக்கல மலைப்பாதைகளில் வாகனங்களை இயக்கும் போது குறைந்த கியர்களை (Low Gear) பயன்படுத்தவும். பிரேக் சூடாவதை தவிர்க்கவும். அவசர உதவிக்கு கீழே உள்ள சேவைகளை அழைக்கவும்.
                @else
                    Riverston Pass and Rattota-Laggala roads feature steep gradients and sharp hairpin bends. Use low engine gears to prevent brake overheating. If stranded or sliding off the road, contact the 4x4 mountain winch recovery teams below.
                @endif
            </div>
        </div>

        {{-- Service Filters --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mr-1">
                        <i class="bi bi-funnel"></i> Specialization:
                    </span>

                    <a href="{{ route('emergency.vehicle-assistance', ['lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ empty($service) || $service === 'all' ? 'bg-amber-500 text-gray-950 shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All Providers
                    </a>
                    <a href="{{ route('emergency.vehicle-assistance', ['service' => 'recovery_4x4', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($service ?? '') === 'recovery_4x4' ? 'bg-amber-500 text-gray-950 shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        4x4 Mountain Recovery (කඳුකර මුදවාගැනීම්)
                    </a>
                    <a href="{{ route('emergency.vehicle-assistance', ['service' => 'towing', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($service ?? '') === 'towing' ? 'bg-amber-500 text-gray-950 shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Towing (වාහන ඇදගෙන යාම)
                    </a>
                    <a href="{{ route('emergency.vehicle-assistance', ['service' => 'tyre_repair', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($service ?? '') === 'tyre_repair' ? 'bg-amber-500 text-gray-950 shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Tyre Repair (ටයර් සේවා)
                    </a>
                    <a href="{{ route('emergency.vehicle-assistance', ['service' => 'mechanic', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($service ?? '') === 'mechanic' ? 'bg-amber-500 text-gray-950 shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Mobile Mechanic (කාර්මික)
                    </a>
                </div>

                <div class="text-xs text-gray-500 font-medium">
                    Available <span class="font-bold text-gray-800">{{ $assistances->count() }}</span> rescue teams
                </div>
            </div>
        </div>

        {{-- Providers Grid --}}
        @if($assistances->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($assistances as $item)
                    @php $t = $item->translationFor($lang); @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all overflow-hidden flex flex-col justify-between">
                        <div>
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="Provider Photo" class="w-full h-44 object-cover">
                            @endif

                            <div class="p-6">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider
                                        {{ $item->service_type === 'recovery_4x4' ? 'bg-amber-100 text-amber-900 font-extrabold' : 'bg-gray-100 text-gray-800' }}">
                                        {{ str_replace('_', ' ', strtoupper($item->service_type)) }}
                                    </span>

                                    <div class="flex items-center gap-1.5">
                                        @if($item->is_24x7)
                                            <span class="bg-red-50 text-red-700 border border-red-200 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> 24/7 On-Call
                                            </span>
                                        @endif
                                        @if($item->has_4x4_recovery)
                                            <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                                Winch Ready
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <h3 class="text-xl font-bold text-gray-900 leading-snug">
                                    {{ $t?->provider_name ?? 'Vehicle Assistance Service' }}
                                </h3>

                                @if($t?->contact_person)
                                    <div class="text-xs text-gray-600 font-semibold mt-1 flex items-center gap-1.5">
                                        <i class="bi bi-person-fill text-amber-600"></i> Contact: {{ $t->contact_person }}
                                    </div>
                                @endif

                                @if($t?->covered_areas)
                                    <div class="mt-3 p-3 bg-amber-50/60 rounded-xl border border-amber-100/60">
                                        <div class="text-[11px] font-bold uppercase tracking-wider text-amber-900 mb-1 flex items-center gap-1">
                                            <i class="bi bi-geo-alt-fill"></i> Covered Mountain Routes
                                        </div>
                                        <p class="text-xs text-amber-950 leading-relaxed">
                                            {{ $t->covered_areas }}
                                        </p>
                                    </div>
                                @endif

                                @if($t?->services_offered)
                                    <div class="text-xs text-gray-700 mt-3 leading-relaxed">
                                        <strong>Services:</strong> {{ $t->services_offered }}
                                    </div>
                                @endif

                                @if($t?->address)
                                    <div class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                        <i class="bi bi-shop"></i> Base: {{ $t->address }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <div class="pt-4 border-t border-gray-100 flex flex-col gap-2.5">
                                {{-- Primary Call Action --}}
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $item->primary_phone) }}"
                                   class="inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-gray-950 font-extrabold py-2.5 px-3 rounded-xl text-xs shadow-sm transition-all">
                                    <i class="bi bi-telephone-fill"></i>
                                    <span>Call Hotline: {{ $item->primary_phone }}</span>
                                </a>

                                @if($item->secondary_phone)
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $item->secondary_phone) }}"
                                       class="inline-flex items-center justify-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-3 rounded-xl text-xs transition-colors">
                                        <i class="bi bi-telephone"></i>
                                        <span>Alt Mobile: {{ $item->secondary_phone }}</span>
                                    </a>
                                @endif

                                {{-- Google Map Button --}}
                                @if($item->google_maps_url || ($item->latitude && $item->longitude))
                                    @php
                                        $mapUrl = $item->google_maps_url ?: "https://maps.google.com/?q={$item->latitude},{$item->longitude}";
                                    @endphp
                                    <a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center justify-center gap-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 font-medium py-1.5 px-3 rounded-xl text-xs transition-colors">
                                        <i class="bi bi-map"></i> View Garage Location
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
                <i class="bi bi-truck text-5xl text-gray-300 mb-3"></i>
                <h3 class="text-lg font-bold text-gray-700">No vehicle assistance providers found</h3>
            </div>
        @endif

    </div>
@endsection
