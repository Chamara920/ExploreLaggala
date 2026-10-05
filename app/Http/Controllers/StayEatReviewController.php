<?php

namespace App\Http\Controllers;

use App\Models\StayEatItem;
use App\Models\StayEatItemReview;
use Illuminate\Http\Request;

class StayEatReviewController extends Controller
{
    /**
     * Store or update a user's review for a stay & eat item.
     */
    public function store(Request $request, StayEatItem $item)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $existing = StayEatItemReview::where('stay_eat_item_id', $item->id)
            ->where('user_id', $request->user()->id)
            ->first();

        // Admin and super_admin reviews are auto-approved, others are pending moderation
        $isStaff = $request->user()->hasAnyRole(['admin', 'super_admin']);

        StayEatItemReview::updateOrCreate(
            [
                'stay_eat_item_id' => $item->id,
                'user_id' => $request->user()->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'status' => $isStaff ? 'approved' : 'pending',
                'reviewed_by' => $isStaff ? $request->user()->id : null,
                'reviewed_at' => $isStaff ? now() : null,
            ]
        );

        $message = $existing
            ? (__('stay_eat.review_updated') ?: 'Thank you. Your review has been updated and is awaiting admin approval.')
            : (__('stay_eat.review_submitted') ?: 'Thank you. Your review has been submitted and is awaiting admin approval.');

        if ($isStaff) {
            $message = __('stay_eat.review_published') ?: 'Your review has been published.';
        }

        return back()->with('review_success', $message);
    }

    /**
     * Remove a review (Admin / Super Admin action).
     */
    public function destroy(Request $request, StayEatItemReview $review)
    {
        $user = $request->user();

        if (! $user || ! $user->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $review->delete();

        return back()->with('review_deleted', __('stay_eat.review_deleted') ?: 'Review successfully removed.');
    }
}
