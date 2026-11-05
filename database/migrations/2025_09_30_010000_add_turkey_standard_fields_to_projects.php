<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'building_permit_number')) {
                $table->string('building_permit_number')->nullable()->after('contract_number');
            }
            if (!Schema::hasColumn('projects', 'permit_issue_date')) {
                $table->date('permit_issue_date')->nullable()->after('building_permit_number');
            }

            if (!Schema::hasColumn('projects', 'tapu_pafta')) {
                $table->string('tapu_pafta')->nullable()->after('permit_issue_date');
            }
            if (!Schema::hasColumn('projects', 'tapu_ada')) {
                $table->string('tapu_ada')->nullable()->after('tapu_pafta');
            }
            if (!Schema::hasColumn('projects', 'tapu_parsel')) {
                $table->string('tapu_parsel')->nullable()->after('tapu_ada');
            }
            if (!Schema::hasColumn('projects', 'tapu_volume')) {
                $table->string('tapu_volume')->nullable()->after('tapu_parsel');
            }
            if (!Schema::hasColumn('projects', 'land_registry_reference')) {
                $table->string('land_registry_reference')->nullable()->after('tapu_volume');
            }
            if (!Schema::hasColumn('projects', 'cadde_sokak')) {
                $table->string('cadde_sokak')->nullable()->after('land_registry_reference');
            }
            if (!Schema::hasColumn('projects', 'province')) {
                $table->string('province')->nullable()->after('cadde_sokak');
            }
            if (!Schema::hasColumn('projects', 'district')) {
                $table->string('district')->nullable()->after('province');
            }

            if (!Schema::hasColumn('projects', 'gross_area')) {
                $table->decimal('gross_area', 12, 2)->nullable()->after('attachments');
            }
            if (!Schema::hasColumn('projects', 'net_area')) {
                $table->decimal('net_area', 12, 2)->nullable()->after('gross_area');
            }
            if (!Schema::hasColumn('projects', 'floor_count')) {
                $table->integer('floor_count')->nullable()->after('net_area');
            }

            if (!Schema::hasColumn('projects', 'building_usage')) {
                $table->string('building_usage')->nullable()->after('floor_count');
            }
            if (!Schema::hasColumn('projects', 'structure_system')) {
                $table->string('structure_system')->nullable()->after('building_usage');
            }

            if (!Schema::hasColumn('projects', 'contractor_vkn')) {
                $table->string('contractor_vkn')->nullable()->after('contractor_name');
            }
            if (!Schema::hasColumn('projects', 'contractor_license_number')) {
                $table->string('contractor_license_number')->nullable()->after('contractor_vkn');
            }

            if (!Schema::hasColumn('projects', 'responsible_engineer_name')) {
                $table->string('responsible_engineer_name')->nullable()->after('project_manager');
            }
            if (!Schema::hasColumn('projects', 'responsible_engineer_registration')) {
                $table->string('responsible_engineer_registration')->nullable()->after('responsible_engineer_name');
            }
            if (!Schema::hasColumn('projects', 'responsible_architect_name')) {
                $table->string('responsible_architect_name')->nullable()->after('responsible_engineer_registration');
            }
            if (!Schema::hasColumn('projects', 'responsible_architect_registration')) {
                $table->string('responsible_architect_registration')->nullable()->after('responsible_architect_name');
            }
            if (!Schema::hasColumn('projects', 'employer_name')) {
                $table->string('employer_name')->nullable()->after('responsible_architect_registration');
            }

            if (!Schema::hasColumn('projects', 'insurance_policy_number')) {
                $table->string('insurance_policy_number')->nullable()->after('contractor_license_number');
            }
            if (!Schema::hasColumn('projects', 'insurance_policy_provider')) {
                $table->string('insurance_policy_provider')->nullable()->after('insurance_policy_number');
            }
            if (!Schema::hasColumn('projects', 'performance_bond_number')) {
                $table->string('performance_bond_number')->nullable()->after('insurance_policy_provider');
            }
            if (!Schema::hasColumn('projects', 'financing_source')) {
                $table->string('financing_source')->nullable()->after('performance_bond_number');
            }
            if (!Schema::hasColumn('projects', 'contract_value')) {
                $table->decimal('contract_value', 15, 2)->nullable()->after('financing_source');
            }

            if (!Schema::hasColumn('projects', 'municipality')) {
                $table->string('municipality')->nullable()->after('region');
            }
            if (!Schema::hasColumn('projects', 'zoning_plan_status')) {
                $table->string('zoning_plan_status')->nullable()->after('municipality');
            }
            if (!Schema::hasColumn('projects', 'environmental_permit_number')) {
                $table->string('environmental_permit_number')->nullable()->after('zoning_plan_status');
            }
            if (!Schema::hasColumn('projects', 'risk_classification')) {
                $table->string('risk_classification')->nullable()->after('environmental_permit_number');
            }

            if (!Schema::hasColumn('projects', 'municipality_project_id')) {
                $table->string('municipality_project_id')->nullable()->after('environmental_permit_number');
            }

            if (!Schema::hasColumn('projects', 'construction_license_expiry_date')) {
                $table->date('construction_license_expiry_date')->nullable()->after('municipality_project_id');
            }

            if (!Schema::hasColumn('projects', 'safety_plan_reference')) {
                $table->string('safety_plan_reference')->nullable()->after('construction_license_expiry_date');
            }

            if (!Schema::hasColumn('projects', 'bim_level')) {
                $table->string('bim_level')->nullable()->after('safety_plan_reference');
            }
            if (!Schema::hasColumn('projects', 'project_contact_person')) {
                $table->string('project_contact_person')->nullable()->after('bim_level');
            }
            if (!Schema::hasColumn('projects', 'project_contact_email')) {
                $table->string('project_contact_email')->nullable()->after('project_contact_person');
            }
            if (!Schema::hasColumn('projects', 'project_contact_phone')) {
                $table->string('project_contact_phone')->nullable()->after('project_contact_email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $cols = [
                'building_permit_number','permit_issue_date','tapu_pafta','tapu_ada','tapu_parsel','tapu_volume','land_registry_reference','cadde_sokak','province','district',
                'gross_area','net_area','floor_count','building_usage','structure_system',
                'contractor_vkn','contractor_license_number','responsible_engineer_name','responsible_engineer_registration','responsible_architect_name','responsible_architect_registration','employer_name',
                'insurance_policy_number','insurance_policy_provider','performance_bond_number','financing_source','contract_value',
                'municipality','zoning_plan_status','environmental_permit_number','risk_classification','municipality_project_id','construction_license_expiry_date','safety_plan_reference','bim_level',
                'project_contact_person','project_contact_email','project_contact_phone'
            ];

            foreach ($cols as $c) {
                if (Schema::hasColumn('projects', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
