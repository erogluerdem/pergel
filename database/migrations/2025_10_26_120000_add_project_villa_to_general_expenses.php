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
        if (!Schema::hasTable('general_expenses')) {
            return; // Table doesn't exist yet, skip
        }

        Schema::table('general_expenses', function (Blueprint $table) {
            // Add project_id column if it doesn't already exist
            if (!Schema::hasColumn('general_expenses', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable()->after('category');
                // Only add foreign key if projects table exists
                if (Schema::hasTable('projects')) {
                    $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
                }
            }

            // Add villa_id column if it doesn't already exist
            if (!Schema::hasColumn('general_expenses', 'villa_id')) {
                $table->unsignedBigInteger('villa_id')->nullable()->after('project_id');
                // Only add foreign key if villas table exists
                if (Schema::hasTable('villas')) {
                    $table->foreign('villa_id')->references('id')->on('villas')->onDelete('set null');
                }
            }

            // Add vendor_id column if it doesn't already exist
            if (!Schema::hasColumn('general_expenses', 'vendor_id')) {
                $table->unsignedBigInteger('vendor_id')->nullable()->after('vendor_name');
                // Only add foreign key if vendors table exists
                if (Schema::hasTable('vendors')) {
                    $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('general_expenses')) {
            return;
        }

        Schema::table('general_expenses', function (Blueprint $table) {
            // Drop foreign keys first if they exist
            $indexesToCheck = [
                'general_expenses_project_id_foreign',
                'general_expenses_villa_id_foreign',
                'general_expenses_vendor_id_foreign'
            ];

            foreach ($indexesToCheck as $index) {
                try {
                    if (Schema::hasTable('general_expenses')) {
                        $table->dropForeignIdIfExists($index);
                    }
                } catch (\Exception $e) {
                    // Ignore if foreign key doesn't exist
                }
            }

            if (Schema::hasColumn('general_expenses', 'project_id')) {
                $table->dropColumn('project_id');
            }

            if (Schema::hasColumn('general_expenses', 'villa_id')) {
                $table->dropColumn('villa_id');
            }

            if (Schema::hasColumn('general_expenses', 'vendor_id')) {
                $table->dropColumn('vendor_id');
            }
        });
    }
};
