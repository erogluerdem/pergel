<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeasibilityStudy;
use App\Models\Opportunity;
use Carbon\Carbon;

class FeasibilityController extends Controller
{
    public function index()
    {
        $studies = FeasibilityStudy::orderBy('created_at','desc')->get();
        $summaries = [];
        $totalLandCost = 0;
        $totalCostSum = 0;
        $totalRevenueSum = 0;
        $roiSum = 0;
        $constructionMonthsSum = 0;
        $count = $studies->count();

        foreach ($studies as $s) {
            $res = $this->computeResults($s);
            $summaries[$s->id] = [
                'roi' => $res['roi'] ?? 0,
                'total_cost' => $res['total_cost'] ?? 0,
                'total_revenue' => $res['total_revenue'] ?? 0,
                'gross_profit' => $res['gross_profit'] ?? 0,
                'npv' => $res['npv'] ?? 0,
            ];
            $totalLandCost += floatval($s->land_cost ?? 0);
            $totalCostSum += floatval($res['total_cost'] ?? 0);
            $totalRevenueSum += floatval($res['total_revenue'] ?? 0);
            $roiSum += floatval($res['roi'] ?? 0);
            $constructionMonthsSum += intval($s->construction_months ?? 0);
        }

        $avgRoi = $count > 0 ? round($roiSum / $count, 1) : 0;
        $avgMonths = $count > 0 ? round($constructionMonthsSum / $count, 1) : 0;

        $stats = [
            'total_studies' => $count,
            'avg_roi' => $avgRoi,
            'total_land_cost' => $totalLandCost,
            'total_cost_sum' => $totalCostSum,
            'total_revenue_sum' => $totalRevenueSum,
            'avg_months' => $avgMonths,
        ];

        return view('feasibility.index', compact('studies', 'summaries', 'stats'));
    }

    public function create(\Illuminate\Http\Request $request)
    {
        $opportunities = Opportunity::all();
        $prefillOpportunityId = $request->get('opportunity_id');
        return view('feasibility.create', compact('opportunities','prefillOpportunityId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string',
            'opportunity_id'=>'nullable|exists:opportunities,id',
            'project_type'=>'nullable|string',
            'total_area'=>'nullable|numeric',
            'unit_sale_price'=>'nullable|numeric',
            'other_revenue'=>'nullable|numeric',
            'land_cost'=>'nullable|numeric',
            'construction_cost_per_m2'=>'nullable|numeric',
            'soft_costs_percent'=>'nullable|numeric',
            'construction_months'=>'nullable|integer',
            'sales_months'=>'nullable|integer',
            'equity'=>'nullable|numeric',
            'debt'=>'nullable|numeric',
            'interest_rate_percent'=>'nullable|numeric',
        ]);

        if (auth()->check()) {
            $data['created_by'] = auth()->id();
        }

        $study = FeasibilityStudy::create($data);

        if (!empty($study->opportunity_id)) {
            $opp = Opportunity::find($study->opportunity_id);
            if ($opp) {
                $user = auth()->user();
                $who = $user ? $user->name : 'Sistem';
                $opp->addActivity("Fizibilite senaryosu oluşturuldu: {$study->name} (ID: {$study->id}) — oluşturulma: {$who}");
            }
        }

        return redirect()->route('feasibility.show', $study->id);
    }

    public function show(Request $request, $id)
    {
        $s = FeasibilityStudy::findOrFail($id);
        $kdv = $request->get('kdv', null);
        $results = $this->computeResults($s, $kdv);
        return view('feasibility.show', compact('s','results','kdv'));
    }

    public function duplicate(Request $request, $id)
    {
        $s = FeasibilityStudy::findOrFail($id);
        $copy = $s->replicate();
        $copy->name = $s->name . ' - Senaryo';
        $copy->status = 'draft';
        $copy->created_at = now();
        $copy->updated_at = now();
        $copy->push();
        return redirect()->route('feasibility.show', $copy->id)->with('success','Senaryo kopyalandı');
    }

