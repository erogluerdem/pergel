<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Program;
use Carbon\Carbon;

class PortfolioFinancialController extends Controller
{
    public function index(Request $request)
    {
        // Basic KPIs - load real projects from DB
        $projects = Project::query();

        // filters (optional)
        if ($request->filled('type')) {
            $projects->where('type', $request->type);
        }

        // allow filtering by program id as well (if provided)
        if ($request->filled('program_id')) {
            $projects->where('program_id', $request->program_id);
        }

        $projects = $projects->get();

        // If no real projects exist in DB, try loading demo data from resources/demo
        $usingDemo = false;
        if ($projects->isEmpty()) {
            try {
                $demoPath = resource_path('demo/portfolio_demo_projects.json');
                if (file_exists($demoPath)) {
                    $raw = file_get_contents($demoPath);
                    $arr = json_decode($raw, true) ?: [];
                    // Convert arrays to objects so existing code using ->property still works
                    $projects = collect(array_map(function($i){ return (object)$i; }, $arr));
                    $usingDemo = true;
                }
            } catch (\Exception $e) {
                // fallback: keep $projects empty
            }
        }

        $totalContractValue = $projects->sum('budget');
        $totalSpent = $projects->sum('spent');

        // KDV seçenekleri: önce DB'den aktif olanları al, yoksa config üzerinden
        try {
            $dbRates = \App\Models\KdvRate::where('active', true)->orderBy('sort')->pluck('rate')->toArray();
            $allowedKdvOptions = count($dbRates) ? array_map('strval', $dbRates) : array_map('strval', config('finance.kdv_rates', [0,1,10,20]));
        } catch (\Exception $e) {
            $allowedKdvOptions = array_map('strval', config('finance.kdv_rates', [0,1,10,20]));
        }

        // resolve selected KDV percent using FinanceService (falls back to config/DB)
        $kdvRequested = $request->get('kdv', null);
        $kdvPercent = \App\Services\FinanceService::resolveKdvPercent($kdvRequested);
        $totalContractValueKdv = \App\Services\FinanceService::applyKdv((float)$totalContractValue, $kdvPercent);

        // Ortalama bütçe sapması (%): (spent - budget) / budget
        $variancePercents = [];
        foreach ($projects as $p) {
            if (!empty($p->budget) && $p->budget > 0) {
                $variancePercents[] = (($p->spent ?? 0) - $p->budget) / $p->budget * 100;
            }
        }
        $avgCostVariancePercent = count($variancePercents) ? round(array_sum($variancePercents) / count($variancePercents), 2) : 0;

        // Ek KPI'lar
        $activeProjects = $projects->filter(function($p) {
            return ($p->progress_percent ?? 0) < 100;
        })->count();

        $completedProjects = $projects->filter(function($p) {
            return ($p->progress_percent ?? 0) >= 100;
        })->count();

        $totalProjects = $projects->count();

        // Risk analizi
        $highRiskProjects = $projects->filter(function($p) {
            $costOverrun = $p->budget > 0 ? (($p->spent ?? 0) - $p->budget) / $p->budget * 100 : 0;
            $scheduleRisk = false;
            if ($p->end_date) {
                try {
                    $end = Carbon::parse($p->end_date);
                    $progress = $p->progress_percent ?? 0;
                    $today = Carbon::today();
                    $totalDays = $p->start_date ? Carbon::parse($p->start_date)->diffInDays($end) : 1;
                    $elapsedDays = $p->start_date ? Carbon::parse($p->start_date)->diffInDays($today) : 0;
                    $expectedProgress = $totalDays > 0 ? ($elapsedDays / $totalDays) * 100 : 0;
                    $scheduleRisk = $progress < ($expectedProgress - 10); // 10% tolerans
                } catch (\Exception $e) {
                    $scheduleRisk = false;
                }
            }
            return $costOverrun > 15 || $scheduleRisk; // %15 maliyet aşımı veya program riski
        })->count();

        // Portföy performans skorları
        $performanceScore = $this->calculatePerformanceScore($projects);

        // Aylık trend analizi
        $monthlyTrends = $this->calculateMonthlyTrends($projects);        // Gecikmiş projeler (bitiş tarihi geçmiş ve tamamlanmamış)
        $today = Carbon::today();
        $overdueProjects = $projects->filter(function($p) use ($today){
            try {
                if (empty($p->end_date)) return false;
                $end = Carbon::parse($p->end_date);
                return $end->lt($today) && (($p->progress_percent ?? 0) < 100);
            } catch (\Exception $e) {
                return false;
            }
        })->count();

        // naive profitability: (budget - spent) / budget
        $profitabilityPercent = $totalContractValue > 0 ? round((($totalContractValue - $totalSpent) / $totalContractValue) * 100, 2) : 0;

        // cash on hand and projection
        $cashOnHand = config('app.starting_cash', 0);

        // Build S-curve (planned vs actual) aggregated monthly from projects
        $sCurve = $this->buildSCurve($projects);

        // Build a simple cash projection for next 12 months using planned spend
        $cashProjection = $this->buildCashProjection($sCurve, $cashOnHand);

        // group by type or program for consolidated table
        $groupBy = $request->get('group_by', 'type'); // 'type' or 'program'

    $groups = [];

        if ($groupBy === 'program') {
            $programs = Program::with('projects')->get();
            foreach ($programs as $program) {
                $projCount = $program->projects->count();
                $contractValue = $program->projects->sum('budget');
                $budget = $contractValue; // placeholder
                $spent = $program->projects->sum('spent');
                $remaining = $budget - $spent;
                $estimatedFinishCost = $budget; // placeholder
                $profitTl = $contractValue - $estimatedFinishCost;
                $profitMargin = $contractValue > 0 ? round(($profitTl / $contractValue) * 100,2) : 0;

                $kdvAmount = \App\Services\FinanceService::kdvAmount((float)$contractValue, $kdvPercent);
                $contractWithKdv = \App\Services\FinanceService::applyKdv((float)$contractValue, $kdvPercent);

                $groups[] = [
                    'group' => $program->name,
                    'project_count' => $projCount,
                    'contract_value' => $contractValue,
                    'kdv_percent' => $kdvPercent,
                    'kdv_amount' => $kdvAmount,
                    'contract_value_with_kdv' => $contractWithKdv,
                    'budget' => $budget,
                    'spent' => $spent,
                    'remaining' => $remaining,
                    'estimated_finish_cost' => $estimatedFinishCost,
                    'profit_tl' => $profitTl,
                    'profit_margin' => $profitMargin,
                    'roi' => $contractValue ? round(($profitTl / $contractValue) * 100,2) : 0,
                    // include the projects themselves for expand/collapse in the view
                    'items' => $program->projects->map(function($p){
                        return [
                            'id' => $p->id,
                            'name' => $p->name,
                            'budget' => $p->budget ?? 0,
                            'spent' => $p->spent ?? 0,
                            'progress_percent' => $p->progress_percent ?? 0,
                        ];
                    })->toArray(),
                ];
            }
        } else {
            // group by project type
            $types = $projects->groupBy('type');
            foreach ($types as $type => $items) {
                $projCount = $items->count();
                $contractValue = $items->sum('budget');
                $budget = $contractValue; // placeholder
                $spent = $items->sum('spent');
                $remaining = $budget - $spent;
                $estimatedFinishCost = $budget; // placeholder
                $profitTl = $contractValue - $estimatedFinishCost;
                $profitMargin = $contractValue > 0 ? round(($profitTl / $contractValue) * 100,2) : 0;

                $kdvAmount = \App\Services\FinanceService::kdvAmount((float)$contractValue, $kdvPercent);
                $contractWithKdv = \App\Services\FinanceService::applyKdv((float)$contractValue, $kdvPercent);

                $groups[] = [
                    'group' => $type ?: 'Unspecified',
                    'project_count' => $projCount,
                    'contract_value' => $contractValue,
                    'kdv_percent' => $kdvPercent,
                    'kdv_amount' => $kdvAmount,
                    'contract_value_with_kdv' => $contractWithKdv,
                    'budget' => $budget,
                    'spent' => $spent,
                    'remaining' => $remaining,
                    'estimated_finish_cost' => $estimatedFinishCost,
                    'profit_tl' => $profitTl,
                    'profit_margin' => $profitMargin,
                    'roi' => $contractValue ? round(($profitTl / $contractValue) * 100,2) : 0,
                    'items' => $items->map(function($p){
                        return [
                            'id' => $p->id,
                            'name' => $p->name,
                            'budget' => $p->budget ?? 0,
                            'spent' => $p->spent ?? 0,
                            'progress_percent' => $p->progress_percent ?? 0,
                        ];
                    })->values()->toArray(),
                ];
            }
        }

        return view('portfolio.financials.index', compact(
            'totalContractValue','totalSpent','profitabilityPercent','cashOnHand','cashProjection','sCurve','groups','groupBy','allowedKdvOptions','kdvPercent',
            'totalContractValueKdv','avgCostVariancePercent','activeProjects','completedProjects','totalProjects','highRiskProjects','performanceScore','monthlyTrends','usingDemo'
        ));
    }

    public function exportCsv(Request $request)
    {
        $groupBy = $request->get('group_by', 'type');
        $filename = 'portfolio_financials_'.date('Ymd_His').'.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $handle = fopen('php://output', 'w');
        // include KDV columns
        $columns = ['Group','Project Count','Contract Value','KDV %','KDV Tutarı','Contract Value (KDV Dahil)','Budget','Spent','Remaining','Estimated Finish Cost','Profit TL','Profit Margin %','ROI %'];
        fputcsv($handle, $columns);
        // resolve KDV for export (use FinanceService which reads DB/config)
        $kdvPercent = \App\Services\FinanceService::resolveKdvPercent($request->get('kdv', null));

        // reuse index logic quickly (not optimal)
        $projects = Project::get();
        if ($groupBy === 'program') {
            $programs = Program::with('projects')->get();
            foreach ($programs as $program) {
                $items = $program->projects;
                $contractValue = $items->sum('budget');
                $budget = $contractValue;
                $spent = $items->sum('spent');
                $remaining = $budget - $spent;
                $estimatedFinishCost = $budget;
                $profitTl = $contractValue - $estimatedFinishCost;
                $profitMargin = $contractValue ? round(($profitTl / $contractValue) * 100,2) : 0;
                $kdvAmount = \App\Services\FinanceService::kdvAmount((float)$contractValue, $kdvPercent);
                $contractWithKdv = \App\Services\FinanceService::applyKdv((float)$contractValue, $kdvPercent);
                fputcsv($handle, [$program->name,$items->count(),$contractValue,$kdvPercent,$kdvAmount,$contractWithKdv,$budget,$spent,$remaining,$estimatedFinishCost,$profitTl,$profitMargin,$profitMargin]);
            }
        } else {
            $types = $projects->groupBy('type');
            foreach ($types as $type => $items) {
                $contractValue = $items->sum('budget');
                $budget = $contractValue;
                $spent = $items->sum('spent');
                $remaining = $budget - $spent;
                $estimatedFinishCost = $budget;
                $profitTl = $contractValue - $estimatedFinishCost;
                $profitMargin = $contractValue ? round(($profitTl / $contractValue) * 100,2) : 0;
                $kdvAmount = \App\Services\FinanceService::kdvAmount((float)$contractValue, $kdvPercent);
                $contractWithKdv = \App\Services\FinanceService::applyKdv((float)$contractValue, $kdvPercent);
                fputcsv($handle, [$type ?: 'Unspecified',$items->count(),$contractValue,$kdvPercent,$kdvAmount,$contractWithKdv,$budget,$spent,$remaining,$estimatedFinishCost,$profitTl,$profitMargin,$profitMargin]);
            }
        }

        fclose($handle);
        exit;
    }

