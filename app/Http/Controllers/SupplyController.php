<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\VendorReview;
use App\Models\VendorDocument;

class SupplyController extends Controller
{
    public function vendors(Request $request)
    {
        $query = Vendor::query();

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where('name', 'like', "%{$q}%")->orWhere('tax_number', 'like', "%{$q}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $vendors = $query->orderBy('name')->paginate(20);

        // KPIs
        $kpis = [
            'total' => Vendor::count(),
            'approved' => Vendor::where('status','approved')->count(),
            'pending' => Vendor::where('status','pending')->count(),
            'low_performance' => Vendor::where('performance_score','<',3)->count(),
        ];

        return view('supply.vendors.index', compact('vendors','kpis'));
    }

    public function show(Vendor $vendor)
    {
        $reviews = VendorReview::where('vendor_id', $vendor->id)->orderBy('created_at','desc')->get();
        $documents = VendorDocument::where('vendor_id',$vendor->id)->orderBy('created_at','desc')->get();
        return view('supply.vendors.show', compact('vendor','reviews','documents'));
    }

    public function create()
    {
        $statusOptions = [
            'approved' => 'Onaylı',
            'pending' => 'Onay Bekliyor',
            'conditional' => 'Şartlı',
            'blacklisted' => 'Kara Liste',
        ];

        return view('finance.accounts.suppliers.create', compact('statusOptions'));
    }

    public function uploadDocument(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'file' => 'required|file|max:5120',
            'type' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
        ]);

        $path = $request->file('file')->store('vendor_documents');

        VendorDocument::create([
            'vendor_id' => $vendor->id,
            'type' => $data['type'] ?? null,
            'file_path' => $path,
            'expiry_date' => $data['expiry_date'] ?? null,
        ]);

    return redirect()->route('finance.accounts.suppliers.show', $vendor->id)->with('success','Doküman yüklendi');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'specialty' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
            'performance_score' => 'nullable|numeric|min:0|max:5',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'iban' => 'nullable|string|max:34',
        ]);

        $vendor = Vendor::create($data);

    return redirect()->route('finance.accounts.suppliers')->with('success','Tedarikçi başarıyla eklendi');
    }

    // Edit form for a vendor
    public function edit(Vendor $vendor)
    {
        return view('supply.vendors.edit', compact('vendor'));
    }

    // Update vendor
    public function update(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'specialty' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
            'performance_score' => 'nullable|numeric|min:0|max:5',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $vendor->update($data);

        return redirect()->route('finance.accounts.suppliers.show', $vendor->id)->with('success', 'Tedarikçi güncellendi');
    }

    // Destroy vendor
    public function destroy(Request $request, Vendor $vendor)
    {
        // If permanent flag present, force delete
        if ($request->input('permanent')) {
            if (method_exists($vendor, 'forceDelete')) {
                $vendor->forceDelete();
                return redirect()->route('finance.accounts.suppliers')->with('success', 'Tedarikçi kalıcı olarak silindi');
            }
        }

        // Soft delete
        if (method_exists($vendor, 'delete')) {
            $vendor->delete();
            return redirect()->route('finance.accounts.suppliers')->with('success', 'Tedarikçi geri dönüşüm kutusuna taşındı');
        }

        // fallback hard delete
    $vendor->forceDelete();
    return redirect()->route('finance.accounts.suppliers')->with('success', 'Tedarikçi silindi');
    }

    // Show soft-deleted vendors (recycle bin)
    public function recycle(Request $request)
    {
        $query = Vendor::onlyTrashed();

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where('name', 'like', "%{$q}%");
        }

        $vendors = $query->orderBy('deleted_at', 'desc')->paginate(20);

        return view('supply.vendors.recycle', compact('vendors'));
    }

    // Restore a soft-deleted vendor
    public function restore($id)
    {
        $vendor = Vendor::withTrashed()->findOrFail($id);
        if ($vendor->trashed()) {
            $vendor->restore();
            return redirect()->route('finance.accounts.suppliers.recycle')->with('success', 'Tedarikçi geri yüklendi');
        }
    return redirect()->route('finance.accounts.suppliers')->with('info', 'Tedarikçi zaten aktif');
    }

    public function apiList(Request $request)
    {
        $query = Vendor::query();
        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where('name', 'like', "%{$q}%");
        }
        return response()->json($query->limit(50)->get());
    }

    public function apiStats(Request $request)
    {
        // return specialty distribution
        $rows = Vendor::select('specialty')->get()->pluck('specialty')->filter()->flatMap(function($s){
            return array_map('trim', explode(',', $s));
        })->countBy()->sortDesc();

        return response()->json($rows);
    }

    // Reviews
    public function storeReview(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'quality' => 'required|integer|min:1|max:5',
            'schedule' => 'required|integer|min:1|max:5',
            'safety' => 'required|integer|min:1|max:5',
            'communication' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
            'project_id' => 'nullable|integer',
        ]);

        $data['vendor_id'] = $vendor->id;
        $data['created_by'] = auth()->id();

        VendorReview::create($data);

        // recompute simple average performance
        $this->recomputeVendorScore($vendor);

    return redirect()->route('finance.accounts.suppliers.show', $vendor->id)->with('success','Değerlendirme kaydedildi');
    }

    protected function recomputeVendorScore(Vendor $vendor)
    {
        $reviews = VendorReview::where('vendor_id',$vendor->id)->get();
        if ($reviews->isEmpty()) return;

        $avgQuality = $reviews->avg('quality');
        $avgSchedule = $reviews->avg('schedule');
        $avgSafety = $reviews->avg('safety');
        $avgCommunication = $reviews->avg('communication');

        // Weighted average (equal weight for now)
        $score = ($avgQuality + $avgSchedule + $avgSafety + $avgCommunication) / 4;
        $vendor->performance_score = round($score,1);
        $vendor->save();
    }
}
