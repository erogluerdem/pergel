<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('customer')->nullable();
            $table->decimal('estimated_value', 20, 2)->default(0);
            $table->string('stage')->default('lead'); // lead, qualification, proposal, negotiation, won, lost
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('source')->nullable();
            $table->integer('probability')->nullable();
            $table->date('expected_close_date')->nullable();
            $table->date('next_action_date')->nullable();
            $table->date('last_contacted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('opportunities');
    }
};
