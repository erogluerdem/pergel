<?php

namespace App\Http\Controllers;

use App\Models\ConsultantChange;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class ConsultantChangeController extends Controller
{
    public function index()
    {
        $changes = ConsultantChange::with(['customer', 'oldConsultant', 'newConsultant', 'changedBy'])
                                   ->orderBy('change_date', 'desc')
                                   ->paginate(15);
        return view('consultant_changes.index', compact('changes'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $consultants = User::orderBy('name')->get();
        return view('consultant_changes.create', compact('customers', 'consultants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'old_consultant_id' => 'required|exists:users,id',
            'new_consultant_id' => 'required|exists:users,id|different:old_consultant_id',
            'change_date' => 'required|date',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $validated['changed_by'] = auth()->id() ?? User::first()?->id ?? 1;

        $consultantChange = ConsultantChange::create($validated);

        return redirect()->route('consultant-changes.index')
                       ->with('success', 'Danışman değişikliği kaydı başarıyla oluşturuldu.');
    }

    public function show($id)
    {
        $consultantChange = ConsultantChange::with(['customer', 'oldConsultant', 'newConsultant', 'changedBy'])->findOrFail($id);
        return view('consultant_changes.show', compact('consultantChange'));
    }

    public function edit($id)
    {
        $consultantChange = ConsultantChange::findOrFail($id);
        $customers = Customer::orderBy('name')->get();
        $consultants = User::orderBy('name')->get();
        return view('consultant_changes.edit', compact('consultantChange', 'customers', 'consultants'));
    }

    public function update(Request $request, $id)
    {
        $consultantChange = ConsultantChange::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'old_consultant_id' => 'required|exists:users,id',
            'new_consultant_id' => 'required|exists:users,id|different:old_consultant_id',
            'change_date' => 'required|date',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $consultantChange->update($validated);

        return redirect()->route('consultant-changes.show', $consultantChange->id)
                       ->with('success', 'Danışman değişikliği başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $consultantChange = ConsultantChange::findOrFail($id);
        $consultantChange->delete();

        return redirect()->route('consultant-changes.index')
                       ->with('success', 'Danışman değişikliği kaydı silindi.');
    }

    public function byCustomer($customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        $changes = $customer->consultantChanges()
                           ->with(['oldConsultant', 'newConsultant', 'changedBy'])
                           ->orderBy('change_date', 'desc')
                           ->paginate(10);
        return view('consultant_changes.by_customer', compact('customer', 'changes'));
    }

    public function byConsultant($consultant_id)
    {
        $consultant = User::findOrFail($consultant_id);
        $changes = ConsultantChange::where('old_consultant_id', $consultant->id)
                                   ->orWhere('new_consultant_id', $consultant->id)
                                   ->with(['customer', 'oldConsultant', 'newConsultant'])
                                   ->orderBy('change_date', 'desc')
                                   ->paginate(10);
        return view('consultant_changes.by_consultant', compact('consultant', 'changes'));
    }
}
