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
        Schema::create('subcontractors', function (Blueprint $table) {
            $table->id();
            $table->string('company_name'); // Firma adı
            $table->text('address')->nullable(); // Adres
            $table->string('tax_number')->nullable(); // Vergi numarası
            $table->string('phone')->nullable(); // Telefon
            $table->string('email')->nullable(); // E-posta
            $table->string('contact_person')->nullable(); // Ana kontak kişisi
            $table->string('expertise_area'); // Uzmanlık alanı (örn: Kaba İnşaat, Mekanik)
            $table->decimal('performance_score', 3, 2)->default(0); // Performans puanı (0-5)
            $table->decimal('safety_score', 5, 2)->default(0); // İSG skoru (%)
            $table->enum('status', ['pending', 'approved', 'conditional', 'blacklisted'])->default('pending'); // Durum
            $table->integer('active_projects')->default(0); // Aktif projeler sayısı
            $table->json('documents')->nullable(); // Dokümanlar (JSON array)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcontractors');
    }
};
