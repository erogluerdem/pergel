<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class VoucherValidationTest extends TestCase
{
    public function test_validation_requires_amount_and_account()
    {
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

        $payload = [
            'type' => 'collection',
            'date' => now()->toDateString(),
            // missing account and amount
        ];

        $res = $this->postJson(route('finance.accounts.vouchers.store'), $payload);
        $res->assertStatus(422);
    }
}
