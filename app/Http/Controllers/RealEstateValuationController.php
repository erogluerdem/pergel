<?php

namespace App\Http\Controllers;

use App\Models\RealEstateValuation;
use Illuminate\Http\Request;

class RealEstateValuationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = RealEstateValuation::orderBy('report_date', 'desc')->paginate(15);

        $totalCurrent = RealEstateValuation::sum('current_value');
        $totalPrevious = RealEstateValuation::sum('previous_value');
        $growthAmount = $totalCurrent - $totalPrevious;
        $growthRate = $totalPrevious > 0 ? (($totalCurrent / $totalPrevious) - 1) * 100 : 0;

        $stats = [
            'total_current_value' => $totalCurrent,
            'total_previous_value' => $totalPrevious,
            'growth_amount' => $growthAmount,
            'growth_rate' => $growthRate,
            'total_count' => RealEstateValuation::count(),
        ];

        return view('real_estate_valuations.index', compact('items', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_name' => 'required|string',
            'valuation_company' => 'required|string',
            'spk_license_number' => 'nullable|string',
            'report_date' => 'required|date',
            'previous_value' => 'nullable|numeric|min:0',
            'current_value' => 'required|numeric|min:0',
        ]);

        RealEstateValuation::create($validated);

        return back()->with('success', 'SPK Gayrimenkul Değerleme Rapor kaydı oluşturuldu.');
    }

    public function update(Request $request, RealEstateValuation $realEstateValuation)
    {
        $validated = $request->validate([
            'current_value' => 'required|numeric|min:0',
        ]);

        $realEstateValuation->update($validated);

        return back()->with('success', 'Değerleme raporu güncellendi.');
    }

    public function destroy(RealEstateValuation $realEstateValuation)
    {
        $realEstateValuation->delete();
        return back()->with('success', 'Değerleme kaydı silindi.');
    }
}
