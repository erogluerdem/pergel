<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;
use App\Models\Opportunity;

class OpportunityMoveStageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // create a minimal opportunities table for the test to avoid running full migrations
        if (!Schema::hasTable('opportunities')) {
            Schema::create('opportunities', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('customer')->nullable();
                $table->decimal('estimated_value', 15, 2)->nullable();
                $table->string('stage')->default('lead');
                $table->unsignedBigInteger('owner_id')->nullable();
                $table->integer('probability')->nullable();
                $table->date('expected_close_date')->nullable();
                $table->date('next_action_date')->nullable();
                $table->dateTime('last_contacted_at')->nullable();
                $table->integer('kdv_percent')->nullable();
                $table->decimal('kdv_amount',15,2)->nullable();
                $table->decimal('estimated_value_kdv',15,2)->nullable();
                $table->timestamps();
            });
        }
    }

    public function test_move_stage_endpoint_changes_stage()
    {
        // create an opportunity
        $opp = Opportunity::create([
            'title' => 'MoveStage Test',
            'description' => 'test',
            'customer' => 'TestCo',
            'estimated_value' => 1000,
            'stage' => 'lead'
        ]);

        $this->postJson('/opportunities/'.$opp->id.'/move', ['stage' => 'proposal'])
            ->assertStatus(200)
            ->assertJson(['ok' => true]);

        $this->assertDatabaseHas('opportunities', ['id' => $opp->id, 'stage' => 'proposal']);
    }
}
