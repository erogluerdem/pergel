@extends('layouts.master')

@section('title', 'Yeni Satış Hedefi Ekle')

@push('styles')
<style>
    .sales-perf-create-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .sales-perf-create-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: perfCreateGlow 4s linear infinite;
    }

    @keyframes perfCreateGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .sales-perf-badge {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-perf-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-perf {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-perf:focus {
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
    <div class="sales-perf-create-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill sales-perf-badge">
                        <i class="ri-trophy-fill text-warning fs-5"></i> SATIŞ PERFORMANCE & PRİM PORTALI
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Yeni Hedef Tanımı
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Yeni Satış Hedefi Belirle</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Temsilciniz veya danışmanınız için ciro hedefi, dönem tarihleri ve prim parametreleri tanımlayın.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('sales_performance.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Performans Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-perf-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-add-circle-fill text-warning fs-5"></i>Satış Hedefi Tanımlama Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('sales_performance.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <!-- Consultant -->
                    <div class="col-md-6">
                        <label for="user_id" class="form-label-custom">Satış Temsilcisi / Danışman <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-select custom-input-perf @error('user_id') is-invalid @enderror" required>
                            <option value="">-- Danışman Seçiniz --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                    👤 {{ $u->name }} ({{ $u->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Target Type -->
                    <div class="col-md-6">
                        <label for="target_type" class="form-label-custom">Hedef Tipi <span class="text-danger">*</span></label>
                        <select name="target_type" id="target_type" class="form-select custom-input-perf @error('target_type') is-invalid @enderror" required>
                            <option value="revenue" {{ old('target_type', 'revenue') === 'revenue' ? 'selected' : '' }}>💰 Ciro Bazlı (TL)</option>
                            <option value="unit" {{ old('target_type') === 'unit' ? 'selected' : '' }}>🎯 Satış Adedi Bazlı (Birim)</option>
                        </select>
                        @error('target_type')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Target Amount -->
                    <div class="col-md-6">
                        <label for="target_amount" class="form-label-custom">Hedef Tutar / Miktar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">TL / Adet</span>
                            <input type="number" step="0.01" name="target_amount" id="target_amount" class="form-control custom-input-perf @error('target_amount') is-invalid @enderror" 
                                placeholder="Örn: 15000000.00" value="{{ old('target_amount') }}" required>
                        </div>
                        @error('target_amount')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label-custom">Hedef Durumu <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select custom-input-perf @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>🟢 Aktif (Devam Ediyor)</option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>🔵 Tamamlandı (Başarıyla Kapandı)</option>
                            <option value="abandoned" {{ old('status') === 'abandoned' ? 'selected' : '' }}>🔴 İptal Edildi (Terk)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dates -->
                    <div class="col-md-6">
                        <label for="period_start" class="form-label-custom">Dönem Başlangıcı <span class="text-danger">*</span></label>
                        <input type="date" name="period_start" id="period_start" class="form-control custom-input-perf @error('period_start') is-invalid @enderror" 
                            value="{{ old('period_start', date('Y-m-01')) }}" required>
                        @error('period_start')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="period_end" class="form-label-custom">Dönem Bitişi <span class="text-danger">*</span></label>
                        <input type="date" name="period_end" id="period_end" class="form-control custom-input-perf @error('period_end') is-invalid @enderror" 
                            value="{{ old('period_end', date('Y-m-t')) }}" required>
                        @error('period_end')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label-custom">Hedef Açıklaması & Prim Şartları</label>
                        <textarea name="description" id="description" class="form-control custom-input-perf @error('description') is-invalid @enderror" 
                            rows="4" placeholder="Portföy hedefi, prim oranları ve prim hak ediş açıklamaları...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('sales_performance.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i>Hedefi Kaydet & Yayınla
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
