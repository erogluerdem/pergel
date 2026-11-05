<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProjectAnalysisReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function financialAnalysis(Request $request)
    {
        $projectId = $request->get('project_id');
        $startDate = $request->get('start_date', now()->subMonths(6)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $projects = Project::orderBy('name')->get();
        $analysis = [];
        $totals = ['total_budget' => 0, 'total_revenue' => 0, 'total_cost' => 0, 'total_profit' => 0];

        if ($projectId) {
            $project = Project::find($projectId);
            // Get all invoices for date range (not project-specific since Invoice table has no project_id)
            $invoices = Invoice::whereDate('date', '>=', $start)
                ->whereDate('date', '<=', $end)
                ->get();

            $totalSales = $invoices->sum('amount') ?? 0;
            $budget = $project->budget ?? 0;
            $variance = $totalSales - $budget;

            $analysis = [
                'project_id' => $project->id,
                'project_name' => $project->name,
                'budget' => $budget,
                'actual_sales' => $totalSales,
                'variance' => $variance,
                'variance_percentage' => $budget > 0 ? ($variance / $budget) * 100 : 0,
                'invoice_count' => $invoices->count(),
                'average_invoice' => $invoices->count() > 0 ? $totalSales / $invoices->count() : 0,
            ];

            $totals = [
                'total_budget' => $budget,
                'total_revenue' => $totalSales,
                'total_cost' => 0,
                'total_profit' => $totalSales - $budget,
            ];
        } else {
            $projectsList = Project::orderBy('name')->get();
            $allInvoices = Invoice::whereDate('date', '>=', $start)
                ->whereDate('date', '<=', $end)
                ->get();

            foreach ($projectsList as $project) {
                // Show all invoices for all projects (since Invoice table has no project_id)
                $totalSales = $allInvoices->sum('amount') ?? 0;
                $budget = $project->budget ?? 0;
                $variance = $totalSales - $budget;

                $analysis[] = [
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'budget' => $budget,
                    'actual_sales' => $totalSales,
                    'variance' => $variance,
                    'variance_percentage' => $budget > 0 ? ($variance / $budget) * 100 : 0,
                    'invoice_count' => $allInvoices->count(),
                ];

                $totals['total_budget'] += $budget;
                $totals['total_revenue'] += $totalSales;
                $totals['total_cost'] += 0;
                $totals['total_profit'] += ($totalSales - $budget);
            }
        }

        $statusFilter = $request->get('status', '');

        return view('reports.projects.financial-analysis', compact('analysis', 'totals', 'projects', 'projectId', 'startDate', 'endDate', 'statusFilter'));
    }

    public function stockSalesBalance(Request $request)
    {
        $projectId = $request->get('project_id');
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $projects = Project::orderBy('name')->get();
        $balanceData = [];
        $totals = ['total_assets' => 0, 'total_sold' => 0, 'total_available' => 0, 'total_sales_value' => 0];

        // Get all invoices for the date range (Invoice table has no project_id)
        $allInvoices = Invoice::whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end)
            ->get();

        $query = Project::orderBy('name');

        if ($projectId) {
            $query->where('id', $projectId);
        }

        $projectsData = $query->get();

        foreach ($projectsData as $project) {
            $budget = $project->budget ?? 0;
            $sales = $allInvoices->sum('amount') ?? 0;
            $remaining = $budget - $sales;
            $avg_price = $allInvoices->count() > 0 ? $sales / $allInvoices->count() : 0;

            $balanceData[] = [
                'project_id' => $project->id,
                'project_name' => $project->name,
                'name' => $project->name,
                'stock_amount' => $budget,
                'sales_amount' => $sales,
                'remaining_amount' => $remaining,
                'total_assets' => $budget,
                'sold_assets' => $sales,
                'available_assets' => $remaining,
                'total_sales_value' => $sales,
                'average_asset_price' => $avg_price,
                'sales_percentage' => $budget > 0 ? ($sales / $budget) * 100 : 0,
                'invoice_count' => $allInvoices->count(),
            ];

            $totals['total_assets'] += $budget;
            $totals['total_sold'] += $sales;
            $totals['total_available'] += $remaining;
            $totals['total_sales_value'] += $sales;
        }

        $statusFilter = $request->get('status', '');

        return view('reports.projects.stock-sales-balance', compact('balanceData', 'totals', 'projects', 'projectId', 'startDate', 'endDate', 'statusFilter'));
    }

    public function revenueAnalysis(Request $request)
    {
        $projectId = $request->get('project_id');
        $startDate = $request->get('start_date', now()->subMonths(12)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $projects = Project::orderBy('name')->get();
        $revenue = [];
        $totals = ['total_billed' => 0, 'total_paid' => 0, 'total_variance' => 0, 'average_collection_rate' => 0];

        // Get all invoices for the date range (Invoice table has no project_id)
        $allInvoices = Invoice::whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end)
            ->get();

        $query = Project::orderBy('name');

        if ($projectId) {
            $query->where('id', $projectId);
        }

        $projectsData = $query->get();

        foreach ($projectsData as $project) {
            $expected = $project->budget ?? 0;
            $actual = $allInvoices->sum('amount') ?? 0;
            $difference = $actual - $expected;

            $revenue[] = [
                'project_id' => $project->id,
                'project_name' => $project->name,
                'expected_revenue' => $expected,
                'actual_revenue' => $actual,
                'difference' => $difference,
                'achievement_percentage' => $expected > 0 ? ($actual / $expected) * 100 : 0,
            ];

            $totals['total_billed'] += $expected;
            $totals['total_paid'] += $actual;
            $totals['total_variance'] += $difference;
        }

        if ($totals['total_billed'] > 0) {
            $totals['average_collection_rate'] = ($totals['total_paid'] / $totals['total_billed']) * 100;
        }

        $statusFilter = $request->get('status', '');

        return view('reports.projects.revenue-analysis', compact('revenue', 'totals', 'projects', 'projectId', 'startDate', 'endDate', 'statusFilter'));
    }

    public function discountAndProfit(Request $request)
    {
        $projectId = $request->get('project_id');
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $projects = Project::orderBy('name')->get();
        $profitData = [];
        $totals = ['gross_sales' => 0, 'total_discount' => 0, 'net_sales' => 0, 'net_profit' => 0];

        // Get all invoices for the date range (Invoice table has no project_id)
        $allInvoices = Invoice::whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end)
            ->get();

        $query = Project::orderBy('name');

        if ($projectId) {
            $query->where('id', $projectId);
        }

        $projectsData = $query->get();

        foreach ($projectsData as $project) {
            $grossSales = $allInvoices->sum('amount') ?? 0;
            $totalTax = $allInvoices->sum('tax_amount') ?? 0;
            $discount = 0;
            $profit = $grossSales - $totalTax;

            $profitData[] = [
                'project_id' => $project->id,
                'project_name' => $project->name,
                'gross_sales' => $grossSales,
                'discount_amount' => $discount,
                'net_sales' => $grossSales - $discount,
                'profit' => $profit,
                'profit_margin' => $grossSales > 0 ? ($profit / $grossSales) * 100 : 0,
                'invoice_count' => $allInvoices->count(),
            ];

            $totals['gross_sales'] += $grossSales;
            $totals['total_discount'] += $discount;
            $totals['net_sales'] = $totals['gross_sales'] - $totals['total_discount'];
            $totals['net_profit'] += $profit;
        }

        $statusFilter = $request->get('status', '');

        return view('reports.projects.discount-profit', compact('profitData', 'totals', 'projects', 'projectId', 'startDate', 'endDate', 'statusFilter'));
    }

    public function costComparison(Request $request)
    {
        $projectId = $request->get('project_id');
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $groupBy = $request->get('group_by', 'daily');

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $projects = Project::orderBy('name')->get();
        $comparison = [];
        $totals = ['total_sales' => 0, 'total_costs' => 0, 'total_margin' => 0];

        // Get all invoices for the date range (Invoice table has no project_id)
        $allInvoices = Invoice::whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end)
            ->get();

        // Group invoices by period (daily, weekly, monthly)
        $grouped = [];
        foreach ($allInvoices as $invoice) {
            $date = Carbon::parse($invoice->date);
            if ($groupBy === 'weekly') {
                $key = $date->format('Y-\WW');
            } elseif ($groupBy === 'monthly') {
                $key = $date->format('Y-m');
            } else {
                $key = $date->format('Y-m-d');
            }

            if (!isset($grouped[$key])) {
                $grouped[$key] = [];
            }
            $grouped[$key][] = $invoice;
        }

        // Process grouped data
        foreach ($grouped as $period => $invoices) {
            $sales = collect($invoices)->sum('amount') ?? 0;
            $costs = collect($invoices)->sum('tax_amount') ?? 0;
            $margin = $sales - $costs;

            $comparison[] = [
                'period_label' => $period,
                'sales' => $sales,
                'costs' => $costs,
                'margin' => $margin,
                'margin_percentage' => $sales > 0 ? ($margin / $sales) * 100 : 0,
            ];

            $totals['total_sales'] += $sales;
            $totals['total_costs'] += $costs;
            $totals['total_margin'] += $margin;
        }

        $statusFilter = $request->get('status', '');

        return view('reports.projects.cost-comparison', compact('comparison', 'totals', 'projects', 'projectId', 'startDate', 'endDate', 'statusFilter', 'groupBy'));
    }
}
