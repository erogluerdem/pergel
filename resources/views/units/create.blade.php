@extends('layouts.master')

@section('title', 'Yeni Bağımsız Bölüm Oluştur')

@push('styles')
<style>
    .unit-create-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .unit-create-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: unitCreateGlow 4s linear infinite;
    }

    @keyframes unitCreateGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .unit-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-unit-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-unit {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-unit:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .form-label-custom {
        font-weight: 800 !important;
        color: #0f172a !important;
        font-size: 0.88rem !important;
        margin-bottom: 6px !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="unit-create-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill unit-badge-pill">
                        <i class="ri-building-line text-warning fs-5"></i> CRM & BAĞIMSIZ BÖLÜM PORTALI
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Yeni Birim Tanımı
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Yeni Bağımsız Bölüm Tanımla</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Proje seçimi, mimari detaylar ve satış fiyatlandırma parametrelerini girin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('units.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Portföy Listesine Dön
                </a>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-unit-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-add-circle-fill text-warning fs-5"></i>Bağımsız Bölüm Kayıt Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('units.store') }}" method="POST">
                @csrf

                <!-- SECTION 1: ARCHITECTURAL DETAILS -->
                <h6 class="fw-extrabold text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2">
                    <i class="ri-building-4-fill text-warning fs-5"></i>1. Temel Mimari Özellikler
                </h6>

                <div class="row g-4 mb-4">
                    <!-- Project -->
                    <div class="col-md-6">
                        <label for="project_id" class="form-label-custom">Bağlı Proje <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-unit @error('project_id') is-invalid @enderror" id="project_id" name="project_id" required>
                            <option value="">-- Proje Seçiniz --</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    🏗️ {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Unit No -->
                    <div class="col-md-6">
                        <label for="unit_no" class="form-label-custom">Birim No / Kodu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control custom-input-unit @error('unit_no') is-invalid @enderror" id="unit_no" name="unit_no" value="{{ old('unit_no') }}" placeholder="Örn: A-102 veya PH-2501" required>
                        @error('unit_no')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Block & Floor -->
                    <div class="col-md-3">
                        <label for="block" class="form-label-custom">Blok</label>
                        <input type="text" class="form-control custom-input-unit @error('block') is-invalid @enderror" id="block" name="block" value="{{ old('block') }}" placeholder="Örn: A Blok">
                        @error('block')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="floor" class="form-label-custom">Kat</label>
                        <input type="number" class="form-control custom-input-unit @error('floor') is-invalid @enderror" id="floor" name="floor" value="{{ old('floor') }}" placeholder="Örn: 10">
                        @error('floor')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Unit Type -->
                    <div class="col-md-6">
                        <label for="unit_type" class="form-label-custom">Birim Tipi / Formatı <span class="text-danger">*</span></label>
                        <input type="text" class="form-control custom-input-unit @error('unit_type') is-invalid @enderror" id="unit_type" name="unit_type" value="{{ old('unit_type') }}" placeholder="Örn: 3+1 Konut, Ticari Ofis, Penthouse" required>
                        @error('unit_type')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Area, Rooms, Baths -->
                    <div class="col-md-4">
                        <label for="area" class="form-label-custom">Net Alan (m²) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control custom-input-unit @error('area') is-invalid @enderror" id="area" name="area" step="0.01" value="{{ old('area') }}" placeholder="145.50" required>
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">m²</span>
                        </div>
                        @error('area')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="room_count" class="form-label-custom">Oda Sayısı <span class="text-danger">*</span></label>
                        <input type="number" class="form-control custom-input-unit @error('room_count') is-invalid @enderror" id="room_count" name="room_count" value="{{ old('room_count', '3') }}" required>
                        @error('room_count')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="bathroom_count" class="form-label-custom">Banyo Sayısı <span class="text-danger">*</span></label>
                        <input type="number" class="form-control custom-input-unit @error('bathroom_count') is-invalid @enderror" id="bathroom_count" name="bathroom_count" value="{{ old('bathroom_count', '2') }}" required>
                        @error('bathroom_count')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- SECTION 2: FINANCIAL DETAILS -->
                <h6 class="fw-extrabold text-dark border-bottom pb-2 mb-3 pt-3 d-flex align-items-center gap-2">
                    <i class="ri-money-dollar-circle-fill text-success fs-5"></i>2. Fiyatlandırma & Satış Şartları
                </h6>

                <div class="row g-4 mb-4">
                    <!-- List Price -->
                    <div class="col-md-6">
                        <label for="list_price" class="form-label-custom">Liste Fiyatı <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="number" class="form-control custom-input-unit @error('list_price') is-invalid @enderror" id="list_price" name="list_price" step="0.01" value="{{ old('list_price') }}" placeholder="12500000" required>
                        </div>
                        @error('list_price')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Selling Price -->
                    <div class="col-md-6">
                        <label for="selling_price" class="form-label-custom">Hedef Satış Fiyatı <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="number" class="form-control custom-input-unit @error('selling_price') is-invalid @enderror" id="selling_price" name="selling_price" step="0.01" value="{{ old('selling_price') }}" placeholder="11200000" required>
                        </div>
                        @error('selling_price')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Max Discount Rate -->
                    <div class="col-md-4">
                        <label for="max_discount_rate" class="form-label-custom">Max İndirim Oranı (%) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control custom-input-unit @error('max_discount_rate') is-invalid @enderror" id="max_discount_rate" name="max_discount_rate" step="0.01" value="{{ old('max_discount_rate', '10') }}" required>
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">%</span>
                        </div>
                        @error('max_discount_rate')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Currency -->
                    <div class="col-md-4">
                        <label for="currency" class="form-label-custom">Para Birimi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control custom-input-unit @error('currency') is-invalid @enderror" id="currency" name="currency" maxlength="3" value="{{ old('currency', 'TRY') }}" required>
                        @error('currency')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label for="status" class="form-label-custom">Müsaitlik Durumu <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-unit @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>🟢 Müsait (Satışta)</option>
                            <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>🟡 Opsiyonlu / Rezerve</option>
                            <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>🔴 Satıldı</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>🔧 Bakımda / Revizyonda</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label-custom">Birim Açıklaması & Özel Notlar</label>
                        <textarea class="form-control custom-input-unit @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Örn: Güney-Doğu cepheli, kapalı otopark tahsisli, ankastre set hediyeli.">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('units.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i>Birimi Kaydet & Yayınla
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
