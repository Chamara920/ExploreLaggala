<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CommunityOrganization;
use App\Models\Event;
use App\Models\ForumTopic;
use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Http\Request;

class ContributorsController extends Controller
{
    /**
     * Our Contributors page — list all contributors with post counts.
     * When a user is selected, show that user's published posts.
     */
    public function index(Request $request)
    {
        $locale = app()->getLocale();

        if (! in_array($locale, ['si', 'en', 'ta'])) {
            $locale = 'en';
        }

        /*
        |--------------------------------------------------------------------------
        | Build contributors list with total published post counts
        |--------------------------------------------------------------------------
        */
        $contributors = User::query()
            ->withCount([
                'blogPosts as blog_posts_count' => function ($q) {
                    $q->where('status', 'published');
                },
                'newsPosts as news_posts_count' => function ($q) {
                    $q->where('status', 'published');
                },
                'events as events_count' => function ($q) {
                    $q->where('status', 'published');
                },
                'communityOrganizations as organizations_count' => function ($q) {
                    $q->where('status', 'published');
                },
                'forumTopics as forum_topics_count',
            ])
            ->get()
            ->map(function (User $user) {
                $user->total_posts =
                    $user->blog_posts_count
                    + $user->news_posts_count
                    + $user->events_count
                    + $user->organizations_count
                    + $user->forum_topics_count;

                return $user;
            })
            ->filter(fn (User $user) => $user->total_posts > 0)
            ->sortByDesc('total_posts')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Selected contributor's posts
        |--------------------------------------------------------------------------
        */
        $selectedUser = null;
        $userPosts = collect();

        $selectedUserId = $request->input('user');

        if ($selectedUserId) {
            $selectedUser = User::find($selectedUserId);

            if ($selectedUser) {
                $blogPosts = BlogPost::with(['translations', 'category'])
                    ->where('user_id', $selectedUser->id)
                    ->where('status', 'published')
                    ->latest('published_at')
                    ->get()
                    ->map(function ($post) use ($locale) {
                        $translation = $post->translationFor($locale);

                        return [
                            'type' => 'blog',
                            'icon' => 'fa-newspaper',
                            'label' => 'Blog',
                            'title' => $translation?->title ?? '—',
                            'slug' => $translation?->slug,
                            'route' => 'community.blog.show',
                            'published_at' => $post->published_at,
                        ];
                    });

                $newsPosts = NewsPost::with(['translations', 'category'])
                    ->where('user_id', $selectedUser->id)
                    ->where('status', 'published')
                    ->latest('published_at')
                    ->get()
                    ->map(function ($post) use ($locale) {
                        $translation = $post->translationFor($locale);

                        return [
                            'type' => 'news',
                            'icon' => 'fa-broadcast-tower',
                            'label' => 'News',
                            'title' => $translation?->title ?? '—',
                            'slug' => $translation?->slug,
                            'route' => 'community.news.show',
                            'published_at' => $post->published_at,
                        ];
                    });

                $events = Event::with(['translations', 'category'])
                    ->where('user_id', $selectedUser->id)
                    ->where('status', 'published')
                    ->latest('published_at')
                    ->get()
                    ->map(function ($event) use ($locale) {
                        $translation = $event->translationFor($locale);

                        return [
                            'type' => 'event',
                            'icon' => 'fa-calendar-alt',
                            'label' => 'Event',
                            'title' => $translation?->title ?? '—',
                            'slug' => $translation?->slug,
                            'route' => 'community.events.show',
                            'published_at' => $event->published_at,
                        ];
                    });

                $organizations = CommunityOrganization::with(['translations', 'type'])
                    ->where('user_id', $selectedUser->id)
                    ->where('status', 'published')
                    ->latest()
                    ->get()
                    ->map(function ($org) use ($locale) {
                        $translation = $org->translationFor($locale);

                        return [
                            'type' => 'organization',
                            'icon' => 'fa-building',
                            'label' => 'Organization',
                            'title' => $translation?->name ?? '—',
                            'slug' => $translation?->slug,
                            'route' => 'community.organizations.show',
                            'published_at' => $org->created_at,
                        ];
                    });

                $forumTopics = ForumTopic::with(['category'])
                    ->where('user_id', $selectedUser->id)
                    ->latest()
                    ->get()
                    ->map(function ($topic) {
                        return [
                            'type' => 'forum',
                            'icon' => 'fa-comments',
                            'label' => 'Forum',
                            'title' => $topic->title ?? '—',
                            'slug' => $topic->slug,
                            'route' => 'community.forum.show',
                            'published_at' => $topic->created_at,
                        ];
                    });

                $userPosts = $blogPosts
                    ->merge($newsPosts)
                    ->merge($events)
                    ->merge($organizations)
                    ->merge($forumTopics)
                    ->sortByDesc('published_at')
                    ->values();
            }
        }

        return view('pages.contributors', compact(
            'contributors',
            'selectedUser',
            'userPosts',
            'locale'
        ));
    }
}
