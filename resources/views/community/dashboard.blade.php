<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Dashboard - Laggala-Pallegama</title>

    <!-- Favicon / Title Logo -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen" x-data="{ tab: '{{ session('active_tab', 'blog') }}' }">

    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Community Dashboard</h1>
                <p class="text-gray-600 mt-1">ආයුබෝවන්, {{ $user->name }}!</p>
            </div>
          
            <div class="flex items-center gap-3">

                {{-- Notifications --}}
                <a
                    href="{{ route('notifications.index') }}"
                    class="relative inline-flex items-center px-4 py-2 text-sm bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                >
                    🔔 Notifications

                    @php
                        $unreadNotificationsCount = auth()->user()
                            ->unreadNotifications()
                            ->count();
                    @endphp

                    @if($unreadNotificationsCount > 0)
                        <span
                            class="ml-2 rounded-full bg-red-600 px-2 py-0.5 text-xs font-bold text-white"
                        >
                            {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                        </span>
                    @endif
                </a>

                {{-- Public Site --}}
                <a
                    href="{{ url('/community/blog') }}"
                    class="px-4 py-2 text-sm bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                >
                    Public Site →
                </a>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="px-4 py-2 text-sm bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                    >
                        Logout
                    </button>
                </form>

            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Statistics --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                <p class="text-sm text-gray-500">Drafts</p>
                <p class="text-3xl font-bold text-gray-700 mt-1">{{ $draftCount }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                <p class="text-sm text-yellow-600">Pending Review</p>
                <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $pendingCount }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                <p class="text-sm text-green-600">Published</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ $publishedCount }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                <p class="text-sm text-blue-600">Forum Topics</p>
                <p class="text-3xl font-bold text-blue-600 mt-1">{{ $forumTopics->count() }}</p>
            </div>
        </div>

        {{-- Create Buttons --}}
        <div class="bg-white rounded-xl shadow-sm p-5 mb-8">
            <h2 class="text-base font-semibold text-gray-700 mb-3">Content Create කරන්න</h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('community.blog-posts.create') }}"
                   class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                    + Blog Post
                </a>
                <a href="{{ route('community.news.create') }}"
                   class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    + News
                </a>
                <a href="{{ route('community.events.create') }}"
                   class="px-4 py-2 text-sm bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    + Event
                </a>
                <a href="{{ route('community.organizations.create') }}"
                   class="px-4 py-2 text-sm bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                    + Organization
                </a>
                <a href="{{ route('community.forum.create') }}"
                   class="px-4 py-2 text-sm bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                    + Forum Topic
                </a>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            {{-- Tab Nav --}}
            <div class="border-b border-gray-200">
                <nav class="flex overflow-x-auto">
                    @foreach([
                        ['key'=>'blog',  'label'=>'Blog Posts',    'count'=>$blogPosts->count()],
                        ['key'=>'news',  'label'=>'News',           'count'=>$newsPosts->count()],
                        ['key'=>'events','label'=>'Events',         'count'=>$events->count()],
                        ['key'=>'orgs',  'label'=>'Organizations',  'count'=>$organizations->count()],
                        ['key'=>'forum', 'label'=>'Forum Topics',   'count'=>$forumTopics->count()],
                    ] as $t)
                    <button
                        @click="tab = '{{ $t['key'] }}'"
                        :class="tab === '{{ $t['key'] }}' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
                        class="px-5 py-4 text-sm font-medium whitespace-nowrap transition-colors">
                        {{ $t['label'] }}
                        <span class="ml-1.5 text-xs bg-gray-100 text-gray-600 rounded-full px-2 py-0.5">{{ $t['count'] }}</span>
                    </button>
                    @endforeach
                </nav>
            </div>

            {{-- ============ BLOG POSTS TAB ============ --}}
            <div x-show="tab === 'blog'" x-cloak>
                @forelse ($blogPosts as $post)
                    @php $title = $post->translationFor('si')?->title ?? $post->translations->first()?->title ?? 'Untitled'; @endphp
                    <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $title }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $post->category?->name ?? '—' }} · {{ $post->created_at->format('Y-m-d') }}</p>
                            @if($post->status === 'rejected' && $post->rejection_reason)
                                <div class="mt-2 rounded bg-red-50 border border-red-200 p-2 text-xs text-red-700">
                                    <strong>Rejection Reason:</strong> {{ $post->rejection_reason }}
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @include('community._status-badge', ['status' => $post->status])
                            @if(in_array($post->status, ['draft','rejected']))
                                <a href="{{ route('community.blog-posts.edit', $post) }}" class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">Edit</a>
                                <form method="POST" action="{{ route('community.blog-posts.submit', $post) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs bg-green-600 text-white rounded hover:bg-green-700">Submit</button>
                                </form>
                            @endif
                            @if($post->status !== 'published')
                                <form method="POST" action="{{ route('community.blog-posts.destroy', $post) }}" onsubmit="return confirm('Delete this post?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-gray-400">Blog Post එකක් නොමැත. <a href="{{ route('community.blog-posts.create') }}" class="text-blue-600 hover:underline">Create කරන්න</a></div>
                @endforelse
            </div>

            {{-- ============ NEWS TAB ============ --}}
            <div x-show="tab === 'news'" x-cloak>
                @forelse ($newsPosts as $post)
                    @php $title = $post->translationFor('si')?->title ?? $post->translations->first()?->title ?? 'Untitled'; @endphp
                    <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $title }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $post->category?->name ?? '—' }} · {{ $post->created_at->format('Y-m-d') }}</p>
                            @if($post->status === 'rejected' && $post->rejection_reason)
                                <div class="mt-2 rounded bg-red-50 border border-red-200 p-2 text-xs text-red-700">
                                    <strong>Rejection Reason:</strong> {{ $post->rejection_reason }}
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @include('community._status-badge', ['status' => $post->status])
                            @if(in_array($post->status, ['draft','rejected']))
                                <a href="{{ route('community.news.edit', $post) }}" class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">Edit</a>
                                <form method="POST" action="{{ route('community.news.submit', $post) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs bg-green-600 text-white rounded hover:bg-green-700">Submit</button>
                                </form>
                            @endif
                            @if($post->status !== 'published')
                                <form method="POST" action="{{ route('community.news.destroy', $post) }}" onsubmit="return confirm('Delete this news post?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-gray-400">News Post එකක් නොමැත. <a href="{{ route('community.news.create') }}" class="text-blue-600 hover:underline">Create කරන්න</a></div>
                @endforelse
            </div>

            {{-- ============ EVENTS TAB ============ --}}
            <div x-show="tab === 'events'" x-cloak>
                @forelse ($events as $event)
                    @php $title = $event->translationFor('si')?->title ?? $event->translations->first()?->title ?? 'Untitled'; @endphp
                    <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $title }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $event->category?->name ?? '—' }} ·
                                {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') : '—' }}
                            </p>
                            @if($event->status === 'rejected' && $event->rejection_reason)
                                <div class="mt-2 rounded bg-red-50 border border-red-200 p-2 text-xs text-red-700">
                                    <strong>Rejection Reason:</strong> {{ $event->rejection_reason }}
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @include('community._status-badge', ['status' => $event->status])
                            @if(in_array($event->status, ['draft','rejected']))
                                <a href="{{ route('community.events.edit', $event) }}" class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">Edit</a>
                                <form method="POST" action="{{ route('community.events.submit', $event) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs bg-green-600 text-white rounded hover:bg-green-700">Submit</button>
                                </form>
                            @endif
                            @if($event->status !== 'published')
                                <form method="POST" action="{{ route('community.events.destroy', $event) }}" onsubmit="return confirm('Delete this event?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-gray-400">Event එකක් නොමැත. <a href="{{ route('community.events.create') }}" class="text-blue-600 hover:underline">Create කරන්න</a></div>
                @endforelse
            </div>

            {{-- ============ ORGANIZATIONS TAB ============ --}}
            <div x-show="tab === 'orgs'" x-cloak>
                @forelse ($organizations as $org)
                    @php $title = $org->translationFor('si')?->name ?? $org->translations->first()?->name ?? 'Untitled'; @endphp
                    <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $title }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $org->organizationType?->name ?? '—' }} · {{ $org->created_at->format('Y-m-d') }}</p>
                            @if($org->status === 'rejected' && $org->rejection_reason)
                                <div class="mt-2 rounded bg-red-50 border border-red-200 p-2 text-xs text-red-700">
                                    <strong>Rejection Reason:</strong> {{ $org->rejection_reason }}
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @include('community._status-badge', ['status' => $org->status])
                            @if(in_array($org->status, ['draft','rejected']))
                                <a href="{{ route('community.organizations.edit', $org) }}" class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">Edit</a>
                                <form method="POST" action="{{ route('community.organizations.submit', $org) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs bg-green-600 text-white rounded hover:bg-green-700">Submit</button>
                                </form>
                            @endif
                            @if($org->status !== 'published')
                                <form method="POST" action="{{ route('community.organizations.destroy', $org) }}" onsubmit="return confirm('Delete this organization?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-gray-400">Organization එකක් නොමැත. <a href="{{ route('community.organizations.create') }}" class="text-blue-600 hover:underline">Create කරන්න</a></div>
                @endforelse
            </div>

            {{-- ============ FORUM TOPICS TAB ============ --}}
            <div x-show="tab === 'forum'" x-cloak>
                @forelse ($forumTopics as $topic)
                    <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <h3 class="font-medium text-gray-900">
                                <a href="{{ route('community.forum.show', $topic->slug) }}" class="hover:text-blue-600">
                                    {{ $topic->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $topic->category?->name ?? '—' }} ·
                                {{ $topic->replies->count() }} replies ·
                                {{ $topic->created_at->format('Y-m-d') }}
                                @if($topic->is_pinned) <span class="ml-1 text-blue-500">[Pinned]</span> @endif
                                @if($topic->is_locked) <span class="ml-1 text-red-500">[Locked]</span> @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="px-2.5 py-1 text-xs rounded-full bg-teal-100 text-teal-700 font-medium">Published</span>
                            <form method="POST" action="{{ route('community.forum.destroy', $topic) }}" onsubmit="return confirm('Delete this topic and all its replies?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-xs bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-gray-400">Forum Topic එකක් නොමැත. <a href="{{ route('community.forum.create') }}" class="text-blue-600 hover:underline">Create කරන්න</a></div>
                @endforelse
            </div>

        </div>

    </div>

</body>
</html>