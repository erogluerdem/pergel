<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentMaintenance;
use App\Models\EquipmentAssignment;
use App\Models\EquipmentDocument;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    public function dashboard()
    {
        // KPI'lar için veriler
        $totalEquipment = Equipment::count();
        $activeEquipment = Equipment::where('status', 'Çalışıyor')->count();
        $maintenanceEquipment = Equipment::where('status', 'Bakımda')->count();
        $upcomingMaintenance = Equipment::whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '<=', now()->addDays(7))
            ->count();

        $usageRate = $totalEquipment > 0 ? round(($activeEquipment / $totalEquipment) * 100, 1) : 0;

        // Harita için aktif ekipmanlar
        $activeEquipments = Equipment::where('status', 'Çalışıyor')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'name', 'type', 'latitude', 'longitude', 'current_project_id']);

        return view('resources.equipment.dashboard', compact(
            'totalEquipment',
            'activeEquipment',
            'maintenanceEquipment',
            'upcomingMaintenance',
            'usageRate',
            'activeEquipments'
        ));
    }

    public function index(Request $request)
    {
        $query = Equipment::with('currentProject');

        // Filtreler
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('current_project_id', $request->project_id);
        }

        $equipment = $query->paginate(20);

        $types = Equipment::distinct('type')->pluck('type');
        $statuses = ['Çalışıyor', 'Boşta', 'Bakımda', 'Arızalı'];
        $projects = Project::select('id', 'name')->get();

        return view('resources.equipment.index', compact('equipment', 'types', 'statuses', 'projects'));
    }

    public function create()
    {
        $projects = Project::select('id', 'name')->get();
        return view('resources.equipment.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'plate_number' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'status' => 'required|in:Çalışıyor,Boşta,Bakımda,Arızalı',
            'current_project_id' => 'nullable|exists:projects,id',
            'operator' => 'nullable|string|max:255',
            'next_maintenance_date' => 'nullable|date',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'working_hours' => 'nullable|integer|min:0',
            'fuel_level' => 'nullable|integer|min:0|max:100',
            'ownership' => 'required|in:Sahip,Kiralık',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        Equipment::create($validated);

        return redirect()->route('resources.equipment.index')->with('success', 'Ekipman başarıyla eklendi.');
    }

    public function show(Equipment $equipment)
    {
        $equipment->load(['currentProject', 'maintenances', 'assignments.project', 'documents']);

        return view('resources.equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        $projects = Project::select('id', 'name')->get();
        return view('resources.equipment.edit', compact('equipment', 'projects'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'plate_number' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'status' => 'required|in:Çalışıyor,Boşta,Bakımda,Arızalı',
            'current_project_id' => 'nullable|exists:projects,id',
            'operator' => 'nullable|string|max:255',
            'next_maintenance_date' => 'nullable|date',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'working_hours' => 'nullable|integer|min:0',
            'fuel_level' => 'nullable|integer|min:0|max:100',
            'ownership' => 'required|in:Sahip,Kiralık',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $equipment->update($validated);

        return redirect()->route('resources.equipment.show', $equipment)->with('success', 'Ekipman başarıyla güncellendi.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('resources.equipment.index')->with('success', 'Ekipman başarıyla silindi.');
    }

    public function maintenance(Request $request, Equipment $equipment)
    {
        $maintenances = $equipment->maintenances()->orderBy('maintenance_date', 'desc')->paginate(10);
        return view('resources.equipment.maintenance', compact('equipment', 'maintenances'));
    }

    public function storeMaintenance(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'maintenance_date' => 'required|date',
            'type' => 'required|in:Periyodik,Arıza,Acil',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'working_hours_before' => 'nullable|integer|min:0',
            'working_hours_after' => 'nullable|integer|min:0',
            'performed_by' => 'nullable|string|max:255',
            'parts_used' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);

        $equipment->maintenances()->create($validated);

        return redirect()->back()->with('success', 'Bakım kaydı başarıyla eklendi.');
    }

    public function storeDocument(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'issued_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $file = $request->file('document');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('equipment_documents', $fileName, 'public');

        $equipment->documents()->create([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'issue_date' => $validated['issue_date'],
            'expiry_date' => $validated['expiry_date'],
            'issued_by' => $validated['issued_by'],
            'notes' => $validated['notes']
        ]);

        return redirect()->back()->with('success', 'Belge başarıyla yüklendi.');
    }
}
