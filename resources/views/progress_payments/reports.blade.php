@extends('layouts.master')

@section('title', 'Hakediş & Finansal Raporlar')

@section('topbar-title', 'Hakediş Raporları')

@push('styles')
<style>
    .progress-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #4338ca 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .progress-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
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

    .stat-badge-box {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        background: #ffffff;
        border: 1px solid #e2e8f0;
    }

    html[data-bs-theme="dark"] .stat-badge-box {
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
</style>
@endpush

@section('content')

<!-- Hero Banner -->
<div class="progress-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('progress-payments.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Hakediş Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Hakediş & Ödeme Analiz Raporları</h1>
            <p class="text-white-50 mb-0 fs-6">Proje bazlı hakediş toplamları, onaylanan tutarlar, nakit çıkışları ve geciken vadedeki hakedişler.</p>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="progress-glass-card mb-4 p-3">
    <form method="GET" action="{{ route('progress-payments.reports') }}">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
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
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" onchange="this.form.submit()" placeholder="Başlangıç Tarihi">
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" onchange="this.form.submit()" placeholder="Bitiş Tarihi">
            </div>
            <div class="col-md-2">
                <a href="{{ route('progress-payments.reports') }}" class="btn btn-outline-secondary w-100 rounded-pill">
                    <i class="ri-refresh-line me-1"></i>Filtreyi Sıfırla
                </a>
            </div>
        </div>
    </form>
</div>

<!-- 4 Top KPI Stat Boxes -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-badge-box border-start border-4 border-indigo" style="border-left-color:#4338ca !important;">
            <span class="text-muted fw-bold text-uppercase small d-block mb-1">Toplam Hakediş Hacmi</span>
            <h3 class="fw-extrabold mb-0 text-dark">₺{{ number_format($data['total_amount'] ?? 0, 2, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-badge-box border-start border-4 border-primary">
            <span class="text-muted fw-bold text-uppercase small d-block mb-1">Onaylanan Tutarlar</span>
            <h3 class="fw-extrabold mb-0 text-primary">₺{{ number_format($data['total_approved'] ?? 0, 2, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-badge-box border-start border-4 border-success">
            <span class="text-muted fw-bold text-uppercase small d-block mb-1">Ödenmiş Tutarlar</span>
            <h3 class="fw-extrabold mb-0 text-success">₺{{ number_format($data['total_paid'] ?? 0, 2, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-badge-box border-start border-4 border-warning">
            <span class="text-muted fw-bold text-uppercase small d-block mb-1">Bekleyen Hakediş Tutar</span>
            <h3 class="fw-extrabold mb-0 text-warning">₺{{ number_format($data['pending_payments'] ?? 0, 2, ',', '.') }}</h3>
        </div>
    </div>
</div>

<!-- Report Table -->
<div class="progress-glass-card">
    <h5 class="fw-bold text-dark mb-3"><i class="ri-file-chart-line text-indigo me-2" style="color:#4338ca;"></i>Analize Dahil Edilen Hakediş Listesi</h5>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Hakediş No</th>
                    <th>Proje</th>
                    <th>Sözleşme</th>
                    <th>Tutar (KDV Hariç)</th>
                    <th>Durum</th>
                    <th>Hakediş Tarihi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                    <tr>
                        <td class="ps-4">
                            <a href="{{ route('progress-payments.show', $p) }}" class="fw-bold text-indigo font-monospace" style="color:#4338ca;">
                                {{ $p->payment_number }}
                            </a>
                        </td>
                        <td><strong class="text-dark">{{ $p->project?->name ?? 'Proje #' . $p->project_id }}</strong></td>
                        <td><span class="badge bg-light text-dark border">Sözleşme #{{ $p->contract?->number ?? $p->contract_id }}</span></td>
                        <td><strong class="text-dark">₺{{ number_format($p->amount, 2, ',', '.') }}</strong></td>
                        <td>
                            @if($p->status === 'paid')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Ödendi</span>
                            @elseif($p->status === 'approved')
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onaylandı</span>
                            @elseif($p->status === 'submitted')
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1 fw-bold">Sunuldu</span>
                            @elseif($p->status === 'rejected')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1 fw-bold">Reddedildi</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Taslak</span>
                            @endif
                        </td>
                        <td><span class="text-dark small">{{ $p->payment_date ? $p->payment_date->format('d.m.Y') : '-' }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Rapor verisi bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
