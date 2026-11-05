<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\FinancialRisk;
use App\Models\Payment;
use App\Services\RiskAnalysisService;

class FinancialRiskController extends Controller
{
    private $riskService;

    public function __construct(RiskAnalysisService $riskService)
    {
        $this->riskService = $riskService;
    }

    /**
     * Risk analizi dashboard'ı
     */
    public function dashboard(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = $projectId ? Project::findOrFail($projectId) : Project::first();

        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Proje bulunamadı');
        }

        $risks = FinancialRisk::where('project_id', $project->id)
            ->where('status', '!=', 'resolved')
            ->orderBy('risk_score', 'desc')
            ->get();

        // Risk metrikleri
        $criticalRisks = $risks->where('risk_level', 'critical')->count();
        $highRisks = $risks->where('risk_level', 'high')->count();
        $mediumRisks = $risks->where('risk_level', 'medium')->count();
        $lowRisks = $risks->where('risk_level', 'low')->count();

        $totalRiskAmount = $risks->sum('risk_amount');
        $avgRiskScore = $risks->avg('risk_score') ?? 0;

        $projects = Project::all();

        return view('finance.risks.dashboard', compact(
            'project',
            'risks',
            'projects',
            'criticalRisks',
            'highRisks',
            'mediumRisks',
            'lowRisks',
            'totalRiskAmount',
            'avgRiskScore'
        ));
    }

    /**
     * Riskleri listele
     */
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');
        $riskLevel = $request->query('level');
        $status = $request->query('status', 'open');

        $query = FinancialRisk::query();

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if ($riskLevel) {
            $query->where('risk_level', $riskLevel);
        }

        $risks = $query->where('status', '!=', 'resolved')
            ->orderBy('risk_score', 'desc')
            ->paginate(15);

        $projects = Project::all();

        return view('finance.risks.index', compact('risks', 'projects', 'projectId', 'riskLevel'));
    }

    /**
     * Risk detaylarını göster
     */
    public function show($id)
    {
        $risk = FinancialRisk::findOrFail($id);
        $project = $risk->project;
        $payment = $risk->payment;

        return view('finance.risks.show', compact('risk', 'project', 'payment'));
    }

    /**
     * Risk oluşturma formu göster
     */
    public function create()
    {
        $projects = Project::where('deleted_at', null)->get();
        return view('finance.risks.create', compact('projects'));
    }

    /**
     * Yeni risk kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'risk_type' => 'required|in:overdue,cash_shortage,currency_fluctuation,vendor_default,budget_overrun',
            'probability_score' => 'required|numeric|min:0|max:100',
            'impact_score' => 'required|numeric|min:0|max:100',
            'description' => 'required|string|min:10',
            'mitigation_strategy' => 'nullable|string',
            'status' => 'required|in:open,monitoring,resolved',
        ]);

        // Risk skorunu hesapla: (Olasılık × Etki) / 100
        $validated['risk_score'] = ($validated['probability_score'] * $validated['impact_score']) / 100;

        // Risk seviyesini belirle
        $score = $validated['risk_score'];
        if ($score >= 75) {
            $validated['risk_level'] = 'critical';
        } elseif ($score >= 50) {
            $validated['risk_level'] = 'high';
        } elseif ($score >= 25) {
            $validated['risk_level'] = 'medium';
        } else {
            $validated['risk_level'] = 'low';
        }

        // Beklenen risk miktarını hesapla
        $project = Project::findOrFail($validated['project_id']);
        $estimated_amount = $project->estimated_budget ?? 0;
        $validated['risk_amount'] = ($validated['probability_score'] / 100) * $estimated_amount;

        $risk = FinancialRisk::create($validated);

        return redirect()->route('finance.risks.dashboard', ['project_id' => $project->id])
            ->with('success', 'Risk başarıyla kaydedildi!');
    }

    /**
     * Risk analizi çalıştır
     */
    public function analyze($projectId)
    {
        $project = Project::findOrFail($projectId);

        try {
            $riskList = $this->riskService->analyzeProjectRisks($project);

            foreach ($riskList as $riskData) {
                $existingRisk = FinancialRisk::where('project_id', $project->id)
                    ->where('risk_type', $riskData['risk_type'])
                    ->where('payment_id', $riskData['payment_id'] ?? null)
                    ->first();

                if ($existingRisk) {
                    $existingRisk->update($riskData);
                } else {
                    FinancialRisk::create($riskData);
                }
            }

            return redirect()->route('finance.risks.dashboard', ['project_id' => $project->id])
                ->with('success', 'Risk analizi tamamlandı. ' . $riskList->count() . ' risk tespit edildi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Risk analizi başarısız: ' . $e->getMessage());
        }
    }

    /**
     * Risk düzenle
     */
    public function edit($id)
    {
        $risk = FinancialRisk::findOrFail($id);
        $users = \App\Models\User::all();

        return view('finance.risks.edit', compact('risk', 'users'));
    }

    /**
     * Risk güncelle
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'risk_level' => 'required|in:low,medium,high,critical',
            'impact_score' => 'required|numeric|min:0|max:100',
            'probability_score' => 'required|numeric|min:0|max:100',
            'mitigation_strategy' => 'nullable|string',
            'target_resolution_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:open,monitoring,resolved',
            'notes' => 'nullable|string',
        ]);

        $risk = FinancialRisk::findOrFail($id);
        $risk->update($validated);

        // Risk skorunu yeniden hesapla
        $this->riskService->updateRiskScore($risk);

        return redirect()->route('finance.risks.show', $risk->id)
            ->with('success', 'Risk başarıyla güncellendi!');
    }

    /**
     * Kritik riskleri getir (API)
     */
    public function getCriticalRisks($projectId)
    {
        $project = Project::findOrFail($projectId);
        $risks = $this->riskService->getCriticalRisks($project);

        return response()->json([
            'count' => $risks->count(),
            'risks' => $risks->map(fn($r) => [
                'id' => $r->id,
                'type' => $r->getRiskTypeLabel(),
                'level' => $r->getRiskLevelLabel(),
                'amount' => $r->risk_amount,
                'score' => $r->risk_score,
                'description' => $r->description,
            ]),
        ]);
    }

    /**
     * Risk riski sil
     */
    public function destroy($id)
    {
        $risk = FinancialRisk::findOrFail($id);
        $projectId = $risk->project_id;
        $risk->delete();

        return redirect()->route('finance.risks.dashboard', ['project_id' => $projectId])
            ->with('success', 'Risk kaydı silinmiştir.');
    }
}
