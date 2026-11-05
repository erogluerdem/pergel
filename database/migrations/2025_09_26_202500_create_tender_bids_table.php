<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tender_bids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_package_id');
            $table->string('bidder_email');
            $table->decimal('price', 14, 2)->nullable();
            $table->text('notes')->nullable();
            $table->json('documents')->nullable();
            $table->timestamps();

            $table->foreign('tender_package_id')->references('id')->on('tender_packages')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tender_bids');
    }
};
