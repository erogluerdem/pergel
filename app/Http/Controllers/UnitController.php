<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Project;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('project')->paginate(20);
        return view('units.index', compact('units'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        return view('units.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'unit_no' => 'required|string',
            'block' => 'nullable|string',
            'floor' => 'nullable|integer',
            'unit_type' => 'required|string',
            'area' => 'required|numeric|min:0',
            'room_count' => 'required|integer|min:0',
            'bathroom_count' => 'required|integer|min:0',
            'list_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'max_discount_rate' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string|size:3',
            'status' => 'required|in:available,reserved,sold,maintenance',
            'description' => 'nullable|string',
        ]);

        Unit::create($validated);

        return redirect()->route('units.index')
                       ->with('success', 'Birim başarıyla oluşturuldu.');
    }

    public function show(Unit $unit)
    {
        $unit->load('project', 'reservations');
        return view('units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        $projects = Project::orderBy('name')->get();
        return view('units.edit', compact('unit', 'projects'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'unit_no' => 'required|string',
            'block' => 'nullable|string',
            'floor' => 'nullable|integer',
            'unit_type' => 'required|string',
            'area' => 'required|numeric|min:0',
            'room_count' => 'required|integer|min:0',
            'bathroom_count' => 'required|integer|min:0',
            'list_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'max_discount_rate' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string|size:3',
            'status' => 'required|in:available,reserved,sold,maintenance',
            'description' => 'nullable|string',
        ]);

        $unit->update($validated);

        return redirect()->route('units.show', $unit)
                       ->with('success', 'Birim başarıyla güncellendi.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')
                       ->with('success', 'Birim başarıyla silindi.');
    }

    public function listByProject(Project $project)
    {
        $units = $project->units()->paginate(20);
        return view('units.list', compact('project', 'units'));
    }

    public function pricing(Unit $unit)
    {
        return view('units.pricing', compact('unit'));
    }
}
