@extends('layouts.public')

@section('title', 'Explore Laggala — Discover • Explore • Experience')

@section('content')

    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-gray-900">
        <div class="mx-auto max-w-7xl px-6 py-20 lg:px-8 lg:py-28">

            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-widest text-green-400">
                    Explore Laggala
                </p>

                <h1 class="mt-4 text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    Discover Laggala
                </h1>

                <p class="mt-6 text-lg leading-8 text-gray-300">
                    Explore destinations, culture, heritage, outdoor adventures
                    and beautiful places around Laggala.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('explore.destinations.index') }}"
                       class="rounded-lg bg-green-600 px-6 py-3 text-sm font-semibold text-white hover:bg-green-700">
                        Explore Destinations
                    </a>

                    <a href="{{ route('explore.map') }}"
                       class="rounded-lg border border-white/30 px-6 py-3 text-sm font-semibold text-white hover:bg-white/10">
                        Interactive Map
                    </a>
                </div>
            </div>

        </div>
    </section>


    {{-- Featured Destinations --}}
    <section class="mx-auto max-w-7xl px-6 py-16 lg:px-8">

        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-green-600">
                    Destinations
                </p>

                <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900">
                    Featured Places
                </h2>

                <p class="mt-3 max-w-2xl text-gray-600">
                    Discover some of the places worth exploring in Laggala.
                </p>
            </div>

            <a href="{{ route('explore.destinations.index') }}"
               class="hidden text-sm font-semibold text-green-700 hover:text-green-800 sm:block">
                View All →
            </a>
        </div>


        @if($featuredDestinations->count())

            <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">

                @foreach($featuredDestinations as $destination)

                    @php
                        $translation = $destination->translationFor(app()->getLocale());
                        $coverImage = $destination->coverImage();
                    @endphp

                    <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

                        @if($coverImage)
                            <img
                                src="{{ asset('storage/' . $coverImage->image_path) }}"
                                alt="{{ $translation?->name }}"
                                class="h-56 w-full object-cover"
                            >
                        @else
                            <div class="flex h-56 items-center justify-center bg-gray-100 text-gray-400">
                                No Image
                            </div>
                        @endif

                        <div class="p-6">

                            <div class="flex items-start justify-between gap-4">

                                <h3 class="text-xl font-bold text-gray-900">
                                    {{ $translation?->name ?? 'Destination' }}
                                </h3>

                                @if($destination->average_rating > 0)
                                    <span class="whitespace-nowrap text-sm font-semibold text-amber-600">
                                        ★ {{ number_format($destination->average_rating, 1) }}
                                    </span>
                                @endif

                            </div>

                            @if($translation?->location_name)
                                <p class="mt-2 text-sm text-gray-500">
                                    📍 {{ $translation->location_name }}
                                </p>
                            @endif

                            @if($translation?->short_description)
                                <p class="mt-4 line-clamp-3 text-sm leading-6 text-gray-600">
                                    {{ $translation->short_description }}
                                </p>
                            @endif

                            <a href="{{ route('explore.destinations.show', $translation->slug) }}"
                               class="mt-5 inline-flex text-sm font-semibold text-green-700 hover:text-green-800">
                                View Details →
                            </a>

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            <div class="mt-10 rounded-xl border border-dashed border-gray-300 p-10 text-center">
                <p class="text-gray-500">
                    No featured destinations available yet.
                </p>
            </div>

        @endif

    </section>


    {{-- Explore Categories --}}
    <section class="bg-gray-50">
        <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">

            <div class="grid gap-6 md:grid-cols-3">

                <a href="{{ route('explore.destinations.index') }}"
                   class="rounded-2xl bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-3xl">📍</div>
                    <h3 class="mt-4 text-xl font-bold text-gray-900">
                        Destinations
                    </h3>
                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        Discover beautiful places and destinations around Laggala.
                    </p>
                </a>

                <a href="{{ route('explore.culture-heritage.index') }}"
                   class="rounded-2xl bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-3xl">🏛️</div>
                    <h3 class="mt-4 text-xl font-bold text-gray-900">
                        Culture & Heritage
                    </h3>
                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        Explore the cultural and historical heritage of the area.
                    </p>
                </a>

                <a href="{{ route('explore.outdoor-adventure.index') }}"
                   class="rounded-2xl bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-3xl">🥾</div>
                    <h3 class="mt-4 text-xl font-bold text-gray-900">
                        Outdoor & Adventure
                    </h3>
                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        Find outdoor activities and adventure experiences.
                    </p>
                </a>

            </div>

        </div>
    </section>

</div>

@endsection