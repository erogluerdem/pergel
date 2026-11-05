<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArrayInput([]),
    new Symfony\Component\Console\Output\NullOutput
);

use App\Models\User;
use App\Models\Opportunity;
use App\Http\Controllers\FeasibilityController;
use Illuminate\Http\Request;

// create or get a test user (id=1 if exists)
$user = User::first() ?? User::create(['name'=>'Test User','email'=>'test@example.com','password'=>bcrypt('secret')]);
// login
auth()->login($user);

// create opportunity
$opp = Opportunity::create(['title'=>'Integration Opp '.time(),'description'=>'integration test','customer'=>'TestCo','estimated_value'=>50000]);

// prepare request data
$req = Request::create('/portfolio/tenders/feasibility-capital','POST',[
    'name'=>'Integration Study '.time(),
    'opportunity_id'=>$opp->id,
    'project_type'=>'Demo',
    'total_area'=>200,
    'unit_sale_price'=>1200,
    'land_cost'=>50000,
    'construction_cost_per_m2'=>600,
    'soft_costs_percent'=>8,
    'construction_months'=>10,
    'sales_months'=>12,
    'equity'=>20000,
    'debt'=>10000,
    'interest_rate_percent'=>7,
]);

$ctrl = new FeasibilityController();
$response = $ctrl->store($req);

$opp = $opp->fresh();
$study = \App\Models\FeasibilityStudy::where('opportunity_id',$opp->id)->orderBy('id','desc')->first();

echo "Created study id: " . ($study->id ?? 'none') . "\n";
echo "Study created_by: " . ($study->created_by ?? 'null') . "\n";
echo "Opportunity activity log:\n" . ($opp->activity_log ?? '(empty)') . "\n";

$kernel->terminate($input, $status);
