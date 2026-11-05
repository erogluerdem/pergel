@extends('layouts.master')

@section('title', 'Envanter Öğesini Düzenle — ' . ($item->name ?? 'Öğe'))
@section('topbar-title', 'Envanter - Düzenle')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="ri-edit-box-line text-warning me-2"></i>Envanter Öğesini Düzenle: {{ $item->name }}
                    </h5>
                    <a href="{{ route('inventory.show', $item->id) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Detaya Dön
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ Route::has('inventory.update') ? route('inventory.update', $item->id) : url('/inventory/' . $item->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Öğe Adı *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
                                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Envanter Kodu / SKU *</label>
                                <input type="text" name="code" class="form-control" value="{{ old('code', $item->code ?? $item->sku) }}" required>
                                @error('code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kategori *</label>
                                <input type="text" name="category" class="form-control" value="{{ old('category', $item->category) }}" required>
                                @error('category')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Konum / Depo *</label>
                                <input type="text" name="location" class="form-control" value="{{ old('location', $item->location) }}" required>
                                @error('location')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Miktar *</label>
                                <input type="number" name="quantity" class="form-control" min="0" value="{{ old('quantity', $item->quantity) }}" required>
                                @error('quantity')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Birim Fiyat (₺) *</label>
                                <input type="number" step="0.01" name="unit_price" class="form-control" min="0" value="{{ old('unit_price', $item->unit_price ?? $item->price) }}" required>
                                @error('unit_price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Durum *</label>
                                @php $currentStatus = old('status', $item->status); @endphp
                                <select name="status" class="form-select" required>
                                    <option value="Aktif" {{ in_array($currentStatus, ['Aktif', 'Active', 'active']) ? 'selected' : '' }}>Aktif</option>
                                    <option value="Bakımda" {{ $currentStatus === 'Bakımda' ? 'selected' : '' }}>Bakımda</option>
                                    <option value="Pasif" {{ in_array($currentStatus, ['Pasif', 'Inactive', 'inactive']) ? 'selected' : '' }}>Pasif</option>
                                </select>
                                @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Açıklama</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('inventory.index') }}" class="btn btn-light">İptal</a>
                            <button type="submit" class="btn btn-warning text-dark fw-semibold px-4">
                                <i class="ri-save-line me-1"></i>Güncelle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