    /**
     * AJAX: return items (projects) for a given group (by name or index)
     */
    public function groupItems(Request $request)
    {
        $groupBy = $request->get('group_by', 'type');
        $groupName = $request->get('group', null);
        $groupIndex = $request->get('group_index', null);

        $projects = Project::get();

        $items = [];
        if ($groupBy === 'program') {
            $programs = Program::with('projects')->get();
            if ($groupIndex !== null) {
                $p = $programs->get($groupIndex);
            } else {
                $p = $programs->firstWhere('name', $groupName);
            }
            if ($p) {
                $items = $p->projects->map(function($pr){
                    return [
                        'id' => $pr->id,
                        'name' => $pr->name,
                        'budget' => $pr->budget ?? 0,
                        'spent' => $pr->spent ?? 0,
                        'progress_percent' => $pr->progress_percent ?? 0,
                        'start_date' => $pr->start_date ?? null,
                        'end_date' => $pr->end_date ?? null,
                        'show_url' => url('projects/'.$pr->id)
                    ];
                })->toArray();
            }
        } else {
            $types = $projects->groupBy('type');
            if ($groupIndex !== null) {
                $keys = array_values($types->keys()->toArray());
                $key = $keys[$groupIndex] ?? null;
            } else {
                $key = $groupName;
            }
            $items = collect($types[$key] ?? collect())->map(function($pr){
                return [
                    'id' => $pr->id,
                    'name' => $pr->name,
                    'budget' => $pr->budget ?? 0,
                    'spent' => $pr->spent ?? 0,
                    'progress_percent' => $pr->progress_percent ?? 0,
                    'start_date' => $pr->start_date ?? null,
                    'end_date' => $pr->end_date ?? null,
                    'show_url' => url('projects/'.$pr->id)
                ];
            })->values()->toArray();
        }

        return response()->json(['items' => $items]);
    }

