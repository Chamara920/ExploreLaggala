<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use App\Models\NewsPost;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsSubmissionController extends Controller
{
    use AuthorizesRequests;

    public function create()
    {
        $categories = NewsCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('community.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateNews($request);
        $this->ensureLanguageContent($request);

        $newsPost = NewsPost::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'] ?? null,
            'status' => 'draft',
            'featured' => false,
            'views' => 0,
        ]);

        $this->saveTranslations($request, $newsPost);
        $this->saveCoverImage($request, $newsPost);

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'News article saved as draft successfully.');
    }

    public function edit(NewsPost $newsPost)
    {
        $this->authorize('update', $newsPost);

        $categories = NewsCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $newsPost->load('translations');

        return view('community.news.edit', compact('newsPost', 'categories'));
    }

    public function update(Request $request, NewsPost $newsPost)
    {
        $this->authorize('update', $newsPost);

        $validated = $this->validateNews($request);
        $this->ensureLanguageContent($request);

        $newsPost->update([
            'category_id' => $validated['category_id'] ?? null,
        ]);

        $newsPost->translations()->delete();
        $this->saveTranslations($request, $newsPost);
        $this->saveCoverImage($request, $newsPost);

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'News article updated successfully.');
    }

    public function destroy(NewsPost $newsPost)
    {
        $this->authorize('delete', $newsPost);

        if ($newsPost->cover_image) {
            Storage::disk('public')->delete($newsPost->cover_image);
        }

        $newsPost->delete();

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'News article deleted successfully.');
    }

    public function submitForReview(NewsPost $newsPost)
    {
        $this->authorize('submitForReview', $newsPost);

        $newsPost->update([
            'status' => 'pending_review',
            'rejection_reason' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'News article submitted for admin review.');
    }

    private function validateNews(Request $request): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'exists:news_categories,id'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'si.title' => ['nullable', 'string', 'max:255'],
            'si.slug' => ['nullable', 'string', 'max:255'],
            'si.excerpt' => ['nullable', 'string', 'max:1000'],
            'si.content' => ['nullable', 'string'],
            'en.title' => ['nullable', 'string', 'max:255'],
            'en.slug' => ['nullable', 'string', 'max:255'],
            'en.excerpt' => ['nullable', 'string', 'max:1000'],
            'en.content' => ['nullable', 'string'],
            'ta.title' => ['nullable', 'string', 'max:255'],
            'ta.slug' => ['nullable', 'string', 'max:255'],
            'ta.excerpt' => ['nullable', 'string', 'max:1000'],
            'ta.content' => ['nullable', 'string'],
        ]);
    }

    private function ensureLanguageContent(Request $request): void
    {
        foreach (['si', 'en', 'ta'] as $locale) {
            if (filled($request->input("$locale.title")) && filled($request->input("$locale.content"))) {
                return;
            }
        }

        abort(
            redirect()
                ->back()
                ->withErrors(['languages' => 'Please provide a title and content in at least one language.'])
                ->withInput()
        );
    }

    private function saveTranslations(Request $request, NewsPost $newsPost): void
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

            $newsPost->translations()->create([
                'locale' => $locale,
                'title' => $title,
                'slug' => $slug,
                'excerpt' => $request->input("$locale.excerpt"),
                'content' => $request->input("$locale.content"),
            ]);
        }
    }

    private function saveCoverImage(Request $request, NewsPost $newsPost): void
    {
        if (! $request->hasFile('cover_image')) {
            return;
        }

        if ($newsPost->cover_image) {
            Storage::disk('public')->delete($newsPost->cover_image);
        }

        $path = $request->file('cover_image')->store('news/covers', 'public');
        $newsPost->update(['cover_image' => $path]);
    }
}
