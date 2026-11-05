<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyDocument;
use App\Models\LegalProcess;
use App\Models\PropertyEvaluation;
use App\Models\DocumentReminder;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager', ['except' => ['index', 'show']]);
    }

    /**
     * Arsa/Emlak listesi
     */
    public function index(Request $request)
    {
        $query = Property::query();

        // Filtreler
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('legal_status')) {
            $query->where('legal_status', $request->legal_status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }

        if ($request->filled('investment_risk_level')) {
            $query->where('investment_risk_level', $request->investment_risk_level);
        }

        // Arama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('property_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('deed_number', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%");
            });
        }

        $properties = $query->with(['project', 'documents', 'legalProcesses', 'evaluations'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $projects = Project::pluck('name', 'id');
        $statuses = [
            'vacant' => 'Boş',
            'acquired' => 'Satın Alındı',
            'in_development' => 'Geliştirme Aşamasında',
            'developed' => 'Geliştirildi',
            'inactive' => 'İnaktif',
        ];
        $legalStatuses = [
            'clear' => 'Temiz',
            'disputed' => 'Anlaşmazlık Var',
            'under_process' => 'İşlem Altında',
            'restricted' => 'Kısıtlı',
        ];
        $riskLevels = [
            'low' => 'Düşük',
            'medium' => 'Orta',
            'high' => 'Yüksek',
        ];

        return view('properties.index', compact('properties', 'projects', 'statuses', 'legalStatuses', 'riskLevels'));
    }

    /**
     * Arsa oluşturma formu
     */
    public function create()
    {
        $projects = Project::pluck('name', 'id');

        return view('properties.create', compact('projects'));
    }

    /**
     * Arsa kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'property_code' => 'required|unique:properties,property_code',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'city' => 'required|string',
            'district' => 'nullable|string',
            'neighborhood' => 'nullable|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'area' => 'nullable|numeric|min:0',
            'building_area' => 'nullable|numeric|min:0',
            'zoning_status' => 'nullable|string',
            'floor_ratio' => 'nullable|numeric|min:0',
            'coverage_ratio' => 'nullable|numeric|min:0',
            'land_value' => 'nullable|numeric|min:0',
            'development_potential_value' => 'nullable|numeric|min:0',
            'potential_notes' => 'nullable|string',
            'investment_risk_level' => 'in:low,medium,high',
            'deed_number' => 'nullable|string',
            'block_number' => 'nullable|string',
            'parcel_number' => 'nullable|string',
            'deed_date' => 'nullable|date',
            'owner_name' => 'nullable|string',
            'deed_description' => 'nullable|string',
            'legal_status' => 'in:clear,disputed,under_process,restricted',
            'legal_notes' => 'nullable|string',
            'status' => 'in:vacant,acquired,in_development,developed,inactive',
        ]);

        try {
            $property = Property::create($validated + [
                'user_id' => auth()->id(),
                'assigned_to_id' => auth()->id(),
            ]);

            return redirect()
                ->route('operations.properties.show', $property)
                ->with('success', 'Arsa başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Arsa oluşturulamadı: ' . $e->getMessage());
        }
    }

    /**
     * Arsa detayı
     */
    public function show(Property $property)
    {
        $property->load([
            'project',
            'user',
            'assignedTo',
            'documents',
            'legalProcesses',
            'evaluations'
        ]);

        return view('properties.show', compact('property'));
    }

    /**
     * Arsa düzenleme formu
     */
    public function edit(Property $property)
    {
        $projects = Project::pluck('name', 'id');

        return view('properties.edit', compact('property', 'projects'));
    }

    /**
     * Arsa güncelle
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'city' => 'required|string',
            'district' => 'nullable|string',
            'neighborhood' => 'nullable|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'area' => 'nullable|numeric|min:0',
            'building_area' => 'nullable|numeric|min:0',
            'zoning_status' => 'nullable|string',
            'floor_ratio' => 'nullable|numeric|min:0',
            'coverage_ratio' => 'nullable|numeric|min:0',
            'land_value' => 'nullable|numeric|min:0',
            'development_potential_value' => 'nullable|numeric|min:0',
            'potential_notes' => 'nullable|string',
            'investment_risk_level' => 'in:low,medium,high',
            'deed_number' => 'nullable|string',
            'block_number' => 'nullable|string',
            'parcel_number' => 'nullable|string',
            'deed_date' => 'nullable|date',
            'owner_name' => 'nullable|string',
            'deed_description' => 'nullable|string',
            'legal_status' => 'in:clear,disputed,under_process,restricted',
            'legal_notes' => 'nullable|string',
            'status' => 'in:vacant,acquired,in_development,developed,inactive',
        ]);

        try {
            $property->update($validated);

            return redirect()
                ->route('operations.properties.show', $property)
                ->with('success', 'Arsa başarıyla güncellendi.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Arsa güncellenemedi: ' . $e->getMessage());
        }
    }

    /**
     * Arsa sil
     */
    public function destroy(Property $property)
    {
        try {
            $property->delete();

            return redirect()
                ->route('operations.properties.index')
                ->with('success', 'Arsa başarıyla silindi.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Arsa silinemedi: ' . $e->getMessage());
        }
    }

    /**
     * Belgeleri yönet
     */
    public function documents(Property $property)
    {
        $property->load('documents');

        return view('properties.documents', compact('property'));
    }

    /**
     * Belge yükle
     */
    public function uploadDocument(Request $request, Property $property)
    {
        $validated = $request->validate([
            'document_name' => 'required|string',
            'document_category' => 'required|in:deed,cadastral,zoning,permit,survey,evaluation,legal,correspondence,contract,other',
            'file' => 'required|file|max:10240',
            'description' => 'nullable|string',
            'document_date' => 'nullable|date',
            'validity_date' => 'nullable|date',
            'is_critical' => 'boolean',
            'requires_renewal' => 'boolean',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('properties/' . $property->id, 'private');

            $document = PropertyDocument::create([
                'property_id' => $property->id,
                'document_name' => $validated['document_name'],
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'document_category' => $validated['document_category'],
                'description' => $validated['description'],
                'document_date' => $validated['document_date'] ?? null,
                'validity_date' => $validated['validity_date'] ?? null,
                'is_critical' => $validated['is_critical'] ?? false,
                'requires_renewal' => $validated['requires_renewal'] ?? false,
                'uploaded_by_id' => auth()->id(),
                'indexed_by' => auth()->user()->name,
                'indexed_date' => now()->toDateString(),
            ]);

            return redirect()
                ->route('operations.properties.documents', $property)
                ->with('success', 'Belge başarıyla yüklendi.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Belge yüklenemedi: ' . $e->getMessage());
        }
    }

    /**
     * Yasal işlem ekle
     */
    public function addLegalProcess(Request $request, Property $property)
    {
        $validated = $request->validate([
            'process_name' => 'required|string',
            'process_type' => 'required|in:deed_transfer,zoning_approval,permit_acquisition,survey,dispute_resolution,other',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'authority' => 'nullable|string',
            'reference_number' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'in:not_started,in_progress,pending_approval,completed,delayed,blocked',
        ]);

        try {
            $legalProcess = $property->legalProcesses()->create($validated + [
                'responsible_id' => auth()->id(),
                'last_status_update' => now(),
            ]);

            return redirect()
                ->route('operations.properties.show', $property)
                ->with('success', 'Yasal işlem başarıyla eklendi.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Yasal işlem eklenemedi: ' . $e->getMessage());
        }
    }

    /**
     * Değerlendirme ekle
     */
    public function addEvaluation(Request $request, Property $property)
    {
        $validated = $request->validate([
            'evaluation_name' => 'required|string',
            'description' => 'nullable|string',
            'evaluation_date' => 'required|date',
            'terrain_suitability' => 'nullable|numeric|min:0|max:100',
            'accessibility_score' => 'nullable|numeric|min:0|max:100',
            'infrastructure_readiness' => 'nullable|numeric|min:0|max:100',
            'market_value_per_sqm' => 'nullable|numeric|min:0',
            'total_market_value' => 'nullable|numeric|min:0',
            'appreciation_potential_percent' => 'nullable|numeric',
            'roi_projection' => 'nullable|numeric',
            'payback_period_months' => 'nullable|numeric|min:0',
            'investment_recommendation' => 'in:highly_recommended,recommended,neutral,not_recommended',
            'market_risk' => 'in:low,medium,high',
            'legal_risk' => 'in:low,medium,high',
            'development_risk' => 'in:low,medium,high',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'opportunities' => 'nullable|string',
            'threats' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'is_final' => 'boolean',
        ]);

        try {
            $evaluation = $property->evaluations()->create($validated + [
                'evaluated_by_id' => auth()->id(),
            ]);

            return redirect()
                ->route('operations.properties.show', $property)
                ->with('success', 'Değerlendirme başarıyla eklendi.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Değerlendirme eklenemedi: ' . $e->getMessage());
        }
    }

    /**
     * Hatırlatma ekle
     */
    public function addReminder(Request $request, PropertyDocument $document)
    {
        $validated = $request->validate([
            'reminder_title' => 'required|string',
            'description' => 'nullable|string',
            'renewal_due_date' => 'required|date|after:now',
            'reminder_frequency' => 'required|in:one_time,weekly,monthly,quarterly,yearly',
            'notification_recipients' => 'required|array|min:1',
            'notification_channels' => 'required|array|min:1',
        ]);

        try {
            $reminder = DocumentReminder::create($validated + [
                'property_document_id' => $document->id,
                'notification_recipients' => json_encode($validated['notification_recipients']),
                'notification_channels' => json_encode($validated['notification_channels']),
            ]);

            return redirect()
                ->back()
                ->with('success', 'Hatırlatma başarıyla eklendi.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Hatırlatma eklenemedi: ' . $e->getMessage());
        }
    }

    /**
     * Toplu aksiyon: Durum güncelle
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'property_ids' => 'required|array',
            'status' => 'required|in:vacant,acquired,in_development,developed,inactive',
        ]);

        try {
            Property::whereIn('id', $validated['property_ids'])->update([
                'status' => $validated['status'],
            ]);

            return redirect()
                ->route('operations.properties.index')
                ->with('success', 'Arsaların durumu başarıyla güncellendi.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Durum güncellenemedi: ' . $e->getMessage());
        }
    }

    /**
     * Rapor: Değerlendirme özeti
     */
    public function evaluationReport(Property $property)
    {
        $property->load('evaluations');
        $evaluations = $property->evaluations()->where('is_final', true)->get();

        return view('properties.reports.evaluation', compact('property', 'evaluations'));
    }

    /**
     * Rapor: Yasal durum özeti
     */
    public function legalStatusReport(Property $property)
    {
        $property->load('legalProcesses');
        $processes = $property->legalProcesses()->get();

        return view('properties.reports.legal_status', compact('property', 'processes'));
    }
}
