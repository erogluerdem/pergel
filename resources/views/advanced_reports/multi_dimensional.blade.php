@extends('layouts.master')

@section('title', 'Çok Boyutlu Analitik Rapor')

@section('topbar-title', 'Gelişmiş Raporlar - Çok Boyutlu Rapor')

@push('styles')
<style>
    .adv-rep-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0284c7 50%, #0369a1 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .adv-rep-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .adv-rep-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="adv-rep-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('advanced_reports.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Raporlar Hub
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Çok Boyutlu Analitik Matris</h1>
            <p class="text-white-50 mb-0 fs-6">Danışman, şantiye projesi ve ödeme durumlarına göre çapraz veri matrisi.</p>
        </div>
    </div>
</div>

<div class="adv-rep-glass-card">
    <form method="GET" action="{{ route('advanced_reports.multi_dimensional') }}" class="row g-2 align-items-center mb-4">
        <div class="col-md-4">
            <label class="form-label small fw-bold text-dark mb-1">Analiz Boyutu</label>
            <select name="dimension" class="form-select">
                <option value="consultant" {{ $dimension === 'consultant' ? 'selected' : '' }}>Danışman Boyutu</option>
                <option value="project" {{ $dimension === 'project' ? 'selected' : '' }}>Proje / Şantiye Boyutu</option>
                <option value="payment_status" {{ $dimension === 'payment_status' ? 'selected' : '' }}>Ödeme Durumu Boyutu</option>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end pt-4">
            <button type="submit" class="btn btn-primary text-white w-100 rounded-pill fw-bold" style="background:#0284c7;">
                <i class="ri-layout-grid-line me-1"></i>Boyutlandırmayı Değiştir
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Segment / Boyut Adı</th>
                    <th>Toplam Hacim (₺)</th>
                    <th>Tahsil Edilen (₺)</th>
                    <th>Bekleyen Bakiye (₺)</th>
                </tr>
            </thead>
            <tbody>
                @if(is_iterable($data))
                    @forelse($data as $row)
                        <tr>
                            <td class="ps-4"><strong class="text-dark fs-6">{{ $row['name'] ?? $row['payment_status'] ?? '-' }}</strong></td>
                            <td>₺{{ number_format($row['total'] ?? $row['total_amount'] ?? 0, 2, ',', '.') }}</td>
                            <td><strong class="text-success">₺{{ number_format($row['paid'] ?? 0, 2, ',', '.') }}</strong></td>
                            <td><strong class="text-primary">₺{{ number_format($row['pending'] ?? 0, 2, ',', '.') }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Kayıt bulunamadı.</td>
                        </tr>
                    @endforelse
                @else
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Veri yüklenemedi.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection
