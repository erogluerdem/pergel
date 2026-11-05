<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('subject')->comment('Görüşme konusu');
            $table->text('notes')->nullable()->comment('Görüşme notları');
            $table->dateTime('meeting_date')->comment('Görüşme tarihi ve saati');
            $table->string('location')->nullable()->comment('Görüşme yeri');
            $table->enum('outcome', ['interested', 'not_interested', 'pending', 'converted_to_sale'])->default('pending')->comment('Görüşme sonucu');
            $table->decimal('potential_sale_amount', 15, 2)->nullable()->comment('Potansiyel satış tutarı');
            $table->string('next_followup')->nullable()->comment('Sonraki takip');
            $table->dateTime('next_followup_date')->nullable()->comment('Sonraki takip tarihi');
            $table->enum('meeting_type', ['call', 'visit', 'email', 'online'])->default('call')->comment('Görüşme türü');
            $table->timestamps();
            $table->softDeletes();

            $table->index('customer_id');
            $table->index('user_id');
            $table->index('meeting_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_meetings');
    }
};
