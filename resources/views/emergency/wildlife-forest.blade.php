@extends('layouts.public')

@php
    $lang = $locale ?? session('locale', 'si');
    $title = match($lang) {
        'si' => 'වනජීවී සහ වන සංරක්ෂණ කාර්යාල — Wildlife & Forest',
        'ta' => 'வனவிலங்கு & வன பாதுகாப்பு அலுவலகங்கள் — Wildlife & Forest',
        default => 'Wildlife & Forest Conservation Offices — Knuckles & Laggala',
    };
    $subtitle = match($lang) {
        'si' => 'නකල්ස් රක්ෂිත ප්‍රවේශ අවසරපත්, කඳුකර මඟපෙන්වන්නන්, වන සතුන්ගෙන් සිදුවන ආපදා සහ වන ගිනි හදිසි ප්‍රතිචාර මධ්‍යස්ථාන.',
        'ta' => 'நக்கிள்ஸ் நுழைவு அனுமதி, காட்டுத்தீ மற்றும் வனவிலங்கு அவசர மீட்பு அலுவலகங்கள்.',
        default => 'Knuckles forest entry permits, wildlife distress hotlines, elephant encounters, lost hiker rescue, and forest range posts.',
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

        {{-- Department Filters --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mr-1">
                        <i class="bi bi-funnel"></i> Department:
                    </span>

                    <a href="{{ route('emergency.wildlife-forest', ['lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ empty($department) || $department === 'all' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All Departments
                    </a>
                    <a href="{{ route('emergency.wildlife-forest', ['department' => 'conservation', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($department ?? '') === 'conservation' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Knuckles Conservation Centers (නකල්ස් මධ්‍යස්ථාන)
                    </a>
                    <a href="{{ route('emergency.wildlife-forest', ['department' => 'wildlife', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($department ?? '') === 'wildlife' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Wildlife Dept - DWC (වනජීවී දෙපාර්තමේන්තුව)
                    </a>
                    <a href="{{ route('emergency.wildlife-forest', ['department' => 'forest', 'lang' => $lang]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($department ?? '') === 'forest' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Forest Range Offices (වන සංරක්ෂණ)
                    </a>
                </div>

                <div class="text-xs text-gray-500 font-medium">
                    Found <span class="font-bold text-gray-800">{{ $offices->count() }}</span> conservation posts
                </div>
            </div>
        </div>

        {{-- Wildlife & Forest Offices Grid --}}
        @if($offices->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($offices as $office)
                    @php $t = $office->translationFor($lang); @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all overflow-hidden flex flex-col justify-between">
                        <div>
                            @if($office->image)
                                <img src="{{ asset('storage/' . $office->image) }}" alt="Office Photo" class="w-full h-44 object-cover">
                            @endif

                            <div class="p-6">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider
                                        {{ $office->department_type === 'conservation' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                        {{ $office->department_type === 'wildlife' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $office->department_type === 'forest' ? 'bg-teal-100 text-teal-800' : '' }}">
                                        {{ strtoupper($office->department_type) }}
                                    </span>

                                    @if($office->emergency_hotline)
                                        <span class="bg-red-50 text-red-700 border border-red-200 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Rapid Rescue
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-xl font-bold text-gray-900 leading-snug">
                                    {{ $t?->name ?? 'Wildlife / Forest Office' }}
                                </h3>

                                @if($office->range_area)
                                    <div class="text-xs text-emerald-700 font-semibold mt-1 flex items-center gap-1">
                                        <i class="bi bi-geo-alt"></i> {{ $office->range_area }}
                                    </div>
                                @endif

                                @if($t?->address)
                                    <div class="text-xs text-gray-500 mt-2 flex items-start gap-1.5">
                                        <i class="bi bi-pin-map text-gray-400 mt-0.5"></i>
                                        <span>{{ $t->address }}</span>
                                    </div>
                                @endif

                                @if($t?->duties_description)
                                    <div class="mt-4 p-3 bg-emerald-50/60 rounded-xl border border-emerald-100/60">
                                        <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-900 mb-1 flex items-center gap-1">
                                            <i class="bi bi-ticket-perforated"></i> Permits & Rescue Duties
                                        </div>
                                        <p class="text-xs text-emerald-950 leading-relaxed">
                                            {{ $t->duties_description }}
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
                                {{-- Phone Contact --}}
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $office->phone) }}"
                                   class="inline-flex items-center justify-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-2.5 px-3 rounded-xl text-xs shadow-sm transition-all">
                                    <i class="bi bi-telephone-fill"></i>
                                    <span>Call Office: {{ $office->phone }}</span>
                                </a>

                                @if($office->emergency_hotline)
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $office->emergency_hotline) }}"
                                       class="inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-bold py-2 px-3 rounded-xl text-xs transition-colors">
                                        <i class="bi bi-exclamation-octagon-fill"></i>
                                        <span>Wildlife Emergency: {{ $office->emergency_hotline }}</span>
                                    </a>
                                @endif

                                {{-- Google Map Button --}}
                                @if($office->google_maps_url || ($office->latitude && $office->longitude))
                                    @php
                                        $mapUrl = $office->google_maps_url ?: "https://maps.google.com/?q={$office->latitude},{$office->longitude}";
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
                <i class="bi bi-tree text-5xl text-gray-300 mb-3"></i>
                <h3 class="text-lg font-bold text-gray-700">No forest or wildlife offices found</h3>
            </div>
        @endif

    </div>
@endsection
