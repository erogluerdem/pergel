<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Opportunity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class OpportunityController extends Controller
{
    public function list(Request $request)
    {
        $request->merge(['view' => 'list']);
        return $this->index($request);
    }

    public function kanban(Request $request)
    {
        $request->merge(['view' => 'kanban']);
        return $this->index($request);
    }

    public function index(Request $request)
    {
        $view = $request->get('view', 'kanban'); // kanban or list
        $stages = ['lead'=>'Fırsat Belirlendi','qualification'=>'Değerlendirme','proposal'=>'Teklif Hazırlığı','negotiation'=>'Teklif Sunuldu','won'=>'Kazanıldı','lost'=>'Kaybedildi'];

        // Calculate statistics
        $totalOpportunities = Opportunity::count();
        $totalValue = Opportunity::sum('estimated_value');
        $totalValueKdv = Opportunity::sum('estimated_value_kdv');
        $wonOpportunities = Opportunity::where('stage', 'won')->count();
        $avgProbability = Opportunity::avg('probability') ?? 0;
        $activeOpportunities = Opportunity::whereNotIn('stage', ['won', 'lost'])->count();

        $stats = [
            'total' => $totalOpportunities,
            'total_value' => $totalValue,
            'won' => $wonOpportunities,
            'avg_probability' => $avgProbability,
            'active' => $activeOpportunities,
        ];

        // Monthly trend data
        $monthExpr = DB::getDriverName() === 'sqlite' ? "strftime('%Y-%m', created_at)" : "DATE_FORMAT(created_at, '%Y-%m')";
        $monthlyTrend = Opportunity::selectRaw("{$monthExpr} as month, COUNT(*) as count, SUM(estimated_value) as value")
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Stage distribution
        $stageStats = [];
        foreach ($stages as $key => $label) {
            $count = Opportunity::where('stage', $key)->count();
            $value = Opportunity::where('stage', $key)->sum('estimated_value');
            $stageStats[$key] = [
                'label' => $label,
                'count' => $count,
                'value' => $value,
                'percentage' => $totalOpportunities > 0 ? round(($count / $totalOpportunities) * 100, 1) : 0
            ];
        }

        if ($view === 'list') {
            $query = Opportunity::with('owner');

            if ($request->filled('owner')) $query->where('owner_id', $request->owner);
            if ($request->filled('stage')) $query->where('stage', $request->stage);
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('title','like','%'.$request->search.'%')
                      ->orWhere('customer','like','%'.$request->search.'%')
                      ->orWhere('description','like','%'.$request->search.'%');
                });
            }
            if ($request->filled('value_min')) $query->where('estimated_value', '>=', $request->value_min);
            if ($request->filled('value_max')) $query->where('estimated_value', '<=', $request->value_max);
            if ($request->filled('probability_min')) $query->where('probability', '>=', $request->probability_min);
            if ($request->filled('date_from')) $query->where('expected_close_date', '>=', $request->date_from);
            if ($request->filled('date_to')) $query->where('expected_close_date', '<=', $request->date_to);

            $opportunities = $query->orderBy('expected_close_date','asc')->get();
            $owners = User::all();

            return view('opportunities.list', compact('opportunities','owners','stages','stats','totalOpportunities','totalValue','totalValueKdv','wonOpportunities','avgProbability','activeOpportunities','stageStats','monthlyTrend'));
        }

        // kanban view
        $columns = [];
        foreach ($stages as $key=>$label) {
            $columns[$key] = Opportunity::with('owner')->where('stage',$key)->orderBy('expected_close_date','asc')->get();
        }

        return view('opportunities.kanban', compact('columns','stages','stats','totalOpportunities','totalValue','totalValueKdv','wonOpportunities','avgProbability','activeOpportunities','stageStats','monthlyTrend'));
    }

    public function create()
    {
        $owners = User::all();
        $customers = Opportunity::whereNotNull('customer')->distinct()->pluck('customer');
        return view('opportunities.create', compact('owners', 'customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string',
            'description'=>'nullable|string',
            'customer'=>'nullable|string',
            'estimated_value'=>'nullable|numeric',
            'owner_id'=>'nullable|exists:users,id',
            'expected_close_date'=>'nullable|date',
            'source'=>'nullable|string',
            'probability'=>'nullable|integer|min:0|max:100',
            'kdv_percent'=>'nullable|integer|min:0|max:100',
            'next_action_date'=>'nullable|date',
            'last_contacted_at'=>'nullable|date',
        ]);

        $kdvPercent = \App\Services\FinanceService::resolveKdvPercent($data['kdv_percent'] ?? null);
        $est = floatval($data['estimated_value'] ?? 0);
        $kdvAmount = \App\Services\FinanceService::kdvAmount($est, $kdvPercent);
        $estKdv = \App\Services\FinanceService::applyKdv($est, $kdvPercent);

        $data['kdv_percent'] = $kdvPercent;
        $data['kdv_amount'] = $kdvAmount;
        $data['estimated_value_kdv'] = $estKdv;

        Opportunity::create($data);
        return redirect()->route('opportunities.list')->with('success','Potansiyel fırsat başarıyla kaydedildi.');
    }

    public function edit($id)
    {
        $opp = Opportunity::findOrFail($id);
        $owners = User::all();
        $customers = Opportunity::whereNotNull('customer')->distinct()->pluck('customer');
        return view('opportunities.edit', compact('opp','owners','customers'));
    }

    public function update(Request $request, $id)
    {
        $opp = Opportunity::findOrFail($id);
        $data = $request->validate([
            'title'=>'required|string',
            'description'=>'nullable|string',
            'customer'=>'nullable|string',
            'estimated_value'=>'nullable|numeric',
            'owner_id'=>'nullable|exists:users,id',
            'expected_close_date'=>'nullable|date',
            'source'=>'nullable|string',
            'probability'=>'nullable|integer|min:0|max:100',
            'kdv_percent'=>'nullable|integer|min:0|max:100',
            'next_action_date'=>'nullable|date',
            'last_contacted_at'=>'nullable|date',
        ]);

        $kdvPercent = \App\Services\FinanceService::resolveKdvPercent($data['kdv_percent'] ?? null);
        $est = floatval($data['estimated_value'] ?? 0);
        $kdvAmount = \App\Services\FinanceService::kdvAmount($est, $kdvPercent);
        $estKdv = \App\Services\FinanceService::applyKdv($est, $kdvPercent);

        $data['kdv_percent'] = $kdvPercent;
        $data['kdv_amount'] = $kdvAmount;
        $data['estimated_value_kdv'] = $estKdv;

        $opp->update($data);
        return redirect()->route('opportunities.show', $opp->id)->with('success','Fırsat güncellendi.');
    }

    public function show($id)
    {
        $opp = Opportunity::findOrFail($id);
        $activities = [];
        $contacts = [];
        $feas = $opp->feasibilityStudies()->orderBy('created_at','desc')->get();
        return view('opportunities.show', compact('opp','activities','contacts','feas'));
    }

    public function convertToProject($id)
    {
        $opp = Opportunity::findOrFail($id);
        $opp->stage = 'won';
        $opp->probability = 100;
        $opp->save();

        $project = Project::create([
            'name' => $opp->title,
            'description' => $opp->description,
            'client_name' => $opp->customer,
            'budget' => $opp->estimated_value ?? 0,
            'kdv_amount' => $opp->kdv_amount ?? 0,
            'kdv_rate' => $opp->kdv_percent ?? 20,
            'status' => 'planning',
            'project_manager' => $opp->owner_id,
            'start_date' => now(),
            'end_date' => $opp->expected_close_date ?? now()->addYear(),
        ]);

        return redirect()->route('opportunities.show', $opp->id)->with('success', 'Fırsat başarıyla Kazanıldı aşamasına alındı ve Proje #' . $project->id . ' (' . $project->name . ') olarak aktarıldı.');
    }

    public function moveStage(Request $request, $id)
    {
        $opp = Opportunity::findOrFail($id);
        $stage = $request->input('stage');
        if ($stage) {
            $opp->stage = $stage;
            if ($stage === 'won') {
                $opp->probability = 100;
            } elseif ($stage === 'lost') {
                $opp->probability = 0;
            }
            $opp->save();
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['ok' => true, 'success' => true, 'message' => 'Aşama güncellendi.']);
        }

        return redirect()->back()->with('success', 'Fırsat aşaması güncellendi.');
    }

    public function destroy($id)
    {
        $opp = Opportunity::findOrFail($id);
        $opp->delete();
        return redirect()->route('opportunities.list')->with('success', 'Fırsat silindi.');
    }

    public function addActivity(Request $request, $id)
    {
        $opp = Opportunity::findOrFail($id);
        $data = $request->validate([
            'activity' => 'nullable|string',
            'note' => 'nullable|string',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:50',
        ]);

        $text = $data['activity'] ?? $data['note'] ?? $data['description'] ?? '';
        if (!empty($text)) {
            $opp->addActivity($text);
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['ok' => true, 'success' => true, 'message' => 'Aktivite eklendi.']);
        }

        return redirect()->back()->with('success', 'Aktivite başarıyla eklendi.');
    }

    public function addNote(Request $request, $id)
    {
        $opp = Opportunity::findOrFail($id);
        $data = $request->validate([
            'note' => 'nullable|string',
            'notes' => 'nullable|string',
            'content' => 'nullable|string',
        ]);

        $noteText = $data['note'] ?? $data['notes'] ?? $data['content'] ?? '';
        if (!empty($noteText)) {
            if (Schema::hasColumn('opportunities', 'notes')) {
                $opp->notes = trim(($opp->notes ? $opp->notes . "\n" : '') . $noteText);
                $opp->save();
            } else {
                $opp->description = trim(($opp->description ? $opp->description . "\n" : '') . $noteText);
                $opp->save();
            }
            $opp->addActivity($noteText);
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['ok' => true, 'success' => true, 'message' => 'Not eklendi.']);
        }

        return redirect()->back()->with('success', 'Not başarıyla eklendi.');
    }

    public function bulkAction(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:opportunities,id',
            'action' => 'required|string',
            'stage' => 'nullable|string',
        ]);

        $ids = $data['ids'];
        $action = $data['action'];

        if ($action === 'delete') {
            Opportunity::whereIn('id', $ids)->delete();
            $message = 'Seçili fırsatlar silindi.';
        } elseif ($action === 'change_stage' || $action === 'move_stage' || !empty($data['stage'])) {
            $stage = $data['stage'] ?? $action;
            $update = ['stage' => $stage];
            if ($stage === 'won') {
                $update['probability'] = 100;
            } elseif ($stage === 'lost') {
                $update['probability'] = 0;
            }
            Opportunity::whereIn('id', $ids)->update($update);
            $message = 'Seçili fırsatların aşaması güncellendi.';
        } else {
            Opportunity::whereIn('id', $ids)->update(['stage' => $action]);
            $message = 'Toplu işlem tamamlandı.';
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['ok' => true, 'success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function exportCsv(Request $request)
    {
        $filename = 'opportunities_'.date('Ymd_His').'.csv';
        return response()->streamDownload(function () use ($request) {
            $handle = fopen('php://output','w');
            fputcsv($handle, ['ID','Title','Customer','Stage','Estimated Value','kdv_percent','kdv_amount','estimated_value_kdv','Probability','Expected Close']);
            $items = Opportunity::all();
            foreach ($items as $i) {
                $kdvPercent = isset($i->kdv_percent) ? intval($i->kdv_percent) : \App\Services\FinanceService::resolveKdvPercent($request->get('kdv', null));
                $est = floatval($i->estimated_value ?: 0);
                if (!is_null($i->kdv_amount)) {
                    $kdvAmount = (float)$i->kdv_amount;
                } else {
                    $kdvAmount = \App\Services\FinanceService::kdvAmount($est, $kdvPercent);
                }
                $estKdv = !is_null($i->estimated_value_kdv) ? (float)$i->estimated_value_kdv : \App\Services\FinanceService::applyKdv($est, $kdvPercent);
                fputcsv($handle, [
                    $i->id, $i->title, $i->customer, $i->stage, $i->estimated_value, $kdvPercent, $kdvAmount, $estKdv, $i->probability, $i->expected_close_date
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
