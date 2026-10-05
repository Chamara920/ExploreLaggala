@extends('layouts.public')

@section('title', 'Community Organizations — සංවිධාන')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">🤝 Community Organizations</h1>
        <p class="text-gray-500 mt-1">ලග්ගල-පල්ලේගම ප්‍රදේශයේ ජනතාවට සේවය සපයන සංවිධාන</p>
    </div>

    {{-- Type Filter --}}
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ url('/community/organizations') }}"
           class="px-4 py-1.5 rounded-full text-sm {{ !request('type') ? 'bg-orange-600 text-white' : 'bg-white text-gray-600 border hover:bg-gray-50' }}">
            All
        </a>
        @foreach ($types as $type)
            <a href="{{ url('/community/organizations?type=' . $type->slug) }}"
               class="px-4 py-1.5 rounded-full text-sm {{ request('type') === $type->slug ? 'bg-orange-600 text-white' : 'bg-white text-gray-600 border hover:bg-gray-50' }}">
                {{ $type->name }}
            </a>
        @endforeach
    </div>

    @if ($organizations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($organizations as $org)
                @php $t = $org->translationFor($locale); @endphp
                <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            @if ($org->logo)
                                <img src="{{ asset('storage/' . $org->logo) }}" alt="Logo" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                            @else
                                <div class="w-16 h-16 bg-orange-100 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">🤝</div>
                            @endif
                            <div class="min-w-0">
                                <span class="text-xs text-orange-600 font-medium">{{ $org->organizationType?->name }}</span>
                                <h2 class="font-bold text-gray-900 mt-0.5 leading-snug">
                                    <a href="{{ url('/community/organizations/' . ($t?->slug ?? $org->id)) }}" class="hover:text-orange-700">
                                        {{ $t?->name ?? 'Untitled' }}
                                    </a>
                                </h2>
                            </div>
                        </div>
                        @if ($t?->summary)
                            <p class="text-sm text-gray-500 mt-3 line-clamp-2">{{ $t->summary }}</p>
                        @endif
                        <div class="mt-4 flex flex-wrap gap-2 text-xs text-gray-400">
                            @if ($org->contact_phone)
                                <span>📞 {{ $org->contact_phone }}</span>
                            @endif
                            @if ($org->address)
                                <span>📍 {{ Str::limit($org->address, 40) }}</span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-8">{{ $organizations->links() }}</div>
    @else
        <div class="text-center py-20 text-gray-400">
            <div class="text-5xl mb-4">🤝</div>
            <p>Community Organizations නොමැත.</p>
        </div>
    @endif

</div>
@endsection
