<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\ExploreCategory;
use App\Models\ExploreItem;
use App\Models\Institution;
use App\Models\Interest;
use App\Models\MobileCoverageReport;
use App\Models\ServicePlace;
use App\Models\StayEatItem;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    /**
     * Main Explore page
     */
    public function index()
    {
        $featuredDestinations = Destination::query()
            ->where('status', 'published')
            ->where('featured', true)
            ->with([
                'translations',
                'images',
            ])
            ->orderBy('sort_order')
            ->get();

        return view('explore.index', compact(
            'featuredDestinations'
        ));
    }

    /**
     * All published destinations with featured slider, categories and search filtering
     */
    public function destinations(Request $request)
    {
        $locale = app()->getLocale();

        // 1. Featured / Selected Destinations for Top Hero Slider
        $featuredDestinations = Destination::query()
            ->where('status', 'published')
            ->where('featured', true)
            ->with([
                'translations',
                'images',
                'interests',
            ])
            ->orderBy('sort_order')
            ->get();

        if ($featuredDestinations->isEmpty()) {
            $featuredDestinations = Destination::query()
                ->where('status', 'published')
                ->with(['translations', 'images', 'interests'])
                ->orderBy('sort_order')
                ->take(5)
                ->get();
        }

        // 2. Categories / Interests for Sidebar Classification
        $interests = Interest::query()
            ->where('is_active', true)
            ->withCount(['destinations' => function ($q) {
                $q->where('status', 'published');
            }])
            ->orderBy('sort_order')
            ->get();

        // 3. Destinations Listing Query with Filtering
        $query = Destination::query()
            ->where('status', 'published')
            ->with([
                'translations',
                'images',
                'interests',
                'approvedReviews',
            ]);

        // Category / Interest filter
        $selectedCategory = $request->query('category');
        if ($selectedCategory) {
            $query->whereHas('interests', function ($q) use ($selectedCategory) {
                $q->where('slug', $selectedCategory)
                    ->orWhere('interests.id', $selectedCategory);
            });
        }

        // Search query
        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('location_name', 'LIKE', "%{$search}%")
                    ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->query('sort', 'default');
        if ($sort === 'rating') {
            $query->withAvg('approvedReviews as rating_avg', 'rating')
                ->orderByDesc('rating_avg')
                ->orderBy('sort_order');
        } elseif ($sort === 'name') {
            $query->whereHas('translations', function ($q) {
                $q->where('locale', app()->getLocale());
            })->orderBy('sort_order');
        } else {
            $query->orderBy('sort_order')->orderByDesc('featured');
        }

        $destinations = $query->paginate(9)->withQueryString();
        $totalCount = Destination::query()->where('status', 'published')->count();

        return view('explore.destinations.index', compact(
            'featuredDestinations',
            'destinations',
            'interests',
            'selectedCategory',
            'search',
            'sort',
            'totalCount'
        ));
    }

    /**
     * Single destination
     */
    public function destination(string $slug)
    {
        $locale = app()->getLocale();

        $destination = Destination::query()
            ->where('status', 'published')
            ->whereHas('translations', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->with([
                'translations',
                'images',
                'plannerDetails',
                'approvedReviews.user',
                'approvedMobileCoverageReports.reporter',
            ])
            ->firstOrFail();

        $translation = $destination->translationFor($locale);

        $userReview = auth()->check()
            ? $destination->reviews()->where('user_id', auth()->id())->first()
            : null;

        // Coverage reports for this destination or nearby
        $coverageReports = $destination->approvedMobileCoverageReports;
        if ($coverageReports->isEmpty() && $destination->latitude && $destination->longitude) {
            $coverageReports = MobileCoverageReport::where('status', 'approved')
                ->where(function ($q) use ($destination, $translation) {
                    if ($translation?->name) {
                        $q->where('location_name', 'LIKE', '%'.$translation->name.'%');
                    }
                    $q->orWhereRaw('ABS(latitude - ?) < 0.05 AND ABS(longitude - ?) < 0.05', [
                        $destination->latitude,
                        $destination->longitude,
                    ]);
                })
                ->with('reporter')
                ->get();
        }

        // 5 nearest destinations based on geographic proximity
        $nearbyDestinations = Destination::query()
            ->where('status', 'published')
            ->where('id', '!=', $destination->id)
            ->with([
                'translations',
                'images',
                'interests',
            ])
            ->get()
            ->map(function ($d) use ($destination) {
                if ($destination->latitude && $destination->longitude && $d->latitude && $d->longitude) {
                    $latFrom = deg2rad((float) $destination->latitude);
                    $lonFrom = deg2rad((float) $destination->longitude);
                    $latTo = deg2rad((float) $d->latitude);
                    $lonTo = deg2rad((float) $d->longitude);
                    $latDelta = $latTo - $latFrom;
                    $lonDelta = $lonTo - $lonFrom;
                    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                    $d->distance_km = round($angle * 6371, 1);
                } else {
                    $d->distance_km = null;
                }

                return $d;
            })
            ->sortBy(fn ($d) => $d->distance_km ?? 999999)
            ->take(5)
            ->values();

        return view('explore.destinations.show', compact(
            'destination',
            'translation',
            'userReview',
            'coverageReports',
            'nearbyDestinations'
        ));
    }

    /**
     * Interactive map
     */
    public function map(Request $request)
    {
        $locale = $this->resolveLocale($request);

        $destinations = Destination::query()
            ->where('status', 'published')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with([
                'translations',
                'images',
                'interests',
                'plannerDetails',
            ])
            ->orderBy('sort_order')
            ->get();

        $exploreItems = ExploreItem::query()
            ->where('status', 'published')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with([
                'translations',
                'images',
                'category',
            ])
            ->orderBy('sort_order')
            ->get();

        $institutions = Institution::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('translations')
            ->get();

        $servicePlaces = ServicePlace::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('translations')
            ->get();

        $stayEatItems = StayEatItem::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['translations', 'images'])
            ->get();

        $coverageReports = MobileCoverageReport::query()
            ->where('status', 'approved')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['reporter', 'destination.translations'])
            ->latest('reported_at')
            ->get();

        return view('explore.map', compact(
            'destinations',
            'exploreItems',
            'institutions',
            'servicePlaces',
            'stayEatItems',
            'coverageReports',
            'locale'
        ));
    }

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
     * Culture & Heritage index
     */
    public function cultureHeritage(Request $request)
    {
        $locale = $this->resolveLocale($request);
        $type = 'culture-heritage';

        // 1. Featured items for top hero slider
        $featuredItems = ExploreItem::query()
            ->where('type', $type)
            ->where('status', 'published')
            ->where('featured', true)
            ->with(['translations', 'images', 'category'])
            ->orderBy('sort_order')
            ->get();

        if ($featuredItems->isEmpty()) {
            $featuredItems = ExploreItem::query()
                ->where('type', $type)
                ->where('status', 'published')
                ->with(['translations', 'images', 'category'])
                ->orderBy('sort_order')
                ->take(5)
                ->get();
        }

        // 2. Categories for sidebar & filter tags
        $categories = ExploreCategory::query()
            ->where('type', $type)
            ->where('is_active', true)
            ->withCount(['items' => fn ($q) => $q->where('status', 'published')])
            ->orderBy('sort_order')
            ->get();

        // 3. Main query
        $query = ExploreItem::query()
            ->where('type', $type)
            ->where('status', 'published')
            ->with(['translations', 'images', 'category', 'approvedReviews']);

        $selectedCategory = $request->query('category');
        if ($selectedCategory) {
            $query->whereHas('category', function ($q) use ($selectedCategory) {
                $q->where('slug', $selectedCategory)
                    ->orWhere('id', $selectedCategory);
            });
        }

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('location_name', 'LIKE', "%{$search}%")
                    ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        $sort = $request->query('sort', 'default');
        if ($sort === 'rating') {
            $query->withAvg('approvedReviews as rating_avg', 'rating')
                ->orderByDesc('rating_avg')
                ->orderBy('sort_order');
        } elseif ($sort === 'newest') {
            $query->latest();
        } else {
            $query->orderBy('sort_order')->orderByDesc('featured');
        }

        $items = $query->paginate(9)->withQueryString();
        $totalCount = ExploreItem::where('type', $type)->where('status', 'published')->count();

        return view('explore.culture-heritage.index', compact(
            'featuredItems',
            'items',
            'categories',
            'selectedCategory',
            'search',
            'sort',
            'totalCount'
        ));
    }

    /**
     * Culture & Heritage single item
     */
    public function cultureHeritageShow(Request $request, string $slug)
    {
        $locale = $this->resolveLocale($request);

        $item = ExploreItem::query()
            ->where('type', 'culture-heritage')
            ->where('status', 'published')
            ->whereHas('translations', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->with([
                'translations',
                'images',
                'category',
                'approvedReviews.user',
            ])
            ->firstOrFail();

        $translation = $item->translationFor($locale);

        $userReview = auth()->check()
            ? $item->reviews()->where('user_id', auth()->id())->first()
            : null;

        $relatedItems = ExploreItem::query()
            ->where('type', 'culture-heritage')
            ->where('status', 'published')
            ->where('id', '!=', $item->id)
            ->with(['translations', 'images', 'category'])
            ->take(4)
            ->get();

        return view('explore.culture-heritage.show', compact(
            'item',
            'translation',
            'userReview',
            'relatedItems'
        ));
    }

    /**
     * Outdoor & Adventure index
     */
    public function outdoorAdventure(Request $request)
    {
        $locale = $this->resolveLocale($request);
        $type = 'outdoor-adventure';

        // 1. Featured items for top hero slider
        $featuredItems = ExploreItem::query()
            ->where('type', $type)
            ->where('status', 'published')
            ->where('featured', true)
            ->with(['translations', 'images', 'category'])
            ->orderBy('sort_order')
            ->get();

        if ($featuredItems->isEmpty()) {
            $featuredItems = ExploreItem::query()
                ->where('type', $type)
                ->where('status', 'published')
                ->with(['translations', 'images', 'category'])
                ->orderBy('sort_order')
                ->take(5)
                ->get();
        }

        // 2. Categories
        $categories = ExploreCategory::query()
            ->where('type', $type)
            ->where('is_active', true)
            ->withCount(['items' => fn ($q) => $q->where('status', 'published')])
            ->orderBy('sort_order')
            ->get();

        // 3. Query
        $query = ExploreItem::query()
            ->where('type', $type)
            ->where('status', 'published')
            ->with(['translations', 'images', 'category', 'approvedReviews']);

        $selectedCategory = $request->query('category');
        if ($selectedCategory) {
            $query->whereHas('category', function ($q) use ($selectedCategory) {
                $q->where('slug', $selectedCategory)
                    ->orWhere('id', $selectedCategory);
            });
        }

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('location_name', 'LIKE', "%{$search}%")
                    ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        $sort = $request->query('sort', 'default');
        if ($sort === 'rating') {
            $query->withAvg('approvedReviews as rating_avg', 'rating')
                ->orderByDesc('rating_avg')
                ->orderBy('sort_order');
        } elseif ($sort === 'newest') {
            $query->latest();
        } else {
            $query->orderBy('sort_order')->orderByDesc('featured');
        }

        $items = $query->paginate(9)->withQueryString();
        $totalCount = ExploreItem::where('type', $type)->where('status', 'published')->count();

        return view('explore.outdoor-adventure.index', compact(
            'featuredItems',
            'items',
            'categories',
            'selectedCategory',
            'search',
            'sort',
            'totalCount'
        ));
    }

    /**
     * Outdoor & Adventure single item
     */
    public function outdoorAdventureShow(Request $request, string $slug)
    {
        $locale = $this->resolveLocale($request);

        $item = ExploreItem::query()
            ->where('type', 'outdoor-adventure')
            ->where('status', 'published')
            ->whereHas('translations', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->with([
                'translations',
                'images',
                'category',
                'approvedReviews.user',
            ])
            ->firstOrFail();

        $translation = $item->translationFor($locale);

        $userReview = auth()->check()
            ? $item->reviews()->where('user_id', auth()->id())->first()
            : null;

        $relatedItems = ExploreItem::query()
            ->where('type', 'outdoor-adventure')
            ->where('status', 'published')
            ->where('id', '!=', $item->id)
            ->with(['translations', 'images', 'category'])
            ->take(4)
            ->get();

        return view('explore.outdoor-adventure.show', compact(
            'item',
            'translation',
            'userReview',
            'relatedItems'
        ));
    }
}
