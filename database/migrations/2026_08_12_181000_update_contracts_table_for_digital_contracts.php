<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('contracts', 'title')) {
                $table->string('title')->nullable()->after('id');
            }
            if (!Schema::hasColumn('contracts', 'content')) {
                $table->longText('content')->nullable()->after('title');
            }
            if (!Schema::hasColumn('contracts', 'template_id')) {
                $table->unsignedBigInteger('template_id')->nullable()->after('content');
            }
            if (!Schema::hasColumn('contracts', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('template_id');
            }
            if (!Schema::hasColumn('contracts', 'effective_date')) {
                $table->date('effective_date')->nullable()->after('created_by');
            }
            if (!Schema::hasColumn('contracts', 'expiration_date')) {
                $table->date('expiration_date')->nullable()->after('effective_date');
            }
            if (!Schema::hasColumn('contracts', 'signed_at')) {
                $table->timestamp('signed_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('contracts', 'signed_by')) {
                $table->string('signed_by')->nullable()->after('signed_at');
            }
            if (!Schema::hasColumn('contracts', 'signature_data')) {
                $table->json('signature_data')->nullable()->after('signed_by');
            }
            if (!Schema::hasColumn('contracts', 'pdf_path')) {
                $table->string('pdf_path')->nullable()->after('signature_data');
            }
        });

        Schema::table('contract_signatures', function (Blueprint $table) {
            if (!Schema::hasColumn('contract_signatures', 'signer_name')) {
                $table->string('signer_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('contract_signatures', 'signer_email')) {
                $table->string('signer_email')->nullable()->after('signer_name');
            }
            if (!Schema::hasColumn('contract_signatures', 'signer_role')) {
                $table->string('signer_role')->nullable()->after('signer_email');
            }
            if (!Schema::hasColumn('contract_signatures', 'token')) {
                $table->string('token', 100)->nullable()->after('signer_role');
            }
            if (Schema::hasColumn('contract_signatures', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn(['title', 'content', 'template_id', 'created_by', 'effective_date', 'expiration_date', 'signed_at', 'signed_by', 'signature_data', 'pdf_path']);
        });

        Schema::table('contract_signatures', function (Blueprint $table) {
            $table->dropColumn(['signer_name', 'signer_email', 'signer_role', 'token']);
        });
    }
};
