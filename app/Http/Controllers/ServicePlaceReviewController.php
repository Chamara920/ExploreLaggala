<?php

namespace App\Http\Controllers;

use App\Models\ServicePlace;
use App\Models\ServicePlaceReview;
use Illuminate\Http\Request;

class ServicePlaceReviewController extends Controller
{
    /**
     * Store or update a user's review for a service place.
     */
    public function store(Request $request, ServicePlace $servicePlace)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $existing = ServicePlaceReview::where('service_place_id', $servicePlace->id)
            ->where('user_id', $request->user()->id)
            ->first();

        // Admin and super_admin reviews are auto-approved, others are pending moderation
        $isStaff = $request->user()->hasAnyRole(['admin', 'super_admin']);

        ServicePlaceReview::updateOrCreate(
            [
                'service_place_id' => $servicePlace->id,
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
            ? (__('services.review_updated') ?: 'Thank you! Your review has been updated and is awaiting admin approval.')
            : (__('services.review_submitted') ?: 'Thank you! Your review has been submitted and will appear following administrator approval.');

        if ($isStaff) {
            $message = __('services.review_published') ?: 'Your review has been published.';
        }

        return back()->with('review_success', $message);
    }

    /**
     * Delete review (Admin / Super Admin action only).
     */
    public function destroy(Request $request, ServicePlaceReview $review)
    {
        $user = $request->user();

        if (! $user || ! $user->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $review->delete();

        return back()->with('review_deleted', __('services.review_deleted') ?: 'Review successfully removed.');
    }
}
