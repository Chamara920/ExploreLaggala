<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'content' => 'required|string|min:2|max:2000',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'commentable_type' => $validated['commentable_type'],
            'commentable_id' => $validated['commentable_id'],
            'content' => $validated['content'],
            'is_approved' => true,
        ]);

        return back()->with('success', 'ඔබේ අදහස/Comment සාර්ථකව එක් කරන ලදී.');
    }

    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id && ! Auth::user()->hasAnyRole(['admin', 'super_admin'])) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comment සාර්ථකව මකා දමන ලදී.');
    }
}
