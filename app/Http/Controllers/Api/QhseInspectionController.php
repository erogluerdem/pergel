<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\QhseInspection;
use App\Models\QhsePunch;

class QhseInspectionController extends Controller
{
    public function index()
    {
        return response()->json(QhseInspection::orderBy('created_at','desc')->limit(100)->get());
    }

    public function show($id)
    {
        $ins = QhseInspection::find($id);
        if(!$ins) return response()->json(['message'=>'Not found'],404);
        return response()->json($ins);
    }

    public function store(Request $req)
    {
        $data = $req->only(['title','discipline','status','items','due_date']);
        if(isset($data['items']) && is_string($data['items'])){ try{ $data['items'] = json_decode($data['items'], true); }catch(\Throwable $e){} }
        $ins = QhseInspection::create($data);
        return response()->json($ins,201);
    }

    public function update(Request $req, $id)
    {
        $ins = QhseInspection::find($id);
        if(!$ins) return response()->json(['message'=>'Not found'],404);
        $data = $req->only(['title','discipline','status','items','due_date']);
        if(isset($data['items']) && is_string($data['items'])){ $data['items'] = json_decode($data['items'], true); }
        $ins->update($data);
        return response()->json($ins);
    }

    // Create punchlist item
    public function createPunch(Request $req)
    {
        $data = $req->only(['title','description','responsible','due_date','evidence','inspection_item_id','inspection_id']);
        $p = QhsePunch::create([
            'title' => $data['title'] ?? 'Punch',
            'description' => $data['description'] ?? null,
            'responsible' => $data['responsible'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'evidence' => $data['evidence'] ?? null,
            'inspection_id' => $data['inspection_id'] ?? null,
            'status' => 'open'
        ]);
        return response()->json($p,201);
    }

    // Update punch item (partial)
    public function updatePunch(Request $req, $id)
    {
        $p = QhsePunch::find($id);
        if(!$p) return response()->json(['message'=>'Not found'],404);
        $data = $req->only(['title','description','responsible','status','due_date','evidence','priority','location']);
        // merge evidence if provided as array
        if(isset($data['evidence'])){
            $p->evidence = $data['evidence'];
            unset($data['evidence']);
        }
        $p->fill($data);
        $p->save();
        return response()->json($p);
    }

    // simple media upload stub: accept file and return fake url
    public function uploadMedia(Request $req)
    {
        if($req->hasFile('file')){
            $f = $req->file('file');
            // store in public disk under qhse_media (requires php artisan storage:link to expose)
            try{
                $path = $f->store('qhse_media', 'public');
                $url = Storage::url($path);
                return response()->json(['url' => $url, 'path' => $path, 'name' => $f->getClientOriginalName()]);
            }catch(\Throwable $e){
                // fallback to stub behavior
                return response()->json(['url' => '/storage/tmp/' . $f->getClientOriginalName(), 'name' => $f->getClientOriginalName()]);
            }
        }
        return response()->json(['message'=>'No file'],400);
    }

    // Post activity/comment for a punch item
    public function postPunchActivity(Request $req, $id)
    {
        $p = QhsePunch::find($id);
        if(!$p) return response()->json(['message'=>'Not found'],404);
        $note = $req->input('note');
        if(!$note) return response()->json(['message'=>'note required'],400);

        $actor = auth()->user()->name ?? (auth()->user()->email ?? 'user');
        $entry = ['actor' => $actor, 'note' => $note, 'created_at' => now()->toDateTimeString()];

        // store activities inside evidence->activities if evidence is an array/object; otherwise append to evidence array
        $ev = $p->evidence ?? [];
        if(!is_array($ev)) $ev = [$ev];
        // attach activities array as a special object
        $evActivities = [];
        // search for existing activities holder
        foreach($ev as $item){ if(is_array($item) && isset($item['__activities'])){ $evActivities = $item['__activities']; } }
        $evActivities[] = $entry;
        // remove old activities holder
        $ev = array_filter($ev, function($it){ return !(is_array($it) && isset($it['__activities'])); });
        $ev[] = ['__activities' => $evActivities];
        $p->evidence = array_values($ev);
        $p->save();
        return response()->json(['ok' => true, 'activities' => $evActivities]);
    }
}
