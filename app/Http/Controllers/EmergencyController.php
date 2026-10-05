<?php

namespace App\Http\Controllers;

use App\Models\EmergencyContact;
use App\Models\Hospital;
use App\Models\PoliceStation;
use App\Models\VehicleAssistance;
use App\Models\WildlifeForestOffice;
use Illuminate\Http\Request;

class EmergencyController extends Controller
{
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

    /**
     * Emergency Overview & Quick Contacts
     */
    public function index(Request $request)
    {
        return $this->contacts($request);
    }

    /**
     * Emergency Contacts Page
     */
    public function contacts(Request $request)
    {
        $locale = $this->resolveLocale($request);

        $category = $request->get('category');
        $query = EmergencyContact::query()
            ->where('status', 'published')
            ->with(['translations']);

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        $contacts = $query->orderBy('sort_order')->get();

        return view('emergency.contacts', compact('contacts', 'locale', 'category'));
    }

    /**
     * Hospitals & Medical Centers (Gov & Private)
     */
    public function hospitals(Request $request)
    {
        $locale = $this->resolveLocale($request);

        $type = $request->get('type');
        $query = Hospital::query()
            ->where('status', 'published')
            ->with(['translations']);

        if ($type && $type !== 'all') {
            $query->where('type', $type);
        }

        $hospitals = $query->orderBy('sort_order')->get();

        return view('emergency.hospitals', compact('hospitals', 'locale', 'type'));
    }

    /**
     * Police Stations
     */
    public function police(Request $request)
    {
        $locale = $this->resolveLocale($request);

        $stations = PoliceStation::query()
            ->where('status', 'published')
            ->with(['translations'])
            ->orderBy('sort_order')
            ->get();

        return view('emergency.police', compact('stations', 'locale'));
    }

    /**
     * Wildlife & Forest Range Offices
     */
    public function wildlifeForest(Request $request)
    {
        $locale = $this->resolveLocale($request);

        $department = $request->get('department');
        $query = WildlifeForestOffice::query()
            ->where('status', 'published')
            ->with(['translations']);

        if ($department && $department !== 'all') {
            $query->where('department_type', $department);
        }

        $offices = $query->orderBy('sort_order')->get();

        return view('emergency.wildlife-forest', compact('offices', 'locale', 'department'));
    }

    /**
     * Vehicle Assistance, Mountain Recovery & Breakdown
     */
    public function vehicleAssistance(Request $request)
    {
        $locale = $this->resolveLocale($request);

        $service = $request->get('service');
        $query = VehicleAssistance::query()
            ->where('status', 'published')
            ->with(['translations']);

        if ($service && $service !== 'all') {
            $query->where('service_type', $service);
        }

        $assistances = $query->orderBy('sort_order')->get();

        return view('emergency.vehicle-assistance', compact('assistances', 'locale', 'service'));
    }
}
