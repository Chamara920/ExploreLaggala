<?php

namespace App\Http\Controllers;

use App\Models\ServicePlace;
use Illuminate\Http\Request;

class ServicePlaceController extends Controller
{
    /**
     * Resolve the active locale based on query parameter, session, or app default.
     */
    private function resolveLocale(?Request $request = null): string
    {
        $lang = $request?->get('lang');
        if ($lang && in_array($lang, ['si', 'en', 'ta'])) {
            session(['locale' => $lang]);
            app()->setLocale($lang);

            return $lang;
        }

        $locale = session('locale', app()->getLocale() ?: 'en');
        if (in_array($locale, ['si', 'en', 'ta'])) {
            app()->setLocale($locale);

            return $locale;
        }

        return app()->getLocale();
    }

    /**
     * Service Category Listing (health, shops-businesses, banks-atms, fuel-ev, education)
     */
    public function section(Request $request, string $section)
    {
        if (! array_key_exists($section, ServicePlace::SECTIONS)) {
            abort(404, 'Service section not found');
        }

        $locale = $this->resolveLocale($request);
        $sectionMeta = ServicePlace::SECTIONS[$section];

        $subCategory = $request->query('category');
        $search = trim((string) $request->query('q', ''));

        $query = ServicePlace::query()
            ->where('section', $section)
            ->where('status', 'published')
            ->with(['translations', 'approvedReviews']);

        if (! empty($subCategory)) {
            $query->where('sub_category', $subCategory);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('translations', function ($tq) use ($search) {
                    $tq->where('name', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%")
                        ->orWhere('location_name', 'like', "%{$search}%")
                        ->orWhere('key_facilities', 'like', "%{$search}%");
                })->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $places = $query->orderBy('featured', 'desc')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(9)
            ->withQueryString();

        // Coordinates for map pins
        $mapPlaces = ServicePlace::query()
            ->where('section', $section)
            ->where('status', 'published')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('translations')
            ->get()
            ->map(function ($place) use ($locale, $section) {
                $t = $place->translationFor($locale);

                return [
                    'id' => $place->id,
                    'name' => $t?->name ?? 'Service Place',
                    'slug' => $t?->slug ?? '',
                    'address' => $t?->location_name ?? '',
                    'phone' => $place->phone ?? $place->emergency_hotline,
                    'lat' => (float) $place->latitude,
                    'lng' => (float) $place->longitude,
                    'url' => route('services.places.show', [$section, $t?->slug ?? $place->id]),
                    'image' => $place->image_path ? asset('storage/'.$place->image_path) : null,
                ];
            });

        return view('services.places.index', compact(
            'places',
            'section',
            'sectionMeta',
            'subCategory',
            'search',
            'mapPlaces'
        ));
    }

    /**
     * Single Service Place Details
     */
    public function show(Request $request, string $section, string $slug)
    {
        if (! array_key_exists($section, ServicePlace::SECTIONS)) {
            abort(404, 'Service section not found');
        }

        $locale = $this->resolveLocale($request);
        $sectionMeta = ServicePlace::SECTIONS[$section];

        $place = ServicePlace::query()
            ->where('section', $section)
            ->where('status', 'published')
            ->where(function ($q) use ($slug) {
                $q->whereHas('translations', function ($tq) use ($slug) {
                    $tq->where('slug', $slug);
                });
                if (is_numeric($slug)) {
                    $q->orWhere('id', (int) $slug);
                }
            })
            ->with([
                'translations',
                'approvedReviews.user',
            ])
            ->firstOrFail();

        $translation = $place->translationFor($locale);

        $userReview = auth()->check()
            ? $place->reviews()->where('user_id', auth()->id())->first()
            : null;

        // Nearby / Related places in the same category
        $relatedPlaces = ServicePlace::query()
            ->where('section', $section)
            ->where('status', 'published')
            ->where('id', '!=', $place->id)
            ->with('translations')
            ->take(3)
            ->get();

        return view('services.places.show', compact(
            'place',
            'section',
            'sectionMeta',
            'translation',
            'userReview',
            'relatedPlaces'
        ));
    }
}
