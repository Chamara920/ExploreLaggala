@extends('layouts.public')

@section('title', ($translation?->title ?? __('community.news_title')) . ' — Explore Laggala')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">

    {{-- Breadcrumb --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <nav class="flex items-center gap-2 text-xs sm:text-sm text-gray-500">
            <a href="{{ url('/') }}" class="hover:text-green-700 transition">{{ __('community.home') }}</a>
            <span class="text-gray-300">/</span>
            <a href="{{ url('/community/news') }}" class="hover:text-green-700 transition">{{ __('community.news_title') }}</a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-700 font-medium truncate max-w-xs">{{ $translation?->title }}</span>
        </nav>

        {{-- Report Button --}}
        @include('partials._report-modal', ['model' => $newsPost])
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-green-700 text-sm font-medium shadow-sm">{{ session('success') }}</div>
    @endif

    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if ($newsPost->cover_image)
            <img src="{{ asset('storage/' . $newsPost->cover_image) }}" alt="{{ $translation?->title }}" class="w-full h-80 object-cover">
        @endif
        <div class="p-6 sm:p-8">
            <div class="flex flex-wrap gap-2 items-center text-xs text-gray-500 mb-4">
                @if ($newsPost->category)
                    @php
                        $catName = __('community.cat_' . $newsPost->category->slug);
                        if ($catName === 'community.cat_' . $newsPost->category->slug) {
                            $catName = $newsPost->category->name;
                        }
                    @endphp
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-bold">{{ $catName }}</span>
                @endif
                <span>{{ $newsPost->published_at?->format('F d, Y') }}</span>
                <span>·</span>
                <span>{{ __('community.by') }} {{ $newsPost->user?->name ?? __('community.community') }}</span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 mb-6 leading-tight">{{ $translation?->title }}</h1>

            @if ($translation?->excerpt)
                <p class="text-base sm:text-lg text-gray-600 mb-6 border-l-4 border-green-500 pl-4 italic leading-relaxed">{{ $translation->excerpt }}</p>
            @endif

            <div class="prose max-w-none text-gray-700 leading-relaxed text-sm sm:text-base">
                {!! $translation?->content !!}
            </div>
        </div>
    </article>

    {{-- Comments Section --}}
    @include('partials._comments', ['model' => $newsPost])

    {{-- Related News --}}
    @if ($related->count() > 0)
        <div class="mt-12">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-newspaper text-green-600"></i>
                {{ __('community.related_news') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($related as $item)
                    @php $rt = $item->translationFor($locale); @endphp
                    <a href="{{ url('/community/news/' . ($rt?->slug ?? $item->id)) }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition">
                        <h3 class="font-bold text-gray-900 text-sm leading-snug hover:text-green-700">{{ $rt?->title ?? 'Untitled' }}</h3>
                        <p class="text-xs text-gray-400 mt-2">{{ $item->published_at?->format('M d, Y') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
