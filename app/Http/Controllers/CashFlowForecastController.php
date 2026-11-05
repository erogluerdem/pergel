<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\CashFlowForecast;
use App\Services\CashFlowForecastService;
use Carbon\Carbon;

class CashFlowForecastController extends Controller
{
    private $forecastService;

    public function __construct(CashFlowForecastService $forecastService)
    {
        $this->forecastService = $forecastService;
    }

    /**
     * Tahmin sayfası - Tüm tahminleri listele
     */
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = null;

        if ($projectId) {
            $project = Project::findOrFail($projectId);
            $forecasts = CashFlowForecast::where('project_id', $projectId)
                ->orderBy('forecast_date', 'desc')
                ->paginate(12);
        } else {
            $forecasts = CashFlowForecast::orderBy('forecast_date', 'desc')
                ->paginate(12);
        }

        $projects = Project::all();

        return view('finance.forecast.index', compact('forecasts', 'project', 'projects'));
    }

    /**
     * Tahmin dashboard'ı
     */
    public function dashboard(Request $request)
    {
        $projectId = $request->query('project_id');
        $monthsAhead = $request->query('months', 12);

        $project = $projectId ? Project::findOrFail($projectId) : Project::first();

        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Proje bulunamadı');
        }

        $forecasts = CashFlowForecast::where('project_id', $project->id)
            ->orderBy('forecast_date', 'asc')
            ->take($monthsAhead)
            ->get();

        // Özet istatistikler
        $avgConfidence = $forecasts->avg('confidence_level') ?? 0;
        $avgVariance = $forecasts->avg('variance_percentage') ?? 0;
        $positiveFlowCount = $forecasts->where('closing_balance', '>', 0)->count();
        $negativeFlowCount = $forecasts->where('closing_balance', '<', 0)->count();

        $projects = Project::all();

        return view('finance.forecast.dashboard', compact(
            'project',
            'forecasts',
            'projects',
            'avgConfidence',
            'avgVariance',
            'positiveFlowCount',
            'negativeFlowCount'
        ));
    }

    /**
     * Tahmin oluşturma formu göster
     */
    public function create()
    {
        $projects = Project::where('deleted_at', null)->get();
        return view('finance.forecast.create', compact('projects'));
    }

    /**
     * Yeni tahmin kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'forecast_date' => 'required|date',
            'inflow_planned' => 'required|numeric|min:0',
            'outflow_planned' => 'required|numeric|min:0',
            'inflow_actual' => 'nullable|numeric|min:0',
            'outflow_actual' => 'nullable|numeric|min:0',
            'confidence_level' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,confirmed,revised',
        ]);

        $project = Project::findOrFail($validated['project_id']);
        
        // Açılış bakiyesini hesapla
        $lastForecast = CashFlowForecast::where('project_id', $project->id)
            ->orderBy('forecast_date', 'desc')
            ->first();
        
        $openingBalance = $lastForecast ? $lastForecast->closing_balance : ($project->estimated_budget ?? 0);
        
        // Tahmin oluştur
        $forecast = new CashFlowForecast($validated);
        $forecast->opening_balance = $openingBalance;
        $forecast->closing_balance = $openingBalance + 
            ($validated['inflow_planned'] - $validated['outflow_planned']);
        
        // Sapma hesapla
        if (isset($validated['inflow_actual']) && isset($validated['outflow_actual'])) {
            $plannedNet = $validated['inflow_planned'] - $validated['outflow_planned'];
            if ($plannedNet != 0) {
                $actualNet = $validated['inflow_actual'] - $validated['outflow_actual'];
                $forecast->variance_percentage = abs($actualNet - $plannedNet) / $plannedNet * 100;
            }
        }

        $forecast->save();

        return redirect()->route('finance.forecast.dashboard', ['project_id' => $project->id])
            ->with('success', 'Tahmin başarıyla oluşturuldu!');
    }

    /**
     * Tahmin oluştur/güncelle
     */
    public function generate(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $monthsAhead = $request->input('months_ahead', 12);

        try {
            $forecasts = $this->forecastService->generateForecast($project, $monthsAhead);

            foreach ($forecasts as $forecastData) {
                CashFlowForecast::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'forecast_date' => $forecastData['forecast_date'],
                    ],
                    $forecastData
                );
            }

            return redirect()->route('finance.forecast.dashboard', ['project_id' => $project->id])
                ->with('success', "$monthsAhead aylık tahmin başarıyla oluşturuldu!");
        } catch (\Exception $e) {
            return back()->with('error', 'Tahmin oluşturulamadı: ' . $e->getMessage());
        }
    }

    /**
     * Tekil tahmin göster
     */
    public function show($id)
    {
        $forecast = CashFlowForecast::findOrFail($id);
        $project = $forecast->project;

        // Tarihsel veriler
        $historicalForecasts = CashFlowForecast::where('project_id', $project->id)
            ->where('forecast_date', '<', $forecast->forecast_date)
            ->orderBy('forecast_date', 'desc')
            ->limit(6)
            ->get()
            ->reverse();

        return view('finance.forecast.show', compact('forecast', 'project', 'historicalForecasts'));
    }

    /**
     * Tahmin düzenle
     */
    public function edit($id)
    {
        $forecast = CashFlowForecast::findOrFail($id);
        return view('finance.forecast.edit', compact('forecast'));
    }

    /**
     * Tahmin güncelle
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'inflow_planned' => 'required|numeric|min:0',
            'outflow_planned' => 'required|numeric|min:0',
            'confidence_level' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,confirmed,revised',
        ]);

        $forecast = CashFlowForecast::findOrFail($id);
        
        // Tahmin değerlerini güncelledikten sonra bakiye yeniden hesapla
        $forecast->update($validated);
        
        // Kapanış bakiyesi = Açılış + (Planlanan Giriş - Planlanan Çıkış)
        $forecast->closing_balance = $forecast->opening_balance + 
            ($forecast->inflow_planned - $forecast->outflow_planned);
        
        // Sapma hesapla
        if ($forecast->inflow_planned - $forecast->outflow_planned != 0) {
            $forecast->variance_percentage = abs(
                ($forecast->inflow_actual - $forecast->outflow_actual) - 
                ($forecast->inflow_planned - $forecast->outflow_planned)
            ) / ($forecast->inflow_planned - $forecast->outflow_planned) * 100;
        }

        $forecast->save();

        return redirect()->route('finance.forecast.show', $forecast->id)
            ->with('success', 'Tahmin başarıyla güncellendi!');
    }

    /**
     * Aylık karşılaştırma (Tahmin vs Gerçekleşme)
     */
    public function comparison(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = $projectId ? Project::findOrFail($projectId) : Project::first();

        $forecasts = CashFlowForecast::where('project_id', $project->id)
            ->orderBy('forecast_date', 'asc')
            ->get();

        $comparisonData = $forecasts->map(function ($forecast) {
            return [
                'period' => $forecast->forecast_date->format('M Y'),
                'planned_inflow' => $forecast->inflow_planned,
                'actual_inflow' => $forecast->inflow_actual,
                'inflow_variance' => $forecast->inflow_actual - $forecast->inflow_planned,
                'planned_outflow' => $forecast->outflow_planned,
                'actual_outflow' => $forecast->outflow_actual,
                'outflow_variance' => $forecast->outflow_actual - $forecast->outflow_planned,
                'planned_net' => $forecast->inflow_planned - $forecast->outflow_planned,
                'actual_net' => $forecast->inflow_actual - $forecast->outflow_actual,
                'closing_balance' => $forecast->closing_balance,
                'confidence' => $forecast->confidence_level,
            ];
        });

        $projects = Project::all();

        return view('finance.forecast.comparison', compact('project', 'comparisonData', 'projects'));
    }

    /**
     * Tahmin sil
     */
    public function destroy($id)
    {
        $forecast = CashFlowForecast::findOrFail($id);
        $projectId = $forecast->project_id;
        $forecast->delete();

        return redirect()->route('finance.forecast.dashboard', ['project_id' => $projectId])
            ->with('success', 'Tahmin silinmiştir.');
    }
}
