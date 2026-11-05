<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncidentReport;
use App\Models\Permit;
use App\Models\ToolboxTalk;

class IsgController extends Controller
{
    public function dashboard()
    {
        // Kayıp Zamansız Gün Sayısı: En son ciddi kaza yaşandığından bu yana geçen gün sayısı
        $daysLost = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('incident_reports')) {
            $lastSeriousIncident = IncidentReport::whereIn('type', ['hafif_yaralanmali', 'agir_yaralanmali'])
                ->latest('created_at')
                ->first();
            if ($lastSeriousIncident) {
                $daysLost = now()->diffInDays($lastSeriousIncident->created_at);
            }
        }

        // Bu Ayki "Ramak Kala" Bildirimi
        if (\Illuminate\Support\Facades\Schema::hasTable('incident_reports')) {
            $ramakCount = IncidentReport::where('type', 'ramak_kala')
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count();
        } else {
            $ramakCount = 0;
        }

        // Açık Düzeltici Faaliyet Sayısı
        $openCorrectiveActions = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('incident_reports')) {
            $openCorrectiveActions = IncidentReport::where('status', 'open')
                ->whereNotNull('corrective_action')
                ->count();
        }

        // Aktif Çalışma İzni Sayısı
        if (\Illuminate\Support\Facades\Schema::hasTable('permits')) {
            $activePermits = Permit::where('status', 'active')->count();
        } else {
            $activePermits = 0;
        }

        // Tamamlanan Toolbox sayısı (değişmedi)
        if (\Illuminate\Support\Facades\Schema::hasTable('toolbox_talks')) {
            $completedToolbox = ToolboxTalk::where('status', 'completed')->count();
        } else {
            $completedToolbox = 0;
        }

        // recent incidents (last 7 days) for the "Son Olaylar" widget
        if (\Illuminate\Support\Facades\Schema::hasTable('incident_reports')) {
            $recentIncidents = IncidentReport::where('created_at', '>=', now()->subDays(7))->latest()->limit(6)->get();
        } else {
            $recentIncidents = \App\Models\IncidentReport::latest()->take(5)->get();
        }

        return view('field.qhse.safety.dashboard', compact('daysLost','ramakCount','openCorrectiveActions','activePermits','completedToolbox','recentIncidents'));
    }

    public function incidentCreate()
    {
        return view('field.qhse.safety.incident_create');
    }

    public function incidentIndex()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('incident_reports')) {
            $query = IncidentReport::query();

            // Filtreleme
            if (request('type')) {
                $query->where('type', request('type'));
            }

            if (request('status')) {
                $query->where('status', request('status'));
            }

            if (request('date_from')) {
                $query->whereDate('occurred_at', '>=', request('date_from'));
            }

            if (request('date_to')) {
                $query->whereDate('occurred_at', '<=', request('date_to'));
            }

            $incidents = $query->latest()->paginate(15)->withQueryString();
        } else {
            $incidents = \App\Models\IncidentReport::with('project')->get();
        }

        return view('field.qhse.safety.incident_index', compact('incidents'));
    }

    public function incidentShow($id)
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('incident_reports')) {
            $incident = IncidentReport::findOrFail($id);
        } else {
            abort(404);
        }

        return view('field.qhse.safety.incident_show', compact('incident'));
    }

    public function incidentStore(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:ramak_kala,hafif_yaralanmali,agir_yaralanmali,guvensiz_durum',
            'description' => 'required|string|max:1000',
            'occurred_at' => 'required|date',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'evidence' => 'required|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:20480', // 20MB max
            'root_cause' => 'nullable|string|max:1000',
            'corrective_action' => 'nullable|string|max:1000',
            'action_responsible' => 'nullable|string|max:255',
            'action_deadline' => 'nullable|date|after:today',
        ]);

        // Dosya yükleme
        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('incidents', 'public');
        }

        IncidentReport::create([
            'type' => $validated['type'],
            'description' => $validated['description'],
            'occurred_at' => $validated['occurred_at'],
            'location' => $validated['location'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'evidence_path' => $evidencePath,
            'root_cause' => $validated['root_cause'] ?? null,
            'corrective_action' => $validated['corrective_action'] ?? null,
            'action_responsible' => $validated['action_responsible'] ?? null,
            'action_deadline' => $validated['action_deadline'] ?? null,
            'status' => 'open',
        ]);

        return redirect()->route('field.qhse.safety.incidents.index')->with('success', 'Olay bildirimi başarıyla kaydedildi.');
    }

    public function permitsIndex()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('permits')) {
            $query = Permit::query();

            // Filtreleme
            if (request('type')) {
                $query->where('type', request('type'));
            }

            if (request('status')) {
                $query->where('status', request('status'));
            }

            if (request('date_from')) {
                $query->whereDate('valid_from', '>=', request('date_from'));
            }

            if (request('date_to')) {
                $query->whereDate('valid_to', '<=', request('date_to'));
            }

            $permits = $query->latest()->paginate(15)->withQueryString();
        } else {
            $permits = \App\Models\Permit::with('project')->get();
        }

        return view('field.qhse.safety.permits_index', compact('permits'));
    }

    public function permitCreate()
    {
        return view('field.qhse.safety.permit_create');
    }

    public function permitStore(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:yuksekte_calisma,sicak_islem,kapali_alan,elektrik,kazma_hafriyat,diger',
            'description' => 'required|string|max:1000',
            'location' => 'required|string|max:255',
            'responsible_person' => 'required|string|max:255',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'checklist' => 'nullable|array',
            'checklist.*' => 'string',
            'risk_assessment' => 'nullable|string|max:1000',
            'special_conditions' => 'nullable|string|max:500',
        ]);

        // Checklist'i JSON olarak sakla
        $checklistData = null;
        if ($request->has('checklist') && is_array($request->checklist)) {
            $checklistData = json_encode($request->checklist);
        }

        Permit::create([
            'type' => $validated['type'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'responsible_person' => $validated['responsible_person'],
            'valid_from' => $validated['valid_from'],
            'valid_to' => $validated['valid_to'],
            'checklist' => $checklistData,
            'risk_assessment' => $validated['risk_assessment'] ?? null,
            'special_conditions' => $validated['special_conditions'] ?? null,
            'status' => 'pending', // Onay bekliyor
            'requested_by' => auth()->id(),
        ]);

        return redirect()->route('field.qhse.safety.permits.index')->with('success', 'Çalışma izni başarıyla oluşturuldu ve onay için gönderildi.');
    }

    public function permitShow($id)
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('permits')) {
            $permit = Permit::findOrFail($id);
        } else {
            abort(404);
        }

        return view('field.qhse.safety.permit_show', compact('permit'));
    }

    public function permitApprove(Request $request, $id)
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('permits')) {
            $permit = Permit::findOrFail($id);
            $permit->update(['status' => 'approved']);
        }

        return redirect()->back()->with('success', 'İzin onaylandı.');
    }

    public function permitClose(Request $request, $id)
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('permits')) {
            $permit = Permit::findOrFail($id);
            $permit->update(['status' => 'closed']);
        }

        return redirect()->back()->with('success', 'İzin kapatıldı.');
    }

    public function toolboxIndex()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('toolbox_talks')) {
            $query = ToolboxTalk::query();

            // Filtreleme
            if (request('date_from')) {
                $query->whereDate('date', '>=', request('date_from'));
            }

            if (request('date_to')) {
                $query->whereDate('date', '<=', request('date_to'));
            }

            if (request('topic')) {
                $query->where('topic', 'like', '%' . request('topic') . '%');
            }

            $talks = $query->latest()->paginate(15)->withQueryString();
        } else {
            $talks = \App\Models\ToolboxTalk::with('project')->get();
        }

        return view('field.qhse.safety.toolbox_index', compact('talks'));
    }

    public function toolboxCreate()
    {
        return view('field.qhse.safety.toolbox_create');
    }

    public function toolboxStore(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'topic' => 'required|string|max:255',
            'facilitator' => 'required|string|max:255',
            'participants' => 'required|string', // JSON string olarak gelecek
            'notes' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048', // 2MB max
            'signature_data' => 'nullable|string',
        ]);

        // Katılımcıları JSON'dan array'e çevir
        $participants = json_decode($validated['participants'], true);
        if (!is_array($participants) || empty($participants)) {
            return back()->withErrors(['participants' => 'En az bir katılımcı ekleyin.']);
        }

        // Fotoğraf yükleme
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('toolbox', 'public');
        }

        ToolboxTalk::create([
            'date' => $validated['date'],
            'topic' => $validated['topic'],
            'facilitator' => $validated['facilitator'],
            'participants' => json_encode($participants),
            'notes' => $validated['notes'] ?? null,
            'photo_path' => $photoPath,
            'signature_data' => $validated['signature_data'] ?? null,
            'status' => 'completed',
        ]);

        return redirect()->route('field.qhse.safety.toolbox.index')->with('success', 'Toolbox talk eğitimi başarıyla kaydedildi.');
    }
}
