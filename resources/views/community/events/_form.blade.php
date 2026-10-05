@php
    $isEdit = isset($event);
    $si = $isEdit ? $event->translations->firstWhere('locale', 'si') : null;
    $en = $isEdit ? $event->translations->firstWhere('locale', 'en') : null;
    $ta = $isEdit ? $event->translations->firstWhere('locale', 'ta') : null;
@endphp

<div class="space-y-6">
    @if ($errors->any())
        <div class="rounded-lg bg-red-50 p-4 text-red-700 dark:bg-red-900/30 dark:text-red-300">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Event Dates & Logistics --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">
            Event Details & Schedule (උත්සව විස්තර)
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Select Event Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $event->category_id ?? '') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date *</label>
                <input type="date" name="start_date" id="start_date" required value="{{ old('start_date', $isEdit && $event->start_date ? $event->start_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date (Optional)</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $isEdit && $event->end_date ? $event->end_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Time (e.g. 09:00 AM)</label>
                <input type="text" name="start_time" id="start_time" value="{{ old('start_time', $event->start_time ?? '') }}" placeholder="09:00 AM" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="location_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Venue / Location Name</label>
                <input type="text" name="location_name" id="location_name" value="{{ old('location_name', $event->location_name ?? '') }}" placeholder="e.g. Pallegama Ground / Temple" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="google_maps_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Google Maps Link</label>
                <input type="url" name="google_maps_url" id="google_maps_url" value="{{ old('google_maps_url', $event->google_maps_url ?? '') }}" placeholder="https://maps.app.goo.gl/..." class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="organizer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Organizer Name / Organization</label>
                <input type="text" name="organizer_name" id="organizer_name" value="{{ old('organizer_name', $event->organizer_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="organizer_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Organizer Contact (Phone / Email)</label>
                <input type="text" name="organizer_contact" id="organizer_contact" value="{{ old('organizer_contact', $event->organizer_contact ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
        </div>

        <div>
            <label for="cover_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Event Poster / Banner Image</label>
            <input type="file" name="cover_image" id="cover_image" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-sm text-gray-700 dark:text-gray-300">

            @if ($isEdit && $event->cover_image)
                <div class="mt-3">
                    <p class="text-sm text-gray-500 mb-2">Current Poster Image</p>
                    <img src="{{ asset('storage/' . $event->cover_image) }}" alt="Event Banner" class="w-48 h-32 object-cover rounded-lg">
                </div>
            @endif
        </div>
    </div>

    {{-- Sinhala --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">
            සිංහල (Sinhala)
        </h3>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">උත්සවයේ නම (Title)</label>
            <input type="text" name="si[title]" value="{{ old('si.title', $si?->title) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">කෙටි හැඳින්වීම (Excerpt)</label>
            <textarea name="si[excerpt]" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('si.excerpt', $si?->excerpt) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">සම්පූර්ණ විස්තරය (Description)</label>
            <div id="editor-si" class="quill-editor bg-white text-gray-900" data-content="{{ old('si.description', $si?->description) }}"></div>
            <input type="hidden" name="si[description]" id="si-description" value="{{ old('si.description', $si?->description) }}">
        </div>
    </div>

    {{-- English --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">
            English
        </h3>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Event Title</label>
            <input type="text" name="en[title]" value="{{ old('en.title', $en?->title) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Excerpt</label>
            <textarea name="en[excerpt]" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('en.excerpt', $en?->excerpt) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Description</label>
            <div id="editor-en" class="quill-editor bg-white text-gray-900" data-content="{{ old('en.description', $en?->description) }}"></div>
            <input type="hidden" name="en[description]" id="en-description" value="{{ old('en.description', $en?->description) }}">
        </div>
    </div>

    {{-- Tamil --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">
            தமிழ் (Tamil)
        </h3>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">நிகழ்வு தலைப்பு (Title)</label>
            <input type="text" name="ta[title]" value="{{ old('ta.title', $ta?->title) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">சுருக்கம் (Excerpt)</label>
            <textarea name="ta[excerpt]" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('ta.excerpt', $ta?->excerpt) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">முழு விளக்கம் (Description)</label>
            <div id="editor-ta" class="quill-editor bg-white text-gray-900" data-content="{{ old('ta.description', $ta?->description) }}"></div>
            <input type="hidden" name="ta[description]" id="ta-description" value="{{ old('ta.description', $ta?->description) }}">
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('community.dashboard') }}" class="px-5 py-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">Cancel</a>
        <button type="submit" class="px-5 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 font-medium">
            {{ $isEdit ? 'Update Event' : 'Save Event Draft' }}
        </button>
    </div>
</div>
