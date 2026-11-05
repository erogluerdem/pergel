<?php

namespace App\Http\Controllers;

use App\Models\SiteDiary;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SiteDiaryController extends Controller
{
    public function index(Request $request)
    {
        $query = SiteDiary::with(['project', 'creator'])->orderBy('entry_date', 'desc');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('work_done_notes', 'like', "%{$search}%")
                  ->orWhere('weather_condition', 'like', "%{$search}%");
            });
        }

        $diaries = $query->paginate(15);
        $projects = Project::orderBy('name')->get();

        // Sample seed if empty
        if ($diaries->isEmpty() && !request()->filled('project_id') && !request()->filled('search')) {
            $this->seedSampleDiaries($projects);
            $diaries = SiteDiary::with(['project', 'creator'])->orderBy('entry_date', 'desc')->paginate(15);
        }

        return view('site-diaries.index', compact('diaries', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'entry_date' => 'required|date',
            'weather_condition' => 'nullable|string',
            'temperature' => 'nullable|string',
            'worker_count' => 'required|numeric|min:0',
            'work_done_notes' => 'required|string',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $photoUrls = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('site-diaries', 'public');
                $photoUrls[] = Storage::url($path);
            }
        }

        SiteDiary::create([
            'project_id' => $request->project_id,
            'entry_date' => $request->entry_date,
            'weather_condition' => $request->weather_condition ?? 'Güneşli 24°C',
            'temperature' => $request->temperature ?? '24°C',
            'worker_count' => $request->worker_count,
            'work_done_notes' => $request->work_done_notes,
            'equipment_notes' => $request->equipment_notes,
            'safety_notes' => $request->safety_notes,
            'photo_urls' => $photoUrls,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('site_diaries.index')->with('success', 'Mobil Şantiye Günlüğü başarıyla kaydedildi.');
    }

    public function destroy(SiteDiary $site_diary)
    {
        $site_diary->delete();
        return redirect()->route('site_diaries.index')->with('success', 'Şantiye günlük kaydı silindi.');
    }

    private function seedSampleDiaries($projects)
    {
        $project = $projects->first();
        if (!$project) return;

        SiteDiary::create([
            'project_id' => $project->id,
            'entry_date' => now()->format('Y-m-d'),
            'weather_condition' => 'Güneşli 26°C',
            'temperature' => '26°C',
            'worker_count' => 34,
            'work_done_notes' => 'A Blok 4. kat radye temel demir bağlama ve beton döküm hazırlığı tamamlandı. 120m3 C35 hazır beton EBİS kırım numunesi alındı.',
            'equipment_notes' => '1 adet mobil beton pompası ve 4 adet mikser sahada aktif çalıştı.',
            'safety_notes' => 'Sabah İSG bilgilendirme toplantısı (toolbox) yapıldı. Baret ve emniyet kemeri kontrolü eksiksiz.',
            'photo_urls' => [
                'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?w=600&auto=format&fit=crop&q=60',
                'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=600&auto=format&fit=crop&q=60'
            ],
            'created_by' => Auth::id(),
        ]);

        SiteDiary::create([
            'project_id' => $project->id,
            'entry_date' => now()->subDay()->format('Y-m-d'),
            'weather_condition' => 'Parçalı Bulutlu 22°C',
            'temperature' => '22°C',
            'worker_count' => 28,
            'work_done_notes' => 'B Blok kaba inşaat 2. kat kalıp imalatı tamamlandı. Sıhhi tesisat ve elektrik borulama ekibi altyapı çalışmasını bitirdi.',
            'equipment_notes' => 'Kule vinç periyodik bakımı yapıldı.',
            'safety_notes' => 'Döşeme kenarlarındaki emniyet ağları kontrol edildi.',
            'photo_urls' => [
                'https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=600&auto=format&fit=crop&q=60'
            ],
            'created_by' => Auth::id(),
        ]);
    }
}
