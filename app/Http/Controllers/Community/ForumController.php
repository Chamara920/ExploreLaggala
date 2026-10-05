<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use App\Models\ForumReply;
use App\Models\ForumTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    public function create()
    {
        $categories = ForumCategory::orderBy('name')->get();

        return view('community.forum.create', compact('categories'));
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

        // Ensure slug uniqueness
        $baseSlug = $slug;
        $i = 1;
        while (ForumTopic::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$i++;
        }

        ForumTopic::create([
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'is_pinned' => false,
            'is_locked' => false,
        ]);

        return redirect()->route('community.dashboard')
            ->with('success', 'ඔබේ Forum Topic සාර්ථකව ප්‍රකාශිත කරන ලදී.');
    }

    public function destroy(ForumTopic $topic)
    {
        $this->authorize('delete', $topic);
        $topic->delete();

        return redirect()->route('community.dashboard')
            ->with('success', 'Forum Topic සාර්ථකව මකා දමන ලදී.');
    }

    public function storeReply(Request $request, ForumTopic $topic)
    {
        if ($topic->is_locked) {
            return back()->with('error', 'මෙම Forum Topic lock කර ඇති නිසා Reply කළ නොහැක.');
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
            ->with('success', 'ඔබේ Reply සාර්ථකව ඇතුළත් කරන ලදී.');
    }

    public function destroyReply(ForumReply $reply)
    {
        $this->authorize('delete', $reply);
        $reply->delete();

        return back()->with('success', 'Reply සාර්ථකව මකා දමන ලදී.');
    }
}
