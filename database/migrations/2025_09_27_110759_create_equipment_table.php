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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ekipman adı
            $table->string('type'); // Tip: Ekskavatör, Vinç, vb.
            $table->string('plate_number')->nullable(); // Plaka
            $table->string('serial_number')->nullable(); // Seri No
            $table->enum('status', ['Çalışıyor', 'Boşta', 'Bakımda', 'Arızalı'])->default('Boşta'); // Durum
            $table->unsignedBigInteger('current_project_id')->nullable(); // Bulunduğu proje
            $table->string('operator')->nullable(); // Operatör
            $table->date('next_maintenance_date')->nullable(); // Sonraki bakım tarihi
            $table->string('brand')->nullable(); // Marka
            $table->string('model')->nullable(); // Model
            $table->year('year')->nullable(); // Yıl
            $table->decimal('latitude', 10, 7)->nullable(); // GPS enlem
            $table->decimal('longitude', 10, 7)->nullable(); // GPS boylam
            $table->integer('working_hours')->default(0); // Toplam çalışma saati
            $table->integer('fuel_level')->nullable(); // Yakıt seviyesi (%)
            $table->enum('ownership', ['Sahip', 'Kiralık'])->default('Sahip'); // Sahip/Kiralık
            $table->date('purchase_date')->nullable(); // Satın alma tarihi
            $table->decimal('purchase_cost', 15, 2)->nullable(); // Satın alma maliyeti
            $table->text('notes')->nullable(); // Notlar
            $table->timestamps();

            $table->foreign('current_project_id')->references('id')->on('projects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
