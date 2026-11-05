<?php

namespace App\Services;

use App\Models\FinancialRisk;
use App\Models\Payment;
use App\Models\Project;
use App\Models\CashBankTransaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RiskAnalysisService
{
    /**
     * Proje için risk analizi yap
     */
    public function analyzeProjectRisks(Project $project): Collection
    {
        $risks = collect();

        // Vadesi geçmiş ödeme riskleri
        $overdueRisks = $this->detectOverduePaymentRisks($project);
        $risks = $risks->merge($overdueRisks);

        // Nakit yetersizliği riskleri
        $cashRisks = $this->detectCashShortageRisks($project);
        $risks = $risks->merge($cashRisks);

        // Bütçe aşımı riskleri
        $budgetRisks = $this->detectBudgetOverrunRisks($project);
        $risks = $risks->merge($budgetRisks);

        // Tedarikçi temerrüdü riskleri
        $vendorRisks = $this->detectVendorDefaultRisks($project);
        $risks = $risks->merge($vendorRisks);

        return $risks;
    }

    /**
     * Vadesi geçmiş ödemeleri tespit et
     */
    private function detectOverduePaymentRisks(Project $project): Collection
    {
        $risks = collect();
        $overduePayments = Payment::where('project_id', $project->id)
            ->where('date', '<', now()->subDays(30)->toDateString())
            ->whereNull('approved')
            ->orWhere('approved', 0)
            ->get();

        foreach ($overduePayments as $payment) {
            $daysOverdue = now()->diffInDays($payment->date);
            $impactScore = min(100, ($payment->requested / $project->budget) * 100); // Proje bütçesine oranı
            $probabilityScore = min(100, $daysOverdue / 10); // 10 gün başına 10 puan

            $risks->push([
                'project_id' => $project->id,
                'payment_id' => $payment->id,
                'risk_type' => 'overdue_payment',
                'days_overdue' => $daysOverdue,
                'risk_amount' => $payment->requested - ($payment->approved ?? 0),
                'impact_score' => $impactScore,
                'probability_score' => $probabilityScore,
                'description' => "{$daysOverdue} gün vadesi geçmiş: {$payment->contractor}",
                'identified_date' => now()->toDateString(),
                'status' => 'open',
            ]);
        }

        return $risks;
    }

    /**
     * Nakit yetersizliği tespit et
     */
    private function detectCashShortageRisks(Project $project): Collection
    {
        $risks = collect();
        $currentBalance = $this->getProjectBalance($project);
        $monthlyOutflow = $this->getMonthlyOutflow($project);

        if ($currentBalance < $monthlyOutflow) {
            $shortageAmount = $monthlyOutflow - $currentBalance;
            
            $risks->push([
                'project_id' => $project->id,
                'risk_type' => 'cash_shortage',
                'risk_amount' => $shortageAmount,
                'impact_score' => 85,
                'probability_score' => 90,
                'description' => "Aylık giderler karşılanamayabilir. Açık: " . number_format($shortageAmount, 2),
                'identified_date' => now()->toDateString(),
                'target_resolution_date' => now()->addDays(7)->toDateString(),
                'status' => 'open',
                'mitigation_strategy' => 'Kredi kullanımı veya ödeme planı değişikliği',
            ]);
        }

        return $risks;
    }

    /**
     * Bütçe aşımını tespit et
     */
    private function detectBudgetOverrunRisks(Project $project): Collection
    {
        $risks = collect();
        $spent = $this->getProjectSpent($project);
        $budget = $project->budget ?? 0;

        if ($spent > $budget) {
            $overrun = $spent - $budget;
            $overrunPercent = ($overrun / $budget) * 100;

            $risks->push([
                'project_id' => $project->id,
                'risk_type' => 'budget_overrun',
                'risk_amount' => $overrun,
                'impact_score' => min(100, $overrunPercent * 2),
                'probability_score' => 100,
                'description' => "Bütçe aşımı: %" . number_format($overrunPercent, 1),
                'identified_date' => now()->toDateString(),
                'status' => 'open',
                'mitigation_strategy' => 'Bütçe revizyon talepnamesi',
            ]);
        }

        return $risks;
    }

    /**
     * Tedarikçi temerrüdü tespit et
     */
    private function detectVendorDefaultRisks(Project $project): Collection
    {
        $risks = collect();
        
        // İlk etapta temel kontrol
        $risks->push([
            'project_id' => $project->id,
            'risk_type' => 'vendor_default',
            'risk_amount' => 0,
            'impact_score' => 60,
            'probability_score' => 20,
            'description' => "Tedarikçi performansı izlenmektedir",
            'identified_date' => now()->toDateString(),
            'status' => 'monitoring',
        ]);

        return $risks;
    }

    /**
     * Proje bakiyesi
     */
    private function getProjectBalance(Project $project): float
    {
        return (float) CashBankTransaction::where('project_id', $project->id)
            ->where('direction', 'in')
            ->sum('amount') - 
        CashBankTransaction::where('project_id', $project->id)
            ->where('direction', 'out')
            ->sum('amount');
    }

    /**
     * Aylık çıkış
     */
    private function getMonthlyOutflow(Project $project): float
    {
        return (float) CashBankTransaction::where('project_id', $project->id)
            ->where('direction', 'out')
            ->whereBetween('transaction_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->sum('amount');
    }

    /**
     * Proje harcaması
     */
    private function getProjectSpent(Project $project): float
    {
        return (float) Payment::where('project_id', $project->id)
            ->sum('approved');
    }

    /**
     * Risk skorunu güncelle
     */
    public function updateRiskScore(FinancialRisk $risk): FinancialRisk
    {
        $risk->risk_score = $risk->calculateRiskScore();
        $risk->risk_level = $risk->determineRiskLevel();
        $risk->save();
        return $risk;
    }

    /**
     * Kritik riskleri getir
     */
    public function getCriticalRisks(Project $project): Collection
    {
        return FinancialRisk::where('project_id', $project->id)
            ->whereIn('risk_level', ['high', 'critical'])
            ->where('status', '!=', 'resolved')
            ->orderBy('risk_score', 'desc')
            ->get();
    }
}
