@extends('layouts.public')

@section('title', $topic->title)

@section('content')
@php
    $catLabel = fn ($cat) => $cat
        ? (\Illuminate\Support\Facades\Lang::has('community.cat_' . $cat->slug) ? __('community.cat_' . $cat->slug) : $cat->name)
        : '';
@endphp
<div class="max-w-4xl mx-auto px-4 py-10">

    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ url('/') }}" class="hover:text-teal-700">{{ __('community.home') }}</a>
        <span class="mx-2">/</span>
        <a href="{{ url('/community/forum') }}" class="hover:text-teal-700">{{ __('community.forum') }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">{{ Str::limit($topic->title, 50) }}</span>
    </nav>

    @if (session('success'))
        <div class="mb-5 rounded-lg bg-green-50 border border-green-200 p-4 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-4 text-red-700 text-sm">{{ session('error') }}</div>
    @endif

    {{-- Original Topic --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="text-xs bg-teal-100 text-teal-700 px-2 py-0.5 rounded font-medium">{{ $catLabel($topic->category) }}</span>
                @if ($topic->is_pinned) <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded">📌 {{ __('community.pinned') }}</span> @endif
                @if ($topic->is_locked) <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded">🔒 {{ __('community.locked') }}</span> @endif
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $topic->title }}</h1>
            <div class="flex items-center gap-3 text-sm text-gray-500 mb-4">
                <span>{{ __('community.by') }} <strong>{{ $topic->user?->name }}</strong></span>
                <span>·</span>
                <span>{{ $topic->created_at->format('M d, Y H:i') }}</span>
                <span>·</span>
                <span>{{ $topic->views }} {{ __('community.views') }}</span>
            </div>
            <div class="prose max-w-none text-gray-700">{!! nl2br(e($topic->content)) !!}</div>
        </div>

        {{-- Topic actions (owner/admin) --}}
        @auth
            <div class="px-6 py-3 bg-gray-50 flex items-center gap-2">
                @if (auth()->id() === $topic->user_id)
                    <form method="POST" action="{{ route('community.forum.destroy', $topic) }}" onsubmit="return confirm(@js(__('community.delete_topic_confirm')))">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 hover:underline">{{ __('community.delete_topic') }}</button>
                    </form>
                @endif
                @if (auth()->user()->hasAnyRole(['admin','super_admin']))
                    <form method="POST" action="{{ route('community.forum.destroy', $topic) }}" onsubmit="return confirm(@js(__('community.admin_delete_confirm')))">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 hover:underline">{{ __('community.admin_delete') }}</button>
                    </form>
                @endif
            </div>
        @endauth
    </div>

    {{-- Replies --}}
    @if ($topic->replies->count() > 0)
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $topic->replies->count() }} {{ __('community.replies') }}</h2>
            <div class="space-y-4">
                @foreach ($topic->replies as $reply)
                    <div class="bg-white rounded-xl shadow-sm p-5" id="reply-{{ $reply->id }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-sm text-gray-800">{{ $reply->user?->name }}</span>
                                <span class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                            </div>
                            @auth
                                @if (auth()->id() === $reply->user_id || auth()->user()->hasAnyRole(['admin','super_admin']))
                                    <form method="POST" action="{{ route('community.forum.reply.destroy', $reply) }}" onsubmit="return confirm(@js(__('community.delete_reply_confirm')))">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:underline">{{ __('community.delete_reply') }}</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                        <div class="text-gray-700 text-sm">{!! nl2br(e($reply->content)) !!}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Reply Form --}}
    @auth
        @if (!$topic->is_locked)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">{{ __('community.reply_heading') }}</h3>
                <form method="POST" action="{{ route('community.forum.reply.store', $topic) }}">
                    @csrf
                    <textarea name="content" rows="5" required minlength="5"
                        placeholder="{{ __('community.reply_placeholder') }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 text-sm font-medium">
                            {{ __('community.post_reply_btn') }}
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-red-50 border border-red-200 rounded-xl p-5 text-center text-red-600 text-sm">
                🔒 {{ __('community.topic_locked_msg') }}
            </div>
        @endif
    @else
        <div class="bg-teal-50 border border-teal-200 rounded-xl p-5 text-center">
            <p class="text-teal-700 text-sm mb-3">{{ __('community.login_to_reply') }}</p>
            <a href="{{ route('login') }}" class="inline-block px-5 py-2 bg-teal-600 text-white rounded-lg text-xs font-bold hover:bg-teal-700">{{ __('community.login') }}</a>
        </div>
    @endauth

</div>
@endsection
