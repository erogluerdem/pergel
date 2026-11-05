<?php

namespace App\Http\Controllers;

use App\Models\CsbPozLibrary;
use Illuminate\Http\Request;

class CsbPozController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = CsbPozLibrary::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('poz_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('work_group', 'like', "%{$search}%");
            });
        }

        $pozs = $query->orderBy('poz_number')->paginate(20);

        return view('csb_poz.index', compact('pozs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'poz_number' => 'required|string|unique:csb_poz_libraries,poz_number',
            'title' => 'required|string',
            'unit' => 'required|string',
            'unit_price' => 'required|numeric|min:0',
            'year' => 'required|integer|min:2020|max:2030',
            'work_group' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        CsbPozLibrary::create($validated);

        return redirect()->route('csb_poz.index')->with('success', 'ÇŞB Poz kalemi başarıyla eklendi.');
    }

    public function seedSampleData()
    {
        $samplePozs = [
            ['poz_number' => '15.150.1001', 'title' => 'Gazbeton bloklarla (Ytong) duvar yapılması', 'unit' => 'm3', 'unit_price' => 2850.00, 'year' => 2026, 'work_group' => 'İnşaat / Duvar'],
            ['poz_number' => '15.120.1002', 'title' => 'Hazır beton dökülmesi (C30/37)', 'unit' => 'm3', 'unit_price' => 3100.00, 'year' => 2026, 'work_group' => 'İnşaat / Beton'],
            ['poz_number' => '15.140.1005', 'title' => 'Nervürlü çelik çubukların bükülüp yerine konulması (B420C)', 'unit' => 'ton', 'unit_price' => 34500.00, 'year' => 2026, 'work_group' => 'İnşaat / Demir'],
            ['poz_number' => '15.260.1001', 'title' => 'Çimento esaslı sıva yapılması', 'unit' => 'm2', 'unit_price' => 380.00, 'year' => 2026, 'work_group' => 'İnce İşler / Sıva'],
            ['poz_number' => '15.300.1004', 'title' => 'Seramik karolar ile zemin kaplaması', 'unit' => 'm2', 'unit_price' => 850.00, 'year' => 2026, 'work_group' => 'İnce İşler / Kaplama'],
        ];

        foreach ($samplePozs as $poz) {
            CsbPozLibrary::updateOrCreate(['poz_number' => $poz['poz_number']], $poz);
        }

        return redirect()->route('csb_poz.index')->with('success', 'Örnek ÇŞB Poz kütüphanesi verileri yüklendi.');
    }

    public function destroy(CsbPozLibrary $poz)
    {
        $poz->delete();
        return redirect()->route('csb_poz.index')->with('success', 'Poz kalemi silindi.');
    }
}
