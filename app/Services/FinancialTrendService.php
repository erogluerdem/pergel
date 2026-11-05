<?php

namespace App\Services;

use App\Models\FinancialTrend;
use App\Models\CashBankTransaction;
use App\Models\Payment;
use App\Models\Voucher;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class FinancialTrendService
{
    /**
     * Trend verileri oluştur
     */
    public function generateTrends(Project $project, $periodType = 'monthly', $periodsBack = 12): Collection
    {
        $trends = collect();
        $now = now();

        for ($i = $periodsBack - 1; $i >= 0; $i--) {
            $period = $this->getPeriodDates($now, $periodType, $i);
            
            $trend = $this->calculateTrendForPeriod(
                $project,
                $period['start'],
                $period['end'],
                $periodType
            );

            $trends->push($trend);
        }

        return $trends;
    }

    /**
     * Dönem tarihlerini belirle
     */
    private function getPeriodDates(Carbon $now, $periodType, $periodsBack): array
    {
        $date = $now->copy();

        switch ($periodType) {
            case 'daily':
                $date->subDays($periodsBack);
                return [
                    'start' => $date->copy()->startOfDay(),
                    'end' => $date->copy()->endOfDay(),
                ];
            case 'weekly':
                $date->subWeeks($periodsBack);
                return [
                    'start' => $date->copy()->startOfWeek(),
                    'end' => $date->copy()->endOfWeek(),
                ];
            case 'quarterly':
                $date->subQuarters($periodsBack);
                return [
                    'start' => $date->copy()->startOfQuarter(),
                    'end' => $date->copy()->endOfQuarter(),
                ];
            case 'yearly':
                $date->subYears($periodsBack);
                return [
                    'start' => $date->copy()->startOfYear(),
                    'end' => $date->copy()->endOfYear(),
                ];
            default: // monthly
                $date->subMonths($periodsBack);
                return [
                    'start' => $date->copy()->startOfMonth(),
                    'end' => $date->copy()->endOfMonth(),
                ];
        }
    }

    /**
     * Bir dönem için trend hesapla
     */
    private function calculateTrendForPeriod(Project $project, Carbon $start, Carbon $end, $periodType): array
    {
        // Nakit akış verileri
        $inflow = (float) CashBankTransaction::where('project_id', $project->id)
            ->where('direction', 'in')
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');

        $outflow = (float) CashBankTransaction::where('project_id', $project->id)
            ->where('direction', 'out')
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');

        $netCashFlow = $inflow - $outflow;

        // Ödeme metrikleri
        $totalPayments = Payment::where('project_id', $project->id)
            ->whereBetween('date', [$start, $end])
            ->count();

        $onTimePayments = Payment::where('project_id', $project->id)
            ->whereBetween('date', [$start, $end])
            ->where('status', 'Onaylandı')
            ->count();

        $latePayments = $totalPayments - $onTimePayments;
        $paymentTimelinessRatio = $totalPayments > 0 ? ($onTimePayments / $totalPayments) * 100 : 0;

        // Tahsilat metrikleri
        $collections = Voucher::where('project_id', $project->id)
            ->where('type', 'collection')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $collectionCount = $collections->count();
        $collectionRatio = $inflow > 0 ? ($collections->sum('amount') / ($inflow + $collections->sum('amount') ?: 1)) * 100 : 0;

        // Gider metrikleri
        $expenses = $outflow;
        $expenseRatio = $inflow > 0 ? ($expenses / $inflow) * 100 : 0;

        // Bütçe vs Gerçekleşme
        $budgetedAmount = $project->budget / 12; // Aylık ortalama
        $budgetVariance = $budgetedAmount > 0 ? (($outflow - $budgetedAmount) / $budgetedAmount) * 100 : 0;

        // Trend yönü (önceki döneme göre)
        $previousTrend = FinancialTrend::where('project_id', $project->id)
            ->where('period_end', '<', $start)
            ->orderBy('period_end', 'desc')
            ->first();

        $growthRate = 0;
        $trendDirection = 0;

        if ($previousTrend) {
            if ($previousTrend->net_cash_flow != 0) {
                $growthRate = (($netCashFlow - $previousTrend->net_cash_flow) / $previousTrend->net_cash_flow) * 100;
            }
            $trendDirection = $netCashFlow > $previousTrend->net_cash_flow ? 50 : ($netCashFlow < $previousTrend->net_cash_flow ? -50 : 0);
        }

        return [
            'project_id' => $project->id,
            'period_start' => $start->toDateString(),
            'period_end' => $end->toDateString(),
            'period_type' => $periodType,
            'total_inflow' => $inflow,
            'total_outflow' => $outflow,
            'net_cash_flow' => $netCashFlow,
            'payments_count' => $totalPayments,
            'on_time_payments' => $onTimePayments,
            'late_payments' => $latePayments,
            'payment_timeliness_ratio' => $paymentTimelinessRatio,
            'collections_count' => $collectionCount,
            'average_collection_period' => $collectionCount > 0 ? 30 : 0, // Basit hesaplama
            'collection_ratio' => $collectionRatio,
            'operating_expenses' => $expenses * 0.8,
            'capital_expenses' => $expenses * 0.2,
            'expense_ratio' => $expenseRatio,
            'budgeted_amount' => $budgetedAmount,
            'actual_amount' => $outflow,
            'budget_variance' => $budgetVariance,
            'growth_rate' => $growthRate,
            'trend_direction' => $trendDirection,
        ];
    }

    /**
     * Trend analiz verilerini al
     */
    public function getTrendAnalysis(Project $project, $periodType = 'monthly'): Collection
    {
        return FinancialTrend::where('project_id', $project->id)
            ->where('period_type', $periodType)
            ->orderBy('period_start', 'asc')
            ->get();
    }

    /**
     * Ortalama çıkış analizi
     */
    public function getAverageTrends(Project $project): array
    {
        $trends = FinancialTrend::where('project_id', $project->id)
            ->where('period_type', 'monthly')
            ->orderBy('period_end', 'desc')
            ->take(12)
            ->get();

        if ($trends->isEmpty()) {
            return [];
        }

        return [
            'avg_inflow' => $trends->avg('total_inflow'),
            'avg_outflow' => $trends->avg('total_outflow'),
            'avg_net_cash_flow' => $trends->avg('net_cash_flow'),
            'avg_payment_timeliness' => $trends->avg('payment_timeliness_ratio'),
            'avg_collection_ratio' => $trends->avg('collection_ratio'),
            'avg_expense_ratio' => $trends->avg('expense_ratio'),
            'avg_growth_rate' => $trends->avg('growth_rate'),
        ];
    }
}
