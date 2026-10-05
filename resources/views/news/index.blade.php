@extends('layouts.public')

@section('title', __('community.news_title') . ' — Explore Laggala')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">

    <div class="mb-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-green-200 bg-green-50 px-3 py-1 text-xs font-semibold text-green-700 mb-3">
            <i class="bi bi-newspaper"></i> {{ __('community.portal_title') }}
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
            {{ __('community.news_title') }}
        </h1>
        <p class="text-gray-500 mt-2 text-base max-w-2xl leading-relaxed">
            {{ __('community.news_subtitle') }}
        </p>
    </div>

    {{-- Category Filter --}}
    <div class="flex flex-wrap items-center gap-2 mb-8">
        <a href="{{ url('/community/news') }}"
           class="px-4 py-2 rounded-full text-xs font-bold transition {{ !request('category') ? 'bg-green-700 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
            {{ __('community.all') }}
        </a>
        @foreach ($categories as $cat)
            @php
                $catName = __('community.cat_' . $cat->slug);
                if ($catName === 'community.cat_' . $cat->slug) {
                    $catName = $cat->name;
                }
            @endphp
            <a href="{{ url('/community/news?category=' . $cat->slug) }}"
               class="px-4 py-2 rounded-full text-xs font-bold transition {{ request('category') === $cat->slug ? 'bg-green-700 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                {{ $catName }}
            </a>
        @endforeach
    </div>

    {{-- News Grid --}}
    @if ($newsPosts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($newsPosts as $post)
                @php
                    $t = $post->translationFor($locale);
                    $catName = $post->category ? (__('community.cat_' . $post->category->slug) !== 'community.cat_' . $post->category->slug ? __('community.cat_' . $post->category->slug) : $post->category->name) : null;
                @endphp
                <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    @if ($post->cover_image)
                        <img src="{{ asset('storage/' . $post->cover_image) }}" alt="{{ $t?->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center text-4xl text-green-600">
                            <i class="bi bi-newspaper"></i>
                        </div>
                    @endif
                    <div class="p-5">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            @if ($catName)
                                <span class="text-xs font-semibold text-green-700 bg-green-50 border border-green-200 px-2.5 py-0.5 rounded-md">
                                    {{ $catName }}
                                </span>
                            @endif
                            @if ($post->featured)
                                <span class="text-[11px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full uppercase tracking-wider">
                                    ★ {{ __('community.featured') }}
                                </span>
                            @endif
                        </div>
                        <h2 class="font-bold text-gray-900 text-lg leading-snug hover:text-green-700 transition">
                            <a href="{{ url('/community/news/' . ($t?->slug ?? $post->id)) }}">
                                {{ $t?->title ?? 'Untitled' }}
                            </a>
                        </h2>
                        @if ($t?->excerpt)
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2 leading-relaxed">{{ $t->excerpt }}</p>
                        @endif
                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-50 text-xs text-gray-400">
                            <span><i class="bi bi-person me-1"></i>{{ $post->user?->name ?? __('community.community') }}</span>
                            <span>{{ $post->published_at?->format('M d, Y') }}</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $newsPosts->links() }}
        </div>
    @else
        <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 py-16 text-center bg-gray-50/50">
            <div class="h-16 w-16 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-3xl mb-3">
                <i class="bi bi-newspaper"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">{{ __('community.no_news_title') }}</h3>
            <p class="mt-1 text-sm text-gray-400 max-w-md">{{ __('community.no_news_desc') }}</p>
        </div>
    @endif

</div>
@endsection
