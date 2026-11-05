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
        Schema::create('equipment_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_id');
            $table->string('type'); // Tip: Ruhsat, Sigorta, Kullanım Kılavuzu, vb.
            $table->string('title'); // Başlık
            $table->string('file_path'); // Dosya yolu
            $table->string('file_name'); // Orijinal dosya adı
            $table->string('mime_type')->nullable(); // MIME tipi
            $table->integer('file_size')->nullable(); // Dosya boyutu (bytes)
            $table->date('issue_date')->nullable(); // Düzenleme tarihi
            $table->date('expiry_date')->nullable(); // Geçerlilik sonu
            $table->string('issued_by')->nullable(); // Düzenleyen kurum
            $table->text('notes')->nullable(); // Notlar
            $table->timestamps();

            $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('cascade');
            $table->index(['equipment_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_documents');
    }
};
