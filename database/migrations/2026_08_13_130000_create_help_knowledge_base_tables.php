<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('help_categories')) {
            Schema::create('help_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('icon')->default('ri-folder-open-line');
                $table->string('color')->default('primary');
                $table->integer('sort_order')->default(0);
                $table->string('remote_id')->nullable()->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('help_articles')) {
            Schema::create('help_articles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('help_category_id')->nullable()->constrained('help_categories')->onDelete('set null');
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('summary')->nullable();
                $table->longText('content')->nullable();
                $table->integer('views')->default(0);
                $table->integer('read_time')->default(3);
                $table->boolean('is_popular')->default(false);
                $table->json('tags')->nullable();
                $table->string('author')->default('Merkez Destek Ekibi');
                $table->string('remote_id')->nullable()->index();
                $table->timestamp('last_synced_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('help_faqs')) {
            Schema::create('help_faqs', function (Blueprint $table) {
                $table->id();
                $table->string('category_name')->default('Genel Sorular');
                $table->string('question');
                $table->text('answer');
                $table->integer('sort_order')->default(0);
                $table->string('remote_id')->nullable()->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('help_video_tutorials')) {
            Schema::create('help_video_tutorials', function (Blueprint $table) {
                $table->id();
                $table->string('category_name')->default('Şantiye & Saha');
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('duration')->default('04:30');
                $table->string('video_url')->nullable();
                $table->string('remote_id')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('help_video_tutorials');
        Schema::dropIfExists('help_faqs');
        Schema::dropIfExists('help_articles');
        Schema::dropIfExists('help_categories');
    }
};
