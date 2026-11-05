<?php

namespace App\Http\Controllers;

use App\Models\Closeout;
use App\Models\CloseoutItem;
use App\Models\Project;
use App\Models\WarrantyClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CloseoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $closeouts = Closeout::with('project')->get();
        return view('closeout.index', compact('closeouts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::whereDoesntHave('closeout')->get();
        return view('closeout.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        DB::transaction(function () use ($request) {
            $closeout = Closeout::create([
                'project_id' => $request->project_id,
                'status' => 'draft',
                'started_at' => now(),
            ]);

            // Create default closeout items
            $defaultItems = [
                ['category' => '"As-Built" Çizimler', 'item' => 'Mimari, Statik, Mekanik, Elektrik'],
                ['category' => 'Garanti Belgeleri', 'item' => 'Tüm mekanik/elektrik ekipmanları (Klima, Asansör vb.)'],
                ['category' => 'Kullanım Kılavuzları', 'item' => 'Tüm cihazların O&M (İşletme ve Bakım) kılavuzları'],
                ['category' => 'Resmi Belgeler', 'item' => 'İskan (Yapı Kullanma İzin Belgesi), SGK İlişiksizlik Belgesi vb.'],
                ['category' => 'Yedek Parça Listesi', 'item' => 'İşverene teslim edilen yedek parçaların listesi.'],
                ['category' => 'Taşeron İletişim Listesi', 'item' => 'Garanti süresince ihtiyaç duyulabilecek tüm taşeronların iletişim bilgileri.'],
            ];

            foreach ($defaultItems as $item) {
                CloseoutItem::create([
                    'closeout_id' => $closeout->id,
                    'category' => $item['category'],
                    'item' => $item['item'],
                    'status' => 'pending',
                ]);
            }
        });

        return redirect()->route('closeout.index')->with('success', 'Proje kapanış süreci başlatıldı.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Closeout $closeout)
    {
        $closeout->load(['project', 'items', 'warrantyClaims.equipment', 'warrantyClaims.contractor']);
        return view('closeout.show', compact('closeout'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Closeout $closeout)
    {
        $closeout->load('items');
        return view('closeout.edit', compact('closeout'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Closeout $closeout)
    {
        // Update closeout items
        if ($request->has('items')) {
            foreach ($request->items as $itemId => $itemData) {
                $item = CloseoutItem::find($itemId);
                if ($item && $item->closeout_id === $closeout->id) {
                    $item->update([
                        'status' => $itemData['status'] ?? $item->status,
                        'documents' => $itemData['documents'] ?? $item->documents,
                        'notes' => $itemData['notes'] ?? $item->notes,
                    ]);
                }
            }
        }

        // Update completion percentage
        $totalItems = $closeout->items()->count();
        $completedItems = $closeout->items()->where('status', 'completed')->count();
        $completionPercentage = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;

        $closeout->update([
            'completion_percentage' => $completionPercentage,
            'status' => $completionPercentage === 100 ? 'completed' : 'in_progress',
            'completed_at' => $completionPercentage === 100 ? now() : null,
        ]);

        return redirect()->route('closeout.show', $closeout)->with('success', 'Kapanış kontrol listesi güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Closeout $closeout)
    {
        $closeout->delete();
        return redirect()->route('closeout.index')->with('success', 'Proje kapanış süreci silindi.');
    }

    public function createWarrantyClaim(Closeout $closeout)
    {
        $equipments = $closeout->project->equipments ?? collect();
        $contractors = $closeout->project->vendors ?? collect();
        return view('closeout.create-warranty-claim', compact('closeout', 'equipments', 'contractors'));
    }

    public function storeWarrantyClaim(Request $request, Closeout $closeout)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'notification_date' => 'required|date',
            'equipment_id' => 'nullable|exists:equipment,id',
            'description' => 'nullable|string',
            'evidence_photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('evidence_photo')) {
            $data['evidence_photo'] = $request->file('evidence_photo')->store('warranty-claims', 'public');
        }

        // Auto-assign contractor based on equipment
        if ($request->equipment_id) {
            $equipment = \App\Models\Equipment::find($request->equipment_id);
            if ($equipment && $equipment->vendor_id) {
                $data['contractor_id'] = $equipment->vendor_id;
                $data['status'] = 'assigned';
            }
        }

        $data['closeout_id'] = $closeout->id;
        $data['claim_no'] = 'WC-' . strtoupper(uniqid());

        WarrantyClaim::create($data);

        return redirect()->route('closeout.show', $closeout)->with('success', 'Garanti talebi oluşturuldu.');
    }

    public function updateWarrantyClaim(Request $request, WarrantyClaim $warrantyClaim)
    {
        $request->validate([
            'status' => 'required|in:open,assigned,intervened,approved,closed',
        ]);

        $warrantyClaim->update($request->only('status'));

        return redirect()->back()->with('success', 'Garanti talebi güncellendi.');
    }
}
