@extends('layouts.public')

@section('title', __('community.blog_title'))

@section('content')

@php
    $catLabel = fn ($cat) => $cat
        ? (\Illuminate\Support\Facades\Lang::has('community.cat_' . $cat->slug) ? __('community.cat_' . $cat->slug) : $cat->name)
        : '';
@endphp

<div class="bg-gray-50/50 min-h-screen">

    {{-- ============================================================
         1. HERO HEADER BANNER
         ============================================================ --}}
    <section class="relative bg-gradient-to-br from-emerald-950 via-slate-900 to-teal-950 text-white py-14 lg:py-20 overflow-hidden">
        {{-- Ambient background blurs --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
                
                {{-- Left: Text & Badges --}}
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold uppercase tracking-wider mb-4">
                        <i class="fas fa-feather-alt"></i>
                        <span>{{ __('community.blog_badge') }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                        {{ __('community.blog_heading_prefix') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">{{ __('community.blog_heading_highlight') }}</span>
                    </h1>

                    <p class="mt-4 text-base sm:text-lg text-slate-300 leading-relaxed">
                        {{ __('community.blog_subtitle') }}
                    </p>

                    {{-- Search in Hero --}}
                    <div class="mt-6 max-w-lg">
                        <form method="GET" action="{{ route('community.blog.index') }}" class="relative">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="{{ __('community.search_placeholder') }}"
                                   class="w-full rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 pl-11 pr-24 py-3 text-sm text-white placeholder-slate-400 focus:bg-white focus:text-gray-900 focus:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition shadow-lg">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-emerald-400 text-sm">
                                <i class="fas fa-search"></i>
                            </div>
                            <button type="submit"
                                    class="absolute right-1.5 top-1.5 bottom-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white px-4 text-xs font-semibold transition flex items-center gap-1.5 shadow">
                                <span>{{ __('community.search_btn') }}</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Right: Actions & Metrics --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 lg:self-end">
                    <a href="{{ route('community.blog-posts.create') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold px-5 py-3 text-sm shadow-lg hover:shadow-emerald-500/25 transition">
                        <i class="fas fa-pen-nib"></i>
                        <span>{{ __('community.write_story') }}</span>
                    </a>

                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md rounded-2xl p-3 px-4 border border-white/10">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-300">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div>
                            <div class="text-xl font-bold text-white leading-none">{{ $totalPostsCount }}</div>
                            <div class="text-[11px] text-slate-300 mt-1">{{ __('community.articles_published') }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- ============================================================
         2. CATEGORY PILL TABS
         ============================================================ --}}
    <nav class="sticky top-16 z-20 bg-white/90 backdrop-blur-md border-b border-gray-200 py-3 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0 no-scrollbar">
                
                {{-- All Stories --}}
                <a href="{{ route('community.blog.index', array_merge(request()->except(['category', 'page']))) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ !request()->filled('category') ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-globe-asia text-[11px]"></i>
                    <span>{{ __('community.all_stories') }}</span>
                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ !request()->filled('category') ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700' }}">
                        {{ $totalPostsCount }}
                    </span>
                </a>

                {{-- Categories --}}
                @foreach($categories as $category)
                    @php
                        $isActive = request('category') == $category->slug || request('category') == $category->id;
                        $catIcon = match(strtolower($category->name)) {
                            'travel & tourism' => 'fas fa-map-marked-alt',
                            'culture & heritage' => 'fas fa-landmark',
                            'nature & environment' => 'fas fa-leaf',
                            'adventure & outdoor' => 'fas fa-hiking',
                            'local community' => 'fas fa-users',
                            'news & events' => 'fas fa-bullhorn',
                            default => 'fas fa-tag'
                        };
                    @endphp

                    <a href="{{ route('community.blog.index', array_merge(request()->except('page'), ['category' => $category->slug])) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ $isActive ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="{{ $catIcon }} text-[11px] {{ $isActive ? 'text-white' : 'text-emerald-600' }}"></i>
                        <span>{{ $catLabel($category) }}</span>
                        @if($category->blog_posts_count > 0)
                            <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $isActive ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700' }}">
                                {{ $category->blog_posts_count }}
                            </span>
                        @endif
                    </a>
                @endforeach

            </div>
        </div>
    </nav>


    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">

        {{-- ============================================================
             3. ACTIVE FILTER PILLS (IF FILTERED OR SEARCHED)
             ============================================================ --}}
        @if(request('category') || request('search'))
            <div class="bg-white rounded-2xl border border-gray-200 p-4 mb-8 flex flex-wrap items-center justify-between gap-3 shadow-sm">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-gray-700">{{ __('community.filter_applied') }}</span>

                    @if(request('category'))
                        @php
                            $activeCategory = $categories->first(fn($c) => $c->slug == request('category') || $c->id == request('category'));
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-semibold">
                            <i class="fas fa-folder text-[10px]"></i>
                            <span>{{ $activeCategory ? $catLabel($activeCategory) : request('category') }}</span>
                            <a href="{{ route('community.blog.index', array_merge(request()->except(['category', 'page']))) }}" class="text-emerald-700 hover:text-emerald-900 ml-1">
                                <i class="fas fa-times text-[10px]"></i>
                            </a>
                        </span>
                    @endif

                    @if(request('search'))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">
                            <i class="fas fa-search text-[10px]"></i>
                            <span>{{ __('community.search') }}: "{{ request('search') }}"</span>
                            <a href="{{ route('community.blog.index', array_merge(request()->except(['search', 'page']))) }}" class="text-blue-700 hover:text-blue-900 ml-1">
                                <i class="fas fa-times text-[10px]"></i>
                            </a>
                        </span>
                    @endif
                </div>

                <a href="{{ route('community.blog.index') }}" class="text-xs font-semibold text-red-600 hover:text-red-700 flex items-center gap-1">
                    <i class="fas fa-times-circle"></i> {{ __('community.clear_filters') }}
                </a>
            </div>
        @endif


        {{-- ============================================================
             4. FEATURED HERO STORY (ONLY ON MAIN PAGE WITHOUT FILTERS)
             ============================================================ --}}
        @if(isset($featuredPost) && !request()->filled('category') && !request()->filled('search'))
            @php
                $fTrans = $featuredPost->translationFor(app()->getLocale());
                $fSlug = $fTrans?->slug ?? $featuredPost->translations->first()?->slug ?? $featuredPost->id;
            @endphp

            <section class="mb-12">
                <article class="group relative rounded-3xl overflow-hidden bg-gray-900 shadow-xl border border-gray-200 transition duration-300 hover:shadow-2xl">
                    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[420px]">
                        
                        {{-- Image Column --}}
                        <div class="lg:col-span-7 relative overflow-hidden bg-gray-800 min-h-[280px] lg:min-h-full">
                            @if($featuredPost->cover_image)
                                <img src="{{ asset('storage/' . $featuredPost->cover_image) }}"
                                     alt="{{ $fTrans?->title }}"
                                     class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                            @else
                                <div class="w-full h-full bg-gradient-to-tr from-emerald-900 to-slate-800 flex items-center justify-center text-white/40">
                                    <i class="fas fa-newspaper text-6xl"></i>
                                </div>
                            @endif

                            {{-- Featured Badge --}}
                            <div class="absolute top-4 left-4 z-10">
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-emerald-600 text-white text-xs font-bold shadow-lg">
                                    <i class="fas fa-star text-amber-300"></i> {{ __('community.featured_story') }}
                                </span>
                            </div>

                            <div class="absolute inset-0 bg-gradient-to-t from-gray-950/80 via-transparent to-transparent lg:hidden"></div>
                        </div>

                        {{-- Content Column --}}
                        <div class="lg:col-span-5 bg-gradient-to-br from-slate-900 via-slate-950 to-emerald-950 p-6 sm:p-10 flex flex-col justify-between text-white">
                            <div>
                                {{-- Category & Date --}}
                                <div class="flex items-center gap-3 text-xs mb-3 text-emerald-300">
                                    @if($featuredPost->category)
                                        <span class="font-bold uppercase tracking-wider bg-emerald-950/80 border border-emerald-400/30 px-3 py-1 rounded-full">
                                            {{ $catLabel($featuredPost->category) }}
                                        </span>
                                    @endif
                                    <span class="text-slate-400 flex items-center gap-1">
                                        <i class="far fa-clock"></i>
                                        {{ $featuredPost->published_at ? $featuredPost->published_at->format('M d, Y') : __('community.recently') }}
                                    </span>
                                </div>

                                {{-- Title --}}
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-white group-hover:text-emerald-300 transition leading-snug">
                                    <a href="{{ route('community.blog.show', $fSlug) }}">
                                        {{ $fTrans?->title ?? __('community.featured_story') }}
                                    </a>
                                </h2>

                                {{-- Excerpt --}}
                                @if($fTrans?->excerpt)
                                    <p class="mt-4 text-sm text-slate-300 leading-relaxed line-clamp-3">
                                        {{ $fTrans->excerpt }}
                                    </p>
                                @endif
                            </div>

                            {{-- Author & Link --}}
                            <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center text-white font-bold text-sm shadow">
                                        {{ strtoupper(substr($featuredPost->user?->name ?? 'L', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-white">{{ $featuredPost->user?->name ?? __('community.contributor') }}</div>
                                        <div class="text-[11px] text-slate-400">{{ __('community.writer') }} &bull; {{ $featuredPost->views ?? 0 }} {{ __('community.views') }}</div>
                                    </div>
                                </div>

                                <a href="{{ route('community.blog.show', $fSlug) }}"
                                   class="inline-flex items-center gap-2 rounded-xl bg-white text-gray-900 px-4 py-2 text-xs font-bold hover:bg-emerald-400 transition shadow">
                                    <span>{{ __('community.read_story') }}</span>
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </article>
            </section>
        @endif


        {{-- ============================================================
             5. MAIN SECTION: POSTS GRID & COMMUNITY SIDEBAR
             ============================================================ --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- --------------------------------------------------------
                 LEFT: POSTS LIST / GRID
                 -------------------------------------------------------- --}}
            <div class="lg:col-span-8">
                
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-stream text-emerald-600"></i>
                        <span>{{ __('community.latest_articles') }}</span>
                    </h2>
                    <span class="text-xs text-gray-500 font-medium">
                        {{ __('community.showing_count', ['count' => $posts->total()]) }}
                    </span>
                </div>

                @if($posts->count())

                    <div class="grid gap-6 sm:grid-cols-2">

                        @foreach($posts as $post)
                            @php
                                $pTrans = $post->translationFor(app()->getLocale());
                                $pSlug = $pTrans?->slug ?? $post->translations->first()?->slug ?? $post->id;
                            @endphp

                            <article class="group flex flex-col rounded-2xl overflow-hidden bg-white border border-gray-200 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                                
                                {{-- Card Cover Image --}}
                                <div class="relative overflow-hidden aspect-[16/10] bg-gray-100">
                                    <a href="{{ route('community.blog.show', $pSlug) }}" class="block w-full h-full">
                                        @if($post->cover_image)
                                            <img src="{{ asset('storage/' . $post->cover_image) }}"
                                                 alt="{{ $pTrans?->title }}"
                                                 loading="lazy"
                                                 class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-emerald-800 to-slate-900 flex items-center justify-center text-white/30">
                                                <i class="fas fa-feather-alt text-4xl"></i>
                                            </div>
                                        @endif
                                    </a>

                                    {{-- Category badge on image --}}
                                    @if($post->category)
                                        <div class="absolute top-3 left-3 pointer-events-none">
                                            <span class="px-2.5 py-1 rounded-full bg-black/60 backdrop-blur-md text-emerald-300 text-[10px] font-bold uppercase tracking-wider border border-white/10 shadow-sm">
                                                {{ $catLabel($post->category) }}
                                            </span>
                                        </div>
                                    @endif

                                    {{-- Views Badge --}}
                                    <div class="absolute bottom-3 right-3 pointer-events-none">
                                        <span class="px-2 py-0.5 rounded-lg bg-black/60 backdrop-blur-md text-white text-[10px] font-medium">
                                            <i class="fas fa-eye mr-1 text-emerald-400"></i> {{ $post->views ?? 0 }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Card Content --}}
                                <div class="flex flex-1 flex-col p-5">
                                    
                                    {{-- Date & Author --}}
                                    <div class="flex items-center gap-2 text-[11px] text-gray-500 mb-2">
                                        <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : __('community.recently') }}</span>
                                        <span>&bull;</span>
                                        <span class="font-medium text-gray-700 truncate max-w-[120px]">{{ $post->user?->name ?? __('community.contributor') }}</span>
                                    </div>

                                    {{-- Title --}}
                                    <h3 class="text-base font-bold text-gray-900 group-hover:text-emerald-700 transition line-clamp-2 leading-snug">
                                        <a href="{{ route('community.blog.show', $pSlug) }}">
                                            {{ $pTrans?->title ?? __('community.untitled') }}
                                        </a>
                                    </h3>

                                    {{-- Excerpt --}}
                                    @if($pTrans?->excerpt)
                                        <p class="mt-2.5 text-xs text-gray-600 line-clamp-2 leading-relaxed">
                                            {{ $pTrans->excerpt }}
                                        </p>
                                    @endif

                                    {{-- Card Footer --}}
                                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                                        <a href="{{ route('community.blog.show', $pSlug) }}"
                                           class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 group-hover:text-emerald-800 transition">
                                            <span>{{ __('community.read_article') }}</span>
                                            <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition"></i>
                                        </a>

                                        <span class="text-[11px] text-gray-400 flex items-center gap-1">
                                            <i class="far fa-comment-alt"></i> {{ $post->comments()->count() }}
                                        </span>
                                    </div>

                                </div>

                            </article>
                        @endforeach

                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10">
                        {{ $posts->links() }}
                    </div>

                @else

                    {{-- Empty State --}}
                    <div class="rounded-3xl border border-dashed border-gray-300 bg-white p-12 text-center shadow-sm">
                        <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center text-2xl mb-4 shadow-inner">
                            <i class="fas fa-pen-fancy"></i>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900">
                            {{ __('community.no_stories_title') }}
                        </h3>

                        <p class="mt-2 text-xs sm:text-sm text-gray-500 max-w-md mx-auto">
                            {{ __('community.no_stories_desc') }}
                        </p>

                        <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                            @if(request('category') || request('search'))
                                <a href="{{ route('community.blog.index') }}"
                                   class="inline-flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 px-4 py-2.5 text-xs font-semibold text-gray-700 transition">
                                    <i class="fas fa-redo-alt"></i>
                                    <span>{{ __('community.reset_filters') }}</span>
                                </a>
                            @endif

                            <a href="{{ route('community.blog-posts.create') }}"
                               class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 px-5 py-2.5 text-xs font-bold text-white transition shadow">
                                <i class="fas fa-plus-circle"></i>
                                <span>{{ __('community.write_story') }}</span>
                            </a>
                        </div>
                    </div>

                @endif

            </div>


            {{-- --------------------------------------------------------
                 RIGHT: COMMUNITY ENGAGEMENT SIDEBAR
                 -------------------------------------------------------- --}}
            <aside class="lg:col-span-4 space-y-6">

                {{-- Writer Invitation CTA Card --}}
                <div class="rounded-3xl bg-gradient-to-br from-emerald-800 via-teal-900 to-slate-900 text-white p-6 shadow-xl relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-400/20 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-emerald-300 text-xl mb-4 border border-white/20">
                            <i class="fas fa-feather-alt"></i>
                        </div>

                        <h3 class="text-lg font-bold text-white leading-snug">
                            {{ __('community.cta_title') }}
                        </h3>

                        <p class="mt-2 text-xs text-slate-200 leading-relaxed">
                            {{ __('community.cta_desc') }}
                        </p>

                        <div class="mt-5">
                            <a href="{{ route('community.blog-posts.create') }}"
                               class="block text-center rounded-xl bg-emerald-400 hover:bg-emerald-300 text-slate-950 font-bold px-4 py-2.5 text-xs shadow-lg transition">
                                <i class="fas fa-plus-circle mr-1"></i> {{ __('community.submit_article') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Categories Widget --}}
                <div class="rounded-2xl bg-white border border-gray-200 p-5 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fas fa-folder-open text-emerald-600"></i>
                        <span>{{ __('community.explore_categories') }}</span>
                    </h3>

                    <ul class="space-y-2">
                        @foreach($categories as $cat)
                            @php
                                $isCatActive = request('category') == $cat->slug || request('category') == $cat->id;
                            @endphp
                            <li>
                                <a href="{{ route('community.blog.index', ['category' => $cat->slug]) }}"
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition {{ $isCatActive ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-chevron-right text-[10px] text-emerald-500"></i>
                                        <span>{{ $catLabel($cat) }}</span>
                                    </span>
                                    <span class="rounded-full bg-gray-100 text-gray-600 px-2 py-0.5 text-[10px] font-bold">
                                        {{ $cat->blog_posts_count }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Travel Guide & Safety Companion Widget --}}
                <div class="rounded-2xl bg-white border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-lg flex-shrink-0">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900">{{ __('community.visiting_title') }}</h4>
                            <p class="text-[11px] text-gray-500 mt-1 leading-relaxed">
                                {{ __('community.visiting_desc') }}
                            </p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <a href="{{ route('explore.map') }}"
                                   class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-800">
                                    <span>{{ __('community.interactive_map') }}</span> &rarr;
                                </a>
                                <span class="text-gray-300">&bull;</span>
                                <a href="{{ route('explore.destinations.index') }}"
                                   class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-800">
                                    <span>{{ __('community.destinations') }}</span> &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </aside>

        </div>

    </div>

</div>

@endsection