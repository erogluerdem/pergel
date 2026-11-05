<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesInstallmentReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function monthlyReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $projectId = $request->get('project_id');

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $invoices = Invoice::with('customer', 'project')
            ->when($projectId, fn($q) => $q->where('project_id', $projectId))
            ->whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end)
            ->get();

        $installments = [];
        $totals = ['invoice_count' => 0, 'total_amount' => 0, 'paid_amount' => 0, 'remaining_amount' => 0, 'payment_count' => 0];

        foreach ($invoices as $invoice) {
            $paid = $invoice->paid_amount ?? 0;
            $remaining = ($invoice->amount ?? 0) - $paid;
            $installments[] = [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->no ?? 'N/A',
                'customer_name' => $invoice->customer?->name ?? '-',
                'invoice_date' => $invoice->date,
                'total_amount' => $invoice->amount ?? 0,
                'paid_amount' => $paid,
                'remaining_amount' => $remaining,
                'payment_percentage' => ($invoice->amount ?? 0) > 0 ? ($paid / ($invoice->amount ?? 1)) * 100 : 0,
                'payment_count' => 0,
            ];
            $totals['invoice_count']++;
            $totals['total_amount'] += $invoice->amount ?? 0;
            $totals['paid_amount'] += $paid;
            $totals['remaining_amount'] += $remaining;
        }

        $projects = Project::orderBy('name')->get();
        return view('reports.sales.monthly-installment', compact('installments', 'totals', 'startDate', 'endDate', 'projects', 'projectId'));
    }

    public function byInvoice($invoice)
    {
        $invoice = Invoice::with('customer')->findOrFail($invoice);
        $totalPaid = $invoice->paid_amount ?? 0;
        $remaining = ($invoice->amount ?? 0) - $totalPaid;
        $payments = collect(); // Empty collection since we don't have separate payments table

        return view('reports.sales.invoice-installments', compact('invoice', 'totalPaid', 'remaining', 'payments'));
    }

    public function paymentTimeline(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $customerId = $request->get('customer_id');

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $timeline = [];
        $totals = ['on_time_payments' => 0, 'early_payments' => 0, 'late_payments' => 0, 'actual_amount' => 0];

        $customers = Customer::orderBy('name')->get();
        return view('reports.sales.payment-timeline', compact('timeline', 'totals', 'startDate', 'endDate', 'customers', 'customerId'));
    }

    public function balanceDetail(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $status = $request->get('status');

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $invoices = Invoice::with('customer')
            ->whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end)
            ->get();

        $balances = [];
        $totals = ['total_balance' => 0, 'paid_amount' => 0, 'remaining_amount' => 0, 'overdue_amount' => 0];

        foreach ($invoices as $invoice) {
            $paid = $invoice->paid_amount ?? 0;
            $remaining = ($invoice->amount ?? 0) - $paid;
            $isOverdue = $invoice->due_date && $invoice->due_date->isPast() && $remaining > 0;

            if ($status) {
                $matchStatus = false;
                if ($status === 'paid' && $remaining == 0) $matchStatus = true;
                if ($status === 'unpaid' && $paid == 0) $matchStatus = true;
                if ($status === 'partial' && $paid > 0 && $remaining > 0) $matchStatus = true;
                if ($status === 'overdue' && $isOverdue) $matchStatus = true;
                if (!$matchStatus) continue;
            }

            $balances[] = [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->no ?? 'N/A',
                'customer_name' => $invoice->customer?->name ?? '-',
                'invoice_date' => $invoice->date,
                'invoice_amount' => $invoice->amount ?? 0,
                'paid_amount' => $paid,
                'remaining_amount' => $remaining,
                'payment_percentage' => ($invoice->amount ?? 0) > 0 ? ($paid / ($invoice->amount ?? 1)) * 100 : 0,
                'is_overdue' => $isOverdue,
            ];

            $totals['total_balance'] += $remaining;
            $totals['paid_amount'] += $paid;
            $totals['remaining_amount'] += $remaining;
            if ($isOverdue) $totals['overdue_amount'] += $remaining;
        }

        return view('reports.sales.balance-detail', compact('balances', 'totals', 'startDate', 'endDate', 'status'));
    }

    public function accountingSummary(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $projectId = $request->get('project_id');

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $invoices = Invoice::with('customer')
            ->when($projectId, fn($q) => $q->where('project_id', $projectId))
            ->whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end)
            ->get();

        $summary = [];
        $accounting = ['total_sales' => 0, 'paid_sales' => 0, 'pending_sales' => 0, 'total_discount' => 0, 'on_time_payments' => 0, 'overdue_payments' => 0, 'overdue_amount' => 0, 'not_paid_yet' => 0];

        foreach ($invoices as $invoice) {
            $paid = $invoice->paid_amount ?? 0;
            $remaining = ($invoice->amount ?? 0) - $paid;
            $isOverdue = $invoice->due_date && $invoice->due_date->isPast() && $remaining > 0;

            $summary[] = [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->no ?? 'N/A',
                'customer_name' => $invoice->customer?->name ?? '-',
                'invoice_date' => $invoice->date,
                'total_amount' => $invoice->amount ?? 0,
                'discount_amount' => 0,
                'net_amount' => $invoice->amount ?? 0,
                'paid_amount' => $paid,
                'remaining_amount' => $remaining,
                'is_overdue' => $isOverdue,
            ];

            $accounting['total_sales'] += $invoice->amount ?? 0;
            $accounting['paid_sales'] += $paid;
            $accounting['pending_sales'] += $remaining;
            if ($isOverdue) { $accounting['overdue_amount'] += $remaining; $accounting['overdue_payments']++; }
            if ($paid == 0) $accounting['not_paid_yet'] += $invoice->amount ?? 0;
        }

        $accounting['on_time_payments'] = count($invoices) - $accounting['overdue_payments'];
        $projects = Project::orderBy('name')->get();

        return view('reports.sales.accounting-summary', compact('summary', 'accounting', 'startDate', 'endDate', 'projects', 'projectId'));
    }

    public function exportAccountingSummary(Request $request)
    {
        return redirect()->route('reports.sales.accounting_summary')->with('message', 'Export coming soon!');
    }
}
