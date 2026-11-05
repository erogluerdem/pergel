@extends('layouts.master')

@section('title', 'Bordro Tahakkukunu Düzenle')

@section('topbar-title', 'Personel Bordroları - Düzenle')

@push('styles')
<style>
    .hr-payroll-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #f59e0b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .hr-payroll-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .hr-payroll-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="hr-payroll-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.hr.payroll.show', $id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Bordro Detayı
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Personel Bordro Tahakkukunu Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">Brüt maaş, SGK primi, gelir vergisi ve mesai hakediş güncellemeleri.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="hr-payroll-form-card">
            <form action="{{ route('finance.hr.payroll') }}" method="GET">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="gross_salary" class="form-label fw-bold small text-dark">Brüt Maaş (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="gross_salary" class="form-control text-indigo fw-bold" value="{{ old('gross_salary', $payroll->gross_salary ?? 85000.00) }}" required style="color:#4f46e5;">
                    </div>

                    <div class="col-md-6">
                        <label for="net_salary" class="form-label fw-bold small text-dark">Net Ödenecek Maaş (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="net_salary" class="form-control text-success fw-bold" value="{{ old('net_salary', $payroll->net_salary ?? 62450.00) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="sgk_deduction" class="form-label fw-bold small text-dark">SGK Primi Kesintisi (₺)</label>
                        <input type="number" step="0.01" id="sgk_deduction" class="form-control" value="{{ old('sgk_deduction', $payroll->sgk_deduction ?? 12750.00) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="tax_deduction" class="form-label fw-bold small text-dark">Gelir Vergisi Kesintisi (₺)</label>
                        <input type="number" step="0.01" id="tax_deduction" class="form-control" value="{{ old('tax_deduction', $payroll->tax_deduction ?? 9800.00) }}">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="overtime_bonus" class="form-label fw-bold small text-dark">Fazla Mesai Hakedişi (₺)</label>
                        <input type="number" step="0.01" id="overtime_bonus" class="form-control" value="{{ old('overtime_bonus', $payroll->overtime_bonus ?? 4500.00) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold small text-dark">Bordro Onay Durumu</label>
                        <select id="status" class="form-select">
                            <option value="pending" {{ ($payroll->status ?? '') === 'pending' ? 'selected' : '' }}>Onay Bekliyor</option>
                            <option value="approved" {{ ($payroll->status ?? '') === 'approved' ? 'selected' : '' }}>Onaylandı</option>
                            <option value="paid" {{ ($payroll->status ?? '') === 'paid' ? 'selected' : '' }}>Ödendi (Banka Talimatı)</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('finance.hr.payroll.show', $id) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
