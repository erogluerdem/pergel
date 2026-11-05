<?php

namespace App\Http\Controllers;

use App\Mail\ServiceRequestNotification;
use App\Models\Asset;
use App\Models\Maintenance;
use App\Models\ServiceRequest;
use App\Models\AssetDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FacilityController extends Controller
{
    public function index()
    {
        // Tesis Panosu - Dashboard
        $openWorkOrders = Maintenance::where('status', '!=', 'completed')->count();
        $overdueMaintenance = Asset::whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '<', now())
            ->count();
        $monthlyRequests = ServiceRequest::whereMonth('created_at', now()->month)->count();

        // Ek KPI metrikleri
        $totalAssets = Asset::count();
        $activeAssets = Asset::where('status', 'active')->count();
        $maintenanceThisMonth = Maintenance::whereMonth('created_at', now()->month)->count();
        $completedMaintenanceThisMonth = Maintenance::where('status', 'completed')
            ->whereMonth('updated_at', now()->month)->count();

        // Enerji tüketimi simülasyonu (gerçek veriye bağlanabilir)
        $energyConsumption = rand(850, 1200); // kWh - gerçek sensör verisi ile değiştirilebilir

        // Sistem durumu
        $systemStatus = [
            'uptime' => '99.9%',
            'last_backup' => now()->subHours(2)->format('H:i'),
            'active_alerts' => rand(0, 3),
            'temperature' => rand(20, 25) . '°C'
        ];

        // Yaklaşan bakımlar
        $upcomingMaintenances = Asset::whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '>=', now())
            ->where('next_maintenance_date', '<=', now()->addWeeks(4))
            ->orderBy('next_maintenance_date')
            ->take(10)
            ->get();

        // Harita için aktif varlıklar
        $mapAssets = Asset::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('status', 'active')
            ->get(['id', 'asset_code', 'name', 'category', 'location', 'latitude', 'longitude', 'floor_number', 'next_maintenance_date']);

        // Son aktiviteler için örnek veri
        $recentActivities = [
            ['title' => 'Yeni varlık eklendi', 'description' => 'Klima ünitesi B-12 sisteme eklendi', 'created_at' => now()->subMinutes(15), 'type' => 'asset_added'],
            ['title' => 'Bakım tamamlandı', 'description' => 'Asansör A-01 aylık bakımı yapıldı', 'created_at' => now()->subHours(2), 'type' => 'maintenance_completed'],
            ['title' => 'Arıza bildirimi', 'description' => 'Bina aydınlatma sistemi arızası rapor edildi', 'created_at' => now()->subHours(4), 'type' => 'service_request'],
            ['title' => 'Sistem güncellemesi', 'description' => 'Tesis yönetim yazılımı v2.1.4 sürümüne güncellendi', 'created_at' => now()->subDays(1), 'type' => 'system_update'],
        ];

        return view('facility.index', compact(
            'openWorkOrders',
            'overdueMaintenance',
            'monthlyRequests',
            'totalAssets',
            'activeAssets',
            'maintenanceThisMonth',
            'completedMaintenanceThisMonth',
            'energyConsumption',
            'systemStatus',
            'upcomingMaintenances',
            'mapAssets',
            'recentActivities'
        ));
    }

    public function assets(Request $request)
    {
        $query = Asset::query();

        // Filtreler
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        if ($request->filled('warranty_status')) {
            if ($request->warranty_status === 'active') {
                $query->where('warranty_expiry', '>', now());
            } elseif ($request->warranty_status === 'expired') {
                $query->where('warranty_expiry', '<=', now());
            }
        }

        $assets = $query->paginate(20);

        return view('facility.assets', compact('assets'));
    }

    public function assetShow(Asset $asset)
    {
        $asset->load('documents');
        $maintenanceHistory = Maintenance::where('asset_id', $asset->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('facility.asset-detail', compact('asset', 'maintenanceHistory'));
    }

    public function maintenance()
    {
        $workOrders = Maintenance::with('asset')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('facility.maintenance', compact('workOrders'));
    }

    public function requests()
    {
        $requests = ServiceRequest::orderBy('created_at', 'desc')->paginate(20);

        return view('facility.requests', compact('requests'));
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('facility-requests', 'public');
        }

        ServiceRequest::create([
            'subject' => $request->subject,
            'location' => $request->location,
            'description' => $request->description,
            'photo_path' => $photoPath,
            'status' => 'pending',
        ]);

        // Servis talebi oluşturulduğunda email bildirimi gönder
        $serviceRequest = ServiceRequest::latest()->first(); // Yeni oluşturulan talebi al
        try {
            Mail::to('facility-manager@company.com')->send(new ServiceRequestNotification($serviceRequest));
        } catch (\Exception $e) {
            // Email gönderimi başarısız olursa logla ama kullanıcıya hata gösterme
            \Log::error('Service request notification email failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Talebiniz başarıyla gönderildi.');
    }

    public function uploadDocument(Request $request, Asset $asset)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:warranty,manual,asbuilt,other',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp,dwg,zip|max:20480',
            'description' => 'nullable|string|max:1000',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('facility-documents', 'public');

        AssetDocument::create([
            'asset_id' => $asset->id,
            'name' => $request->name,
            'type' => $request->type,
            'file_path' => $filePath,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Doküman başarıyla yüklendi.');
    }

    // Store maintenance request for an asset (from asset detail page)
    public function storeMaintenance(Request $request, Asset $asset)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:100',
            'priority' => 'nullable|in:low,medium,high',
            'scheduled_date' => 'nullable|date',
        ]);

        $maintenance = Maintenance::create(array_merge($data, [
            'asset_id' => $asset->id,
            'status' => 'pending',
        ]));

        return response()->json(['success' => true, 'maintenance' => $maintenance], 201);
    }

    // Return nearby assets (JSON) for map visualization
    public function nearbyAssets(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');
        if (!$lat || !$lng) return response()->json([], 200);

        // Simple bounding box search (small radius)
        $delta = 0.005; // ~500m depending on latitude
        $minLat = $lat - $delta; $maxLat = $lat + $delta;
        $minLng = $lng - $delta; $maxLng = $lng + $delta;

        $assets = Asset::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereBetween('latitude', [$minLat, $maxLat])
            ->whereBetween('longitude', [$minLng, $maxLng])
            ->get(['id','asset_code','name','category','latitude','longitude']);

        return response()->json($assets);
    }

    // Store a new asset (AJAX)
    public function storeAsset(Request $request)
    {
        $data = $request->validate([
            'asset_code' => 'required|string|max:100|unique:assets,asset_code',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
            'status' => 'nullable|in:active,maintenance,passive',
            'warranty_expiry' => 'nullable|date',
        ]);

        // Normalize empty strings to null for optional fields
        foreach (['latitude','longitude','last_maintenance_date','next_maintenance_date'] as $f) {
            if (array_key_exists($f, $data) && $data[$f] === '') $data[$f] = null;
        }

        $asset = Asset::create($data);

        return response()->json(['success' => true, 'asset' => $asset], 201);
    }

    // Update existing asset (AJAX)
    public function updateAsset(Request $request, Asset $asset)
    {
        $data = $request->validate([
            'asset_code' => 'required|string|max:100|unique:assets,asset_code,' . $asset->id,
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
            'status' => 'nullable|in:active,maintenance,passive',
            'warranty_expiry' => 'nullable|date',
        ]);

        foreach (['latitude','longitude','last_maintenance_date','next_maintenance_date'] as $f) {
            if (array_key_exists($f, $data) && $data[$f] === '') $data[$f] = null;
        }

        $asset->update($data);

        return response()->json(['success' => true, 'asset' => $asset]);
    }

    // Delete asset (AJAX)
    public function destroyAsset(Asset $asset)
    {
        $asset->delete();
        return response()->json(['success' => true]);
    }

    // Reports page
    public function reports()
    {            // Varlık Raporu Verileri
            $assets = Asset::take(50)->get(); // Limit to 50 for testing
            $assetsByStatus = Asset::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
            $assetsByCategory = Asset::selectRaw('category, COUNT(*) as count')->whereNotNull('category')->groupBy('category')->get();

            // Bakım Raporu Verileri
            $isSqlite = \Illuminate\Support\Facades\DB::getDriverName() === 'sqlite';
            $monthExpr = $isSqlite ? "CAST(strftime('%m', created_at) AS INTEGER)" : "MONTH(created_at)";
            $diffHoursExpr = $isSqlite ? "AVG((julianday(updated_at) - julianday(created_at)) * 24)" : "AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at))";

            $maintenances = Maintenance::with('asset')->orderBy('created_at', 'desc')->take(50)->get(); // Limit to 50
            $maintenanceByStatus = Maintenance::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
            $maintenanceByMonth = Maintenance::selectRaw("{$monthExpr} as month, COUNT(*) as count")
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->get();

            // Arıza Raporu Verileri
            $serviceRequests = ServiceRequest::orderBy('created_at', 'desc')->take(50)->get(); // Limit to 50
            $requestsByStatus = ServiceRequest::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();

            // Performans Raporu Verileri
            $totalAssets = Asset::count();
            $activeAssets = Asset::where('status', 'active')->count();
            $maintenanceCompleted = Maintenance::where('status', 'completed')->count();
            $avgResolutionTime = ServiceRequest::where('status', 'completed')
                ->selectRaw("{$diffHoursExpr} as avg_hours")
                ->first()->avg_hours ?? 0;

            // Maliyet Raporu Verileri
            $totalMaintenanceCost = Maintenance::whereNotNull('cost')->sum('cost');
            $maintenanceCostByMonth = Maintenance::selectRaw("{$monthExpr} as month, SUM(cost) as total_cost")
                ->whereNotNull('cost')
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->get();
            $avgCostPerAsset = $totalAssets > 0 ? $totalMaintenanceCost / $totalAssets : 0;

            // Özet Raporu Verileri
            $summary = [
                'total_assets' => $totalAssets,
                'active_assets' => $activeAssets,
                'total_maintenances' => Maintenance::count(),
                'completed_maintenances' => $maintenanceCompleted,
                'total_requests' => ServiceRequest::count(),
                'resolved_requests' => ServiceRequest::where('status', 'completed')->count(),
                'total_cost' => $totalMaintenanceCost,
                'upcoming_maintenances' => Asset::whereNotNull('next_maintenance_date')
                    ->where('next_maintenance_date', '>=', now())
                    ->where('next_maintenance_date', '<=', now()->addWeeks(4))
                    ->count(),
                'overdue_maintenances' => Asset::whereNotNull('next_maintenance_date')
                    ->where('next_maintenance_date', '<', now())
                    ->count()
            ];

            return view('facility.reports', compact(
                'assets', 'assetsByStatus', 'assetsByCategory',
                'maintenances', 'maintenanceByStatus', 'maintenanceByMonth',
                'serviceRequests', 'requestsByStatus',
                'totalAssets', 'activeAssets', 'maintenanceCompleted', 'avgResolutionTime',
                'totalMaintenanceCost', 'maintenanceCostByMonth', 'avgCostPerAsset',
                'summary'
            ));

    }

    // ======================== QR CODE MANAGEMENT ========================

    public function qrCodesList(Request $request)
    {
        $query = \App\Models\UnitQrCode::with('unit', 'project');

        if ($request->filled('search')) {
            $query->where('qr_token', 'like', '%' . $request->search . '%')
                  ->orWhere('qr_label', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $qrCodes = $query->orderBy('created_at', 'desc')->paginate(20);
        $projects = \App\Models\Project::all();
        $totalQrCodes = \App\Models\UnitQrCode::count();
        $activeQrCodes = \App\Models\UnitQrCode::where('is_active', true)->count();
        $inactiveQrCodes = \App\Models\UnitQrCode::where('is_active', false)->count();
        $totalScans = \App\Models\UnitQrCode::sum('scans_count') ?? 0;

        return view('facility.qr-codes.index', compact('qrCodes', 'projects', 'totalQrCodes', 'activeQrCodes', 'inactiveQrCodes', 'totalScans'));
    }

    public function createQrCode()
    {
        $projects = \App\Models\Project::all();
        $units = \App\Models\Villa::all();

        return view('facility.qr-codes.create', compact('projects', 'units'));
    }

    public function storeQrCode(Request $request, \App\Services\QrCodeService $qrCodeService)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'unit_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $inVillas = \Illuminate\Support\Facades\DB::table('villas')->where('id', $value)->exists();
                    $inUnits = \Illuminate\Support\Facades\Schema::hasTable('units')
                        && \Illuminate\Support\Facades\DB::table('units')->where('id', $value)->exists();
                    if (!$inVillas && !$inUnits) {
                        $fail('Seçilen bağımsız bölüm (ünite/villa) bulunamadı.');
                    }
                },
            ],
            'qr_label' => 'nullable|string|max:255'
        ]);

        try {
            $qrCode = $qrCodeService->generateQrCodeForUnit(
                $request->unit_id,
                $request->project_id,
                $request->qr_label
            );

            return redirect()->route('facility.qr.index')->with('success', 'QR kod başarıyla oluşturuldu!');
        } catch (\Exception $e) {
            return back()->with('error', 'QR kod oluşturulamadı: ' . $e->getMessage());
        }
    }

    public function showQrCode($id)
    {
        $qrCode = \App\Models\UnitQrCode::with('unit', 'project')->find($id);

        if (!$qrCode) {
            return back()->with('error', 'QR kod bulunamadı');
        }

        $scanHistory = []; // JSON veriden çekilebilir

        return view('facility.qr-codes.show', compact('qrCode', 'scanHistory'));
    }

    public function generateQrCode($id, \App\Services\QrCodeService $qrCodeService)
    {
        try {
            $qrCode = \App\Models\UnitQrCode::find($id);
            $url = $qrCodeService->getQrCodeUrl($qrCode->qr_token);

            return response()->json([
                'success' => true,
                'qr_url' => $url,
                'qr_code' => $qrCode
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function downloadQrCode($id, Request $request)
    {
        $qrCode = \App\Models\UnitQrCode::find($id);

        if (!$qrCode || !$qrCode->qr_code_path) {
            return back()->with('error', 'QR kod bulunamadı');
        }

        $path = public_path($qrCode->qr_code_path);

        if (!file_exists($path)) {
            return back()->with('error', 'QR kod dosyası bulunamadı');
        }

        if ($request->query('format') === 'pdf') {
            return $this->downloadQrCodePdf($qrCode);
        }

        return response()->download($path, "qr-code-{$qrCode->qr_token}.png");
    }

    private function downloadQrCodePdf($qrCode)
    {
        // PDF indirme işlemi (isteğe bağlı)
        return response()->download(public_path($qrCode->qr_code_path));
    }

    public function generateProjectQrCodes($projectId, \App\Services\QrCodeService $qrCodeService)
    {
        try {
            $project = \App\Models\Project::find($projectId);
            if (!$project) {
                return back()->with('error', 'Proje bulunamadı');
            }

            $units = \App\Models\Villa::where('project_id', $projectId)->get();
            $codes = [];

            foreach ($units as $unit) {
                $qrCode = $qrCodeService->generateQrCodeForUnit($unit->id, $projectId);
                $codes[] = $qrCode;
            }

            return response()->json([
                'success' => true,
                'count' => count($codes),
                'message' => count($codes) . ' QR kod başarıyla oluşturuldu'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function disableQrCode($id)
    {
        try {
            $qrCode = \App\Models\UnitQrCode::find($id);
            if (!$qrCode) {
                return back()->with('error', 'QR kod bulunamadı');
            }

            $qrCode->update(['is_active' => false]);
            return back()->with('success', 'QR kod devre dışı bırakıldı');
        } catch (\Exception $e) {
            return back()->with('error', 'İşlem başarısız');
        }
    }

    public function enableQrCode($id)
    {
        try {
            $qrCode = \App\Models\UnitQrCode::find($id);
            if (!$qrCode) {
                return back()->with('error', 'QR kod bulunamadı');
            }

            $qrCode->update(['is_active' => true]);
            return back()->with('success', 'QR kod etkinleştirildi');
        } catch (\Exception $e) {
            return back()->with('error', 'İşlem başarısız');
        }
    }

    // ======================== SLA MANAGEMENT ========================

    public function slaConfig(Request $request)
    {
        $query = \App\Models\SlaConfig::with('project');

        if ($request->filled('search')) {
            $query->whereHas('project', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('issue_category', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $slaConfigs = $query->orderBy('created_at', 'desc')->paginate(20);
        $projects = \App\Models\Project::all();
        $totalSlaConfigs = \App\Models\SlaConfig::count();
        $warningAlerts = \App\Models\SlaAlert::where('alert_type', 'warning')->where('acknowledged_at', null)->count();
        $criticalAlerts = \App\Models\SlaAlert::where('alert_type', 'critical')->where('acknowledged_at', null)->count();

        return view('facility.sla.index', compact('slaConfigs', 'projects', 'totalSlaConfigs', 'warningAlerts', 'criticalAlerts'));
    }

    public function createSlaConfig()
    {
        $projects = \App\Models\Project::all();

        return view('facility.sla.create', compact('projects'));
    }

    public function storeSlaConfig(Request $request, \App\Services\SlaService $slaService)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'issue_category' => 'required|string|max:255',
            'response_time' => 'required|numeric|min:0.08',
            'resolution_time' => 'required|numeric|min:0.08',
            'priority_weight' => 'nullable|integer|min:1|max:1000',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        try {
            $slaConfig = \App\Models\SlaConfig::create([
                'project_id' => $request->project_id,
                'issue_category' => $request->issue_category,
                'response_time' => $request->response_time * 3600, // Saat to seconds
                'resolution_time' => $request->resolution_time * 3600, // Saat to seconds
                'priority_weight' => $request->priority_weight ?? 50,
                'description' => $request->description,
                'is_active' => $request->has('is_active')
            ]);

            return redirect()->route('facility.sla.index')->with('success', 'SLA hedefi başarıyla tanımlandı!');
        } catch (\Exception $e) {
            return back()->with('error', 'İşlem başarısız: ' . $e->getMessage());
        }
    }

    public function editSlaConfig($id)
    {
        $slaConfig = \App\Models\SlaConfig::find($id);
        if (!$slaConfig) {
            return back()->with('error', 'SLA hedefi bulunamadı');
        }

        $projects = \App\Models\Project::all();

        return view('facility.sla.edit', compact('slaConfig', 'projects'));
    }

    public function updateSlaConfig($id, Request $request)
    {
        $slaConfig = \App\Models\SlaConfig::find($id);
        if (!$slaConfig) {
            return back()->with('error', 'SLA konfigürasyonu bulunamadı');
        }

        $request->validate([
            'issue_category' => 'required|string|max:255',
            'response_time' => 'required|numeric|min:0.08',
            'resolution_time' => 'required|numeric|min:0.08',
            'priority_weight' => 'nullable|integer|min:1|max:1000',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        try {
            $slaConfig->update([
                'issue_category' => $request->issue_category,
                'response_time' => $request->response_time * 3600,
                'resolution_time' => $request->resolution_time * 3600,
                'priority_weight' => $request->priority_weight ?? 50,
                'description' => $request->description,
                'is_active' => $request->has('is_active')
            ]);

            return redirect()->route('facility.sla.index')->with('success', 'SLA hedefi güncellendi!');
        } catch (\Exception $e) {
            return back()->with('error', 'Güncelleme başarısız: ' . $e->getMessage());
        }
    }

    public function deleteSlaConfig($id)
    {
        $slaConfig = \App\Models\SlaConfig::find($id);
        if (!$slaConfig) {
            return back()->with('error', 'SLA konfigürasyonu bulunamadı');
        }

        $slaConfig->delete();
        return back()->with('success', 'SLA hedefi silindi');
    }

    public function slaDashboard($projectId, \App\Services\SlaService $slaService)
    {
        $stats = $slaService->getProjectSlaStats($projectId);
        $compliancePercentage = $slaService->getSlaCompliancePercentage($projectId);
        $criticalAlerts = $slaService->getCriticalAlerts($projectId);
        $slaConfigs = \App\Models\SlaConfig::where('project_id', $projectId)->get();

        return view('facility.sla.dashboard', compact('stats', 'compliancePercentage', 'criticalAlerts', 'slaConfigs', 'projectId'));
    }

    public function slaAlerts($projectId, \App\Services\SlaService $slaService)
    {
        $criticalAlerts = $slaService->getCriticalAlerts($projectId);
        $warningAlerts = \App\Models\SlaAlert::whereHas('serviceRequest', function ($query) use ($projectId) {
            $query->where('project_id', $projectId)
                ->where('status', '!=', 'completed');
        })
        ->where('alert_type', 'warning')
        ->where('acknowledged_at', null)
        ->with('serviceRequest')
        ->orderBy('triggered_at', 'desc')
        ->get();

        return view('facility.sla.alerts', compact('criticalAlerts', 'warningAlerts', 'projectId'));
    }

    public function acknowledgeSlaAlert($alertId, Request $request)
    {
        $alert = \App\Models\SlaAlert::find($alertId);
        if (!$alert) {
            return response()->json(['success' => false, 'message' => 'Uyarı bulunamadı'], 404);
        }

        $alert->acknowledge(auth()->user(), $request->notes);

        return response()->json([
            'success' => true,
            'message' => 'Uyarı kabul edildi'
        ]);
    }
}
