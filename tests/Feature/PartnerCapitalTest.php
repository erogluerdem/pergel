<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PartnerCapitalTest extends TestCase
{
    public function test_partner_adds_foreign_currency_capital_and_account_is_created()
    {
        // Create minimal tables required for this test in the sqlite memory DB
        Schema::dropIfExists('account_transactions');
        Schema::dropIfExists('cash_bank_transactions');
        Schema::dropIfExists('capital_transactions');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('partners');

        Schema::create('partners', function ($table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->decimal('share', 8, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('capital_transactions', function ($table) {
            $table->bigIncrements('id');
            $table->foreignId('partner_id');
            $table->decimal('amount', 15, 2);
            $table->string('source')->nullable();
            $table->string('description')->nullable();
            $table->date('transaction_date')->nullable();
            $table->timestamps();
        });

        Schema::create('accounts', function ($table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type');
            $table->string('currency')->default('TRY');
            $table->decimal('balance', 18, 2)->default(0);
            $table->boolean('active')->default(true);
            $table->date('opening_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('cash_bank_transactions', function ($table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->decimal('amount', 15, 2);
            $table->string('description')->nullable();
            $table->date('transaction_date')->nullable();
            $table->string('direction');
            $table->unsignedBigInteger('capital_transaction_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->string('currency', 3)->default('TRY');
            $table->decimal('exchange_rate', 18, 6)->nullable();
            $table->decimal('amount_tl', 18, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('account_transactions', function ($table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id');
            $table->date('date')->nullable();
            $table->string('description')->nullable();
            $table->decimal('debit', 18, 2)->default(0);
            $table->decimal('credit', 18, 2)->default(0);
            $table->decimal('balance', 18, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        // insert a partner
        $id = DB::table('partners')->insertGetId(['name' => 'Test Partner', 'share' => 10]);

        // ensure no USD cash account exists
        $this->assertDatabaseMissing('accounts', ['currency' => 'USD', 'type' => 'cash']);

        // post a request to add capital (API route)
        // Call controller method directly to avoid routing setup in test
        $request = \Illuminate\Http\Request::create('/partners/' . $id . '/capital', 'POST', [
            'amount' => 100,
            'source' => 'cash',
            'transaction_date' => now()->toDateString(),
            'currency' => 'USD',
            'exchange_rate' => 27.5,
        ]);

    $controller = new \App\Http\Controllers\PartnerController();
    $response = $controller->addCapital($request, $id);

    // addCapital returns a JsonResponse on success
    $this->assertTrue($response instanceof \Illuminate\Http\JsonResponse);

        // account should be created
        $this->assertDatabaseHas('accounts', ['currency' => 'USD', 'type' => 'cash']);

        // cash_bank_transactions should have a record with currency USD
        $this->assertDatabaseHas('cash_bank_transactions', ['currency' => 'USD', 'amount' => 100]);
    }
}
