<?php

namespace App\Http\Controllers;

use App\Models\ExploreItem;
use App\Models\ExploreItemReview;
use Illuminate\Http\Request;

class ExploreReviewController extends Controller
{
    /**
     * Store or update a user's review for an explore item.
     */
    public function store(Request $request, ExploreItem $item)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $existing = ExploreItemReview::where('explore_item_id', $item->id)
            ->where('user_id', $request->user()->id)
            ->first();

        ExploreItemReview::updateOrCreate(
            [
                'explore_item_id' => $item->id,
                'user_id' => $request->user()->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'status' => 'pending',
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]
        );

        $message = $existing
            ? (__('explore.review_updated') ?: 'Thank you. Your review has been updated and is awaiting admin approval.')
            : (__('explore.review_submitted') ?: 'Thank you. Your review has been submitted and is awaiting admin approval.');

        return back()->with('review_success', $message);
    }

    /**
     * Remove a review (Admin / Super Admin action).
     */
    public function destroy(Request $request, ExploreItemReview $review)
    {
        $user = $request->user();

        if (! $user || ! $user->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $review->delete();

        return back()->with('review_deleted', __('explore.review_deleted') ?: 'Review successfully removed.');
    }
}
