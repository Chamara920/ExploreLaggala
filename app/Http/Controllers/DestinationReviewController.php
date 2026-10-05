<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\DestinationReview;
use Illuminate\Http\Request;

class DestinationReviewController extends Controller
{
    public function store(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $existing = DestinationReview::where('destination_id', $destination->id)
            ->where('user_id', $request->user()->id)
            ->first();

        DestinationReview::updateOrCreate(
            [
                'destination_id' => $destination->id,
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
            ? 'Thank you. Your review has been updated and is awaiting approval.'
            : 'Thank you. Your review has been submitted and is awaiting approval.';

        return back()->with('review_success', $message);
    }
}