    public function recalc(Request $request, $id)
    {
        $s = FeasibilityStudy::findOrFail($id);
        $kdv = $request->input('kdv', null);
        $financeTermMonths = $request->input('finance_term_months', null);
        $results = $this->computeResults($s, $kdv, $financeTermMonths);
        $s->scenario_meta = array_merge($s->scenario_meta ?? [], [
            'last_results' => $results,
            'recalculated_at' => now()->toDateTimeString(),
        ]);
        $s->save();

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['ok' => true, 'success' => true, 'results' => $results]);
        }

        return redirect()->route('feasibility.show', $s->id)->with('success', 'Fizibilite hesaplamaları güncellendi.');
    }

    public function edit(Request $request, $id)
    {
        $s = FeasibilityStudy::findOrFail($id);
        $opportunities = Opportunity::all();
        return view('feasibility.edit', compact('s','opportunities'));
    }

    public function update(Request $request, $id)
    {
        $s = FeasibilityStudy::findOrFail($id);
        $data = $request->validate([
            'name'=>'required|string',
            'opportunity_id'=>'nullable|exists:opportunities,id',
            'project_type'=>'nullable|string',
            'total_area'=>'nullable|numeric',
            'unit_sale_price'=>'nullable|numeric',
            'other_revenue'=>'nullable|numeric',
            'land_cost'=>'nullable|numeric',
            'construction_cost_per_m2'=>'nullable|numeric',
            'soft_costs_percent'=>'nullable|numeric',
            'construction_months'=>'nullable|integer',
            'sales_months'=>'nullable|integer',
            'equity'=>'nullable|numeric',
            'debt'=>'nullable|numeric',
            'interest_rate_percent'=>'nullable|numeric',
        ]);

        $s->update($data);
        return redirect()->route('feasibility.show', $s->id)->with('success','Fizibilite güncellendi');
    }

    public function destroy(Request $request, $id)
    {
        $s = FeasibilityStudy::findOrFail($id);
        $s->delete();
        return redirect()->route('feasibility.index')->with('success','Fizibilite çöp kutusuna taşındı');
    }

    public function trash()
    {
        $studies = FeasibilityStudy::onlyTrashed()->orderBy('deleted_at','desc')->get();
        return view('feasibility.trash', compact('studies'));
    }

    public function restore($id)
    {
        $s = FeasibilityStudy::onlyTrashed()->findOrFail($id);
        $s->restore();
        return redirect()->route('feasibility.index')->with('success','Fizibilite geri yüklendi');
    }

    public function forceDelete($id)
    {
        $s = FeasibilityStudy::onlyTrashed()->findOrFail($id);
        $s->forceDelete();
        return redirect()->route('feasibility.trash')->with('success','Fizibilite kalıcı olarak silindi');
    }

    public function exportPdf($id)
    {
        $s = FeasibilityStudy::findOrFail($id);
        $results = $this->computeResults($s);
        return view('feasibility.pdf', compact('s','results'));
    }

    public function compare(Request $request)
    {
        $ids = $request->get('ids', []);
        if (is_string($ids)) {
            $ids = array_filter(explode(',', $ids));
        }
        $studies = FeasibilityStudy::whereIn('id', $ids)->get();
        if ($studies->isEmpty()) {
            $studies = FeasibilityStudy::orderBy('created_at', 'desc')->take(4)->get();
        }
        $allStudies = FeasibilityStudy::all();
        $results = [];
        $rows = [];
        foreach ($studies as $s) {
            $res = $this->computeResults($s);
            $results[$s->id] = $res;
            $row = array_merge([
                'id' => $s->id,
                'name' => $s->name,
                'project_type' => $s->project_type ?? 'Genel',
                'total_area' => $s->total_area ?? 0,
                'unit_sale_price' => $s->unit_sale_price ?? 0,
                'land_cost' => $s->land_cost ?? 0,
            ], $res);
            $rows[] = $row;
        }
        return view('feasibility.compare', compact('studies','allStudies','results','rows'));
    }

    public function exportCompareCsv(Request $request)
    {
        $ids = array_filter(explode(',', $request->get('ids', '')));
        $studies = FeasibilityStudy::whereIn('id', $ids)->get();
        if ($studies->isEmpty()) {
            $studies = FeasibilityStudy::orderBy('created_at', 'desc')->take(4)->get();
        }
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename=fizibilite_kiyaslama.csv',
        ];
        $callback = function() use ($studies) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['ID', 'Senaryo Adı', 'Proje Türü', 'Toplam Alan m2', 'Arsa Maliyeti', 'Birim Satış Fiyatı', 'Toplam Gelir', 'Toplam Maliyet', 'Net Kar', 'ROI %']);
            foreach ($studies as $s) {
                $res = $this->computeResults($s);
                fputcsv($file, [
                    $s->id,
                    $s->name,
                    $s->project_type ?? 'Genel',
                    $s->total_area,
                    $s->land_cost,
                    $s->unit_sale_price,
                    $res['total_revenue'],
                    $res['total_cost'],
                    $res['gross_profit'],
                    $res['roi'] . '%'
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportComparePdf(Request $request)
    {
        $ids = array_filter(explode(',', $request->get('ids', '')));
        $studies = FeasibilityStudy::whereIn('id', $ids)->get();
        if ($studies->isEmpty()) {
            $studies = FeasibilityStudy::orderBy('created_at', 'desc')->take(4)->get();
        }
        $results = [];
        foreach ($studies as $s) {
            $results[$s->id] = $this->computeResults($s);
        }
        return view('feasibility.compare_pdf', compact('studies','results'));
    }

    protected function computeResults(FeasibilityStudy $s, $kdv = null, $financeTermMonths = null)
    {
        $totalArea = floatval($s->total_area ?: 0);
        $unitSalePrice = floatval($s->unit_sale_price ?: 0);
        $otherRevenue = floatval($s->other_revenue ?: 0);
        $landCost = floatval($s->land_cost ?: 0);
        $constructionCostPerM2 = floatval($s->construction_cost_per_m2 ?: 0);
        $softCostsPercent = floatval($s->soft_costs_percent ?: 0);
        $constructionMonths = intval($s->construction_months ?: 1);
        $salesMonths = intval($s->sales_months ?: 1);

        $salesRevenue = $totalArea * $unitSalePrice;
        $totalRevenue = $salesRevenue + $otherRevenue;

        if ($kdv !== null) {
            $kdvPct = floatval($kdv);
            $totalRevenue = $totalRevenue * (1 + ($kdvPct / 100));
        }

        $constructionCost = $totalArea * $constructionCostPerM2;
        $construction_materials = $constructionCost * 0.55;
        $construction_labor = $constructionCost * 0.30;
        $construction_engineering = $constructionCost * 0.10;
        $construction_contingency = $constructionCost * 0.05;

        $softCosts = ($constructionCost + $landCost) * ($softCostsPercent / 100);
        $soft_permits = $softCosts * 0.40;
        $soft_marketing = $softCosts * 0.35;
        $soft_fees = $softCosts * 0.25;

        $totalCost = $landCost + $constructionCost + $softCosts;
        $grossProfit = $totalRevenue - $totalCost;
        $roi = $totalCost > 0 ? round(($grossProfit / $totalCost) * 100, 2) : 0;

        $initialInvestment = $landCost + ($constructionCost * 0.20) + ($softCosts * 0.30);
        $months = max(1, $constructionMonths + $salesMonths);
        $monthlyRevenue = $salesMonths > 0 ? $totalRevenue / $salesMonths : 0;
        $monthlyConstCost = $constructionMonths > 0 ? ($constructionCost * 0.80) / $constructionMonths : 0;

        $cashflows = [];
        $cashflows[0] = -1 * $initialInvestment;
        for ($m = 1; $m <= $months; $m++) {
            $cf = 0;
            if ($m <= $constructionMonths) {
                $cf -= $monthlyConstCost;
            }
            if ($m > $constructionMonths) {
                $cf += $monthlyRevenue;
            }
            $cashflows[$m] = $cf;
        }

        $discountRate = 0.15;
        $npv = 0;
        foreach ($cashflows as $t => $cf) {
            $npv += $cf / pow(1 + $discountRate / 12, $t);
        }

        $irr = $this->approxIrr($cashflows);

        $cum = 0;
        $payback = null;
        foreach ($cashflows as $t => $cf) {
            $cum += $cf;
            if ($cum >= 0 && $t > 0) {
                $payback = $t;
                break;
            }
        }

        $debt = $s->debt ?: 0;
        $finance_term_months = $financeTermMonths ?: 120;
        $monthly_debt_payment = 0;
        $total_finance_cost = 0;
        if ($debt > 0) {
            $r = max(0, ($s->interest_rate_percent ?: 0) / 100 / 12);
            if ($r > 0) {
                $monthly_debt_payment = ($r * $debt) / (1 - pow(1 + $r, -$finance_term_months));
            } else {
                $monthly_debt_payment = $debt / $finance_term_months;
            }
            $total_finance_cost = $monthly_debt_payment * $finance_term_months - $debt;
        }

        $labels = array_map(function ($m) { return 'M' . $m; }, array_keys($cashflows));
        $values = array_values($cashflows);

        $scenarios = [-20, -10, 0, 10, 20];
        $sensitivity = [];
        foreach ($scenarios as $pct) {
            $rev = $totalRevenue * (1 + $pct / 100);
            $monthlyRev = $salesMonths > 0 ? $rev / $salesMonths : 0;
            $scf = [];
            $scf[0] = -1 * $initialInvestment;
            for ($m = 1; $m <= $months; $m++) {
                $cf = 0;
                if ($m <= $constructionMonths) {
                    $cf -= $monthlyConstCost;
                }
                if ($m > $constructionMonths) {
                    $cf += $monthlyRev;
                }
                $scf[$m] = $cf;
            }
            $snpv = 0;
            foreach ($scf as $t => $cf) {
                $snpv += $cf / pow(1 + $discountRate / 12, $t);
            }
            $sgross = $rev - $totalCost;
            $sroi = $totalCost > 0 ? round(($sgross / $totalCost) * 100, 2) : 0;
            $sensitivity[] = [
                'pct' => $pct,
                'total_revenue' => round($rev, 2),
                'roi' => $sroi,
                'npv' => round($snpv, 2)
            ];
        }

        return [
            'total_revenue' => round($totalRevenue, 2),
            'total_cost' => round($totalCost, 2),
            'gross_profit' => round($grossProfit, 2),
            'roi' => $roi,
            'npv' => round($npv, 2),
            'irr' => round($irr * 100, 2),
            'payback_months' => $payback,
            'cashflow_labels' => $labels,
            'cashflow_values' => $values,
            'construction_cost' => round($constructionCost, 2),
            'construction_materials' => round($construction_materials, 2),
            'construction_labor' => round($construction_labor, 2),
            'construction_engineering' => round($construction_engineering, 2),
            'construction_contingency' => round($construction_contingency, 2),
            'soft_costs' => round($softCosts, 2),
            'soft_permits' => round($soft_permits, 2),
            'soft_marketing' => round($soft_marketing, 2),
            'soft_fees' => round($soft_fees, 2),
            'land_cost' => round($landCost, 2),
            'monthly_revenue' => round($monthlyRevenue, 2),
            'discount_rate' => $discountRate,
            'initial_investment' => round($initialInvestment, 2),
            'debt' => round($debt, 2),
            'monthly_debt_payment' => round($monthly_debt_payment, 2),
            'total_finance_cost' => round($total_finance_cost, 2),
            'finance_term_months' => $finance_term_months,
            'sensitivity' => $sensitivity,
        ];
    }

    protected function approxIrr(array $cashflows)
    {
        $f = function ($r) use ($cashflows) {
            $v = 0;
            foreach ($cashflows as $t => $cf) {
                $v += $cf / pow(1 + $r, $t);
            }
            return $v;
        };
        $low = -0.999;
        $high = 2;
        $mid = 0;
        for ($i = 0; $i < 60; $i++) {
            $mid = ($low + $high) / 2;
            $val = $f($mid);
            if (abs($val) < 1e-6) {
                break;
            }
            if ($val > 0) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }
        return $mid;
    }
}
