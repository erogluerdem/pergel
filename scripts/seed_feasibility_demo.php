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

echo "Seeding demo feasibility studies...\n";

$samples = [
    [
        'opp'=>['title'=>'Demo Fırsat - Konut Projesi','description'=>'Konut projesi için fırsat örneği','customer'=>'ACME Yapı','estimated_value'=>1200000],
        'study'=>['name'=>'Konut Fizibilite - A','project_type'=>'Konut','total_area'=>2500,'unit_sale_price'=>1500,'other_revenue'=>50000,'land_cost'=>300000,'construction_cost_per_m2'=>900,'soft_costs_percent'=>10,'construction_months'=>14,'sales_months'=>12,'equity'=>200000,'debt'=>700000,'interest_rate_percent'=>9,'status'=>'draft']
    ],
    [
        'opp'=>['title'=>'Demo Fırsat - Ticari Kompleks','description'=>'Ticari alan ve ofis karma kullanım','customer'=>'Beta İnşaat','estimated_value'=>2500000],
        'study'=>['name'=>'Ticari Fizibilite - B','project_type'=>'Ticari','total_area'=>4000,'unit_sale_price'=>2000,'other_revenue'=>120000,'land_cost'=>500000,'construction_cost_per_m2'=>1100,'soft_costs_percent'=>12,'construction_months'=>18,'sales_months'=>18,'equity'=>400000,'debt'=>1000000,'interest_rate_percent'=>8,'status'=>'draft']
    ],
    [
        'opp'=>['title'=>'Demo Fırsat - Karma Proje','description'=>'Karma kullanım örneği (konut+ticari)','customer'=>'Gamma Yapı','estimated_value'=>1800000],
        'study'=>['name'=>'Karma Fizibilite - C','project_type'=>'Karma','total_area'=>3200,'unit_sale_price'=>1400,'other_revenue'=>80000,'land_cost'=>350000,'construction_cost_per_m2'=>950,'soft_costs_percent'=>11,'construction_months'=>16,'sales_months'=>14,'equity'=>250000,'debt'=>600000,'interest_rate_percent'=>8.5,'status'=>'draft']
    ],
];

$created = [];
foreach ($samples as $s) {
    try {
        $opp = Opportunity::create($s['opp']);
    } catch (Exception $e) {
        echo "Warning: could not create Opportunity ({$s['opp']['title']}): " . $e->getMessage() . "\n";
        // Try to find existing
        $opp = Opportunity::where('title', $s['opp']['title'])->first();
        if (!$opp) continue;
    }

    $data = $s['study'];
    $data['opportunity_id'] = $opp->id;
    try {
        $study = FeasibilityStudy::create($data);
        $created[] = ['opp'=>$opp,'study'=>$study];
        echo "Created study ID: {$study->id} (" . $study->name . ") linked to Opportunity ID: {$opp->id}\n";
    } catch (Exception $e) {
        echo "Error creating study for Opportunity ID {$opp->id}: " . $e->getMessage() . "\n";
    }
}

if (count($created) > 0) {
    echo "\nCreated " . count($created) . " demo feasibility studies.\n";
    foreach ($created as $c) {
        echo "- Study: {$c['study']->name} -> /portfolio/tenders/feasibility-capital/{$c['study']->id}\n";
    }
} else {
    echo "No studies created.\n";
}

$kernel->terminate($input, $status);
