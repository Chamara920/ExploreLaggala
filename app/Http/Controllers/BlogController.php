<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogPostTranslation;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Public Blog Index
     */
    public function index(Request $request)
    {
        $locale = session('locale', app()->getLocale() ?: 'si');

        if (! in_array($locale, ['si', 'en', 'ta'], true)) {
            $locale = 'si';
        }

        /*
        |--------------------------------------------------------------------------
        | Published Posts
        |--------------------------------------------------------------------------
        */

        $query = BlogPost::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with([
                'user',
                'category',
                'translations',
            ])
            ->latest('published_at');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        $selectedCategory = $request->input('category');
        if ($request->filled('category')) {
            $cat = $request->input('category');
            $query->where(function ($q) use ($cat) {
                $q->where('category_id', $cat)
                    ->orWhereHas('category', function ($cq) use ($cat) {
                        $cq->where('slug', $cat);
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Featured Post (Fallback to latest if none marked featured)
        |--------------------------------------------------------------------------
        */

        $featuredPost = BlogPost::query()
            ->where('status', 'published')
            ->where('featured', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with([
                'user',
                'category',
                'translations',
            ])
            ->latest('published_at')
            ->first();

        if (! $featuredPost && ! $request->filled('category') && ! $request->filled('search')) {
            $featuredPost = (clone $query)->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Posts
        |--------------------------------------------------------------------------
        */

        $posts = $query
            ->paginate(9)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Categories with published posts count
        |--------------------------------------------------------------------------
        */

        $categories = BlogCategory::query()
            ->where('is_active', true)
            ->withCount(['blogPosts' => function ($q) {
                $q->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
            }])
            ->orderBy('sort_order')
            ->get();

        $totalPostsCount = BlogPost::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();

        $search = $request->input('search');

        return view('blog.index', compact(
            'posts',
            'featuredPost',
            'categories',
            'locale',
            'selectedCategory',
            'search',
            'totalPostsCount'
        ));
    }

    /**
     * Public Single Blog Post
     */
    public function show(string $slug)
    {
        $locale = session('locale', app()->getLocale() ?: 'si');

        if (! in_array($locale, ['si', 'en', 'ta'], true)) {
            $locale = 'si';
        }

        $translation = BlogPostTranslation::query()
            ->where('slug', $slug)
            ->whereHas('blogPost', function ($query) {
                $query
                    ->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
            })
            ->with([
                'blogPost.user',
                'blogPost.category',
                'blogPost.translations',
            ])
            ->first();

        if (! $translation && is_numeric($slug)) {
            $post = BlogPost::where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->find($slug);
            if ($post) {
                $translation = $post->translationFor($locale) ?? $post->translations->first();
            }
        }

        if (! $translation) {
            abort(404);
        }

        $post = $translation->blogPost;

        /*
        |--------------------------------------------------------------------------
        | Requested language / fallback
        |--------------------------------------------------------------------------
        */

        $localizedTranslation = $post->translationFor($locale);

        if ($localizedTranslation) {
            $translation = $localizedTranslation;
        }

        /*
        |--------------------------------------------------------------------------
        | Sanitize Rich Text HTML
        |--------------------------------------------------------------------------
        */

        $sanitizedContent = clean($translation->content);

        /*
        |--------------------------------------------------------------------------
        | Increment Views
        |--------------------------------------------------------------------------
        */

        $post->increment('views');

        return view('blog.show', compact(
            'post',
            'translation',
            'locale',
            'sanitizedContent'
        ));
    }
}
