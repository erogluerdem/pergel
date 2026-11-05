<?php

namespace App\Http\Controllers;

use App\Models\SalesReservation;
use App\Models\Unit;
use App\Models\Customer;
use Illuminate\Http\Request;

class SalesReservationController extends Controller
{
    public function index()
    {
        $reservations = SalesReservation::with('customer', 'unit', 'createdBy')->paginate(15);
        return view('sales_reservations.index', compact('reservations'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $units = Unit::where('status', 'available')->orderBy('unit_no')->get();
        return view('sales_reservations.create', compact('customers', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'unit_id' => 'required|exists:units,id',
            'option_no' => 'required|string|unique:sales_reservations',
            'option_date' => 'required|date',
            'expiry_date' => 'required|date|after:option_date',
            'option_price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,confirmed,expired,cancelled',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        SalesReservation::create($validated);

        return redirect()->route('sales-reservations.index')
                       ->with('success', 'Opsyon başarıyla oluşturuldu.');
    }

    public function show(SalesReservation $salesReservation)
    {
        $salesReservation->load('customer', 'unit', 'createdBy');
        return view('sales_reservations.show', compact('salesReservation'));
    }

    public function edit(SalesReservation $salesReservation)
    {
        $customers = Customer::orderBy('name')->get();
        $units = Unit::where('status', 'available')
                     ->orWhere('id', $salesReservation->unit_id)
                     ->orderBy('unit_no')
                     ->get();
        return view('sales_reservations.edit', compact('salesReservation', 'customers', 'units'));
    }

    public function update(Request $request, SalesReservation $salesReservation)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'unit_id' => 'required|exists:units,id',
            'option_no' => 'required|string|unique:sales_reservations,option_no,' . $salesReservation->id,
            'option_date' => 'required|date',
            'expiry_date' => 'required|date|after:option_date',
            'option_price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,confirmed,expired,cancelled',
            'notes' => 'nullable|string',
        ]);

        $salesReservation->update($validated);

        return redirect()->route('sales-reservations.show', $salesReservation)
                       ->with('success', 'Opsyon başarıyla güncellendi.');
    }

    public function destroy(SalesReservation $salesReservation)
    {
        $salesReservation->delete();
        return redirect()->route('sales-reservations.index')
                       ->with('success', 'Opsyon başarıyla silindi.');
    }

    public function byCustomer(Customer $customer)
    {
        $reservations = $customer->salesReservations()->with('unit', 'createdBy')->paginate(10);
        return view('sales_reservations.by_customer', compact('customer', 'reservations'));
    }
}
