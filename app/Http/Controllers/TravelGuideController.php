<?php

namespace App\Http\Controllers;

use App\Models\TravelGuide;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TravelGuideController extends Controller
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

    /**
     * Display all published travel guides, grouped by category.
     */
    public function index(Request $request): View
    {
        $locale = $this->resolveLocale($request);

        $guides = TravelGuide::published()
            ->with(['author', 'translations'])
            ->orderBy('featured', 'desc')
            ->orderBy('sort_order')
            ->orderBy('published_at', 'desc')
            ->get()
            ->groupBy('category');

        $featuredGuides = TravelGuide::published()
            ->where('featured', true)
            ->with(['author', 'translations'])
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('plan.travel-guide.index', compact('guides', 'featuredGuides', 'locale'));
    }

    /**
     * Display a single travel guide.
     */
    public function show(Request $request, string $slug): View
    {
        $locale = $this->resolveLocale($request);

        $guide = TravelGuide::published()
            ->where('slug', $slug)
            ->with(['author', 'translations'])
            ->firstOrFail();

        $relatedGuides = TravelGuide::published()
            ->where('category', $guide->category)
            ->where('id', '!=', $guide->id)
            ->with('translations')
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('plan.travel-guide.show', compact('guide', 'relatedGuides', 'locale'));
    }
}
