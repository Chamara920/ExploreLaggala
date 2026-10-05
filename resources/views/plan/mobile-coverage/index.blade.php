@extends('layouts.public')

@section('title', __('mobile_coverage.page_title'))

@section('content')

{{-- ========= HERO ========= --}}
<section class="relative overflow-hidden" style="background:linear-gradient(135deg,#0c4a6e 0%,#0369a1 45%,#0284c7 80%,#0ea5e9 100%);">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
    <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        <div class="max-w-3xl">
            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-sky-300/30 bg-sky-400/10 px-3.5 py-1 text-xs font-semibold text-sky-200 backdrop-blur-sm">
                <i class="bi bi-reception-4"></i> {{ __('mobile_coverage.badge_plan') }}
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                {{ __('mobile_coverage.hero_title') }}
            </h1>
            <p class="mt-4 max-w-2xl text-base text-sky-100 sm:text-lg leading-relaxed">
                {{ __('mobile_coverage.hero_subtitle') }}
            </p>
        </div>

        {{-- Quick Stats --}}
        <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 max-w-2xl">
            <div class="rounded-xl border border-white/15 bg-white/10 p-3.5 backdrop-blur-md">
                <div class="text-2xl font-black text-white">{{ $reports->count() }}</div>
                <div class="text-xs font-medium text-sky-200">{{ __('mobile_coverage.stat_reports') }}</div>
            </div>
            <div class="rounded-xl border border-white/15 bg-white/10 p-3.5 backdrop-blur-md">
                <div class="text-2xl font-black text-white">{{ $mapData->pluck('destination_id')->filter()->unique()->count() }}</div>
                <div class="text-xs font-medium text-sky-200">{{ __('mobile_coverage.stat_destinations') }}</div>
            </div>
            <div class="col-span-2 sm:col-span-1 rounded-xl border border-red-300/30 bg-red-500/20 p-3.5 backdrop-blur-md">
                <div class="text-2xl font-black text-white flex items-center gap-1.5">
                    {{ $reports->where('signal_strength', 'none')->count() }}
                    <span class="text-xs font-normal text-red-200">⚠️</span>
                </div>
                <div class="text-xs font-medium text-red-200">{{ __('mobile_coverage.stat_dead_zones') }}</div>
            </div>
        </div>
    </div>
</section>

{{-- ========= FLASH NOTIFICATIONS ========= --}}
@if(session('success'))
<div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
    <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800 shadow-sm">
        <i class="bi bi-check-circle-fill text-lg text-emerald-600"></i>
        <span class="text-sm font-semibold">{{ session('success') }}</span>
    </div>
</div>
@endif

@if($errors->any())
<div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
    <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 shadow-sm">
        <p class="mb-2 text-sm font-bold text-red-700 flex items-center gap-1.5">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ __('common.please_fix_errors') ?? 'Please correct the errors below:' }}
        </p>
        <ul class="list-disc list-inside space-y-0.5 text-sm text-red-600">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
</div>
@endif

