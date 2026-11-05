<?php

namespace App\Http\Controllers;

use App\Models\BudgetAllocation;
use App\Models\Project;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $query = BudgetAllocation::with('project');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $budgets = $query->orderBy('created_at', 'desc')->paginate(20);
        $projects = Project::all();

        $totalAllocated = BudgetAllocation::sum('allocated_amount');
        $totalSpent = BudgetAllocation::sum('spent_amount');
        $totalRemaining = BudgetAllocation::sum('remaining_amount');

        return view('purchasing.budgets.index', compact(
            'budgets',
            'projects',
            'totalAllocated',
            'totalSpent',
            'totalRemaining'
        ));
    }

    public function create()
    {
        $projects = Project::all();
        $categories = ['İnşaat', 'Malzeme', 'İşçilik', 'Ekipman', 'Taşımacılık', 'Diğer'];

        return view('purchasing.budgets.create', compact('projects', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'category' => 'required|string',
            'allocated_amount' => 'required|numeric|min:0',
            'fiscal_year' => 'required|integer'
        ]);

        $budget = BudgetAllocation::create([
            ...$request->all(),
            'remaining_amount' => $request->allocated_amount
        ]);

        return redirect()->route('purchasing.budgets.index')
                       ->with('success', 'Bütçe başarıyla oluşturuldu');
    }

    public function edit(BudgetAllocation $budget)
    {
        $projects = Project::all();
        $categories = ['İnşaat', 'Malzeme', 'İşçilik', 'Ekipman', 'Taşımacılık', 'Diğer'];

        return view('purchasing.budgets.edit', compact('budget', 'projects', 'categories'));
    }

    public function update(Request $request, BudgetAllocation $budget)
    {
        $request->validate([
            'allocated_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $diff = $request->allocated_amount - $budget->allocated_amount;
        $budget->update([
            'allocated_amount' => $request->allocated_amount,
            'remaining_amount' => $budget->remaining_amount + $diff,
            'notes' => $request->notes
        ]);

        return redirect()->route('purchasing.budgets.index')
                       ->with('success', 'Bütçe güncellendi');
    }

    public function projectBudget($projectId)
    {
        $budgets = BudgetAllocation::where('project_id', $projectId)
                                  ->orderBy('category')
                                  ->get();

        $totalAllocated = $budgets->sum('allocated_amount');
        $totalSpent = $budgets->sum('spent_amount');
        $totalRemaining = $budgets->sum('remaining_amount');

        return view('purchasing.budgets.project', compact(
            'budgets',
            'projectId',
            'totalAllocated',
            'totalSpent',
            'totalRemaining'
        ));
    }
}
