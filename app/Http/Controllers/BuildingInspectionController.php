<?php

namespace App\Http\Controllers;

use App\Models\BuildingInspection;
use App\Models\Project;
use Illuminate\Http\Request;

class BuildingInspectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = BuildingInspection::orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::pluck('name', 'id');

        $stats = [
            'total_inspections' => BuildingInspection::count(),
            'total_amount' => BuildingInspection::sum('amount'),
            'approved_count' => BuildingInspection::where('status', 'onaylandi')->count(),
            'pending_count' => BuildingInspection::where('status', 'beklemede')->count(),
        ];

        return view('building_inspections.index', compact('items', 'projects', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string',
            'ydk_company_name' => 'required|string',
            'document_type' => 'required|string',
            'progress_percentage' => 'nullable|numeric|min:0|max:100',
            'amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'status' => 'required|in:beklemede,odendi,onaylandi',
        ]);

        BuildingInspection::create($validated);

        return back()->with('success', 'YDK Seviye Tespit kaydı başarıyla oluşturuldu.');
    }

    public function update(Request $request, BuildingInspection $buildingInspection)
    {
        $validated = $request->validate([
            'status' => 'required|in:beklemede,odendi,onaylandi',
        ]);

        $buildingInspection->update($validated);

        return back()->with('success', 'YDK kaydı güncellendi.');
    }

    public function destroy(BuildingInspection $buildingInspection)
    {
        $buildingInspection->delete();
        return back()->with('success', 'YDK kaydı silindi.');
    }
}
