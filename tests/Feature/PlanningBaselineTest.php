<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\PlanningTask;
use Illuminate\Support\Facades\DB;

class PlanningBaselineTest extends TestCase
{
    public function test_create_and_list_baseline()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $project->project_manager = $user->id; $project->save();

        // create a couple of tasks
        PlanningTask::factory()->create(['project_id' => $project->id, 'name' => 'A']);
        PlanningTask::factory()->create(['project_id' => $project->id, 'name' => 'B']);

        $resp = $this->actingAs($user)->postJson(route('projects.planning.baselines.create', ['project' => $project->id]), ['name' => 'Test BL']);
        $resp->assertStatus(200)->assertJsonStructure(['id','message']);

        $list = $this->actingAs($user)->getJson(route('projects.planning.baselines.list', ['project' => $project->id]));
        $list->assertStatus(200);
        $this->assertNotEmpty($list->json());
    }

    public function test_compare_baseline_returns_diffs()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $project->project_manager = $user->id; $project->save();

        $t1 = PlanningTask::factory()->create(['project_id' => $project->id, 'name' => 'X', 'start_date' => '2025-01-01']);
        $t2 = PlanningTask::factory()->create(['project_id' => $project->id, 'name' => 'Y', 'start_date' => '2025-02-01']);

        // create baseline via API
        $resp = $this->actingAs($user)->postJson(route('projects.planning.baselines.create', ['project' => $project->id]), ['name' => 'BL1']);
        $resp->assertStatus(200);
        $bid = $resp->json('id');

        // change a task
        $t1->start_date = '2025-01-05'; $t1->save();

        $cmp = $this->actingAs($user)->getJson(route('projects.planning.baselines.compare', ['project' => $project->id, 'baseline' => $bid]));
        $cmp->assertStatus(200);
        $this->assertArrayHasKey('diffs', $cmp->json());
    }
}
