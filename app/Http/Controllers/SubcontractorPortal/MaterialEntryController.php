<?php

namespace App\Http\Controllers\SubcontractorPortal;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubcontractorPortal\StoreMaterialEntryRequest;
use App\Http\Requests\SubcontractorPortal\UpdateMaterialEntryRequest;
use App\Models\SubcontractorContract;
use App\Models\SubcontractorMaterialEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MaterialEntryController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::guard('subcontractor')->user();

        $query = $user->materialEntries()->with(['project', 'contract'])->latest('delivery_date');

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('contract_id')) {
            $contract = $this->resolveContract($user, (int) $request->get('contract_id'));
            $query->where('subcontractor_contract_id', $contract->id);
        }

        $entries = $query->paginate(15)->withQueryString();
        $contracts = $this->getAccessibleContracts($user);

        return view('subcontractor-portal.materials.index', compact('entries', 'contracts'));
    }

    public function create(): View
    {
        $user = Auth::guard('subcontractor')->user();
        $contracts = $this->getAccessibleContracts($user);

        abort_if($contracts->isEmpty(), 403, __('Yetkili olduğunuz sözleşme bulunamadı.'));

        return view('subcontractor-portal.materials.create', compact('contracts'));
    }

    public function store(StoreMaterialEntryRequest $request): RedirectResponse
    {
        $user = Auth::guard('subcontractor')->user();
        $contract = $this->resolveContract($user, (int) $request->validated('subcontractor_contract_id'));

        $entry = SubcontractorMaterialEntry::create([
            'subcontractor_contract_id' => $contract->id,
            'project_id' => $contract->project_id,
            'subcontractor_user_id' => $user->id,
            'delivery_date' => $request->validated('delivery_date'),
            'material_name' => $request->validated('material_name'),
            'specification' => $request->validated('specification'),
            'quantity' => $request->validated('quantity'),
            'unit' => $request->validated('unit'),
            'unit_price' => $request->validated('unit_price'),
            'status' => $request->validated('status'),
            'notes' => $request->validated('notes'),
            'submitted_at' => $request->validated('status') === 'submitted' ? now() : null,
        ]);

        $attachments = $this->storeAttachments($request, $entry);
        if (! empty($attachments)) {
            $entry->update(['attachments' => $attachments]);
        }

        return redirect()
            ->route('subcontractor.materials.index')
            ->with('success', __('Malzeme girişi başarıyla kaydedildi.'));
    }

    public function edit(SubcontractorMaterialEntry $material): View
    {
        $user = Auth::guard('subcontractor')->user();
        $this->ensureEntryBelongsToUser($material, $user->id);

        $contracts = $this->getAccessibleContracts($user);

        return view('subcontractor-portal.materials.edit', compact('material', 'contracts'));
    }

    public function update(UpdateMaterialEntryRequest $request, SubcontractorMaterialEntry $material): RedirectResponse
    {
        $user = Auth::guard('subcontractor')->user();
        $this->ensureEntryBelongsToUser($material, $user->id);

        if (! in_array($material->status, ['draft', 'rejected'], true) && $request->validated('status') === 'draft') {
            abort(403, __('Gönderilmiş kayıt taslağa geri alınamaz.'));
        }

        $attributes = $request->validated();

        $material->fill([
            'delivery_date' => $attributes['delivery_date'],
            'material_name' => $attributes['material_name'],
            'specification' => $attributes['specification'],
            'quantity' => $attributes['quantity'],
            'unit' => $attributes['unit'],
            'unit_price' => $attributes['unit_price'],
            'status' => $attributes['status'],
            'notes' => $attributes['notes'],
        ]);

        if ($attributes['status'] === 'submitted' && $material->submitted_at === null) {
            $material->submitted_at = now();
        }

        if ($attributes['status'] === 'draft') {
            $material->submitted_at = null;
        }

        $attachments = $this->storeAttachments($request, $material, append: true);
        if (! empty($attachments)) {
            $material->attachments = array_values(array_merge($material->attachments ?? [], $attachments));
        }

        $material->save();

        return redirect()
            ->route('subcontractor.materials.index')
            ->with('success', __('Malzeme girişi güncellendi.'));
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

    private function ensureEntryBelongsToUser(SubcontractorMaterialEntry $entry, int $userId): void
    {
        abort_unless($entry->subcontractor_user_id === $userId, 403, __('Bu kaydı görüntüleme yetkiniz yok.'));
    }

    private function storeAttachments(Request $request, SubcontractorMaterialEntry $entry, bool $append = false): array
    {
        if (! $request->hasFile('attachments')) {
            return [];
        }

        $stored = [];
        foreach ($request->file('attachments') as $file) {
            if (! $file->isValid()) {
                continue;
            }

            $path = $file->store("subcontractor/material_entries/{$entry->id}", 'public');
            $stored[] = [
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'uploaded_at' => now()->toDateTimeString(),
            ];
        }

        return $stored;
    }
}
