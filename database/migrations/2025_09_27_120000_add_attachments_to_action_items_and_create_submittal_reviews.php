<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachmentsToActionItemsAndCreateSubmittalReviews extends Migration
{
    public function up()
    {
        if (Schema::hasTable('action_items') && !Schema::hasColumn('action_items', 'attachments')) {
            Schema::table('action_items', function (Blueprint $table) {
                $table->json('attachments')->nullable()->after('notes');
            });
        }

        if (!Schema::hasTable('submittal_reviews')) {
            Schema::create('submittal_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('submittal_id')->constrained('submittals')->onDelete('cascade');
                $table->string('reviewer_email')->nullable();
                $table->enum('action', ['approved', 'rejected']);
                $table->text('comments')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('submittal_reviews')) Schema::dropIfExists('submittal_reviews');
        if (Schema::hasTable('action_items') && Schema::hasColumn('action_items', 'attachments')) {
            Schema::table('action_items', function (Blueprint $table) {
                $table->dropColumn('attachments');
            });
        }
    }
}
