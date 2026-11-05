<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TenderPackage;
use App\Models\TenderBid;
use App\Models\TenderBidItem;

// Create or update a test package (id=1)
$p = TenderPackage::firstOrNew(['id'=>1]);
$p->name = 'Test Tender Package';
$p->project_id = 1;
$p->boq = [
    ['no'=>'1001','description'=>'Betonarme temel kazısı','unit'=>'m3','quantity'=>120],
    ['no'=>'1002','description'=>'Dolgu toprağı serimi','unit'=>'m3','quantity'=>200],
    ['no'=>'1003','description'=>'Kırmataş dolgu','unit'=>'ton','quantity'=>50],
];
$p->save();

// Remove existing test bids for clarity
TenderBid::where('tender_package_id', $p->id)->delete();

// Bidder A (good poz matches)
$a = TenderBid::create([ 'tender_package_id'=>$p->id, 'bidder_email'=>'alpha@example.com', 'price'=>100000, 'notes'=>'Alpha offer', 'documents'=>[] ]);
TenderBidItem::create([ 'tender_bid_id'=>$a->id, 'poz_no'=>'1001', 'description'=>'Betonarme temel kazısı', 'unit'=>'m3', 'quantity'=>120, 'unit_price'=>50, 'total_price'=>6000 ]);
TenderBidItem::create([ 'tender_bid_id'=>$a->id, 'poz_no'=>'1002', 'description'=>'Dolgu toprağı serimi', 'unit'=>'m3', 'quantity'=>200, 'unit_price'=>10, 'total_price'=>2000 ]);

// Bidder B (description slightly different)
$b = TenderBid::create([ 'tender_package_id'=>$p->id, 'bidder_email'=>'beta@example.com', 'price'=>110000, 'notes'=>'Beta offer', 'documents'=>[] ]);
TenderBidItem::create([ 'tender_bid_id'=>$b->id, 'poz_no'=>'', 'description'=>'Temel kazısı betonarme', 'unit'=>'m3', 'quantity'=>120, 'unit_price'=>55, 'total_price'=>6600 ]);
TenderBidItem::create([ 'tender_bid_id'=>$b->id, 'poz_no'=>'1003', 'description'=>'Kırmataş dolgu', 'unit'=>'ton', 'quantity'=>50, 'unit_price'=>30, 'total_price'=>1500 ]);

echo "Seeded package id={$p->id}, bids: alpha={$a->id}, beta={$b->id}\n";
