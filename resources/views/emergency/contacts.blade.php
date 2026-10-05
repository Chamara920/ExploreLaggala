@extends('layouts.public')

@php
    $lang = $locale ?? session('locale', 'si');
    $title = match($lang) {
        'si' => 'හදිසි ඇමතුම් සහ සේවා — Emergency Contacts',
        'ta' => 'அவசர உதவி எண்கள் — Emergency Contacts',
        default => 'Emergency Contacts & Hotlines — Laggala',
    };
    $subtitle = match($lang) {
        'si' => 'ලග්ගල, නකල්ස් සහ රිවස්ටන් කලාපය සඳහා අත්‍යවශ්‍ය හදිසි දුරකථන අංක සහ ක්ෂණික සේවා.',
        'ta' => 'லக்கல, நக்கிள்ஸ் மற்றும் ரிவர்ஸ்டன் பகுதிகளுக்கான அவசர உதவி தொலைபேசி எண்கள்.',
        default => 'Critical 24/7 telephone hotlines, medical rescue, police, and disaster response for Laggala & Knuckles.',
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

        {{-- Quick Dial Emergency Grid --}}
        <div class="mb-10">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-lightning-charge-fill text-amber-500"></i>
                <span>
                    @if($lang === 'si') ක්ෂණික හදිසි ඇමතුම් අංක @elseif($lang === 'ta') உடனடி அவசர அழைப்புகள் @else Critical Speed Dials (Nationwide & Local) @endif
                </span>
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4">
                {{-- 1990 Ambulance --}}
                <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl p-5 text-white shadow-lg shadow-red-500/20 hover:shadow-xl hover:scale-[1.02] transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-red-100">
                            <span>Ambulance</span>
                            <span class="bg-white/20 px-2 py-0.5 rounded-full">24/7 Free</span>
                        </div>
                        <div class="text-4xl font-extrabold my-2 tracking-tight">1990</div>
                        <div class="text-xs text-red-100/90 leading-snug">
                            @if($lang === 'si') සුවසැරිය ගිලන්රථ සේවය @elseif($lang === 'ta') சுவசரிய இலவச ஆம்புலன்ஸ் @else Suwa Seriya Pre-Hospital Ambulance @endif
                        </div>
                    </div>
                    <a href="tel:1990" class="mt-4 inline-flex items-center justify-center gap-2 bg-white text-red-600 font-bold py-2 px-3 rounded-xl text-xs shadow hover:bg-red-50 transition-colors">
                        <i class="bi bi-telephone-fill"></i> Call 1990
                    </a>
                </div>

                {{-- 119 Police --}}
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-5 text-white shadow-lg shadow-blue-600/20 hover:shadow-xl hover:scale-[1.02] transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-blue-100">
                            <span>Police Emergency</span>
                            <span class="bg-white/20 px-2 py-0.5 rounded-full">24/7</span>
                        </div>
                        <div class="text-4xl font-extrabold my-2 tracking-tight">119</div>
                        <div class="text-xs text-blue-100/90 leading-snug">
                            @if($lang === 'si') ජාතික පොලිස් හදිසි ඇමතුම් @elseif($lang === 'ta') காவல்துறை அவசர உதவி @else National Police Emergency Hotline @endif
                        </div>
                    </div>
                    <a href="tel:119" class="mt-4 inline-flex items-center justify-center gap-2 bg-white text-blue-700 font-bold py-2 px-3 rounded-xl text-xs shadow hover:bg-blue-50 transition-colors">
                        <i class="bi bi-telephone-fill"></i> Call 119
                    </a>
                </div>

                {{-- 117 Disaster --}}
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-5 text-white shadow-lg shadow-amber-500/20 hover:shadow-xl hover:scale-[1.02] transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-amber-100">
                            <span>Disaster Alerts</span>
                            <span class="bg-white/20 px-2 py-0.5 rounded-full">DMC</span>
                        </div>
                        <div class="text-4xl font-extrabold my-2 tracking-tight">117</div>
                        <div class="text-xs text-amber-100/90 leading-snug">
                            @if($lang === 'si') ආපදා කළමනාකරණ මධ්‍යස්ථානය @elseif($lang === 'ta') அனர்த்த முகாமைத்துவம் @else Disaster Management & Landslide Alerts @endif
                        </div>
                    </div>
                    <a href="tel:117" class="mt-4 inline-flex items-center justify-center gap-2 bg-white text-orange-600 font-bold py-2 px-3 rounded-xl text-xs shadow hover:bg-amber-50 transition-colors">
                        <i class="bi bi-telephone-fill"></i> Call 117
                    </a>
                </div>

                {{-- 110 Fire & Rescue --}}
                <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-5 text-white shadow-lg shadow-emerald-600/20 hover:shadow-xl hover:scale-[1.02] transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-emerald-100">
                            <span>Fire & Rescue</span>
                            <span class="bg-white/20 px-2 py-0.5 rounded-full">Rapid</span>
                        </div>
                        <div class="text-4xl font-extrabold my-2 tracking-tight">110</div>
                        <div class="text-xs text-emerald-100/90 leading-snug">
                            @if($lang === 'si') ගිනි නිවීම හා මුදවාගැනීම් @elseif($lang === 'ta') தீயணைப்பு & மீட்பு @else Fire Extinguishing & Rescue Extraction @endif
                        </div>
                    </div>
                    <a href="tel:110" class="mt-4 inline-flex items-center justify-center gap-2 bg-white text-teal-700 font-bold py-2 px-3 rounded-xl text-xs shadow hover:bg-emerald-50 transition-colors">
                        <i class="bi bi-telephone-fill"></i> Call 110
                    </a>
                </div>
            </div>
        </div>

        {{-- Category Filters --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mr-1">
                        <i class="bi bi-funnel"></i> Filter:
                    </span>

                    <a href="{{ route('emergency.contacts', ['lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ empty($category) || $category === 'all' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All Contacts
                    </a>
                    <a href="{{ route('emergency.contacts', ['category' => 'medical', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($category ?? '') === 'medical' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Medical
                    </a>
                    <a href="{{ route('emergency.contacts', ['category' => 'police', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($category ?? '') === 'police' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Police & Security
                    </a>
                    <a href="{{ route('emergency.contacts', ['category' => 'disaster', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($category ?? '') === 'disaster' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Disaster
                    </a>
                    <a href="{{ route('emergency.contacts', ['category' => 'hotline', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($category ?? '') === 'hotline' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Hotlines
                    </a>
                    <a href="{{ route('emergency.contacts', ['category' => 'local', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($category ?? '') === 'local' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Local Administration
                    </a>
                </div>

                <div class="text-xs text-gray-500 font-medium">
                    Showing <span class="font-bold text-gray-800">{{ $contacts->count() }}</span> emergency numbers
                </div>
            </div>
        </div>

        {{-- Contacts Directory Grid --}}
        @if($contacts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($contacts as $contact)
                    @php $t = $contact->translationFor($lang); @endphp
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider
                                    {{ $contact->category === 'medical' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $contact->category === 'police' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $contact->category === 'disaster' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $contact->category === 'hotline' ? 'bg-rose-100 text-rose-700' : '' }}
                                    {{ $contact->category === 'local' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ !in_array($contact->category, ['medical', 'police', 'disaster', 'hotline', 'local']) ? 'bg-gray-100 text-gray-700' : '' }}">
                                    {{ strtoupper($contact->category) }}
                                </span>

                                <div class="flex items-center gap-1.5">
                                    @if($contact->is_toll_free)
                                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                            Toll-Free
                                        </span>
                                    @endif
                                    @if($contact->is_24x7)
                                        <span class="bg-red-50 text-red-700 border border-red-200 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> 24/7
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 leading-snug">
                                {{ $t?->name ?? 'Emergency Service' }}
                            </h3>

                            @if($t?->department)
                                <div class="text-xs text-gray-500 font-medium mt-1 flex items-center gap-1">
                                    <i class="bi bi-building"></i> {{ $t->department }}
                                </div>
                            @endif

                            @if($t?->description)
                                <p class="text-xs text-gray-600 mt-3 leading-relaxed">
                                    {{ $t->description }}
                                </p>
                            @endif

                            @if($t?->address)
                                <div class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                    <i class="bi bi-geo-alt"></i> {{ $t->address }}
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider">Dial Number</div>
                                    <div class="text-xl font-extrabold text-gray-900 tracking-tight">
                                        {{ $contact->phone_number }}
                                    </div>
                                    @if($contact->alternate_phone)
                                        <div class="text-[11px] text-gray-500">Alt: {{ $contact->alternate_phone }}</div>
                                    @endif
                                </div>

                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact->phone_number) }}"
                                   class="inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md shadow-red-500/20 transition-all">
                                    <i class="bi bi-telephone-outbound-fill"></i> Call Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
                <i class="bi bi-telephone-x text-5xl text-gray-300 mb-3"></i>
                <h3 class="text-lg font-bold text-gray-700">No emergency contacts found</h3>
                <p class="text-sm text-gray-500 mt-1">Please try choosing a different category filter.</p>
            </div>
        @endif

    </div>
@endsection
