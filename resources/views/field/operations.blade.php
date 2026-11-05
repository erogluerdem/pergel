@extends('layouts.master')

@section('title', 'Günlük Saha Operasyonları & Şantiye Günlüğü')

@section('topbar-title', 'Saha Operasyonları')

@push('styles')
<style>
    /* Hero Header Banner */
    .field-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .field-hero-banner,
    :root:not([data-bs-theme="dark"]) .field-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #047857 50%, #059669 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .field-hero-banner {
        background: linear-gradient(135deg, rgba(4, 120, 87, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .field-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .field-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .field-kpi-card,
    :root:not([data-bs-theme="dark"]) .field-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .field-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .field-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .field-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .field-glass-card,
    :root:not([data-bs-theme="dark"]) .field-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .field-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="field-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-hard-hat-line me-1"></i>Field Operations & Site Logs
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Günlük Saha Operasyonları & Şantiye Günlüğü</h1>
            <p class="text-white-50 mb-0 fs-6">Saha puantajını, iş makinesi kullanım saatlerini, hava durumunu ve günlük ilerleme fotoğraflarını takip edin.</p>
        </div>

        <a href="{{ route('field.operations.create') }}" class="btn btn-emerald text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#059669;">
            <i class="ri-add-circle-line me-1"></i>Yeni Saha Raporu Ekle
        </a>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="field-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Bugünkü Saha Raporları</span>
                    <h2 class="fw-extrabold mb-0 text-success">{{ $todayReportsCount }}</h2>
                </div>
                <div class="field-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-file-list-3-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="field-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Saha İş Gücü</span>
                    <h2 class="fw-extrabold mb-0 text-primary">{{ number_format($totalLabours) }} Usta/Kişi</h2>
                </div>
                <div class="field-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-team-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="field-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Aktif Ekipman & Makine</span>
                    <h2 class="fw-extrabold mb-0 text-warning">{{ $totalEquipments }} Adet</h2>
                </div>
                <div class="field-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-truck-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="field-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Kayıtlı Rapor Sayısı</span>
                    <h2 class="fw-extrabold mb-0 text-info">{{ $reports->total() }}</h2>
                </div>
                <div class="field-stat-icon bg-info bg-opacity-10 text-info">
                    <i class="ri-camera-lens-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="field-glass-card mb-4 p-3">
    <form method="GET" action="{{ route('field.operations') }}">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-building-line"></i></span>
                    <select name="project_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tüm Projeler</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ $selectedProjectId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" onchange="this.form.submit()">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Tüm Durumlar</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Taslak</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Onaya Gönderildi</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Onaylandı</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <a href="{{ route('field.operations') }}" class="btn btn-outline-secondary rounded-circle" title="Temizle">
                    <i class="ri-refresh-line"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Reports Table Card -->
<div class="field-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-calendar-event-line text-emerald me-2" style="color:#059669;"></i>Günlük Şantiye Raporları Listesi
        </h5>
        <span class="badge bg-emerald bg-opacity-10 text-emerald border border-emerald border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#059669;">
            {{ $reports->total() }} Kayıt
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Rapor Tarihi</th>
                    <th>Proje</th>
                    <th>Hava Durumu</th>
                    <th>İş Gücü (Kişi)</th>
                    <th>Makine Parkı</th>
                    <th>Durum</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    @php
                        $weather = is_array($report->weather) ? $report->weather : [];
                        $totalLabourCount = $report->labours->sum('count');
                        $totalEquipmentCount = $report->equipments->count();
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold fs-6">
                                <i class="ri-calendar-line me-1"></i>{{ $report->report_date ? $report->report_date->format('d.m.Y') : '-' }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $report->project_id ? ($projects->firstWhere('id', $report->project_id)?->name ?? 'Proje #'.$report->project_id) : 'Genel Saha' }}</strong>
                        </td>
                        <td>
                            @if(!empty($weather['temperature']))
                                <span class="badge bg-warning bg-opacity-10 text-dark border me-1">
                                    <i class="ri-sun-line text-warning me-1"></i>{{ $weather['temperature'] }}°C — {{ $weather['description'] ?? '' }}
                                </span>
                            @else
                                <span class="text-muted small">Veri Yok</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-primary"><i class="ri-team-line me-1"></i>{{ $totalLabourCount }} Kişi</span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark"><i class="ri-truck-line me-1 text-muted"></i>{{ $totalEquipmentCount }} Ekipman</span>
                        </td>
                        <td>
                            @if($report->status === 'approved')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onaylandı</span>
                            @elseif($report->status === 'submitted')
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onay Bekliyor</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Taslak</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group gap-1">
                                <a href="{{ route('field.operations.show', $report->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="ri-eye-line me-1"></i>İncele
                                </a>
                                <a href="{{ route('field.operations.edit', $report->id) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('field.operations.destroy', $report->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu günlük saha raporunu silmek istediğinize emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Sil">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ri-hard-hat-line fs-1 d-block mb-2 text-emerald" style="color:#059669;"></i>
                            Henüz kayıtlı günlük saha raporu bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-opacity-10">
        <small class="text-muted">Toplam {{ $reports->total() }} kayıt gösteriliyor</small>
        <div>{{ $reports->links() }}</div>
    </div>
</div>

@endsection
