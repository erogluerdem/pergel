<?php

namespace App\Http\Controllers;

use App\Models\PriceDifferenceCalculation;
use App\Models\Project;
use Illuminate\Http\Request;

class PriceDifferenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = PriceDifferenceCalculation::orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::pluck('name', 'id');

        $stats = [
            'total_hakedis' => PriceDifferenceCalculation::sum('hakedis_amount'),
            'total_price_diff' => PriceDifferenceCalculation::sum('price_difference_amount'),
            'total_count' => PriceDifferenceCalculation::count(),
            'avg_inflation_rate' => PriceDifferenceCalculation::count() > 0 ?
                PriceDifferenceCalculation::where('base_index_value', '>', 0)->selectRaw('AVG((current_index_value / base_index_value - 1) * 100) as avg_rate')->value('avg_rate') : 0,
        ];

        return view('price_differences.index', compact('items', 'projects', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_name' => 'required|string',
            'base_index_month' => 'required|string',
            'base_index_value' => 'required|numeric|gt:0',
            'current_index_month' => 'required|string',
            'current_index_value' => 'required|numeric|min:0',
            'hakedis_amount' => 'required|numeric|min:0.01',
        ]);

        $baseVal = floatval($validated['base_index_value']);
        $currentVal = floatval($validated['current_index_value']);
        $hakedis = floatval($validated['hakedis_amount']);

        // F = An * (Pn / P0 - 1)
        $priceDifference = $hakedis * (($currentVal / $baseVal) - 1);

        PriceDifferenceCalculation::create([
            'contract_name' => $validated['contract_name'],
            'base_index_month' => $validated['base_index_month'],
            'base_index_value' => $baseVal,
            'current_index_month' => $validated['current_index_month'],
            'current_index_value' => $currentVal,
            'hakedis_amount' => $hakedis,
            'price_difference_amount' => $priceDifference,
        ]);

        return back()->with('success', 'Yİ-ÜFE Kamu Fiyat Farkı hesabı başarıyla kaydedildi.');
    }

    public function destroy(PriceDifferenceCalculation $priceDifference)
    {
        $priceDifference->delete();
        return back()->with('success', 'Fiyat farkı hesabı silindi.');
    }
}
