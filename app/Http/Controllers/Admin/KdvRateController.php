<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KdvRate;

class KdvRateController extends Controller
{
    public function index()
    {
        $rates = KdvRate::orderBy('sort')->get();
        return view('admin.kdv.index', compact('rates'));
    }

    public function create()
    {
        return view('admin.kdv.form', ['rate' => new KdvRate]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rate' => 'required|integer|min:0|max:100',
            'label' => 'nullable|string|max:255',
            'sort' => 'nullable|integer',
            'active' => 'nullable|boolean'
        ]);
        $data['active'] = $request->has('active');
        KdvRate::create($data);
        return redirect()->route('admin.kdv.index')->with('success','KDV oranı kaydedildi');
    }

    public function edit(KdvRate $kdvRate)
    {
        return view('admin.kdv.form', ['rate' => $kdvRate]);
    }

    public function update(Request $request, KdvRate $kdvRate)
    {
        $data = $request->validate([
            'rate' => 'required|integer|min:0|max:100',
            'label' => 'nullable|string|max:255',
            'sort' => 'nullable|integer',
            'active' => 'nullable|boolean'
        ]);
        $data['active'] = $request->has('active');
        $kdvRate->update($data);
        return redirect()->route('admin.kdv.index')->with('success','KDV oranı güncellendi');
    }

    public function destroy(KdvRate $kdvRate)
    {
        $kdvRate->delete();
        return redirect()->route('admin.kdv.index')->with('success','KDV oranı silindi');
    }
}
