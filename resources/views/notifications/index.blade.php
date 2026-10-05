@extends('layouts.app')

@section('content')

<div class="bg-gray-50 py-10">

    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Notifications
                </h1>

                <p class="mt-1 text-sm text-gray-600">
                    Updates and messages related to your account and submissions.
                </p>
            </div>

            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Mark all as read
                    </button>
                </form>
            @endif

        </div>

        {{-- Success message --}}
        @if(session('success'))
            <div class="mt-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Unread count --}}
        <div class="mt-6 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
            <strong>{{ $unreadCount }}</strong>
            unread notification{{ $unreadCount === 1 ? '' : 's' }}.
        </div>

        {{-- Notifications --}}
        <div class="mt-6 space-y-3">

            @forelse($notifications as $notification)

                @php
                    $data = $notification->data;

                    $title = $data['title']
                        ?? 'Notification';

                    $message = $data['message']
                        ?? '';

                    $status = $data['status']
                        ?? null;

                    $isUnread = is_null($notification->read_at);
                @endphp

                <div
                    class="rounded-xl border bg-white p-5 shadow-sm
                        {{ $isUnread
                            ? 'border-blue-200 ring-1 ring-blue-100'
                            : 'border-gray-200' }}"
                >

                    <div class="flex items-start gap-4">

                        {{-- Icon --}}
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full
                            {{ $status === 'approved'
                                ? 'bg-green-100 text-green-600'
                                : ($status === 'rejected'
                                    ? 'bg-red-100 text-red-600'
                                    : 'bg-blue-100 text-blue-600') }}"
                        >
                            @if($status === 'approved')
                                ✓
                            @elseif($status === 'rejected')
                                ✕
                            @else
                                🔔
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">

                            <div class="flex flex-wrap items-center gap-2">

                                <h2 class="text-base font-bold text-gray-900">
                                    {{ $title }}
                                </h2>

                                @if($isUnread)
                                    <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-700">
                                        New
                                    </span>
                                @endif

                            </div>

                            <p class="mt-2 text-sm leading-6 text-gray-600">
                                {{ $message }}
                            </p>

                            <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-gray-400">

                                <span>
                                    {{ $notification->created_at?->diffForHumans() }}
                                </span>

                                @if($notification->read_at)
                                    <span>
                                        •
                                    </span>

                                    <span>
                                        Read {{ $notification->read_at->diffForHumans() }}
                                    </span>
                                @endif

                            </div>

                            @if(!empty($data['url']))
                                <div class="mt-4">

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'notifications.read',
                                            $notification->id
                                        ) }}"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="text-sm font-semibold text-blue-600 hover:text-blue-800"
                                        >
                                            View related information →
                                        </button>
                                    </form>

                                </div>
                            @elseif($isUnread)
                                <div class="mt-4">

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'notifications.read',
                                            $notification->id
                                        ) }}"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="text-sm font-semibold text-blue-600 hover:text-blue-800"
                                        >
                                            Mark as read
                                        </button>

                                    </form>

                                </div>
                            @endif

                        </div>
                    </div>

                </div>

            @empty

                <div class="rounded-xl border-2 border-dashed border-gray-200 bg-white p-12 text-center">

                    <div class="text-4xl">
                        🔔
                    </div>

                    <h2 class="mt-4 text-lg font-bold text-gray-900">
                        No notifications
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        You don't have any notifications yet.
                    </p>

                </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif

    </div>

</div>

@endsection