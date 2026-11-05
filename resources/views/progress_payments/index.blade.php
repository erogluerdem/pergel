@extends('layouts.master')

@section('title', 'Hakediş & Ödeme Yönetimi')

@section('topbar-title', 'Hakediş Yönetimi')

@push('styles')
<style>
    /* Hero Header Banner */
    .progress-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .progress-hero-banner,
    :root:not([data-bs-theme="dark"]) .progress-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .progress-hero-banner {
        background: linear-gradient(135deg, rgba(30, 27, 75, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .progress-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .progress-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .progress-kpi-card,
    :root:not([data-bs-theme="dark"]) .progress-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .progress-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .progress-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Cards */
    .progress-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .progress-glass-card,
    :root:not([data-bs-theme="dark"]) .progress-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .progress-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $totalAmount = $progressPayments->sum('amount');
    $paidAmount = $progressPayments->where('status', 'paid')->sum('amount');
    $approvedAmount = $progressPayments->where('status', 'approved')->sum('amount');
    $pendingAmount = $progressPayments->whereIn('status', ['pending', 'submitted'])->sum('amount');
@endphp

<!-- Hero Header Banner -->
<div class="progress-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-money-dollar-circle-line me-1"></i>Hakediş & Finans Merkezi
                </span>
                <a href="{{ route('progress-payments.reports') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-bar-chart-2-line me-1"></i>Raporlar & Analizler
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Hakediş & Ödeme Yönetimi</h1>
            <p class="text-white-50 mb-0 fs-6">Saha imalat onaylarına göre hakediş cetvellerini, onay süreçlerini ve ödeme takiplerini yönetin.</p>
        </div>

        <a href="{{ route('progress-payments.create') }}" class="btn btn-indigo text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#4338ca;">
            <i class="ri-add-circle-line me-1"></i>Yeni Hakediş Oluştur
        </a>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="progress-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Hakediş Hacmi</span>
                    <h3 class="fw-extrabold mb-0 text-dark">₺{{ number_format($totalAmount, 2, ',', '.') }}</h3>
                </div>
                <div class="progress-stat-icon bg-indigo bg-opacity-10 text-indigo" style="color:#4338ca;">
                    <i class="ri-calculator-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="progress-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Ödenen Toplam Tutar</span>
                    <h3 class="fw-extrabold mb-0 text-success">₺{{ number_format($paidAmount, 2, ',', '.') }}</h3>
                </div>
                <div class="progress-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="progress-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Onaylanan (Ödeme Bekleyen)</span>
                    <h3 class="fw-extrabold mb-0 text-primary">₺{{ number_format($approvedAmount, 2, ',', '.') }}</h3>
                </div>
                <div class="progress-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-shield-check-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="progress-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">İnceleme Bekleyen Hakediş</span>
                    <h3 class="fw-extrabold mb-0 text-warning">₺{{ number_format($pendingAmount, 2, ',', '.') }}</h3>
                </div>
                <div class="progress-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-time-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="progress-glass-card mb-4 p-3">
    <form method="GET" action="{{ route('progress-payments.index') }}">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-building-line text-indigo" style="color:#4338ca;"></i></span>
                    <select name="project_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tüm Projeler</option>
                        @foreach ($projects as $projectId => $projectName)
                            <option value="{{ $projectId }}" {{ request('project_id') == $projectId ? 'selected' : '' }}>
                                {{ $projectName }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-file-text-line text-muted"></i></span>
                    <select name="contract_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tüm Sözleşmeler</option>
                        @foreach ($contracts as $contractId => $contractNumber)
                            <option value="{{ $contractId }}" {{ request('contract_id') == $contractId ? 'selected' : '' }}>
                                Sözleşme #{{ $contractNumber }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Tüm Durumlar</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Hakediş No veya Açıklama..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-1 d-flex gap-2">
                <button type="submit" class="btn btn-indigo rounded-circle text-white" style="background:#4338ca;" title="Filtrele">
                    <i class="ri-filter-3-line"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Progress Payments Table Card -->
<div class="progress-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-receipt-line text-indigo me-2" style="color:#4338ca;"></i>Hakediş Cetvelleri & Takip Listesi
        </h5>
        <span class="badge bg-indigo bg-opacity-10 text-indigo border border-indigo border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#4338ca;">
            {{ $progressPayments->total() }} Kayıt
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Hakediş No</th>
                    <th>Proje & Sözleşme</th>
                    <th>Tutar (KDV Hariç)</th>
                    <th>İlerleme %</th>
                    <th>Durum</th>
                    <th>Hakediş Tarihi</th>
                    <th>Vade Tarihi</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($progressPayments as $payment)
                    <tr>
                        <td class="ps-4">
                            <a href="{{ route('progress-payments.show', $payment) }}" class="fw-extrabold text-indigo font-monospace fs-6" style="color:#4338ca;">
                                {{ $payment->payment_number }}
                            </a>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $payment->project?->name ?? 'Proje Atanmamış' }}</strong>
                            <small class="text-muted">Sözleşme: #{{ $payment->contract?->number ?? $payment->contract_id }}</small>
                        </td>
                        <td>
                            <strong class="text-dark fs-6">₺{{ number_format($payment->amount, 2, ',', '.') }}</strong>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2" style="min-width: 100px;">
                                <div class="progress flex-grow-1" style="height: 6px;">
                                    <div class="progress-bar bg-indigo" role="progressbar" style="width: {{ min(100, $payment->percentage) }}%; background:#4338ca;"></div>
                                </div>
                                <small class="fw-bold text-dark">{{ number_format($payment->percentage, 1) }}%</small>
                            </div>
                        </td>
                        <td>
                            @if($payment->status === 'paid')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Ödendi</span>
                            @elseif($payment->status === 'approved')
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onaylandı</span>
                            @elseif($payment->status === 'submitted')
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1 fw-bold">Sunuldu</span>
                            @elseif($payment->status === 'rejected')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1 fw-bold">Reddedildi</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Taslak Beklemede</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-dark fw-bold small">{{ $payment->payment_date ? $payment->payment_date->format('d.m.Y') : '-' }}</span>
                        </td>
                        <td>
                            <span class="text-dark small">{{ $payment->due_date ? $payment->due_date->format('d.m.Y') : '-' }}</span>
                            @if ($payment->isOverdue())
                                <span class="badge bg-danger rounded-pill ms-1">Gecikmiş</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group gap-1">
                                <a href="{{ route('progress-payments.show', $payment) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="ri-eye-line me-1"></i>İncele
                                </a>
                                @if($payment->status === 'pending')
                                    <a href="{{ route('progress-payments.edit', $payment) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Düzenle">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="ri-money-dollar-box-line fs-1 d-block mb-2 text-indigo" style="color:#4338ca;"></i>
                            Henüz kayıtlı hakediş cetveli bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-opacity-10">
        <small class="text-muted">Toplam {{ $progressPayments->total() }} kayıt gösteriliyor</small>
        <div>{{ $progressPayments->links() }}</div>
    </div>
</div>

@endsection
