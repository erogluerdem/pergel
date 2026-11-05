<?php

namespace App\Http\Controllers\SubcontractorPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubcontractorBid;
// use App\Models\Tender; // Assuming this exists

class TenderBiddingController extends Controller
{
    public function index()
    {
        $subcontractorId = Auth::guard('subcontractor')->id() ?? auth()->user()?->subcontractor_id ?? 1;
        $bids = SubcontractorBid::where('subcontractor_id', $subcontractorId)->get();
        return view('subcontractor-portal.bids.index', compact('bids'));
    }

    public function create()
    {
        // $tenders = Tender::where('status', 'acik')->get();
        $tenders = collect([
            (object)['id' => 1, 'title' => 'C Blok Elektrik İşleri', 'description' => 'Tüm kaba elektrik tesisatı'],
            (object)['id' => 2, 'title' => 'A Blok Alçı Sıva', 'description' => 'İnce işler alçı sıva taşeronluğu'],
        ]);
        return view('subcontractor-portal.bids.create', compact('tenders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tender_id' => 'required',
            'bid_amount' => 'required|numeric',
        ]);

        $subcontractorId = Auth::guard('subcontractor')->id() ?? auth()->user()?->subcontractor_id ?? 1;

        SubcontractorBid::create([
            'subcontractor_id' => $subcontractorId,
            'tender_id' => $request->tender_id,
            'bid_amount' => $request->bid_amount,
            'currency' => 'TRY',
            'notes' => $request->notes,
            'status' => 'degerlendirmede',
        ]);

        return redirect()->route('subcontractor.bids.index')->with('success', 'Teklifiniz başarıyla iletildi.');
    }
}
