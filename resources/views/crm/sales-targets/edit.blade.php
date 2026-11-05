@extends('layouts.master')

@section('title', 'Satış Hedefini Düzenle - ' . ($salesTarget->user->name ?? 'Temsilci'))

@push('styles')
<style>
    .st-edit-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .st-edit-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: stEditGlow 4s linear infinite;
    }

    @keyframes stEditGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .st-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-st-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-st {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-st:focus {
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
    <div class="st-edit-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill st-badge-pill">
                        <i class="ri-pencil-fill text-warning fs-5"></i> CRM & SATIŞ TARGET DÜZENLEME
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Hedef #{{ $salesTarget->id }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">{{ $salesTarget->user->name ?? 'Temsilci' }} — Satış Hedefini Düzenle</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Dönem tarihlerini, hedeflenen satış bütçesini/adedini ve gerçekleşme durumunu güncelleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('sales-targets.show', $salesTarget) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold">
                        <i class="ri-eye-line me-1"></i>Detaya Dön
                    </a>
                    <a href="{{ route('sales-targets.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Hedef Listesi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-st-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-edit-2-line text-warning fs-5"></i>Hedef Düzenleme Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('sales-targets.update', $salesTarget) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Target Type (Read-only) -->
                    <div class="col-md-6">
                        <label class="form-label-custom">Hedef Türü (Kilitli)</label>
                        <input type="text" class="form-control custom-input-st bg-light fw-extrabold" 
                            value="{{ $salesTarget->target_type === 'revenue' ? '💰 Ciro Hedefi (₺)' : '🎯 Satış Adedi (Birim)' }}" readonly disabled />
                    </div>

                    <!-- Target Amount -->
                    <div class="col-md-6">
                        <label for="target_amount" class="form-label-custom">Hedef Miktar / Tutar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">TL / Adet</span>
                            <input type="number" name="target_amount" id="target_amount" class="form-control custom-input-st @error('target_amount') is-invalid @enderror" 
                                step="0.01" value="{{ old('target_amount', $salesTarget->target_amount) }}" required>
                        </div>
                        @error('target_amount')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dates -->
                    <div class="col-md-6">
                        <label for="period_start" class="form-label-custom">Dönem Başlangıcı <span class="text-danger">*</span></label>
                        <input type="date" name="period_start" id="period_start" class="form-control custom-input-st @error('period_start') is-invalid @enderror" 
                            value="{{ old('period_start', $salesTarget->period_start ? $salesTarget->period_start->format('Y-m-d') : '') }}" required>
                        @error('period_start')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="period_end" class="form-label-custom">Dönem Bitişi <span class="text-danger">*</span></label>
                        <input type="date" name="period_end" id="period_end" class="form-control custom-input-st @error('period_end') is-invalid @enderror" 
                            value="{{ old('period_end', $salesTarget->period_end ? $salesTarget->period_end->format('Y-m-d') : '') }}" required>
                        @error('period_end')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-12">
                        <label for="status" class="form-label-custom">Hedef Durumu <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select custom-input-st @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', $salesTarget->status) === 'active' ? 'selected' : '' }}>🟢 Aktif (Devam Ediyor)</option>
                            <option value="completed" {{ old('status', $salesTarget->status) === 'completed' ? 'selected' : '' }}>🔵 Tamamlandı (Başarıyla Kapandı)</option>
                            <option value="abandoned" {{ old('status', $salesTarget->status) === 'abandoned' ? 'selected' : '' }}>🔴 Terk Edildi (İptal)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label-custom">Açıklama / Notlar</label>
                        <textarea name="description" id="description" class="form-control custom-input-st @error('description') is-invalid @enderror" 
                            rows="4">{{ old('description', $salesTarget->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('sales-targets.show', $salesTarget) }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
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
