@extends('layouts.master')

@section('title', 'Tesis Varlık & Ekipman Parkı')

@section('topbar-title', 'Tesis - Varlık Listesi')

@push('styles')
<style>
    .facility-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #059669 50%, #047857 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .facility-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .facility-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="facility-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('facility.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Tesis Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Tesis Varlık & Ekipman Parkı</h1>
            <p class="text-white-50 mb-0 fs-6">HVAC, jeneratör, asansör, hidrofor ve yangın güvenlik ekipmanı demirbaş kayıtları.</p>
        </div>
    </div>
</div>

<div class="facility-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-archive-line text-emerald me-2" style="color:#059669;"></i>Kayıtlı Ekipman Parkı Tablosu
        </h5>
        <span class="badge bg-emerald bg-opacity-10 border border-emerald border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#059669;">
            {{ count($assets) }} Ekipman
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Varlık Kodu</th>
                    <th>Ekipman Adı & Marka/Model</th>
                    <th>Kategori</th>
                    <th>Lokasyon</th>
                    <th>Satın Alma Değeri (₺)</th>
                    <th>Sonraki Bakım Tarihi</th>
                    <th>Durum</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets as $ast)
                    @php
                        $isOverdue = $ast->next_maintenance_date && \Carbon\Carbon::parse($ast->next_maintenance_date)->isPast();
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold fs-6">
                                {{ $ast->asset_code }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $ast->name }}</strong>
                            <small class="text-muted">{{ $ast->brand ?? '-' }} {{ $ast->model ? '('.$ast->model.')' : '' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info border px-2 py-1 small">{{ $ast->category }}</span>
                        </td>
                        <td>
                            <span class="text-dark small">{{ $ast->location ?? 'Genel Ambar' }}</span>
                        </td>
                        <td>
                            <strong class="text-dark fs-6">₺{{ number_format($ast->purchase_price, 2, ',', '.') }}</strong>
                        </td>
                        <td>
                            <span class="small fw-bold {{ $isOverdue ? 'text-danger' : 'text-dark' }}">
                                {{ $ast->next_maintenance_date ? \Carbon\Carbon::parse($ast->next_maintenance_date)->format('d.m.Y') : '-' }}
                            </span>
                        </td>
                        <td>
                            @if($ast->status === 'active')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Aktif Çalışıyor</span>
                            @elseif($ast->status === 'maintenance')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Bakımda</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill px-3 py-1 fw-bold">Pasif</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('facility.assets.show', $ast->id) }}" class="btn btn-sm btn-outline-emerald rounded-pill px-3" style="color:#059669; border-color:#059669;">
                                <i class="ri-eye-line me-1"></i>İncele
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="ri-building-4-line fs-1 d-block mb-2 text-emerald" style="color:#059669;"></i>
                            Henüz kayıtlı tesis varlığı bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
