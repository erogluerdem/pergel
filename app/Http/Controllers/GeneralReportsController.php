<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GeneralReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function kpiDashboard(Request $request)
    {
        $period = $request->get('period', 'weekly');
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $invoices = Invoice::whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end)
            ->get();

        $allInvoices = Invoice::all();
        $overdue = 0;

        foreach ($allInvoices as $inv) {
            if ($inv->due_date && $inv->due_date->isPast() && ($inv->amount ?? 0) > ($inv->paid_amount ?? 0)) {
                $overdue++;
            }
        }

        // Calculate collection rate
        $totalAmount = $invoices->sum('amount') ?? 0;
        $totalPaid = $invoices->sum('paid_amount') ?? 0;
        $collectionRate = $totalAmount > 0 ? ($totalPaid / $totalAmount) * 100 : 0;

        $kpis = [
            'sales_count' => $invoices->count(),
            'sales_value' => $totalAmount,
            'payments' => $totalPaid,
            'collection_rate' => $collectionRate,
            'new_customers' => $invoices->pluck('customer_id')->unique()->count(),
            'meetings' => 0, // Placeholder - integrate CustomerMeeting model if needed
            'documents' => 0, // Placeholder - integrate PropertyDocument model if needed
            'active_projects' => Project::count(),
        ];

        return view('reports.general.kpi-dashboard', compact('kpis', 'period', 'startDate', 'endDate'));
    }

    public function performanceGraphs(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(6)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $invoices = Invoice::whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end)
            ->get();

        // Build monthly sales trend
        $salesTrend = [];
        $currentDate = $start->copy();

        while ($currentDate <= $end) {
            $monthStart = $currentDate->copy()->startOfMonth();
            $monthEnd = $currentDate->copy()->endOfMonth();

            $monthInvoices = Invoice::whereDate('date', '>=', $monthStart)
                ->whereDate('date', '<=', $monthEnd)
                ->get();

            $salesTrend[] = [
                'month' => $currentDate->format('Y-m'),
                'value' => $monthInvoices->sum('amount') ?? 0,
            ];

            $currentDate->addMonth();
        }

        // Build top customers list
        $customerTotals = [];
        foreach ($invoices as $invoice) {
            $customerId = $invoice->customer_id;
            if (!isset($customerTotals[$customerId])) {
                $customerTotals[$customerId] = [
                    'customer_id' => $customerId,
                    'name' => $invoice->customer?->name ?? 'Bilinmiyor',
                    'total' => 0,
                ];
            }
            $customerTotals[$customerId]['total'] += $invoice->amount ?? 0;
        }

        // Sort by total and get top 10
        usort($customerTotals, function($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        $topCustomers = array_slice($customerTotals, 0, 10);

        $monthlyData = [];
        $currentDate = $start->copy();

        while ($currentDate <= $end) {
            $monthStart = $currentDate->copy()->startOfMonth();
            $monthEnd = $currentDate->copy()->endOfMonth();

            $monthInvoices = Invoice::whereDate('date', '>=', $monthStart)
                ->whereDate('date', '<=', $monthEnd)
                ->get();

            $monthlyData[] = [
                'month' => $currentDate->format('Y-m'),
                'sales' => $monthInvoices->sum('amount') ?? 0,
                'invoices' => $monthInvoices->count(),
                'paid' => $monthInvoices->sum('paid_amount') ?? 0,
            ];

            $currentDate->addMonth();
        }

        $chartData = json_encode($monthlyData);

        return view('reports.general.performance-graphs', compact('salesTrend', 'topCustomers', 'monthlyData', 'chartData', 'startDate', 'endDate'));
    }

    public function salesAnalysis(Request $request)
    {
        $analysisType = $request->get('type', 'monthly');
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $invoices = Invoice::whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end)
            ->get();

        $analysis = [];

        if ($analysisType === 'monthly') {
            $currentDate = $start->copy();
            while ($currentDate <= $end) {
                $monthStart = $currentDate->copy()->startOfMonth();
                $monthEnd = $currentDate->copy()->endOfMonth();

                $monthInvoices = Invoice::whereDate('date', '>=', $monthStart)
                    ->whereDate('date', '<=', $monthEnd)
                    ->get();

                $totalSales = $monthInvoices->sum('amount') ?? 0;
                $invoiceCount = $monthInvoices->count();

                $analysis[] = [
                    'period' => $currentDate->format('Y-m'),
                    'total_sales' => $totalSales,
                    'invoice_count' => $invoiceCount,
                    'avg_sale' => $invoiceCount > 0 ? $totalSales / $invoiceCount : 0,
                    'total_discount' => 0,
                    'customer_count' => $monthInvoices->pluck('customer_id')->unique()->count(),
                ];

                $currentDate->addMonth();
            }
        } elseif ($analysisType === 'yearly') {
            $currentDate = $start->copy();
            while ($currentDate->year <= $end->year) {
                $yearStart = $currentDate->copy()->startOfYear();
                $yearEnd = $currentDate->copy()->endOfYear();

                $yearInvoices = Invoice::whereDate('date', '>=', $yearStart)
                    ->whereDate('date', '<=', $yearEnd)
                    ->get();

                $totalSales = $yearInvoices->sum('amount') ?? 0;
                $invoiceCount = $yearInvoices->count();

                $analysis[] = [
                    'period' => $currentDate->format('Y'),
                    'total_sales' => $totalSales,
                    'invoice_count' => $invoiceCount,
                    'avg_sale' => $invoiceCount > 0 ? $totalSales / $invoiceCount : 0,
                    'total_discount' => 0,
                    'customer_count' => $yearInvoices->pluck('customer_id')->unique()->count(),
                ];

                $currentDate->addYear();
            }
        } else {
            // Weekly analysis
            $currentDate = $start->copy();
            while ($currentDate <= $end) {
                $weekStart = $currentDate->copy()->startOfWeek();
                $weekEnd = $currentDate->copy()->endOfWeek();

                $weekInvoices = Invoice::whereDate('date', '>=', $weekStart)
                    ->whereDate('date', '<=', $weekEnd)
                    ->get();

                $totalSales = $weekInvoices->sum('amount') ?? 0;
                $invoiceCount = $weekInvoices->count();

                $analysis[] = [
                    'period' => $weekStart->format('Y-m-d') . ' ~ ' . $weekEnd->format('Y-m-d'),
                    'total_sales' => $totalSales,
                    'invoice_count' => $invoiceCount,
                    'avg_sale' => $invoiceCount > 0 ? $totalSales / $invoiceCount : 0,
                    'total_discount' => 0,
                    'customer_count' => $weekInvoices->pluck('customer_id')->unique()->count(),
                ];

                $currentDate->addWeek();
            }
        }

        $totals = [
            'total_sales' => $invoices->sum('amount') ?? 0,
            'total_invoices' => $invoices->count(),
            'total_customers' => $invoices->pluck('customer_id')->unique()->count(),
        ];

        return view('reports.general.sales-analysis', compact('analysis', 'totals', 'analysisType', 'startDate', 'endDate'));
    }

    public function dynamicQuery(Request $request)
    {
        $queryType = $request->get('query_type', 'invoices');
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $query = Invoice::with('customer')
            ->whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end);

        // Get filter parameters from request
        $customerId = $request->get('customer_id');
        $projectId = $request->get('project_id');
        $status = $request->get('status');
        $minAmount = $request->get('min_amount');
        $maxAmount = $request->get('max_amount');

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($minAmount) {
            $query->where('amount', '>=', $minAmount);
        }

        if ($maxAmount) {
            $query->where('amount', '<=', $maxAmount);
        }

        $invoices = $query->get();
        $customers = Customer::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        // Build filters array for view
        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'customer_id' => $customerId,
            'project_id' => $projectId,
            'status' => $status,
            'min_amount' => $minAmount,
            'max_amount' => $maxAmount,
        ];

        $totals = [
            'invoice_count' => $invoices->count(),
            'total_amount' => $invoices->sum('amount') ?? 0,
            'paid_amount' => $invoices->sum('paid_amount') ?? 0,
            'remaining_amount' => $invoices->sum(fn($i) => (($i->amount ?? 0) - ($i->paid_amount ?? 0))),
        ];

        return view('reports.general.dynamic-query', compact('invoices', 'customers', 'projects', 'filters', 'totals', 'queryType', 'startDate', 'endDate'));
    }

    public function availablePortfolio(Request $request)
    {
        $projectId = $request->get('project_id');
        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->addMonths(12)->format('Y-m-d'));

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $query = Project::orderBy('name');
        
        if ($projectId) {
            $query->where('id', $projectId);
        }
        
        $projects = $query->get();
        $allProjects = Project::orderBy('name')->get();

        $portfolio = [];
        $totals = [
            'available_assets' => 0,
            'total_assets' => 0,
            'sold_assets' => 0,
            'avg_availability' => 0,
        ];

        foreach ($projects as $project) {
            $budget = $project->budget ?? 0;
            
            // Get sold amount directly from invoices table for this date range
            $sold = Invoice::whereDate('date', '>=', $start)
                ->whereDate('date', '<=', $end)
                ->sum('amount') ?? 0;
            
            $available = $budget - $sold;

            $availabilityPercentage = $budget > 0 ? ($available / $budget) * 100 : 0;

            $portfolio[] = [
                'project_id' => $project->id,
                'name' => $project->name,
                'total_assets' => $budget,
                'available_assets' => max(0, $available),
                'sold_assets' => $sold,
                'availability_percentage' => $availabilityPercentage,
            ];

            $totals['total_assets'] += $budget;
            $totals['sold_assets'] += $sold;
            $totals['available_assets'] += max(0, $available);
        }

        // Calculate average availability percentage
        if (count($portfolio) > 0) {
            $totals['avg_availability'] = array_sum(array_column($portfolio, 'availability_percentage')) / count($portfolio);
        }

        return view('reports.general.available-portfolio', compact('portfolio', 'totals', 'allProjects', 'projectId', 'startDate', 'endDate'));
    }
}
