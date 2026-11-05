<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\CostItem;
use App\Models\Payment;
use App\Models\IncidentReport;
use App\Models\Vendor;
use App\Models\VendorReview;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Prepare summary statistics for the dashboard so views don't run extra queries
        $activeProjectsCount = Project::where('status', 'active')->count();
        $totalContractValue = Project::sum('budget');
        $openIncidentsCount = IncidentReport::where('status', 'open')->count();
        $activeVendorsCount = Vendor::where('status', 'active')->count();

        // Provide a small list of active projects for other pages (eg. what-if select)
        $activeProjects = Project::where('status', 'active')->get(['id', 'name']);

        return view('analytics.index', compact(
            'activeProjectsCount',
            'totalContractValue',
            'openIncidentsCount',
            'activeVendorsCount',
            'activeProjects'
        ));
    }

    public function riskMap()
    {
        // Portföy Risk Haritası - Maliyet ve Zaman Sapması
        $projects = Project::with(['costItems', 'milestones'])
            ->whereIn('status', ['active', 'planning'])
            ->get()
            ->map(function ($project) {
                $budget = $this->sumCostItemBudget($project);
                $actual = $this->sumCostItemActual($project);
                $costDeviation = $budget > 0 ? (($actual - $budget) / $budget) * 100 : 0;

                $plannedEnd = $project->end_date;
                $currentDate = now();
                $timeDeviation = 0;
                if ($plannedEnd) {
                    $totalDays = $plannedEnd->diffInDays($project->start_date);
                    $elapsedDays = $currentDate->diffInDays($project->start_date);
                    $timeDeviation = $totalDays > 0 ? (($elapsedDays - $totalDays) / $totalDays) * 100 : 0;
                }

                return [
                    'name' => $project->name,
                    'cost_deviation' => round($costDeviation, 2),
                    'time_deviation' => round($timeDeviation, 2),
                    'status' => $project->status
                ];
            })->values();

        // Guarantee we pass a Collection to the view (not a plain array)
        $projects = collect($projects);

        return view('analytics.risk_map', ['projects' => $projects]);
    }

    public function benchmarking()
    {
        // Taşeron Performans Kıyaslaması
        $vendors = Vendor::with(['reviews', 'projects'])
            ->get()
            ->map(function ($vendor) {
                $reviews = $vendor->reviews;
                $qualityScore = $reviews->avg('quality') ?? 0;
                $timelinessScore = $reviews->avg('schedule') ?? 0;
                $safetyScore = $reviews->avg('safety') ?? 0;
                $costControlScore = $reviews->avg('communication') ?? 0;

                $totalProjects = $vendor->projects->count();
                $completedProjects = $vendor->projects->where('status', 'completed')->count();
                $completionRate = $totalProjects > 0 ? ($completedProjects / $totalProjects) * 100 : 0;

                return [
                    'name' => $vendor->name,
                    'quality' => round($qualityScore, 1),
                    'timeliness' => round($timelinessScore, 1),
                    'safety' => round($safetyScore, 1),
                    'cost_control' => round($costControlScore, 1),
                    'completion_rate' => round($completionRate, 1),
                    'total_projects' => $totalProjects
                ];
            });

        return view('analytics.benchmarking', compact('vendors'));
    }

    public function profitForecast()
    {
        // Proje Kârlılık Tahmini
        $projects = Project::with(['costItems', 'payments'])
            ->whereIn('status', ['active', 'planning'])
            ->get()
            ->map(function ($project) {
                $totalBudget = $this->sumCostItemBudget($project);
                $totalActual = $this->sumCostItemActual($project);
                $totalPaid = $project->payments->sum('amount');

                $currentCost = $totalActual > 0 ? $totalActual : $totalPaid;
                $projectedTotalCost = $this->calculateProjectedCost($project);

                $profit = $totalBudget - $projectedTotalCost;

                return [
                    'name' => $project->name,
                    'budget' => $totalBudget,
                    'current_cost' => $currentCost,
                    'projected_cost' => $projectedTotalCost,
                    'profit' => $profit,
                    'profit_margin' => $totalBudget > 0 ? round(($profit / $totalBudget) * 100, 2) : 0
                ];
            });

        return view('analytics.profit_forecast', compact('projects'));
    }

    public function whatIf()
    {
        $baselineData = $this->buildBaselineData();
        $activeProjects = Project::whereIn('status', ['active', 'planning'])->get(['id', 'name']);

        return view('analytics.what_if', compact('baselineData', 'activeProjects'));
    }

    public function baseline()
    {
        $baselineData = $this->buildBaselineData();
        return response()->json($baselineData);
    }

    public function wizard()
    {
        // Rapor Sihirbazı
        return view('analytics.wizard');
    }

    public function generateReport(Request $request)
    {
        // Rapor oluşturma mantığı
        $dataSource = $request->input('data_source');
        $fields = $request->input('fields', []);
        $filters = $request->input('filters', []);
        $groupBy = $request->input('group_by');
        $chartType = $request->input('chart_type');

        $query = $this->buildQuery($dataSource, $fields, $filters);

        if ($groupBy) {
            $cleanGroupBy = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $groupBy);
            if ($cleanGroupBy !== '') {
                $query->groupBy($cleanGroupBy);
            }
        }

        $results = $query->get();

        return response()->json([
            'data' => $results,
            'chart_type' => $chartType,
            'fields' => $fields
        ]);
    }

    public function saveReport(Request $request)
    {
        // Rapor kaydetme mantığı - ileride database'e kaydedebiliriz
        return response()->json(['message' => 'Rapor kaydedildi']);
    }

    private function calculateProjectedCost($project)
    {
        // Basit projeksiyon: mevcut maliyet trendine göre
        $totalBudget = $this->sumCostItemBudget($project);
        $totalActual = $this->sumCostItemActual($project);

        if ($project->start_date && $project->end_date) {
            $totalDays = $project->end_date->diffInDays($project->start_date);
            $elapsedDays = now()->diffInDays($project->start_date);
            $progress = $totalDays > 0 ? $elapsedDays / $totalDays : 1;

            if ($progress > 0 && $totalActual > 0) {
                $costPerProgress = $totalActual / $progress;
                return $costPerProgress;
            }
        }

        return $totalActual;
    }

    /**
     * Sum budget-like value from a project's cost items.
     * Uses 'revised' if present, falls back to 'original'.
     */
    private function sumCostItemBudget($project)
    {
        return $project->costItems->sum(function ($ci) {
            if (isset($ci->revised) && $ci->revised > 0) return (float) $ci->revised;
            if (isset($ci->original) && $ci->original !== null) return (float) $ci->original;
            return 0;
        });
    }

    /**
     * Sum actual values from a project's cost items.
     */
    private function sumCostItemActual($project)
    {
        return $project->costItems->sum(function ($ci) {
            if (isset($ci->actual) && $ci->actual !== null) return (float) $ci->actual;
            if (isset($ci->committed) && $ci->committed !== null) return (float) $ci->committed;
            return 0;
        });
    }

    private function buildQuery($dataSource, $fields, $filters)
    {

        $modelMap = [
            'projects' => Project::class,
            'cost_items' => CostItem::class,
            'payments' => Payment::class,
            'incidents' => IncidentReport::class,
        ];

        if (!isset($modelMap[$dataSource])) {
            throw new \Exception('Geçersiz veri kaynağı');
        }

        $query = $modelMap[$dataSource]::query();
        $allowedOperators = ['=', '!=', '<>', '>', '<', '>=', '<=', 'like', 'LIKE'];

        // Filtreleri uygula
        foreach ($filters as $filter) {
            if (isset($filter['field'], $filter['operator'], $filter['value'])) {
                $cleanField = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $filter['field']);
                $operator = in_array($filter['operator'], $allowedOperators, true) ? $filter['operator'] : '=';
                if ($cleanField !== '') {
                    $query->where($cleanField, $operator, $filter['value']);
                }
            }
        }

        // Alanları seç
        if (!empty($fields) && is_array($fields)) {
            $cleanFields = array_values(array_filter(array_map(function ($field) {
                return preg_replace('/[^a-zA-Z0-9_]/', '', (string) $field);
            }, $fields)));
            if (!empty($cleanFields)) {
                $query->select($cleanFields);
            }
        }

        return $query;
    }

    /**
     * Build baseline analytics data used by whatIf and API endpoint
     */
    public function buildBaselineData()
    {
    // Include projects that are active or in planning so analytics reflect current portfolio
    $projects = Project::with(['costItems'])->whereIn('status', ['active', 'planning'])->get();

    $portfolioBudget = $projects->sum('budget');
    $totalActual = $projects->map(function ($p) { return $this->sumCostItemActual($p); })->sum();
        $portfolioProfit = $portfolioBudget - $totalActual;

        // average delay estimation (days): average of (elapsed - planned) where available
        $delays = [];
        foreach ($projects as $p) {
            if ($p->start_date && $p->end_date) {
                $totalDays = $p->end_date->diffInDays($p->start_date);
                $elapsedDays = now()->diffInDays($p->start_date);
                $delay = $elapsedDays - $totalDays;
                $delays[] = $delay > 0 ? $delay : 0;
            }
        }
        $avgDelay = count($delays) ? round(array_sum($delays) / count($delays)) : 0;

        // simple risky project count based on cost deviation >10% or time deviation >10%
        $riskyCount = 0;
        foreach ($projects as $p) {
            $budget = $this->sumCostItemBudget($p);
            $actual = $this->sumCostItemActual($p);
            $costDeviation = $budget > 0 ? (($actual - $budget) / $budget) * 100 : 0;
            $timeDeviation = 0;
            if ($p->start_date && $p->end_date) {
                $totalDays = $p->end_date->diffInDays($p->start_date);
                $elapsedDays = now()->diffInDays($p->start_date);
                $timeDeviation = $totalDays > 0 ? (($elapsedDays - $totalDays) / $totalDays) * 100 : 0;
            }
            if ($costDeviation > 10 || $timeDeviation > 10) $riskyCount++;
        }

        // build baseline projects list for the JS simulation (name and originalProfit)
        $baselineProjects = $projects->map(function ($p) {
            $actual = $this->sumCostItemActual($p);
            $originalProfit = $p->budget - $actual;
            return [
                'id' => $p->id,
                'name' => $p->name,
                'originalProfit' => $originalProfit
            ];
        })->values();

        return [
            'portfolioProfit' => (int) $portfolioProfit,
            'avgDelay' => (int) $avgDelay,
            'riskyProjects' => (int) $riskyCount,
            'cashFlowImpact' => (float) (-1 * $portfolioProfit * 0.1),
            'projects' => $baselineProjects
        ];
    }
}
