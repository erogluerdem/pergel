@extends('layouts.master')

@section('title', 'Sözleşme Düzenle — ' . ($contract->title ?? $contract->name))

@push('styles')
<style>
    .dc-edit-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .dc-edit-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: dcEditGlow 4s linear infinite;
    }

    @keyframes dcEditGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .dc-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-dc-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-dc {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-dc:focus {
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
    <div class="dc-edit-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill dc-badge-pill">
                        <i class="ri-edit-box-fill text-warning fs-5"></i> HUKUK & İDARİ - DİJİTAL SÖZLEŞMELER
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Sözleşme Revizyonu
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Sözleşme Düzenle — {{ $contract->title ?? $contract->name }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Sözleşme metnini, taraf firma bilgilerini, tutarı ve yürürlük tarihlerini güncelleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('digital-contracts.show', $contract) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-eye-line me-1"></i>Detaylara Dön
                    </a>
                    <a href="{{ route('digital-contracts.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Sözleşmeler Listesi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-dc-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-pencil-fill text-warning fs-5"></i>Dijital Sözleşme Güncelleme Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('digital-contracts.update', $contract) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-3.5 rounded-3 border mb-4 d-flex align-items-center gap-3" style="background: #f8fafc; border-left: 4px solid #0f172a !important;">
                    <i class="ri-building-line fs-3 text-primary"></i>
                    <div>
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">İlişkili Proje Bilgisi</small>
                        <strong class="text-dark fs-6 fw-extrabold">🏗️ {{ $contract->project?->name ?? 'Bağımsız Proje' }}</strong>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Title -->
                    <div class="col-12">
                        <label for="title" class="form-label-custom">Sözleşme Başlığı <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control custom-input-dc @error('title') is-invalid @enderror" 
                               value="{{ old('title', $contract->title ?? $contract->name) }}" required>
                        @error('title')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Company -->
                    <div class="col-md-6">
                        <label for="company" class="form-label-custom">Şirket / Taraf Firma Unvanı</label>
                        <input type="text" name="company" id="company" class="form-control custom-input-dc" 
                               value="{{ old('company', $contract->company) }}">
                    </div>

                    <!-- Value -->
                    <div class="col-md-6">
                        <label for="value" class="form-label-custom">Sözleşme Tutarı (₺)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="number" step="0.01" name="value" id="value" class="form-control custom-input-dc" 
                                   value="{{ old('value', $contract->value) }}">
                        </div>
                    </div>

                    <!-- Effective Date -->
                    <div class="col-md-4">
                        <label for="effective_date" class="form-label-custom">Yürürlük Başlangıç Tarihi <span class="text-danger">*</span></label>
                        <input type="date" name="effective_date" id="effective_date" class="form-control custom-input-dc" 
                               value="{{ old('effective_date', $contract->effective_date?->format('Y-m-d')) }}" required>
                    </div>

                    <!-- Expiration Date -->
                    <div class="col-md-4">
                        <label for="expiration_date" class="form-label-custom">Bitiş / Fesih Tarihi</label>
                        <input type="date" name="expiration_date" id="expiration_date" class="form-control custom-input-dc" 
                               value="{{ old('expiration_date', $contract->expiration_date?->format('Y-m-d')) }}">
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label for="status" class="form-label-custom">Sözleşme Durumu <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select custom-input-dc" required>
                            <option value="active" @selected(old('status', $contract->status) == 'active')>🟢 Aktif / Yürürlükte</option>
                            <option value="draft" @selected(old('status', $contract->status) == 'draft')>🟡 Taslak / Onay Bekliyor</option>
                            <option value="expired" @selected(old('status', $contract->status) == 'expired')>⚪ Süresi Dolmuş</option>
                            <option value="cancelled" @selected(old('status', $contract->status) == 'cancelled')>🔴 İptal Edildi</option>
                        </select>
                    </div>

                    <!-- Content -->
                    <div class="col-12">
                        <label for="content" class="form-label-custom">Sözleşme İçeriği ve Şartname Metni <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" class="form-control custom-input-dc @error('content') is-invalid @enderror" rows="8" required>{{ old('content', $contract->content) }}</textarea>
                        @error('content')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('digital-contracts.show', $contract) }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i>Değişiklikleri Kaydet & Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
