@extends('layouts.master')

@section('title', 'Personel Masraf & Harcama Beyan Yönetimi')

@section('topbar-title', 'Finans - Personel Harcamaları')

@push('styles')
<style>
    /* Hero Header Banner */
    .hr-expense-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .hr-expense-hero-banner,
    :root:not([data-bs-theme="dark"]) .hr-expense-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #0284c7 50%, #0369a1 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .hr-expense-hero-banner {
        background: linear-gradient(135deg, rgba(3, 105, 161, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .hr-expense-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .hr-expense-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .hr-expense-kpi-card,
    :root:not([data-bs-theme="dark"]) .hr-expense-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .hr-expense-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .hr-expense-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .hr-expense-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .hr-expense-glass-card,
    :root:not([data-bs-theme="dark"]) .hr-expense-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .hr-expense-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="hr-expense-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-receipt-line me-1"></i>Employee Reimbursement & Site Expenses
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Personel Masraf & Harcama Beyan Yönetimi</h1>
            <p class="text-white-50 mb-0 fs-6">Saha harcırahı, şantiye seyahat masrafları, yemek ve şirket içi harcama onay süreçleri.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.hr.expenses.create') }}" class="btn btn-sky text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#0284c7;">
                <i class="ri-add-circle-line me-1"></i>Yeni Harcama Beyanı
            </a>
            <a href="{{ route('finance.hr.expenses.export') }}" class="btn btn-light text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-file-excel-line me-1"></i>Excel Aktar
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="hr-expense-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Beyan Edilen</span>
                    <h2 class="fw-extrabold mb-0 text-sky" style="color:#0284c7;">₺{{ number_format($totalExpenses, 2, ',', '.') }}</h2>
                </div>
                <div class="hr-expense-stat-icon bg-sky bg-opacity-10 text-sky" style="color:#0284c7;">
                    <i class="ri-money-dollar-box-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="hr-expense-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Onay Bekleyen Beyanlar</span>
                    <h2 class="fw-extrabold mb-0 text-warning">{{ $pendingCount }}</h2>
                </div>
                <div class="hr-expense-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-time-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="hr-expense-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Bu Ayki Harcamalar</span>
                    <h2 class="fw-extrabold mb-0 text-success">₺{{ number_format($thisMonthTotal, 2, ',', '.') }}</h2>
                </div>
                <div class="hr-expense-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-calendar-check-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="hr-expense-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Ortalama Beyan Tutarı</span>
                    <h2 class="fw-extrabold mb-0 text-primary">₺{{ number_format($averageAmount, 2, ',', '.') }}</h2>
                </div>
                <div class="hr-expense-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-calculator-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Expenses Table & Onay Bekleyenler -->
<div class="row">
    <!-- Main Table -->
    <div class="col-lg-8">
        <div class="hr-expense-glass-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="ri-receipt-line text-sky me-2" style="color:#0284c7;"></i>Son Harcama Beyanları Listesi
                </h5>
                <span class="badge bg-sky bg-opacity-10 text-sky border border-sky border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#0284c7;">
                    {{ count($recentExpenses) }} Kayıt
                </span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Referans No</th>
                            <th>Personel Ad Soyad</th>
                            <th>Harcama Başlığı & Türü</th>
                            <th>Tutar (₺)</th>
                            <th>Harcama Tarihi</th>
                            <th>Durum</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentExpenses as $exp)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold fs-6">
                                        {{ $exp->reference_no ?: ('EXP-2026-' . sprintf('%04d', $exp->id)) }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-dark d-block">{{ $exp->employee->name ?? 'Personel' }}</strong>
                                    <small class="text-muted">{{ $exp->department ?? 'Proje Ekibi' }}</small>
                                </td>
                                <td>
                                    <strong class="text-dark d-block">{{ $exp->title }}</strong>
                                    <span class="badge bg-info bg-opacity-10 text-info border px-2 py-0 fs-7">
                                        {{ \App\Models\HrExpense::getExpenseTypes()[$exp->expense_type] ?? $exp->expense_type }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-dark fs-6">₺{{ number_format($exp->amount, 2, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <span class="text-dark small">{{ $exp->expense_date ? $exp->expense_date->format('d.m.Y') : '-' }}</span>
                                </td>
                                <td>
                                    @if($exp->status === 'paid')
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1 fw-bold">Ödendi</span>
                                    @elseif($exp->status === 'approved')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onaylandı</span>
                                    @elseif($exp->status === 'rejected')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1 fw-bold">Reddedildi</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onay Bekliyor</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group gap-1">
                                        <a href="{{ route('finance.hr.expenses.show', $exp->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="ri-eye-line me-1"></i>İncele
                                        </a>
                                        <a href="{{ route('finance.hr.expenses.edit', $exp->id) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Düzenle">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="ri-wallet-3-line fs-1 d-block mb-2 text-sky" style="color:#0284c7;"></i>
                                    Henüz harcama beyanı bulunamadı.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Sidebar: Pending Approvals & Top Spenders -->
    <div class="col-lg-4">
        <div class="hr-expense-glass-card">
            <h6 class="fw-bold text-dark mb-3"><i class="ri-time-line text-warning me-2"></i>Onay Bekleyen Masraflar</h6>
            <div class="list-group list-group-flush border-0">
                @forelse($pendingExpenses as $p)
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent py-2">
                        <div>
                            <strong class="text-dark d-block">{{ $p->employee->name ?? 'Personel' }}</strong>
                            <small class="text-muted d-block">{{ Str::limit($p->title, 28) }}</small>
                        </div>
                        <div class="text-end">
                            <strong class="text-warning d-block">₺{{ number_format($p->amount, 2, ',', '.') }}</strong>
                            <a href="{{ route('finance.hr.expenses.show', $p->id) }}" class="badge bg-warning text-dark text-decoration-none">Onayla</a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small py-3 mb-0 text-center">Onay bekleyen masraf kalmadı.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
