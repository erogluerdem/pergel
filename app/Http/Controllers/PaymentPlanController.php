<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlan;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentPlanController extends Controller
{
    public function index()
    {
        $plans = PaymentPlan::with('invoice.customer')->paginate(15);
        return view('payment_plans.index', compact('plans'));
    }

    public function create()
    {
        $invoices = Invoice::with('customer')
                          ->orderBy('no')
                          ->get();
        return view('payment_plans.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'plan_no' => 'required|string|unique:payment_plans,plan_no',
            'total_amount' => 'required|numeric|min:0',
            'installment_count' => 'required|integer|min:1|max:36',
            'start_date' => 'required|date',
            'payment_interval_days' => 'required|integer|min:1|max:365',
            'status' => 'required|in:active,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $plan = PaymentPlan::create($validated);

        return redirect()->route('payment-plans.show', $plan->id)
                       ->with('success', 'Ödeme planı başarıyla oluşturuldu.');
    }

    public function show($id)
    {
        $paymentPlan = PaymentPlan::with(['invoice.customer', 'installments'])->findOrFail($id);
        return view('payment_plans.show', compact('paymentPlan'));
    }

    public function edit($id)
    {
        $paymentPlan = PaymentPlan::findOrFail($id);
        $invoices = Invoice::with('customer')
                          ->orderBy('no')
                          ->get();
        return view('payment_plans.edit', compact('paymentPlan', 'invoices'));
    }

    public function update(Request $request, $id)
    {
        $paymentPlan = PaymentPlan::findOrFail($id);

        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'plan_no' => 'required|string|unique:payment_plans,plan_no,' . $paymentPlan->id,
            'total_amount' => 'required|numeric|min:0',
            'installment_count' => 'required|integer|min:1|max:36',
            'start_date' => 'required|date',
            'payment_interval_days' => 'required|integer|min:1|max:365',
            'status' => 'required|in:active,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $paymentPlan->update($validated);

        return redirect()->route('payment-plans.show', $paymentPlan->id)
                       ->with('success', 'Ödeme planı başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $paymentPlan = PaymentPlan::findOrFail($id);
        $paymentPlan->delete();

        return redirect()->route('payment-plans.index')
                       ->with('success', 'Ödeme planı başarıyla silindi.');
    }

    public function byInvoice($invoice_id)
    {
        $invoice = Invoice::findOrFail($invoice_id);
        $plans = $invoice->paymentPlans()->paginate(10);
        return view('payment_plans.by_invoice', compact('invoice', 'plans'));
    }
}
