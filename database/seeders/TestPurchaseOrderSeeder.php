<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\StockCard;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;

class TestPurchaseOrderSeeder extends Seeder
{
    public function run(): void
    {
        // create a vendor
        $vendor = Vendor::firstOrCreate([
            'name' => 'TEST TEDARIKCI A.S.'
        ], [
            'contact_person' => 'Test User',
            'email' => 'vendor@example.test'
        ]);

        // create 2 stock cards
        $s1 = StockCard::firstOrCreate(['code' => 'MAT-001'], [
            'name' => 'Beton 25kg', 'unit_price' => 150
        ]);
        $s2 = StockCard::firstOrCreate(['code' => 'MAT-002'], [
            'name' => 'Demir 8mm', 'unit_price' => 250
        ]);

        // ensure a project exists
    $project = \App\Models\Project::firstOrCreate(['name' => 'Test Proje']);

        // ensure a test user exists for requested_by
        $user = \App\Models\User::firstOrCreate(['email' => 'testuser@example.test'], ['name' => 'Test User', 'password' => bcrypt('secret')]);

        // ensure a requisition exists
        $requisition = \App\Models\Requisition::firstOrCreate([
            'project_id' => $project->id,
            'requested_by' => $user->id
        ], [
            'required_date' => now()->addDays(3)->toDateString(),
            'status' => 'pending'
        ]);

        // create a purchase order
        $po = PurchaseOrder::create([
            'po_number' => 'PO-TEST-'.time(),
            'requisition_id' => $requisition->id,
            'project_id' => $project->id,
            'vendor_id' => $vendor->id,
            'order_date' => now()->toDateString(),
            'delivery_date' => now()->addDays(7)->toDateString(),
            'total_amount' => 1000,
            'status' => 'open'
        ]);

        // items
        PurchaseOrderItem::create([
            'purchase_order_id' => $po->id,
            'description' => 'Beton 25kg',
            'sku' => 'MAT-001',
            'quantity' => 5,
            'unit_price' => 150,
            'tax_rate' => 18
        ]);

        PurchaseOrderItem::create([
            'purchase_order_id' => $po->id,
            'description' => 'Demir 8mm',
            'sku' => 'MAT-002',
            'quantity' => 2,
            'unit_price' => 250,
            'tax_rate' => 18
        ]);
    }
}
