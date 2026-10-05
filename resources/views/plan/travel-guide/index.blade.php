@extends('layouts.public')

@section('title', __('travel_guide.page_title'))

@section('content')

{{-- ========= HERO ========= --}}
<section class="relative overflow-hidden" style="background:linear-gradient(135deg,#0d3d20 0%,#14532d 50%,#166534 100%);">
    <div class="absolute inset-0 opacity-10">
        <div style="background-image:url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1800&q=60');background-size:cover;background-position:center;width:100%;height:100%;"></div>
    </div>
    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
        <div class="max-w-3xl">
            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-300">
                <i class="bi bi-journal-text"></i> {{ __('travel_guide.badge_plan') }}
            </div>
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                {{ __('travel_guide.hero_title') }}
            </h1>
            <p class="mt-4 max-w-2xl text-lg text-white/80">
                {{ __('travel_guide.hero_subtitle') }}
            </p>
        </div>

        {{-- Language switcher --}}
        <div class="mt-6 flex flex-wrap gap-2">
            @foreach(['si' => 'සිංහල', 'en' => 'English', 'ta' => 'தமிழ்'] as $code => $label)
            <a href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}"
               class="rounded-full border px-3 py-1 text-xs font-semibold transition
                      {{ ($locale ?? app()->getLocale()) === $code
                         ? 'border-emerald-300 bg-emerald-400/30 text-white shadow-xs'
                         : 'border-white/20 bg-white/10 text-white/70 hover:bg-white/20' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ========= FLASH MESSAGE ========= --}}
@if(session('success'))
<div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
    <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800">
        <i class="bi bi-check-circle-fill text-lg"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
</div>
@endif

