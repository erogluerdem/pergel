<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderInvitationsTable extends Migration
{
    public function up()
    {
        Schema::create('tender_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_package_id');
            $table->string('bidder_email');
            $table->string('token')->unique();
            $table->timestamp('used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('tender_package_id')->references('id')->on('tender_packages')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tender_invitations');
    }
}
