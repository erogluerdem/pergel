<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {


        // Taşeron Avans Talepleri
        Schema::create('subcontractor_advance_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subcontractor_id');
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('TRY');
            $table->text('reason');
            $table->date('requested_date');
            $table->string('status')->default('bekliyor'); // onaylandi, red
            $table->timestamps();
        });

        // Taşeron İhale Teklifleri
        Schema::create('subcontractor_bids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subcontractor_id');
            $table->unsignedBigInteger('tender_id');
            $table->decimal('bid_amount', 15, 2);
            $table->string('currency')->default('TRY');
            $table->text('notes')->nullable();
            $table->string('status')->default('degerlendirmede'); // kazandi, elendi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcontractor_bids');
        Schema::dropIfExists('subcontractor_advance_requests');
    }
};
