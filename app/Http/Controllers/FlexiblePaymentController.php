<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlan;
use Illuminate\Http\Request;

class FlexiblePaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $plans = PaymentPlan::orderBy('created_at', 'desc')->paginate(15);
        
        return view('flexible_payments.index', compact('plans'));
    }

    public function create()
    {
        return view('flexible_payments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'installment_count' => 'required|integer|min:1|max:36',
            'interval_days' => 'required|integer|min:1|max:365',
            'down_payment_percentage' => 'required|numeric|min:0|max:100',
            'is_template' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $plan = PaymentPlan::create($validated);
        
        return redirect()->route('flexible-payments.show', $plan)->with('success', 'Ödeme planı şablonu oluşturuldu.');
    }

    public function show(PaymentPlan $paymentPlan)
    {
        $paymentPlan->load('installments');
        
        return view('flexible_payments.show', compact('paymentPlan'));
    }

    public function edit(PaymentPlan $paymentPlan)
    {
        return view('flexible_payments.edit', compact('paymentPlan'));
    }

    public function update(Request $request, PaymentPlan $paymentPlan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'installment_count' => 'required|integer|min:1|max:36',
            'interval_days' => 'required|integer|min:1|max:365',
            'down_payment_percentage' => 'required|numeric|min:0|max:100',
            'is_template' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $paymentPlan->update($validated);
        
        return redirect()->route('flexible-payments.show', $paymentPlan)->with('success', 'Ödeme planı güncellendi.');
    }

    public function destroy(PaymentPlan $paymentPlan)
    {
        $paymentPlan->delete();
        
        return redirect()->route('flexible-payments.index')->with('success', 'Ödeme planı silindi.');
    }

    public function scenarioBuilder()
    {
        return view('flexible_payments.scenario_builder');
    }

    public function calculateScenario(Request $request)
    {
        $validated = $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'installment_count' => 'required|integer|min:1|max:36',
            'interval_days' => 'required|integer|min:1',
            'down_payment_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $down_payment = ($validated['total_amount'] * $validated['down_payment_percentage']) / 100;
        $remaining = $validated['total_amount'] - $down_payment;
        $installment_amount = $remaining / $validated['installment_count'];

        $scenario = [
            'total_amount' => $validated['total_amount'],
            'down_payment' => $down_payment,
            'remaining_amount' => $remaining,
            'installment_count' => $validated['installment_count'],
            'installment_amount' => round($installment_amount, 2),
            'interval_days' => $validated['interval_days'],
        ];

        return response()->json($scenario);
    }
}
