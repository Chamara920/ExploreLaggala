@php
    $isEdit = isset($blogPost);

    $si = $isEdit
        ? $blogPost->translations->firstWhere('locale', 'si')
        : null;

    $en = $isEdit
        ? $blogPost->translations->firstWhere('locale', 'en')
        : null;

    $ta = $isEdit
        ? $blogPost->translations->firstWhere('locale', 'ta')
        : null;
@endphp

<div class="space-y-6">

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="rounded-lg bg-red-50 p-4 text-red-700 dark:bg-red-900/30 dark:text-red-300">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Basic Information --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">

        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">
            Basic Information
        </h3>

        {{-- Category --}}
        <div class="mb-5">

            <label for="category_id"
                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Category
            </label>

            <select
                name="category_id"
                id="category_id"
                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="">Select Category</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(old('category_id', $blogPost->category_id ?? '') == $category->id)
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

        </div>


        {{-- Cover Image --}}
        <div>

            <label for="cover_image"
                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Cover Image
            </label>

            <input
                type="file"
                name="cover_image"
                id="cover_image"
                accept="image/jpeg,image/png,image/webp"
                class="mt-2 block w-full text-sm text-gray-700 dark:text-gray-300"
            >

            @if ($isEdit && $blogPost->cover_image)
                <div class="mt-3">
                    <p class="text-sm text-gray-500 mb-2">
                        Current Cover Image
                    </p>

                    <img
                        src="{{ asset('storage/' . $blogPost->cover_image) }}"
                        alt="Cover Image"
                        class="w-48 h-32 object-cover rounded-lg"
                    >
                </div>
            @endif

        </div>

    </div>


    {{-- Sinhala --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">

        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">
            සිංහල
        </h3>

        {{-- Title --}}
        <div class="mb-4">

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Title
            </label>

            <input
                type="text"
                name="si[title]"
                value="{{ old('si.title', $si?->title) }}"
                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >

        </div>


        {{-- Slug --}}
        <div class="mb-4">

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Slug
            </label>

            <input
                type="text"
                name="si[slug]"
                value="{{ old('si.slug', $si?->slug) }}"
                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >

        </div>


        {{-- Excerpt --}}
        <div class="mb-4">

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Excerpt
            </label>

            <textarea
                name="si[excerpt]"
                rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >{{ old('si.excerpt', $si?->excerpt) }}</textarea>

        </div>


        {{-- Content --}}
        <div>

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Content
            </label>

            <div
                id="editor-si"
                class="quill-editor bg-white text-gray-900"
                data-content="{{ old('si.content', $si?->content) }}"
            ></div>

            <input
                type="hidden"
                name="si[content]"
                id="si-content"
                value="{{ old('si.content', $si?->content) }}"
            >

        </div>

    </div>


    {{-- English --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">

        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">
            English
        </h3>

        <div class="mb-4">

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Title
            </label>

            <input
                type="text"
                name="en[title]"
                value="{{ old('en.title', $en?->title) }}"
                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >

        </div>


        <div class="mb-4">

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Slug
            </label>

            <input
                type="text"
                name="en[slug]"
                value="{{ old('en.slug', $en?->slug) }}"
                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >

        </div>


        <div class="mb-4">

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Excerpt
            </label>

            <textarea
                name="en[excerpt]"
                rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >{{ old('en.excerpt', $en?->excerpt) }}</textarea>

        </div>


        <div>

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Content
            </label>

            <div
                id="editor-en"
                class="quill-editor bg-white text-gray-900"
                data-content="{{ old('en.content', $en?->content) }}"
            ></div>

            <input
                type="hidden"
                name="en[content]"
                id="en-content"
                value="{{ old('en.content', $en?->content) }}"
            >

        </div>

    </div>


    {{-- Tamil --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">

        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">
            தமிழ்
        </h3>

        <div class="mb-4">

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Title
            </label>

            <input
                type="text"
                name="ta[title]"
                value="{{ old('ta.title', $ta?->title) }}"
                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >

        </div>


        <div class="mb-4">

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Slug
            </label>

            <input
                type="text"
                name="ta[slug]"
                value="{{ old('ta.slug', $ta?->slug) }}"
                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >

        </div>


        <div class="mb-4">

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Excerpt
            </label>

            <textarea
                name="ta[excerpt]"
                rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >{{ old('ta.excerpt', $ta?->excerpt) }}</textarea>

        </div>


        <div>

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Content
            </label>

            <div
                id="editor-ta"
                class="quill-editor bg-white text-gray-900"
                data-content="{{ old('ta.content', $ta?->content) }}"
            ></div>

            <input
                type="hidden"
                name="ta[content]"
                id="ta-content"
                value="{{ old('ta.content', $ta?->content) }}"
            >

        </div>

    </div>


    {{-- Actions --}}
    <div class="flex items-center justify-end gap-3">

        <a
            href="{{ route('community.dashboard') }}"
            class="px-5 py-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200"
        >
            Cancel
        </a>

        <button
            type="submit"
            class="px-5 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700"
        >
            {{ $isEdit ? 'Update Draft' : 'Save Draft' }}
        </button>

    </div>

</div>