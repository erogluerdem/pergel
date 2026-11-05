<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TenderPackage;
use App\Http\Controllers\TenderPackageController;

// find first tender package or create one
$p = TenderPackage::first();
if (!$p) {
    // create a dummy project & package
    $proj = App\Models\Project::first() ?? App\Models\Project::create(['name'=>'Demo Project '.time(),'status'=>'planned']);
    $p = App\Models\TenderPackage::create(['project_id'=>$proj->id,'name'=>'Demo Package '.time(),'status'=>'draft','responsible_id'=>1]);
}

echo "Using TenderPackage id={$p->id}\n";
$ctrl = new TenderPackageController();
$request = new Illuminate\Http\Request();
$response = $ctrl->seedDemoBid($request, $p->id);
if ($response instanceof Illuminate\Http\RedirectResponse) {
    echo "Redirected to: " . $response->getTargetUrl() . "\n";
}
$bid = App\Models\TenderBid::where('tender_package_id',$p->id)->orderBy('created_at','desc')->first();
if ($bid) echo "Created TenderBid id={$bid->id} documents=".json_encode($bid->documents)."\n";
else echo "No bid created\n";
