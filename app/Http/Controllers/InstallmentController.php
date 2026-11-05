<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\PaymentPlan;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $installments = Installment::with(['paymentPlan', 'invoice.customer', 'receivedBy'])
            ->orderBy('due_date', 'asc')
            ->paginate(20);

        $stats = [
            'total_pending' => Installment::where('status', 'pending')->sum('amount'),
            'total_overdue' => Installment::where('status', 'pending')->where('due_date', '<', today())->sum('amount'),
            'total_paid' => Installment::where('status', 'paid')->sum('paid_amount'),
            'upcoming_count' => Installment::where('status', 'pending')->whereDate('due_date', '>=', today())->whereDate('due_date', '<=', today()->addDays(7))->count(),
        ];

        return view('installments.index', compact('installments', 'stats'));
    }

    public function create(Request $request)
    {
        $paymentPlans = PaymentPlan::orderBy('plan_no')->get();
        $selectedPlanId = $request->get('payment_plan_id');
        $paymentPlan = $selectedPlanId ? PaymentPlan::find($selectedPlanId) : null;

        return view('installments.create', compact('paymentPlans', 'paymentPlan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_plan_id' => 'required|exists:payment_plans,id',
            'installment_number' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $paymentPlan = PaymentPlan::findOrFail($validated['payment_plan_id']);
        $validated['invoice_id'] = $paymentPlan->invoice_id;
        $validated['status'] = 'pending';

        $installment = Installment::create($validated);

        return redirect()->route('installments.show', $installment->id)
            ->with('success', 'Taksit kaydı başarıyla oluşturuldu.');
    }

    public function show($id)
    {
        $installment = Installment::with(['paymentPlan', 'invoice.customer', 'receivedBy'])->findOrFail($id);
        return view('installments.show', compact('installment'));
    }

    public function edit($id)
    {
        $installment = Installment::with('paymentPlan')->findOrFail($id);
        $paymentPlans = PaymentPlan::orderBy('plan_no')->get();
        return view('installments.edit', compact('installment', 'paymentPlans'));
    }

    public function update(Request $request, $id)
    {
        $installment = Installment::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'penalty_amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,paid,partial',
        ]);

        $installment->update($validated);

        return redirect()->route('installments.show', $installment->id)
            ->with('success', 'Taksit bilgileri başarıyla güncellendi.');
    }

    public function markAsPaid(Request $request, $id)
    {
        $installment = Installment::findOrFail($id);

        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0.01',
            'paid_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|in:cash,check,transfer,credit_card',
            'transaction_reference' => 'nullable|string|max:100',
        ]);

        $installment->markAsPaid(
            paidAmount: $validated['paid_amount'],
            paidDate: $validated['paid_date'],
            method: $validated['payment_method'],
            reference: $validated['transaction_reference'] ?? null,
            receivedBy: auth()->id()
        );

        return redirect()->route('installments.show', $installment->id)
            ->with('success', 'Taksit tahsilat kaydı başarıyla işlendi.');
    }

    public function destroy($id)
    {
        $installment = Installment::findOrFail($id);
        $installment->delete();

        return redirect()->route('installments.index')
            ->with('success', 'Taksit kaydı silindi.');
    }

    // Payment status report
    public function paymentStatusReport()
    {
        $overdue = Installment::where('status', 'pending')
            ->where('due_date', '<', today())
            ->with(['invoice.customer', 'paymentPlan'])
            ->get();

        $upcoming = Installment::where('status', 'pending')
            ->whereDate('due_date', '>=', today())
            ->whereDate('due_date', '<=', today()->addDays(30))
            ->with(['invoice.customer', 'paymentPlan'])
            ->orderBy('due_date')
            ->get();

        $stats = [
            'overdue_amount' => $overdue->sum('amount'),
            'overdue_count' => $overdue->count(),
            'upcoming_amount' => $upcoming->sum('amount'),
            'upcoming_count' => $upcoming->count(),
            'total_collected' => Installment::where('status', 'paid')->sum('paid_amount'),
        ];

        return view('installments.payment-status-report', compact('overdue', 'upcoming', 'stats'));
    }

    // Installment tracking
    public function byInvoice($invoice_id)
    {
        $invoice = Invoice::findOrFail($invoice_id);
        $installments = $invoice->installments()
            ->orderBy('installment_number')
            ->with('receivedBy')
            ->get();

        return view('installments.by-invoice', compact('invoice', 'installments'));
    }
}
