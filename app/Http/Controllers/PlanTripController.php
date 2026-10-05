<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\MobileCoverageReport;
use App\Models\PublicTransport;
use App\Models\PublicTransportTranslation;
use App\Models\SafetyAlert;
use App\Models\WeatherLocation;
use App\Models\WeatherObservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PlanTripController extends Controller
{
    // -------------------------------------------------------------------------
    // Locale resolution (same pattern as EmergencyController)
    // -------------------------------------------------------------------------
    private function resolveLocale(Request $request): string
    {
        $lang = $request->get('lang');
        if ($lang && in_array($lang, ['si', 'en', 'ta'])) {
            session(['locale' => $lang]);
            app()->setLocale($lang);

            return $lang;
        }

        $locale = session('locale', app()->getLocale() ?: 'si');
        if (! in_array($locale, ['si', 'en', 'ta'])) {
            $locale = 'si';
        }
        app()->setLocale($locale);

        return $locale;
    }

    // =========================================================================
    // Public Transport
    // =========================================================================

    /**
     * Public Transport – listing page.
     */
    public function publicTransport(Request $request): View
    {
        $locale = $this->resolveLocale($request);

        $query = PublicTransport::where('status', '!=', 'inactive')
            ->with(['translations', 'submitter'])
            ->orderBy('is_suspended', 'asc') // active first, suspended grouped or flagged
            ->orderBy('transport_type')
            ->orderBy('route_name');

        $allRecords = $query->get();
        $routes = $allRecords->groupBy('transport_type');

        $totalCount = $allRecords->count();
        $activeCount = $allRecords->where('is_suspended', false)->count();
        $suspendedCount = $allRecords->where('is_suspended', true)->count();

        $canManageTransport = auth()->check() && (
            auth()->user()->hasAnyRole(['admin', 'super_admin', 'community_user']) ||
            auth()->user()->hasRole('admin') ||
            true // Any logged-in community contributor
        );

        return view('plan.public-transport.index', compact(
            'routes',
            'locale',
            'canManageTransport',
            'totalCount',
            'activeCount',
            'suspendedCount'
        ));
    }

    /**
     * Store a community-submitted transport update.
     */
    public function storeTransport(Request $request): RedirectResponse
    {
        $this->authorizeTransportManager();

        $validated = $request->validate([
            'route_name' => ['required', 'string', 'max:255'],
            'route_number' => ['nullable', 'string', 'max:50'],
            'transport_type' => ['required', 'in:bus,train,taxi,tuk_tuk,private_hire,other'],
            'bus_category' => ['nullable', 'string', 'max:50'],
            'from_location' => ['required', 'string', 'max:255'],
            'to_location' => ['required', 'string', 'max:255'],
            'key_stops' => ['nullable', 'string', 'max:1000'],
            'departure_time' => ['nullable', 'string', 'max:50'],
            'arrival_time' => ['nullable', 'string', 'max:50'],
            'frequency' => ['nullable', 'string', 'max:100'],
            'fare' => ['nullable', 'numeric', 'min:0'],
            'fare_note' => ['nullable', 'string', 'max:255'],
            'operator_name' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'is_suspended' => ['nullable', 'boolean'],
            'suspension_reason' => ['nullable', 'string', 'max:500'],
            'suspended_until' => ['nullable', 'string', 'max:100'],
        ]);

        $isSuspended = $request->boolean('is_suspended');

        $transport = PublicTransport::create([
            'route_name' => $validated['route_name'],
            'route_number' => $validated['route_number'] ?? null,
            'transport_type' => $validated['transport_type'],
            'bus_category' => $validated['bus_category'] ?? 'sltb',
            'from_location' => $validated['from_location'],
            'to_location' => $validated['to_location'],
            'key_stops' => $validated['key_stops'] ?? null,
            'departure_time' => $validated['departure_time'] ?? null,
            'arrival_time' => $validated['arrival_time'] ?? null,
            'frequency' => $validated['frequency'] ?? null,
            'fare' => $validated['fare'] ?? null,
            'fare_note' => $validated['fare_note'] ?? null,
            'operator_name' => $validated['operator_name'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_suspended' => $isSuspended,
            'suspension_reason' => $isSuspended ? ($validated['suspension_reason'] ?? null) : null,
            'suspended_until' => $isSuspended ? ($validated['suspended_until'] ?? null) : null,
            'submitted_by' => auth()->id(),
            'community_submitted' => true,
            'status' => 'active',
        ]);

        // Save translation for current locale
        $currentLocale = app()->getLocale() ?: 'si';
        PublicTransportTranslation::updateOrCreate(
            ['public_transport_id' => $transport->id, 'locale' => $currentLocale],
            [
                'route_name' => $validated['route_name'],
                'from_location' => $validated['from_location'],
                'to_location' => $validated['to_location'],
                'key_stops' => $validated['key_stops'] ?? null,
                'fare_note' => $validated['fare_note'] ?? null,
                'operator_name' => $validated['operator_name'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'suspension_reason' => $isSuspended ? ($validated['suspension_reason'] ?? null) : null,
            ]
        );

        return back()->with('success', __('public_transport.msg_saved_success'));
    }

    /**
     * Update an existing transport record.
     */
    public function updateTransport(Request $request, PublicTransport $publicTransport): RedirectResponse
    {
        $this->authorizeTransportManager();

        $validated = $request->validate([
            'route_name' => ['required', 'string', 'max:255'],
            'route_number' => ['nullable', 'string', 'max:50'],
            'transport_type' => ['required', 'in:bus,train,taxi,tuk_tuk,private_hire,other'],
            'bus_category' => ['nullable', 'string', 'max:50'],
            'from_location' => ['required', 'string', 'max:255'],
            'to_location' => ['required', 'string', 'max:255'],
            'key_stops' => ['nullable', 'string', 'max:1000'],
            'departure_time' => ['nullable', 'string', 'max:50'],
            'arrival_time' => ['nullable', 'string', 'max:50'],
            'frequency' => ['nullable', 'string', 'max:100'],
            'fare' => ['nullable', 'numeric', 'min:0'],
            'fare_note' => ['nullable', 'string', 'max:255'],
            'operator_name' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'is_suspended' => ['nullable', 'boolean'],
            'suspension_reason' => ['nullable', 'string', 'max:500'],
            'suspended_until' => ['nullable', 'string', 'max:100'],
        ]);

        $isSuspended = $request->boolean('is_suspended');

        $publicTransport->update([
            'route_name' => $validated['route_name'],
            'route_number' => $validated['route_number'] ?? null,
            'transport_type' => $validated['transport_type'],
            'bus_category' => $validated['bus_category'] ?? $publicTransport->bus_category,
            'from_location' => $validated['from_location'],
            'to_location' => $validated['to_location'],
            'key_stops' => $validated['key_stops'] ?? null,
            'departure_time' => $validated['departure_time'] ?? null,
            'arrival_time' => $validated['arrival_time'] ?? null,
            'frequency' => $validated['frequency'] ?? null,
            'fare' => $validated['fare'] ?? null,
            'fare_note' => $validated['fare_note'] ?? null,
            'operator_name' => $validated['operator_name'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_suspended' => $isSuspended,
            'suspension_reason' => $isSuspended ? ($validated['suspension_reason'] ?? null) : null,
            'suspended_until' => $isSuspended ? ($validated['suspended_until'] ?? null) : null,
        ]);

        // Update translation for current locale
        $currentLocale = app()->getLocale() ?: 'si';
        PublicTransportTranslation::updateOrCreate(
            ['public_transport_id' => $publicTransport->id, 'locale' => $currentLocale],
            [
                'route_name' => $validated['route_name'],
                'from_location' => $validated['from_location'],
                'to_location' => $validated['to_location'],
                'key_stops' => $validated['key_stops'] ?? null,
                'fare_note' => $validated['fare_note'] ?? null,
                'operator_name' => $validated['operator_name'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'suspension_reason' => $isSuspended ? ($validated['suspension_reason'] ?? null) : null,
            ]
        );

        return back()->with('success', __('public_transport.msg_updated_success'));
    }

    /**
     * Quick status update (operational vs suspended) for transport route.
     */
    public function updateTransportStatus(Request $request, PublicTransport $publicTransport): RedirectResponse
    {
        $this->authorizeTransportManager();

        $validated = $request->validate([
            'is_suspended' => ['required', 'boolean'],
            'suspension_reason' => ['nullable', 'string', 'max:500'],
            'suspended_until' => ['nullable', 'string', 'max:100'],
        ]);

        $isSuspended = $request->boolean('is_suspended');

        $publicTransport->update([
            'is_suspended' => $isSuspended,
            'suspension_reason' => $isSuspended ? ($validated['suspension_reason'] ?? null) : null,
            'suspended_until' => $isSuspended ? ($validated['suspended_until'] ?? null) : null,
        ]);

        if ($isSuspended && ! empty($validated['suspension_reason'])) {
            $currentLocale = app()->getLocale() ?: 'si';
            PublicTransportTranslation::updateOrCreate(
                ['public_transport_id' => $publicTransport->id, 'locale' => $currentLocale],
                ['suspension_reason' => $validated['suspension_reason']]
            );
        }

        return back()->with('success', __('public_transport.msg_status_updated'));
    }

    /**
     * Authorize that the user is an admin, super_admin, or community_user.
     */
    private function authorizeTransportManager(): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, __('public_transport.msg_error_unauthorized'));
        }

        if (! $user->hasAnyRole(['admin', 'super_admin', 'community_user']) && ! $user->hasRole('admin')) {
            abort(403, __('public_transport.msg_error_unauthorized'));
        }
    }

    // =========================================================================
    // Weather & Safety
    // =========================================================================

    /**
     * Weather & Safety – combined page with Open-Meteo forecast + local observations.
     */
    /**
     * Weather & Safety – combined page with Open-Meteo forecast + local observations + safety alerts.
     */
    public function weatherSafety(Request $request): View
    {
        $locale = $this->resolveLocale($request);

        // Active weather locations configured by admin/super_admin
        $locations = WeatherLocation::where('is_active', true)
            ->with('translations')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Prepare locations JSON data for frontend Open-Meteo selector
        $locationsData = $locations->map(function (WeatherLocation $loc) use ($locale) {
            return [
                'id' => $loc->id,
                'name' => $loc->translationFor($locale)?->name ?? $loc->name,
                'latitude' => (float) $loc->latitude,
                'longitude' => (float) $loc->longitude,
                'elevation_m' => $loc->elevation_m ?? 350,
                'description' => $loc->translationFor($locale)?->description ?? $loc->description,
            ];
        });

        // Real ground observations: ONLY past 48 hours ("පැය 48 කට වඩා පරණ කාලගුණික දත්ත දර්ශනීය නොවිය යුතුයි")
        // and immediately visible ("community user කරන යාවත් කාලින කිරීම් අනුමැතියකින් තොරව වෙබ් යෙදවුමේ පෙන්විය යුතු")
        $cutoff = now()->subHours(48);
        $observations = WeatherObservation::where('observed_at', '>=', $cutoff)
            ->where('status', 'approved')
            ->with(['user', 'translations', 'weatherLocation.translations'])
            ->orderBy('observed_at', 'desc')
            ->get();

        // Safety & natural disaster alerts: active alerts
        $safetyAlerts = SafetyAlert::where('is_active', true)
            ->with(['reporter', 'translations', 'weatherLocation.translations'])
            ->orderBy('reported_at', 'desc')
            ->get();

        return view('plan.weather-safety.index', compact(
            'locations',
            'locationsData',
            'observations',
            'safetyAlerts',
            'locale'
        ));
    }

    /**
     * Store a local weather observation submitted by an authenticated community user.
     */
    public function storeWeatherObservation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'weather_location_id' => ['nullable', 'exists:weather_locations,id'],
            'location_name' => ['required_without:weather_location_id', 'nullable', 'string', 'max:255'],
            'condition' => ['required', 'in:sunny,partly_cloudy,cloudy,rain,heavy_rain,mist,fog,windy'],
            'temperature' => ['nullable', 'numeric', 'between:-10,50'],
            'rainfall' => ['nullable', 'numeric', 'min:0', 'max:500'],
            'wind_condition' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $weatherLocation = ! empty($validated['weather_location_id'])
            ? WeatherLocation::find($validated['weather_location_id'])
            : null;

        $locationName = $validated['location_name'] ?? $weatherLocation?->name ?? 'Laggala';
        $latitude = $validated['latitude'] ?? $weatherLocation?->latitude ?? 7.5583;
        $longitude = $validated['longitude'] ?? $weatherLocation?->longitude ?? 80.7306;

        // Ground observations submitted by community user appear immediately without prior approval
        $observation = WeatherObservation::create([
            'user_id' => $request->user()->id,
            'weather_location_id' => $weatherLocation?->id,
            'location_name' => $locationName,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'condition' => $validated['condition'],
            'temperature' => $validated['temperature'] ?? null,
            'rainfall' => $validated['rainfall'] ?? null,
            'wind_condition' => $validated['wind_condition'] ?? null,
            'description' => $validated['description'] ?? null,
            'observed_at' => now(),
            'status' => 'approved',
        ]);

        $locale = $this->resolveLocale($request);
        $observation->translations()->create([
            'locale' => $locale,
            'location_name' => $locationName,
            'wind_condition' => $validated['wind_condition'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', __('weather_safety.msg_obs_saved'));
    }

    /**
     * Store a natural hazard / safety alert submitted by a community user or admin.
     */
    public function storeSafetyAlert(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'hazard_type' => ['required', 'in:landslide,rockfall,flash_flood,high_wind,dense_mist,road_closure,other'],
            'severity' => ['required', 'in:advisory,warning,danger'],
            'weather_location_id' => ['nullable', 'exists:weather_locations,id'],
            'location_name' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'safety_instructions' => ['nullable', 'string', 'max:1000'],
        ]);

        $weatherLocation = ! empty($validated['weather_location_id'])
            ? WeatherLocation::find($validated['weather_location_id'])
            : null;

        $locationName = $validated['location_name'] ?? $weatherLocation?->name ?? 'Laggala';

        // Community user alerts appear immediately without prior approval
        $alert = SafetyAlert::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'hazard_type' => $validated['hazard_type'],
            'severity' => $validated['severity'],
            'weather_location_id' => $weatherLocation?->id,
            'location_name' => $locationName,
            'description' => $validated['description'],
            'safety_instructions' => $validated['safety_instructions'] ?? null,
            'is_active' => true,
            'reported_at' => now(),
            'expires_at' => now()->addDays(3),
        ]);

        $locale = $this->resolveLocale($request);
        $alert->translations()->create([
            'locale' => $locale,
            'title' => $validated['title'],
            'location_name' => $locationName,
            'description' => $validated['description'],
            'safety_instructions' => $validated['safety_instructions'] ?? null,
        ]);

        return back()->with('success', __('weather_safety.msg_alert_saved'));
    }

    /**
     * Store a new regional weather station location (Admin & Super Admin only).
     */
    public function storeWeatherLocation(Request $request): RedirectResponse
    {
        if (! auth()->user()?->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, __('weather_safety.msg_unauthorized'));
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'elevation_m' => ['nullable', 'integer', 'min:0', 'max:5000'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (WeatherLocation::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $location = WeatherLocation::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'elevation_m' => $validated['elevation_m'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => true,
            'created_by' => auth()->id(),
        ]);

        $locale = $this->resolveLocale($request);
        $location->translations()->create([
            'locale' => $locale,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', __('weather_safety.msg_location_saved'));
    }

    /**
     * Delete a weather observation (Admin & Super Admin only).
     */
    public function destroyWeatherObservation(WeatherObservation $weatherObservation): RedirectResponse
    {
        if (! auth()->user()?->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, __('weather_safety.msg_unauthorized'));
        }

        $weatherObservation->translations()->delete();
        $weatherObservation->delete();

        return back()->with('success', __('weather_safety.msg_item_deleted'));
    }

    /**
     * Delete a safety alert (Admin & Super Admin only).
     */
    public function destroySafetyAlert(SafetyAlert $safetyAlert): RedirectResponse
    {
        if (! auth()->user()?->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, __('weather_safety.msg_unauthorized'));
        }

        $safetyAlert->translations()->delete();
        $safetyAlert->delete();

        return back()->with('success', __('weather_safety.msg_item_deleted'));
    }

    // =========================================================================
    // Mobile Coverage
    // =========================================================================

    /**
     * Mobile Coverage – listing & map page.
     */
    public function mobileCoverage(Request $request): View
    {
        $locale = $this->resolveLocale($request);

        $reports = MobileCoverageReport::where('status', 'approved')
            ->with(['reporter', 'translations', 'destination.translations'])
            ->orderBy('reported_at', 'desc')
            ->get();

        $destinations = Destination::where('status', 'published')
            ->with('translations')
            ->orderBy('sort_order')
            ->get();

        $currentUserId = auth()->id();
        $isManager = auth()->user()?->hasAnyRole(['admin', 'super_admin', 'community_user']) ?? false;

        $mapData = $reports
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->map(function ($r) use ($locale, $currentUserId, $isManager) {
                $destName = $r->destination?->translationFor($locale)?->name;
                $locName = $r->translationFor($locale)?->location_name ?? $r->location_name;

                return [
                    'id' => $r->id,
                    'destination_id' => $r->destination_id,
                    'destination_name' => $destName,
                    'location_name' => $locName,
                    'latitude' => (float) $r->latitude,
                    'longitude' => (float) $r->longitude,
                    'network_operator' => $r->network_operator,
                    'operator_label' => match ($r->network_operator) {
                        'dialog' => __('mobile_coverage.op_dialog'),
                        'mobitel' => __('mobile_coverage.op_mobitel'),
                        'hutch' => __('mobile_coverage.op_hutch'),
                        'airtel' => __('mobile_coverage.op_airtel'),
                        'multiple' => __('mobile_coverage.op_multiple'),
                        default => __('mobile_coverage.op_other'),
                    },
                    'coverage_type' => match ($r->coverage_type) {
                        '5g' => __('mobile_coverage.cov_5g'),
                        '4g' => __('mobile_coverage.cov_4g'),
                        '3g' => __('mobile_coverage.cov_3g'),
                        '2g' => __('mobile_coverage.cov_2g'),
                        default => __('mobile_coverage.cov_no_signal'),
                    },
                    'raw_coverage_type' => $r->coverage_type,
                    'signal_strength' => $r->signal_strength,
                    'signal_label' => match ($r->signal_strength) {
                        'excellent' => __('mobile_coverage.signal_excellent'),
                        'good' => __('mobile_coverage.signal_good'),
                        'fair' => __('mobile_coverage.signal_fair'),
                        'poor' => __('mobile_coverage.signal_poor'),
                        default => __('mobile_coverage.signal_none'),
                    },
                    'is_dead_zone' => $r->signal_strength === 'none' || $r->coverage_type === 'no_signal',
                    'description' => $r->translationFor($locale)?->description ?? $r->description,
                    'reported_by_name' => $r->reporter?->name ?? __('mobile_coverage.community_badge'),
                    'reported_at' => $r->reported_at?->diffForHumans(),
                    'can_edit' => $currentUserId && ($isManager || $r->reported_by === $currentUserId),
                ];
            })
            ->values();

        return view('plan.mobile-coverage.index', compact('reports', 'mapData', 'destinations', 'locale'));
    }

    /**
     * Store a community mobile coverage report.
     * Reports submitted by community users and admins appear immediately without prior approval.
     */
    public function storeMobileCoverage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'destination_id' => ['nullable', 'exists:destinations,id'],
            'location_name' => ['required', 'string', 'max:255'],
            'network_operator' => ['required', 'in:dialog,mobitel,hutch,airtel,multiple,other'],
            'coverage_type' => ['required', 'in:2g,3g,4g,5g,no_signal'],
            'signal_strength' => ['required', 'in:excellent,good,fair,poor,none'],
            'description' => ['nullable', 'string', 'max:1000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        if (! empty($validated['destination_id']) && (empty($validated['latitude']) || empty($validated['longitude']))) {
            $dest = Destination::find($validated['destination_id']);
            if ($dest) {
                $validated['latitude'] = $validated['latitude'] ?: $dest->latitude;
                $validated['longitude'] = $validated['longitude'] ?: $dest->longitude;
            }
        }

        // Community user updates appear immediately without prior approval
        $report = MobileCoverageReport::create([
            ...$validated,
            'reported_by' => auth()->id(),
            'reported_at' => now(),
            'status' => 'approved',
        ]);

        $locale = $this->resolveLocale($request);
        $report->translations()->updateOrCreate(
            ['locale' => $locale],
            [
                'location_name' => $validated['location_name'],
                'description' => $validated['description'] ?? null,
            ]
        );

        return back()->with('success', __('mobile_coverage.msg_created'));
    }

    /**
     * Update an existing mobile coverage report (by author, community user, or admin).
     */
    public function updateMobileCoverage(Request $request, MobileCoverageReport $mobileCoverageReport): RedirectResponse
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, __('mobile_coverage.msg_unauthorized'));
        }

        if (! $user->hasAnyRole(['admin', 'super_admin', 'community_user']) && $mobileCoverageReport->reported_by !== $user->id) {
            abort(403, __('mobile_coverage.msg_unauthorized'));
        }

        $validated = $request->validate([
            'destination_id' => ['nullable', 'exists:destinations,id'],
            'location_name' => ['required', 'string', 'max:255'],
            'network_operator' => ['required', 'in:dialog,mobitel,hutch,airtel,multiple,other'],
            'coverage_type' => ['required', 'in:2g,3g,4g,5g,no_signal'],
            'signal_strength' => ['required', 'in:excellent,good,fair,poor,none'],
            'description' => ['nullable', 'string', 'max:1000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        if (! empty($validated['destination_id']) && (empty($validated['latitude']) || empty($validated['longitude']))) {
            $dest = Destination::find($validated['destination_id']);
            if ($dest) {
                $validated['latitude'] = $validated['latitude'] ?: $dest->latitude;
                $validated['longitude'] = $validated['longitude'] ?: $dest->longitude;
            }
        }

        $mobileCoverageReport->update($validated);

        $locale = $this->resolveLocale($request);
        $mobileCoverageReport->translations()->updateOrCreate(
            ['locale' => $locale],
            [
                'location_name' => $validated['location_name'],
                'description' => $validated['description'] ?? null,
            ]
        );

        return back()->with('success', __('mobile_coverage.msg_updated'));
    }

    /**
     * Delete a mobile coverage report (by author, community user, or admin).
     */
    public function destroyMobileCoverage(Request $request, MobileCoverageReport $mobileCoverageReport): RedirectResponse
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, __('mobile_coverage.msg_unauthorized'));
        }

        if (! $user->hasAnyRole(['admin', 'super_admin', 'community_user']) && $mobileCoverageReport->reported_by !== $user->id) {
            abort(403, __('mobile_coverage.msg_unauthorized'));
        }

        $mobileCoverageReport->delete();

        return back()->with('success', __('mobile_coverage.msg_deleted'));
    }
}
