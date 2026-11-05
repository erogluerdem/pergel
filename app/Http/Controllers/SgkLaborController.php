<?php

namespace App\Http\Controllers;

use App\Models\SgkLaborCalculation;
use App\Models\Project;
use Illuminate\Http\Request;

class SgkLaborController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $calculations = SgkLaborCalculation::with('project')->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::pluck('name', 'id');

        // ÇŞB Yapı Birim Maliyetleri 2026 referans değerleri (TL/m2)
        $structureClasses = [
            'I-A' => ['name' => 'I. Sınıf A Grubu (Fidanlık, kulübe vb.)', 'cost' => 3500],
            'II-A' => ['name' => 'II. Sınıf A Grubu (Ağıl, kümes vb.)', 'cost' => 6500],
            'III-A' => ['name' => 'III. Sınıf A Grubu (Konutlar 3 kata kadar)', 'cost' => 18500],
            'III-B' => ['name' => 'III. Sınıf B Grubu (Konutlar 3 kattan fazla)', 'cost' => 22400],
            'IV-A' => ['name' => 'IV. Sınıf A Grubu (İş merkezleri, oteller)', 'cost' => 28500],
            'IV-B' => ['name' => 'IV. Sınıf B Grubu (Lüks konut, hastane)', 'cost' => 34000],
            'V-A' => ['name' => 'V. Sınıf A Grubu (Nitelikli yapılar, müze vb.)', 'cost' => 42000],
        ];

        return view('sgk.index', compact('calculations', 'projects', 'structureClasses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'building_area_sqm' => 'required|numeric|min:1',
            'structure_class' => 'required|string',
            'unit_cost_per_sqm' => 'required|numeric|min:1',
            'mandatory_labor_rate' => 'required|numeric|min:0.1|max:100',
            'reported_labor_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $area = floatval($validated['building_area_sqm']);
        $unitCost = floatval($validated['unit_cost_per_sqm']);
        $rate = floatval($validated['mandatory_labor_rate']) / 100;
        $reported = floatval($validated['reported_labor_amount'] ?? 0);

        // Hesaplama: Toplam Yapı Yaklaşık Maliyeti = m2 * Birim Maliyet
        $approxCost = $area * $unitCost;
        // Zorunlu SGK İşçilik Tutarı = Yapı Yaklaşık Maliyeti * Asgari İşçilik Oranı (%6.75 vb)
        $requiredAmount = $approxCost * $rate;
        $remaining = max(0, $requiredAmount - $reported);

        // Risk Durumu
        $coverageRatio = $requiredAmount > 0 ? ($reported / $requiredAmount) * 100 : 0;
        $riskStatus = 'high';
        if ($coverageRatio >= 90) {
            $riskStatus = 'low';
        } elseif ($coverageRatio >= 50) {
            $riskStatus = 'medium';
        }

        SgkLaborCalculation::create([
            'project_id' => $validated['project_id'],
            'building_area_sqm' => $area,
            'structure_class' => $validated['structure_class'],
            'unit_cost_per_sqm' => $unitCost,
            'mandatory_labor_rate' => $validated['mandatory_labor_rate'],
            'required_labor_amount' => $requiredAmount,
            'reported_labor_amount' => $reported,
            'remaining_labor_amount' => $remaining,
            'risk_status' => $riskStatus,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('sgk.index')->with('success', 'SGK Asgari İşçilik hesaplaması kaydedildi.');
    }

    public function destroy(SgkLaborCalculation $sgk)
    {
        $sgk->delete();
        return redirect()->route('sgk.index')->with('success', 'SGK Kaydı silindi.');
    }
}
