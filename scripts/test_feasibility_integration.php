<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArrayInput([]),
    new Symfony\Component\Console\Output\NullOutput
);

use App\Models\Opportunity;
use App\Models\FeasibilityStudy;

$opp = Opportunity::create(['title'=>'Test Opp '.time(),'description'=>'Test','customer'=>'ACME','estimated_value'=>100000]);
$study = FeasibilityStudy::create(['name'=>'Test Study '.time(),'opportunity_id'=>$opp->id,'project_type'=>'Test','total_area'=>100,'unit_sale_price'=>1000,'land_cost'=>10000,'construction_cost_per_m2'=>500,'soft_costs_percent'=>10,'construction_months'=>6,'sales_months'=>6,'equity'=>5000,'debt'=>20000,'interest_rate_percent'=>8,'status'=>'draft']);

$opp = $opp->fresh();
$study = $study->fresh();

echo "Opportunity ID: {$opp->id}\n";
echo "Activity log:\n" . ($opp->activity_log ?? '(empty)') . "\n";
echo "Study created_by: " . ($study->created_by ?? 'null') . "\n";

$kernel->terminate($input, $status);
