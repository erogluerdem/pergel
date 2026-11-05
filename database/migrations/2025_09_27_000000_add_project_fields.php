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
        // Add each column only if it does not already exist. This makes the migration safe
        // to run on databases that may already have some of these fields (avoids
        // "Duplicate column" errors).
        if (!Schema::hasColumn('projects', 'project_code')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('project_code')->nullable();
            });
        }

        if (!Schema::hasColumn('projects', 'contract_number')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('contract_number')->nullable();
            });
        }

        if (!Schema::hasColumn('projects', 'client_name')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('client_name')->nullable();
            });
        }

        if (!Schema::hasColumn('projects', 'contractor_name')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('contractor_name')->nullable();
            });
        }

        if (!Schema::hasColumn('projects', 'tax_number')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('tax_number')->nullable();
            });
        }

        if (!Schema::hasColumn('projects', 'contact_phone')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('contact_phone')->nullable();
            });
        }

        if (!Schema::hasColumn('projects', 'program_id')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->unsignedBigInteger('program_id')->nullable()->index();
            });
        }

        if (!Schema::hasColumn('projects', 'type')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('type')->nullable();
            });
        }

        if (!Schema::hasColumn('projects', 'project_manager')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('project_manager')->nullable();
            });
        }

        if (!Schema::hasColumn('projects', 'region')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('region')->nullable();
            });
        }

        if (!Schema::hasColumn('projects', 'progress_percent')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->integer('progress_percent')->default(0);
            });
        }

        if (!Schema::hasColumn('projects', 'spent')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->decimal('spent', 15, 2)->nullable();
            });
        }

        if (!Schema::hasColumn('projects', 'health_status')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('health_status')->nullable();
            });
        }

        if (!Schema::hasColumn('projects', 'attachments')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->json('attachments')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop only the columns that exist on the table to avoid errors when
        // rolling back in environments that may already have some of them.
        $columnsToDrop = [];

        $maybe = [
            'project_code',
            'contract_number',
            'client_name',
            'contractor_name',
            'tax_number',
            'contact_phone',
            'program_id',
            'type',
            'project_manager',
            'region',
            'progress_percent',
            'spent',
            'health_status',
            'attachments',
        ];

        foreach ($maybe as $col) {
            if (Schema::hasColumn('projects', $col)) {
                $columnsToDrop[] = $col;
            }
        }

        if (!empty($columnsToDrop)) {
            Schema::table('projects', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
    }
};
