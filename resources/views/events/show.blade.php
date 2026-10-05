@extends('layouts.public')

@section('title', $translation?->title ?? __('community.events'))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <nav class="text-sm text-gray-500">
            <a href="{{ url('/') }}" class="hover:text-purple-700">{{ __('community.home') }}</a>
            <span class="mx-2">/</span>
            <a href="{{ url('/community/events') }}" class="hover:text-purple-700">{{ __('community.events') }}</a>
            <span class="mx-2">/</span>
            <span class="text-gray-700">{{ $translation?->title }}</span>
        </nav>

        {{-- Report Button --}}
        @include('partials._report-modal', ['model' => $event])
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <article class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if ($event->cover_image)
            <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $translation?->title }}" class="w-full h-72 object-cover">
        @endif
        <div class="p-8">
            <div class="flex flex-wrap gap-3 mb-4">
                @if ($event->category)
                    <span class="bg-purple-100 text-purple-700 px-3 py-0.5 rounded-full text-xs font-medium">
                        {{ __('community.cat_' . $event->category->slug) !== 'community.cat_' . $event->category->slug ? __('community.cat_' . $event->category->slug) : $event->category->name }}
                    </span>
                @endif
                @if ($event->is_free ?? false)
                    <span class="bg-green-100 text-green-700 px-3 py-0.5 rounded-full text-xs">{{ __('community.free_entry') }}</span>
                @endif
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $translation?->title }}</h1>

            {{-- Event Details --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 p-5 bg-purple-50 rounded-xl">
                @if ($event->event_date)
                    <div class="flex items-start gap-2">
                        <span class="text-xl">📅</span>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">{{ __('community.event_date') }}</p>
                            <p class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
                            @if ($event->event_end_date)
                                <p class="text-sm text-gray-500">{{ __('community.to') }} {{ \Carbon\Carbon::parse($event->event_end_date)->format('F d, Y') }}</p>
                            @endif
                        </div>
                    </div>
                @endif
                @if ($event->location)
                    <div class="flex items-start gap-2">
                        <span class="text-xl">📍</span>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">{{ __('community.event_location') }}</p>
                            <p class="text-gray-800 font-medium">{{ $event->location }}</p>
                        </div>
                    </div>
                @endif
                @if ($event->organizer)
                    <div class="flex items-start gap-2">
                        <span class="text-xl">👤</span>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">{{ __('community.event_organizer') }}</p>
                            <p class="text-gray-800 font-medium">{{ $event->organizer }}</p>
                        </div>
                    </div>
                @endif
                @if ($event->contact_phone)
                    <div class="flex items-start gap-2">
                        <span class="text-xl">📞</span>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">{{ __('community.event_contact') }}</p>
                            <p class="text-gray-800 font-medium">{{ $event->contact_phone }}</p>
                        </div>
                    </div>
                @endif
            </div>

            @if ($translation?->excerpt)
                <p class="text-lg text-gray-600 mb-6 border-l-4 border-purple-400 pl-4 italic">{{ $translation->excerpt }}</p>
            @endif

            <div class="prose max-w-none text-gray-700 leading-relaxed">
                {!! $translation?->description ?? $translation?->content !!}
            </div>
        </div>
    </article>

    {{-- Comments Section --}}
    @include('partials._comments', ['model' => $event])

    @if ($related->count() > 0)
        <div class="mt-12">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('community.related_events') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($related as $item)
                    @php $rt = $item->translationFor($locale); @endphp
                    <a href="{{ url('/community/events/' . ($rt?->slug ?? $item->id)) }}" class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
                        <h3 class="font-semibold text-gray-900 text-sm">{{ $rt?->title ?? 'Untitled' }}</h3>
                        <p class="text-xs text-gray-400 mt-1">{{ $item->event_date ? \Carbon\Carbon::parse($item->event_date)->format('M d, Y') : '' }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
