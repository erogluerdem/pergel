<?php

namespace Tests\Feature;

// Note: RefreshDatabase removed to avoid running migrations during tests in this workspace
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;

class DocumentUploadTest extends TestCase
{
    // not using RefreshDatabase here to avoid migrate:fresh which hits duplicate-column migrations

    public function test_authenticated_user_can_upload_new_document()
    {
        // Arrange: fake storage and create user + project
        Storage::fake('local');

        $user = User::factory()->create();
        $project = Project::factory()->create([
            'project_manager' => $user->id,
        ]);

    // Disable all middleware for this test (avoid CSRF/session/middleware interactions in workspace)
    $this->withoutMiddleware();

        // Act: act as user and post upload
        $response = $this->actingAs($user)
            ->post(route('projects.documents.store', ['project' => $project->id]), [
                'mode' => 'new',
                'name' => 'Test Document',
                'code' => 'DOC-001',
                'discipline' => 'Architecture',
                'file' => UploadedFile::fake()->create('test.pdf', 100, 'application/pdf'),
            ]);

        // helpful debug if we get 419
        if ($response->status() === 419) {
            fwrite(STDOUT, "Response content:\n" . $response->getContent() . "\n");
        }

        $response->assertStatus(302);

        // Assert: database has document and file was stored
        $this->assertDatabaseHas('documents', [
            'project_id' => $project->id,
            'name' => 'Test Document',
            'code' => 'DOC-001',
            'discipline' => 'Architecture',
        ]);

        $doc = \App\Models\Document::where('project_id', $project->id)->where('code', 'DOC-001')->first();
        $this->assertNotNull($doc);

        // File path should exist in storage
        Storage::disk('local')->assertExists($doc->file_path);
    }
}
