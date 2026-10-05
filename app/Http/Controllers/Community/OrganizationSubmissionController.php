<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\CommunityOrganization;
use App\Models\OrganizationType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrganizationSubmissionController extends Controller
{
    use AuthorizesRequests;

    public function create()
    {
        $types = OrganizationType::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('community.organizations.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateOrg($request);
        $this->ensureLanguageContent($request);

        $org = CommunityOrganization::create([
            'user_id' => Auth::id(),
            'type_id' => $validated['type_id'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'website' => $validated['website'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => 'draft',
            'featured' => false,
            'views' => 0,
        ]);

        $this->saveTranslations($request, $org);
        $this->saveMedia($request, $org);

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'Organization profile saved as draft successfully.');
    }

    public function edit(CommunityOrganization $organization)
    {
        $this->authorize('update', $organization);

        $types = OrganizationType::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $organization->load('translations');

        return view('community.organizations.edit', compact('organization', 'types'));
    }

    public function update(Request $request, CommunityOrganization $organization)
    {
        $this->authorize('update', $organization);

        $validated = $this->validateOrg($request);
        $this->ensureLanguageContent($request);

        $organization->update([
            'type_id' => $validated['type_id'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'website' => $validated['website'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        $organization->translations()->delete();
        $this->saveTranslations($request, $organization);
        $this->saveMedia($request, $organization);

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'Organization profile updated successfully.');
    }

    public function destroy(CommunityOrganization $organization)
    {
        $this->authorize('delete', $organization);

        if ($organization->logo) {
            Storage::disk('public')->delete($organization->logo);
        }
        if ($organization->cover_image) {
            Storage::disk('public')->delete($organization->cover_image);
        }

        $organization->delete();

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'Organization profile deleted successfully.');
    }

    public function submitForReview(CommunityOrganization $organization)
    {
        $this->authorize('submitForReview', $organization);

        $organization->update([
            'status' => 'pending_review',
            'rejection_reason' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'Organization profile submitted for admin review.');
    }

    private function validateOrg(Request $request): array
    {
        return $request->validate([
            'type_id' => ['nullable', 'exists:organization_types,id'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:500'],
            'address' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'si.name' => ['nullable', 'string', 'max:255'],
            'si.slug' => ['nullable', 'string', 'max:255'],
            'si.summary' => ['nullable', 'string', 'max:1000'],
            'si.description' => ['nullable', 'string'],
            'si.services_offered' => ['nullable', 'string'],
            'en.name' => ['nullable', 'string', 'max:255'],
            'en.slug' => ['nullable', 'string', 'max:255'],
            'en.summary' => ['nullable', 'string', 'max:1000'],
            'en.description' => ['nullable', 'string'],
            'en.services_offered' => ['nullable', 'string'],
            'ta.name' => ['nullable', 'string', 'max:255'],
            'ta.slug' => ['nullable', 'string', 'max:255'],
            'ta.summary' => ['nullable', 'string', 'max:1000'],
            'ta.description' => ['nullable', 'string'],
            'ta.services_offered' => ['nullable', 'string'],
        ]);
    }

    private function ensureLanguageContent(Request $request): void
    {
        foreach (['si', 'en', 'ta'] as $locale) {
            if (filled($request->input("$locale.name")) && filled($request->input("$locale.description"))) {
                return;
            }
        }

        abort(
            redirect()
                ->back()
                ->withErrors(['languages' => 'Please provide an organization name and description in at least one language.'])
                ->withInput()
        );
    }

    private function saveTranslations(Request $request, CommunityOrganization $org): void
    {
        foreach (['si', 'en', 'ta'] as $locale) {
            $name = $request->input("$locale.name");
            if (blank($name)) {
                continue;
            }

            $slug = $request->input("$locale.slug");
            if (blank($slug)) {
                $slug = Str::slug($name);
                if (blank($slug)) {
                    $slug = trim(preg_replace('/[^\pL\pM\pN]+/u', '-', mb_strtolower($name)), '-');
                }
            }

            $org->translations()->create([
                'locale' => $locale,
                'name' => $name,
                'slug' => $slug,
                'summary' => $request->input("$locale.summary"),
                'description' => $request->input("$locale.description"),
                'services_offered' => $request->input("$locale.services_offered"),
            ]);
        }
    }

    private function saveMedia(Request $request, CommunityOrganization $org): void
    {
        if ($request->hasFile('logo')) {
            if ($org->logo) {
                Storage::disk('public')->delete($org->logo);
            }
            $logoPath = $request->file('logo')->store('organizations/logos', 'public');
            $org->update(['logo' => $logoPath]);
        }

        if ($request->hasFile('cover_image')) {
            if ($org->cover_image) {
                Storage::disk('public')->delete($org->cover_image);
            }
            $coverPath = $request->file('cover_image')->store('organizations/covers', 'public');
            $org->update(['cover_image' => $coverPath]);
        }
    }
}
