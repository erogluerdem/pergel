@extends('layouts.master')

@section('title', 'Hakediş Detayı — ' . $progressPayment->payment_number)

@section('topbar-title', 'Hakediş Detay Sayfası')

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

    .progress-spec-box {
        border-radius: 14px;
        padding: 1.25rem;
        background: rgba(15, 23, 42, 0.03);
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    html[data-bs-theme="dark"] .progress-spec-box {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }
</style>
@endpush

@section('content')

@php
    $subtotal = $progressPayment->amount;
    $kdvAmount = $subtotal * 0.20; // 20% KDV
    $grandTotal = $subtotal + $kdvAmount;
@endphp

<!-- Hero Banner -->
<div class="progress-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('progress-payments.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Hakediş Panosu
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    {{ $progressPayment->payment_number }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Hakediş Cetveli & Onay Detayları</h1>
            <p class="text-white-50 mb-0 fs-6">Proje: {{ $progressPayment->project?->name ?? 'Proje Atanmamış' }} — Sözleşme: #{{ $progressPayment->contract?->number ?? $progressPayment->contract_id }}</p>
        </div>

        <!-- Action Buttons according to status -->
        <div class="d-flex gap-2 flex-wrap">
            @if($progressPayment->status === 'pending')
                <form action="{{ route('progress-payments.submit', $progressPayment) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-info text-white rounded-pill px-4 fw-bold shadow-sm">
                        <i class="ri-send-plane-line me-1"></i>Onaya Gönder
                    </button>
                </form>
            @endif

            @if($progressPayment->status === 'submitted')
                <form action="{{ route('progress-payments.approve', $progressPayment) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success text-white rounded-pill px-4 fw-bold shadow-sm">
                        <i class="ri-checkbox-circle-line me-1"></i>Hakedişi Onayla
                    </button>
                </form>

                <button type="button" class="btn btn-danger text-white rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="ri-close-circle-line me-1"></i>Reddet
                </button>
            @endif

            @if($progressPayment->status === 'approved')
                <form action="{{ route('progress-payments.mark-as-paid', $progressPayment) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-emerald text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#10b981;">
                        <i class="ri-money-dollar-circle-line me-1"></i>Ödeme Yapıldı İşaretle
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- 4 Spec Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="progress-spec-box">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Hakediş Tutarı (KDV Hariç)</span>
            <h3 class="fw-extrabold mb-0 text-dark">₺{{ number_format($subtotal, 2, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="progress-spec-box">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Hesaplanan KDV (%20)</span>
            <h3 class="fw-extrabold mb-0 text-indigo" style="color:#4338ca;">₺{{ number_format($kdvAmount, 2, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="progress-spec-box">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Genel Toplam (KDV Dahil)</span>
            <h3 class="fw-extrabold mb-0 text-success">₺{{ number_format($grandTotal, 2, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="progress-spec-box">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Onay Durumu</span>
            <div class="mt-1">
                @if($progressPayment->status === 'paid')
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-2 fw-bold fs-6">Ödendi</span>
                @elseif($progressPayment->status === 'approved')
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-30 rounded-pill px-3 py-2 fw-bold fs-6">Onaylandı</span>
                @elseif($progressPayment->status === 'submitted')
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-2 fw-bold fs-6">Onay Bekliyor</span>
                @elseif($progressPayment->status === 'rejected')
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-2 fw-bold fs-6">Reddedildi</span>
                @else
                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-2 fw-bold fs-6">Taslak</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content: Breakdown Table -->
    <div class="col-lg-8">
        <div class="progress-glass-card">
            <h5 class="fw-bold text-dark mb-3">
                <i class="ri-list-check text-indigo me-2" style="color:#4338ca;"></i>Hakediş İmalat Detay Satırları
            </h5>

            <div class="table-responsive mb-4">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">İmalat Açıklaması</th>
                            <th>Miktar</th>
                            <th>Birim Fiyat</th>
                            <th class="text-end pe-3">Toplam Tutar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($progressPayment->details as $detail)
                            <tr>
                                <td class="ps-3"><strong class="text-dark">{{ $detail->description }}</strong></td>
                                <td><span class="badge bg-light text-dark border font-monospace">{{ number_format($detail->quantity, 2, ',', '.') }}</span></td>
                                <td><span class="text-muted">₺{{ number_format($detail->unit_price, 2, ',', '.') }}</span></td>
                                <td class="text-end pe-3"><strong class="text-dark">₺{{ number_format($detail->amount, 2, ',', '.') }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted text-center py-4">İmalat detay satırı bulunamadı.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="3" class="text-end ps-3">Ara Toplam (KDV Hariç):</td>
                            <td class="text-end pe-3">₺{{ number_format($subtotal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end ps-3">KDV (%20):</td>
                            <td class="text-end pe-3 text-indigo" style="color:#4338ca;">₺{{ number_format($kdvAmount, 2, ',', '.') }}</td>
                        </tr>
                        <tr class="fs-6 text-dark">
                            <td colspan="3" class="text-end ps-3">Genel Ödenecek Tutar:</td>
                            <td class="text-end pe-3 text-success">₺{{ number_format($grandTotal, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Notes -->
            <h6 class="fw-bold text-dark mb-2">Hakediş Notları & Açıklama</h6>
            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark" style="white-space: pre-line;">
                {{ $progressPayment->notes ?: 'Ek not bulunmuyor.' }}
            </div>
        </div>
    </div>

    <!-- Right Sidebar: Info Box -->
    <div class="col-lg-4">
        <div class="progress-glass-card">
            <h6 class="fw-bold text-dark mb-3"><i class="ri-information-line text-indigo me-2" style="color:#4338ca;"></i>Hakediş Künyesi</h6>
            <div class="list-group list-group-flush border-0">
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">Proje:</span>
                    <strong class="text-dark">{{ $progressPayment->project?->name ?? '-' }}</strong>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">Sözleşme No:</span>
                    <strong class="text-dark">#{{ $progressPayment->contract?->number ?? $progressPayment->contract_id }}</strong>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">Hakediş Tarihi:</span>
                    <strong class="text-dark">{{ $progressPayment->payment_date ? $progressPayment->payment_date->format('d.m.Y') : '-' }}</strong>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">Son Ödeme Vadesi:</span>
                    <strong class="text-dark">{{ $progressPayment->due_date ? $progressPayment->due_date->format('d.m.Y') : '-' }}</strong>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">İlerleme Oranı:</span>
                    <strong class="text-indigo" style="color:#4338ca;">%{{ number_format($progressPayment->percentage, 2) }}</strong>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">Onaylayan:</span>
                    <strong class="text-dark">{{ $progressPayment->approver?->name ?? 'Onay Bekliyor' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('progress-payments.reject', $progressPayment) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger"><i class="ri-error-warning-line me-1"></i>Hakedişi Reddet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="reject_reason" class="form-label fw-bold text-dark">Reddedilme Gerekçesi <span class="text-danger">*</span></label>
                    <textarea name="reject_reason" id="reject_reason" class="form-control" rows="4" placeholder="Hakediş cetvelindeki uyuşmazlık veya metraj eksikliğini belirtin..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Reddet</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
