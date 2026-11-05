<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Installment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdvancedReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function applyInvoiceDateRange($query, $startDate, $endDate)
    {
        $cols = [];
        if (Schema::hasTable('invoices')) {
            foreach (['invoice_date', 'issue_date', 'date', 'created_at'] as $col) {
                if (Schema::hasColumn('invoices', $col)) {
                    $cols[] = $col;
                }
            }
        }

        if (empty($cols)) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $coalesceExpr = count($cols) > 1 ? 'COALESCE(' . implode(', ', $cols) . ')' : $cols[0];
        return $query->whereRaw("{$coalesceExpr} BETWEEN ? AND ?", [$startDate, $endDate]);
    }

    public function index()
    {
        return view('advanced_reports.index');
    }

    public function consultantPerformance(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $consultants = User::whereHas('roles', fn($q) => $q->where('name', 'consultant'))
            ->with([
                'invoices' => fn($q) => $this->applyInvoiceDateRange($q, $startDate, $endDate),
            ])
            ->get()
            ->map(function ($consultant) use ($startDate, $endDate) {
                $totalRevenue = $consultant->invoices->sum('amount');
                $totalInvoices = $consultant->invoices->count();
                $paidInvoices = $consultant->invoices->where('payment_status', 'paid')->count();

                return [
                    'id' => $consultant->id,
                    'name' => $consultant->name,
                    'total_revenue' => $totalRevenue,
                    'total_invoices' => $totalInvoices,
                    'paid_invoices' => $paidInvoices,
                    'payment_rate' => $totalInvoices > 0 ? ($paidInvoices / $totalInvoices) * 100 : 0,
                    'average_invoice' => $totalInvoices > 0 ? $totalRevenue / $totalInvoices : 0,
                ];
            });

        return view('advanced_reports.consultant_performance', compact('consultants', 'startDate', 'endDate'));
    }

    public function seasonalAnalysis(Request $request)
    {
        $year = $request->input('year', now()->year);
        $analysis = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = now()->setYear($year)->setMonth($month)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            $invoices = $this->applyInvoiceDateRange(Invoice::query(), $startDate, $endDate)->get();
            $paidAmount = $invoices->where('payment_status', 'paid')->sum('paid_amount');
            $totalAmount = $invoices->sum('amount');

            $analysis[] = [
                'month' => $month,
                'month_name' => trans('months.' . $month),
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'pending_amount' => $totalAmount - $paidAmount,
                'invoice_count' => $invoices->count(),
                'collection_rate' => $totalAmount > 0 ? ($paidAmount / $totalAmount) * 100 : 0,
            ];
        }

        return view('advanced_reports.seasonal_analysis', compact('analysis', 'year'));
    }

    public function paymentAnalysis(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfYear());
        $endDate = $request->input('end_date', now());

        $installments = Installment::whereBetween('due_date', [$startDate, $endDate])
            ->with('paymentPlan.invoice')
            ->get()
            ->groupBy(fn($i) => $i->paymentPlan->invoice->consultant_id ?? 'unknown');

        $analysis = $installments->map(function ($group) {
            $total = $group->sum('amount');
            $paid = $group->where('status', 'paid')->sum('paid_amount');
            $pending = $group->where('status', 'pending')->sum('amount');
            $overdue = $group->where('status', 'overdue')->count();

            return [
                'total_amount' => $total,
                'paid_amount' => $paid,
                'pending_amount' => $pending,
                'overdue_count' => $overdue,
                'collection_rate' => $total > 0 ? ($paid / $total) * 100 : 0,
            ];
        });

        return view('advanced_reports.payment_analysis', compact('analysis', 'startDate', 'endDate'));
    }

    public function activityDashboard(Request $request)
    {
        $days = $request->input('days', 30);
        $startDate = now()->subDays($days);

        $activities = ActivityLog::whereBetween('created_at', [$startDate, now()])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $summary = [
            'total_activities' => $activities->total(),
            'by_action' => ActivityLog::whereBetween('created_at', [$startDate, now()])
                ->groupBy('action')
                ->selectRaw('action, count(*) as count')
                ->get(),
            'by_user' => ActivityLog::whereBetween('created_at', [$startDate, now()])
                ->with('user')
                ->groupBy('user_id')
                ->selectRaw('user_id, count(*) as count')
                ->get(),
        ];

        return view('advanced_reports.activity_dashboard', compact('activities', 'summary', 'days'));
    }

    public function multiDimensionalReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now());
        $dimension = $request->input('dimension', 'consultant');

        $data = match ($dimension) {
            'consultant' => $this->reportByConsultant($startDate, $endDate),
            'project' => $this->reportByProject($startDate, $endDate),
            'payment_status' => $this->reportByPaymentStatus($startDate, $endDate),
            'currency' => $this->reportByCurrency($startDate, $endDate),
            default => [],
        };

        return view('advanced_reports.multi_dimensional', compact('data', 'dimension', 'startDate', 'endDate'));
    }

    private function reportByConsultant($startDate, $endDate)
    {
        return User::whereHas('invoices')
            ->with(['invoices' => function ($q) use ($startDate, $endDate) {
                $this->applyInvoiceDateRange($q, $startDate, $endDate);
            }])
            ->get()
            ->map(function ($u) {
                return [
                    'name' => $u->name,
                    'total' => $u->invoices->sum('amount'),
                    'paid' => $u->invoices->where('payment_status', 'paid')->sum('paid_amount'),
                    'pending' => $u->invoices->where('payment_status', 'pending')->sum('amount'),
                ];
            });
    }

    private function reportByProject($startDate, $endDate)
    {
        return \App\Models\Project::with(['invoices' => function ($q) use ($startDate, $endDate) {
            $this->applyInvoiceDateRange($q, $startDate, $endDate);
        }])
            ->get()
            ->map(function ($p) {
                return [
                    'name' => $p->name,
                    'total' => $p->invoices->sum('amount'),
                    'paid' => $p->invoices->where('payment_status', 'paid')->sum('paid_amount'),
                    'pending' => $p->invoices->where('payment_status', 'pending')->sum('amount'),
                ];
            });
    }

    private function reportByPaymentStatus($startDate, $endDate)
    {
        return $this->applyInvoiceDateRange(Invoice::query(), $startDate, $endDate)
            ->groupBy('payment_status')
            ->selectRaw('payment_status, COUNT(*) as invoice_count, SUM(amount) as total_amount')
            ->get();
    }

    private function reportByCurrency($startDate, $endDate)
    {
        return $this->applyInvoiceDateRange(Invoice::query(), $startDate, $endDate)
            ->with('currency')
            ->groupBy('currency_id')
            ->selectRaw('currency_id, COUNT(*) as invoice_count, SUM(amount) as total_amount')
            ->get();
    }

    public function exportReport(Request $request)
    {
        $reportType = $request->input('type', 'consultant_performance');
        $format = $request->input('format', 'csv');

        $csv = "Rapor Tipi,Tarih,Durum\n\"{$reportType}\",\"" . now()->toDateTimeString() . "\",\"Hazır\"\n";

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="report_' . preg_replace('/[^a-zA-Z0-9_\-]/', '', $reportType) . '.' . ($format === 'csv' ? 'csv' : 'csv') . '"',
        ]);
    }
}
