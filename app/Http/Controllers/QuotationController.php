<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Requisition;
use App\Models\Vendor;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $query = Quotation::with('vendor', 'requisition', 'createdBy');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        $quotations = $query->orderBy('created_at', 'desc')->paginate(20);
        $vendors = Vendor::all();
        $statuses = ['active', 'approved', 'rejected', 'expired'];

        return view('purchasing.quotations.index', compact('quotations', 'vendors', 'statuses'));
    }

    public function create(Request $request)
    {
        $requisition = Requisition::find($request->requisition_id);
        $vendors = Vendor::all();

        return view('purchasing.quotations.create', compact('requisition', 'vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'requisition_id' => 'required|exists:requisitions,id',
            'vendor_id' => 'required|exists:vendors,id',
            'quote_number' => 'required|unique:quotations',
            'quote_date' => 'required|date',
            'valid_until' => 'required|date|after:quote_date',
            'total_amount' => 'required|numeric|min:0'
        ]);

        $quotation = Quotation::create([
            ...$request->all(),
            'created_by' => auth()->id()
        ]);

        return redirect()->route('purchasing.quotations.show', $quotation)
                       ->with('success', 'Teklif başarıyla oluşturuldu');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load('vendor', 'requisition', 'items');

        return view('purchasing.quotations.show', compact('quotation'));
    }

    public function approve(Quotation $quotation)
    {
        $quotation->markAsApproved();

        return back()->with('success', 'Teklif onaylandı');
    }

    public function reject(Quotation $quotation)
    {
        $quotation->markAsRejected();

        return back()->with('success', 'Teklif reddedildi');
    }
}
