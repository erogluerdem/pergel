<?php

namespace App\Http\Controllers;

use App\Models\PromissoryNote;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PromissoryNoteTimelineController extends Controller
{
    public function index(Request $request)
    {
        $query = PromissoryNote::orderBy('due_date', 'asc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $notes = $query->get();

        // Calculate summary metrics
        $totalAmount = $notes->sum('amount');
        $paidAmount = $notes->where('status', 'Ödendi')->sum('amount');
        $pendingAmount = $notes->where('status', 'Bekliyor')->sum('amount');
        $overdueCount = $notes->filter(function($n) {
            return $n->status === 'Bekliyor' && Carbon::parse($n->due_date)->isPast();
        })->count();

        // Group notes by month for timeline presentation
        $groupedTimeline = $notes->groupBy(function($n) {
            return Carbon::parse($n->due_date)->translatedFormat('F Y');
        });

        return view('promissory_notes.timeline', compact('notes', 'groupedTimeline', 'totalAmount', 'paidAmount', 'pendingAmount', 'overdueCount'));
    }
}
