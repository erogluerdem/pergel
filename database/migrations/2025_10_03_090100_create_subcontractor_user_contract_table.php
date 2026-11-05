<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcontractor_user_contract', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcontractor_user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subcontractor_contract_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['subcontractor_user_id', 'subcontractor_contract_id'], 'subcontractor_user_contract_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcontractor_user_contract');
    }
};
