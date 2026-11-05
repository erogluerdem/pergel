<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\PurchaseOrder;
use App\Models\Project;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcurementController extends Controller
{
    public function index(Request $request)
    {
        // Gelişmiş KPI'lar hesapla
        $kpis = $this->calculateKPIs();

        // Harcama analizi
        $spendingAnalysis = $this->getSpendingAnalysis();

        // Trend verileri
        $trendData = $this->getTrendData();

        // Vendor performansı
        $vendorPerformance = $this->getVendorPerformance();

        // Son aktiviteler
        $recentActivities = $this->getRecentActivities();

        // Approval workflow durumu
        $workflowStats = $this->getWorkflowStats();

        // Budget karşılaştırması
        $budgetComparison = $this->getBudgetComparison();

        // Risk analizi
        $riskAnalysis = $this->getRiskAnalysis();

        // Requisitions verisi (gelişmiş filtreleme ile)
        $requisitionsQuery = Requisition::with(['project', 'requestedBy']);
        if ($request->filled('status')) {
            $requisitionsQuery->where('status', $request->get('status'));
        }
        if ($request->filled('project_id')) {
            $requisitionsQuery->where('project_id', $request->get('project_id'));
        }
        if ($request->filled('priority')) {
            $requisitionsQuery->where('priority', $request->get('priority'));
        }
        $requisitions = $requisitionsQuery->orderBy('created_at', 'desc')->paginate(10);

        // Purchase Orders verisi (gelişmiş filtreleme ile)
        $purchaseOrdersQuery = PurchaseOrder::with(['vendor', 'project']);
        if ($request->filled('status')) {
            $purchaseOrdersQuery->where('status', $request->get('status'));
        }
        if ($request->filled('project_id')) {
            $purchaseOrdersQuery->where('project_id', $request->get('project_id'));
        }
        if ($request->filled('vendor_id')) {
            $purchaseOrdersQuery->where('vendor_id', $request->get('vendor_id'));
        }
        $purchaseOrders = $purchaseOrdersQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('supply.procurement.index', compact(
            'kpis',
            'spendingAnalysis',
            'trendData',
            'vendorPerformance',
            'recentActivities',
            'workflowStats',
            'budgetComparison',
            'riskAnalysis',
            'requisitions',
            'purchaseOrders'
        ));
    }

    private function calculateKPIs()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        return [
            'pending_requisitions' => Requisition::where('status', 'pending')->count(),
            'approved_requisitions' => Requisition::where('status', 'approved')->count(),
            'open_orders' => PurchaseOrder::where('status', 'open')->count(),
            'completed_orders' => PurchaseOrder::where('status', 'completed')->count(),
            'open_orders_value' => PurchaseOrder::where('status', 'open')->sum('total_amount') ?? 0,
            'completed_orders_value' => PurchaseOrder::where('status', 'completed')
                ->whereBetween('created_at', [$thisMonth, $today])
                ->sum('total_amount') ?? 0,
            'delayed_deliveries' => PurchaseOrder::where('status', 'open')
                ->where('delivery_date', '<', now())
                ->count(),
            'on_time_deliveries' => PurchaseOrder::where('status', 'completed')
                ->whereBetween('created_at', [$thisMonth, $today])
                ->count(),
            'total_vendors' => PurchaseOrder::distinct('vendor_id')->count(),
            'active_projects' => Requisition::distinct('project_id')->count(),
            'avg_processing_time' => round(
                Requisition::where('status', 'approved')
                ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_days')
                ->value('avg_days') ?? 0, 1
            ),
            'cost_savings' => 0,
            'monthly_growth' => 0
        ];
    }

    private function getSpendingAnalysis()
    {
        return [
            'by_category' => [
                ['category' => 'İnşaat Malzemeleri', 'amount' => 450000, 'percentage' => 35, 'color' => '#007bff'],
                ['category' => 'Elektrik & Elektronik', 'amount' => 320000, 'percentage' => 25, 'color' => '#28a745'],
                ['category' => 'Makine & Ekipman', 'amount' => 280000, 'percentage' => 22, 'color' => '#ffc107'],
                ['category' => 'İnsan Kaynakları', 'amount' => 150000, 'percentage' => 12, 'color' => '#dc3545'],
                ['category' => 'Diğer', 'amount' => 80000, 'percentage' => 6, 'color' => '#6c757d'],
            ],
            'by_project' => Project::withSum('purchaseOrders', 'total_amount')
                ->orderBy('purchase_orders_sum_total_amount', 'desc')
                ->limit(8)
                ->get()
                ->map(function($project) {
                    return [
                        'name' => $project->name,
                        'amount' => $project->purchase_orders_sum_total_amount ?? 0,
                        'code' => $project->code ?? 'N/A'
                    ];
                })->toArray(),
            'monthly_trend' => [
                ['month' => 'Ocak', 'amount' => 280000],
                ['month' => 'Şubat', 'amount' => 320000],
                ['month' => 'Mart', 'amount' => 350000],
                ['month' => 'Nisan', 'amount' => 420000],
                ['month' => 'Mayıs', 'amount' => 380000],
                ['month' => 'Haziran', 'amount' => 450000],
            ]
        ];
    }

    private function getTrendData()
    {
        return [
            'requisitions_trend' => [
                ['date' => '2024-01-01', 'count' => 12],
                ['date' => '2024-01-02', 'count' => 15],
                ['date' => '2024-01-03', 'count' => 18],
                ['date' => '2024-01-04', 'count' => 14],
                ['date' => '2024-01-05', 'count' => 22],
                ['date' => '2024-01-06', 'count' => 19],
                ['date' => '2024-01-07', 'count' => 25],
            ],
            'orders_trend' => [
                ['date' => '2024-01-01', 'count' => 8],
                ['date' => '2024-01-02', 'count' => 12],
                ['date' => '2024-01-03', 'count' => 10],
                ['date' => '2024-01-04', 'count' => 15],
                ['date' => '2024-01-05', 'count' => 18],
                ['date' => '2024-01-06', 'count' => 14],
                ['date' => '2024-01-07', 'count' => 20],
            ]
        ];
    }

    private function getVendorPerformance()
    {
        return [
            ['name' => 'ABC İnşaat Malz.', 'orders' => 25, 'value' => 450000, 'rating' => 4.8, 'on_time' => 95],
            ['name' => 'XYZ Elektrik Ltd.', 'orders' => 18, 'value' => 320000, 'rating' => 4.6, 'on_time' => 88],
            ['name' => 'DEF Makina San.', 'orders' => 15, 'value' => 280000, 'rating' => 4.4, 'on_time' => 82],
            ['name' => 'GHI Tedarik A.Ş.', 'orders' => 12, 'value' => 150000, 'rating' => 4.2, 'on_time' => 90],
            ['name' => 'JKL Endüstri Ltd.', 'orders' => 8, 'value' => 80000, 'rating' => 4.0, 'on_time' => 85],
        ];
    }

    private function getRecentActivities()
    {
        return [
            ['type' => 'requisition', 'title' => 'Yeni satın alma talebi oluşturuldu', 'project' => 'Proje A', 'user' => 'Ahmet Yılmaz', 'time' => '2 saat önce', 'status' => 'pending'],
            ['type' => 'order', 'title' => 'Satın alma siparişi onaylandı', 'project' => 'Proje B', 'user' => 'Mehmet Kaya', 'time' => '4 saat önce', 'status' => 'approved'],
            ['type' => 'delivery', 'title' => 'Teslimat tamamlandı', 'project' => 'Proje C', 'user' => 'Ayşe Demir', 'time' => '6 saat önce', 'status' => 'completed'],
            ['type' => 'requisition', 'title' => 'Satın alma talebi reddedildi', 'project' => 'Proje D', 'user' => 'Fatma Öz', 'time' => '1 gün önce', 'status' => 'rejected'],
            ['type' => 'order', 'title' => 'Acil sipariş oluşturuldu', 'project' => 'Proje E', 'user' => 'Ali Çelik', 'time' => '2 gün önce', 'status' => 'urgent'],
        ];
    }

    private function getWorkflowStats()
    {
        return [
            'pending_approvals' => 15,
            'in_review' => 8,
            'approved_today' => 12,
            'rejected_today' => 2,
            'avg_approval_time' => 2.5, // days
            'bottlenecks' => ['Bütçe Onayı', 'Teknik İnceleme']
        ];
    }

    private function getBudgetComparison()
    {
        return [
            'allocated' => 2500000,
            'spent' => 1850000,
            'committed' => 420000,
            'remaining' => 230000,
            'utilization_rate' => 74,
            'monthly_burn_rate' => 310000
        ];
    }

    private function getRiskAnalysis()
    {
        return [
            'high_risk_orders' => 3,
            'delayed_deliveries' => 8,
            'budget_overruns' => 2,
            'vendor_issues' => 1,
            'compliance_risks' => 0,
            'supply_shortages' => 4
        ];
    }

    public function dashboard(Request $request)
    {
        // KPI'lar hesapla
        $kpis = [
            'pending_requisitions' => Requisition::where('status', 'pending')->count(),
            'open_orders' => PurchaseOrder::where('status', 'open')->count(),
            'open_orders_value' => PurchaseOrder::where('status', 'open')->sum('total_amount'),
            'delayed_deliveries' => PurchaseOrder::where('status', 'open')
                ->where('delivery_date', '<', now())
                ->count(),
        ];

        // Harcama dağılımı için örnek veri (şimdilik sabit)
        $spending_distribution = [
            ['category' => 'Beton', 'amount' => 150000],
            ['category' => 'Demir', 'amount' => 120000],
            ['category' => 'Elektrik Malzemeleri', 'amount' => 80000],
            ['category' => 'Diğer', 'amount' => 50000],
        ];

        return view('supply.procurement.index', compact('kpis', 'spending_distribution'));
    }

    public function requisitions(Request $request)
    {
        $query = Requisition::with(['project', 'requestedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->get('project_id'));
        }

        $requisitions = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('supply.procurement.requisitions', compact('requisitions'));
    }

    public function purchaseOrders(Request $request)
    {
        $query = PurchaseOrder::with(['vendor', 'project']);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->get('project_id'));
        }

        $purchaseOrders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Vendors list for quick-create when there are no purchase orders
        $vendors = Vendor::orderBy('name')->get();

        return view('supply.procurement.purchase_orders', compact('purchaseOrders', 'vendors'));
    }

    public function createRequisition(Request $request)
    {
        // Talep oluşturma formu için
        return view('supply.procurement.create_requisition');
    }

    public function storeRequisition(Request $request)
    {
        // Talep kaydetme
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'required_date' => 'required|date',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric',
            'items.*.unit' => 'required|string',
            'items.*.cost_code' => 'nullable|string',
        ]);

        $requisition = Requisition::create([
            'project_id' => $validated['project_id'],
            'requested_by' => auth()->id(),
            'required_date' => $validated['required_date'],
            'status' => 'pending',
        ]);

        // İtemleri ekle (şimdilik basit)
        foreach ($validated['items'] as $item) {
            $requisition->items()->create($item);
        }

        return redirect()->route('supply.procurement.requisitions')->with('success', 'Talep başarıyla oluşturuldu.');
    }
}
