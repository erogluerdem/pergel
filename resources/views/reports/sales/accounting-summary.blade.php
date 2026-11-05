@extends('layouts.master')

@section('title', 'Satış Taksit Muhasebe Özeti & Finansal Rapor')

@section('topbar-title', 'Satış Raporları - Muhasebe Özeti')

@push('styles')
<style>
    /* Hero Header Banner */
    .sales-acc-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .sales-acc-hero-banner,
    :root:not([data-bs-theme="dark"]) .sales-acc-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #059669 50%, #047857 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .sales-acc-hero-banner {
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .sales-acc-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .sales-acc-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .sales-acc-kpi-card,
    :root:not([data-bs-theme="dark"]) .sales-acc-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .sales-acc-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .sales-acc-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .sales-acc-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .sales-acc-glass-card,
    :root:not([data-bs-theme="dark"]) .sales-acc-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .sales-acc-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="sales-acc-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold border border-white border-opacity-25 shadow-sm">
                    <i class="ri-calculator-line me-1"></i>Sales Installment & Accounting Analytics
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Satış Taksit Muhasebe Özeti</h1>
            <p class="text-white-50 mb-0 fs-6">Gayrimenkul ve konut satış taksit tahsilatları, matrah ve vadesi geçen bakiye raporu.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('reports.sales.accounting_summary_export') }}" class="btn btn-emerald text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#059669;">
                <i class="ri-file-excel-2-line me-1"></i>Excel Raporu İndir
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="sales-acc-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Satış Hakediş Ciro</span>
                    <h2 class="fw-extrabold mb-0 text-emerald" style="color:#059669;">₺{{ number_format($accounting['total_sales'] ?? 0, 2, ',', '.') }}</h2>
                </div>
                <div class="sales-acc-stat-icon bg-emerald bg-opacity-10 text-emerald" style="color:#059669;">
                    <i class="ri-money-dollar-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="sales-acc-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Tahsil Edilen Tutar</span>
                    <h2 class="fw-extrabold mb-0 text-success">₺{{ number_format($accounting['paid_sales'] ?? 0, 2, ',', '.') }}</h2>
                </div>
                <div class="sales-acc-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="sales-acc-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Açık Taksit Bakiyesi</span>
                    <h2 class="fw-extrabold mb-0 text-warning">₺{{ number_format($accounting['pending_sales'] ?? 0, 2, ',', '.') }}</h2>
                </div>
                <div class="sales-acc-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-time-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="sales-acc-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Geciken Taksit Tutarı</span>
                    <h2 class="fw-extrabold mb-0 text-danger">₺{{ number_format($accounting['overdue_amount'] ?? 0, 2, ',', '.') }}</h2>
                </div>
                <div class="sales-acc-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-error-warning-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="sales-acc-glass-card p-3 mb-4">
    <form method="GET" action="{{ route('reports.sales.accounting_summary') }}" class="row g-2 align-items-center">
        <div class="col-md-3">
            <label class="form-label small fw-bold mb-1">Başlangıç Tarihi</label>
            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold mb-1">Bitiş Tarihi</label>
            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold mb-1">Şantiye / Proje</label>
            <select name="project_id" class="form-select">
                <option value="">-- Tüm Projeler --</option>
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}" {{ $projectId == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end pt-4">
            <button type="submit" class="btn btn-emerald text-white w-100 rounded-pill fw-bold" style="background:#059669;">
                <i class="ri-filter-3-line me-1"></i>Raporu Güncelle
            </button>
        </div>
    </form>
</div>

<!-- Sub Navigation Tabs for Reports -->
<div class="d-flex gap-2 mb-4 overflow-auto pb-2">
    <a href="{{ route('reports.sales.accounting_summary') }}" class="btn btn-emerald rounded-pill px-4 fw-bold text-white shadow-sm" style="background:#059669;">
        <i class="ri-bill-line me-1"></i>Muhasebe Özeti
    </a>
    <a href="{{ route('reports.sales.monthly_installment') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="ri-calendar-event-line me-1"></i>Aylık Taksit Dağılımı
    </a>
    <a href="{{ route('reports.sales.balance_detail') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="ri-scales-line me-1"></i>Bakiye Detay Analizi
    </a>
    <a href="{{ route('reports.sales.payment_timeline') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="ri-history-line me-1"></i>Tahsilat Zaman Çizelgesi
    </a>
</div>

<!-- Sales Accounting Ledger Table -->
<div class="sales-acc-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-file-list-3-line text-emerald me-2" style="color:#059669;"></i>Satış Fatura & Taksit Muhasebe Cetveli
        </h5>
        <span class="badge bg-emerald bg-opacity-10 border border-emerald border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#059669;">
            {{ count($summary) }} Kayıt
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Sözleşme / Fatura No</th>
                    <th>Müşteri Ad Soyad</th>
                    <th>Tarih</th>
                    <th>Toplam Tutar (₺)</th>
                    <th>Tahsil Edilen (₺)</th>
                    <th>Kalan Bakiye (₺)</th>
                    <th>Taksit Durumu</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summary as $row)
                    <tr>
                        <td class="ps-4">
                            <span class="badge badge-code rounded-pill px-3 py-1 fw-bold fs-6">
                                {{ $row['invoice_number'] }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $row['customer_name'] }}</strong>
                        </td>
                        <td>
                            <span class="text-muted small">{{ \Carbon\Carbon::parse($row['invoice_date'])->format('d.m.Y') }}</span>
                        </td>
                        <td>
                            <strong class="text-dark">₺{{ number_format($row['total_amount'], 2, ',', '.') }}</strong>
                        </td>
                        <td>
                            <strong class="text-success">₺{{ number_format($row['paid_amount'], 2, ',', '.') }}</strong>
                        </td>
                        <td>
                            <strong class="text-emerald" style="color:#059669;">₺{{ number_format($row['remaining_amount'], 2, ',', '.') }}</strong>
                        </td>
                        <td>
                            @if($row['remaining_amount'] == 0)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Tamamlandı</span>
                            @elseif($row['is_overdue'])
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1 fw-bold">Vadesi Geçti</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Ödeme Bekliyor</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('reports.sales.by_invoice', $row['invoice_id']) }}" class="btn btn-sm btn-outline-emerald rounded-pill px-3" style="color:#059669; border-color:#059669;">
                                <i class="ri-eye-line me-1"></i>Taksit Detayı
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="ri-calculator-line fs-1 d-block mb-2 text-emerald" style="color:#059669;"></i>
                            Seçilen kriterlerde satış taksit kaydı bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
