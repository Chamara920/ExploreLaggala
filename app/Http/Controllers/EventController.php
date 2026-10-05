<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $locale = session('locale', app()->getLocale() ?: 'si');
        $categories = EventCategory::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        $query = Event::with(['category', 'user', 'translations'])
            ->where('status', 'published')
            ->orderBy('start_date');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        // Upcoming vs Past filter
        if ($request->get('filter') === 'past') {
            $query->where('start_date', '<', now()->toDateString());
        } else {
            $query->where('start_date', '>=', now()->toDateString());
        }

        $events = $query->paginate(12)->withQueryString();

        return view('events.index', compact('events', 'categories', 'locale'));
    }

    public function show(string $slug)
    {
        $locale = session('locale', app()->getLocale() ?: 'si');

        $event = Event::with(['category', 'user', 'translations', 'reviewer'])
            ->where('status', 'published')
            ->whereHas('translations', fn ($q) => $q->where('slug', $slug))
            ->first();

        if (! $event && is_numeric($slug)) {
            $event = Event::with(['category', 'user', 'translations', 'reviewer'])
                ->where('status', 'published')
                ->find($slug);
        }

        if (! $event) {
            abort(404);
        }

        $translation = $event->translationFor($locale);

        $related = Event::with(['category', 'translations'])
            ->where('status', 'published')
            ->where('category_id', $event->category_id)
            ->where('id', '!=', $event->id)
            ->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date')
            ->limit(3)
            ->get();

        return view('events.show', compact('event', 'translation', 'related', 'locale'));
    }
}
