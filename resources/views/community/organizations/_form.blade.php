@php
    $isEdit = isset($organization);
    $si = $isEdit ? $organization->translations->firstWhere('locale', 'si') : null;
    $en = $isEdit ? $organization->translations->firstWhere('locale', 'en') : null;
    $ta = $isEdit ? $organization->translations->firstWhere('locale', 'ta') : null;
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

    {{-- General Information --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">
            Organization Profile & Contact Information (සංවිධානයේ තොරතුරු)
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            <div>
                <label for="type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Organization Type *</label>
                <select name="type_id" id="type_id" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Select Organization Type</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" @selected(old('type_id', $organization->type_id ?? '') == $type->id)>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="registration_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Legal Registration No (ලියාපදිංචි අංකය)</label>
                <input type="text" name="registration_number" id="registration_number" value="{{ old('registration_number', $organization->registration_number ?? '') }}" placeholder="e.g. GA/MT/NGO/..." class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number (දුරකථන අංකය)</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $organization->phone ?? '') }}" placeholder="066-xxxxxxx / 07x-xxxxxxx" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address (විද්‍යුත් තැපෑල)</label>
                <input type="email" name="email" id="email" value="{{ old('email', $organization->email ?? '') }}" placeholder="info@organization.org" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website / Facebook Page</label>
                <input type="url" name="website" id="website" value="{{ old('website', $organization->website ?? '') }}" placeholder="https://..." class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Office Address (ලිපිනය)</label>
                <input type="text" name="address" id="address" value="{{ old('address', $organization->address ?? '') }}" placeholder="e.g. Pallegama, Laggala" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Organization Logo (ලාංඡනය)</label>
                <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-sm text-gray-700 dark:text-gray-300">
                @if ($isEdit && $organization->logo)
                    <img src="{{ asset('storage/' . $organization->logo) }}" alt="Logo" class="mt-2 w-20 h-20 object-contain rounded border p-1">
                @endif
            </div>

            <div>
                <label for="cover_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cover / Photo (පින්තූරය)</label>
                <input type="file" name="cover_image" id="cover_image" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-sm text-gray-700 dark:text-gray-300">
                @if ($isEdit && $organization->cover_image)
                    <img src="{{ asset('storage/' . $organization->cover_image) }}" alt="Cover" class="mt-2 w-40 h-24 object-cover rounded">
                @endif
            </div>
        </div>
    </div>

    {{-- Sinhala --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">
            සිංහල (Sinhala)
        </h3>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">සංවිධානයේ නම (Organization Name) *</label>
            <input type="text" name="si[name]" value="{{ old('si.name', $si?->name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">කෙටි හැඳින්වීම (Summary)</label>
            <textarea name="si[summary]" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('si.summary', $si?->summary) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ලබාදෙන සේවාවන් (Services Offered)</label>
            <input type="text" name="si[services_offered]" value="{{ old('si.services_offered', $si?->services_offered) }}" placeholder="e.g. Microfinance, Agricultural training, Environmental care" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">සම්පූර්ණ විස්තරය / අරමුණු (Full Description / Objectives)</label>
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
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Organization Name</label>
            <input type="text" name="en[name]" value="{{ old('en.name', $en?->name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Summary</label>
            <textarea name="en[summary]" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('en.summary', $en?->summary) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Services Offered</label>
            <input type="text" name="en[services_offered]" value="{{ old('en.services_offered', $en?->services_offered) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
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
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">அமைப்பின் பெயர் (Organization Name)</label>
            <input type="text" name="ta[name]" value="{{ old('ta.name', $ta?->name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">சுருக்கம் (Summary)</label>
            <textarea name="ta[summary]" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('ta.summary', $ta?->summary) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">வழங்கப்படும் சேவைகள் (Services Offered)</label>
            <input type="text" name="ta[services_offered]" value="{{ old('ta.services_offered', $ta?->services_offered) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
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
            {{ $isEdit ? 'Update Organization' : 'Save Organization Draft' }}
        </button>
    </div>
</div>
