<?php

namespace Tests\Feature;

use Tests\TestCase;
// Not using RefreshDatabase to avoid running all migrations during test run
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\User;
use App\Models\IncidentReport;

class IsgIncidentUploadTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create minimal incident_reports table if it doesn't exist to avoid migrating the entire project
        if (!Schema::hasTable('incident_reports')) {
            Schema::create('incident_reports', function (Blueprint $table) {
                $table->id();
                $table->string('type');
                $table->text('description')->nullable();
                $table->timestamp('occurred_at')->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->string('photo_path')->nullable();
                $table->unsignedBigInteger('reported_by')->nullable();
                $table->string('status')->default('open');
                $table->text('root_cause')->nullable();
                $table->text('corrective_action')->nullable();
                $table->timestamps();
            });
        }
    }

    public function test_incident_submission_stores_photo_and_db_record()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user);

        $file = UploadedFile::fake()->image('evidence.jpg');

        $response = $this->post(route('field.qhse.safety.incident.store'), [
            'type' => 'ramak_kala',
            'description' => 'Test upload',
            'latitude' => '41.0',
            'longitude' => '29.0',
            'photo' => $file,
        ]);

        $response->assertRedirect(route('field.qhse.safety.dashboard'));

        // Assert the file was stored
        Storage::disk('public')->assertExists('isg/photos/'.$file->hashName());

        // Assert DB has record with photo_path
        $this->assertDatabaseHas('incident_reports', [
            'type' => 'ramak_kala',
            'description' => 'Test upload'
        ]);

        $incident = IncidentReport::first();
        $this->assertNotNull($incident->photo_path);
        Storage::disk('public')->assertExists($incident->photo_path);
    }
}
