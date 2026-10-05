<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\InstitutionReview;
use Illuminate\Http\Request;

class InstitutionReviewController extends Controller
{
    /**
     * Store or update a user's review and rating for a government institution.
     */
    public function store(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $existing = InstitutionReview::where('institution_id', $institution->id)
            ->where('user_id', $request->user()->id)
            ->first();

        // Admin and super_admin reviews are auto-approved, others are pending moderation
        $isStaff = $request->user()->hasAnyRole(['admin', 'super_admin']);

        InstitutionReview::updateOrCreate(
            [
                'institution_id' => $institution->id,
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
            ? (__('institutions.review_updated') ?: 'Thank you. Your feedback has been updated and is awaiting admin approval.')
            : (__('institutions.review_submitted') ?: 'Thank you. Your feedback has been submitted and will appear after admin approval.');

        if ($isStaff) {
            $message = __('institutions.review_published') ?: 'Your review has been published.';
        }

        return back()->with('review_success', $message);
    }

    /**
     * Remove a review (Admin / Super Admin action only).
     */
    public function destroy(Request $request, InstitutionReview $review)
    {
        $user = $request->user();

        if (! $user || ! $user->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $review->delete();

        return back()->with('review_deleted', __('institutions.review_deleted') ?: 'Review successfully removed.');
    }
}
