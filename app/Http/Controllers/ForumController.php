<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumReply;
use App\Models\ForumTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        $locale = session('locale', app()->getLocale() ?: 'si');

        $categories = ForumCategory::where('is_active', true)
            ->withCount('topics')
            ->orderBy('sort_order')
            ->get();

        $query = ForumTopic::with(['category', 'user'])
            ->withCount('replies')
            ->latest();

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        $topics = $query->paginate(20)->withQueryString();

        return view('forum.index', compact('topics', 'categories', 'locale'));
    }

    public function show(string $slug)
    {
        $locale = session('locale', app()->getLocale() ?: 'si');

        $topic = ForumTopic::with(['category', 'user', 'replies.user'])
            ->where('slug', $slug)
            ->first();

        if (! $topic && is_numeric($slug)) {
            $topic = ForumTopic::with(['category', 'user', 'replies.user'])
                ->find($slug);
        }

        if (! $topic) {
            abort(404);
        }

        $topic->increment('views');

        return view('forum.show', compact('topic', 'locale'));
    }

    public function create()
    {
        $locale = session('locale', app()->getLocale() ?: 'si');
        $categories = ForumCategory::where('is_active', true)->orderBy('sort_order')->get();

        return view('forum.create', compact('categories', 'locale'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:forum_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
        ]);

        $slug = Str::slug($validated['title']);
        if (empty($slug)) {
            $slug = preg_replace('/[^\pL\pM\pN]+/u', '-', mb_strtolower($validated['title']));
        }
        $base = $slug;
        $i = 1;
        while (ForumTopic::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        $topic = ForumTopic::create([
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
        ]);

        return redirect()->route('community.forum.show', $topic->slug)
            ->with('success', 'ඔබේ Topic සාර්ථකව ප්‍රකාශිත කරන ලදී!');
    }

    public function storeReply(Request $request, ForumTopic $topic)
    {
        if ($topic->is_locked) {
            return back()->with('error', 'මෙම Topic lock කර ඇත.');
        }

        $validated = $request->validate([
            'content' => 'required|string|min:5',
        ]);

        ForumReply::create([
            'topic_id' => $topic->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return redirect()->route('community.forum.show', $topic->slug)
            ->with('success', 'Reply ඇතුළත් කරන ලදී!');
    }

    public function destroyTopic(ForumTopic $topic)
    {
        $this->authorize('delete', $topic);
        $topic->delete();

        return redirect()->route('community.forum.index')
            ->with('success', 'Topic මකා දමන ලදී.');
    }

    public function destroyReply(ForumReply $reply)
    {
        $this->authorize('delete', $reply);
        $reply->delete();

        return back()->with('success', 'Reply මකා දමන ලදී.');
    }
}
