<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Villa;
use App\Models\ExpenseCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProjectCostController extends Controller
{
    public function index(Project $project)
    {
        // Toplam Harcama
        $totalExpenses = $project->expenses()->sum('amount');

        // Bütçe Kullanım Oranı
        $budgetUsage = $project->budget > 0 ? ($totalExpenses / $project->budget) * 100 : 0;

        // Harcamaların Kategori Dağılımı
        $expensesByCategory = $project->expenses()
            ->with('category')
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->get()
            ->map(function ($expense) {
                return [
                    'category' => $expense->category->name ?? 'Bilinmeyen',
                    'total' => $expense->total,
                ];
            });

        // Son Harcamalar
        $recentExpenses = $project->expenses()->latest()->take(10)->get();

        // Villalar ve maliyet analizi
        $villas = $project->villas()->get()->map(function ($villa) {
            $villa->actual_cost = $villa->expenses()->sum('amount');
            $villa->variance = $villa->actual_cost - $villa->target_cost;
            $villa->variance_percentage = $villa->target_cost > 0 ? ($villa->variance / $villa->target_cost) * 100 : 0;
            return $villa;
        });

        return view('projects.costs.index', compact('project', 'totalExpenses', 'budgetUsage', 'expensesByCategory', 'recentExpenses', 'villas'));
    }

    public function create(Project $project)
    {
        $categories = ExpenseCategory::all();
        $suppliers = Supplier::all();
        $villas = $project->villas;

        return view('projects.costs.create', compact('project', 'categories', 'suppliers', 'villas'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'villa_id' => 'nullable|exists:villas,id',
            'category_id' => 'required|exists:expense_categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $project->expenses()->create($request->all());

        return redirect()->route('projects.costs.index', $project)->with('success', 'Harcama başarıyla eklendi.');
    }
}
