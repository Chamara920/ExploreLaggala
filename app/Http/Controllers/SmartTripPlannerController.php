<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use App\Services\TripPlannerService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SmartTripPlannerController extends Controller
{
    public function create(Request $request, TripPlannerService $planner): View
    {
        $lang = $request->query('lang');
        if ($lang && in_array($lang, ['en', 'si', 'ta'], true)) {
            session(['locale' => $lang]);
            app()->setLocale($lang);
        }

        $interests = Interest::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $weatherPreview = $planner->resolveWeatherData();
        $roadSafetyPreview = $planner->resolveRoadSafetyData();

        return view(
            'plan.smart-trip-planner.index',
            compact('interests', 'weatherPreview', 'roadSafetyPreview')
        );
    }

    public function generate(
        Request $request,
        TripPlannerService $planner
    ): View {
        $validated = $request->validate([
            'trip_days' => [
                'nullable',
                'integer',
                'in:1,2,3',
            ],

            'travel_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],

            'available_time' => [
                'nullable',
                'in:4,6,8,full_day',
            ],

            'interest_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'interest_ids.*' => [
                'integer',
                'exists:interests,id',
            ],

            'start_location_name' => [
                'required',
                'string',
                'max:255',
            ],

            'start_latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'start_longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'travel_mode' => [
                'required',
                'in:private_vehicle,motorbike,public_transport,private_bus',
            ],

            'include_meal_stop' => [
                'nullable',
                'boolean',
            ],

            'include_accommodation' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['include_meal_stop'] = $request->boolean('include_meal_stop');
        $validated['include_accommodation'] = $request->boolean('include_accommodation');
        $validated['trip_days'] = (int) ($validated['trip_days'] ?? 1);
        if ($validated['trip_days'] < 1 || $validated['trip_days'] > 3) {
            $validated['trip_days'] = 1;
        }
        if (empty($validated['available_time'])) {
            $validated['available_time'] = 'full_day';
        }

        $plan = $planner->generate($validated);

        $interests = Interest::query()
            ->whereIn('id', $validated['interest_ids'])
            ->orderBy('sort_order')
            ->get();

        return view(
            'plan.smart-trip-planner.result',
            compact(
                'plan',
                'interests'
            )
        );
    }
}