{{-- ========= FEATURED GUIDES ========= --}}
@if($featuredGuides->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 pt-12 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center gap-3">
        <span class="h-1.5 w-7 rounded-full bg-emerald-500"></span>
        <h2 class="text-2xl font-extrabold text-slate-900">{{ __('travel_guide.essential_reads') }}</h2>
    </div>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($featuredGuides as $guide)
        @php $t = $guide->translationFor($locale); @endphp
        <a href="{{ route('plan.travel-guide.show', $guide->slug) }}"
           class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-xl">
            <div class="h-48 w-full overflow-hidden bg-slate-100 relative">
                <img src="{{ $guide->cover_image_url }}" alt="{{ $t?->title ?? $guide->title }}"
                     class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                     onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80';">
                <span class="absolute top-3 left-3 inline-flex items-center gap-1 rounded-full bg-emerald-600/90 backdrop-blur-xs px-2.5 py-0.5 text-xs font-semibold text-white shadow-xs">
                    <i class="bi bi-tag"></i> {{ $guide->category_label }}
                </span>
            </div>

            <div class="flex flex-1 flex-col p-5">
                <h3 class="text-base font-bold text-slate-900 line-clamp-2 group-hover:text-emerald-700 transition">
                    {{ $t?->title ?? $guide->title }}
                </h3>
                @if($t?->summary ?? $guide->summary)
                    <p class="mt-2 text-sm text-slate-500 line-clamp-2 leading-relaxed">
                        {{ $t?->summary ?? $guide->summary }}
                    </p>
                @endif
                <div class="mt-auto flex items-center justify-between pt-4 border-t border-slate-100 text-xs">
                    <span class="text-slate-400">{{ $guide->published_at?->format('d M Y') }}</span>
                    <span class="font-semibold text-emerald-600 group-hover:text-emerald-700 inline-flex items-center gap-1">
                        {{ __('travel_guide.read_more') }}
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ========= ALL GUIDES BY CATEGORY ========= --}}
<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">

    @php
    $categoryIcons = [
        'getting_here'       => ['icon'=>'bi-signpost-2',    'bg'=>'bg-blue-50',   'color'=>'text-blue-600'],
        'accommodation'      => ['icon'=>'bi-building',      'bg'=>'bg-violet-50', 'color'=>'text-violet-600'],
        'food_drink'         => ['icon'=>'bi-cup-hot',       'bg'=>'bg-orange-50', 'color'=>'text-orange-600'],
        'safety_tips'        => ['icon'=>'bi-shield-check',  'bg'=>'bg-red-50',    'color'=>'text-red-600'],
        'cultural_etiquette' => ['icon'=>'bi-flower1',       'bg'=>'bg-pink-50',   'color'=>'text-pink-600'],
        'packing_list'       => ['icon'=>'bi-backpack2',     'bg'=>'bg-teal-50',   'color'=>'text-teal-600'],
        'best_time_to_visit' => ['icon'=>'bi-calendar-heart','bg'=>'bg-amber-50',  'color'=>'text-amber-600'],
        'local_customs'      => ['icon'=>'bi-people',        'bg'=>'bg-emerald-50','color'=>'text-emerald-600'],
        'transportation'     => ['icon'=>'bi-bus-front',     'bg'=>'bg-sky-50',    'color'=>'text-sky-600'],
        'money_budget'       => ['icon'=>'bi-cash-coin',     'bg'=>'bg-green-50',  'color'=>'text-green-600'],
        'health_medical'     => ['icon'=>'bi-heart-pulse',   'bg'=>'bg-rose-50',   'color'=>'text-rose-600'],
        'general'            => ['icon'=>'bi-info-circle',   'bg'=>'bg-slate-50',  'color'=>'text-slate-600'],
    ];
    @endphp

    @if($guides->isEmpty())
        <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 py-20 text-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100">
                <i class="bi bi-journal-x text-3xl text-slate-400"></i>
            </div>
            <h3 class="mt-4 text-lg font-bold text-slate-700">{{ __('travel_guide.no_guides') }}</h3>
            <p class="mt-1 text-sm text-slate-400">{{ __('travel_guide.no_guides_desc') }}</p>
        </div>
    @else
        @foreach($guides as $category => $categoryGuides)
        @php
            $ci = $categoryIcons[$category] ?? $categoryIcons['general'];
        @endphp
        <div class="mb-12">
            <div class="mb-5 flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ $ci['bg'] }}">
                    <i class="bi {{ $ci['icon'] }} text-lg {{ $ci['color'] }}"></i>
                </span>
                <h2 class="text-xl font-extrabold text-slate-900">{{ $categoryGuides->first()->category_label }}</h2>
                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500">{{ $categoryGuides->count() }}</span>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($categoryGuides as $guide)
                @php $t = $guide->translationFor($locale); @endphp
                <a href="{{ route('plan.travel-guide.show', $guide->slug) }}"
                   class="group flex flex-col h-full overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-xl">
                    {{-- Cover Image --}}
                    <div class="h-48 w-full overflow-hidden bg-slate-100 relative">
                        <img src="{{ $guide->cover_image_url }}" alt="{{ $t?->title ?? $guide->title }}"
                             class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80';">
                        <span class="absolute top-3 left-3 inline-flex items-center gap-1 rounded-full bg-slate-900/75 backdrop-blur-xs px-2.5 py-1 text-[11px] font-bold text-white shadow-xs">
                            <i class="bi bi-tag-fill text-emerald-400"></i> {{ $guide->category_label }}
                        </span>
                    </div>

                    <div class="flex flex-1 flex-col p-5">
                        <h3 class="text-base font-bold text-slate-900 line-clamp-2 group-hover:text-emerald-700 transition">
                            {{ $t?->title ?? $guide->title }}
                        </h3>
                        @if($t?->summary ?? $guide->summary)
                            <p class="mt-2 text-sm text-slate-600 line-clamp-2 leading-relaxed">
                                {{ $t?->summary ?? $guide->summary }}
                            </p>
                        @endif
                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-slate-100 text-xs">
                            <span class="text-slate-400 font-medium">
                                <i class="bi bi-calendar3 me-1"></i>{{ ($guide->published_at ?? $guide->created_at)?->format('d M Y') }}
                            </span>
                            <span class="font-bold text-emerald-600 group-hover:text-emerald-700 inline-flex items-center gap-1">
                                {{ __('travel_guide.read_more') }}
                                <i class="bi bi-arrow-right transition duration-200 group-hover:translate-x-1"></i>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endforeach
    @endif
</section>

@endsection
