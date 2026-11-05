@extends('layouts.master')

@section('title', 'İSG & Mesleki Sertifika Takip Portalı')

@section('topbar-title', 'İK - Sertifika Takibi')

@push('styles')
<style>
    .hr-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #f59e0b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .hr-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .hr-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Banner -->
<div class="hr-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('resources.hr.dashboard') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>İK Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">İSG & Mesleki Sertifika Takip Portalı</h1>
            <p class="text-white-50 mb-0 fs-6">Saha personelinin İSG uzmanlık, kule vinç ehliyeti, MYK ve yetki belgelerinin geçerlilik süreleri.</p>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="hr-glass-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Personel</th>
                    <th>Unvan</th>
                    <th>Sertifika / Yetki Belgesi</th>
                    <th>Belge No</th>
                    <th>Veren Kurum</th>
                    <th>Son Geçerlilik Tarihi</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certificates as $cert)
                    <tr>
                        <td class="ps-4">
                            <a href="{{ route('resources.hr.employee_profile', $cert->employee_id) }}" class="fw-extrabold text-dark d-block">
                                {{ $cert->employee?->full_name ?? 'Personel' }}
                            </a>
                        </td>
                        <td><small class="text-muted">{{ $cert->employee?->position }}</small></td>
                        <td><strong class="text-dark">{{ $cert->certificate_type }}</strong></td>
                        <td><span class="badge bg-dark bg-opacity-10 text-dark font-monospace">{{ $cert->certificate_number }}</span></td>
                        <td>{{ $cert->issuing_authority }}</td>
                        <td>
                            <span class="fw-bold {{ $cert->isExpiringSoon() ? 'text-danger' : 'text-dark' }}">
                                <i class="ri-calendar-event-line me-1"></i>{{ $cert->expiry_date ? $cert->expiry_date->format('d.m.Y') : '-' }}
                            </span>
                        </td>
                        <td>
                            @if($cert->isExpired())
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1 fw-bold">Süresi Doldu</span>
                            @elseif($cert->isExpiringSoon())
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">30 Gün İçinde Doluyor</span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Geçerli Belge</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Sertifika kaydı bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-opacity-10">
        <small class="text-muted">Toplam {{ $certificates->total() }} sertifika gösteriliyor</small>
        <div>{{ $certificates->links() }}</div>
    </div>
</div>

@endsection
