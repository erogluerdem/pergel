<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChangeOrder;
use Illuminate\Support\Facades\Auth;

class ChangeOrderController extends Controller
{
    public function index()
    {
        $orders = ChangeOrder::orderBy('created_at', 'desc')->paginate(50);
        return view('change_orders.index', compact('orders'));
    }

    public function create()
    {
        return view('change_orders.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'no' => 'nullable|string',
            'subject' => 'required|string',
            'reason' => 'nullable|string',
            'cost' => 'nullable|numeric',
        ]);
        $data['created_by'] = Auth::id();
        $co = ChangeOrder::create($data);
        return redirect()->route('change_orders.index')->with('success', 'Ek talep oluşturuldu');
    }

    public function approve(ChangeOrder $change_order)
    {
        $change_order->status = 'Onaylandı';
        $change_order->approved_at = now();
        $change_order->save();
        return redirect()->back()->with('success', 'Ek talep onaylandı');
    }
}
