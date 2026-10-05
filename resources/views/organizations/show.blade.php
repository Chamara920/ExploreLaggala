@extends('layouts.public')

@section('title', $translation?->name ?? 'Organization')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <nav class="text-sm text-gray-500">
            <a href="{{ url('/') }}" class="hover:text-orange-700">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ url('/community/organizations') }}" class="hover:text-orange-700">Organizations</a>
            <span class="mx-2">/</span>
            <span class="text-gray-700">{{ $translation?->name }}</span>
        </nav>

        {{-- Report Button --}}
        @include('partials._report-modal', ['model' => $organization])
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if ($organization->cover_image)
            <img src="{{ asset('storage/' . $organization->cover_image) }}" alt="Cover" class="w-full h-64 object-cover">
        @endif

        <div class="p-8">
            <div class="flex items-start gap-5 mb-6">
                @if ($organization->logo)
                    <img src="{{ asset('storage/' . $organization->logo) }}" alt="Logo" class="w-20 h-20 object-cover rounded-xl shadow flex-shrink-0">
                @endif
                <div>
                    <span class="text-sm text-orange-600 font-medium">{{ $organization->organizationType?->name }}</span>
                    <h1 class="text-3xl font-bold text-gray-900 mt-1">{{ $translation?->name }}</h1>
                </div>
            </div>

            {{-- Contact Details --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 p-5 bg-orange-50 rounded-xl">
                @if ($organization->contact_phone)
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Phone</p><p class="text-gray-800">{{ $organization->contact_phone }}</p></div>
                @endif
                @if ($organization->contact_email)
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Email</p><a href="mailto:{{ $organization->contact_email }}" class="text-orange-600 hover:underline">{{ $organization->contact_email }}</a></div>
                @endif
                @if ($organization->address)
                    <div class="md:col-span-2"><p class="text-xs font-semibold text-gray-500 uppercase">Address</p><p class="text-gray-800">{{ $organization->address }}</p></div>
                @endif
                @if ($organization->website)
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Website</p><a href="{{ $organization->website }}" target="_blank" class="text-orange-600 hover:underline">{{ $organization->website }}</a></div>
                @endif
                @if ($organization->facebook_url)
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Facebook</p><a href="{{ $organization->facebook_url }}" target="_blank" class="text-orange-600 hover:underline">Facebook Page</a></div>
                @endif
            </div>

            @if ($translation?->summary)
                <p class="text-lg text-gray-600 mb-6 border-l-4 border-orange-400 pl-4">{{ $translation->summary }}</p>
            @endif

            @if ($translation?->description)
                <div class="prose max-w-none text-gray-700 leading-relaxed mb-6">
                    {!! $translation->description !!}
                </div>
            @endif

            @if ($translation?->services_offered)
                <div class="mt-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Services Offered</h2>
                    <div class="prose max-w-none text-gray-700">{!! $translation->services_offered !!}</div>
                </div>
            @endif
        </div>
    </div>

    {{-- Comments Section --}}
    @include('partials._comments', ['model' => $organization])

</div>
@endsection
