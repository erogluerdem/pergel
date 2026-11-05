<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Inspection;
use App\Models\InspectionItem;
use App\Models\Punch;
use App\Http\Controllers\QhseController;
use Illuminate\Http\Request;

// create a test inspection
// create inspection and item
$ins = Inspection::create(['title'=>'Test Inspection for AutoPunch','discipline'=>'test']);
$item = $ins->items()->create(['text'=>'Test item to fail']);

// create a Request that simulates updating the item to 'fail'
$req = Request::create('/api/qhse/inspections/'.$ins->id.'/items/'.$item->id, 'PUT', [
    'result' => 'fail',
    'note' => 'Failing during automated test',
    'assignee' => 'testuser'
]);

$controller = new QhseController();
$resp = $controller->updateInspectionItem($req, $ins, $item->id);

// check punch created
$p = Punch::where('inspection_item_id',$item->id)->first();
if($p){
    echo "Punch created: " . $p->id . "\n";
} else {
    echo "No punch created.\n";
}
