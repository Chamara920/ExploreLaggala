<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
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
     * Government Institutions directory page
     */
    public function index(Request $request)
    {
        $locale = $this->resolveLocale($request);
        $selectedType = $request->query('type');
        $search = trim((string) $request->query('q', ''));

        $query = Institution::query()
            ->where('status', 'published')
            ->with([
                'translations',
                'rootUnits.translations',
                'services.translations',
            ]);

        if (! empty($selectedType) && array_key_exists($selectedType, Institution::TYPES)) {
            $query->where('type', $selectedType);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('translations', function ($tq) use ($search) {
                    $tq->where('name', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('location_name', 'like', "%{$search}%");
                })->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $institutions = $query->orderBy('featured', 'desc')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(9)
            ->withQueryString();

        $types = Institution::TYPES;

        // Coordinates for map pins
        $mapInstitutions = Institution::query()
            ->where('status', 'published')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('translations')
            ->get()
            ->map(function ($inst) use ($locale) {
                $t = $inst->translationFor($locale);

                return [
                    'id' => $inst->id,
                    'name' => $t?->name ?? 'Institution',
                    'slug' => $t?->slug ?? '',
                    'type' => $inst->type_label,
                    'address' => $t?->location_name ?? '',
                    'phone' => $inst->phone,
                    'lat' => (float) $inst->latitude,
                    'lng' => (float) $inst->longitude,
                    'url' => route('services.institutions.show', $t?->slug ?? $inst->id),
                    'image' => $inst->image_path ? asset('storage/'.$inst->image_path) : null,
                ];
            });

        return view('services.institutions.index', compact(
            'institutions',
            'types',
            'selectedType',
            'search',
            'mapInstitutions'
        ));
    }

    /**
     * Single Government Institution Profile (Hybrid Page Builder)
     */
    public function show(Request $request, string $slug)
    {
        $locale = $this->resolveLocale($request);

        $institution = Institution::query()
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
                'units' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order');
                },
                'units.translations',
                'units.children.translations',
                'units.services.translations',
                'units.officers.translations',
                'services' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order');
                },
                'services.translations',
                'services.unit.translations',
                'officers' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order');
                },
                'officers.translations',
                'officers.unit.translations',
                'customSections' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order');
                },
                'customSections.translations',
                'documents' => function ($q) {
                    $q->orderBy('sort_order');
                },
                'documents.translations',
                'approvedReviews.user',
            ])
            ->firstOrFail();

        $translation = $institution->translationFor($locale);

        $userReview = auth()->check()
            ? $institution->reviews()->where('user_id', auth()->id())->first()
            : null;

        // Group units hierarchically
        $rootUnits = $institution->units->whereNull('parent_id')->values();

        return view('services.institutions.show', compact(
            'institution',
            'translation',
            'rootUnits',
            'userReview'
        ));
    }
}
