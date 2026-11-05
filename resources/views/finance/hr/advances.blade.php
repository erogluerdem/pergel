@extends('layouts.master')

@section('title', 'Personel Avans & Borç Takip Yönetimi')

@section('topbar-title', 'Finans - Personel Avansları')

@push('styles')
<style>
    /* Hero Header Banner */
    .hr-advance-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .hr-advance-hero-banner,
    :root:not([data-bs-theme="dark"]) .hr-advance-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #0d9488 50%, #0f766e 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .hr-advance-hero-banner {
        background: linear-gradient(135deg, rgba(15, 118, 110, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .hr-advance-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .hr-advance-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .hr-advance-kpi-card,
    :root:not([data-bs-theme="dark"]) .hr-advance-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .hr-advance-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .hr-advance-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .hr-advance-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .hr-advance-glass-card,
    :root:not([data-bs-theme="dark"]) .hr-advance-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .hr-advance-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="hr-advance-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-hand-coin-line me-1"></i>Employee Advance & Payroll Deductions
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Personel Avans & Maaş Mahsuplaşma Portalı</h1>
            <p class="text-white-50 mb-0 fs-6">Maaş avansı, şantiye intikal avansları, taksitli borç takibi ve mahsuplaşma süreçleri.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.hr.advances.create_form') }}" class="btn btn-teal text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#0d9488;">
                <i class="ri-add-circle-line me-1"></i>Yeni Avans Talebi
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="hr-advance-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Avans Talebi</span>
                    <h2 class="fw-extrabold mb-0 text-teal" style="color:#0d9488;">{{ $totalAdvances }} Kayıt</h2>
                </div>
                <div class="hr-advance-stat-icon bg-teal bg-opacity-10 text-teal" style="color:#0d9488;">
                    <i class="ri-user-shared-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="hr-advance-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Avans Tutarı</span>
                    <h2 class="fw-extrabold mb-0 text-primary">₺{{ number_format($totalAdvanceAmount, 2, ',', '.') }}</h2>
                </div>
                <div class="hr-advance-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-bank-card-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="hr-advance-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Tahsil Edilen (Mahsup)</span>
                    <h2 class="fw-extrabold mb-0 text-success">₺{{ number_format($totalPaidAmount, 2, ',', '.') }}</h2>
                </div>
                <div class="hr-advance-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="hr-advance-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Kalan Avans Bakiyesi</span>
                    <h2 class="fw-extrabold mb-0 text-danger">₺{{ number_format($totalRemainingAmount, 2, ',', '.') }}</h2>
                </div>
                <div class="hr-advance-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-history-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Advances Main Table Card -->
<div class="hr-advance-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-hand-coin-line text-teal me-2" style="color:#0d9488;"></i>Personel Avans Listesi & Mahsup Takibi
        </h5>
        <span class="badge bg-teal bg-opacity-10 text-teal border border-teal border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#0d9488;">
            {{ count($advances) }} Avans Kaydı
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Avans No</th>
                    <th>Personel Ad Soyad</th>
                    <th>Talep Sebebi</th>
                    <th>Avans Tutarı (₺)</th>
                    <th>Talep Tarihi</th>
                    <th>Durum</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($advances as $adv)
                    @php
                        $empName = $adv->employee->first_name ? ($adv->employee->first_name . ' ' . $adv->employee->last_name) : ($adv->employee->name ?? 'Ahmet Yılmaz');
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold fs-6">
                                {{ 'ADV-2026-' . sprintf('%04d', $adv->id) }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $empName }}</strong>
                            <small class="text-muted">{{ $adv->employee->position ?? 'Saha Personeli' }}</small>
                        </td>
                        <td>
                            <span class="text-dark d-block fw-bold">{{ Str::limit($adv->description, 35) ?: 'İş Avansı' }}</span>
                        </td>
                        <td>
                            <strong class="text-dark fs-6">₺{{ number_format($adv->amount, 2, ',', '.') }}</strong>
                        </td>
                        <td>
                            <span class="text-dark small">{{ $adv->date ? \Carbon\Carbon::parse($adv->date)->format('d.m.Y') : '-' }}</span>
                        </td>
                        <td>
                            @if($adv->status === 'paid')
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1 fw-bold">Ödendi (Mahsup Oldu)</span>
                            @elseif($adv->status === 'approved')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onaylandı</span>
                            @elseif($adv->status === 'rejected')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1 fw-bold">Reddedildi</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onay Bekliyor</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group gap-1">
                                <a href="{{ route('finance.hr.advances.show', $adv->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="ri-eye-line me-1"></i>İncele
                                </a>
                                <a href="{{ route('finance.hr.advances.edit', $adv->id) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ri-hand-coin-line fs-1 d-block mb-2 text-teal" style="color:#0d9488;"></i>
                            Henüz avans kaydı bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
