<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\FinancialTrend;
use App\Services\FinancialTrendService;

class FinancialTrendController extends Controller
{
    private $trendService;

    public function __construct(FinancialTrendService $trendService)
    {
        $this->trendService = $trendService;
    }

    /**
     * Trend oluşturma formu göster
     */
    public function create()
    {
        $projects = Project::where('deleted_at', null)->get();
        return view('finance.trends.create', compact('projects'));
    }

    /**
     * Yeni trend kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'trend_type' => 'required|in:cash_flow,expenses,revenue,profitability',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'starting_value' => 'required|numeric',
            'ending_value' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $project = Project::findOrFail($validated['project_id']);
        
        // Trend hesaplamalarını yap
        $difference = $validated['ending_value'] - $validated['starting_value'];
        $percent_change = ($validated['starting_value'] != 0) 
            ? (($difference / $validated['starting_value']) * 100) 
            : 0;

        $validated['value_difference'] = $difference;
        $validated['percent_change'] = $percent_change;
        $validated['period_type'] = 'custom';

        // Trend yönü belirle
        $validated['trend_direction'] = $difference > 0 ? 'increasing' : ($difference < 0 ? 'decreasing' : 'stable');

        $trend = FinancialTrend::create($validated);

        return redirect()->route('finance.trends.dashboard', ['project_id' => $project->id])
            ->with('success', 'Trend başarıyla kaydedildi!');
    }

    /**
     * Trend detaylarını göster
     */
    public function show($id)
    {
        $trend = FinancialTrend::findOrFail($id);
        $project = $trend->project;

        // Benzer trendleri getir
        $similarTrends = FinancialTrend::where('project_id', $project->id)
            ->where('trend_type', $trend->trend_type)
            ->where('id', '!=', $trend->id)
            ->orderBy('period_start', 'desc')
            ->limit(5)
            ->get();

        return view('finance.trends.show', compact('trend', 'project', 'similarTrends'));
    }

    /**
     * Trend düzenle
     */
    public function edit($id)
    {
        $trend = FinancialTrend::findOrFail($id);
        return view('finance.trends.edit', compact('trend'));
    }

    /**
     * Trend güncelle
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'trend_type' => 'required|in:cash_flow,expenses,revenue,profitability',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'starting_value' => 'required|numeric',
            'ending_value' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $trend = FinancialTrend::findOrFail($id);

        // Trend hesaplamalarını yeniden yap
        $difference = $validated['ending_value'] - $validated['starting_value'];
        $percent_change = ($validated['starting_value'] != 0) 
            ? (($difference / $validated['starting_value']) * 100) 
            : 0;

        $validated['value_difference'] = $difference;
        $validated['percent_change'] = $percent_change;
        $validated['trend_direction'] = $difference > 0 ? 'increasing' : ($difference < 0 ? 'decreasing' : 'stable');

        $trend->update($validated);

        return redirect()->route('finance.trends.show', $trend->id)
            ->with('success', 'Trend başarıyla güncellendi!');
    }

    /**
     * Trend indeksini listele
     */
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');
        $trendType = $request->query('type');

        $query = FinancialTrend::query();

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if ($trendType) {
            $query->where('trend_type', $trendType);
        }

        $trends = $query->orderBy('period_start', 'desc')->paginate(15);
        $projects = Project::all();

