<?php

namespace App\Http\Controllers;

use App\Models\CustomerMeeting;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerMeetingController extends Controller
{
    /**
     * Müşteri görüşmeleri listesi
     */
    public function index(Request $request)
    {
        $meetings = CustomerMeeting::with(['customer', 'user'])
            ->when($request->filled('customer_id'), fn($q) => $q->where('customer_id', $request->customer_id))
            ->when($request->filled('outcome'), fn($q) => $q->where('outcome', $request->outcome))
            ->when($request->filled('type'), fn($q) => $q->where('meeting_type', $request->type))
            ->orderBy('meeting_date', 'desc')
            ->paginate(20);

        $customers = Customer::orderBy('name')->get();
        $outcomes = ['interested' => 'İlgilendi', 'not_interested' => 'İlgilenmedi', 'pending' => 'Bekleme', 'converted_to_sale' => 'Satışa Dönüştü'];
        $types = ['call' => 'Telefon', 'visit' => 'Ziyaret', 'email' => 'E-Posta', 'online' => 'Online'];

        return view('customer_meetings.index', compact('meetings', 'customers', 'outcomes', 'types'));
    }

    /**
     * Müşteri görüşmesi oluştur
     */
    public function create(Request $request)
    {
        $customer = null;
        if ($request->has('customer_id')) {
            $customer = Customer::findOrFail($request->customer_id);
        }
        $customers = Customer::orderBy('name')->get();

        return view('customer_meetings.create', compact('customers', 'customer'));
    }

    /**
     * Müşteri görüşmesini kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subject' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'meeting_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'outcome' => 'required|in:interested,not_interested,pending,converted_to_sale',
            'potential_sale_amount' => 'nullable|numeric|min:0',
            'next_followup' => 'nullable|string',
            'next_followup_date' => 'nullable|date',
            'meeting_type' => 'required|in:call,visit,email,online',
        ]);

        $validated['user_id'] = auth()->id();

        CustomerMeeting::create($validated);

        return redirect()->route('customer_meetings.index')
            ->with('success', 'Müşteri görüşmesi başarıyla kaydedildi!');
    }

    /**
     * Müşteri görüşmesi detayları
     */
    public function show($id)
    {
        $meeting = CustomerMeeting::with(['customer', 'user'])->findOrFail($id);
        return view('customer_meetings.show', compact('meeting'));
    }

    /**
     * Müşteri görüşmesini düzenle
     */
    public function edit($id)
    {
        $meeting = CustomerMeeting::findOrFail($id);
        $customers = Customer::orderBy('name')->get();
        return view('customer_meetings.edit', compact('meeting', 'customers'));
    }

    /**
     * Müşteri görüşmesini güncelle
     */
    public function update(Request $request, $id)
    {
        $meeting = CustomerMeeting::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subject' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'meeting_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'outcome' => 'required|in:interested,not_interested,pending,converted_to_sale',
            'potential_sale_amount' => 'nullable|numeric|min:0',
            'next_followup' => 'nullable|string',
            'next_followup_date' => 'nullable|date',
            'meeting_type' => 'required|in:call,visit,email,online',
        ]);

        $meeting->update($validated);

        return redirect()->route('customer_meetings.show', $meeting->id)
            ->with('success', 'Görüşme başarıyla güncellendi!');
    }

    /**
     * Müşteri görüşmesini sil
     */
    public function destroy($id)
    {
        $meeting = CustomerMeeting::findOrFail($id);
        $meeting->delete();

        return redirect()->route('customer_meetings.index')
            ->with('success', 'Görüşme silinmiştir.');
    }

    /**
     * Müşteri başına görüşmeleri listele
     */
    public function byCustomer($id)
    {
        $customer = Customer::findOrFail($id);

        $meetings = $customer->meetings()
            ->orderBy('meeting_date', 'desc')
            ->paginate(20);

        return view('customer_meetings.by_customer', compact('customer', 'meetings'));
    }
}
