<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\GeneralExpense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PortfolioController extends Controller
{
    public function allProjects()
    {
        $projects = Project::orderBy('created_at','desc')->get();
        return view('portfolio.all-projects', compact('projects'));
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        // Build an expense report for this project if project_code is present
        $expenseReport = null;
        try {
            if (!empty($project->project_code)) {
                $code = $project->project_code;

                // months for last 12 months
                $months = [];
                $now = Carbon::now()->startOfMonth();
                for ($i = 11; $i >= 0; $i--) {
                    $m = $now->copy()->subMonths($i);
                    $months[] = $m->format('Y-m');
                }

                // Use cache to avoid repeated heavy aggregation for same project_code
                $cacheKey = 'expense_report_' . $code;
                $projectId = $project->id;
                $expenseReport = Cache::remember($cacheKey, now()->addMinutes(10), function() use ($code, $months, $projectId) {

                    // Determine whether to match by project_code or fallback to project_id
                    $matchBy = 'project_code';
                    $matchValue = $code;
                    $exists = DB::table('general_expenses')
                        ->where('project_code', $code)
                        ->whereNotNull('expense_date')
                        ->where('status', '!=', 'rejected')
                        ->exists();
                    if (!$exists) {
                        $matchBy = 'project_id';
                        $matchValue = $projectId;
                    }

                    // DB-level aggregation: category totals
                    $catRows = DB::table('general_expenses')
                    ->select('category', DB::raw('SUM(COALESCE(total_amount, amount, 0)) as total'))
                    ->where($matchBy, $matchValue)
                    ->whereNotNull('expense_date')
                    ->where('status', '!=', 'rejected')
                    ->groupBy('category')
                    ->get();
                    $totalAll = 0.0;
                    $categorySummary = [];
                    foreach ($catRows as $r) {
                        $cat = $r->category ?: 'other';
                        $t = floatval($r->total);
                        $categorySummary[$cat] = [
                            'label' => GeneralExpense::getCategories()[$cat] ?? $cat,
                            'total' => $t,
                            'percent' => 0,
                            'monthly' => []
                        ];
                        $totalAll += $t;
                    }

                    // DB-level aggregation: monthly totals
                        $monthlyRows = DB::table('general_expenses')
                            ->select(DB::raw("DATE_FORMAT(expense_date, '%Y-%m') as ym"), DB::raw('SUM(COALESCE(total_amount, amount, 0)) as total'))
                            ->where($matchBy, $matchValue)
                        ->whereNotNull('expense_date')
                        ->where('status', '!=', 'rejected')
                        ->where('expense_date', '>=', Carbon::now()->startOfMonth()->subMonths(11)->toDateString())
                        ->groupBy('ym')
                        ->orderBy('ym')
                        ->get()
                    ;

                    $totalByMonth = array_fill_keys($months, 0.0);
                    foreach ($monthlyRows as $mrow) {
                        $k = $mrow->ym;
                        if (array_key_exists($k, $totalByMonth)) {
                            $totalByMonth[$k] = floatval($mrow->total);
                        }
                    }

                    // monthly per category (optional detailed) - get category+ym aggregation
                    $catMonthRows = DB::table('general_expenses')
                        ->select('category', DB::raw("DATE_FORMAT(expense_date, '%Y-%m') as ym"), DB::raw('SUM(COALESCE(total_amount, amount, 0)) as total'))
                        ->where($matchBy, $matchValue)
                        ->whereNotNull('expense_date')
                        ->where('status', '!=', 'rejected')
                        ->where('expense_date', '>=', Carbon::now()->startOfMonth()->subMonths(11)->toDateString())
                        ->groupBy('category', 'ym')
                        ->get();

                    // fill monthly for categories
                    foreach ($categorySummary as $cat => &$c) {
                        $c['monthly'] = array_fill_keys($months, 0.0);
                    }
                    unset($c);

                    foreach ($catMonthRows as $r) {
                        $cat = $r->category ?: 'other';
                        $ym = $r->ym;
                        $amt = floatval($r->total);
                        if (!isset($categorySummary[$cat])) {
                            $categorySummary[$cat] = [
                                'label' => GeneralExpense::getCategories()[$cat] ?? $cat,
                                'total' => $amt,
                                'percent' => 0,
                                'monthly' => array_fill_keys($months, 0.0)
                            ];
                        }
                        if (array_key_exists($ym, $categorySummary[$cat]['monthly'])) {
                            $categorySummary[$cat]['monthly'][$ym] = $amt;
                        }
                    }

                    // compute percents
                    foreach ($categorySummary as $cat => &$c) {
                        $c['percent'] = $totalAll > 0 ? round(($c['total'] / $totalAll) * 100, 2) : 0;
                    }
                    unset($c);

                    return [
                        'months' => $months,
                        'categories' => $categorySummary,
                        'total_by_month' => $totalByMonth,
                        'total' => $totalAll,
                    ];
                });
            }
        } catch (\Exception $ex) {
            // fail silently
            $expenseReport = null;
        }

        return view('portfolio.project-detail', compact('project','expenseReport'));
    }
}
