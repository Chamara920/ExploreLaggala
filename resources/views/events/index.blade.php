@extends('layouts.public')

@section('title', __('community.events_title') . ' — Explore Laggala')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">

    <div class="mb-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-purple-200 bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700 mb-3">
            <i class="bi bi-calendar-event"></i> {{ __('community.portal_title') }}
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
            {{ __('community.events_title') }}
        </h1>
        <p class="text-gray-500 mt-2 text-base max-w-2xl leading-relaxed">
            {{ __('community.events_subtitle') }}
        </p>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-2 mb-8">
        <a href="{{ url('/community/events') }}"
           class="px-4 py-2 rounded-full text-xs font-bold transition {{ !request('filter') || request('filter') === 'upcoming' ? 'bg-purple-700 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
            {{ __('community.upcoming_events') }}
        </a>
        <a href="{{ url('/community/events?filter=past') }}"
           class="px-4 py-2 rounded-full text-xs font-bold transition {{ request('filter') === 'past' ? 'bg-purple-700 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
            {{ __('community.past_events') }}
        </a>
        @foreach ($categories as $cat)
            @php
                $catName = __('community.cat_' . $cat->slug);
                if ($catName === 'community.cat_' . $cat->slug) {
                    $catName = $cat->name;
                }
            @endphp
            <a href="{{ url('/community/events?category=' . $cat->slug . (request('filter') ? '&filter=' . request('filter') : '')) }}"
               class="px-4 py-2 rounded-full text-xs font-bold transition {{ request('category') === $cat->slug ? 'bg-purple-700 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                {{ $catName }}
            </a>
        @endforeach
    </div>

    @if ($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($events as $event)
                @php
                    $t = $event->translationFor($locale);
                    $eventDate = $event->start_date ? \Carbon\Carbon::parse($event->start_date) : null;
                    $locName = $event->location_name ?? $event->location;
                    $catName = $event->category ? (__('community.cat_' . $event->category->slug) !== 'community.cat_' . $event->category->slug ? __('community.cat_' . $event->category->slug) : $event->category->name) : null;
                @endphp
                <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    @if ($event->cover_image)
                        <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $t?->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-purple-50 to-indigo-100 flex items-center justify-center text-4xl text-purple-600">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                    @endif
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-2.5">
                            @if ($eventDate)
                                <span class="text-xs font-extrabold bg-purple-100 text-purple-800 px-2.5 py-0.5 rounded-md">
                                    {{ $eventDate->format('M d, Y') }}
                                </span>
                            @endif
                            @if ($catName)
                                <span class="text-xs font-medium bg-gray-100 text-gray-700 px-2 py-0.5 rounded-md">
                                    {{ $catName }}
                                </span>
                            @endif
                            @if ($event->is_free ?? false)
                                <span class="text-xs font-semibold bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-md">
                                    {{ __('community.free_entry') }}
                                </span>
                            @endif
                        </div>
                        <h2 class="font-bold text-gray-900 text-lg leading-snug hover:text-purple-700 transition">
                            <a href="{{ url('/community/events/' . ($t?->slug ?? $event->id)) }}">
                                {{ $t?->title ?? 'Untitled' }}
                            </a>
                        </h2>
                        @if ($locName)
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                <i class="bi bi-geo-alt text-purple-600"></i> {{ $locName }}
                            </p>
                        @endif
                        @if ($t?->excerpt)
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2 leading-relaxed">{{ $t->excerpt }}</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-8">{{ $events->links() }}</div>
    @else
        <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 py-16 text-center bg-gray-50/50">
            <div class="h-16 w-16 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-3xl mb-3">
                <i class="bi bi-calendar-x"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">
                {{ request('filter') === 'past' ? __('community.no_past_events') : __('community.no_upcoming_events') }}
            </h3>
        </div>
    @endif

</div>
@endsection
