<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tender_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_package_id')->index();
            $table->unsignedBigInteger('tender_bid_id')->index();
            $table->unsignedBigInteger('tender_bid_item_id')->nullable()->index();
            $table->string('poz_no')->nullable()->index();
            $table->string('bidder_email')->nullable();
            $table->float('score')->nullable();
            $table->boolean('accepted')->default(true);
            $table->timestamps();

            $table->foreign('tender_package_id')->references('id')->on('tender_packages')->onDelete('cascade');
            $table->foreign('tender_bid_id')->references('id')->on('tender_bids')->onDelete('cascade');
            $table->foreign('tender_bid_item_id')->references('id')->on('tender_bid_items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tender_mappings');
    }
};