    /**
     * Build s-curve series (monthly labels, planned cumulative, actual cumulative)
     * Accepts a Collection of Project models
     */
    protected function buildSCurve($projects)
    {
        // We expect projects to have: start_date, end_date, budget, spent, progress_percent
        $months = [];
        $monthlyPlanned = [];
        $monthlyActual = [];

        // determine global range (from earliest project start to latest project end) limited to 36 months
        $starts = $projects->pluck('start_date')->filter()->map(function ($d) { return Carbon::parse($d); });
        $ends = $projects->pluck('end_date')->filter()->map(function ($d) { return Carbon::parse($d); });

        if ($starts->isEmpty() || $ends->isEmpty()) {
            return ['labels'=>[], 'planned'=>[], 'actual'=>[]];
        }

        $min = $starts->min();
        $max = $ends->max();

        // cap to 36 months window for performance
        if ($min->diffInMonths($max) > 36) {
            $max = $min->copy()->addMonths(36);
        }

        // build monthly buckets
        $cursor = $min->copy()->startOfMonth();
        while ($cursor->lte($max)) {
            $key = $cursor->format('Y-m');
            $months[] = $key;
            $monthlyPlanned[$key] = 0;
            $monthlyActual[$key] = 0;
            $cursor->addMonth();
        }

        foreach ($projects as $p) {
            $pStart = $p->start_date ? Carbon::parse($p->start_date)->startOfMonth() : null;
            $pEnd = $p->end_date ? Carbon::parse($p->end_date)->endOfMonth() : null;
            $budget = $p->budget ?: 0;
            $spent = $p->spent ?: 0;

            if (!$pStart || !$pEnd) continue;

            $durationMonths = max(1, $pStart->diffInMonths($pEnd) + 1);
            // distribute planned budget evenly across months
            $perMonthPlanned = $budget / $durationMonths;

            $cursor = $pStart->copy();
            while ($cursor->lte($pEnd)) {
                $key = $cursor->format('Y-m');
                if (array_key_exists($key, $monthlyPlanned)) {
                    $monthlyPlanned[$key] += $perMonthPlanned;
                }
                $cursor->addMonth();
            }

            // naive allocation of actual spent: attribute fully to end month
            $endKey = $pEnd->format('Y-m');
            if (array_key_exists($endKey, $monthlyActual)) {
                $monthlyActual[$endKey] += $spent;
            }
        }

        // cumulative sums
        $labels = [];
        $planned = [];
        $actual = [];
        $cumPlanned = 0;
        $cumActual = 0;
        foreach ($months as $m) {
            $labels[] = $m;
            $cumPlanned += $monthlyPlanned[$m] ?? 0;
            $cumActual += $monthlyActual[$m] ?? 0;
            $planned[] = round($cumPlanned, 2);
            $actual[] = round($cumActual, 2);
        }

        return ['labels'=>$labels, 'planned'=>$planned, 'actual'=>$actual];
    }

    /**
     * Build simple cash projection from s-curve planned series and starting cash
     */
    protected function buildCashProjection($sCurve, $startingCash)
    {
        $labels = $sCurve['labels'] ?? [];
        $planned = $sCurve['planned'] ?? [];
        $projection = [];
        $cash = $startingCash;

        $prevPlanned = 0;
        foreach ($labels as $i => $label) {
            $monthPlanned = $planned[$i] ?? $prevPlanned;
            // month incremental spend is difference between cumulative
            $incremental = $monthPlanned - $prevPlanned;
            $cash -= $incremental; // assume planned spend reduces cash
            $projection[] = ['label'=>$label, 'cash'=>$cash];
            $prevPlanned = $monthPlanned;
        }

        return $projection;
    }

