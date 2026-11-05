<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class VoucherCreateTest extends TestCase
{
    public function test_can_create_collection_voucher()
    {
        // create minimal tables needed for voucher creation
        if (!Schema::hasTable('vouchers')) {
            Schema::create('vouchers', function (Blueprint $table) {
                $table->id();
                $table->string('type');
                $table->date('date');
                $table->string('account')->nullable();
                $table->string('counterparty')->nullable();
                $table->decimal('amount', 15, 2)->default(0);
                $table->string('payment_method')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('voucher_lines')) {
            Schema::create('voucher_lines', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('voucher_id');
                $table->string('account')->nullable();
                $table->decimal('debit', 15, 2)->default(0);
                $table->decimal('credit', 15, 2)->default(0);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        $payload = [
            'type' => 'collection',
            'date' => now()->toDateString(),
            'account' => 'Kasa 1',
            'counterparty' => 'Acme A.Ş.',
            'amount' => 150.50,
            'payment_method' => 'cash',
            'notes' => 'Unit test tahsilat',
        ];

        $res = $this->postJson(route('finance.accounts.vouchers.store'), $payload);
        $res->assertStatus(200)->assertJson(['ok' => true]);
        $this->assertDatabaseHas('vouchers', ['counterparty' => 'Acme A.Ş.']);
    }
}
