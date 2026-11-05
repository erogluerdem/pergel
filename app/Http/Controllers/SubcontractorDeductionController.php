<?php

namespace App\Http\Controllers;

use App\Models\SubcontractorDeduction;
use App\Models\Subcontractor;
use Illuminate\Http\Request;

class SubcontractorDeductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = SubcontractorDeduction::orderBy('created_at', 'desc')->paginate(15);
        $subcontractors = Subcontractor::pluck('company_name', 'id');

        $stats = [
            'total_gross' => SubcontractorDeduction::sum('gross_amount'),
            'total_teminat' => SubcontractorDeduction::sum('teminat_amount'),
            'total_damga' => SubcontractorDeduction::sum('damga_vergisi_amount'),
            'total_stopaj' => SubcontractorDeduction::sum('stopaj_amount'),
            'total_net' => SubcontractorDeduction::sum('net_amount'),
        ];

        return view('subcontractor_deductions.index', compact('items', 'subcontractors', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subcontractor_name' => 'required|string',
            'hakedis_period' => 'required|string',
            'gross_amount' => 'required|numeric|min:0.01',
            'teminat_kesintisi_rate' => 'nullable|numeric|min:0|max:100',
            'stopaj_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $gross = floatval($validated['gross_amount']);
        $teminatRate = floatval($validated['teminat_kesintisi_rate'] ?? 5.00);
        $stopajRate = floatval($validated['stopaj_rate'] ?? 0.00);
        $damgaRate = 0.00948; // binde 9.48

        $teminatAmount = $gross * ($teminatRate / 100.0);
        $damgaAmount = $gross * $damgaRate;
        $stopajAmount = $gross * ($stopajRate / 100.0);
        $netAmount = $gross - ($teminatAmount + $damgaAmount + $stopajAmount);

        SubcontractorDeduction::create([
            'subcontractor_name' => $validated['subcontractor_name'],
            'hakedis_period' => $validated['hakedis_period'],
            'gross_amount' => $gross,
            'teminat_kesintisi_rate' => $teminatRate,
            'teminat_amount' => $teminatAmount,
            'damga_vergisi_rate' => $damgaRate,
            'damga_vergisi_amount' => $damgaAmount,
            'stopaj_rate' => $stopajRate,
            'stopaj_amount' => $stopajAmount,
            'net_amount' => $netAmount,
        ]);

        return back()->with('success', 'Taşeron hakediş yasal kesinti hesabı kaydedildi.');
    }

    public function destroy(SubcontractorDeduction $subcontractorDeduction)
    {
        $subcontractorDeduction->delete();
        return back()->with('success', 'Taşeron kesinti kaydı silindi.');
    }
}