        return view('finance.trends.index', compact('trends', 'projects', 'projectId', 'trendType'));
    }

    /**
     * Trend analizi çalıştır
     */
    public function analyze(Request $request)
    {
        $projectId = $request->query('project_id');
        $periodType = $request->query('period', 'monthly');

        $project = $projectId ? Project::findOrFail($projectId) : Project::first();

        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Proje bulunamadı');
        }

        $trends = $this->trendService->generateTrends($project, $periodType, 12);

        foreach ($trends as $trendData) {
            FinancialTrend::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'period_start' => $trendData['period_start'],
                    'period_end' => $trendData['period_end'],
                    'period_type' => $trendData['period_type'],
                ],
                $trendData
            );
        }

        return redirect()->route('finance.trends.dashboard', ['project_id' => $project->id])
            ->with('success', 'Trend analizi başarıyla tamamlandı!');
    }

    /**
     * Trend verilerini dışa aktar
     */
    public function export(Request $request)
    {
        $projectId = $request->query('project_id');
        $periodType = $request->query('period', 'monthly');

        $project = $projectId ? Project::findOrFail($projectId) : Project::first();

        $trends = FinancialTrend::where('project_id', $project->id)
            ->where('period_type', $periodType)
            ->orderBy('period_start', 'asc')
            ->get();

        // CSV formatında dışa aktar
        $filename = "trends_{$project->id}_" . now()->format('Ymd_His') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($trends) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Dönem Başı', 'Dönem Sonu', 'Başlangıç Değeri', 'Bitiş Değeri', 'Fark', 'Yüzde Değişim']);

            foreach ($trends as $trend) {
                fputcsv($file, [
                    $trend->period_start->format('d.m.Y'),
                    $trend->period_end->format('d.m.Y'),
                    $trend->starting_value,
                    $trend->ending_value,
                    $trend->value_difference,
                    $trend->percent_change . '%',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Trend sil
     */
    public function destroy($id)
    {
        $trend = FinancialTrend::findOrFail($id);
        $projectId = $trend->project_id;
        $trend->delete();

        return redirect()->route('finance.trends.dashboard', ['project_id' => $projectId])
            ->with('success', 'Trend silinmiştir.');
    }

    /**
     * Trend analiz dashboard'ı
     */
    public function dashboard(Request $request)
    {
        $projectId = $request->query('project_id');
        $periodType = $request->query('period', 'monthly');
        $periodsBack = $request->query('periods', 12);

        $project = $projectId ? Project::findOrFail($projectId) : Project::first();

        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Proje bulunamadı');
        }

        // Trendleri oluştur ve kaydet
        $trends = $this->trendService->generateTrends($project, $periodType, $periodsBack);

        foreach ($trends as $trendData) {
            FinancialTrend::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'period_start' => $trendData['period_start'],
                    'period_end' => $trendData['period_end'],
                    'period_type' => $trendData['period_type'],
                ],
                $trendData
            );
        }

        // Veritabanından yeniden yükle
        $trendRecords = FinancialTrend::where('project_id', $project->id)
            ->where('period_type', $periodType)
            ->orderBy('period_start', 'asc')
            ->take($periodsBack)
            ->get();

        $averages = $this->trendService->getAverageTrends($project);

        $projects = Project::all();

        return view('finance.trends.dashboard', compact(
            'project',
            'trendRecords',
            'projects',
            'periodType',
            'periodsBack',
            'averages'
        ));
    }

    /**
     * Trend grafikleri
     */
    public function charts(Request $request)
    {
        $projectId = $request->query('project_id');
        $periodType = $request->query('period', 'monthly');

        $project = $projectId ? Project::findOrFail($projectId) : Project::first();

        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $trends = FinancialTrend::where('project_id', $project->id)
            ->where('period_type', $periodType)
            ->orderBy('period_start', 'asc')
            ->take(12)
            ->get();

        // Grafik verisi hazırla
        $chartData = [
            'periods' => $trends->map(fn($t) => $t->period_start->format('M Y'))->toArray(),
            'net_cash_flow' => $trends->pluck('net_cash_flow')->toArray(),
            'inflow' => $trends->pluck('total_inflow')->toArray(),
            'outflow' => $trends->pluck('total_outflow')->toArray(),
            'payment_timeliness' => $trends->pluck('payment_timeliness_ratio')->toArray(),
            'expense_ratio' => $trends->pluck('expense_ratio')->toArray(),
            'budget_variance' => $trends->pluck('budget_variance')->toArray(),
        ];

        return response()->json($chartData);
    }

    /**
     * Trend karşılaştırması
     */
    public function comparison(Request $request)
    {
        $projectId = $request->query('project_id');
        $periodType = $request->query('period', 'monthly');

        $project = $projectId ? Project::findOrFail($projectId) : Project::first();

        $trends = FinancialTrend::where('project_id', $project->id)
            ->where('period_type', $periodType)
            ->orderBy('period_start', 'asc')
            ->take(12)
            ->get();

        $comparisonData = $trends->map(function ($trend) {
            return [
                'period' => $trend->period_start->format('M Y'),
                'inflow' => $trend->total_inflow,
                'outflow' => $trend->total_outflow,
                'net_flow' => $trend->net_cash_flow,
                'payment_performance' => $trend->payment_timeliness_ratio,
                'collection_rate' => $trend->collection_ratio,
                'expense_ratio' => $trend->expense_ratio,
                'budget_variance' => $trend->budget_variance,
                'health_score' => $trend->getHealthScoreAttribute(),
                'growth_rate' => $trend->growth_rate,
                'trend_direction' => $trend->getTrendDirectionLabel(),
            ];
        });

        $projects = Project::all();

        return view('finance.trends.comparison', compact('project', 'comparisonData', 'projects', 'periodType'));
    }

    /**
     * Detaylı trend raporu
     */
    public function report(Request $request)
    {
        $projectId = $request->query('project_id');
        $periodType = $request->query('period', 'monthly');

        $project = $projectId ? Project::findOrFail($projectId) : Project::first();

        $trends = FinancialTrend::where('project_id', $project->id)
            ->where('period_type', $periodType)
            ->orderBy('period_start', 'asc')
            ->take(12)
            ->get();

        $averages = $this->trendService->getAverageTrends($project);

        $projects = Project::all();

        return view('finance.trends.report', compact('project', 'trends', 'projects', 'averages', 'periodType'));
    }

    /**
     * Sağlık skoru dashboard'ı
     */
    public function healthScore(Request $request)
    {
        $projectId = $request->query('project_id');
        $periodType = $request->query('period', 'monthly');

        $project = $projectId ? Project::findOrFail($projectId) : Project::first();

        $trends = FinancialTrend::where('project_id', $project->id)
            ->where('period_type', $periodType)
            ->orderBy('period_start', 'asc')
            ->take(12)
            ->get();

        $healthScores = $trends->map(fn($t) => [
            'period' => $t->period_start->format('M Y'),
            'score' => $t->getHealthScoreAttribute(),
            'inflow' => $t->total_inflow,
            'outflow' => $t->total_outflow,
            'payment_timeliness' => $t->payment_timeliness_ratio,
            'collection_ratio' => $t->collection_ratio,
        ]);

        $projects = Project::all();

        return view('finance.trends.health-score', compact('project', 'healthScores', 'projects'));
    }
}
