<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BimModel;
use App\Models\BimClashTest;
use Illuminate\Support\Facades\Storage;

class BimModelController extends Controller
{
    public function index(Request $request)
    {
        $query = BimModel::query();

        // simple search
        if ($q = $request->input('q')) {
            $query->where('name', 'like', "%{$q}%");
        }

        if ($discipline = $request->input('discipline')) {
            $query->where('discipline', $discipline);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $models = $query->orderBy('created_at','desc')->paginate(12)->appends($request->except('page'));

        // provide discipline list for filter (simple static list + distinct fallback)
        $disciplines = ['Mimari','Statik','Mekanik','Elektrik'];
        $extra = BimModel::select('discipline')->distinct()->pluck('discipline')->filter()->toArray();
        $disciplines = array_values(array_unique(array_merge($disciplines, $extra)));

        if ($request->wantsJson() || $request->input('json')) {
            $stats = $this->getModelStats();
            return response()->json([
                'data' => $models,
                'stats' => $stats,
                'models' => $models->items()
            ]);
        }

        return view('bim.models.index', compact('models','disciplines'));
    }

    private function getModelStats()
    {
        $total = BimModel::count();
        $ready = BimModel::where('status', 'ready')->count();
        $processing = BimModel::where('status', 'processing')->count();
        $archived = BimModel::where('status', 'archived')->count();

        // Calculate total file size (mock data for now)
        $totalSize = BimModel::sum('file_size') ?: 0;

        return [
            'total' => $total,
            'ready' => $ready,
            'processing' => $processing,
            'archived' => $archived,
            'totalSize' => $totalSize
        ];
    }

    public function uploadForm(Request $request)
    {
        return view('bim.models.upload');
    }

    // Return only the upload partial (no layout) for AJAX panel injection
    public function uploadPartial(Request $request)
    {
        // Force the view to behave as if it's an AJAX request
        return view('bim.models.upload')->with('isAjax', true);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required','discipline'=>'required','file'=>'required|file'
        ]);
        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        $path = $file->storeAs('bim/models', time().'_'.preg_replace('/[^A-Za-z0-9_\.-]/','_', $file->getClientOriginalName()));

        $m = BimModel::create([
            'name'=>$data['name'],
            'discipline'=>$data['discipline'],
            'file_type'=>$ext,
            'version'=>$request->input('version', 'V1'),
            'version_notes'=>$request->input('version_notes'),
            'status'=>'processing',
            'file_path'=>$path,
            'file_size'=>$file->getSize(),
            'uploaded_by'=>$request->user()->id ?? null
        ]);

        // Dispatch background processing job
        try {
            \App\Jobs\ProcessBimModel::dispatch($m->id);
        } catch (\Exception $e) {
            // fallback: mark ready to avoid blocking UI
            $m->status = 'ready'; $m->save();
        }

        return redirect()->route('bim.models.index');
    }

    public function viewer(Request $request, $id)
    {
        $m = BimModel::findOrFail($id);
        // generate a temporary signed url for the public file route (valid 15 minutes)
        $signed = \URL::temporarySignedRoute('bim.models.file.public', now()->addMinutes(15), ['id'=>$m->id]);
        return view('bim.models.viewer', compact('m','signed'));
    }

    public function show(Request $request, $id)
    {
        $model = BimModel::findOrFail($id);
        return response()->json($model);
    }

    public function compare(Request $request, $id1, $id2)
    {
        $m1 = BimModel::findOrFail($id1);
        $m2 = BimModel::findOrFail($id2);

        $signed1 = \URL::temporarySignedRoute('bim.models.file.public', now()->addMinutes(15), ['id'=>$m1->id]);
        $signed2 = \URL::temporarySignedRoute('bim.models.file.public', now()->addMinutes(15), ['id'=>$m2->id]);

        return view('bim.models.compare', compact('m1','m2','signed1','signed2'));
    }    public function file(Request $request, $id)
    {
        $m = BimModel::findOrFail($id);
        if (!$m->file_path || !\Illuminate\Support\Facades\Storage::exists($m->file_path)) {
            abort(404, 'File not found');
        }
        // return inline response so the viewer can fetch it
        return \Illuminate\Support\Facades\Storage::response($m->file_path);
    }

    public function filePublic(Request $request, $id)
    {
        $m = BimModel::findOrFail($id);
        if (!$m->file_path || !\Illuminate\Support\Facades\Storage::exists($m->file_path)) {
            abort(404, 'File not found');
        }
        // stream file without requiring session-based auth (signed middleware already validated)
        return \Illuminate\Support\Facades\Storage::response($m->file_path);
    }

    // trigger a Revit -> IFC conversion (placeholder job)
    public function convertRvt(Request $request, $id)
    {
        $m = BimModel::findOrFail($id);
        // only allow if file_type is rvt (or force via query)
        if ($m->file_type !== 'rvt' && !$request->input('force')) {
            return redirect()->back()->with('error', 'Dosya tipi .rvt değil veya dönüşüm gerekli değil.');
        }

        try {
            \App\Jobs\ConvertRvtJob::dispatch($m->id);
            return redirect()->back()->with('status', 'RVT -> IFC dönüşümü kuyruğa eklendi.');
        } catch (\Exception $e) {
            // fallback to synchronous conversion
            \App\Jobs\ConvertRvtJob::dispatchSync($m->id);
            return redirect()->back()->with('status', 'Dönüşüm eşzamanlı olarak tamamlandı.');
        }
    }

    // create a clash test (stub - fake results)
    public function createClashTest(Request $request)
    {
        $data = $request->validate(['name'=>'required','models_a'=>'required','models_b'=>'required']);
        $a = array_filter(array_map('intval', array_map('trim', explode(',', $data['models_a']))));
        $b = array_filter(array_map('intval', array_map('trim', explode(',', $data['models_b']))));

        $t = BimClashTest::create(['name'=>$data['name'],'models_a'=>$a,'models_b'=>$b,'status'=>'pending','created_by'=>$request->user()->id ?? null]);

        // Dispatch clash test job
        try {
            \App\Jobs\RunClashTest::dispatch($t->id);
        } catch (\Exception $e) {
            // Fallback: run synchronously (best-effort)
            \App\Jobs\RunClashTest::dispatchSync($t->id);
        }

        return redirect()->route('bim.clashtests.detail', ['id'=>$t->id]);
    }

    public function clashDetail(Request $request, $id)
    {
        $t = BimClashTest::findOrFail($id);
        return view('bim.models.clash', compact('t'));
    }
}
