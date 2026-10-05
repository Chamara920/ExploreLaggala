<?php

namespace App\Http\Controllers;

use App\Models\StayEatCategory;
use App\Models\StayEatItem;
use Illuminate\Http\Request;

class StayEatController extends Controller
{
    /**
     * Allowed section slugs.
     */
    public const SECTIONS = [
        'accommodation' => [
            'name_key' => 'stay_eat.sections.accommodation',
            'title' => 'Accommodation',
            'icon' => '🏨',
            'meta' => 'Hotels, resorts, eco lodges, homestays, and camping sites in Laggala and Knuckles range.',
        ],
        'restaurants-cafes' => [
            'name_key' => 'stay_eat.sections.restaurants_cafes',
            'title' => 'Restaurants & Cafés',
            'icon' => '🍽️',
            'meta' => 'Dine in style with authentic restaurants, cosy cafés, and takeaway spots in Laggala.',
        ],
        'local-food' => [
            'name_key' => 'stay_eat.sections.local_food',
            'title' => 'Local Food & Flavours',
            'icon' => '🍛',
            'meta' => 'Savour authentic Sri Lankan village meals, herbal drinks, and local sweetmeats in Knuckles region.',
        ],
        'outdoor-dining' => [
            'name_key' => 'stay_eat.sections.outdoor_dining',
            'title' => 'Outdoor Dining & Catering',
            'icon' => '🏕️',
            'meta' => 'Enjoy scenic picnic spreads, campfire culinary experiences, and event catering in wilderness.',
        ],
    ];

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
     * Main Stay & Eat Hub page.
     */
    public function index(Request $request)
    {
        $locale = $this->resolveLocale($request);

        $sectionsData = [];
        foreach (self::SECTIONS as $slug => $meta) {
            $categories = StayEatCategory::query()
                ->where('section', $slug)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $featured = StayEatItem::query()
                ->where('section', $slug)
                ->where('status', 'published')
                ->where('featured', true)
                ->with(['translations', 'images', 'category', 'approvedReviews'])
                ->orderBy('sort_order')
                ->take(4)
                ->get();

            if ($featured->isEmpty()) {
                $featured = StayEatItem::query()
                    ->where('section', $slug)
                    ->where('status', 'published')
                    ->with(['translations', 'images', 'category', 'approvedReviews'])
                    ->orderBy('sort_order')
                    ->take(4)
                    ->get();
            }

            $count = StayEatItem::where('section', $slug)->where('status', 'published')->count();

            $sectionsData[$slug] = [
                'meta' => $meta,
                'categories' => $categories,
                'featured' => $featured,
                'count' => $count,
            ];
        }

        $allFeatured = StayEatItem::query()
            ->where('status', 'published')
            ->where('featured', true)
            ->with(['translations', 'images', 'category'])
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        return view('stay-eat.index', compact('sectionsData', 'allFeatured', 'locale'));
    }

    /**
     * Sub-section Landing Page (e.g. /stay-eat/accommodation).
     */
    public function section(Request $request, string $section)
    {
        if (! array_key_exists($section, self::SECTIONS)) {
            abort(404);
        }

        $locale = $this->resolveLocale($request);
        $sectionMeta = self::SECTIONS[$section];

        // 1. Featured items for top hero slider
        $featuredItems = StayEatItem::query()
            ->where('section', $section)
            ->where('status', 'published')
            ->where('featured', true)
            ->with(['translations', 'images', 'category', 'approvedReviews'])
            ->orderBy('sort_order')
            ->get();

        if ($featuredItems->isEmpty()) {
            $featuredItems = StayEatItem::query()
                ->where('section', $section)
                ->where('status', 'published')
                ->with(['translations', 'images', 'category', 'approvedReviews'])
                ->orderBy('sort_order')
                ->take(5)
                ->get();
        }

        // 2. Categories for this section
        $categories = StayEatCategory::query()
            ->where('section', $section)
            ->where('is_active', true)
            ->withCount(['items' => fn ($q) => $q->where('status', 'published')])
            ->orderBy('sort_order')
            ->get();

        // 3. Filtered query
        $query = StayEatItem::query()
            ->where('section', $section)
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
        $totalCount = StayEatItem::where('section', $section)->where('status', 'published')->count();

        return view('stay-eat.section', compact(
            'section',
            'sectionMeta',
            'featuredItems',
            'items',
            'categories',
            'selectedCategory',
            'search',
            'sort',
            'totalCount',
            'locale'
        ));
    }

    /**
     * Item Detail Page (e.g. /stay-eat/accommodation/river-breeze-resort).
     */
    public function show(Request $request, string $section, string $slug)
    {
        if (! array_key_exists($section, self::SECTIONS)) {
            abort(404);
        }

        $locale = $this->resolveLocale($request);
        $sectionMeta = self::SECTIONS[$section];

        $item = StayEatItem::query()
            ->where('section', $section)
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

        $relatedItems = StayEatItem::query()
            ->where('section', $section)
            ->where('status', 'published')
            ->where('id', '!=', $item->id)
            ->with(['translations', 'images', 'category', 'approvedReviews'])
            ->take(3)
            ->get();

        return view('stay-eat.show', compact(
            'section',
            'sectionMeta',
            'item',
            'translation',
            'userReview',
            'relatedItems',
            'locale'
        ));
    }
}
