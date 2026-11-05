<?php

namespace App\Http\Controllers;

use App\Models\WasteManagement;
use Illuminate\Http\Request;

class WasteManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = WasteManagement::orderBy('disposal_date', 'desc')->paginate(15);

        $totalTons = WasteManagement::sum('quantity_tons');
        $recyclableTons = WasteManagement::whereIn('waste_type', ['Hurda Demir / Metal', 'Ambalaj & Plastik Atık'])->sum('quantity_tons');
        $hazardousTons = WasteManagement::where('waste_type', 'LIKE', '%Tehlikeli%')->orWhere('waste_type', 'LIKE', '%Asbest%')->sum('quantity_tons');
        $motatCount = WasteManagement::whereNotNull('motat_receipt_no')->count();

        $stats = [
            'total_waste_tons' => $totalTons,
            'recyclable_waste_tons' => $recyclableTons,
            'hazardous_waste_tons' => $hazardousTons,
            'motat_receipts_count' => $motatCount,
            'total_disposals' => WasteManagement::count(),
        ];

        return view('waste_managements.index', compact('items', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'disposal_date' => 'required|date',
            'waste_type' => 'required|string',
            'quantity_tons' => 'required|numeric|min:0.01',
            'disposal_facility' => 'required|string',
            'motat_receipt_no' => 'nullable|string',
            'environmental_officer' => 'required|string',
            'status' => 'required|in:teslim_edildi,onaylandi,tasinmada',
        ]);

        WasteManagement::create($validated);

        return back()->with('success', 'ÇED & MoTAT Şantiye Atık Beyanı başarıyla kaydedildi.');
    }

    public function update(Request $request, WasteManagement $wasteManagement)
    {
        $validated = $request->validate([
            'status' => 'required|in:teslim_edildi,onaylandi,tasinmada',
        ]);

        $wasteManagement->update($validated);

        return back()->with('success', 'Atık kaydı durumu güncellendi.');
    }

    public function destroy(WasteManagement $wasteManagement)
    {
        $wasteManagement->delete();
        return back()->with('success', 'Atık beyan kaydı silindi.');
    }
}
