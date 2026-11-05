<?php

namespace App\Http\Controllers\SubcontractorPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubcontractorAdvanceRequest;

class AdvanceRequestController extends Controller
{
    public function index()
    {
        $subcontractorId = Auth::guard('subcontractor')->id() ?? auth()->user()?->subcontractor_id ?? 1;
        $requests = SubcontractorAdvanceRequest::where('subcontractor_id', $subcontractorId)->get();
        return view('subcontractor-portal.advance_requests.index', compact('requests'));
    }

    public function create()
    {
        return view('subcontractor-portal.advance_requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'reason' => 'required|string',
        ]);

        $subcontractorId = Auth::guard('subcontractor')->id() ?? auth()->user()?->subcontractor_id ?? 1;

        SubcontractorAdvanceRequest::create([
            'subcontractor_id' => $subcontractorId,
            'amount' => $request->amount,
            'currency' => 'TRY',
            'reason' => $request->reason,
            'requested_date' => now(),
            'status' => 'bekliyor',
        ]);

        return redirect()->route('subcontractor.advance_requests.index')->with('success', 'Avans talebiniz merkeze iletildi.');
    }
}
