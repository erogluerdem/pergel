<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QhseTemplate;
use App\Models\Inspection;
use App\Models\InspectionItem;
use App\Models\Punch;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class QhseController extends Controller
{
    public function templates()
    {
        return response()->json(QhseTemplate::all());
    }

    public function inspectionsIndex(Request $request)
    {
        $q = Inspection::with('items');
        return response()->json($q->get());
    }

    public function inspectionsStore(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'discipline' => 'nullable|string',
            'due_date' => 'nullable|date',
            'items' => 'nullable|array'
        ]);
        $ins = Inspection::create($data);
        if(!empty($data['items'])){
            foreach($data['items'] as $it){
                $ins->items()->create([ 'text' => $it['text'] ?? '', 'result' => $it['result'] ?? null ]);
            }
        }
        return response()->json($ins->load('items'));
    }

    public function inspectionsShow(Inspection $inspection)
    {
        return response()->json($inspection->load('items'));
    }

    public function addInspectionItem(Request $request, Inspection $inspection)
    {
        $data = $request->validate(['text' => 'required|string']);
        $item = $inspection->items()->create($data);
        return response()->json($item);
    }

    public function updateInspectionItem(Request $request, Inspection $inspection, $itemId)
    {
        $item = $inspection->items()->where('id', $itemId)->firstOrFail();
        $data = $request->validate([
            'result' => 'nullable|string',
            'note' => 'nullable|string',
            'assignee' => 'nullable|string',
            'due_date' => 'nullable|date',
            'evidence' => 'nullable|array'
        ]);
        if(isset($data['due_date'])) $data['due_date'] = date('Y-m-d H:i:s', strtotime($data['due_date']));
        $oldResult = $item->result;
        $item->update($data);

        // if the item result changed to 'fail', create a punch if not exists
        if(($oldResult !== 'fail') && (isset($data['result']) && $data['result'] === 'fail')){
            $p = Punch::create([
                'title' => 'Oluşan hata: ' . substr($item->text ?? 'Madde',0,80),
                'description' => $data['note'] ?? null,
                'responsible' => $data['assignee'] ?? null,
                'due_date' => $data['due_date'] ?? null,
                'evidence' => $data['evidence'] ?? null,
                'inspection_item_id' => $item->id,
                'status' => 'open'
            ]);
            // record an activity
            $p->activities()->create([ 'actor' => auth()->user()?auth()->user()->name:'system', 'note' => 'Auto-created from failed inspection item' ]);
        }

        return response()->json($item->fresh());
    }

    // Punchlist
    public function punchIndex()
    {
        return response()->json(Punch::with('activities')->get());
    }

    public function punchStore(Request $request)
    {
        // When creating a new punch, evidence (photo) is required for field reporting
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'responsible' => 'nullable|string',
            'due_date' => 'nullable|date',
            'evidence' => 'required|array|min:1',
            'inspection_item_id' => 'nullable|integer',
            'location' => 'nullable'
        ]);
        $p = Punch::create($data);
        // record initial activity
        $p->activities()->create([ 'actor' => auth()->user()?auth()->user()->name:'system', 'note' => 'Punch created' ]);

        return response()->json($p->load('activities'));
    }

    public function punchUpdate(Request $request, Punch $punch)
    {
        $old = $punch->status;
        $data = $request->only(['status','responsible','due_date','description','evidence','location']);

        // If moving to 'resolved' require evidence
        if(isset($data['status']) && $data['status'] === 'resolved'){
            if(empty($data['evidence']) || !is_array($data['evidence']) || count($data['evidence'])===0){
                return response()->json(['error' => 'Evidence photo required when marking as resolved.'], 422);
            }
        }

        $punch->update($data);

        // create activity describing the change
        $actor = auth()->user()?auth()->user()->name:'system';
        $note = 'Updated';
        if(isset($data['status']) && $data['status'] !== $old){
            $note = "Status changed: {$old} -> {$data['status']}";
        }
        $punch->activities()->create([ 'actor' => $actor, 'note' => $note ]);

        return response()->json($punch->fresh()->load('activities'));
    }

    /**
     * Upload evidence media (multipart/form-data)
     * Returns JSON { id, url }
     */
    public function uploadEvidence(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240' // max 10MB
        ]);
        $file = $request->file('file');
        $path = $file->store('qhse', 'public');
        $url = Storage::disk('public')->url($path);

        // create a lightweight id reference (uuid)
        $id = (string) Str::uuid();

        // store minimal metadata in a simple JSON file map (lightweight approach)
        // We'll store under storage/app/public/qhse/_meta.json as an array of objects
        $metaPath = storage_path('app/public/qhse/_meta.json');
        $meta = [];
        if (file_exists($metaPath)) {
            $raw = file_get_contents($metaPath);
            $meta = json_decode($raw, true) ?: [];
        }
        $entry = ['id' => $id, 'path' => $path, 'url' => $url, 'original_name' => $file->getClientOriginalName(), 'uploaded_at' => date('c')];
        $meta[] = $entry;
        file_put_contents($metaPath, json_encode($meta, JSON_PRETTY_PRINT));

        return response()->json(['id' => $id, 'url' => $url]);
    }
}
