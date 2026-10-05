<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_type' => 'required|string',
            'reportable_id' => 'required|integer',
            'reason' => 'required|string|min:5|max:1000',
        ]);

        Report::create([
            'user_id' => Auth::id(),
            'reportable_type' => $validated['reportable_type'],
            'reportable_id' => $validated['reportable_id'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'ඔබගේ Report කිරීම සාර්ථකව පද්ධතියට ඇතුළත් විය. Admin පරීක්ෂා කර ක්‍රියාමාර්ග ගනු ඇත.');
    }
}
