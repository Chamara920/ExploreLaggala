@extends('layouts.public')

@section('title', __('community.new_forum_topic'))

@section('content')
@php
    $catLabel = fn ($cat) => $cat
        ? (\Illuminate\Support\Facades\Lang::has('community.cat_' . $cat->slug) ? __('community.cat_' . $cat->slug) : $cat->name)
        : '';
@endphp
<div class="max-w-3xl mx-auto px-4 py-10">

    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ url('/community/forum') }}" class="hover:text-teal-700">{{ __('community.forum') }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">{{ __('community.new_topic') }}</span>
    </nav>

    <div class="bg-white rounded-xl shadow-sm p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">💬 {{ __('community.new_forum_topic') }}</h1>

        @if ($errors->any())
            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('community.forum.store') }}">
            @csrf

            <div class="mb-5">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('community.category_label') }} <span class="text-red-500">*</span></label>
                <select name="category_id" id="category_id" required
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    <option value="">{{ __('community.select_category') }}</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $catLabel($cat) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-5">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">{{ __('community.title_label') }} <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required maxlength="255"
                    placeholder="{{ __('community.title_placeholder') }}"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
            </div>

            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">{{ __('community.content_label') }} <span class="text-red-500">*</span></label>
                <textarea name="content" id="content" rows="8" required minlength="10"
                    placeholder="{{ __('community.content_placeholder') }}"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">{{ old('content') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ url('/community/forum') }}" class="px-5 py-2 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">{{ __('community.cancel') }}</a>
                <button type="submit" class="px-6 py-2 rounded-md bg-teal-600 text-white hover:bg-teal-700 font-medium">
                    {{ __('community.post_topic') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
