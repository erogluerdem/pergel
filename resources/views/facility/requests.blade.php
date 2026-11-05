@extends('layouts.master')

@section('title', 'Servis & Arıza Talepleri')

@section('topbar-title', 'Tesis - Arıza Talepleri')

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
            <h1 class="fw-extrabold text-white mb-1">Servis & Arıza Bildirim Talepleri</h1>
            <p class="text-white-50 mb-0 fs-6">Saha arıza bildirimleri, iklimlendirme, aydınlatma ve bina arıza talepleri.</p>
        </div>
    </div>
</div>

<div class="facility-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-customer-service-2-line text-emerald me-2" style="color:#059669;"></i>Arıza Bildirim Talepleri Listesi
        </h5>
        <span class="badge bg-emerald bg-opacity-10 border border-emerald border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#059669;">
            {{ count($requests) }} Arıza Kaydı
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Talep Konusu</th>
                    <th>Lokasyon</th>
                    <th>Açıklama</th>
                    <th>SLA Hedef Süre</th>
                    <th>Tarih</th>
                    <th class="text-end pe-4">Durum</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr>
                        <td class="ps-4">
                            <strong class="text-dark d-block">{{ $req->subject }}</strong>
                        </td>
                        <td>
                            <span class="text-dark small fw-bold">{{ $req->location }}</span>
                        </td>
                        <td>
                            <span class="text-muted small">{{ Str::limit($req->description, 50) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-dark bg-opacity-10 text-dark font-monospace px-2 py-1">
                                {{ $req->sla_target ? round($req->sla_target / 3600) . ' Saat' : 'Standard SLA' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-dark small">{{ $req->created_at ? $req->created_at->format('d.m.Y H:i') : '-' }}</span>
                        </td>
                        <td class="text-end pe-4">
                            @if($req->status === 'completed')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Çözüldü</span>
                            @elseif($req->status === 'in_progress')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Müdahale Ediliyor</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1 fw-bold">Atama Bekliyor</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="ri-checkbox-circle-line fs-1 d-block mb-2 text-success"></i>
                            Aktif arıza talebi bulunmamaktadır.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
