@php
    $editing = isset($service);
@endphp

<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label" for="subcontractor_contract_id">Sözleşme</label>
        <select name="subcontractor_contract_id" id="subcontractor_contract_id" class="form-select" {{ $editing ? 'disabled' : '' }} required>
            <option value="">Seçiniz</option>
            @foreach ($contracts as $contract)
                <option value="{{ $contract->id }}" @selected(old('subcontractor_contract_id', $service->subcontractor_contract_id ?? '') == $contract->id)>
                    {{ $contract->contract_name }} ({{ optional($contract->project)->name ?? 'Proje yok' }})
                </option>
            @endforeach
        </select>
        @if($editing)
            <input type="hidden" name="subcontractor_contract_id" value="{{ $service->subcontractor_contract_id }}">
        @endif
    </div>
    <div class="col-md-3">
        <label class="form-label" for="entry_date">Tarih</label>
        <input type="date" name="entry_date" id="entry_date" class="form-control" value="{{ old('entry_date', optional($service->entry_date ?? null)->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label" for="status">Durum</label>
        <select name="status" id="status" class="form-select" required>
            <option value="draft" @selected(old('status', $service->status ?? 'draft') === 'draft')>Taslak</option>
            <option value="submitted" @selected(old('status', $service->status ?? 'draft') === 'submitted')>Gönderildi</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="service_type">Hizmet Türü</label>
        <input type="text" name="service_type" id="service_type" class="form-control" value="{{ old('service_type', $service->service_type ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="work_description">Açıklama</label>
        <input type="text" name="work_description" id="work_description" class="form-control" value="{{ old('work_description', $service->work_description ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label" for="quantity">Miktar</label>
        <input type="number" step="0.01" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', $service->quantity ?? '') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label" for="unit">Birim</label>
        <input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit', $service->unit ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label" for="worked_hours">Toplam Saat</label>
        <input type="number" step="0.25" name="worked_hours" id="worked_hours" class="form-control" value="{{ old('worked_hours', $service->worked_hours ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label" for="crew_size">Ekip Sayısı</label>
        <input type="number" name="crew_size" id="crew_size" class="form-control" value="{{ old('crew_size', $service->crew_size ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label" for="notes">Notlar</label>
        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $service->notes ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label" for="attachments">Ekler</label>
        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
        <small class="text-muted">PDF, JPG, PNG, DOC, DOCX, XLSX · Maks. 10MB</small>
        @if ($editing && ! empty($service->attachments))
            <div class="mt-3">
                <div class="fw-semibold mb-2">Mevcut Ekler</div>
                <ul class="list-unstyled mb-0">
                    @foreach ($service->attachments as $attachment)
                        <li class="small"><i class="fa-solid fa-paperclip me-2"></i>{{ $attachment['name'] ?? basename($attachment['path']) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
