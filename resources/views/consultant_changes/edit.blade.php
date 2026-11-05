@extends('layouts.master')

@section('title', 'Danışman Değişikliği Düzenle — ' . ($consultantChange->customer->name ?? 'Müşteri'))

@push('styles')
<style>
    .cc-edit-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .cc-edit-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: ccEditGlow 4s linear infinite;
    }

    @keyframes ccEditGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .cc-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-cc-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-cc {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-cc:focus {
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
    <div class="cc-edit-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill cc-badge-pill">
                        <i class="ri-user-shared-fill text-warning fs-5"></i> CRM & PORTFÖY DEVRİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Danışman Değişikliği Revizyonu
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Danışman Değişikliği Düzenle — {{ $consultantChange->customer->name ?? 'Müşteri' }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Kayıtlı portföy devir bilgilerini, tarihlerini ve devir gerekçesini güncelleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('consultant-changes.show', $consultantChange->id) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-eye-line me-1"></i>Detaylara Dön
                    </a>
                    <a href="{{ route('consultant-changes.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Tüm Değişiklikler
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-cc-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-edit-fill text-warning fs-5"></i>Danışman Devir Güncelleme Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('consultant-changes.update', $consultantChange->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4 mb-3">
                    <!-- Customer Select -->
                    <div class="col-md-6">
                        <label for="customer_id" class="form-label-custom">Devredilecek Müşteri <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-cc @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                            <option value="">-- Müşteri Seçiniz --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $consultantChange->customer_id) == $customer->id ? 'selected' : '' }}>
                                    👤 {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Change Date -->
                    <div class="col-md-6">
                        <label for="change_date" class="form-label-custom">Değişiklik Tarihi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control custom-input-cc @error('change_date') is-invalid @enderror" 
                               id="change_date" name="change_date" 
                               value="{{ old('change_date', $consultantChange->change_date ? $consultantChange->change_date->format('Y-m-d') : '') }}" required>
                        @error('change_date')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-4 mb-3">
                    <!-- Old Consultant -->
                    <div class="col-md-6">
                        <label for="old_consultant_id" class="form-label-custom">Önceki Danışman (Eski Temsilci) <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-cc @error('old_consultant_id') is-invalid @enderror" id="old_consultant_id" name="old_consultant_id" required>
                            <option value="">-- Eski Danışmanı Seçiniz --</option>
                            @foreach ($consultants as $consultant)
                                <option value="{{ $consultant->id }}" {{ old('old_consultant_id', $consultantChange->old_consultant_id) == $consultant->id ? 'selected' : '' }}>
                                    🔴 {{ $consultant->name }} ({{ $consultant->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('old_consultant_id')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- New Consultant -->
                    <div class="col-md-6">
                        <label for="new_consultant_id" class="form-label-custom">Atanacak Danışman (Yeni Temsilci) <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-cc @error('new_consultant_id') is-invalid @enderror" id="new_consultant_id" name="new_consultant_id" required>
                            <option value="">-- Yeni Danışmanı Seçiniz --</option>
                            @foreach ($consultants as $consultant)
                                <option value="{{ $consultant->id }}" {{ old('new_consultant_id', $consultantChange->new_consultant_id) == $consultant->id ? 'selected' : '' }}>
                                    🟢 {{ $consultant->name }} ({{ $consultant->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('new_consultant_id')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Reason -->
                    <div class="col-12">
                        <label for="reason" class="form-label-custom">Değişiklik Gerekçesi / Nedeni <span class="text-danger">*</span></label>
                        <input type="text" class="form-control custom-input-cc @error('reason') is-invalid @enderror" 
                               id="reason" name="reason" placeholder="Örn: Müşteri talebi, Bölgesel rotasyon, Temsilci ayrılışı..." 
                               value="{{ old('reason', $consultantChange->reason) }}" required>
                        @error('reason')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label for="notes" class="form-label-custom">Detaylı Açıklama & Notlar</label>
                        <textarea class="form-control custom-input-cc" id="notes" name="notes" rows="4" 
                                  placeholder="Devir sürecine ilişkin detaylı açıklamalar ve devredilen hesap bilgileri...">{{ old('notes', $consultantChange->notes) }}</textarea>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('consultant-changes.show', $consultantChange->id) }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
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
