<?php
// Script to create subcontractor_quotes table if it doesn't exist (boots Laravel)
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (Schema::hasTable('subcontractor_quotes')) {
    echo "Table subcontractor_quotes already exists\n";
    exit(0);
}

Schema::create('subcontractor_quotes', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('subcontractor_id');
    $table->string('requester_name')->nullable();
    $table->string('requester_email')->nullable();
    $table->string('requester_phone')->nullable();
    $table->text('message')->nullable();
    $table->string('status')->default('new');
    $table->timestamps();

    $table->foreign('subcontractor_id')->references('id')->on('subcontractors')->onDelete('cascade');
});

echo "Created subcontractor_quotes table\n";