    /**
     * Export spreadsheet as simple XML SpreadsheetML compatible with Excel
     */
    public function exportXlsx(Request $request)
    {
        $groupBy = $request->get('group_by', 'type');
        $filename = 'portfolio_financials_'.date('Ymd_His').'.xml';

        // build rows as in exportCsv
        $rows = [];
        $projects = Project::get();
        $kdvPercent = \App\Services\FinanceService::resolveKdvPercent($request->get('kdv', null));
        if ($groupBy === 'program') {
            $programs = Program::with('projects')->get();
            foreach ($programs as $program) {
                $items = $program->projects;
                $contractValue = $items->sum('budget');
                $budget = $contractValue;
                $spent = $items->sum('spent');
                $remaining = $budget - $spent;
                $estimatedFinishCost = $budget;
                $profitTl = $contractValue - $estimatedFinishCost;
                $profitMargin = $contractValue ? round(($profitTl / $contractValue) * 100,2) : 0;
                $kdvAmount = \App\Services\FinanceService::kdvAmount((float)$contractValue, $kdvPercent);
                $contractWithKdv = \App\Services\FinanceService::applyKdv((float)$contractValue, $kdvPercent);
                $rows[] = [$program->name,$items->count(),$contractValue,$kdvPercent,$kdvAmount,$contractWithKdv,$budget,$spent,$remaining,$estimatedFinishCost,$profitTl,$profitMargin,$profitMargin];
            }
        } else {
            $types = $projects->groupBy('type');
            foreach ($types as $type => $items) {
                $contractValue = $items->sum('budget');
                $budget = $contractValue;
                $spent = $items->sum('spent');
                $remaining = $budget - $spent;
                $estimatedFinishCost = $budget;
                $profitTl = $contractValue - $estimatedFinishCost;
                $profitMargin = $contractValue ? round(($profitTl / $contractValue) * 100,2) : 0;
                $kdvAmount = \App\Services\FinanceService::kdvAmount((float)$contractValue, $kdvPercent);
                $contractWithKdv = \App\Services\FinanceService::applyKdv((float)$contractValue, $kdvPercent);
                $rows[] = [$type ?: 'Unspecified',$items->count(),$contractValue,$kdvPercent,$kdvAmount,$contractWithKdv,$budget,$spent,$remaining,$estimatedFinishCost,$profitTl,$profitMargin,$profitMargin];
            }
        }

        // Build simple SpreadsheetML document
        $xml = '<?xml version="1.0"?>'."\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'."\n";
        $xml .= "  <Worksheet ss:Name=\"Portfolio\">\n";
        $xml .= "    <Table>\n";
        // header
    $headers = ['Group','Project Count','Contract Value','KDV %','KDV Tutarı','Contract Value (KDV Dahil)','Budget','Spent','Remaining','Estimated Finish Cost','Profit TL','Profit Margin %','ROI %'];
        $xml .= "      <Row>\n";
        foreach ($headers as $h) {
            $xml .= '        <Cell><Data ss:Type="String">'.htmlspecialchars($h)."</Data></Cell>\n";
        }
        $xml .= "      </Row>\n";

        foreach ($rows as $r) {
            $xml .= "      <Row>\n";
            foreach ($r as $cell) {
                $type = is_numeric($cell) ? 'Number' : 'String';
                $xml .= '        <Cell><Data ss:Type="'.$type.'">'.htmlspecialchars((string)$cell)."</Data></Cell>\n";
            }
            $xml .= "      </Row>\n";
        }

        $xml .= "    </Table>\n";
        $xml .= "  </Worksheet>\n";
        $xml .= "</Workbook>\n";

        return response($xml, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    /**
     * Portföy performans skoru hesapla (0-100 arası)
     */
    protected function calculatePerformanceScore($projects)
    {
        if ($projects->isEmpty()) return 0;

        $scores = [];

        // Maliyet performansı (%40 ağırlık)
        $costPerformance = 0;
        foreach ($projects as $p) {
            if ($p->budget > 0) {
                $variance = (($p->spent ?? 0) - $p->budget) / $p->budget;
                if ($variance <= 0) $costPerformance += 100; // bütçe altında
                elseif ($variance <= 0.1) $costPerformance += 80; // %10 aşım
                elseif ($variance <= 0.2) $costPerformance += 60; // %20 aşım
                else $costPerformance += 40; // %20+ aşım
            }
        }
        $scores['cost'] = $projects->count() > 0 ? $costPerformance / $projects->count() : 0;

        // Program performansı (%30 ağırlık)
        $schedulePerformance = 0;
        foreach ($projects as $p) {
            if ($p->start_date && $p->end_date) {
                try {
                    $start = Carbon::parse($p->start_date);
                    $end = Carbon::parse($p->end_date);
                    $today = Carbon::today();
                    $progress = $p->progress_percent ?? 0;

                    $totalDays = $start->diffInDays($end);
                    $elapsedDays = $start->diffInDays($today);
                    $expectedProgress = $totalDays > 0 ? min(100, ($elapsedDays / $totalDays) * 100) : 100;

                    $scheduleVariance = $progress - $expectedProgress;
                    if ($scheduleVariance >= 0) $schedulePerformance += 100;
                    elseif ($scheduleVariance >= -10) $schedulePerformance += 80;
                    elseif ($scheduleVariance >= -20) $schedulePerformance += 60;
                    else $schedulePerformance += 40;
                } catch (\Exception $e) {
                    $schedulePerformance += 70; // varsayılan
                }
            } else {
                $schedulePerformance += 70; // varsayılan
            }
        }
        $scores['schedule'] = $projects->count() > 0 ? $schedulePerformance / $projects->count() : 0;

        // Kalite/Tamamlanma performansı (%30 ağırlık)
        $qualityPerformance = 0;
        foreach ($projects as $p) {
            $progress = $p->progress_percent ?? 0;
            if ($progress >= 100) $qualityPerformance += 100;
            elseif ($progress >= 80) $qualityPerformance += 85;
            elseif ($progress >= 60) $qualityPerformance += 70;
            elseif ($progress >= 40) $qualityPerformance += 55;
            else $qualityPerformance += 40;
        }
        $scores['quality'] = $projects->count() > 0 ? $qualityPerformance / $projects->count() : 0;

        // Ağırlıklı toplam
        $totalScore = ($scores['cost'] * 0.4) + ($scores['schedule'] * 0.3) + ($scores['quality'] * 0.3);

        return [
            'total' => round($totalScore, 1),
            'cost' => round($scores['cost'], 1),
            'schedule' => round($scores['schedule'], 1),
            'quality' => round($scores['quality'], 1)
        ];
    }

    /**
     * Aylık trend analizi
     */
    protected function calculateMonthlyTrends($projects)
    {
        $trends = [];
        $months = collect();

        // Son 12 ayın verilerini topla
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->format('M Y');

            // O ay başlayan projeler
            $startedProjects = $projects->filter(function($p) use ($date) {
                return $p->start_date && Carbon::parse($p->start_date)->format('Y-m') === $date->format('Y-m');
            })->count();

            // O ay tamamlanan projeler
            $completedProjects = $projects->filter(function($p) use ($date) {
                return $p->end_date && Carbon::parse($p->end_date)->format('Y-m') === $date->format('Y-m') && ($p->progress_percent ?? 0) >= 100;
            })->count();

            // O ay için budget vs spent
            $monthlyBudget = $projects->filter(function($p) use ($date) {
                if (!$p->start_date || !$p->end_date) return false;
                $start = Carbon::parse($p->start_date);
                $end = Carbon::parse($p->end_date);
                return $date->between($start, $end);
            })->sum('budget');

            $monthlySpent = $projects->filter(function($p) use ($date) {
                if (!$p->start_date || !$p->end_date) return false;
                $start = Carbon::parse($p->start_date);
                $end = Carbon::parse($p->end_date);
                return $date->between($start, $end);
            })->sum('spent');

            $trends[] = [
                'month' => $monthKey,
                'label' => $monthLabel,
                'started_projects' => $startedProjects,
                'completed_projects' => $completedProjects,
                'budget' => $monthlyBudget,
                'spent' => $monthlySpent,
                'efficiency' => $monthlyBudget > 0 ? round(($monthlyBudget - $monthlySpent) / $monthlyBudget * 100, 1) : 0
            ];
        }

        return $trends;
    }
}
