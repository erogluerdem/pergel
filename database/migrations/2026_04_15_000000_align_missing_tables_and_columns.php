<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. planning_tasks missing columns
        if (Schema::hasTable('planning_tasks')) {
            Schema::table('planning_tasks', function (Blueprint $table) {
                if (!Schema::hasColumn('planning_tasks', 'dependencies')) {
                    $table->json('dependencies')->nullable();
                }
                if (!Schema::hasColumn('planning_tasks', 'is_milestone')) {
                    $table->boolean('is_milestone')->default(false);
                }
            });
        }

        // 2. budget_allocations table
        if (!Schema::hasTable('budget_allocations')) {
            Schema::create('budget_allocations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('project_id')->nullable();
                $table->string('category')->nullable();
                $table->decimal('allocated_amount', 15, 2)->default(0);
                $table->decimal('spent_amount', 15, 2)->default(0);
                $table->decimal('committed_amount', 15, 2)->default(0);
                $table->decimal('remaining_amount', 15, 2)->default(0);
                $table->integer('fiscal_year')->nullable();
                $table->integer('quarter')->nullable();
                $table->string('status')->default('active');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('budget_allocations', function (Blueprint $table) {
                if (!Schema::hasColumn('budget_allocations', 'committed_amount')) {
                    $table->decimal('committed_amount', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('budget_allocations', 'remaining_amount')) {
                    $table->decimal('remaining_amount', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('budget_allocations', 'quarter')) {
                    $table->integer('quarter')->nullable();
                }
                if (!Schema::hasColumn('budget_allocations', 'status')) {
                    $table->string('status')->default('active');
                }
                if (!Schema::hasColumn('budget_allocations', 'approved_by')) {
                    $table->unsignedBigInteger('approved_by')->nullable();
                }
                if (!Schema::hasColumn('budget_allocations', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable();
                }
                if (!Schema::hasColumn('budget_allocations', 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable();
                }
                if (!Schema::hasColumn('budget_allocations', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        // 3. purchase_requests table
        if (!Schema::hasTable('purchase_requests')) {
            Schema::create('purchase_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('project_id')->nullable();
                $table->unsignedBigInteger('requester_id')->nullable();
                $table->string('request_number')->nullable();
                $table->string('item_name')->nullable();
                $table->text('description')->nullable();
                $table->decimal('quantity', 15, 2)->default(1);
                $table->string('unit')->nullable();
                $table->decimal('estimated_cost', 15, 2)->default(0);
                $table->string('status')->default('pending');
                $table->string('priority')->default('normal');
                $table->date('needed_by')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 4. users missing columns
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable();
                }
                if (!Schema::hasColumn('users', 'phone')) {
                    $table->string('phone')->nullable();
                }
                if (!Schema::hasColumn('users', 'title')) {
                    $table->string('title')->nullable();
                }
                if (!Schema::hasColumn('users', 'department')) {
                    $table->string('department')->nullable();
                }
                if (!Schema::hasColumn('users', 'bio')) {
                    $table->text('bio')->nullable();
                }
                if (!Schema::hasColumn('users', 'language')) {
                    $table->string('language')->default('tr');
                }
                if (!Schema::hasColumn('users', 'timezone')) {
                    $table->string('timezone')->default('Europe/Istanbul');
                }
                if (!Schema::hasColumn('users', 'two_factor_enabled')) {
                    $table->boolean('two_factor_enabled')->default(false);
                }
                if (!Schema::hasColumn('users', 'last_login_at')) {
                    $table->timestamp('last_login_at')->nullable();
                }
                if (!Schema::hasColumn('users', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
                if (!Schema::hasColumn('users', 'theme')) {
                    $table->string('theme')->nullable()->default('santiye');
                }
                if (!Schema::hasColumn('users', 'dark_mode')) {
                    $table->boolean('dark_mode')->default(false);
                }
            });
        }

        // 5. inventory_items missing columns
        if (Schema::hasTable('inventory_items')) {
            Schema::table('inventory_items', function (Blueprint $table) {
                if (!Schema::hasColumn('inventory_items', 'name')) {
                    $table->string('name')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'title')) {
                    $table->string('title')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'sku')) {
                    $table->string('sku')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'code')) {
                    $table->string('code')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'description')) {
                    $table->text('description')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'category')) {
                    $table->string('category')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'price')) {
                    $table->decimal('price', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('inventory_items', 'unit_cost')) {
                    $table->decimal('unit_cost', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('inventory_items', 'unit_price')) {
                    $table->decimal('unit_price', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('inventory_items', 'quantity')) {
                    $table->integer('quantity')->default(0);
                }
                if (!Schema::hasColumn('inventory_items', 'stock')) {
                    $table->integer('stock')->default(0);
                }
                if (!Schema::hasColumn('inventory_items', 'min_quantity')) {
                    $table->integer('min_quantity')->default(0);
                }
                if (!Schema::hasColumn('inventory_items', 'unit')) {
                    $table->string('unit')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'supplier')) {
                    $table->string('supplier')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'location')) {
                    $table->string('location')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'status')) {
                    $table->string('status')->default('active');
                }
                if (!Schema::hasColumn('inventory_items', 'images')) {
                    $table->json('images')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'last_restocked')) {
                    $table->timestamp('last_restocked')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'updated_by')) {
                    $table->unsignedBigInteger('updated_by')->nullable();
                }
            });
        }

        // 6. property_documents missing columns
        if (Schema::hasTable('property_documents')) {
            Schema::table('property_documents', function (Blueprint $table) {
                if (!Schema::hasColumn('property_documents', 'document_name')) {
                    $table->string('document_name')->nullable();
                }
                if (!Schema::hasColumn('property_documents', 'file_type')) {
                    $table->string('file_type')->nullable();
                }
                if (!Schema::hasColumn('property_documents', 'file_size')) {
                    $table->bigInteger('file_size')->nullable();
                }
                if (!Schema::hasColumn('property_documents', 'document_category')) {
                    $table->string('document_category')->nullable();
                }
                if (!Schema::hasColumn('property_documents', 'document_date')) {
                    $table->date('document_date')->nullable();
                }
                if (!Schema::hasColumn('property_documents', 'validity_date')) {
                    $table->date('validity_date')->nullable();
                }
            });
        }

        // 7. invoices missing columns
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('invoices', 'vendor_id')) {
                    $table->unsignedBigInteger('vendor_id')->nullable();
                }
                if (!Schema::hasColumn('invoices', 'project_id')) {
                    $table->unsignedBigInteger('project_id')->nullable();
                }
                if (!Schema::hasColumn('invoices', 'consultant_id')) {
                    $table->unsignedBigInteger('consultant_id')->nullable();
                }
                if (!Schema::hasColumn('invoices', 'currency_id')) {
                    $table->unsignedBigInteger('currency_id')->nullable();
                }
                if (!Schema::hasColumn('invoices', 'invoice_date')) {
                    $table->date('invoice_date')->nullable();
                }
                if (!Schema::hasColumn('invoices', 'payment_status')) {
                    $table->string('payment_status')->nullable()->default('pending');
                }
            });
        }

        // 8. subcontractors missing columns
        if (Schema::hasTable('subcontractors')) {
            Schema::table('subcontractors', function (Blueprint $table) {
                if (!Schema::hasColumn('subcontractors', 'logo')) {
                    $table->string('logo')->nullable();
                }
            });
        }

        // 9. customers missing columns
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                if (!Schema::hasColumn('customers', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        // 10. installments missing columns
        if (Schema::hasTable('installments')) {
            Schema::table('installments', function (Blueprint $table) {
                if (!Schema::hasColumn('installments', 'invoice_id')) {
                    $table->unsignedBigInteger('invoice_id')->nullable();
                }
            });
        }

        // 11. permits missing columns
        if (Schema::hasTable('permits')) {
            Schema::table('permits', function (Blueprint $table) {
                if (!Schema::hasColumn('permits', 'type')) {
                    $table->string('type')->nullable();
                }
                if (!Schema::hasColumn('permits', 'permit_type')) {
                    $table->string('permit_type')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
        Schema::dropIfExists('budget_allocations');
    }
};
