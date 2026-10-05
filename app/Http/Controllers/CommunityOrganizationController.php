<?php

namespace App\Http\Controllers;

use App\Models\CommunityOrganization;
use App\Models\OrganizationType;
use Illuminate\Http\Request;

class CommunityOrganizationController extends Controller
{
    public function index(Request $request)
    {
        $locale = session('locale', app()->getLocale() ?: 'si');
        $types = OrganizationType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        $query = CommunityOrganization::with(['organizationType', 'user', 'translations'])
            ->where('status', 'published')
            ->latest('published_at');

        if ($request->filled('type')) {
            $query->whereHas('organizationType', fn ($q) => $q->where('slug', $request->type));
        }

        $organizations = $query->paginate(12)->withQueryString();

        return view('organizations.index', compact('organizations', 'types', 'locale'));
    }

    public function show(string $slug)
    {
        $locale = session('locale', app()->getLocale() ?: 'si');

        $organization = CommunityOrganization::with(['organizationType', 'user', 'translations', 'reviewer'])
            ->where('status', 'published')
            ->whereHas('translations', fn ($q) => $q->where('slug', $slug))
            ->first();

        if (! $organization && is_numeric($slug)) {
            $organization = CommunityOrganization::with(['organizationType', 'user', 'translations', 'reviewer'])
                ->where('status', 'published')
                ->find($slug);
        }

        if (! $organization) {
            abort(404);
        }

        $translation = $organization->translationFor($locale);

        return view('organizations.show', compact('organization', 'translation', 'locale'));
    }
}
