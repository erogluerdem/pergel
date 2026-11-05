<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('number')->nullable()->index();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->string('company')->nullable();
            $table->string('type')->nullable();
            $table->decimal('value', 18, 2)->default(0);
            $table->decimal('paid', 18, 2)->default(0);
            $table->string('status')->nullable()->index();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