{{-- ========= INTERACTIVE COVERAGE MAP ========= --}}
<div class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="h-2 w-7 rounded-full bg-sky-600"></span>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">{{ __('mobile_coverage.map_title') }}</h2>
                <span class="rounded-full bg-sky-100 text-sky-800 px-3 py-0.5 text-xs font-bold">{{ $mapData->count() }}</span>
            </div>
            <p class="text-xs text-slate-500 mt-1 max-w-2xl">{{ __('mobile_coverage.map_subtitle') }}</p>
        </div>

        {{-- Map Filter Buttons --}}
        <div class="flex flex-wrap items-center gap-1.5 text-xs">
            <button type="button" class="coverage-filter-btn px-3 py-1.5 rounded-lg font-bold border transition bg-sky-600 text-white border-sky-600 shadow-sm" data-operator="all">
                {{ __('mobile_coverage.filter_all_operators') }}
            </button>
            <button type="button" class="coverage-filter-btn px-3 py-1.5 rounded-lg font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition" data-operator="dialog">
                📶 Dialog
            </button>
            <button type="button" class="coverage-filter-btn px-3 py-1.5 rounded-lg font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition" data-operator="mobitel">
                📶 Mobitel
            </button>
            <button type="button" class="coverage-filter-btn px-3 py-1.5 rounded-lg font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition" data-operator="hutch">
                📶 Hutch
            </button>
            <button type="button" class="coverage-filter-btn px-3 py-1.5 rounded-lg font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition" data-operator="airtel">
                📶 Airtel
            </button>
            <button type="button" class="coverage-filter-btn px-3 py-1.5 rounded-lg font-bold border border-red-300 bg-red-50 text-red-700 hover:bg-red-100 transition" data-operator="deadzone">
                ⚠️ {{ __('mobile_coverage.filter_dead_zones') }}
            </button>
        </div>
    </div>

    {{-- Leaflet Map Container --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <div id="coverageMap" class="overflow-hidden rounded-2xl border border-slate-200 shadow-md relative z-10" style="height:440px;width:100%;"></div>

    {{-- Map Legend & Hint --}}
    <div class="mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-slate-600 border-b border-slate-100 pb-4">
        <div class="flex flex-wrap items-center gap-3">
            <span class="font-bold text-slate-800">{{ __('mobile_coverage.map_legend_title') }}</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-3 w-3 rounded-full bg-emerald-500 shadow-sm"></span> {{ __('mobile_coverage.signal_excellent') }} / {{ __('mobile_coverage.signal_good') }}</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-3 w-3 rounded-full bg-amber-400 shadow-sm"></span> {{ __('mobile_coverage.signal_fair') }}</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-3 w-3 rounded-full bg-orange-500 shadow-sm"></span> {{ __('mobile_coverage.signal_poor') }}</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-3 w-3 rounded-full bg-rose-600 shadow-sm"></span> {{ __('mobile_coverage.signal_none') }}</span>
        </div>
        <div class="text-sky-700 font-medium flex items-center gap-1">
            <i class="bi bi-geo-alt-fill text-sky-600"></i>
            {{ __('mobile_coverage.click_map_hint') }}
        </div>
    </div>
</div>

{{-- ========= MAIN CONTENT (REPORTS + SIDEBAR FORM) ========= --}}
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-3 lg:gap-8 items-start">

        {{-- ========= REPORTS LIST (2 COLS) ========= --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="bi bi-reception-4 text-sky-600"></i>
                    {{ __('mobile_coverage.list_title') }}
                </h2>
                <span class="text-xs font-semibold text-slate-500">{{ $reports->count() }} {{ __('mobile_coverage.stat_reports') }}</span>
            </div>

            @if($reports->isEmpty())
            <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 py-16 text-center bg-slate-50/50">
                <i class="bi bi-reception-0 text-5xl text-slate-300 mb-3"></i>
                <h3 class="text-lg font-bold text-slate-700">{{ __('mobile_coverage.no_reports_title') }}</h3>
                <p class="mt-1 text-sm text-slate-400 max-w-md">{{ __('mobile_coverage.no_reports_subtitle') }}</p>
            </div>
            @else
            <div class="space-y-3" id="reportsContainer">
                @foreach($reports as $report)
                @php
                    $signalStyles = match($report->signal_strength) {
                        'excellent' => ['bg'=>'bg-emerald-50', 'border'=>'border-emerald-200', 'badge'=>'bg-emerald-100 text-emerald-800', 'icon'=>'text-emerald-600', 'bars'=>4, 'label'=>__('mobile_coverage.signal_excellent')],
                        'good'      => ['bg'=>'bg-emerald-50', 'border'=>'border-emerald-200', 'badge'=>'bg-emerald-100 text-emerald-800', 'icon'=>'text-emerald-600', 'bars'=>3, 'label'=>__('mobile_coverage.signal_good')],
                        'fair'      => ['bg'=>'bg-amber-50',   'border'=>'border-amber-200',   'badge'=>'bg-amber-100 text-amber-800',   'icon'=>'text-amber-600',   'bars'=>2, 'label'=>__('mobile_coverage.signal_fair')],
                        'poor'      => ['bg'=>'bg-orange-50',  'border'=>'border-orange-200',  'badge'=>'bg-orange-100 text-orange-800', 'icon'=>'text-orange-600', 'bars'=>1, 'label'=>__('mobile_coverage.signal_poor')],
                        default     => ['bg'=>'bg-rose-50',    'border'=>'border-rose-200',    'badge'=>'bg-rose-100 text-rose-800',     'icon'=>'text-rose-600',    'bars'=>0, 'label'=>__('mobile_coverage.signal_none')],
                    };

                    $operatorLabels = [
                        'dialog' => __('mobile_coverage.op_dialog'),
                        'mobitel' => __('mobile_coverage.op_mobitel'),
                        'hutch' => __('mobile_coverage.op_hutch'),
                        'airtel' => __('mobile_coverage.op_airtel'),
                        'multiple' => __('mobile_coverage.op_multiple'),
                        'other' => __('mobile_coverage.op_other'),
                    ];

                    $techLabels = [
                        '5g' => __('mobile_coverage.cov_5g'),
                        '4g' => __('mobile_coverage.cov_4g'),
                        '3g' => __('mobile_coverage.cov_3g'),
                        '2g' => __('mobile_coverage.cov_2g'),
                        'no_signal' => __('mobile_coverage.cov_no_signal'),
                    ];

                    $locationName = $report->translationFor($locale)?->location_name ?? $report->location_name;
                    $description = $report->translationFor($locale)?->description ?? $report->description;
                    $destName = $report->destination?->translationFor($locale)?->name;

                    $canEdit = auth()->check() && (
                        auth()->id() === $report->reported_by ||
                        auth()->user()->hasAnyRole(['admin', 'super_admin', 'community_user'])
                    );
                @endphp

                <div class="rounded-2xl border {{ $signalStyles['border'] }} bg-white p-4 sm:p-5 shadow-sm hover:shadow-md transition report-card"
                     data-id="{{ $report->id }}"
                     data-operator="{{ $report->network_operator }}"
                     data-signal="{{ $report->signal_strength }}"
                     data-lat="{{ $report->latitude }}"
                     data-lng="{{ $report->longitude }}"
                     data-location="{{ $locationName }}"
                     data-dest-id="{{ $report->destination_id }}"
                     data-coverage-type="{{ $report->coverage_type }}"
                     data-description="{{ $description }}">
                    
                    <div class="flex items-start gap-4">
                        {{-- Signal Icon Visual --}}
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl {{ $signalStyles['bg'] }} {{ $signalStyles['border'] }} border">
                            <i class="bi bi-reception-{{ $signalStyles['bars'] }} text-2xl {{ $signalStyles['icon'] }}"></i>
                        </div>

                        {{-- Report Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-1.5">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-base font-bold text-slate-900">{{ $locationName }}</h3>
                                    
                                    @if($destName)
                                    <span class="inline-flex items-center gap-1 rounded-md bg-sky-50 px-2 py-0.5 text-xs font-semibold text-sky-700 border border-sky-200">
                                        <i class="bi bi-geo-alt"></i> {{ $destName }}
                                    </span>
                                    @endif

                                    <span class="rounded-full {{ $signalStyles['badge'] }} px-2.5 py-0.5 text-[11px] font-bold">
                                        {{ $signalStyles['label'] }}
                                    </span>

                                    <span class="rounded-full bg-slate-100 text-slate-700 px-2.5 py-0.5 text-[11px] font-bold">
                                        {{ $techLabels[$report->coverage_type] ?? strtoupper($report->coverage_type) }}
                                    </span>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex items-center gap-1.5">
                                    @if($report->latitude && $report->longitude)
                                    <button type="button"
                                            onclick="focusReportOnMap({{ $report->latitude }}, {{ $report->longitude }}, {{ $report->id }})"
                                            class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
                                            title="{{ __('mobile_coverage.view_on_map') }}">
                                        <i class="bi bi-map text-sky-600"></i>
                                        <span class="hidden sm:inline">{{ __('mobile_coverage.view_on_map') }}</span>
                                    </button>
                                    @endif

                                    @if($canEdit)
                                    <button type="button"
                                            onclick="openEditModal({{ $report->id }})"
                                            class="inline-flex items-center gap-1 rounded-lg border border-orange-200 bg-orange-50 px-2.5 py-1 text-xs font-bold text-orange-700 hover:bg-orange-100 transition"
                                            title="{{ __('mobile_coverage.edit_report') }}">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>{{ __('mobile_coverage.edit_report') }}</span>
                                    </button>

                                    <form method="POST" action="{{ route('plan.coverage.destroy', $report) }}" class="inline" onsubmit="return confirm('{{ __('mobile_coverage.confirm_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 p-1 text-xs font-bold text-red-600 hover:bg-red-100 transition"
                                                title="{{ __('mobile_coverage.delete_report') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>

                            {{-- Network Details row --}}
                            <div class="flex flex-wrap items-center gap-3 text-xs text-slate-600 mb-1">
                                <span class="font-semibold text-slate-800">
                                    <i class="bi bi-broadcast text-sky-600 me-1"></i>
                                    {{ $operatorLabels[$report->network_operator] ?? ucfirst($report->network_operator) }}
                                </span>
                                @if($report->latitude && $report->longitude)
                                <span class="text-slate-400">·</span>
                                <span class="text-slate-500 font-mono">
                                    <i class="bi bi-geo text-slate-400 me-0.5"></i>
                                    {{ number_format($report->latitude, 5) }}, {{ number_format($report->longitude, 5) }}
                                </span>
                                @endif
                            </div>

                            {{-- Description notes --}}
                            @if($description)
                            <div class="mt-2 rounded-xl bg-slate-50/80 border border-slate-100 p-2.5 text-xs text-slate-700 leading-relaxed italic">
                                “{{ $description }}”
                            </div>
                            @endif

                            {{-- Metadata --}}
                            <div class="mt-2.5 flex items-center justify-between text-[11px] text-slate-400">
                                <div class="flex items-center gap-1.5">
                                    <i class="bi bi-person-circle"></i>
                                    <span>{{ $report->reporter->name ?? __('mobile_coverage.community_badge') }}</span>
                                </div>
                                <span>{{ $report->reported_at ? $report->reported_at->diffForHumans() : '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ========= SIDEBAR: SUBMIT / COMMUNITY FORM ========= --}}
        <aside class="mt-8 lg:mt-0">
            @auth
            <div class="sticky top-24 rounded-2xl border border-sky-200 bg-gradient-to-br from-sky-50/80 via-white to-sky-50/40 p-5 sm:p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-600 text-white shadow-sm">
                        <i class="bi bi-plus-circle text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">{{ __('mobile_coverage.form_title_new') }}</h3>
                        <p class="text-xs text-slate-500">{{ __('mobile_coverage.form_subtitle') }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('plan.coverage.store') }}" class="space-y-3.5">
                    @csrf

                    @if(isset($destinations) && $destinations->isNotEmpty())
                    <div>
                        <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('mobile_coverage.field_destination') }}</label>
                        <select name="destination_id" id="destSelect"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                            <option value="">{{ __('mobile_coverage.select_destination_hint') }}</option>
                            @foreach($destinations as $dest)
                                @php $dName = $dest->translationFor($locale)?->name ?? 'Destination #' . $dest->id; @endphp
                                <option value="{{ $dest->id }}"
                                        data-name="{{ $dName }}"
                                        data-lat="{{ $dest->latitude }}"
                                        data-lng="{{ $dest->longitude }}"
                                        {{ old('destination_id') == $dest->id ? 'selected' : '' }}>
                                    📍 {{ $dName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div>
                        <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('mobile_coverage.field_location_name') }}</label>
                        <input type="text" name="location_name" id="covLocationName" value="{{ old('location_name') }}" required
                               placeholder="{{ __('mobile_coverage.placeholder_location') }}"
                               class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('mobile_coverage.field_operator') }}</label>
                        <select name="network_operator" required
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                            <option value="">{{ __('mobile_coverage.select_operator') }}</option>
                            <option value="dialog" {{ old('network_operator')=='dialog'?'selected':'' }}>Dialog Axiata</option>
                            <option value="mobitel" {{ old('network_operator')=='mobitel'?'selected':'' }}>SLT-Mobitel</option>
                            <option value="hutch" {{ old('network_operator')=='hutch'?'selected':'' }}>Hutch</option>
                            <option value="airtel" {{ old('network_operator')=='airtel'?'selected':'' }}>Airtel</option>
                            <option value="multiple" {{ old('network_operator')=='multiple'?'selected':'' }}>Multiple Networks</option>
                            <option value="other" {{ old('network_operator')=='other'?'selected':'' }}>Other / Satellite</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('mobile_coverage.field_coverage_type') }}</label>
                            <select name="coverage_type" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                <option value="">{{ __('mobile_coverage.select_coverage_type') }}</option>
                                <option value="5g" {{ old('coverage_type')=='5g'?'selected':'' }}>5G</option>
                                <option value="4g" {{ old('coverage_type', '4g')=='4g'?'selected':'' }}>4G LTE</option>
                                <option value="3g" {{ old('coverage_type')=='3g'?'selected':'' }}>3G</option>
                                <option value="2g" {{ old('coverage_type')=='2g'?'selected':'' }}>2G GSM</option>
                                <option value="no_signal" {{ old('coverage_type')=='no_signal'?'selected':'' }}>No Signal ⚠️</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('mobile_coverage.field_signal_strength') }}</label>
                            <select name="signal_strength" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                <option value="">{{ __('mobile_coverage.select_signal_strength') }}</option>
                                <option value="excellent" {{ old('signal_strength')=='excellent'?'selected':'' }}>🟢 {{ __('mobile_coverage.signal_excellent') }}</option>
                                <option value="good" {{ old('signal_strength', 'good')=='good'?'selected':'' }}>🟢 {{ __('mobile_coverage.signal_good') }}</option>
                                <option value="fair" {{ old('signal_strength')=='fair'?'selected':'' }}>🟡 {{ __('mobile_coverage.signal_fair') }}</option>
                                <option value="poor" {{ old('signal_strength')=='poor'?'selected':'' }}>🟠 {{ __('mobile_coverage.signal_poor') }}</option>
                                <option value="none" {{ old('signal_strength')=='none'?'selected':'' }}>🔴 {{ __('mobile_coverage.signal_none') }}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('mobile_coverage.field_description') }}</label>
                        <textarea name="description" rows="2"
                                  placeholder="{{ __('mobile_coverage.placeholder_description') }}"
                                  class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <div class="mb-1 flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-700">{{ __('mobile_coverage.field_gps') }}</label>
                            <button type="button" id="getCovLocation" class="text-sky-700 hover:text-sky-800 text-[11px] font-bold">
                                {{ __('mobile_coverage.btn_use_my_location') }}
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="latitude" id="covLat" value="{{ old('latitude', '7.5583') }}" step="any"
                                   placeholder="Latitude"
                                   class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-mono focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                            <input type="number" name="longitude" id="covLng" value="{{ old('longitude', '80.7306') }}" step="any"
                                   placeholder="Longitude"
                                   class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-mono focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full rounded-xl bg-sky-600 py-3 text-sm font-bold text-white shadow-md transition hover:bg-sky-700 active:scale-95 flex items-center justify-center gap-2">
                        <i class="bi bi-send-fill"></i>
                        {{ __('mobile_coverage.btn_submit') }}
                    </button>
                </form>
            </div>
            @else
            <div class="rounded-2xl border border-sky-100 bg-gradient-to-br from-sky-50 to-white p-6 text-center shadow-sm">
                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-100 text-sky-600">
                    <i class="bi bi-reception-4 text-2xl"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">{{ __('mobile_coverage.guest_title') }}</h3>
                <p class="text-xs text-slate-500 mb-5 leading-relaxed">{{ __('mobile_coverage.guest_subtitle') }}</p>
                <a href="{{ route('login') }}"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-sky-700 shadow-sm">
                    <i class="bi bi-box-arrow-in-right"></i> {{ __('mobile_coverage.btn_sign_in') }}
                </a>
            </div>
            @endauth
        </aside>

    </div>
