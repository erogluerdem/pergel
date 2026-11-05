<?php

namespace Database\Seeders;

use App\Models\CashFlowForecast;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoCashFlowForecastSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();
        if ($projects->isEmpty()) {
            return;
        }

        foreach ($projects as $project) {
            $runningBalance = 25000000.00; // 25M TL starting opening balance

            for ($i = 0; $i < 12; $i++) {
                $forecastDate = now()->subMonths(3)->addMonths($i)->startOfMonth()->toDateString();

                if (CashFlowForecast::where('project_id', $project->id)->where('forecast_date', $forecastDate)->exists()) {
                    continue;
                }

                $inflowPlanned = rand(450, 950) * 10000.00; // 4.5M - 9.5M
                $outflowPlanned = rand(350, 750) * 10000.00; // 3.5M - 7.5M

                $isPast = $i < 3;
                $inflowActual = $isPast ? $inflowPlanned * (1 + (rand(-8, 8) / 100)) : 0.00;
                $outflowActual = $isPast ? $outflowPlanned * (1 + (rand(-5, 5) / 100)) : 0.00;

                $netFlow = $inflowPlanned - $outflowPlanned;
                $closingBalance = $runningBalance + $netFlow;

                $variance = 0.00;
                if ($isPast && $inflowActual > 0) {
                    $plannedNet = $inflowPlanned - $outflowPlanned;
                    $actualNet = $inflowActual - $outflowActual;
                    if ($plannedNet != 0) {
                        $variance = abs($actualNet - $plannedNet) / abs($plannedNet) * 100;
                    }
                }

                CashFlowForecast::create([
                    'project_id' => $project->id,
                    'forecast_date' => $forecastDate,
                    'opening_balance' => $runningBalance,
                    'inflow_planned' => $inflowPlanned,
                    'inflow_actual' => $inflowActual,
                    'outflow_planned' => $outflowPlanned,
                    'outflow_actual' => $outflowActual,
                    'closing_balance' => $closingBalance,
                    'currency' => 'TRY',
                    'variance_percentage' => round($variance, 2),
                    'confidence_level' => rand(75, 95),
                    'status' => $isPast ? 'confirmed' : 'draft',
                    'notes' => ($i % 2 === 0 ? 'Hak ediş tahsilatı ve ana imalat malzemesi alımları projelendirildi.' : 'Taşeron ödeme takvimi ve şantiye genel gider nakit çıkışları.'),
                ]);

                $runningBalance = $closingBalance;
            }
        }
    }
}
