<?php

namespace App\Http\Controllers\SubcontractorPortal;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubcontractorPortal\StoreServiceEntryRequest;
use App\Http\Requests\SubcontractorPortal\UpdateServiceEntryRequest;
use App\Models\SubcontractorContract;
use App\Models\SubcontractorServiceEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ServiceEntryController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::guard('subcontractor')->user();

        $query = $user->serviceEntries()->with(['project', 'contract'])->latest('entry_date');

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('contract_id')) {
            $contract = $this->resolveContract($user, (int) $request->get('contract_id'));
            $query->where('subcontractor_contract_id', $contract->id);
        }

        $entries = $query->paginate(15)->withQueryString();
        $contracts = $this->getAccessibleContracts($user);

        return view('subcontractor-portal.services.index', compact('entries', 'contracts'));
    }

    public function create(): View
    {
        $user = Auth::guard('subcontractor')->user();
        $contracts = $this->getAccessibleContracts($user);

        abort_if($contracts->isEmpty(), 403, __('Yetkili olduğunuz sözleşme bulunamadı.'));

        return view('subcontractor-portal.services.create', compact('contracts'));
    }

    public function store(StoreServiceEntryRequest $request): RedirectResponse
    {
        $user = Auth::guard('subcontractor')->user();
        $contract = $this->resolveContract($user, (int) $request->validated('subcontractor_contract_id'));

        $entry = SubcontractorServiceEntry::create([
            'subcontractor_contract_id' => $contract->id,
            'project_id' => $contract->project_id,
            'subcontractor_user_id' => $user->id,
            'entry_date' => $request->validated('entry_date'),
            'service_type' => $request->validated('service_type'),
            'work_description' => $request->validated('work_description'),
            'quantity' => $request->validated('quantity'),
            'unit' => $request->validated('unit'),
            'worked_hours' => $request->validated('worked_hours'),
            'crew_size' => $request->validated('crew_size'),
            'status' => $request->validated('status'),
            'notes' => $request->validated('notes'),
            'submitted_at' => $request->validated('status') === 'submitted' ? now() : null,
        ]);

        $attachments = $this->storeAttachments($request, $entry);
        if (! empty($attachments)) {
            $entry->update(['attachments' => $attachments]);
        }

        return redirect()
            ->route('subcontractor.services.index')
            ->with('success', __('Hizmet kaydı başarıyla oluşturuldu.'));
    }

    public function edit(SubcontractorServiceEntry $service): View
    {
        $user = Auth::guard('subcontractor')->user();
        $this->ensureEntryBelongsToUser($service, $user->id);

        $contracts = $this->getAccessibleContracts($user);

        return view('subcontractor-portal.services.edit', compact('service', 'contracts'));
    }

    public function update(UpdateServiceEntryRequest $request, SubcontractorServiceEntry $service): RedirectResponse
    {
        $user = Auth::guard('subcontractor')->user();
        $this->ensureEntryBelongsToUser($service, $user->id);

        if (! in_array($service->status, ['draft', 'rejected'], true) && $request->validated('status') === 'draft') {
            // allow reverting to draft only from draft/rejected states
            abort(403, __('Gönderilmiş kayıt taslağa geri alınamaz.'));
        }

        $attributes = $request->validated();

        $service->fill([
            'entry_date' => $attributes['entry_date'],
            'service_type' => $attributes['service_type'],
            'work_description' => $attributes['work_description'],
            'quantity' => $attributes['quantity'],
            'unit' => $attributes['unit'],
            'worked_hours' => $attributes['worked_hours'],
            'crew_size' => $attributes['crew_size'],
            'status' => $attributes['status'],
            'notes' => $attributes['notes'],
        ]);

        if ($attributes['status'] === 'submitted' && $service->submitted_at === null) {
            $service->submitted_at = now();
        }

        if ($attributes['status'] === 'draft') {
            $service->submitted_at = null;
        }

        $attachments = $this->storeAttachments($request, $service, append: true);
        if (! empty($attachments)) {
            $service->attachments = array_values(array_merge($service->attachments ?? [], $attachments));
        }

        $service->save();

        return redirect()
            ->route('subcontractor.services.index')
            ->with('success', __('Hizmet kaydı güncellendi.'));
    }

    private function getAccessibleContracts($user)
    {
        $contracts = $user->contracts()->with('project')->orderBy('contract_name')->get();

        if ($contracts->isEmpty()) {
            $contracts = SubcontractorContract::where('subcontractor_id', $user->subcontractor_id)
                ->with('project')
                ->orderBy('contract_name')
                ->get();
        }

        return $contracts;
    }

    private function resolveContract($user, int $contractId): SubcontractorContract
    {
        $contract = $this->getAccessibleContracts($user)->firstWhere('id', $contractId);

        abort_if(is_null($contract), 403, __('Bu sözleşmeye yönelik işlem yetkiniz bulunmuyor.'));

        return $contract;
    }

    private function ensureEntryBelongsToUser(SubcontractorServiceEntry $entry, int $userId): void
    {
        abort_unless($entry->subcontractor_user_id === $userId, 403, __('Bu kaydı görüntüleme yetkiniz yok.'));
    }

    private function storeAttachments(Request $request, SubcontractorServiceEntry $entry, bool $append = false): array
    {
        if (! $request->hasFile('attachments')) {
            return [];
        }

        $stored = [];
        foreach ($request->file('attachments') as $file) {
            if (! $file->isValid()) {
                continue;
            }

            $path = $file->store("subcontractor/service_entries/{$entry->id}", 'public');
            $stored[] = [
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'uploaded_at' => now()->toDateTimeString(),
            ];
        }

        return $stored;
    }
}
