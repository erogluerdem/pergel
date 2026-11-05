@php
    $editing = isset($material);
@endphp

<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label" for="subcontractor_contract_id">Sözleşme</label>
        <select name="subcontractor_contract_id" id="subcontractor_contract_id" class="form-select" {{ $editing ? 'disabled' : '' }} required>
            <option value="">Seçiniz</option>
            @foreach ($contracts as $contract)
                <option value="{{ $contract->id }}" @selected(old('subcontractor_contract_id', $material->subcontractor_contract_id ?? '') == $contract->id)>
                    {{ $contract->contract_name }} ({{ optional($contract->project)->name ?? 'Proje yok' }})
                </option>
            @endforeach
        </select>
        @if ($editing)
            <input type="hidden" name="subcontractor_contract_id" value="{{ $material->subcontractor_contract_id }}">
        @endif
    </div>
    <div class="col-md-3">
        <label class="form-label" for="delivery_date">Teslim Tarihi</label>
        <input type="date" name="delivery_date" id="delivery_date" class="form-control" value="{{ old('delivery_date', optional($material->delivery_date ?? null)->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label" for="status">Durum</label>
        <select name="status" id="status" class="form-select" required>
            <option value="draft" @selected(old('status', $material->status ?? 'draft') === 'draft')>Taslak</option>
            <option value="submitted" @selected(old('status', $material->status ?? 'draft') === 'submitted')>Gönderildi</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="material_name">Malzeme Adı</label>
        <input type="text" name="material_name" id="material_name" class="form-control" value="{{ old('material_name', $material->material_name ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="specification">Teknik Özellik</label>
        <input type="text" name="specification" id="specification" class="form-control" value="{{ old('specification', $material->specification ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label" for="quantity">Miktar</label>
        <input type="number" step="0.01" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', $material->quantity ?? '') }}" required>
    </div>
    <div class="col-md-2">
        <label class="form-label" for="unit">Birim</label>
        <input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit', $material->unit ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label" for="unit_price">Birim Fiyatı (TL)</label>
        <input type="number" step="0.01" name="unit_price" id="unit_price" class="form-control" value="{{ old('unit_price', $material->unit_price ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label" for="notes">Notlar</label>
        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $material->notes ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label" for="attachments">Ekler</label>
        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
        <small class="text-muted">PDF, JPG, PNG, DOC, DOCX, XLSX · Maks. 10MB</small>
        @if ($editing && ! empty($material->attachments))
            <div class="mt-3">
                <div class="fw-semibold mb-2">Mevcut Ekler</div>
                <ul class="list-unstyled mb-0">
                    @foreach ($material->attachments as $attachment)
                        <li class="small"><i class="fa-solid fa-paperclip me-2"></i>{{ $attachment['name'] ?? basename($attachment['path']) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
