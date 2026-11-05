@extends('layouts.master')

@section('title', 'Dinamik Sorgulama Motoru')

@section('topbar-title', 'Genel Raporlar - Dinamik Sorgulama')

@push('styles')
<style>
    .gen-kpi-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #7c3aed 50%, #6d28d9 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .gen-kpi-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .gen-kpi-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="gen-kpi-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('reports.general.kpi_dashboard') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>KPI Dashboard
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Dinamik Raporlama & Sorgulama Motoru</h1>
            <p class="text-white-50 mb-0 fs-6">Özel tutar aralıkları, müşteri ve fatura durumu filtreleri ile özel raporlar üretin.</p>
        </div>
    </div>
</div>

<div class="gen-kpi-glass-card">
    <form method="GET" action="{{ route('reports.general.dynamic_query') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark">Müşteri Seçin</label>
            <select name="customer_id" class="form-select">
                <option value="">-- Tüm Müşteriler --</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ $filters['customer_id'] == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark">Minimum Tutar (₺)</label>
            <input type="number" name="min_amount" class="form-control" value="{{ $filters['min_amount'] }}" placeholder="100000">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark">Maksimum Tutar (₺)</label>
            <input type="number" name="max_amount" class="form-control" value="{{ $filters['max_amount'] }}" placeholder="10000000">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-purple text-white w-100 rounded-pill fw-bold" style="background:#7c3aed;">
                <i class="ri-search-line me-1"></i>Özel Sorgu Çalıştır
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Fatura No</th>
                    <th>Müşteri</th>
                    <th>Tarih</th>
                    <th>Tutar (₺)</th>
                    <th>Tahsil Edilen (₺)</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                    <tr>
                        <td class="ps-4 font-monospace fw-bold text-dark">#{{ $inv->no }}</td>
                        <td><strong class="text-dark">{{ $inv->customer->name ?? '-' }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($inv->date)->format('d.m.Y') }}</td>
                        <td><strong class="text-dark">₺{{ number_format($inv->amount, 2, ',', '.') }}</strong></td>
                        <td><strong class="text-success">₺{{ number_format($inv->paid_amount, 2, ',', '.') }}</strong></td>
                        <td><span class="badge bg-purple bg-opacity-10 text-purple border rounded-pill px-3 py-1" style="color:#7c3aed;">{{ ucfirst($inv->status) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Sorgu kriterlerine uyan kayıt bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
