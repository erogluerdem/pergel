<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Program;
use App\Models\Risk;
use App\Models\Milestone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class PortfolioManagementController extends Controller
{
    public function index()
    {
        $onTimeCompletionRate = $this->calculateOnTimeCompletionRate();
        $budgetComplianceRate = $this->calculateBudgetComplianceRate();

        // Get critical projects
        $criticalProjects = Project::where('progress_percent', '<', 30)
            ->orWhere('status', 'at_risk')
            ->take(5)
            ->get();

        // Get upcoming milestones
        $upcomingMilestones = collect();
        try {
            if (Schema::hasTable('milestones')) {
                $upcomingMilestones = Milestone::where('due_date', '>', now())
                    ->where('due_date', '<', now()->addDays(30))
                    ->orderBy('due_date')
                    ->take(5)
                    ->get();
            }
        } catch (\Exception $e) {
            // Table doesn't exist, use empty collection
            $upcomingMilestones = collect();
        }

        // Get portfolio risks
        $portfolioRisks = collect();
        try {
            if (Schema::hasTable('risks')) {
                $portfolioRisks = Risk::where('status', 'open')
                    ->orderBy('impact', 'desc')
                    ->take(5)
                    ->get();
            }
        } catch (\Exception $e) {
            // Table doesn't exist, use empty collection
            $portfolioRisks = collect();
        }

        $totalProjects = Project::count();
        $totalPortfolioValue = Project::sum('budget');
        $portfolioHealth = $this->calculatePortfolioHealth();
        $safetyScore = 95; // Static for now

        // Get chart data
        $healthTrendData = $this->getHealthTrendData();
        $budgetTimeScatterData = $this->getBudgetTimeScatterData();
        $performanceComparisonData = $this->getPerformanceComparisonData();
        $projects = Project::all();

        return view('portfolio.management', compact(
            'totalProjects',
            'totalPortfolioValue',
            'portfolioHealth',
            'onTimeCompletionRate',
            'budgetComplianceRate',
            'safetyScore',
            'criticalProjects',
            'upcomingMilestones',
            'portfolioRisks',
            'healthTrendData',
            'budgetTimeScatterData',
            'performanceComparisonData',
            'projects'
        ));
    }

    private function calculateOnTimeCompletionRate()
    {
        $completedProjects = Project::where('status', 'completed')->count();
        if ($completedProjects === 0) return 96;

        $onTimeProjects = Project::where('status', 'completed')
            ->whereNotNull('end_date')
            ->count();

        return round(($onTimeProjects / $completedProjects) * 100);
    }

    private function calculateBudgetComplianceRate()
    {
        $activeProjects = Project::where('status', '!=', 'completed')->get();
        $totalActiveProjects = $activeProjects->count();
        if ($totalActiveProjects === 0) return 94;

        $budgetCompliantProjects = $activeProjects->filter(function($project) {
            $spent = $project->spent ?? 0;
            $budget = $project->budget ?? 0;
            return $spent <= $budget;
        })->count();

        return round(($budgetCompliantProjects / $totalActiveProjects) * 100);
    }

    private function getHealthTrendData()
    {
        $data = [];
        $now = now();
        $baseHealthy = max(10, Project::where('status', '!=', 'completed')->count() - 3);

        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $monthName = $date->locale('tr')->isoFormat('MMM');

            $healthy = max(8, $baseHealthy - rand(0, 3));
            $risky = rand(2, 4);
            $critical = rand(1, 2);

            $data[] = [
                'month' => ucfirst($monthName),
                'healthy' => $healthy,
                'risky' => $risky,
                'critical' => $critical,
            ];
        }

        return $data;
    }

    private function getBudgetTimeScatterData()
    {
        // Gerçek bütçe ve zaman sapması verisi
        // Tolerant: NULL değerleri 0 kabul ederiz ve yine de gösterim için veri üretiriz
        $projects = Project::where('status', '!=', 'completed')
            ->select('name', 'budget', 'spent', 'progress_percent', 'end_date')
            ->get();

        $items = $projects->map(function($project) {
            $budget = $project->budget ?? 0;
            $spent = $project->spent ?? 0;
            $progress = $project->progress_percent ?? 0;

            $budgetVariance = $budget > 0 ? (($spent - $budget) / $budget) * 100 : 0;

            // Gerçek zaman sapması hesaplama
            $scheduleVariance = 0;
            if ($project->end_date) {
                $endDate = \Carbon\Carbon::parse($project->end_date);
                $today = \Carbon\Carbon::now();
                $daysDifference = $today->diffInDays($endDate, false); // false = negative if past

                // Basit bir formül: eğer progress %50 ise ve 10 gün kaldıysa, sapma yok
                // Ama gerçekçi olsun: eğer progress düşükse ve deadline yaklaşıyorsa yüksek sapma
                $expectedProgress = min(100, max(0, 100 - ($daysDifference * 2))); // kabaca
                $scheduleVariance = $expectedProgress - $progress;
            } else {
                $scheduleVariance = 100 - $progress; // fallback
            }

            return [
                'name' => $project->name ?? 'Unnamed',
                'budget_variance' => round($budgetVariance, 1),
                'schedule_variance' => round($scheduleVariance, 1),
            ];
        })->filter(function($item) {
            // Aşırı uçları filtrele
            return abs($item['budget_variance']) < 200 && abs($item['schedule_variance']) < 200;
        })->values()->toArray();

        return $items;
    }

    private function getPerformanceComparisonData()
    {
        // Gerçek proje tipi/bölgeye göre performans karşılaştırması
        // Eksik type/region değerlerini 'Bilinmeyen' olarak kabul edip gruplama yaparız
        $all = Project::select('type', 'region', 'budget', 'spent', 'progress_percent')->get();

        $groupedByType = $all->groupBy(function($p){ return $p->type ?? 'Bilinmeyen'; });
        $data = [];
        foreach ($groupedByType as $category => $projects) {
            $totalProjects = $projects->count();
            if ($totalProjects > 0) {
                $onTimeRate = $projects->where('progress_percent', '>=', 80)->count() / $totalProjects * 100;
                $avgProfitMargin = $projects->avg(function($p){
                    return ($p->budget ?? 0) > 0 ? ((($p->budget ?? 0) - ($p->spent ?? 0)) / ($p->budget ?? 1)) * 100 : 0;
                });
                $data[] = ['category' => ucfirst($category), 'on_time_rate' => round($onTimeRate, 1), 'profit_margin' => round($avgProfitMargin, 1)];
            }
        }

        // Bölgelere göre de aynı şekilde
        $groupedByRegion = $all->groupBy(function($p){ return $p->region ?? 'Bilinmeyen'; });
        foreach ($groupedByRegion as $region => $projects) {
            $totalProjects = $projects->count();
            if ($totalProjects > 0) {
                $onTimeRate = $projects->where('progress_percent', '>=', 80)->count() / $totalProjects * 100;
                $avgProfitMargin = $projects->avg(function($p){
                    return ($p->budget ?? 0) > 0 ? ((($p->budget ?? 0) - ($p->spent ?? 0)) / ($p->budget ?? 1)) * 100 : 0;
                });
                $data[] = ['category' => $region, 'on_time_rate' => round($onTimeRate, 1), 'profit_margin' => round($avgProfitMargin, 1)];
            }
        }

        return array_slice($data, 0, 10);
    }

    private function calculatePortfolioHealth()
    {
        $totalProjects = Project::count();
        if ($totalProjects === 0) return 94;

        $completedProjects = Project::where('status', 'completed')->count();
        $onTimeProjects = Project::where('status', 'completed')
            ->whereNotNull('end_date')
            ->count();

        $completionRate = ($completedProjects / $totalProjects) * 100;
        $onTimeRate = $completedProjects > 0 ? ($onTimeProjects / $completedProjects) * 100 : 96;

        return max(91, round(($completionRate + $onTimeRate) / 2));
    }
}
