@extends('layouts.public')

@section('title', __('community.forum_title'))

@section('content')
@php
    $catLabel = fn ($cat) => $cat
        ? (\Illuminate\Support\Facades\Lang::has('community.cat_' . $cat->slug) ? __('community.cat_' . $cat->slug) : $cat->name)
        : '';
@endphp
<div class="max-w-6xl mx-auto px-4 py-10" x-data="{ showNewTopicForm: {{ $errors->any() ? 'true' : 'false' }} }">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">💬 {{ __('community.forum_title') }}</h1>
            <p class="text-gray-500 mt-1">{{ __('community.forum_subtitle') }}</p>
        </div>
        @auth
            <button @click="showNewTopicForm = !showNewTopicForm"
                    class="px-5 py-2.5 bg-teal-700 text-white rounded-xl font-bold hover:bg-teal-800 text-sm shadow-md transition-all flex items-center gap-2">
                <span x-text="showNewTopicForm ? @js(__('community.close_form')) : @js(__('community.new_topic_btn'))"></span>
            </button>
        @else
            <a href="{{ route('login') }}" class="px-5 py-2.5 bg-teal-700 text-white rounded-xl font-bold hover:bg-teal-800 text-sm shadow-md">
                {{ __('community.login_to_post') }}
            </a>
        @endauth
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-green-700 text-sm font-medium shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- INLINE CREATE TOPIC FORM (DIRECTLY ON THIS PAGE) --}}
    @auth
        <div x-show="showNewTopicForm" x-cloak
             class="mb-8 bg-white rounded-2xl shadow-lg border border-teal-100 p-6 md:p-8 animate-in slide-in-from-top-2">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span>💬</span> {{ __('community.new_topic_heading') }}
            </h2>

            @if ($errors->any())
                <div class="mb-5 rounded-xl bg-red-50 border border-red-200 p-4 text-red-700 text-xs">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('community.forum.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="category_id" class="block text-xs font-semibold text-gray-700 mb-1">{{ __('community.category_label') }} <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" required
                            class="block w-full rounded-xl border-gray-300 shadow-sm text-sm focus:border-teal-600 focus:ring-teal-600">
                            <option value="">{{ __('community.select_category') }}</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $catLabel($cat) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="title" class="block text-xs font-semibold text-gray-700 mb-1">{{ __('community.title_label') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required maxlength="255"
                            placeholder="{{ __('community.title_placeholder') }}"
                            class="block w-full rounded-xl border-gray-300 shadow-sm text-sm focus:border-teal-600 focus:ring-teal-600">
                    </div>
                </div>

                <div class="mb-5">
                    <label for="content" class="block text-xs font-semibold text-gray-700 mb-1">{{ __('community.content_label') }} <span class="text-red-500">*</span></label>
                    <textarea name="content" id="content" rows="5" required minlength="10"
                        placeholder="{{ __('community.content_placeholder') }}"
                        class="block w-full rounded-xl border-gray-300 shadow-sm text-sm focus:border-teal-600 focus:ring-teal-600">{{ old('content') }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" @click="showNewTopicForm = false"
                        class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-xs font-medium hover:bg-gray-200">
                        {{ __('community.cancel') }}
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-teal-700 text-white text-xs font-bold hover:bg-teal-800 shadow-md">
                        {{ __('community.post_topic_now') }}
                    </button>
                </div>
            </form>
        </div>
    @endauth

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- Categories sidebar --}}
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-bold text-gray-800 mb-3 text-xs uppercase tracking-wider">{{ __('community.categories_sidebar') }}</h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ url('/community/forum') }}"
                           class="block px-3 py-2 rounded-xl text-sm {{ !request('category') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                            {{ __('community.all_topics') }}
                        </a>
                    </li>
                    @foreach ($categories as $cat)
                        <li>
                            <a href="{{ url('/community/forum?category=' . $cat->slug) }}"
                               class="flex items-center justify-between px-3 py-2 rounded-xl text-sm {{ request('category') === $cat->slug ? 'bg-teal-50 text-teal-800 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                                <span>{{ $catLabel($cat) }}</span>
                                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $cat->topics_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        {{-- Topics list --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-100">
                @forelse ($topics as $topic)
                    <div class="p-5 hover:bg-gray-50/80 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    @if ($topic->is_pinned)
                                        <span class="text-[11px] bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full font-bold">📌 {{ __('community.pinned') }}</span>
                                    @endif
                                    @if ($topic->is_locked)
                                        <span class="text-[11px] bg-red-100 text-red-700 px-2.5 py-0.5 rounded-full font-bold">🔒 {{ __('community.locked') }}</span>
                                    @endif
                                    <span class="text-[11px] bg-gray-100 text-gray-600 px-2.5 py-0.5 rounded-full font-medium">{{ $catLabel($topic->category) }}</span>
                                </div>
                                <h3 class="font-bold text-gray-900 text-base leading-snug">
                                    <a href="{{ route('community.forum.show', $topic->slug) }}" class="hover:text-teal-700">
                                        {{ $topic->title }}
                                    </a>
                                </h3>
                                <p class="text-xs text-gray-400 mt-1.5">
                                    {{ __('community.by') }} <strong class="text-gray-600">{{ $topic->user?->name }}</strong> · {{ $topic->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="text-center flex-shrink-0 bg-teal-50 px-3 py-2 rounded-xl text-teal-800">
                                <p class="text-base font-extrabold leading-none">{{ $topic->replies_count }}</p>
                                <p class="text-[10px] text-teal-600 mt-0.5 font-medium">{{ __('community.replies') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-400">
                        <div class="text-5xl mb-4">💬</div>
                        <p class="text-sm font-semibold text-gray-600">{{ __('community.no_topics_title') }}</p>
                        <p class="text-sm mt-1">{{ __('community.no_topics_desc') }}</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-6">{{ $topics->links() }}</div>
        </div>

    </div>
</div>
@endsection
