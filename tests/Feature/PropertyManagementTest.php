<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $manager;
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->manager = User::factory()->create(['role' => 'manager']);

        // Create test project
        $this->project = Project::factory()->create();
    }

    /**
     * Test property index page
     */
    public function test_can_view_properties_list(): void
    {
        // Create test property
        $property = Property::factory()->create([
            'project_id' => $this->project->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/operations/properties');

        $response->assertStatus(200);
        $response->assertViewIs('properties.index');
        $response->assertSee($property->property_code);
    }

    /**
     * Test create property form
     */
    public function test_can_view_create_property_form(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/operations/properties/create');

        $response->assertStatus(200);
        $response->assertViewIs('properties.create');
    }

    /**
     * Test store property
     */
    public function test_can_store_property(): void
    {
        $data = [
            'project_id' => $this->project->id,
            'property_code' => 'TEST-2025-001',
            'name' => 'Test Property',
            'city' => 'İstanbul',
            'district' => 'Beşiktaş',
            'neighborhood' => 'Test',
            'address' => 'Test Address',
            'latitude' => '41.0455',
            'longitude' => '29.0024',
            'area' => 5000,
            'building_area' => 15000,
            'zoning_status' => 'Ticari',
            'floor_ratio' => 4.5,
            'coverage_ratio' => 0.60,
            'land_value' => 15000000,
            'development_potential_value' => 45000000,
            'investment_risk_level' => 'medium',
            'deed_number' => '1234-5678',
            'block_number' => '234-A',
            'parcel_number' => '567',
            'deed_date' => '2020-01-15',
            'owner_name' => 'Test Owner',
            'legal_status' => 'clear',
            'status' => 'vacant',
        ];

        $response = $this->actingAs($this->admin)
            ->post('/operations/properties', $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('properties', ['property_code' => 'TEST-2025-001']);
    }

    /**
     * Test show property
     */
    public function test_can_view_property_details(): void
    {
        $property = Property::factory()->create([
            'project_id' => $this->project->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/operations/properties/{$property->id}");

        $response->assertStatus(200);
        $response->assertViewIs('properties.show');
        $response->assertSee($property->name);
    }

    /**
     * Test edit property
     */
    public function test_can_edit_property(): void
    {
        $property = Property::factory()->create([
            'project_id' => $this->project->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/operations/properties/{$property->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('properties.edit');
    }

    /**
     * Test update property
     */
    public function test_can_update_property(): void
    {
        $property = Property::factory()->create([
            'project_id' => $this->project->id,
            'user_id' => $this->admin->id,
        ]);

        $data = [
            'name' => 'Updated Property Name',
            'city' => 'Ankara',
            'status' => 'acquired',
        ];

        $response = $this->actingAs($this->admin)
            ->put("/operations/properties/{$property->id}", $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'name' => 'Updated Property Name',
            'city' => 'Ankara',
        ]);
    }

    /**
     * Test delete property
     */
    public function test_can_delete_property(): void
    {
        $property = Property::factory()->create([
            'project_id' => $this->project->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete("/operations/properties/{$property->id}");

        $response->assertRedirect();
        $this->assertTrue($property->fresh()->trashed());
    }

    /**
     * Test manager cannot create property
     */
    public function test_manager_cannot_create_property(): void
    {
        $property = Property::factory()->create([
            'project_id' => $this->project->id,
            'user_id' => $this->manager->id,
        ]);

        $this->assertTrue(
            $this->manager->cannot('create', Property::class)
        );
    }

    /**
     * Test property relationships
     */
    public function test_property_has_relationships(): void
    {
        $property = Property::factory()->create([
            'project_id' => $this->project->id,
            'user_id' => $this->admin->id,
        ]);

        // Add evaluation
        $property->evaluations()->create([
            'evaluation_name' => 'Test Evaluation',
            'evaluated_by_id' => $this->admin->id,
            'investment_recommendation' => 'recommended',
            'is_final' => true,
        ]);

        // Add legal process
        $property->legalProcesses()->create([
            'process_name' => 'Test Process',
            'process_type' => 'zoning_approval',
            'responsible_id' => $this->admin->id,
            'followed_by_id' => $this->admin->id,
            'status' => 'in_progress',
        ]);

        $this->assertCount(1, $property->evaluations);
        $this->assertCount(1, $property->legalProcesses);
    }

    /**
     * Test property scopes
     */
    public function test_property_scopes(): void
    {
        // Create properties with different statuses
        Property::factory()->create([
            'project_id' => $this->project->id,
            'status' => 'vacant',
        ]);
        Property::factory()->create([
            'project_id' => $this->project->id,
            'status' => 'acquired',
        ]);
        Property::factory()->create([
            'project_id' => $this->project->id,
            'status' => 'in_development',
        ]);

        $this->assertEquals(1, Property::vacant()->count());
        $this->assertEquals(1, Property::acquired()->count());
        $this->assertEquals(1, Property::inDevelopment()->count());
    }
}
