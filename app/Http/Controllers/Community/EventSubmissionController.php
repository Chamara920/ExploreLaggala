<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventSubmissionController extends Controller
{
    use AuthorizesRequests;

    public function create()
    {
        $categories = EventCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('community.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateEvent($request);
        $this->ensureLanguageContent($request);

        $event = Event::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'start_time' => $validated['start_time'] ?? null,
            'location_name' => $validated['location_name'] ?? null,
            'google_maps_url' => $validated['google_maps_url'] ?? null,
            'organizer_name' => $validated['organizer_name'] ?? null,
            'organizer_contact' => $validated['organizer_contact'] ?? null,
            'status' => 'draft',
            'featured' => false,
            'views' => 0,
        ]);

        $this->saveTranslations($request, $event);
        $this->saveCoverImage($request, $event);

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'Event saved as draft successfully.');
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        $categories = EventCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $event->load('translations');

        return view('community.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $this->validateEvent($request);
        $this->ensureLanguageContent($request);

        $event->update([
            'category_id' => $validated['category_id'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'start_time' => $validated['start_time'] ?? null,
            'location_name' => $validated['location_name'] ?? null,
            'google_maps_url' => $validated['google_maps_url'] ?? null,
            'organizer_name' => $validated['organizer_name'] ?? null,
            'organizer_contact' => $validated['organizer_contact'] ?? null,
        ]);

        $event->translations()->delete();
        $this->saveTranslations($request, $event);
        $this->saveCoverImage($request, $event);

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->delete();

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'Event deleted successfully.');
    }

    public function submitForReview(Event $event)
    {
        $this->authorize('submitForReview', $event);

        $event->update([
            'status' => 'pending_review',
            'rejection_reason' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'Event submitted for admin review.');
    }

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'exists:event_categories,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable', 'string', 'max:50'],
            'location_name' => ['nullable', 'string', 'max:255'],
            'google_maps_url' => ['nullable', 'url', 'max:500'],
            'organizer_name' => ['nullable', 'string', 'max:255'],
            'organizer_contact' => ['nullable', 'string', 'max:255'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'si.title' => ['nullable', 'string', 'max:255'],
            'si.slug' => ['nullable', 'string', 'max:255'],
            'si.excerpt' => ['nullable', 'string', 'max:1000'],
            'si.description' => ['nullable', 'string'],
            'en.title' => ['nullable', 'string', 'max:255'],
            'en.slug' => ['nullable', 'string', 'max:255'],
            'en.excerpt' => ['nullable', 'string', 'max:1000'],
            'en.description' => ['nullable', 'string'],
            'ta.title' => ['nullable', 'string', 'max:255'],
            'ta.slug' => ['nullable', 'string', 'max:255'],
            'ta.excerpt' => ['nullable', 'string', 'max:1000'],
            'ta.description' => ['nullable', 'string'],
        ]);
    }

    private function ensureLanguageContent(Request $request): void
    {
        foreach (['si', 'en', 'ta'] as $locale) {
            if (filled($request->input("$locale.title")) && filled($request->input("$locale.description"))) {
                return;
            }
        }

        abort(
            redirect()
                ->back()
                ->withErrors(['languages' => 'Please provide a title and description in at least one language.'])
                ->withInput()
        );
    }

    private function saveTranslations(Request $request, Event $event): void
    {
        foreach (['si', 'en', 'ta'] as $locale) {
            $title = $request->input("$locale.title");
            if (blank($title)) {
                continue;
            }

            $slug = $request->input("$locale.slug");
            if (blank($slug)) {
                $slug = Str::slug($title);
                if (blank($slug)) {
                    $slug = trim(preg_replace('/[^\pL\pM\pN]+/u', '-', mb_strtolower($title)), '-');
                }
            }

            $event->translations()->create([
                'locale' => $locale,
                'title' => $title,
                'slug' => $slug,
                'excerpt' => $request->input("$locale.excerpt"),
                'description' => $request->input("$locale.description"),
            ]);
        }
    }

    private function saveCoverImage(Request $request, Event $event): void
    {
        if (! $request->hasFile('cover_image')) {
            return;
        }

        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $path = $request->file('cover_image')->store('events/covers', 'public');
        $event->update(['cover_image' => $path]);
    }
}
