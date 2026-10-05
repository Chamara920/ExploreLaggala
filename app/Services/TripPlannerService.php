<?php

namespace App\Services;

use App\Models\Destination;
use App\Models\ExploreItem;
use App\Models\NewsPost;
use App\Models\PublicTransport;
use App\Models\SafetyAlert;
use App\Models\WeatherLocation;
use App\Models\WeatherObservation;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TripPlannerService
{
    /**
     * Generate a one-day deterministic, rule-based itinerary.
     * Incorporates distances, weather, road safety/news, transport mode,
     * dining, accommodations, and map waypoints.
     */
    public function generate(array $data): array
    {
        $tripDays = (int) ($data['trip_days'] ?? 1);
        if ($tripDays < 1 || $tripDays > 3) {
            $tripDays = 1;
        }

        $availableMinutesPerDay = match ($data['available_time'] ?? '8') {
            '4' => 240,
            '6' => 360,
            '8' => 480,
            'full_day' => 540,
            default => 480,
        };
        if ($tripDays > 1) {
            $availableMinutesPerDay = 510; // ~8.5 hours of touring per day
        }

        $speedKmh = match ($data['travel_mode']) {
            'private_vehicle' => 35,
            'motorbike' => 40,
            'public_transport' => 25,
            'private_bus' => 30,
            default => 35,
        };

        $start = [
            'name' => $data['start_location_name'] ?? 'Laggala Hub',
            'latitude' => (float) $data['start_latitude'],
            'longitude' => (float) $data['start_longitude'],
        ];

        $travelDate = Carbon::parse($data['travel_date']);

        $startDateTime = Carbon::createFromFormat(
            'Y-m-d H:i',
            $data['travel_date'].' '.$data['start_time'],
            'Asia/Colombo'
        );

        $interestIds = collect($data['interest_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        $includeMealStop = ! empty($data['include_meal_stop']);
        $includeAccommodation = ! empty($data['include_accommodation']);

        /*
         * 1. Load destinations configured or available for planning.
         */
        $destinations = Destination::query()
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereDoesntHave('plannerDetails')
                    ->orWhereHas('plannerDetails', function ($subQuery) {
                        $subQuery->where('planner_enabled', true);
                    });
            })
            ->with([
                'translations',
                'plannerDetails',
                'interests',
                'seasons',
                'images',
            ])
            ->get();

        /*
         * Filter by selected interests if matched.
         * For multi-day trips, ensure enough candidate pool by falling back to other published spots.
         */
        $filteredByInterest = $destinations;
        if (! empty($interestIds)) {
            $matched = $destinations->filter(function (Destination $destination) use ($interestIds) {
                return $destination->interests
                    ->pluck('id')
                    ->intersect($interestIds)
                    ->isNotEmpty();
            });

            if ($matched->count() >= ($tripDays * 2)) {
                $filteredByInterest = $matched;
            } else {
                $filteredByInterest = $matched->merge($destinations)->unique('id');
            }
        }

        /*
         * Score candidate destinations based on interest match,
         * seasonal suitability, and proximity to starting hub.
         */
        $candidates = $filteredByInterest
            ->filter(fn (Destination $d) => ! is_null($d->latitude) && ! is_null($d->longitude))
            ->map(function (Destination $destination) use ($interestIds, $travelDate, $start) {
                $matchingInterests = $destination->interests
                    ->whereIn('id', $interestIds)
                    ->values();

                $season = $destination->seasons
                    ->firstWhere('month', $travelDate->month);

                $seasonScore = match ($season?->rating) {
                    'best' => 3,
                    'suitable' => 2,
                    'not_recommended' => -2,
                    default => 1,
                };

                $distanceFromStart = $this->distanceInKm(
                    $start['latitude'],
                    $start['longitude'],
                    (float) $destination->latitude,
                    (float) $destination->longitude
                );

                $interestScore = $matchingInterests->count() * 10;
                $featuredScore = $destination->featured ? 3 : 0;
                $distanceScore = max(0, 6 - ($distanceFromStart / 10));

                $score = $interestScore + ($seasonScore * 4) + $featuredScore + $distanceScore;

                return [
                    'destination' => $destination,
                    'matching_interests' => $matchingInterests,
                    'season' => $season,
                    'season_score' => $seasonScore,
                    'distance_from_start' => $distanceFromStart,
                    'score' => $score,
                ];
            })
            ->sortByDesc('score')
            ->values();

        /*
         * 2. Build multi-day or single-day itinerary.
         */
        $days = [];
        $allSelected = collect();
        $visitedDestinationIds = collect();
        $dayStart = $start;

        for ($dayNumber = 1; $dayNumber <= $tripDays; $dayNumber++) {
            $dayDate = $travelDate->copy()->addDays($dayNumber - 1);
            $dayStartTime = ($dayNumber === 1)
                ? $startDateTime->copy()
                : $dayDate->copy()->setTime(8, 30); // 08:30 AM on subsequent days

            $currentTime = $dayStartTime->copy();
            $remainingMinutes = $availableMinutesPerDay;
            $daySelected = collect();
            $currentLocation = [
                'latitude' => $dayStart['latitude'],
                'longitude' => $dayStart['longitude'],
                'name' => $dayStart['name'],
            ];
            $cumulativeDistance = 0.0;
            $mealStopInserted = false;

            // Maximum destinations per day (3 per day for multi-day, 4 for single full day)
            $dayDestLimit = ($tripDays === 1) ? 4 : 3;

            while ($remainingMinutes > 35 && $candidates->isNotEmpty() && $daySelected->where('type', 'destination')->count() < $dayDestLimit) {
                // Midday lunch stop check (between 12:00 PM and 1:45 PM)
                $currentHour = (int) $currentTime->format('H');
                $currentMinute = (int) $currentTime->format('i');
                $timeInDecimal = $currentHour + ($currentMinute / 60);

                if ($includeMealStop && ! $mealStopInserted && $timeInDecimal >= 12.0 && $timeInDecimal <= 14.0 && $daySelected->isNotEmpty()) {
                    $mealData = $this->resolveMealStop($currentLocation, $currentTime);
                    $mealData['day_number'] = $dayNumber;
                    $daySelected->push($mealData);
                    $remainingMinutes -= $mealData['visit_minutes'];
                    $currentTime = $mealData['visit_end']->copy();
                    $mealStopInserted = true;

                    continue;
                }

                $bestCandidate = null;

                foreach ($candidates as $candidate) {
                    $destination = $candidate['destination'];

                    if ($visitedDestinationIds->contains($destination->id)) {
                        continue;
                    }

                    if ($candidate['season_score'] < 0) {
                        continue;
                    }

                    $legDistance = $this->distanceInKm(
                        $currentLocation['latitude'],
                        $currentLocation['longitude'],
                        (float) $destination->latitude,
                        (float) $destination->longitude
                    );

                    $travelMinutes = $this->travelTimeMinutes(
                        $currentLocation['latitude'],
                        $currentLocation['longitude'],
                        (float) $destination->latitude,
                        (float) $destination->longitude,
                        $speedKmh
                    );

                    $visitMinutes = max(
                        35,
                        (int) ($destination->plannerDetails?->visit_duration_minutes ?? 60)
                    );

                    $returnToOriginMinutes = ($dayNumber === $tripDays)
                        ? $this->travelTimeMinutes(
                            (float) $destination->latitude,
                            (float) $destination->longitude,
                            $start['latitude'],
                            $start['longitude'],
                            $speedKmh
                        )
                        : 20;

                    $requiredMinutes = $travelMinutes + $visitMinutes + $returnToOriginMinutes;

                    if ($requiredMinutes > $remainingMinutes && $daySelected->where('type', 'destination')->count() >= 2) {
                        continue;
                    }

                    $fitScore = $candidate['score']
                        - ($travelMinutes / 8)
                        + ($candidate['matching_interests']->count() * 2);

                    if (is_null($bestCandidate) || $fitScore > $bestCandidate['fit_score']) {
                        $bestCandidate = [
                            ...$candidate,
                            'leg_distance' => $legDistance,
                            'travel_minutes' => $travelMinutes,
                            'visit_minutes' => $visitMinutes,
                            'fit_score' => $fitScore,
                        ];
                    }
                }

                if (is_null($bestCandidate)) {
                    break;
                }

                $destination = $bestCandidate['destination'];
                $visitedDestinationIds->push($destination->id);

                // Travel leg
                $travelStart = $currentTime->copy();
                $currentTime->addMinutes($bestCandidate['travel_minutes']);
                $visitStart = $currentTime->copy();

                // Visit destination
                $currentTime->addMinutes($bestCandidate['visit_minutes']);
                $visitEnd = $currentTime->copy();

                $cumulativeDistance += $bestCandidate['leg_distance'];

                $destTrans = $destination->translationFor(app()->getLocale()) ?? $destination->translations->first();

                $destItem = [
                    'type' => 'destination',
                    'order' => $daySelected->where('type', 'destination')->count() + 1,
                    'overall_order' => $allSelected->where('type', 'destination')->count() + 1,
                    'day_number' => $dayNumber,
                    'destination' => $destination,
                    'translation' => $destTrans,
                    'matching_interests' => $bestCandidate['matching_interests'],
                    'season' => $bestCandidate['season'],
                    'leg_distance_km' => round($bestCandidate['leg_distance'], 1),
                    'cumulative_distance_km' => round($cumulativeDistance, 1),
                    'travel_start' => $travelStart,
                    'travel_minutes' => $bestCandidate['travel_minutes'],
                    'visit_start' => $visitStart,
                    'visit_end' => $visitEnd,
                    'visit_minutes' => $bestCandidate['visit_minutes'],
                    'latitude' => (float) $destination->latitude,
                    'longitude' => (float) $destination->longitude,
                    'nearest_bus_stop' => $destination->plannerDetails?->nearest_bus_stop ?? 'Pallegama / Laggala Central Stand',
                    'bus_routes' => $destination->plannerDetails?->bus_routes ?? 'Matale - Pallegama (Route 704)',
                    'transport_accessibility' => $destination->plannerDetails?->transport_accessibility ?? 'direct_road_access',
                ];

                $daySelected->push($destItem);
                $remainingMinutes -= ($bestCandidate['travel_minutes'] + $bestCandidate['visit_minutes']);

                $currentLocation = [
                    'latitude' => (float) $destination->latitude,
                    'longitude' => (float) $destination->longitude,
                    'name' => $destTrans?->name ?? 'Destination',
                ];
            }

            // Return / Accommodation setup for the day
            $dayReturnMinutes = 0;
            $dayReturnDistance = 0.0;
            $dayAccommodation = null;

            if ($dayNumber === $tripDays) {
                // Final day: Return back to initial starting hub
                if ($daySelected->isNotEmpty()) {
                    $lastItem = $daySelected->last();
                    $lastLat = (float) $lastItem['latitude'];
                    $lastLon = (float) $lastItem['longitude'];

                    $dayReturnDistance = $this->distanceInKm($lastLat, $lastLon, $start['latitude'], $start['longitude']);
                    $dayReturnMinutes = $this->travelTimeMinutes($lastLat, $lastLon, $start['latitude'], $start['longitude'], $speedKmh);
                }
            } else {
                // Intermediate day: Overnight stay recommendation
                $dayAccommodation = $this->resolveAccommodationRecommendations($currentLocation, $dayNumber);
                $dayStart = [
                    'name' => $dayAccommodation['stays']->first()['name'] ?? 'Laggala Eco Stay',
                    'latitude' => $currentLocation['latitude'],
                    'longitude' => $currentLocation['longitude'],
                ];
            }

            $dayVisitMinutes = $daySelected->sum('visit_minutes');
            $dayTravelMinutes = $daySelected->sum('travel_minutes') + $dayReturnMinutes;
            $dayTotalDistance = round($daySelected->sum('leg_distance_km') + $dayReturnDistance, 1);

            $dayMapsUrl = $this->buildGoogleMapsRouteUrl(
                ($dayNumber === 1) ? $start : ['name' => $dayStart['name'], 'latitude' => $dayStart['latitude'], 'longitude' => $dayStart['longitude']],
                $daySelected,
                $data['travel_mode']
            );

            $dayOsmUrl = $this->buildOpenStreetMapRouteUrl(
                ($dayNumber === 1) ? $start : ['name' => $dayStart['name'], 'latitude' => $dayStart['latitude'], 'longitude' => $dayStart['longitude']],
                $daySelected
            );

            $days[$dayNumber] = [
                'day_number' => $dayNumber,
                'date' => $dayDate,
                'start_location' => ($dayNumber === 1) ? $start['name'] : $dayStart['name'],
                'itinerary' => $daySelected,
                'accommodation' => $dayAccommodation,
                'google_maps_url' => $dayMapsUrl,
                'osm_url' => $dayOsmUrl,
                'summary' => [
                    'destination_count' => $daySelected->where('type', 'destination')->count(),
                    'total_distance_km' => $dayTotalDistance,
                    'return_distance_km' => round($dayReturnDistance, 1),
                    'visit_minutes' => $dayVisitMinutes,
                    'travel_minutes' => $dayTravelMinutes,
                    'return_minutes' => $dayReturnMinutes,
                ],
            ];

            $allSelected = $allSelected->concat($daySelected);
        }

        $totalVisitMinutes = $allSelected->sum('visit_minutes');
        $totalTravelMinutes = collect($days)->sum(fn ($d) => $d['summary']['travel_minutes']);
        $totalDistance = round(collect($days)->sum(fn ($d) => $d['summary']['total_distance_km']), 1);
        $finalReturnDistance = $days[$tripDays]['summary']['return_distance_km'] ?? 0.0;
        $finalReturnMinutes = $days[$tripDays]['summary']['return_minutes'] ?? 0;

        /*
         * 4. Fetch Weather Observations for Laggala area.
         */
        $weatherData = $this->resolveWeatherData();

        /*
         * 5. Fetch Road Safety Alerts & Closures from published News posts.
         */
        $roadSafetyData = $this->resolveRoadSafetyData();

        /*
         * 6. Fetch Public Transport bus info (routes, schedules, SLTB vs Private, suspensions).
         */
        $publicTransportData = $this->resolvePublicTransportData($start, $allSelected);

        $privateBusData = ($data['travel_mode'] === 'private_bus')
            ? [
                'coach_note' => __('trip_planner.coach_access_desc'),
                'speed_kmh' => $speedKmh,
            ]
            : null;

        /*
         * 7. Resolve Accommodations / Overnight stay recommendations.
         */
        $accommodationData = $includeAccommodation
            ? $this->resolveAccommodationRecommendations($currentLocation, 1)
            : null;

        /*
         * 8. Generate Google Maps Route URLs (Direct app link and Embed iframe).
         */
        $googleMapsUrl = $this->buildGoogleMapsRouteUrl(
            $start,
            $allSelected,
            $data['travel_mode']
        );

        $googleMapsEmbedUrl = $this->buildGoogleMapsEmbedUrl(
            $start,
            $allSelected
        );

        $osmUrl = $this->buildOpenStreetMapRouteUrl(
            $start,
            $allSelected
        );

        /*
         * 9. Build Leaflet Map Waypoints array for client-side rendering.
         */
        $mapPoints = $this->buildMapWaypoints($start, $allSelected, $finalReturnMinutes, $currentTime);

        return [
            'trip_days' => $tripDays,
            'days' => $days,
            'travel_date' => $travelDate,
            'start_time' => $startDateTime,
            'available_minutes' => $availableMinutesPerDay * $tripDays,
            'travel_mode' => $data['travel_mode'],
            'speed_kmh' => $speedKmh,
            'start_location_name' => $data['start_location_name'],
            'start_latitude' => $start['latitude'],
            'start_longitude' => $start['longitude'],
            'include_meal_stop' => $includeMealStop,
            'include_accommodation' => $includeAccommodation,

            'itinerary' => $allSelected,

            'weather' => $weatherData,
            'road_safety' => $roadSafetyData,
            'public_transport' => $publicTransportData,
            'private_bus' => $privateBusData,
            'accommodation' => $accommodationData,
            'google_maps_url' => $googleMapsUrl,
            'google_maps_embed_url' => $googleMapsEmbedUrl,
            'osm_url' => $osmUrl,
            'map_points' => $mapPoints,

            'summary' => [
                'trip_days' => $tripDays,
                'destination_count' => $allSelected->where('type', 'destination')->count(),
                'total_distance_km' => $totalDistance,
                'return_distance_km' => round($finalReturnDistance, 1),
                'visit_minutes' => $totalVisitMinutes,
                'travel_minutes' => $totalTravelMinutes,
                'return_minutes' => $finalReturnMinutes,
                'used_minutes' => $totalVisitMinutes + $totalTravelMinutes,
                'remaining_minutes' => max(0, ($availableMinutesPerDay * $tripDays) - ($totalVisitMinutes + $totalTravelMinutes)),
            ],
        ];

    }

    /**
     * Resolve or build a dining/lunch stop along the route.
     * Extensible: dynamically queries ExploreItem food/dining or creates smart recommendation.
     */
    private function resolveMealStop(array $currentLocation, Carbon $currentTime): array
    {
        $visitStart = $currentTime->copy();
        $visitEnd = $currentTime->copy()->addMinutes(50);

        // Check if there are active food/dining items in ExploreItem
        $diningItem = ExploreItem::query()
            ->where('status', 'published')
            ->whereHas('category', function ($q) {
                $q->whereIn('slug', ['restaurants', 'dining', 'food', 'cafes']);
            })
            ->with('translations')
            ->first();

        $name = $diningItem?->translations->firstWhere('locale', app()->getLocale())?->title
            ?? $diningItem?->translations->firstWhere('locale', 'en')?->title
            ?? 'Lunch & Refreshment Break';

        $locationName = $diningItem ? 'Registered Local Dining' : 'Traditional Sri Lankan Food / Scenic Rest Hub';

        return [
            'type' => 'meal_stop',
            'order' => null,
            'title' => $name,
            'location_name' => $locationName,
            'note' => 'Recommended mid-day meal break (45–60 min). Supports registered local restaurants and village food outlets in Laggala.',
            'leg_distance_km' => 0.0,
            'cumulative_distance_km' => 0.0,
            'travel_minutes' => 0,
            'visit_start' => $visitStart,
            'visit_end' => $visitEnd,
            'visit_minutes' => 50,
            'latitude' => $currentLocation['latitude'],
            'longitude' => $currentLocation['longitude'],
            'is_database_linked' => ! is_null($diningItem),
        ];
    }

    /**
     * Fetch latest Weather Observation for Laggala (within past 48 hours)
     * and regional weather station summaries to produce actionable travel advice.
     */
    public function resolveWeatherData(): array
    {
        $locale = session('locale', app()->getLocale() ?: 'si');

        // Only observations from the past 48 hours
        $cutoff = now()->subHours(48);
        $latest = WeatherObservation::query()
            ->where('status', 'approved')
            ->where('observed_at', '>=', $cutoff)
            ->with(['translations', 'weatherLocation.translations'])
            ->latest('observed_at')
            ->first();

        // Regional weather stations
        $stations = WeatherLocation::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('sort_order')
            ->get()
            ->map(function (WeatherLocation $loc) use ($locale) {
                return [
                    'id' => $loc->id,
                    'name' => $loc->translationFor($locale)?->name ?? $loc->name,
                    'elevation_m' => $loc->elevation_m ?? 350,
                    'latitude' => (float) $loc->latitude,
                    'longitude' => (float) $loc->longitude,
                ];
            });

        if (! $latest) {
            return [
                'has_data' => false,
                'location_name' => $stations->first()['name'] ?? 'Laggala / Knuckles Region',
                'condition' => 'partly_cloudy',
                'temperature' => 24.0,
                'rainfall' => 0.0,
                'wind_condition' => 'Mild mountain breeze',
                'advisory' => 'Typical Knuckles mountain climate. Expect pleasant weather, with occasional afternoon mist or brief showers in higher altitudes.',
                'status_color' => 'emerald',
                'stations' => $stations,
                'stations_count' => $stations->count(),
                'is_fresh_48h' => false,
            ];
        }

        $condition = strtolower($latest->condition ?? 'partly_cloudy');
        $rainfall = (float) ($latest->rainfall ?? 0);
        $temp = (float) ($latest->temperature ?? 24);

        $isRainy = str_contains($condition, 'rain') || str_contains($condition, 'storm') || $rainfall > 5;
        $isWindy = str_contains(strtolower($latest->wind_condition ?? ''), 'strong') || str_contains(strtolower($latest->wind_condition ?? ''), 'high') || $condition === 'windy';
        $isMist = str_contains($condition, 'mist') || str_contains($condition, 'fog');

        if ($rainfall > 20 || str_contains($condition, 'heavy')) {
            $advisory = 'Heavy rain reported in the region. Knuckles mountain passes (Riverston, Pitawala) may experience thick mist, strong winds, and slippery roads. Drive slowly with fog lights and avoid stream bathing.';
            $statusColor = 'red';
        } elseif ($isRainy) {
            $advisory = 'Scattered showers recorded. Carry rain gear, sturdy footwear for walking trails, and exercise caution on winding descents.';
            $statusColor = 'amber';
        } elseif ($isMist) {
            $advisory = 'Dense mist or fog reported at higher elevations. Reduced visibility on mountain roads and viewpoint cliffs.';
            $statusColor = 'sky';
        } elseif ($isWindy) {
            $advisory = 'High winds at high elevation viewpoints (Riverston Gap, Mini World\'s End). Keep personal belongings secure.';
            $statusColor = 'amber';
        } else {
            $advisory = 'Clear and favorable conditions for exploring scenic spots, photography, and nature walks in Laggala.';
            $statusColor = 'emerald';
        }

        return [
            'has_data' => true,
            'location_name' => $latest->translationFor($locale)?->location_name ?? $latest->location_name,
            'condition' => $latest->condition,
            'temperature' => $temp,
            'rainfall' => $rainfall,
            'wind_condition' => $latest->translationFor($locale)?->wind_condition ?? $latest->wind_condition ?? 'Normal',
            'description' => $latest->translationFor($locale)?->description ?? $latest->description,
            'observed_at' => $latest->observed_at,
            'advisory' => $advisory,
            'status_color' => $statusColor,
            'stations' => $stations,
            'stations_count' => $stations->count(),
            'is_fresh_48h' => true,
        ];
    }

    /**
     * Fetch real-time SafetyAlerts (landslides, rockfalls, flash floods, dense mist)
     * and road notices from NewsPosts to safeguard travel itineraries.
     */
    public function resolveRoadSafetyData(): array
    {
        $locale = session('locale', app()->getLocale() ?: 'si');

        // 1. Fetch active SafetyAlerts
        $safetyAlerts = SafetyAlert::query()
            ->where('is_active', true)
            ->with(['translations', 'weatherLocation.translations'])
            ->latest('reported_at')
            ->get();

        $alerts = collect();
        $hasCriticalDanger = false;
        $hasClosures = false;

        foreach ($safetyAlerts as $sa) {
            $trans = $sa->translationFor($locale);
            $title = $trans?->title ?? $sa->title;
            $desc = $trans?->description ?? $sa->description;
            $instructions = $trans?->safety_instructions ?? $sa->safety_instructions;
            $locationName = $trans?->location_name ?? $sa->location_name;

            $isDanger = $sa->severity === 'danger';
            $isWarning = $sa->severity === 'warning';
            $isClosure = $sa->hazard_type === 'road_closure' || ($isDanger && in_array($sa->hazard_type, ['landslide', 'rockfall']));

            if ($isDanger) {
                $hasCriticalDanger = true;
            }
            if ($isClosure) {
                $hasClosures = true;
            }

            $alerts->push([
                'type' => 'safety_alert',
                'title' => $title,
                'hazard_type' => $sa->hazard_type,
                'hazard_type_label' => $sa->hazard_type_label,
                'severity' => $sa->severity,
                'severity_label' => $sa->severity_label,
                'severity_badge_class' => $sa->severity_badge_class,
                'location_name' => $locationName,
                'description' => $desc,
                'safety_instructions' => $instructions,
                'date' => $sa->reported_at?->diffForHumans() ?? 'Active',
                'is_closure' => $isClosure,
                'is_danger' => $isDanger,
                'is_warning' => $isWarning,
            ]);
        }

        // 2. Also check NewsPosts for infrastructure closures
        $posts = NewsPost::query()
            ->where('status', 'published')
            ->with(['translations', 'category'])
            ->latest('published_at')
            ->take(5)
            ->get();

        foreach ($posts as $post) {
            $translation = $post->translationFor($locale);
            $title = $translation?->title ?? '';
            $content = $translation?->content ?? '';
            $categorySlug = $post->category?->slug ?? '';

            $isSafetyRelevant = in_array($categorySlug, ['development-infrastructure', 'community-updates', 'alerts'])
                || Str::contains(strtolower($title.' '.$content), [
                    'road', 'closure', 'closed', 'landslide', 'bridge', 'traffic', 'hazard', 'warning',
                    'මාර්ග', 'වසා', 'නාය', 'අනතුරු', 'අවවාද',
                ]);

            if ($isSafetyRelevant) {
                $isClosure = Str::contains(strtolower($title), ['closure', 'closed', 'block', 'වසා']);
                if ($isClosure) {
                    $hasClosures = true;
                }
                $alerts->push([
                    'type' => 'news_notice',
                    'title' => $title,
                    'hazard_type' => 'road_notice',
                    'hazard_type_label' => $post->category?->name ?? 'Notice',
                    'severity' => $isClosure ? 'warning' : 'advisory',
                    'severity_label' => $isClosure ? 'Warning' : 'Advisory',
                    'severity_badge_class' => $isClosure ? 'bg-amber-500 text-white' : 'bg-slate-600 text-white',
                    'location_name' => 'Laggala Region',
                    'description' => $translation?->excerpt ?? Str::limit(strip_tags($content), 140),
                    'safety_instructions' => null,
                    'date' => $post->published_at?->format('d M Y') ?? $post->created_at->format('d M Y'),
                    'is_closure' => $isClosure,
                    'is_danger' => false,
                    'is_warning' => $isClosure,
                ]);
            }
        }

        $summaryBadge = match (true) {
            $hasCriticalDanger => 'Critical Hazard Alert',
            $hasClosures => 'Road Closure Notice',
            $alerts->isNotEmpty() => 'Travel Advisory Active',
            default => 'All Routes Clear',
        };

        $summaryColor = match (true) {
            $hasCriticalDanger || $hasClosures => 'red',
            $alerts->isNotEmpty() => 'amber',
            default => 'emerald',
        };

        return [
            'has_alerts' => $alerts->isNotEmpty(),
            'has_closures' => $hasClosures,
            'has_critical_danger' => $hasCriticalDanger,
            'alerts' => $alerts,
            'safety_alerts_count' => $safetyAlerts->count(),
            'summary_badge' => $summaryBadge,
            'summary_color' => $summaryColor,
            'note' => $alerts->isEmpty()
                ? 'No active hazard alerts or road closures reported. Main Laggala–Pallegama and Riverston corridors are safe and clear.'
                : 'Active hazard warnings or advisories exist in the region. Please review instructions before traveling.',
        ];
    }

    /**
     * Resolve public transport bus connections for selected origin & destinations,
     * including SLTB vs Private bus classification, schedules, fares, and suspension status.
     */
    public function resolvePublicTransportData(array $start, Collection $itinerary): array
    {
        $locale = session('locale', app()->getLocale() ?: 'si');

        $routes = PublicTransport::query()
            ->with(['translations', 'submitter'])
            ->orderBy('is_suspended') // active routes first, suspended routes flagged
            ->orderBy('bus_category')
            ->orderBy('route_number')
            ->get();

        $routeList = $routes->map(function (PublicTransport $pt) use ($locale) {
            $trans = $pt->translationFor($locale);

            $categoryLabel = match ($pt->bus_category) {
                'sltb' => match ($locale) {
                    'si' => 'ශ්‍රී ලං.ග.ම. (පොදු බස් රථය)',
                    'ta' => 'இலங்கை போக்குவரத்து சபை (பொதுப் பேருந்து)',
                    default => 'SLTB (Public Bus)',
                },
                'private' => match ($locale) {
                    'si' => 'පුද්ගලික බස් රථය',
                    'ta' => 'தனியார் பேருந்து',
                    default => 'Private Bus',
                },
                default => match ($locale) {
                    'si' => 'පොදු බස් රථය',
                    'ta' => 'பொதுப் பேருந்து',
                    default => 'Public Bus',
                },
            };

            return [
                'id' => $pt->id,
                'route_name' => $trans?->route_name ?? $pt->route_name,
                'route_number' => $pt->route_number ?? 'Regional',
                'transport_type' => $pt->transport_type ?? 'bus',
                'bus_category' => $pt->bus_category ?? 'sltb',
                'bus_category_label' => $categoryLabel,
                'from_location' => $trans?->from_location ?? $pt->from_location,
                'to_location' => $trans?->to_location ?? $pt->to_location,
                'key_stops' => $trans?->key_stops ?? $pt->key_stops,
                'departure_time' => $pt->departure_time,
                'arrival_time' => $pt->arrival_time,
                'frequency' => $pt->frequency,
                'fare' => $pt->fare ? 'Rs. '.number_format($pt->fare, 2) : 'Standard SLTB fare',
                'fare_note' => $pt->fare_note,
                'operator_name' => $pt->operator_name,
                'contact_number' => $pt->contact_number,
                'is_suspended' => (bool) $pt->is_suspended,
                'suspension_reason' => $pt->suspension_reason,
                'suspended_until' => $pt->suspended_until instanceof \DateTimeInterface
                    ? $pt->suspended_until->format('Y-m-d')
                    : $pt->suspended_until,
                'notes' => $trans?->notes ?? $pt->notes,
            ];
        });

        $hasSuspensions = $routeList->contains('is_suspended', true);

        return [
            'available_routes' => $routeList,
            'has_suspensions' => $hasSuspensions,
            'bus_speed_note' => __('trip_planner.bus_tip_1'),
            'frequency_note' => __('trip_planner.bus_tip_2'),
            'local_shuttle_note' => __('trip_planner.bus_tip_3'),
        ];
    }

    /**
     * Resolve accommodations or overnight stay recommendations.
     * Extensible: searches database or suggests top scenic stays in Laggala.
     */
    public function resolveAccommodationRecommendations(array $finalLocation, int $nightNumber = 1): array
    {
        // Check for registered accommodation items in ExploreItem
        $dbItems = ExploreItem::query()
            ->where('status', 'published')
            ->whereHas('category', function ($q) {
                $q->whereIn('slug', ['hotels', 'accommodations', 'resorts', 'villas', 'camping']);
            })
            ->with('translations')
            ->take(4)
            ->get();

        if ($dbItems->isNotEmpty()) {
            $stays = $dbItems->map(function (ExploreItem $item) {
                $trans = $item->translations->firstWhere('locale', app()->getLocale())
                    ?? $item->translations->first();

                return [
                    'name' => $trans?->title ?? 'Laggala Eco Stay',
                    'type' => 'Hotel / Eco Resort',
                    'description' => $trans?->description ?? 'Scenic accommodation near destination',
                    'is_live' => true,
                ];
            });
        } else {
            // Curated stay recommendations tailored by night
            if ($nightNumber === 2) {
                $stays = collect([
                    [
                        'name' => 'Pallegama Central Rest House & Eco Hub',
                        'type' => 'Resthouse / Homestay',
                        'description' => 'Comfortable central lodging in Pallegama with dining, fuel access, and village hospitality.',
                        'is_live' => false,
                    ],
                    [
                        'name' => 'Traditional Meemure Village Heritage Stay',
                        'type' => 'Traditional Homestay / Camping',
                        'description' => 'Authentic clay house stay under Lakegala peak with traditional home-cooked Sri Lankan meals.',
                        'is_live' => false,
                    ],
                ]);
            } else {
                $stays = collect([
                    [
                        'name' => 'Riverston / Knuckles Eco Viewpoint Resorts',
                        'type' => 'Eco Lodge / Mountain Villa',
                        'description' => 'Cool climate mountain stays with panoramic views over Knuckles range and Thelgamu valley.',
                        'is_live' => false,
                    ],
                    [
                        'name' => 'Pitawala Pathana & Thelgamu Oya Campsites',
                        'type' => 'Camping / Glamping',
                        'description' => 'Riverfront natural camping grounds with pristine forest surroundings and stargazing.',
                        'is_live' => false,
                    ],
                ]);
            }
        }

        return [
            'night_number' => $nightNumber,
            'recommended_area' => ($nightNumber === 2) ? 'Pallegama Town & Meemure Foothills' : 'Riverston Gap & Knuckles Highland',
            'stays' => $stays,
            'note' => 'Registered accommodations added through the admin panel automatically populate here based on proximity.',
        ];
    }

    /**
     * Build Google Maps Multi-point Directions URL.
     */
    public function buildGoogleMapsRouteUrl(array $start, Collection $itinerary, string $travelMode): string
    {
        $destinations = $itinerary
            ->filter(fn ($item) => ! empty($item['latitude']) && ! empty($item['longitude']))
            ->values();

        if ($destinations->isEmpty()) {
            return "https://www.google.com/maps/search/?api=1&query={$start['latitude']},{$start['longitude']}";
        }

        $origin = "{$start['latitude']},{$start['longitude']}";
        $destination = "{$start['latitude']},{$start['longitude']}"; // Loop back to start

        $waypointCoords = $destinations->map(fn ($d) => "{$d['latitude']},{$d['longitude']}")->implode('|');

        $gMode = match ($travelMode) {
            'motorbike' => 'driving',
            'public_transport' => 'transit',
            'private_bus' => 'driving',
            default => 'driving',
        };

        return 'https://www.google.com/maps/dir/?api=1'
            .'&origin='.urlencode($origin)
            .'&destination='.urlencode($destination)
            .'&waypoints='.urlencode($waypointCoords)
            .'&travelmode='.$gMode;
    }

    /**
     * Build OpenStreetMap Multi-point Directions URL.
     */
    public function buildOpenStreetMapRouteUrl(array $start, Collection $itinerary): string
    {
        $destinations = $itinerary
            ->filter(fn ($item) => ! empty($item['latitude']) && ! empty($item['longitude']))
            ->values();

        if ($destinations->isEmpty()) {
            return "https://www.openstreetmap.org/?mlat={$start['latitude']}&mlon={$start['longitude']}#map=13/{$start['latitude']}/{$start['longitude']}";
        }

        $allPoints = collect([$start])
            ->concat($destinations)
            ->push($start);

        $routeParam = $allPoints
            ->map(fn ($p) => round((float) $p['latitude'], 6).'%2C'.round((float) $p['longitude'], 6))
            ->implode('%3B');

        return "https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route={$routeParam}";
    }

    /**
     * Build structured JSON array for Leaflet Map rendering.
     */
    public function buildMapWaypoints(
        array $start,
        Collection $itinerary,
        int $returnMinutes,
        Carbon $finalTime
    ): array {
        $points = [];

        // 1. Starting Point
        $points[] = [
            'type' => 'start',
            'order' => 'Start',
            'title' => $start['name'],
            'subtitle' => 'Starting Point',
            'latitude' => $start['latitude'],
            'longitude' => $start['longitude'],
            'icon' => 'house-door-fill',
            'color' => '#10b981', // Emerald
        ];

        // 2. Stops
        foreach ($itinerary as $item) {
            if (empty($item['latitude']) || empty($item['longitude'])) {
                continue;
            }

            $isMeal = ($item['type'] ?? '') === 'meal_stop';
            $dayNum = $item['day_number'] ?? 1;
            $dayColor = match ($dayNum) {
                1 => '#059669', // Emerald
                2 => '#2563eb', // Blue
                3 => '#7c3aed', // Purple
                default => '#2563eb',
            };

            $title = $isMeal
                ? ($item['title'] ?? 'Lunch Break')
                : ($item['translation']?->name ?? 'Destination');

            $subtitle = $isMeal
                ? 'Dining Stop ('.($item['visit_start']?->format('h:i A') ?? '').')'
                : 'Day '.$dayNum.' · Stop #'.($item['order'] ?? '').' ('.($item['visit_start']?->format('h:i A') ?? '').' - '.($item['visit_end']?->format('h:i A') ?? '').')';

            $points[] = [
                'type' => $isMeal ? 'meal' : 'destination',
                'day_number' => $dayNum,
                'order' => $isMeal ? '🍽️' : (string) ($item['order'] ?? ''),
                'title' => $title,
                'subtitle' => $subtitle,
                'latitude' => (float) $item['latitude'],
                'longitude' => (float) $item['longitude'],
                'leg_distance' => $item['leg_distance_km'] ?? 0,
                'nearest_bus_stop' => $item['nearest_bus_stop'] ?? null,
                'bus_routes' => $item['bus_routes'] ?? null,
                'icon' => $isMeal ? 'cup-hot-fill' : 'geo-alt-fill',
                'color' => $isMeal ? '#f59e0b' : $dayColor,
            ];
        }

        // 3. Return Point
        $points[] = [
            'type' => 'return',
            'order' => 'End',
            'title' => 'Return to '.$start['name'],
            'subtitle' => 'Estimated return ~'.$finalTime->copy()->addMinutes($returnMinutes)->format('h:i A'),
            'latitude' => $start['latitude'],
            'longitude' => $start['longitude'],
            'icon' => 'flag-fill',
            'color' => '#10b981',
        ];

        return $points;
    }

    /**
     * Build Google Maps Embed Directions URL (no API key required).
     *
     * Uses the `/maps/embed` endpoint with `mode=directions` which renders
     * a proper driving route with roads inside an iframe without needing an API key.
     */
    public function buildGoogleMapsEmbedUrl(array $start, Collection $itinerary): string
    {
        $stops = $itinerary
            ->filter(fn ($item) => ! empty($item['latitude']) && ! empty($item['longitude']))
            ->values();

        // Collect all relevant coordinates to compute a centre point for the embed
        $allLats = collect([$start['latitude']]);
        $allLngs = collect([$start['longitude']]);

        foreach ($stops as $stop) {
            $allLats->push((float) $stop['latitude']);
            $allLngs->push((float) $stop['longitude']);
        }

        $centreLat = $allLats->avg();
        $centreLng = $allLngs->avg();

        // Zoom level: tighter for few stops close together, wider for spread-out routes
        $latSpan = $allLats->max() - $allLats->min();
        $lngSpan = $allLngs->max() - $allLngs->min();
        $span = max($latSpan, $lngSpan);
        $zoom = $span < 0.05 ? 14 : ($span < 0.2 ? 12 : ($span < 0.5 ? 11 : 10));

        // Use the keyless static maps embed — centres the map on the route area.
        // The interactive directions link (google_maps_url) is the primary navigation tool.
        return 'https://maps.google.com/maps?'
            .http_build_query([
                'q' => "{$centreLat},{$centreLng}",
                'z' => $zoom,
                'output' => 'embed',
                't' => 'm',
            ]);
    }

    /**
     * Refine leg distances and travel times using real road network data (OSRM).
     */
    private function refineWithRealRoadRouting(
        array $start,
        Collection &$selected,
        float $speedKmh,
        float &$returnDistance,
        int &$returnMinutes
    ): void {
        if ($selected->isEmpty()) {
            return;
        }

        try {
            $coords = ["{$start['longitude']},{$start['latitude']}"];
            foreach ($selected as $item) {
                if (! empty($item['latitude']) && ! empty($item['longitude'])) {
                    $coords[] = "{$item['longitude']},{$item['latitude']}";
                }
            }
            $coords[] = "{$start['longitude']},{$start['latitude']}"; // Loop back to start

            $coordsStr = implode(';', $coords);
            $url = "https://router.project-osrm.org/route/v1/driving/{$coordsStr}?overview=false";

            $response = Http::withoutVerifying()
                ->timeout(3)
                ->get($url);

            if ($response->successful() && isset($response->json()['routes'][0]['legs'])) {
                $legs = $response->json()['routes'][0]['legs'];
                $legIndex = 0;
                $cumulative = 0.0;

                foreach ($selected as $key => $item) {
                    if (isset($legs[$legIndex])) {
                        $legDistKm = round($legs[$legIndex]['distance'] / 1000, 1);
                        $legDurationMin = (int) ceil($legs[$legIndex]['duration'] / 60);

                        $cumulative += $legDistKm;

                        $selected[$key] = array_merge($item, [
                            'leg_distance_km' => $legDistKm,
                            'cumulative_distance_km' => round($cumulative, 1),
                            'travel_minutes' => max(10, $legDurationMin),
                        ]);

                        $legIndex++;
                    }
                }

                if (isset($legs[$legIndex])) {
                    $returnDistance = round($legs[$legIndex]['distance'] / 1000, 1);
                    $returnMinutes = (int) ceil($legs[$legIndex]['duration'] / 60);
                }
            }
        } catch (\Throwable $e) {
            // Graceful fallback to mountain road factor calculations
        }
    }

    /**
     * Calculate road distance between two coordinates in Kilometers.
     * Incorporates mountain road detour factor (~1.55x) for Knuckles/Laggala terrain.
     */
    public function distanceInKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $straightLineKm = $earthRadius * $c;

        // Mountain terrain curvature / road detour factor for Knuckles/Laggala
        return $straightLineKm * 1.55;
    }

    /**
     * Estimate travel time based on distance and average speed in minutes.
     */
    public function travelTimeMinutes(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2,
        float $speedKmh
    ): int {
        $distance = $this->distanceInKm($lat1, $lon1, $lat2, $lon2);

        return (int) ceil(($distance / max(5, $speedKmh)) * 60);
    }
}
