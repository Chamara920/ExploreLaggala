@extends('layouts.public')

@section('title', __('pages.contributors_title') . ' — Explore Laggala')

@section('content')

{{-- ============================================================
     HERO BANNER
============================================================ --}}
<section class="relative bg-gradient-to-br from-emerald-950 via-slate-900 to-teal-950 text-white py-14 lg:py-20 overflow-hidden">
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-500/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold uppercase tracking-wider mb-5">
            <i class="fas fa-users"></i>
            <span>{{ __('pages.contributors_hero_tag') }}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
            {{ __('pages.contributors_title') }}
        </h1>
        <p class="mt-4 text-base sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
            {{ __('pages.contributors_intro') }}
        </p>
    </div>
</section>

{{-- ============================================================
     CONTRIBUTORS LIST / POSTS PANEL
============================================================ --}}
<section class="bg-gray-50 py-12 lg:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        @if ($selectedUser)
            {{-- ================================
                 SELECTED USER POSTS VIEW
            ================================ --}}
            <div class="mb-6">
                <a href="{{ route('pages.contributors') }}" class="inline-flex items-center gap-2 text-sm text-emerald-700 hover:text-emerald-600 font-medium transition">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('pages.contributors_back') }}
                </a>
            </div>

            {{-- User Header --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 flex flex-col sm:flex-row sm:items-center gap-5">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-2xl font-extrabold shadow flex-shrink-0">
                    {{ mb_substr($selectedUser->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $selectedUser->name }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ __('pages.contributors_posts_by') }}: <strong>{{ $selectedUser->name }}</strong>
                        &nbsp;·&nbsp; {{ $userPosts->count() }} {{ __('pages.contributors_total_posts') }}
                    </p>
                </div>
            </div>

            @if ($userPosts->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <i class="fas fa-inbox text-4xl mb-3"></i>
                    <p class="text-sm">{{ __('pages.contributors_no_posts') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach ($userPosts as $post)
                        @php
                            $typeColors = [
                                'blog'         => 'emerald',
                                'news'         => 'blue',
                                'event'        => 'purple',
                                'organization' => 'orange',
                                'forum'        => 'teal',
                            ];
                            $color = $typeColors[$post['type']] ?? 'gray';
                        @endphp
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col gap-3 hover:shadow-md transition">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-{{ $color }}-100 text-{{ $color }}-700">
                                    <i class="fas {{ $post['icon'] }}"></i>
                                    {{ ucfirst($post['label']) }}
                                </span>
                                @if ($post['published_at'])
                                    <span class="text-xs text-gray-400 ml-auto">
                                        {{ \Carbon\Carbon::parse($post['published_at'])->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-base font-semibold text-gray-800 leading-snug flex-1">
                                {{ $post['title'] }}
                            </h3>
                            @if ($post['slug'] ?? null)
                                <a href="{{ route($post['route'], $post['slug']) }}"
                                   class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 hover:text-emerald-500 transition mt-auto">
                                    {{ __('pages.contributors_read') }}
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        @elseif ($contributors->isEmpty())

            {{-- EMPTY STATE --}}
            <div class="text-center py-20 text-gray-400">
                <i class="fas fa-user-slash text-5xl mb-4"></i>
                <p class="text-base">{{ __('pages.contributors_empty') }}</p>
                <a href="{{ route('register') }}" class="mt-4 inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow transition">
                    <i class="fas fa-user-plus"></i>
                    {{ __('pages.guidelines_become_cta') }}
                </a>
            </div>

        @else

            {{-- ================================
                 CONTRIBUTORS GRID
            ================================ --}}
            <h2 class="text-lg font-semibold text-gray-700 mb-6">
                {{ __('pages.contributors_select_prompt') }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach ($contributors as $contributor)
                    <a href="{{ route('pages.contributors', ['user' => $contributor->id]) }}"
                       id="contributor-{{ $contributor->id }}"
                       class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col items-center text-center gap-3 hover:shadow-lg hover:border-emerald-300 transition cursor-pointer">

                        {{-- Avatar --}}
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-2xl font-extrabold shadow group-hover:scale-105 transition">
                            {{ mb_substr($contributor->name, 0, 1) }}
                        </div>

                        {{-- Name & Role --}}
                        <div>
                            <div class="font-bold text-gray-900 text-base group-hover:text-emerald-700 transition">{{ $contributor->name }}</div>
                            @if ($contributor->hasAnyRole(['admin', 'super_admin']))
                                <span class="text-xs text-emerald-600 font-semibold mt-0.5 inline-block">
                                    <i class="fas fa-star text-amber-400"></i> Admin
                                </span>
                            @else
                                <span class="text-xs text-gray-400 mt-0.5 inline-block">Community Member</span>
                            @endif
                        </div>

                        {{-- Total Posts Badge --}}
                        <div class="flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-sm font-bold px-3 py-1.5 rounded-full">
                            <i class="fas fa-pen-alt text-xs"></i>
                            {{ $contributor->total_posts }} {{ __('pages.contributors_total_posts') }}
                        </div>

                        {{-- Post Breakdown --}}
                        <div class="w-full grid grid-cols-3 gap-1.5 text-center text-[10px] text-gray-500 pt-1">
                            @if ($contributor->blog_posts_count > 0)
                                <div class="bg-emerald-50 rounded-lg py-1">
                                    <div class="font-bold text-emerald-700 text-xs">{{ $contributor->blog_posts_count }}</div>
                                    <div>{{ __('pages.contributors_blog') }}</div>
                                </div>
                            @endif
                            @if ($contributor->news_posts_count > 0)
                                <div class="bg-blue-50 rounded-lg py-1">
                                    <div class="font-bold text-blue-700 text-xs">{{ $contributor->news_posts_count }}</div>
                                    <div>{{ __('pages.contributors_news') }}</div>
                                </div>
                            @endif
                            @if ($contributor->events_count > 0)
                                <div class="bg-purple-50 rounded-lg py-1">
                                    <div class="font-bold text-purple-700 text-xs">{{ $contributor->events_count }}</div>
                                    <div>{{ __('pages.contributors_events') }}</div>
                                </div>
                            @endif
                            @if ($contributor->organizations_count > 0)
                                <div class="bg-orange-50 rounded-lg py-1">
                                    <div class="font-bold text-orange-700 text-xs">{{ $contributor->organizations_count }}</div>
                                    <div>{{ __('pages.contributors_organizations') }}</div>
                                </div>
                            @endif
                            @if ($contributor->forum_topics_count > 0)
                                <div class="bg-teal-50 rounded-lg py-1">
                                    <div class="font-bold text-teal-700 text-xs">{{ $contributor->forum_topics_count }}</div>
                                    <div>{{ __('pages.contributors_forum') }}</div>
                                </div>
                            @endif
                        </div>

                        <span class="mt-1 text-xs text-emerald-600 font-semibold group-hover:underline flex items-center gap-1">
                            {{ __('pages.contributors_view_posts') }}
                            <i class="fas fa-chevron-right text-[9px]"></i>
                        </span>
                    </a>
                @endforeach
            </div>
        @endif

    </div>
</section>

@endsection