</div>

{{-- ========= EDIT REPORT MODAL ========= --}}
@auth
<div id="editCoverageModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                <i class="bi bi-pencil-square text-sky-600"></i>
                {{ __('mobile_coverage.form_title_edit') }}
            </h3>
            <button type="button" onclick="closeEditModal()" class="rounded-lg p-1 text-slate-400 hover:text-slate-600">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        <form id="editCoverageForm" method="POST" action="" class="space-y-3.5">
            @csrf
            @method('PUT')

            @if(isset($destinations) && $destinations->isNotEmpty())
            <div>
                <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('mobile_coverage.field_destination') }}</label>
                <select name="destination_id" id="editDestSelect"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                    <option value="">{{ __('mobile_coverage.select_destination_hint') }}</option>
                    @foreach($destinations as $dest)
                        @php $dName = $dest->translationFor($locale)?->name ?? 'Destination #' . $dest->id; @endphp
                        <option value="{{ $dest->id }}" data-lat="{{ $dest->latitude }}" data-lng="{{ $dest->longitude }}" data-name="{{ $dName }}">
                            📍 {{ $dName }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div>
                <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('mobile_coverage.field_location_name') }}</label>
                <input type="text" name="location_name" id="editLocationName" required
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
            </div>

            <div>
                <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('mobile_coverage.field_operator') }}</label>
                <select name="network_operator" id="editOperator" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                    <option value="dialog">Dialog Axiata</option>
                    <option value="mobitel">SLT-Mobitel</option>
                    <option value="hutch">Hutch</option>
                    <option value="airtel">Airtel</option>
                    <option value="multiple">Multiple Networks</option>
                    <option value="other">Other / Satellite</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-2.5">
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('mobile_coverage.field_coverage_type') }}</label>
                    <select name="coverage_type" id="editCoverageType" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                        <option value="5g">5G</option>
                        <option value="4g">4G LTE</option>
                        <option value="3g">3G</option>
                        <option value="2g">2G GSM</option>
                        <option value="no_signal">No Signal ⚠️</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('mobile_coverage.field_signal_strength') }}</label>
                    <select name="signal_strength" id="editSignalStrength" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                        <option value="excellent">🟢 {{ __('mobile_coverage.signal_excellent') }}</option>
                        <option value="good">🟢 {{ __('mobile_coverage.signal_good') }}</option>
                        <option value="fair">🟡 {{ __('mobile_coverage.signal_fair') }}</option>
                        <option value="poor">🟠 {{ __('mobile_coverage.signal_poor') }}</option>
                        <option value="none">🔴 {{ __('mobile_coverage.signal_none') }}</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="mb-1 block text-xs font-bold text-slate-700">{{ __('mobile_coverage.field_description') }}</label>
                <textarea name="description" id="editDescription" rows="2"
                          class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs sm:text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="mb-1 block text-[11px] font-bold text-slate-500">Latitude</label>
                    <input type="number" name="latitude" id="editLat" step="any"
                           class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-mono focus:border-sky-500 focus:outline-none">
                </div>
                <div>
                    <label class="mb-1 block text-[11px] font-bold text-slate-500">Longitude</label>
                    <input type="number" name="longitude" id="editLng" step="any"
                           class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-mono focus:border-sky-500 focus:outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50">
                    {{ __('mobile_coverage.btn_cancel') }}
                </button>
                <button type="submit" class="rounded-xl bg-sky-600 px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-sky-700">
                    {{ __('mobile_coverage.btn_update') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endauth

{{-- ========= LEAFLET MAP & SCRIPT ========= --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    // Map center on Laggala center
    const defaultCenter = [7.5583, 80.7306];
    const map = L.map('coverageMap', {
        scrollWheelZoom: false,
    }).setView(defaultCenter, 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18,
    }).addTo(map);

    const signalColors = {
        excellent: '#10b981', // emerald-500
        good:      '#10b981', // emerald-500
        fair:      '#f59e0b', // amber-500
        poor:      '#f97316', // orange-500
        none:      '#e11d48', // rose-600
    };

    // Pre-parsed JSON data from controller
    const reports = @json($mapData);
    let markersLayer = L.layerGroup().addTo(map);
    const markerRegistry = {};

    function renderMarkers(filterOperator = 'all') {
        markersLayer.clearLayers();
        const bounds = [];

        reports.forEach(function (r) {
            // Apply filtering
            if (filterOperator === 'deadzone') {
                if (!r.is_dead_zone && r.signal_strength !== 'none' && r.raw_coverage_type !== 'no_signal') {
                    return;
                }
            } else if (filterOperator !== 'all') {
                if (r.network_operator !== filterOperator) {
                    return;
                }
            }

            if (!r.latitude || !r.longitude) return;

            const latLng = [r.latitude, r.longitude];
            bounds.push(latLng);

            const color = signalColors[r.signal_strength] || '#64748b';
            const isDead = r.is_dead_zone || r.signal_strength === 'none';

            const marker = L.circleMarker(latLng, {
                radius: isDead ? 11 : 9,
                fillColor: color,
                color: isDead ? '#ffe4e6' : '#ffffff',
                weight: isDead ? 3 : 2,
                opacity: 1,
                fillOpacity: 0.9,
            }).addTo(markersLayer);

            markerRegistry[r.id] = marker;

            const popupHtml = `
                <div style="min-width:210px;font-family:system-ui,-apple-system,sans-serif;padding:2px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:6px;margin-bottom:4px;">
                        <strong style="font-size:14px;color:#0f172a;font-weight:700;">${r.location_name}</strong>
                    </div>
                    ${r.destination_name ? `<div style="font-size:11px;color:#0284c7;font-weight:600;margin-bottom:6px;">📍 ${r.destination_name}</div>` : ''}
                    
                    <div style="margin-bottom:8px;">
                        <span style="display:inline-block;padding:2px 8px;border-radius:999px;font-size:10px;font-weight:700;background:${color}22;color:${color};border:1px solid ${color}44;">
                            ${r.signal_label}
                        </span>
                        <span style="display:inline-block;padding:2px 8px;border-radius:999px;font-size:10px;font-weight:700;background:#f1f5f9;color:#334155;margin-left:3px;">
                            ${r.coverage_type}
                        </span>
                    </div>

                    <table style="font-size:11px;width:100%;border-collapse:collapse;margin-bottom:6px;">
                        <tr style="border-bottom:1px solid #f1f5f9;"><td style="color:#64748b;padding:3px 0;">Carrier</td><td style="font-weight:700;color:#0f172a;text-align:right;">${r.operator_label}</td></tr>
                        <tr style="border-bottom:1px solid #f1f5f9;"><td style="color:#64748b;padding:3px 0;">Reported by</td><td style="color:#334155;text-align:right;">${r.reported_by_name}</td></tr>
                        <tr><td style="color:#64748b;padding:3px 0;">Updated</td><td style="color:#64748b;text-align:right;">${r.reported_at || ''}</td></tr>
                    </table>

                    ${r.description ? `<div style="font-size:11px;font-style:italic;color:#475569;background:#f8fafc;padding:5px 8px;border-radius:8px;border:1px solid #e2e8f0;margin-top:6px;">"${r.description}"</div>` : ''}
                </div>
            `;

            marker.bindPopup(popupHtml, { maxWidth: 280 });
        });

        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [40, 40], maxZoom: 14 });
        }
    }

    // Initialize with all markers
    renderMarkers('all');

    // Filter Buttons click handler
    const filterButtons = document.querySelectorAll('.coverage-filter-btn');
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            filterButtons.forEach(b => {
                b.classList.remove('bg-sky-600', 'text-white', 'border-sky-600');
                if (b.getAttribute('data-operator') === 'deadzone') {
                    b.classList.add('border-red-300', 'bg-red-50', 'text-red-700');
                } else {
                    b.classList.add('border-slate-200', 'bg-white', 'text-slate-700');
                }
            });

            this.classList.remove('border-slate-200', 'bg-white', 'text-slate-700', 'border-red-300', 'bg-red-50', 'text-red-700');
            this.classList.add('bg-sky-600', 'text-white', 'border-sky-600');

            const op = this.getAttribute('data-operator');
            renderMarkers(op);
        });
    });

    // Window helper to focus a report from list on map
    window.focusReportOnMap = function (lat, lng, id) {
        map.flyTo([lat, lng], 14, { duration: 1 });
        const marker = markerRegistry[id];
        if (marker) {
            setTimeout(() => marker.openPopup(), 1100);
        }
        document.getElementById('coverageMap').scrollIntoView({ behavior: 'smooth', block: 'center' });
    };

    // Click on map to place pin & prefill GPS inputs
    let clickMarker = null;
    map.on('click', function (e) {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);

        if (clickMarker) {
            map.removeLayer(clickMarker);
        }

        clickMarker = L.marker([lat, lng], {
            icon: L.icon({
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
            })
        }).addTo(map);

        clickMarker.bindPopup(`<strong>Selected Pin</strong><br>${lat}, ${lng}`).openPopup();

        const latInput = document.getElementById('covLat');
        const lngInput = document.getElementById('covLng');
        if (latInput && lngInput) {
            latInput.value = lat;
            lngInput.value = lng;
        }
    });

    // Destination select change handler: auto fill name and coordinates
    const destSelect = document.getElementById('destSelect');
    if (destSelect) {
        destSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                const nameInput = document.getElementById('covLocationName');
                const latInput = document.getElementById('covLat');
                const lngInput = document.getElementById('covLng');

                if (nameInput) {
                    nameInput.value = opt.getAttribute('data-name') || '';
                }
                if (latInput && opt.getAttribute('data-lat')) {
                    latInput.value = parseFloat(opt.getAttribute('data-lat')).toFixed(6);
                }
                if (lngInput && opt.getAttribute('data-lng')) {
                    lngInput.value = parseFloat(opt.getAttribute('data-lng')).toFixed(6);
                }
            }
        });
    }

    // Geolocation for coverage form
    const btn = document.getElementById('getCovLocation');
    if (btn) {
        btn.addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                return;
            }
            btn.textContent = '📍 Getting...';
            navigator.geolocation.getCurrentPosition(function (pos) {
                document.getElementById('covLat').value = pos.coords.latitude.toFixed(6);
                document.getElementById('covLng').value = pos.coords.longitude.toFixed(6);
                btn.textContent = '📍 Location set!';
                setTimeout(() => { btn.textContent = '📍 Use My GPS'; }, 3000);
            }, function () {
                btn.textContent = '📍 Use My GPS';
                alert('Could not retrieve location. Please click on the map or enter coordinates manually.');
            });
        });
    }
})();

// Modal handlers for community edit
function openEditModal(reportId) {
    const card = document.querySelector(`.report-card[data-id="${reportId}"]`);
    if (!card) return;

    const modal = document.getElementById('editCoverageModal');
    const form = document.getElementById('editCoverageForm');
    if (!modal || !form) return;

    form.action = `/plan/mobile-coverage/${reportId}`;

    const destSelect = document.getElementById('editDestSelect');
    if (destSelect) {
        destSelect.value = card.getAttribute('data-dest-id') || '';
    }

    document.getElementById('editLocationName').value = card.getAttribute('data-location') || '';
    document.getElementById('editOperator').value = card.getAttribute('data-operator') || 'dialog';
    document.getElementById('editCoverageType').value = card.getAttribute('data-coverage-type') || '4g';
    document.getElementById('editSignalStrength').value = card.getAttribute('data-signal') || 'good';
    document.getElementById('editDescription').value = card.getAttribute('data-description') || '';
    document.getElementById('editLat').value = card.getAttribute('data-lat') || '';
    document.getElementById('editLng').value = card.getAttribute('data-lng') || '';

    modal.classList.remove('hidden');
}

function closeEditModal() {
    const modal = document.getElementById('editCoverageModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}
</script>

@endsection
