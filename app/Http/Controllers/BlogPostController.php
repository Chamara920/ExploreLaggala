<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    use AuthorizesRequests;

    /**
     * Show create form.
     */
    public function create()
    {
        $categories = BlogCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('community.blog-posts.create', compact('categories'));
    }

    /**
     * Store new blog post as draft.
     */
    public function store(Request $request)
    {
        $validated = $this->validatePost($request);

        $this->ensureLanguageContent($request);

        $blogPost = BlogPost::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'] ?? null,
            'status' => 'draft',
            'featured' => false,
            'views' => 0,
        ]);

        $this->saveTranslations($request, $blogPost);

        $this->saveCoverImage($request, $blogPost);

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'Blog post saved as draft successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(BlogPost $blogPost)
    {
        $this->authorize('update', $blogPost);

        $categories = BlogCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $blogPost->load('translations');

        return view('community.blog-posts.edit', compact(
            'blogPost',
            'categories'
        ));
    }

    /**
     * Update blog post.
     */
    public function update(Request $request, BlogPost $blogPost)
    {
        $this->authorize('update', $blogPost);

        $validated = $this->validatePost($request);

        $this->ensureLanguageContent($request);

        $blogPost->update([
            'category_id' => $validated['category_id'] ?? null,
        ]);

        $blogPost->translations()->delete();

        $this->saveTranslations($request, $blogPost);

        $this->saveCoverImage($request, $blogPost);

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'Blog post updated successfully.');
    }

    /**
     * Delete blog post.
     */
    public function destroy(BlogPost $blogPost)
    {
        $this->authorize('delete', $blogPost);

        if ($blogPost->cover_image) {
            Storage::disk('public')->delete($blogPost->cover_image);
        }

        $blogPost->delete();

        return redirect()
            ->route('community.dashboard')
            ->with('success', 'Blog post deleted successfully.');
    }

    /**
     * Submit blog post for admin review.
     */
    public function submitForReview(BlogPost $blogPost)
    {
        $this->authorize('submitForReview', $blogPost);

        $blogPost->update([
            'status' => 'pending_review',
            'rejection_reason' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        return redirect()
            ->route('community.dashboard')
            ->with(
                'success',
                'Blog post submitted for review.'
            );
    }

    /**
     * Validate blog post.
     */
    private function validatePost(Request $request): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'exists:blog_categories,id'],

            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

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

    /**
     * Make sure at least one language has content.
     */
    private function ensureLanguageContent(Request $request): void
    {
        foreach (['si', 'en', 'ta'] as $locale) {
            if (
                filled($request->input("$locale.title")) &&
                filled($request->input("$locale.content"))
            ) {
                return;
            }
        }

        abort(
            redirect()
                ->back()
                ->withErrors([
                    'languages' => 'Please provide a title and content in at least one language.',
                ])
                ->withInput()
        );
    }

    /**
     * Save translations.
     */
    private function saveTranslations(
        Request $request,
        BlogPost $blogPost
    ): void {
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

            $blogPost->translations()->create([
                'locale' => $locale,
                'title' => $title,
                'slug' => $slug,
                'excerpt' => $request->input("$locale.excerpt"),
                'content' => $request->input("$locale.content"),
            ]);
        }
    }

    /**
     * Save cover image.
     */
    private function saveCoverImage(
        Request $request,
        BlogPost $blogPost
    ): void {
        if (! $request->hasFile('cover_image')) {
            return;
        }

        if ($blogPost->cover_image) {
            Storage::disk('public')->delete($blogPost->cover_image);
        }

        $path = $request->file('cover_image')
            ->store('blog/covers', 'public');

        $blogPost->update([
            'cover_image' => $path,
        ]);
    }
}
