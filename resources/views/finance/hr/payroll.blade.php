@extends('layouts.master')

@section('title', 'Personel Bordro & Maaş Tahakkuk Yönetimi')

@section('topbar-title', 'Finans - Personel Bordroları')

@push('styles')
<style>
    /* Hero Header Banner */
    .hr-payroll-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .hr-payroll-hero-banner,
    :root:not([data-bs-theme="dark"]) .hr-payroll-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #4f46e5 50%, #4338ca 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .hr-payroll-hero-banner {
        background: linear-gradient(135deg, rgba(67, 56, 202, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .hr-payroll-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .hr-payroll-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .hr-payroll-kpi-card,
    :root:not([data-bs-theme="dark"]) .hr-payroll-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .hr-payroll-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .hr-payroll-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .hr-payroll-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .hr-payroll-glass-card,
    :root:not([data-bs-theme="dark"]) .hr-payroll-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .hr-payroll-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="hr-payroll-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-file-user-line me-1"></i>Payroll & Salary Processing
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Personel Bordro & Maaş Tahakkuk Portalı</h1>
            <p class="text-white-50 mb-0 fs-6">Aylık SGK matrahları, gelir vergisi kesintileri, fazla mesai ve net maaş ödeme listeleri.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.hr.payroll.create_form') }}" class="btn btn-indigo text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#4f46e5;">
                <i class="ri-add-circle-line me-1"></i>Yeni Bordro Tahakkuku
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="hr-payroll-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Brüt Maaş Hakedişi</span>
                    <h2 class="fw-extrabold mb-0 text-indigo" style="color:#4f46e5;">₺{{ number_format($totalGrossSalary, 2, ',', '.') }}</h2>
                </div>
                <div class="hr-payroll-stat-icon bg-indigo bg-opacity-10 text-indigo" style="color:#4f46e5;">
                    <i class="ri-wallet-3-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="hr-payroll-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Net Ödenecek Maaşlar</span>
                    <h2 class="fw-extrabold mb-0 text-success">₺{{ number_format($totalNetSalary, 2, ',', '.') }}</h2>
                </div>
                <div class="hr-payroll-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-money-dollar-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="hr-payroll-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">SGK Prim Kesintileri</span>
                    <h2 class="fw-extrabold mb-0 text-warning">₺{{ number_format($totalSgk, 2, ',', '.') }}</h2>
                </div>
                <div class="hr-payroll-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-shield-user-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="hr-payroll-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Gelir Vergisi Kesintisi</span>
                    <h2 class="fw-extrabold mb-0 text-danger">₺{{ number_format($totalTax, 2, ',', '.') }}</h2>
                </div>
                <div class="hr-payroll-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-government-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payroll Main Table Card -->
<div class="hr-payroll-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-file-paper-2-line text-indigo me-2" style="color:#4f46e5;"></i>Dönem Bordro & Tahakkuk Listesi
        </h5>
        <span class="badge bg-indigo bg-opacity-10 text-indigo border border-indigo border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#4f46e5;">
            {{ count($payrolls) }} Personel Bordrosu
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Dönem</th>
                    <th>Personel Ad Soyad</th>
                    <th>Brüt Maaş (₺)</th>
                    <th>SGK Kesintisi</th>
                    <th>Net Ödenecek (₺)</th>
                    <th>Durum</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrolls as $p)
                    @php
                        $empName = $p->employee->first_name ? ($p->employee->first_name . ' ' . $p->employee->last_name) : ($p->employee->name ?? 'Ahmet Yılmaz');
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold fs-6">
                                {{ $p->period ?: '2026-08' }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $empName }}</strong>
                            <small class="text-muted">{{ $p->employee->position ?? 'Saha Personeli' }}</small>
                        </td>
                        <td>
                            <span class="text-dark fw-bold fs-6">₺{{ number_format($p->gross_salary, 2, ',', '.') }}</span>
                        </td>
                        <td>
                            <span class="text-muted">₺{{ number_format($p->sgk_deduction, 2, ',', '.') }}</span>
                        </td>
                        <td>
                            <strong class="text-success fs-6">₺{{ number_format($p->net_salary, 2, ',', '.') }}</strong>
                        </td>
                        <td>
                            @if($p->status === 'paid')
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1 fw-bold">Ödendi (Banka)</span>
                            @elseif($p->status === 'approved')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onaylandı</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onay Bekliyor</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group gap-1">
                                <a href="{{ route('finance.hr.payroll.show', $p->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="ri-eye-line me-1"></i>Pusula
                                </a>
                                <a href="{{ route('finance.hr.payroll.edit', $p->id) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ri-file-user-line fs-1 d-block mb-2 text-indigo" style="color:#4f46e5;"></i>
                            Henüz bordro kaydı bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
