<?php

namespace App\Services;

use App\Models\CashFlowForecast;
use App\Models\Payment;
use App\Models\CashBankTransaction;
use App\Models\Voucher;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CashFlowForecastService
{
    /**
     * Proje için nakit akış tahmini oluştur
     */
    public function generateForecast(Project $project, $monthsAhead = 12): Collection
    {
        $forecasts = collect();
        $startDate = now()->startOfMonth();

        for ($i = 0; $i < $monthsAhead; $i++) {
            $periodStart = $startDate->copy()->addMonths($i);
            $periodEnd = $periodStart->copy()->endOfMonth();

            $forecast = $this->calculateForecastForPeriod(
                $project,
                $periodStart,
                $periodEnd
            );

            $forecasts->push($forecast);
        }

        return $forecasts;
    }

    /**
     * Belirli bir dönem için tahmin hesapla
     */
    private function calculateForecastForPeriod(Project $project, Carbon $start, Carbon $end)
    {
        $historicalData = $this->getHistoricalData($project, $start, $end);
        
        // Açılış bakiyesi
        $openingBalance = $this->getOpeningBalance($project, $start);

        // Planlanan giriş/çıkış (geçmiş verilere dayalı)
        $plannedInflow = $this->calculatePlannedInflow($project, $historicalData);
        $plannedOutflow = $this->calculatePlannedOutflow($project, $historicalData);

        // Gerçekleşen (mevcut ay için)
        $actualInflow = $this->getActualInflow($project, $start, $end);
        $actualOutflow = $this->getActualOutflow($project, $start, $end);

        // Kapanış bakiyesi
        $closingBalance = $openingBalance + 
            ($start->diffInDays($end) <= 30 ? $actualInflow - $actualOutflow : $plannedInflow - $plannedOutflow);

        // Sapma hesapla
        $netPlanned = $plannedInflow - $plannedOutflow;
        $variance = $netPlanned != 0 ? abs($actualInflow - $actualOutflow - $netPlanned) / $netPlanned * 100 : 0;

        // Güven seviyesi (geçmiş tahmin doğruluğuna göre)
        $confidence = $this->calculateConfidenceLevel($project, $start);

        return [
            'project_id' => $project->id,
            'forecast_date' => $start->toDateString(),
            'opening_balance' => $openingBalance,
            'inflow_planned' => $plannedInflow,
            'inflow_actual' => $actualInflow,
            'outflow_planned' => $plannedOutflow,
            'outflow_actual' => $actualOutflow,
            'closing_balance' => $closingBalance,
            'currency' => 'TRY',
            'variance_percentage' => $variance,
            'status' => now()->startOfMonth()->eq($start->startOfMonth()) ? 'draft' : 'confirmed',
            'confidence_level' => $confidence,
        ];
    }

    /**
     * Geçmiş verileri getir
     */
    private function getHistoricalData(Project $project, Carbon $start, Carbon $end): Collection
    {
        return CashBankTransaction::where('project_id', $project->id)
            ->whereBetween('transaction_date', [$start->copy()->subMonths(6)->toDateString(), $start->toDateString()])
            ->get();
    }

    /**
     * Açılış bakiyesi hesapla
     */
    private function getOpeningBalance(Project $project, Carbon $date): float
    {
        $previousForecast = CashFlowForecast::where('project_id', $project->id)
            ->where('forecast_date', '<', $date->toDateString())
            ->orderBy('forecast_date', 'desc')
            ->first();

        if ($previousForecast) {
            return (float) $previousForecast->closing_balance;
        }

        // İlk ay ise gerçek bakiye getir
        return $this->getActualBalance($project);
    }

    /**
     * Gerçek bakiye
     */
    private function getActualBalance(Project $project): float
    {
        $transactions = CashBankTransaction::where('project_id', $project->id)
            ->where('direction', 'in')
            ->sum('amount') - 
        CashBankTransaction::where('project_id', $project->id)
            ->where('direction', 'out')
            ->sum('amount');

        return (float) $transactions;
    }

    /**
     * Planlanan girişleri hesapla
     */
    private function calculatePlannedInflow(Project $project, Collection $historicalData): float
    {
        $historicalInflow = $historicalData
            ->where('direction', 'in')
            ->sum('amount') ?: 0;

        $monthCount = 6; // 6 aylık ortalama
        $averageMonthly = $historicalInflow / $monthCount;

        // Gelecek tahsilat ve ödemeler
        $plannedVouchers = Voucher::where('project_id', $project->id)
            ->where('type', 'collection')
            ->where('amount', '>', 0)
            ->sum('amount') ?: 0;

        return (float) max($averageMonthly, $plannedVouchers * 0.7);
    }

    /**
     * Planlanan çıkışları hesapla
     */
    private function calculatePlannedOutflow(Project $project, Collection $historicalData): float
    {
        $historicalOutflow = $historicalData
            ->where('direction', 'out')
            ->sum('amount') ?: 0;

        $monthCount = 6;
        $averageMonthly = $historicalOutflow / $monthCount;

        // Gelecek ödemeler
        $plannedPayments = Payment::where('project_id', $project->id)
            ->whereNull('approved')
            ->sum('requested') ?: 0;

        return (float) max($averageMonthly, $plannedPayments * 0.8);
    }

    /**
     * Gerçek girişleri getir
     */
    private function getActualInflow(Project $project, Carbon $start, Carbon $end): float
    {
        return (float) CashBankTransaction::where('project_id', $project->id)
            ->where('direction', 'in')
            ->whereBetween('transaction_date', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');
    }

    /**
     * Gerçek çıkışları getir
     */
    private function getActualOutflow(Project $project, Carbon $start, Carbon $end): float
    {
        return (float) CashBankTransaction::where('project_id', $project->id)
            ->where('direction', 'out')
            ->whereBetween('transaction_date', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');
    }

    /**
     * Güven seviyesi hesapla
     */
    private function calculateConfidenceLevel(Project $project, Carbon $date): int
    {
        $pastForecasts = CashFlowForecast::where('project_id', $project->id)
            ->where('forecast_date', '<', $date->toDateString())
            ->orderBy('forecast_date', 'desc')
            ->take(6)
            ->get();

        if ($pastForecasts->isEmpty()) {
            return 60;
        }

        $accuracyScores = $pastForecasts->map(fn($f) => 100 - min($f->variance_percentage, 100));
        $averageAccuracy = $accuracyScores->avg();

        return (int) max(40, min(100, $averageAccuracy + 20));
    }

    /**
     * Tahmin kaydını güncelle
     */
    public function updateForecast(CashFlowForecast $forecast, array $data): CashFlowForecast
    {
        // Risk skoru hesapla
        if ($forecast->closing_balance < 0) {
            $forecast->notes = ($forecast->notes ?? '') . "\n⚠️ Nakit açığı: " . abs($forecast->closing_balance);
        }

        $forecast->update($data);
        return $forecast;
    }
}
