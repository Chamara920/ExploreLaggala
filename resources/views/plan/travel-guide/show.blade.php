@extends('layouts.public')

@php
$t = $guide->translationFor($locale);
@endphp

@section('title', ($t?->title ?? $guide->title) . ' — ' . __('travel_guide.page_title'))

@section('content')

{{-- ========= BREADCRUMB ========= --}}
<div class="border-b border-slate-100 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ url('/') }}" class="hover:text-emerald-600">{{ __('travel_guide.home') }}</a>
            <i class="bi bi-chevron-right text-[10px]"></i>
            <a href="{{ route('plan.travel-guide.index') }}" class="hover:text-emerald-600">{{ __('travel_guide.page_title') }}</a>
            <i class="bi bi-chevron-right text-[10px]"></i>
            <span class="text-slate-800 font-medium truncate max-w-xs">{{ $t?->title ?? $guide->title }}</span>
        </nav>
    </div>
</div>

{{-- ========= MAIN CONTENT ========= --}}
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-3 lg:gap-10">

        {{-- ARTICLE --}}
        <article class="lg:col-span-2">

            {{-- Category badge --}}
            <div class="mb-4 flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                    <i class="bi bi-tag"></i> {{ $guide->category_label }}
                </span>
                @if($guide->featured)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                    <i class="bi bi-star-fill"></i> {{ __('travel_guide.featured') }}
                </span>
                @endif

                {{-- Language switcher --}}
                <div class="ml-auto flex flex-wrap gap-1.5">
                    @foreach(['si' => 'සිංහල', 'en' => 'English', 'ta' => 'தமிழ்'] as $code => $label)
                    <a href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}"
                       class="rounded-full border px-3 py-1 text-xs font-semibold transition
                              {{ ($locale ?? app()->getLocale()) === $code
                                 ? 'border-emerald-500 bg-emerald-50 text-emerald-700 font-bold shadow-xs'
                                 : 'border-slate-200 bg-slate-50 text-slate-500 hover:border-emerald-300 hover:text-emerald-600' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </div>
            </div>

            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                {{ $t?->title ?? $guide->title }}
            </h1>

            @if($t?->summary ?? $guide->summary)
            <p class="mt-4 text-lg text-slate-600 leading-relaxed">{{ $t?->summary ?? $guide->summary }}</p>
            @endif

            <div class="mt-4 flex items-center gap-4 text-sm text-slate-400">
                <div class="flex items-center gap-1.5">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">
                        {{ strtoupper(substr($guide->author->name ?? 'A', 0, 1)) }}
                    </span>
                    <span>{{ $guide->author->name ?? 'Admin' }}</span>
                </div>
                <span>·</span>
                <span>{{ $guide->published_at?->format('d M Y') }}</span>
            </div>

            {{-- Cover Image --}}
            <div class="mt-6 overflow-hidden rounded-2xl bg-slate-100 shadow-sm max-h-[460px]">
                <img src="{{ $guide->cover_image_url }}" alt="{{ $t?->title ?? $guide->title }}"
                     class="h-72 w-full object-cover sm:h-96"
                     onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80';">
            </div>

            {{-- Article body: use translated content or fall back to base --}}
            <div class="prose prose-emerald prose-sm sm:prose-base mt-8 max-w-none text-slate-700"
                 style="line-height:1.8;">
                {!! $t?->content ?? $guide->content !!}
            </div>

        </article>

        {{-- SIDEBAR --}}
        <aside class="mt-10 lg:mt-0">

            {{-- Quick Info --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-3">
                    <i class="bi bi-info-circle text-emerald-600 me-1"></i> {{ __('travel_guide.quick_info') }}
                </h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">{{ __('travel_guide.category') }}</dt>
                        <dd class="font-semibold text-slate-800">{{ $guide->category_label }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">{{ __('travel_guide.author') }}</dt>
                        <dd class="font-semibold text-slate-800">{{ $guide->author->name ?? '—' }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">{{ __('travel_guide.published') }}</dt>
                        <dd class="font-semibold text-slate-800">{{ $guide->published_at?->format('d M Y') ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Related Guides --}}
            @if($relatedGuides->isNotEmpty())
            <div class="mt-6 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <h3 class="mb-4 text-sm font-bold text-slate-800">
                    <i class="bi bi-bookmarks text-emerald-600 me-1"></i> {{ __('travel_guide.related_guides') }}
                </h3>
                <div class="space-y-3">
                    @foreach($relatedGuides as $related)
                    @php $rt = $related->translationFor($locale); @endphp
                    <a href="{{ route('plan.travel-guide.show', $related->slug) }}"
                       class="group flex items-center gap-3 rounded-xl border border-transparent p-2 transition hover:border-emerald-100 hover:bg-emerald-50">
                        <div class="h-11 w-11 flex-shrink-0 overflow-hidden rounded-lg bg-slate-100">
                            <img src="{{ $related->cover_image_url }}" alt="{{ $rt?->title ?? $related->title }}"
                                 class="h-full w-full object-cover"
                                 onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=200&q=80';">
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-800 line-clamp-2 group-hover:text-emerald-700">{{ $rt?->title ?? $related->title }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Back to all --}}
            <div class="mt-6">
                <a href="{{ route('plan.travel-guide.index') }}"
                   class="flex w-full items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                    <i class="bi bi-arrow-left"></i> {{ __('travel_guide.back_to_all') }}
                </a>
            </div>

        </aside>
    </div>
</div>

@endsection
