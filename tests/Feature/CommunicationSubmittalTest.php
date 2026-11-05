<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Project;
use App\Models\Submittal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class CommunicationSubmittalTest extends TestCase
{
    use RefreshDatabase;

    public function test_submittal_upload_and_signed_download()
    {
        Storage::fake('local');
        $user = User::factory()->create(['email'=>'submitter@example.com']);
        $project = Project::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('doc.pdf', 100);
        $resp = $this->post(route('projects.communication.submittals.store',['project'=>$project->id]), [
            'description' => 'Test Submittal',
            'reviewers' => 'rev@example.com',
            'attachments' => [$file]
        ]);
        $resp->assertRedirect();

        $sub = Submittal::first();
        $this->assertNotNull($sub);
        $this->assertNotEmpty($sub->attachments);

        // request signed url and attempt download
        $url = $sub->signedAttachmentUrl($sub->attachments[0], 60);
        $download = $this->get($url);
        $download->assertStatus(200);
    }

    public function test_submittal_review_notifies_submitter()
    {
        $submitter = User::factory()->create(['email'=>'submitter2@example.com']);
        $reviewer = User::factory()->create(['email'=>'rev2@example.com']);
        $project = Project::factory()->create();
        $this->actingAs($submitter);

        $sub = Submittal::create(['project_id'=>$project->id,'submittal_no'=>'SUB-'.time(),'description'=>'please review','submitted_by'=>$submitter->email,'reviewers'=>[$reviewer->email]]);

        $this->actingAs($reviewer);
        $resp = $this->post(route('projects.communication.submittals.review',['project'=>$project->id,'submittal'=>$sub->id]), ['action'=>'approved','comments'=>'ok']);
        $resp->assertStatus(302);

        $sub->refresh();
        $this->assertEquals('approved', $sub->status);
    }
}
