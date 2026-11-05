<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanningTask;
use App\Models\Equipment;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FieldOperationsController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'nullable|integer',
            'report_date' => 'required|date',
            'notes' => 'nullable|string',
            'weather' => 'nullable|array',
            'status' => 'nullable|string',
            'labours' => 'nullable|array',
            'equipments' => 'nullable|array',
        ]);

        $data['author_id'] = Auth::id() ?: $request->input('author_id');
        $data['status'] = $data['status'] ?? 'submitted';

        $report = DailyFieldReport::updateOrCreate(
            ['project_id' => $data['project_id'] ?? null, 'report_date' => $data['report_date']],
            $data
        );

        // save labours
        if(!empty($data['labours']) && is_array($data['labours'])){
            // replace existing for simplicity
            DailyFieldReportLabour::where('report_id',$report->id)->delete();
            foreach($data['labours'] as $l){
                DailyFieldReportLabour::create(array_merge($l,['report_id'=>$report->id]));
            }
        }

        // save equipments
        if(!empty($data['equipments']) && is_array($data['equipments'])){
            DailyFieldReportEquipment::where('report_id',$report->id)->delete();
            foreach($data['equipments'] as $e){
                DailyFieldReportEquipment::create(array_merge($e,['report_id'=>$report->id]));
            }
        }

        return response()->json(['ok' => true, 'report' => $report], 201);
    }

    public function show($projectId, $date)
    {
        $report = DailyFieldReport::with(['media','labours','equipments'])->where('project_id', $projectId)->where('report_date', $date)->first();
        if(!$report) return response()->json(['ok'=>false],404);
        return response()->json(['ok'=>true,'report'=>$report]);
    }

    public function uploadMedia(Request $request)
    {
        $request->validate(['file'=>'required|file','project_id'=>'nullable|integer','report_id'=>'nullable|integer']);
        $file = $request->file('file');
        $path = $file->store('field-media','public');

        // attempt EXIF GPS parsing
        $gpsLat = null; $gpsLon = null;
        try{
            if(function_exists('exif_read_data')){
                $exif = @exif_read_data($file->getRealPath());
                if(!empty($exif['GPSLatitude']) && !empty($exif['GPSLongitude']) && !empty($exif['GPSLatitudeRef']) && !empty($exif['GPSLongitudeRef'])){
                    $gpsLat = $this->gpsToDecimal($exif['GPSLatitude'], $exif['GPSLatitudeRef']);
                    $gpsLon = $this->gpsToDecimal($exif['GPSLongitude'], $exif['GPSLongitudeRef']);
                }
            }
        }catch(\Exception $e){}

        $media = FieldMedia::create([
            'project_id' => $request->input('project_id'),
            'report_id' => $request->input('report_id'),
            'uploader_id' => Auth::id(),
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'gps_lat' => $gpsLat,
            'gps_lon' => $gpsLon,
        ]);

        // return with accessible URL
        $media->url = Storage::url($path);

        return response()->json(['ok'=>true,'media'=>$media],201);
    }

    protected function gpsToDecimal($coord, $hem)
    {
        // $coord is array of [deg, min, sec] where each is "num/den"
        try{
            $parts = array_map(function($v){
                $t = explode('/',$v);
                if(count($t)==1) return floatval($t[0]);
                if($t[1]==0) return 0;
                return floatval($t[0]) / floatval($t[1]);
            }, $coord);
            $dec = $parts[0] + ($parts[1] / 60.0) + ($parts[2] / 3600.0);
            if(in_array($hem, ['S','W'])) $dec *= -1;
            return $dec;
        }catch(\Exception $e){
            return null;
        }
    }

    public function mediaIndex(Request $request)
    {
        $projectId = $request->query('project');
        $q = FieldMedia::query();
        if($projectId) $q->where('project_id', $projectId);
        $items = $q->orderBy('created_at','desc')->get();
        foreach($items as $it) $it->url = Storage::url($it->path);
        return response()->json(['ok'=>true,'media'=>$items]);
    }

    /**
     * Attach uploaded media records to a report
     * Expects: report_id (int), media_ids (array of ints)
     */
    public function attachMedia(Request $request)
    {
        $data = $request->validate([
            'report_id' => 'required|integer|exists:daily_field_reports,id',
            'media_ids' => 'required|array'
        ]);
        $reportId = $data['report_id'];
        $ids = array_filter($data['media_ids'], function($v){ return is_numeric($v) && $v>0; });
        if(empty($ids)) return response()->json(['ok'=>false,'message'=>'no media ids provided'],400);
        FieldMedia::whereIn('id',$ids)->update(['report_id'=>$reportId]);
        return response()->json(['ok'=>true,'attached'=>count($ids)]);
    }

    public function statuses(Request $request)
    {
        $projectId = $request->query('project');
        $month = $request->query('month'); // format YYYY-MM
        if(!$month) return response()->json(['ok'=>false,'error'=>'month required'],400);

        [$y,$m] = explode('-', $month);
        $start = \Carbon\Carbon::createFromDate($y,$m,1)->startOfMonth()->toDateString();
        $end = \Carbon\Carbon::createFromDate($y,$m,1)->endOfMonth()->toDateString();

        $q = DailyFieldReport::whereBetween('report_date',[$start,$end]);
        if($projectId) $q->where('project_id',$projectId);
        $rows = $q->get(['report_date','status']);
        $out = [];
        foreach($rows as $r) $out[$r->report_date] = $r->status;
        return response()->json(['ok'=>true,'statuses'=>$out]);
    }

    public function getWeather(Request $request)
    {
        $city = $request->query('city', 'Niğde'); // Default to Niğde
        $apiKey = config('services.openweather.api_key');
        $baseUrl = config('services.openweather.base_url');

        if (!$apiKey) {
            return response()->json(['ok' => false, 'error' => 'Weather API key not configured'], 500);
        }

        try {
            $response = Http::get($baseUrl . 'weather', [
                'q' => $city . ',TR',
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'tr'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'ok' => true,
                    'weather' => [
                        'temperature' => $data['main']['temp'] ?? null,
                        'description' => $data['weather'][0]['description'] ?? null,
                        'humidity' => $data['main']['humidity'] ?? null,
                        'wind_speed' => $data['wind']['speed'] ?? null,
                        'pressure' => $data['main']['pressure'] ?? null,
                        'icon' => $data['weather'][0]['icon'] ?? null,
                    ]
                ]);
            } else {
                return response()->json(['ok' => false, 'error' => 'Weather API request failed'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'error' => 'Weather API error: ' . $e->getMessage()], 500);
        }
    }

    public function getTasks(Request $request)
    {
        $projectId = $request->query('project_id');
        if (!$projectId) {
            return response()->json(['ok' => false, 'error' => 'project_id required'], 400);
        }

        $tasks = PlanningTask::where('project_id', $projectId)
            ->orderBy('start_date')
            ->get(['id', 'name', 'progress']);

        return response()->json(['ok' => true, 'tasks' => $tasks]);
    }

    public function getEquipment(Request $request)
    {
        $projectId = $request->query('project_id');
        if (!$projectId) {
            return response()->json(['ok' => false, 'error' => 'project_id required'], 400);
        }

        $equipment = Equipment::where('current_project_id', $projectId)
            ->where('status', 'active')
            ->get(['id', 'name', 'type', 'plate_number']);

        return response()->json(['ok' => true, 'equipment' => $equipment]);
    }

    public function getVendors(Request $request)
    {
        $projectId = $request->query('project_id');
        if (!$projectId) {
            return response()->json(['ok' => false, 'error' => 'project_id required'], 400);
        }

        $vendors = Vendor::whereHas('projects', function($q) use ($projectId) {
            $q->where('project_id', $projectId);
        })->get(['id', 'name', 'specialty']);

        return response()->json(['ok' => true, 'vendors' => $vendors]);
    }

    public function indexWeb(Request $request)
    {
        $projects = \App\Models\Project::all();
        $selectedProjectId = $request->query('project_id') ?: ($projects->first()?->id);
        
        $query = \App\Models\DailyFieldReport::with(['labours', 'equipments', 'media'])
            ->orderBy('report_date', 'desc');

        if ($selectedProjectId) {
            $query->where('project_id', $selectedProjectId);
        }

        if ($request->filled('date')) {
            $query->whereDate('report_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(15)->appends($request->all());

        // Stats calculation
        $allReports = \App\Models\DailyFieldReport::with(['labours', 'equipments'])->get();
        $totalLabours = $allReports->sum(fn($r) => $r->labours->sum('count'));
        $totalEquipments = $allReports->sum(fn($r) => $r->equipments->count());
        $todayReportsCount = \App\Models\DailyFieldReport::whereDate('report_date', now())->count();

        return view('field.operations', compact('reports', 'projects', 'selectedProjectId', 'totalLabours', 'totalEquipments', 'todayReportsCount'));
    }

    public function createWeb()
    {
        $projects = \App\Models\Project::all();
        $vendors = \App\Models\Vendor::all();
        return view('field.create', compact('projects', 'vendors'));
    }

    public function showWeb($id)
    {
        $report = \App\Models\DailyFieldReport::with(['media', 'labours', 'equipments'])->findOrFail($id);
        $project = \App\Models\Project::find($report->project_id);
        return view('field.show', compact('report', 'project'));
    }

    public function editWeb($id)
    {
        $report = \App\Models\DailyFieldReport::with(['labours', 'equipments', 'media'])->findOrFail($id);
        $projects = \App\Models\Project::all();
        $vendors = \App\Models\Vendor::all();
        return view('field.edit', compact('report', 'projects', 'vendors'));
    }

    public function destroyWeb($id)
    {
        $report = \App\Models\DailyFieldReport::findOrFail($id);
        $report->delete();
        return redirect()->route('field.operations')->with('success', 'Günlük saha raporu başarıyla silindi.');
    }
}
