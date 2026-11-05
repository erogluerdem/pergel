@extends('layouts.master')

@section('title', 'Opsiyon Düzenle')
@section('topbar-title', 'Opsiyon Düzenle')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light border-0 py-3">
                <h5 class="mb-0 fw-bold">{{ $salesOption->name }} - Düzenle</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sales-options.update', $salesOption) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Opsiyon Adı <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $salesOption->name) }}" required>
                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Açıklama</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $salesOption->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Durum <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="draft" @selected(old('status', $salesOption->status) == 'draft')>Taslak</option>
                            <option value="active" @selected(old('status', $salesOption->status) == 'active')>Aktif</option>
                            <option value="archived" @selected(old('status', $salesOption->status) == 'archived')>Arşiv</option>
                            <option value="expired" @selected(old('status', $salesOption->status) == 'expired')>Süresi Dolmuş</option>
                        </select>
                        @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="discount_percentage" class="form-label">İndirim Yüzdesi (%)</label>
                            <input type="number" name="discount_percentage" id="discount_percentage" class="form-control" 
                                   value="{{ old('discount_percentage', $salesOption->discount_percentage) }}" step="0.01" min="0" max="100">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="valid_from" class="form-label">Geçerlilik Başlangıcı <span class="text-danger">*</span></label>
                            <input type="date" name="valid_from" id="valid_from" class="form-control @error('valid_from') is-invalid @enderror" 
                                   value="{{ old('valid_from', $salesOption->valid_from?->format('Y-m-d')) }}" required>
                            @error('valid_from')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="valid_until" class="form-label">Geçerlilik Sonu <span class="text-danger">*</span></label>
                            <input type="date" name="valid_until" id="valid_until" class="form-control @error('valid_until') is-invalid @enderror" 
                                   value="{{ old('valid_until', $salesOption->valid_until?->format('Y-m-d')) }}" required>
                            @error('valid_until')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="extra_features" class="form-label">Ek Özellikler</label>
                        <textarea name="extra_features" id="extra_features" class="form-control" rows="2">{{ old('extra_features', $salesOption->extra_features) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-sync me-2"></i>Güncelle
                        </button>
                        <a href="{{ route('sales-options.show', $salesOption) }}" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
