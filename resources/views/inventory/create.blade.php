@extends('layouts.master')

@section('title', 'Yeni Envanter Öğesi Ekle')
@section('topbar-title', 'Envanter - Yeni Öğe')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="ri-archive-line text-primary me-2"></i>Yeni Envanter Öğesi Oluştur
                    </h5>
                    <a href="{{ route('inventory.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Listeye Dön
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ Route::has('inventory.store') ? route('inventory.store') : url('/inventory') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Öğe Adı *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Örn: Kule Vinç Jeneratörü">
                                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Envanter Kodu / SKU *</label>
                                <input type="text" name="code" class="form-control" value="{{ old('code') }}" required placeholder="Örn: ENV-2026-001">
                                @error('code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kategori *</label>
                                <select name="category" class="form-select" required>
                                    <option value="">Seçiniz...</option>
                                    <option value="İnşaat Ekipmanı" {{ old('category') === 'İnşaat Ekipmanı' ? 'selected' : '' }}>İnşaat Ekipmanı</option>
                                    <option value="Teknoloji" {{ old('category') === 'Teknoloji' ? 'selected' : '' }}>Teknoloji</option>
                                    <option value="Mobilya" {{ old('category') === 'Mobilya' ? 'selected' : '' }}>Mobilya</option>
                                    <option value="Sarf Malzeme" {{ old('category') === 'Sarf Malzeme' ? 'selected' : '' }}>Sarf Malzeme</option>
                                    <option value="Diğer" {{ old('category') === 'Diğer' ? 'selected' : '' }}>Diğer</option>
                                </select>
                                @error('category')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Konum / Depo *</label>
                                <input type="text" name="location" class="form-control" value="{{ old('location') }}" required placeholder="Örn: Merkez Şantiye Ambarı">
                                @error('location')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Miktar *</label>
                                <input type="number" name="quantity" class="form-control" min="0" value="{{ old('quantity', 1) }}" required>
                                @error('quantity')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Birim Fiyat (₺) *</label>
                                <input type="number" step="0.01" name="unit_price" class="form-control" min="0" value="{{ old('unit_price', 0) }}" required>
                                @error('unit_price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Durum *</label>
                                <select name="status" class="form-select" required>
                                    <option value="Aktif" {{ old('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Bakımda" {{ old('status') === 'Bakımda' ? 'selected' : '' }}>Bakımda</option>
                                    <option value="Pasif" {{ old('status') === 'Pasif' ? 'selected' : '' }}>Pasif</option>
                                </select>
                                @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Açıklama</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Teknik özellikler ve stok notları...">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('inventory.index') }}" class="btn btn-light">İptal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="ri-save-line me-1"></i>Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
