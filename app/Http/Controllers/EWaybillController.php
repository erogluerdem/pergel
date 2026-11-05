<?php

namespace App\Http\Controllers;

use App\Models\EWaybill;
use Illuminate\Http\Request;

class EWaybillController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = EWaybill::orderBy('issue_date', 'desc')->paginate(15);

        $stats = [
            'total_waybills' => EWaybill::count(),
            'approved_count' => EWaybill::where('status', 'onaylandi')->count(),
            'pending_count' => EWaybill::where('status', 'bekliyor')->count(),
            'invoiced_count' => EWaybill::where('status', 'faturaya_donustu')->count(),
        ];

        return view('ewaybills.index', compact('items', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'waybill_number' => 'required|string|unique:e_waybills,waybill_number',
            'issue_date' => 'required|date',
            'supplier_name' => 'required|string',
            'supplier_vkn_tckn' => 'nullable|string',
            'material_description' => 'required|string',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string',
            'status' => 'required|in:bekliyor,onaylandi,reddedildi,faturaya_donustu',
        ]);

        EWaybill::create($validated);

        return back()->with('success', 'E-İrsaliye şantiye girişi kaydedildi.');
    }

    public function update(Request $request, EWaybill $ewaybill)
    {
        $validated = $request->validate([
            'status' => 'required|in:bekliyor,onaylandi,reddedildi,faturaya_donustu',
        ]);

        $ewaybill->update($validated);

        return back()->with('success', 'E-İrsaliye durumu güncellendi.');
    }

    public function destroy(EWaybill $ewaybill)
    {
        $ewaybill->delete();
        return back()->with('success', 'E-İrsaliye kaydı silindi.');
    }
}
