<?php

namespace App\Http\Controllers;

use App\Models\NewsCategory;
use App\Models\NewsPost;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $locale = session('locale', app()->getLocale() ?: 'si');

        $categories = NewsCategory::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        $query = NewsPost::with(['category', 'user', 'translations'])
            ->where('status', 'published')
            ->latest('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        $newsPosts = $query->paginate(12)->withQueryString();

        return view('news.index', compact('newsPosts', 'categories', 'locale'));
    }

    public function show(string $slug)
    {
        $locale = session('locale', app()->getLocale() ?: 'si');

        $newsPost = NewsPost::with(['category', 'user', 'translations', 'reviewer'])
            ->where('status', 'published')
            ->whereHas('translations', fn ($q) => $q->where('slug', $slug))
            ->first();

        if (! $newsPost && is_numeric($slug)) {
            $newsPost = NewsPost::with(['category', 'user', 'translations', 'reviewer'])
                ->where('status', 'published')
                ->find($slug);
        }

        if (! $newsPost) {
            abort(404);
        }

        $translation = $newsPost->translationFor($locale);

        $related = NewsPost::with(['category', 'translations'])
            ->where('status', 'published')
            ->where('category_id', $newsPost->category_id)
            ->where('id', '!=', $newsPost->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('news.show', compact('newsPost', 'translation', 'related', 'locale'));
    }
}
