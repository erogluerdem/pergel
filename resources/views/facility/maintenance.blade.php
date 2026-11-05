@extends('layouts.master')

@section('title', 'Bakım & İş Emirleri')

@section('topbar-title', 'Tesis - Bakım Emirleri')

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
            <h1 class="fw-extrabold text-white mb-1">Periyodik Bakım & İş Emirleri Portalı</h1>
            <p class="text-white-50 mb-0 fs-6">Planlı periyodik bakımlar, önleyici arıza onarımları ve servis maliyetleri.</p>
        </div>
    </div>
</div>

<div class="facility-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-tools-line text-emerald me-2" style="color:#059669;"></i>Bakım İş Emirleri Listesi
        </h5>
        <span class="badge bg-emerald bg-opacity-10 border border-emerald border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#059669;">
            {{ count($workOrders) }} İş Emri
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">İş Emri Başlığı</th>
                    <th>İlişkili Varlık/Ekipman</th>
                    <th>Bakım Tipi</th>
                    <th>Öncelik</th>
                    <th>Planlanan Tarih</th>
                    <th>Maliyet (₺)</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                @forelse($workOrders as $wo)
                    <tr>
                        <td class="ps-4">
                            <strong class="text-dark d-block">{{ $wo->title }}</strong>
                            <small class="text-muted">{{ Str::limit($wo->description, 40) }}</small>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $wo->asset->name ?? 'Tesis Ekipmanı' }}</strong>
                            <small class="text-muted">{{ $wo->asset->asset_code ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info border px-2 py-1 small">{{ ucfirst($wo->type) }}</span>
                        </td>
                        <td>
                            @if($wo->priority === 'high')
                                <span class="badge bg-danger bg-opacity-10 text-danger border px-2 py-1 small">Yüksek</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1 small">Normal</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-dark small fw-bold">{{ $wo->scheduled_date ? \Carbon\Carbon::parse($wo->scheduled_date)->format('d.m.Y') : '-' }}</span>
                        </td>
                        <td>
                            <strong class="text-dark fs-6">₺{{ number_format($wo->cost, 2, ',', '.') }}</strong>
                        </td>
                        <td>
                            @if($wo->status === 'completed')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Tamamlandı</span>
                            @elseif($wo->status === 'in_progress')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Devam Ediyor</span>
                            @else
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1 fw-bold">Planlandı</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ri-tools-line fs-1 d-block mb-2 text-emerald" style="color:#059669;"></i>
                            Henüz bakım iş emri bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
