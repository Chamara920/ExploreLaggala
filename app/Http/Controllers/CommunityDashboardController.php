<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CommunityOrganization;
use App\Models\Event;
use App\Models\ForumTopic;
use App\Models\NewsPost;
use Illuminate\Http\Request;

class CommunityDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Posts by category
        $blogPosts = BlogPost::with(['category', 'translations'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $newsPosts = NewsPost::with(['category', 'translations'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $events = Event::with(['category', 'translations'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $organizations = CommunityOrganization::with(['type', 'translations'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $forumTopics = ForumTopic::with(['category', 'replies'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Statistics across all user submissions
        $draftCount = $blogPosts->where('status', 'draft')->count()
            + $newsPosts->where('status', 'draft')->count()
            + $events->where('status', 'draft')->count()
            + $organizations->where('status', 'draft')->count();

        $pendingCount = $blogPosts->where('status', 'pending_review')->count()
            + $newsPosts->where('status', 'pending_review')->count()
            + $events->where('status', 'pending_review')->count()
            + $organizations->where('status', 'pending_review')->count();

        $publishedCount = $blogPosts->where('status', 'published')->count()
            + $newsPosts->where('status', 'published')->count()
            + $events->where('status', 'published')->count()
            + $organizations->where('status', 'published')->count()
            + $forumTopics->count();

        return view('community.dashboard', compact(
            'user',
            'draftCount',
            'pendingCount',
            'publishedCount',
            'blogPosts',
            'newsPosts',
            'events',
            'organizations',
            'forumTopics'
        ));
    }
}
