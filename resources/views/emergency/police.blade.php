@extends('layouts.public')

@php
    $lang = $locale ?? session('locale', 'si');
    $title = match($lang) {
        'si' => 'පොලිස් ස්ථාන සහ හදිසි ආරක්ෂක සේවා — Police Stations',
        'ta' => 'காவல் நிலையங்கள் — Police Stations',
        default => 'Police Stations & Law Enforcement — Laggala',
    };
    $subtitle = match($lang) {
        'si' => 'ලග්ගල, පල්ලේගම, රත්තොට සහ නකල්ස් කඳුකර කලාපය ආවරණය වන ප්‍රාදේශීය පොලිස් ස්ථාන සහ ස්ථානාධිපති (OIC) සෘජු දුරකථන අංක.',
        'ta' => 'லக்கல, பல்லேகம மற்றும் நக்கிள்ஸ் பகுதிக்கான காவல் நிலையங்கள் மற்றும் பொறுப்பதிகாரிகளின் (OIC) தொலைபேசி எண்கள்.',
        default => 'Local police stations, station desk hotlines, Officer In Charge (OIC) mobiles, and jurisdictional coverage.',
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

        {{-- Police Advisory Alert --}}
        <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded-xl mb-8 flex items-start gap-3">
            <i class="bi bi-info-circle-fill text-blue-600 text-xl flex-shrink-0 mt-0.5"></i>
            <div class="text-xs md:text-sm text-blue-900 leading-relaxed">
                <strong>
                    @if($lang === 'si') ජාතික හදිසි ඇමතුම: @elseif($lang === 'ta') தேசிய அவசர எண்: @else Nationwide Emergency: @endif
                </strong>
                @if($lang === 'si')
                    ඕනෑම අපරාධ, අනතුරු හෝ සැකකටයුතු සිදුවීමකදී <strong>119</strong> අමතන්න. ප්‍රාදේශීය සිදුවීම් සඳහා පහත දැක්වෙන අදාළ පොලිස් ස්ථානයේ දුරකථන අංක හෝ ස්ථානාධිපතිවරයා (OIC) සෘජුවම සම්බන්ධ කරගත හැක.
                @elseif($lang === 'ta')
                    அவசர குற்றவியல் சம்பவங்களுக்கு <strong>119</strong> அழைக்கவும். உள்ளூர் விவகாரங்களுக்கு கீழே உள்ள காவல் நிலையங்களை தொடர்பு கொள்ளவும்.
                @else
                    For immediate emergency security response, dial <strong>119</strong>. For regional issues, accident reports, or tourist guidance, contact the respective local police station or OIC directly below.
                @endif
            </div>
        </div>

        {{-- Police Stations Grid --}}
        @if($stations->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($stations as $station)
                    @php $t = $station->translationFor($lang); @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all overflow-hidden flex flex-col justify-between">
                        <div>
                            @if($station->image)
                                <img src="{{ asset('storage/' . $station->image) }}" alt="Police Station" class="w-full h-44 object-cover">
                            @endif

                            <div class="p-6">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-blue-100 text-blue-800">
                                        <i class="bi bi-shield-check"></i> {{ $station->division }}
                                    </span>
                                    <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> 24/7 Desk
                                    </span>
                                </div>

                                <h3 class="text-xl font-bold text-gray-900 leading-snug">
                                    {{ $t?->name ?? 'Police Station' }}
                                </h3>

                                @if($t?->address)
                                    <div class="text-xs text-gray-500 mt-2 flex items-start gap-1.5">
                                        <i class="bi bi-geo-alt-fill text-gray-400 mt-0.5"></i>
                                        <span>{{ $t->address }}</span>
                                    </div>
                                @endif

                                @if($t?->jurisdiction)
                                    <div class="mt-4 p-3 bg-blue-50/60 rounded-xl border border-blue-100/60">
                                        <div class="text-[11px] font-bold uppercase tracking-wider text-blue-900 mb-1 flex items-center gap-1">
                                            <i class="bi bi-compass"></i> Jurisdiction / Covered Areas
                                        </div>
                                        <p class="text-xs text-blue-950 leading-relaxed">
                                            {{ $t->jurisdiction }}
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
                                {{-- Phone Direct Action --}}
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $station->phone) }}"
                                   class="inline-flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white font-bold py-2.5 px-3 rounded-xl text-xs shadow-sm transition-all">
                                    <i class="bi bi-telephone-fill"></i>
                                    <span>Call Desk: {{ $station->phone }}</span>
                                </a>

                                @if($station->oic_phone)
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $station->oic_phone) }}"
                                       class="inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-gray-950 font-bold py-2 px-3 rounded-xl text-xs transition-colors">
                                        <i class="bi bi-person-badge-fill"></i>
                                        <span>OIC Mobile: {{ $station->oic_phone }}</span>
                                    </a>
                                @endif

                                {{-- Google Map Button --}}
                                @if($station->google_maps_url || ($station->latitude && $station->longitude))
                                    @php
                                        $mapUrl = $station->google_maps_url ?: "https://maps.google.com/?q={$station->latitude},{$station->longitude}";
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
                <i class="bi bi-shield-x text-5xl text-gray-300 mb-3"></i>
                <h3 class="text-lg font-bold text-gray-700">No police stations listed</h3>
            </div>
        @endif

    </div>
@endsection
