<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\PlanningTask;

class PlanningDependencyTest extends TestCase
{
    // note: avoid RefreshDatabase in this workspace; tests create isolated data

    public function test_can_add_dependency_successfully()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        // make user project manager to pass ProjectPolicy->view
        $project->project_manager = $user->id; $project->save();

        $t1 = PlanningTask::factory()->create(['project_id' => $project->id]);
        $t2 = PlanningTask::factory()->create(['project_id' => $project->id]);

        $this->actingAs($user)->putJson(route('projects.planning.tasks.update', ['project' => $project->id, 'task' => $t1->id]), [
            'dependencies' => [$t2->id]
        ])->assertStatus(200)->assertJsonFragment(['id' => $t1->id]);

        $fresh = PlanningTask::find($t1->id);
        $this->assertIsArray($fresh->dependencies);
        $this->assertContains($t2->id, array_map('intval', $fresh->dependencies ?? []));
    }

    public function test_server_rejects_circular_dependency()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $project->project_manager = $user->id; $project->save();

        $a = PlanningTask::factory()->create(['project_id' => $project->id]);
        $b = PlanningTask::factory()->create(['project_id' => $project->id]);
        $c = PlanningTask::factory()->create(['project_id' => $project->id]);

        // a -> b, b -> c
        $a->dependencies = [$b->id]; $a->save();
        $b->dependencies = [$c->id]; $b->save();

        // now try to make c -> a which should produce a cycle
        $resp = $this->actingAs($user)->putJson(route('projects.planning.tasks.update', ['project' => $project->id, 'task' => $c->id]), [
            'dependencies' => [$a->id]
        ]);

        $resp->assertStatus(422);
        $this->assertStringContainsString('döngü', strtolower($resp->json('message') ?? ''), 'Expected cycle error message');
    }
}
