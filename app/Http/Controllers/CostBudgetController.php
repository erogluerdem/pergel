<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Schema;
use App\Models\ChangeOrder;
use App\Models\CostItem;
use App\Models\Commitment;

class CostBudgetController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');

        if ($projectId) {
            $project = Project::find($projectId);
            $projects = $project ? collect([$project]) : collect();
        } else {
            $projects = Project::where('status', '!=', 'completed')->get();
        }

        $originalBudget = $projects->sum('budget');

        // Change orders: if table exists, read from DB, otherwise use sample
        $approvedChanges = 0;
        if (Schema::hasTable('change_orders')) {
            $approvedChanges = ChangeOrder::when($projectId, function($q) use ($projectId){
                return $q->where('project_id', $projectId);
            })->where('status', 'Onaylandı')->sum('cost');

            $changeOrders = ChangeOrder::when($projectId, function($q) use ($projectId){
                return $q->where('project_id', $projectId);
            })->orderBy('created_at','desc')->get()->map(function($c){
                return [
                    'no' => $c->no ?: ('ET-' . $c->id),
                    'subject' => $c->subject,
                    'reason' => $c->reason,
                    'status' => $c->status,
                    'cost' => $c->cost,
                    'approved_at' => $c->approved_at ? $c->approved_at->format('Y-m-d') : null,
                ];
            })->toArray();
        } else {
            $changeOrders = [
                ['no' => 'ET-001', 'subject' => 'Asma Tavan Tipi Değişikliği', 'reason' => 'İşveren Talebi', 'status' => 'Onaylandı', 'cost' => 150000, 'approved_at' => '2025-09-25'],
                ['no' => 'ET-002', 'subject' => 'Zemin Etüdü Farkı', 'reason' => 'Öngörülemeyen Durum', 'status' => 'İncelemede', 'cost' => 450000, 'approved_at' => null],
            ];
        }

        $revisedBudget = $originalBudget + $approvedChanges;

        // Portfolio-level summaries
        $committed = 0; // placeholder - detailed committed per cost item calculated below
        $actualSpent = $projects->sum('spent');

        // Simple forecast (naive)
        $estimatedFinishCost = 0;
        foreach ($projects as $p) {
            $spent = $p->spent ?: 0;
            $progress = $p->progress_percent ?: 0;
            if ($progress > 0) {
                $remainingCostEstimate = ($spent / $progress) * max(0, 100 - $progress);
            } else {
                $remainingCostEstimate = $p->budget ?: 0;
            }
            $estimatedFinishCost += $spent + $remainingCostEstimate;
        }

        $variance = $revisedBudget - $estimatedFinishCost;

        // Cost ledger: read from cost_items + commitments if available
        if (Schema::hasTable('cost_items')) {
            $items = CostItem::whereNull('parent_id')->get();
            $costLedger = [];
            foreach ($items as $it) {
                $committedSum = 0;
                $actualSum = $it->actual ?: 0;
                if (Schema::hasTable('commitments')) {
                    $committedSum = Commitment::where('cost_item_id', $it->id)->sum('amount');
                    $actualSum = Commitment::where('cost_item_id', $it->id)->sum('invoiced');
                }
                $children = [];
                foreach ($it->children()->get() as $c) {
                    $cCommitted = Schema::hasTable('commitments') ? Commitment::where('cost_item_id', $c->id)->sum('amount') : $c->committed;
                    $cActual = Schema::hasTable('commitments') ? Commitment::where('cost_item_id', $c->id)->sum('invoiced') : $c->actual;
                    $children[] = [
                        'id' => $c->id,
                        'code' => $c->code,
                        'name' => $c->name,
                        'original' => $c->original,
                        'changes' => $c->changes,
                        'revised' => $c->revised,
                        'committed' => $cCommitted,
                        'actual' => $cActual,
                        'ttm' => $c->ttm,
                    ];
                }

                $costLedger[] = [
                    'id' => $it->id,
                    'code' => $it->code ?: $it->id,
                    'name' => $it->name,
                    'original' => $it->original,
                    'changes' => $it->changes,
                    'revised' => $it->revised,
                    'committed' => $committedSum ?: $it->committed,
                    'actual' => $actualSum ?: $it->actual,
                    'ttm' => $it->ttm,
                    'children' => $children,
                ];
            }
        } else {
            $costLedger = [
                ['code' => '160', 'name' => 'Kaba İnşaat İşleri', 'original' => 2000000, 'changes' => 0, 'revised' => 2000000, 'committed' => 1500000, 'actual' => 1200000, 'ttm' => 2100000],
                ['code' => '160.01', 'name' => 'Beton', 'original' => 800000, 'changes' => 0, 'revised' => 800000, 'committed' => 600000, 'actual' => 480000, 'ttm' => 840000],
                ['code' => '160.02', 'name' => 'Demir', 'original' => 600000, 'changes' => 0, 'revised' => 600000, 'committed' => 500000, 'actual' => 420000, 'ttm' => 630000],
                ['code' => '170', 'name' => 'İnce İşler', 'original' => 1000000, 'changes' => 0, 'revised' => 1000000, 'committed' => 400000, 'actual' => 200000, 'ttm' => 1100000],
            ];
        }

        return view('cost.budget', compact(
            'originalBudget',
            'approvedChanges',
            'revisedBudget',
            'committed',
            'actualSpent',
            'estimatedFinishCost',
            'variance',
            'costLedger',
            'changeOrders'
        ));
    }
}
