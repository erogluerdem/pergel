<?php

namespace App\Http\Controllers;

use App\Models\BudgetSimulator;
use App\Models\Project;
use Illuminate\Http\Request;

class BudgetSimulatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = BudgetSimulator::orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::pluck('name', 'id');

        $remainingSum = BudgetSimulator::sum('current_remaining_budget');
        $simulatedSum = BudgetSimulator::sum('simulated_projected_cost');
        $varianceSum = BudgetSimulator::sum('variance_amount');

        $stats = [
            'total_remaining_budget' => $remainingSum,
            'total_simulated_cost' => $simulatedSum,
            'total_variance' => $varianceSum,
            'high_risk_count' => BudgetSimulator::where('risk_level', 'Yuksek')->count(),
            'total_scenarios' => BudgetSimulator::count(),
        ];

        return view('budget_simulators.index', compact('items', 'projects', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'scenario_name' => 'required|string',
            'usd_exchange_rate' => 'required|numeric|gt:0',
            'inflation_expectation' => 'required|numeric|min:0',
            'current_remaining_budget' => 'required|numeric|min:0.01',
            'risk_level' => 'nullable|string',
        ]);

        $remaining = floatval($validated['current_remaining_budget']);
        $usd = floatval($validated['usd_exchange_rate']);
        $inflation = floatval($validated['inflation_expectation']);

        // Simüle Edilen Yeni Maliyet
        $simulatedCost = $remaining * (1 + ($inflation / 100.0));
        $variance = $simulatedCost - $remaining;

        $risk = $validated['risk_level'];
        if (empty($risk)) {
            if ($inflation >= 40 || $usd >= 45 || $variance > ($remaining * 0.35)) {
                $risk = 'Yuksek';
            } elseif ($inflation >= 20 || $usd >= 40 || $variance > ($remaining * 0.15)) {
                $risk = 'Orta';
            } else {
                $risk = 'Dusuk';
            }
        }

        BudgetSimulator::create([
            'scenario_name' => $validated['scenario_name'],
            'usd_exchange_rate' => $usd,
            'inflation_expectation' => $inflation,
            'current_remaining_budget' => $remaining,
            'simulated_projected_cost' => $simulatedCost,
            'variance_amount' => $variance,
            'risk_level' => $risk,
        ]);

        return back()->with('success', 'Bütçe & Enflasyon Simülasyon senaryosu kaydedildi.');
    }

    public function destroy(BudgetSimulator $budgetSimulator)
    {
        $budgetSimulator->delete();
        return back()->with('success', 'Simülasyon senaryosu silindi.');
    }
}
