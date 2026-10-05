@extends('layouts.public')

@section('title', ($translation->title ?? __('community.blog')) . ' — ' . __('community.blog_title'))

@section('content')

@php
    $catLabel = fn ($cat) => $cat
        ? (\Illuminate\Support\Facades\Lang::has('community.cat_' . $cat->slug) ? __('community.cat_' . $cat->slug) : $cat->name)
        : '';
    $relatedPosts = \App\Models\BlogPost::query()
        ->where('status', 'published')
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->where('id', '!=', $post->id)
        ->when($post->category_id, fn($q) => $q->where('category_id', $post->category_id))
        ->with(['translations', 'category', 'user'])
        ->latest('published_at')
        ->take(3)
        ->get();

    if ($relatedPosts->isEmpty()) {
        $relatedPosts = \App\Models\BlogPost::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $post->id)
            ->with(['translations', 'category', 'user'])
            ->latest('published_at')
            ->take(3)
            ->get();
    }
@endphp

<article class="bg-white min-h-screen pb-20">

    {{-- ============================================================
         1. ARTICLE HEADER / BREADCRUMBS & TITLE
         ============================================================ --}}
    <header class="bg-gradient-to-b from-slate-900 to-slate-950 text-white py-12 lg:py-16">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-6 flex-wrap">
                <a href="{{ route('explore.index') }}" class="hover:text-emerald-400 transition">{{ __('community.explore') }}</a>
                <span>/</span>
                <a href="{{ route('community.blog.index') }}" class="hover:text-emerald-400 transition">{{ __('community.blog') }}</a>
                @if($post->category)
                    <span>/</span>
                    <a href="{{ route('community.blog.index', ['category' => $post->category->slug]) }}" class="hover:text-emerald-400 transition text-emerald-300">
                        {{ $catLabel($post->category) }}
                    </a>
                @endif
            </nav>

            {{-- Category Pill --}}
            @if($post->category)
                <div class="mb-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-tag text-[10px]"></i>
                        {{ $catLabel($post->category) }}
                    </span>
                </div>
            @endif

            {{-- Title --}}
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                {{ $translation->title }}
            </h1>

            {{-- Author & Metadata Bar --}}
            <div class="mt-8 pt-6 border-t border-white/10 flex flex-wrap items-center justify-between gap-4">
                
                {{-- Author Info --}}
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-emerald-600 flex items-center justify-center text-white font-bold text-base shadow">
                        {{ strtoupper(substr($post->user?->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-white">{{ $post->user?->name ?? __('community.contributor') }}</div>
                        <div class="text-xs text-slate-400 flex items-center gap-2 mt-0.5">
                            <span><i class="far fa-calendar-alt mr-1"></i> {{ $post->published_at ? $post->published_at->format('F d, Y') : __('community.recently') }}</span>
                            <span>&bull;</span>
                            <span><i class="far fa-eye mr-1"></i> {{ $post->views ?? 1 }} {{ __('community.views') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Social Share Buttons --}}
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-400 mr-1 hidden sm:inline">{{ __('community.share') }}:</span>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($translation->title . ' - ' . url()->current()) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="w-9 h-9 rounded-full bg-[#25D366] text-white flex items-center justify-center text-xs hover:opacity-85 transition shadow"
                       title="Share on WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="w-9 h-9 rounded-full bg-[#1877F2] text-white flex items-center justify-center text-xs hover:opacity-85 transition shadow"
                       title="Share on Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($translation->title) }}&url={{ urlencode(url()->current()) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="w-9 h-9 rounded-full bg-slate-700 text-white flex items-center justify-center text-xs hover:opacity-85 transition shadow"
                       title="Share on X">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                    <button type="button"
                            onclick="navigator.clipboard.writeText(window.location.href); alert(@js(__('community.link_copied')));"
                            class="w-9 h-9 rounded-full bg-slate-800 text-slate-300 hover:text-white flex items-center justify-center text-xs transition border border-white/10"
                            title="Copy link">
                        <i class="fas fa-link"></i>
                    </button>
                </div>

            </div>

        </div>
    </header>


    {{-- ============================================================
         2. COVER IMAGE
         ============================================================ --}}
    @if($post->cover_image)
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 -mt-8 sm:-mt-12 relative z-10">
            <div class="rounded-3xl overflow-hidden shadow-2xl bg-gray-900 border-4 border-white max-h-[520px]">
                <img src="{{ asset('storage/' . $post->cover_image) }}"
                     alt="{{ $translation->title }}"
                     class="w-full h-full object-cover">
            </div>
        </div>
    @endif


    {{-- ============================================================
         3. MAIN ARTICLE CONTENT BODY
         ============================================================ --}}
    <main class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Lead Excerpt --}}
        @if($translation->excerpt)
            <div class="rounded-2xl bg-emerald-50/70 border-l-4 border-emerald-500 p-6 mb-8 text-gray-700 font-medium text-base sm:text-lg leading-relaxed shadow-sm">
                {{ $translation->excerpt }}
            </div>
        @endif

        {{-- Rich Content Body --}}
        <div class="article-body prose prose-lg prose-emerald max-w-none text-gray-800 leading-relaxed font-sans">
            {!! $sanitizedContent !!}
        </div>

        {{-- Post Tags & Category Info --}}
        <div class="mt-12 pt-6 border-t border-gray-200 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('community.filed_under') }}</span>
                @if($post->category)
                    <a href="{{ route('community.blog.index', ['category' => $post->category->slug]) }}"
                       class="text-xs font-semibold px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition">
                        # {{ $catLabel($post->category) }}
                    </a>
                @endif
            </div>

            <a href="{{ route('community.blog.index') }}"
               class="inline-flex items-center gap-2 text-xs font-bold text-emerald-700 hover:text-emerald-800 transition">
                <i class="fas fa-arrow-left"></i>
                <span>{{ __('community.back_to_stories') }}</span>
            </a>
        </div>


        {{-- ============================================================
             4. AUTHOR BIO CARD
             ============================================================ --}}
        <div class="mt-10 rounded-2xl bg-gradient-to-r from-gray-50 to-emerald-50/40 border border-gray-200 p-6 flex flex-col sm:flex-row items-center sm:items-start gap-5">
            <div class="w-16 h-16 rounded-2xl bg-emerald-600 text-white font-extrabold text-2xl flex items-center justify-center flex-shrink-0 shadow-md">
                {{ strtoupper(substr($post->user?->name ?? 'A', 0, 1)) }}
            </div>
            <div class="text-center sm:text-left flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                    <h3 class="text-base font-bold text-gray-900">{{ $post->user?->name ?? __('community.contributor') }}</h3>
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-100/80 px-2.5 py-0.5 rounded-full self-center sm:self-auto">
                        {{ __('community.contributor') }}
                    </span>
                </div>
                <p class="mt-2 text-xs text-gray-600 leading-relaxed">
                    {{ __('community.author_bio') }}
                </p>
            </div>
        </div>


        {{-- ============================================================
             5. COMMENTS & FEEDBACK SECTION
             ============================================================ --}}
        <section class="mt-14 pt-8 border-t border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="far fa-comments text-emerald-600"></i>
                    <span>{{ __('community.community_discussion') }} ({{ $post->comments->count() }})</span>
                </h3>
            </div>

            {{-- Flash success message --}}
            @if(session('success'))
                <div class="rounded-xl bg-green-50 border border-green-200 text-green-800 p-4 mb-6 text-sm flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Comment Form --}}
            @auth
                <div class="rounded-2xl bg-white border border-gray-200 p-5 shadow-sm mb-8">
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">{{ __('community.leave_comment') }}</h4>
                    <form method="POST" action="{{ route('comments.store') }}">
                        @csrf
                        <input type="hidden" name="commentable_type" value="App\Models\BlogPost">
                        <input type="hidden" name="commentable_id" value="{{ $post->id }}">

                        <div>
                            <textarea name="content"
                                      rows="3"
                                      required
                                      placeholder="{{ __('community.comment_placeholder') }}"
                                      class="w-full rounded-xl border border-gray-300 p-3 text-xs sm:text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"></textarea>
                        </div>

                        <div class="mt-3 flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-5 py-2 text-xs shadow transition">
                                <i class="fas fa-paper-plane text-[10px]"></i>
                                <span>{{ __('community.post_comment') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6 text-center mb-8">
                    <p class="text-xs sm:text-sm text-gray-600 mb-3">
                        {{ __('community.login_comment_prompt') }}
                    </p>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-5 py-2 text-xs shadow transition">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>{{ __('community.login') }}</span>
                    </a>
                </div>
            @endauth

            {{-- Comments List --}}
            @if($post->comments->count())
                <div class="space-y-4">
                    @foreach($post->comments as $comment)
                        <div class="rounded-2xl bg-white border border-gray-100 p-5 shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center">
                                        {{ strtoupper(substr($comment->user?->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="text-xs font-bold text-gray-900">{{ $comment->user?->name ?? __('community.contributor') }}</span>
                                </div>
                                <span class="text-[11px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-700 leading-relaxed pl-10">
                                {{ $comment->content }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-gray-500 italic text-center py-4">{{ __('community.no_comments') }}</p>
            @endif

        </section>


        {{-- ============================================================
             6. RELATED / MORE STORIES
             ============================================================ --}}
        @if($relatedPosts->count())
            <section class="mt-16 pt-10 border-t border-gray-200">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-bookmark text-emerald-600"></i>
                    <span>{{ __('community.more_stories') }}</span>
                </h3>

                <div class="grid gap-6 sm:grid-cols-3">
                    @foreach($relatedPosts as $rPost)
                        @php
                            $rTrans = $rPost->translationFor(app()->getLocale());
                            $rSlug = $rTrans?->slug ?? $rPost->translations->first()?->slug ?? $rPost->id;
                        @endphp
                        <a href="{{ route('community.blog.show', $rSlug) }}"
                           class="group flex flex-col rounded-2xl overflow-hidden bg-white border border-gray-200 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                            <div class="aspect-[16/10] bg-gray-100 overflow-hidden relative">
                                @if($rPost->cover_image)
                                    <img src="{{ asset('storage/' . $rPost->cover_image) }}"
                                         alt="{{ $rTrans?->title }}"
                                         class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-emerald-800 to-slate-900"></div>
                                @endif
                            </div>
                            <div class="p-4 flex flex-1 flex-col">
                                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-1">
                                    {{ $rPost->category ? $catLabel($rPost->category) : __('community.blog') }}
                                </span>
                                <h4 class="text-xs font-bold text-gray-900 group-hover:text-emerald-700 transition line-clamp-2 leading-snug">
                                    {{ $rTrans?->title ?? __('community.untitled') }}
                                </h4>
                                <div class="mt-auto pt-3 text-[11px] text-gray-400 flex items-center justify-between">
                                    <span>{{ $rPost->published_at ? $rPost->published_at->format('M d, Y') : '' }}</span>
                                    <span class="text-emerald-700 font-semibold group-hover:translate-x-0.5 transition">&rarr;</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </main>

</article>

@endsection