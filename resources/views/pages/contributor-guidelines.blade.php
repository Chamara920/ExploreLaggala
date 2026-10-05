@extends('layouts.public')

@section('title', __('pages.guidelines_title') . ' — Explore Laggala')

@section('content')

{{-- ============================================================
     HERO BANNER
============================================================ --}}
<section class="relative bg-gradient-to-br from-emerald-950 via-slate-900 to-teal-950 text-white py-14 lg:py-20 overflow-hidden">
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-500/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold uppercase tracking-wider mb-5">
            <i class="fas fa-shield-alt"></i>
            <span>{{ __('pages.guidelines_hero_tag') }}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
            {{ __('pages.guidelines_title') }}
        </h1>
        <p class="mt-4 text-base sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
            {{ __('pages.guidelines_subtitle') }}
        </p>
    </div>
</section>

{{-- ============================================================
     MAIN CONTENT
============================================================ --}}
<section class="bg-gray-50 py-14 lg:py-20">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-10">

        {{-- INTRO --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="flex gap-5 items-start">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i class="fas fa-hands-helping text-emerald-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">{{ __('pages.guidelines_intro_heading') }}</h2>
                    <p class="text-gray-600 leading-relaxed">{{ __('pages.guidelines_intro_body') }}</p>
                </div>
            </div>
        </div>

        {{-- WHO CAN CONTRIBUTE --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="flex gap-5 items-start">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-user-check text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">{{ __('pages.guidelines_eligibility_heading') }}</h2>
                    <p class="text-gray-600 leading-relaxed">{{ __('pages.guidelines_eligibility_body') }}</p>

                    {{-- Content types --}}
                    <div class="mt-5 grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach([
                            ['icon' => 'fa-newspaper', 'color' => 'emerald', 'label' => 'Blog Posts'],
                            ['icon' => 'fa-broadcast-tower', 'color' => 'blue', 'label' => 'News Articles'],
                            ['icon' => 'fa-calendar-alt', 'color' => 'purple', 'label' => 'Events'],
                            ['icon' => 'fa-building', 'color' => 'orange', 'label' => 'Organizations'],
                            ['icon' => 'fa-comments', 'color' => 'teal', 'label' => 'Forum Topics'],
                        ] as $type)
                            <div class="flex items-center gap-2 text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">
                                <i class="fas {{ $type['icon'] }} text-{{ $type['color'] }}-500 w-4"></i>
                                {{ $type['label'] }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- CONTENT STANDARDS --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="flex gap-5 items-start">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-file-alt text-amber-600 text-xl"></i>
                </div>
                <div class="w-full">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">{{ __('pages.guidelines_content_heading') }}</h2>
                    <p class="text-gray-600 leading-relaxed mb-5">{{ __('pages.guidelines_content_body') }}</p>

                    <ul class="space-y-3 text-sm text-gray-700">
                        @foreach([
                            ['icon' => 'fa-check-circle', 'color' => 'text-emerald-500', 'text' => 'Content must be relevant to the Laggala region and its community.'],
                            ['icon' => 'fa-check-circle', 'color' => 'text-emerald-500', 'text' => 'Write in clear, accurate, and respectful language. Multilingual submissions (Sinhala, Tamil, English) are encouraged.'],
                            ['icon' => 'fa-check-circle', 'color' => 'text-emerald-500', 'text' => 'Submit original content. Always credit any third-party sources or photos.'],
                            ['icon' => 'fa-check-circle', 'color' => 'text-emerald-500', 'text' => 'Include high-quality images where possible. Avoid watermarked or copyrighted images.'],
                            ['icon' => 'fa-check-circle', 'color' => 'text-emerald-500', 'text' => 'Provide accurate dates, locations, and contact information for events and organizations.'],
                        ] as $item)
                            <li class="flex items-start gap-3">
                                <i class="fas {{ $item['icon'] }} {{ $item['color'] }} mt-0.5 flex-shrink-0"></i>
                                <span>{{ $item['text'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- COMMUNITY RULES --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="flex gap-5 items-start">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i class="fas fa-list-ul text-emerald-600 text-xl"></i>
                </div>
                <div class="w-full">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('pages.guidelines_rules_heading') }}</h2>
                    <div class="space-y-3 text-sm text-gray-700">
                        @foreach([
                            '✅ Be respectful and courteous in all forum discussions and content.',
                            '✅ Use your real name and accurate profile information.',
                            '✅ Disclose any personal or commercial interest in content you submit.',
                            '✅ Respond promptly to reviewer feedback to speed up publication.',
                            '✅ Report inaccurate or inappropriate content using the report feature.',
                        ] as $rule)
                            <div class="flex items-start gap-2 bg-gray-50 rounded-lg px-4 py-2.5">{{ $rule }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- PROHIBITED CONTENT --}}
        <div class="bg-red-50 rounded-2xl border border-red-200 p-8">
            <div class="flex gap-5 items-start">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                    <i class="fas fa-ban text-red-600 text-xl"></i>
                </div>
                <div class="w-full">
                    <h2 class="text-xl font-bold text-red-800 mb-4">{{ __('pages.guidelines_prohibited_heading') }}</h2>
                    <ul class="space-y-2 text-sm text-red-700">
                        @foreach([
                            'Political content, propaganda, or partisan messaging.',
                            'Defamatory, hateful, or discriminatory content.',
                            'Adult, violent, or otherwise inappropriate content.',
                            'Spam, unsolicited commercial advertising, or promotional schemes.',
                            'Content that is factually incorrect or intentionally misleading.',
                            'Copyright-infringing material, including unlicensed images or text.',
                        ] as $prohibited)
                            <li class="flex items-start gap-2">
                                <i class="fas fa-times-circle text-red-400 mt-0.5 flex-shrink-0"></i>
                                {{ $prohibited }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- REVIEW PROCESS --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="flex gap-5 items-start">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-teal-100 flex items-center justify-center">
                    <i class="fas fa-tasks text-teal-600 text-xl"></i>
                </div>
                <div class="w-full">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">{{ __('pages.guidelines_approval_heading') }}</h2>
                    <p class="text-gray-600 leading-relaxed mb-6">{{ __('pages.guidelines_approval_body') }}</p>

                    {{-- Steps --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        @foreach([
                            ['step' => '1', 'label' => 'Submit', 'icon' => 'fa-paper-plane', 'desc' => 'Create and submit your content from your dashboard.'],
                            ['step' => '2', 'label' => 'Review', 'icon' => 'fa-search', 'desc' => 'The Media Unit team reviews for quality and accuracy.'],
                            ['step' => '3', 'label' => 'Publish', 'icon' => 'fa-check-circle', 'desc' => 'Approved content goes live and is attributed to you.'],
                        ] as $step)
                            <div class="flex-1 flex flex-col items-center text-center bg-gray-50 rounded-xl p-4">
                                <div class="w-9 h-9 rounded-full bg-emerald-600 text-white text-sm font-bold flex items-center justify-center mb-2">{{ $step['step'] }}</div>
                                <div class="font-semibold text-gray-900 text-sm mb-1">{{ $step['label'] }}</div>
                                <div class="text-xs text-gray-500">{{ $step['desc'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <div class="text-center pt-2">
            @guest
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold px-7 py-3 rounded-xl shadow-lg transition">
                    <i class="fas fa-user-plus"></i>
                    {{ __('pages.guidelines_become_cta') }}
                </a>
            @else
                <a href="{{ route('community.dashboard') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold px-7 py-3 rounded-xl shadow-lg transition">
                    <i class="fas fa-tachometer-alt"></i>
                    {{ __('pages.guidelines_become_cta') }}
                </a>
            @endguest
        </div>

    </div>
</section>

@endsection
