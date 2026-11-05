<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArrayInput([]),
    new Symfony\Component\Console\Output\NullOutput
);

use App\Models\FeasibilityStudy;
use App\Http\Controllers\FeasibilityController;

// create demo study
$demo = FeasibilityStudy::create([
    'name'=>'Demo Test Senaryosu '.time(),
    'project_type'=>'Konut',
    'total_area'=>1000,
    'unit_sale_price'=>1500,
    'other_revenue'=>50000,
    'land_cost'=>200000,
    'construction_cost_per_m2'=>800,
    'soft_costs_percent'=>10,
    'construction_months'=>12,
    'sales_months'=>12,
    'equity'=>100000,
    'debt'=>150000,
    'interest_rate_percent'=>8,
    'status'=>'draft'
]);

// Compute results (copy of controller logic)
$totalRevenue = ($demo->total_area ?: 0) * ($demo->unit_sale_price ?: 0) + ($demo->other_revenue ?: 0);
$constructionCost = ($demo->total_area ?: 0) * ($demo->construction_cost_per_m2 ?: 0);
$softCosts = ($demo->soft_costs_percent ?: 0) / 100 * ($constructionCost + ($demo->land_cost ?: 0));
$totalCost = ($demo->land_cost ?: 0) + $constructionCost + $softCosts;
$grossProfit = $totalRevenue - $totalCost;
$roi = $totalCost > 0 ? round(($grossProfit / $totalCost) * 100,2) : 0;
$discountRate = max(0.01, ($demo->interest_rate_percent ?: 10) / 100);
$initialInvestment = ($demo->equity ?: 0) + ($demo->debt ?: 0) + ($demo->land_cost ?: 0);
$months = max(1, ($demo->construction_months ?: 12) + ($demo->sales_months ?: 12));
$monthlyRevenue = $months>0 ? $totalRevenue / ($demo->sales_months ?: 1) : 0;
$cashflows = [];
$cashflows[0] = -1 * $initialInvestment;
$startMonth = ($demo->construction_months ?: 12);
for ($m=1;$m<=$months;$m++) {
    $cf = 0;
    if ($m > $startMonth) { $cf = $monthlyRevenue; }
    $cashflows[$m] = $cf;
}
$npv = 0;
foreach ($cashflows as $t=>$cf) { $npv += $cf / pow(1+$discountRate/12, $t); }

// approximate irr (simple bisection)
$f = function($r) use ($cashflows) { $v=0; foreach($cashflows as $t=>$cf){ $v += $cf / pow(1+$r, $t); } return $v; };
$low=-0.999; $high=2; $mid=0;
for ($i=0;$i<60;$i++){ $mid=($low+$high)/2; $val=$f($mid); if (abs($val)<1e-6) break; if ($val>0) $low=$mid; else $high=$mid; }

$irr = $mid;
$cum=0; $payback=null; foreach($cashflows as $t=>$cf){ $cum += $cf; if ($cum>=0 && $t>0){ $payback=$t; break; } }

$results = [
    'total_revenue'=>$totalRevenue,
    'total_cost'=>$totalCost,
    'gross_profit'=>$grossProfit,
    'roi'=>$roi,
    'npv'=>round($npv,2),
    'irr'=>round($irr*100,2),
    'payback_months'=>$payback,
    'cashflow_labels'=>array_map(function($m){ return 'M'.$m; }, array_keys($cashflows)),
    'cashflow_values'=>array_values($cashflows),
];

// Use Dompdf if available
if (class_exists('Barryvdh\DomPDF\Facade')) {
    $pdf = Barryvdh\DomPDF\Facade::loadView('feasibility.pdf', ['s'=>$demo,'results'=>$results]);
    file_put_contents(__DIR__.'/../storage/app/feasibility_test_output.pdf', $pdf->output());
    echo "Wrote storage/app/feasibility_test_output.pdf\n";
} else {
    $content = (string) view('feasibility.pdf', ['s'=>$demo,'results'=>$results]);
    file_put_contents(__DIR__.'/../storage/app/feasibility_test_output.html', $content);
    echo "Wrote storage/app/feasibility_test_output.html\n";
}

$kernel->terminate($input, $status);
