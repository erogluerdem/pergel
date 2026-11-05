<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

// create package
$p = App\Models\TenderPackage::create(['project_id'=>1,'name'=>'Invite Test','status'=>'draft','responsible_id'=>1]);
$p->bidders = ['alpha@example.com']; $p->save();

$controller = new App\Http\Controllers\TenderPackageController();

// publish -> creates invitations and queues mail (may be queued)
$req = Request::create('/dummy','POST', ['bidders'=> $p->bidders]);
$controller->publish($req, $p->id);

$inv = App\Models\TenderInvitation::where('tender_package_id',$p->id)->first();
if (!$inv) { echo "Invitation not created\n"; exit(1); }
echo "Invitation created token=".$inv->token." email=".$inv->bidder_email."\n";
$access = URL::temporarySignedRoute('tenders.package.access', $inv->expires_at, ['id'=>$p->id,'bidder'=>base64_encode($inv->bidder_email),'token'=>$inv->token]);
echo "Access URL: $access\n";

// simulate GET access
$req = Request::create($access, 'GET');
$response = $controller->access($req, $p->id);
echo "Access view rendered OK\n";

// simulate submit with matching email and token
$post = ['bidder_email'=>$inv->bidder_email,'price'=>111];
$req = Request::create('/submit','POST',$post,[],[],['QUERY_STRING'=>http_build_query(['bidder'=>base64_encode($inv->bidder_email),'token'=>$inv->token,'signature'=>$req->query('signature'),'expires'=>$req->query('expires')]), 'REQUEST_URI'=>'/submit']);
$req->query->set('bidder', base64_encode($inv->bidder_email));
$req->query->set('token', $inv->token);
try {
    $controller->submitBid($req, $p->id);
    echo "Submit succeeded\n";
} catch (Exception $e) {
    echo "Submit failed: " . get_class($e) . ' - ' . $e->getMessage() . "\n";
}

$inv = App\Models\TenderInvitation::find($inv->id);
echo "Invitation used_at: ".($inv->used_at? $inv->used_at : 'null')."\n";
$bids = App\Models\TenderBid::where('tender_package_id',$p->id)->get();
echo "Bids count: ". $bids->count() ."\n";
