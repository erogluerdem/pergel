@extends('layouts.master')

@section('title', 'Yeni Avans Talebi Oluştur')

@section('topbar-title', 'Personel Avansları - Yeni Avans')

@push('styles')
<style>
    .hr-advance-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0d9488 50%, #0f766e 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .hr-advance-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .hr-advance-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="hr-advance-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.hr.advances') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Avanslar Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Yeni Personel Avans Talebi</h1>
            <p class="text-white-50 mb-0 fs-6">Saha intikal, acil ihtiyaç veya maaş avansı başvuru formu.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="hr-advance-form-card">
            <form action="{{ route('finance.hr.advances') }}" method="GET">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="employee_id" class="form-label fw-bold small text-dark">Talep Eden Personel <span class="text-danger">*</span></label>
                        <select name="employee_id" id="employee_id" class="form-select" required>
                            <option value="">Personel seçin...</option>
                            @foreach($employees as $emp)
                                @php
                                    $empName = $emp->first_name ? ($emp->first_name . ' ' . $emp->last_name) : ($emp->name ?? 'Personel');
                                @endphp
                                <option value="{{ $emp->id }}">{{ $empName }} - {{ $emp->position ?? 'Saha Ekibi' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="amount" class="form-label fw-bold small text-dark">Avans Tutarı (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="amount" class="form-control text-teal fw-bold" placeholder="0.00" required style="color:#0d9488;">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="date" class="form-label fw-bold small text-dark">Talep Tarihi <span class="text-danger">*</span></label>
                        <input type="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold small text-dark">Onay Durumu</label>
                        <select id="status" class="form-select">
                            <option value="pending">Onay Bekliyor</option>
                            <option value="approved">Onaylandı</option>
                            <option value="paid">Ödendi (Mahsup Oldu)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-bold small text-dark">Avans Talebi Sebebi & Açıklama <span class="text-danger">*</span></label>
                    <textarea id="description" class="form-control" rows="4" placeholder="Şantiye intikal ve acil masraflar için avans gerekçesi..." required></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('finance.hr.advances') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-teal text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#0d9488;">
                        <i class="ri-save-line me-1"></i>Avans Talebini Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
