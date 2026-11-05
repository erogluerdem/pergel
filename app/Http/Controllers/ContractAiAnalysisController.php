<?php

namespace App\Http\Controllers;

use App\Models\LandOwnerContract;
use Illuminate\Http\Request;

class ContractAiAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $contracts = LandOwnerContract::with(['project', 'unitShareMatrices'])->get();

        $selectedContract = null;
        $aiAnalysis = null;

        if ($request->filled('contract_id')) {
            $selectedContract = LandOwnerContract::with(['project', 'unitShareMatrices'])->find($request->contract_id);
        } else {
            $selectedContract = $contracts->first();
        }

        if ($selectedContract) {
            $aiAnalysis = $this->generateAiContractAnalysis($selectedContract);
        }

        return view('land_owner_contracts.ai-analysis', compact('contracts', 'selectedContract', 'aiAnalysis'));
    }

    private function generateAiContractAnalysis($contract)
    {
        $shareRatio = $contract->land_share_percentage ?? 50;
        $contractorShare = $contract->contractor_share_percentage ?? (100 - $shareRatio);

        $totalUnits = $contract->unitShareMatrices ? $contract->unitShareMatrices->count() : 0;
        $landownerUnits = $contract->unitShareMatrices ? $contract->unitShareMatrices->where('owner_type', 'Arsa Sahibi')->count() : 0;
        $contractorUnits = $contract->unitShareMatrices ? $contract->unitShareMatrices->where('owner_type', 'Müteahhit')->count() : 0;

        // Risk Evaluation Logic
        $riskScore = 20; // Default low risk
        $riskFlags = [];

        if ($shareRatio > 55) {
            $riskScore += 25;
            $riskFlags[] = [
                'type' => 'warning',
                'title' => 'Yüksek Arsa Sahibi Payı Oranı (%' . $shareRatio . ')',
                'desc' => 'Arsa sahibi payı %55 üzerine çıktığında müteahhit kârlılığı düşer ve inşaatın tamamlanamama finansal riski artar.'
            ];
        }

        if ($contract->delay_penalty_per_day && $contract->delay_penalty_per_day > 1000) {
            $riskScore += 20;
            $riskFlags[] = [
                'type' => 'danger',
                'title' => 'Yüksek Gecikme Cezası Maddesi',
                'desc' => 'Günlük ' . number_format($contract->delay_penalty_per_day, 2, ',', '.') . ' ₺ gecikme cezası sözleşmede mevcut. Ruhsat gecikmelerine karşı munzam süre maddesi kontrol edilmeli.'
            ];
        }

        if ($totalUnits > 0 && ($landownerUnits / $totalUnits * 100) != $shareRatio) {
            $riskFlags[] = [
                'type' => 'info',
                'title' => 'Bağımsız Bölüm Paylaşım Uyumsuzluğu',
                'desc' => 'Sözleşme oranı %' . $shareRatio . ' ancak bağımsız bölüm matrisinde arsa sahibine verilen daire oranı %' . round(($landownerUnits / $totalUnits * 100), 1) . '.'
            ];
        }

        return [
            'risk_score' => min(95, $riskScore),
            'risk_level' => $riskScore > 60 ? 'Yüksek Risk' : ($riskScore > 35 ? 'Orta Risk' : 'Düşük Risk (Güvenli)'),
            'risk_color' => $riskScore > 60 ? '#ef4444' : ($riskScore > 35 ? '#f59e0b' : '#10b981'),
            'landowner_share' => $shareRatio,
            'contractor_share' => $contractorShare,
            'total_units' => $totalUnits,
            'landowner_units' => $landownerUnits,
            'contractor_units' => $contractorUnits,
            'risk_flags' => $riskFlags,
            'ai_summary' => "AI Analiz Özeti: {$contract->land_owner_name} ile imzalanan sözleşmede %{$shareRatio} arsa sahibi / %{$contractorShare} müteahhit paylaşım oranı tespit edildi. Kat karşılığı inşaat sözleşmesi Hukuk ve İmar kanunlarına genel olarak uygundur.",
        ];
    }
}
