<?php

namespace App\Http\Controllers;

use App\Models\VatReturn;
use Illuminate\Http\Request;

class VatReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = VatReturn::orderBy('period', 'desc')->paginate(15);

        $stats = [
            'total_return_amount' => VatReturn::sum('calculated_return_amount'),
            'total_kdv20_purchases' => VatReturn::sum('total_kdv20_purchases'),
            'approved_count' => VatReturn::where('ymm_report_status', 'onaylandi')->count(),
            'submitted_count' => VatReturn::where('ymm_report_status', 'sunuldu')->count(),
        ];

        return view('vat_returns.index', compact('items', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'period' => 'required|string',
            'project_type' => 'required|string',
            'total_kdv1_sales' => 'nullable|numeric|min:0',
            'total_kdv10_sales' => 'nullable|numeric|min:0',
            'total_kdv20_purchases' => 'nullable|numeric|min:0',
            'ymm_report_status' => 'required|in:hazirlaniyor,sunuldu,onaylandi',
        ]);

        $kdv1Sales = floatval($validated['total_kdv1_sales'] ?? 0);
        $kdv10Sales = floatval($validated['total_kdv10_sales'] ?? 0);
        $kdv20Purchases = floatval($validated['total_kdv20_purchases'] ?? 0);

        // KDV İade Alacağı Hesabı: Yüklenilen KDV (%20 alımların %20'si) - Satış KDV'si (%1 ve %10 KDV'ler)
        $salesKdv = ($kdv1Sales * 0.01) + ($kdv10Sales * 0.10);
        $purchasesKdv = $kdv20Purchases * 0.20;
        $calculatedReturn = max(0, $purchasesKdv - $salesKdv);

        VatReturn::create([
            'period' => $validated['period'],
            'project_type' => $validated['project_type'],
            'total_kdv1_sales' => $kdv1Sales,
            'total_kdv10_sales' => $kdv10Sales,
            'total_kdv20_purchases' => $kdv20Purchases,
            'calculated_return_amount' => $calculatedReturn,
            'ymm_report_status' => $validated['ymm_report_status'],
        ]);

        return back()->with('success', 'KDV İade ve Teşvik Rapor kaydı oluşturuldu.');
    }

    public function update(Request $request, VatReturn $vatReturn)
    {
        $validated = $request->validate([
            'ymm_report_status' => 'required|in:hazirlaniyor,sunuldu,onaylandi',
        ]);

        $vatReturn->update($validated);

        return back()->with('success', 'YMM KDV İade rapor durumu güncellendi.');
    }

    public function destroy(VatReturn $vatReturn)
    {
        $vatReturn->delete();
        return back()->with('success', 'KDV iade kaydı silindi.');
    }
}
