<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderBidItemsTable extends Migration
{
    public function up()
    {
        Schema::create('tender_bid_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_bid_id');
            $table->string('poz_no')->nullable();
            $table->text('description')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('quantity', 14, 4)->nullable();
            $table->decimal('unit_price', 18, 2)->nullable();
            $table->decimal('total_price', 18, 2)->nullable();
            $table->timestamps();

            $table->foreign('tender_bid_id')->references('id')->on('tender_bids')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tender_bid_items');
    }
}
