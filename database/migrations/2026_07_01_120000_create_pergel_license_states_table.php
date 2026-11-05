<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pergel_license_states', function (Blueprint $table) {
            $table->id();
            $table->string('installation_id', 64)->unique();
            $table->string('license_key')->nullable();
            $table->string('tier')->default('community');
            $table->json('modules')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('customer')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->string('renew_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pergel_license_states');
    }
};
