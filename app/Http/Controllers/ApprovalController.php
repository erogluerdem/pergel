<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\Requisition;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = Approval::with('requisition', 'approver');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('approval_level')) {
            $query->where('approval_level', $request->approval_level);
        }

        $approvals = $query->orderBy('created_at', 'desc')->paginate(20);
        $levels = Approval::APPROVAL_LEVELS;

        $totalApprovals = Approval::count();
        $pendingApprovals = Approval::where('status', 'pending')->count();
        $pendingCount = $pendingApprovals;
        $approvedCount = Approval::where('status', 'approved')->count();
        $rejectedCount = Approval::where('status', 'rejected')->count();

        return view('purchasing.approvals.index', compact(
            'approvals',
            'levels',
            'totalApprovals',
            'pendingApprovals',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    public function pending()
    {
        $approvals = Approval::pending()
                            ->where('approver_id', auth()->id())
                            ->with('requisition', 'approver')
                            ->orderBy('created_at', 'desc')
                            ->paginate(20);

        $levels = Approval::APPROVAL_LEVELS;
        $totalApprovals = Approval::count();
        $pendingApprovals = Approval::where('status', 'pending')->count();
        $pendingCount = $pendingApprovals;
        $approvedCount = Approval::where('status', 'approved')->count();
        $rejectedCount = Approval::where('status', 'rejected')->count();

        return view('purchasing.approvals.pending', compact(
            'approvals',
            'levels',
            'totalApprovals',
            'pendingApprovals',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    public function show(Approval $approval)
    {
        if (!$approval->exists) {
            $approval = Approval::findOrFail(request()->route('approval') ?? request()->route('id'));
        }
        $approval->load('requisition', 'approver');

        return view('purchasing.approvals.show', compact('approval'));
    }

    public function approve(Request $request, Approval $approval)
    {
        $request->validate([
            'comments' => 'nullable|string'
        ]);

        $approval->approve($request->comments);

        // Check if all approvals are complete
        $requisition = $approval->requisition;
        $allApproved = $requisition->approvals()
                                   ->where('status', '!=', 'approved')
                                   ->doesntExist();

        if ($allApproved) {
            $requisition->update(['status' => 'approved']);
        }

        return back()->with('success', 'Talep onaylandı');
    }

    public function reject(Request $request, Approval $approval)
    {
        $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        $approval->reject($request->rejection_reason);
        $approval->requisition->update(['status' => 'rejected']);

        return back()->with('success', 'Talep reddedildi');
    }

    public function dashboard()
    {
        $pendingApprovals = Approval::pending()
                                   ->where('approver_id', auth()->id())
                                   ->count();

        $approvedToday = Approval::where('approver_id', auth()->id())
                                ->where('status', 'approved')
                                ->whereDate('approved_at', now())
                                ->count();

        $rejectedCount = Approval::where('approver_id', auth()->id())
                                ->where('status', 'rejected')
                                ->count();

        return view('purchasing.approvals.dashboard', compact(
            'pendingApprovals',
            'approvedToday',
            'rejectedCount'
        ));
    }
}
