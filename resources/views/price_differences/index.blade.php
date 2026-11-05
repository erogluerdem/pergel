@extends('layouts.master')

@section('title', 'Yİ-ÜFE Kamu Fiyat Farkı Hesaplayıcı')

@push('styles')
<style>
    .pricediff-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #2563eb 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(37, 99, 235, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
    }
    
    .pricediff-badge {
        background: rgba(37, 99, 235, 0.2) !important;
        border: 1px solid rgba(37, 99, 235, 0.4) !important;
        color: #93c5fd !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-pricediff {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-pricediff {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-pricediff {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-pricediff:focus {
        background-color: #ffffff !important;
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 pricediff-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 pricediff-badge">
                            <i class="ri-line-chart-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">4734 SAYILI KAMU İHALE KANUNU Yİ-ÜFE ENFLASYON HESABI</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Yİ-ÜFE Kamu Fiyat Farkı Hesaplayıcı</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Kararnamelere uygun Yurt İçi Üretici Fiyat Endeksi \(F = A_n \times (P_n / P_0 - 1)\) resmi fiyat farkı hesaplama ve enflasyonist hakediş revizyon robotu.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-money-dollar-circle-line fs-5"></i>Toplam Fiyat Farkı: ₺{{ number_format($stats['total_price_diff'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-2 bg-success text-white" role="alert">
            <i class="ri-checkbox-circle-fill fs-4"></i>
            <span class="fw-bold fs-6">{{ session('success') }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-pricediff h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KAPSAMDAKİ HAKEDİŞ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_hakedis'], 2, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-wallet-3-line me-1"></i>Ana Hakediş Tutarı ($A_n$)</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-cash-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-pricediff h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM FİYAT FARKI</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_price_diff'], 2, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-line-chart-fill me-1"></i>Hesaplanan İlave Fark ($F$)</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-increase-decrease-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-pricediff h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">ORTALAMA Yİ-ÜFE ARTIŞI</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.6rem;">%{{ number_format($stats['avg_inflation_rate'], 1, ',', '.') }}</div>
                        <small class="text-info fw-bold"><i class="ri-pie-chart-line me-1"></i>Endeks Büyüme Oranı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-pulse-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-pricediff h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KAYITLI HESAPLAMA</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_count'] }} Hesaplama</div>
                        <small class="text-warning fw-bold"><i class="ri-calculator-line me-1"></i>Resmi Rapor</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-file-calculator-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New Price Difference Calculation Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-pricediff shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #eff6ff; color: #2563eb;">
                        <i class="ri-calculator-fill fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni Fiyat Farkı Hesapla</h5>
                </div>

                <form action="{{ route('price_differences.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">SÖZLEŞME / HAKEDİŞ ADI <span class="text-danger">*</span></label>
                        <input type="text" name="contract_name" class="form-control rounded-pill custom-input-pricediff" placeholder="Örn: 2026/04 Hakediş No: 2" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">İHALE AYI ($P_0$)</label>
                            <input type="text" name="base_index_month" class="form-control rounded-pill custom-input-pricediff" placeholder="Örn: 2025-01" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">TEMEL ENDEKS ($P_0$)</label>
                            <input type="number" step="0.01" name="base_index_value" class="form-control rounded-pill custom-input-pricediff font-monospace" placeholder="3250.40" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">HAKEDİŞ AYI ($P_n$)</label>
                            <input type="text" name="current_index_month" class="form-control rounded-pill custom-input-pricediff" placeholder="Örn: 2026-08" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">GÜNCEL ENDEKS ($P_n$)</label>
                            <input type="number" step="0.01" name="current_index_value" class="form-control rounded-pill custom-input-pricediff font-monospace text-primary fw-bold" placeholder="4120.80" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">KAPSAMDAKİ HAKEDİŞ TUTARI ($A_n$) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="hakedis_amount" class="form-control rounded-pill custom-input-pricediff text-success fw-bold" placeholder="Örn: 1500000.00" required>
                    </div>

                    <div class="p-3 bg-light rounded-3 border mb-4" style="background: #f8fafc;">
                        <small class="text-secondary d-block fw-bold mb-1"><i class="ri-functions me-1 text-primary"></i>HESAPLAMA FORMÜLÜ:</small>
                        <div class="font-monospace text-dark fw-bold small">\(F = A_n \times \left(\frac{P_n}{P_0} - 1\right)\)</div>
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Sisteme endeks değerleri girildiğinde ilave fiyat farkı otomatik hesaplanır.</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-extrabold shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #2563eb; border-color: #2563eb;">
                        <i class="ri-save-3-fill fs-5"></i>Fiyat Farkını Hesapla & Kaydet
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-table-line text-warning fs-5"></i>Yİ-ÜFE Kamu Fiyat Farkı Hesaplama Cetveli
                    </h5>
                    <span class="badge bg-primary text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #2563eb;">{{ $items->total() }} Kayıt</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Sözleşme / Hakediş</th>
                                    <th class="py-3 fw-extrabold text-dark">Temel Endeks ($P_0$)</th>
                                    <th class="py-3 fw-extrabold text-dark">Güncel Endeks ($P_n$)</th>
                                    <th class="py-3 fw-extrabold text-dark">Hakediş ($A_n$)</th>
                                    <th class="py-3 fw-extrabold text-dark">Fiyat Farkı ($F$)</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    @php
                                        $ratio = $item->base_index_value > 0 ? (($item->current_index_value / $item->base_index_value) - 1) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-extrabold text-dark fs-6">{{ $item->contract_name }}</div>
                                            <small class="text-secondary fw-semibold">Kamu İhale Sözleşmesi</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark font-monospace px-2.5 py-1 rounded-pill fw-bold text-white mb-1 d-inline-block">{{ $item->base_index_month }}</span>
                                            <div class="font-monospace fw-bold text-secondary" style="font-size: 11px;">$P_0$: {{ number_format($item->base_index_value, 2, ',', '.') }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary border font-monospace px-2.5 py-1 rounded-pill fw-bold mb-1 d-inline-block">{{ $item->current_index_month }}</span>
                                            <div class="font-monospace fw-bold text-primary" style="font-size: 11px;">$P_n$: {{ number_format($item->current_index_value, 2, ',', '.') }} (+%{{ number_format($ratio, 1) }})</div>
                                        </td>
                                        <td class="font-monospace fw-extrabold text-dark" style="font-size: 1.05rem;">
                                            ₺{{ number_format($item->hakedis_amount, 2, ',', '.') }}
                                        </td>
                                        <td class="font-monospace fw-extrabold {{ $item->price_difference_amount >= 0 ? 'text-success' : 'text-danger' }}" style="font-size: 1.1rem;">
                                            {{ $item->price_difference_amount >= 0 ? '+' : '' }}₺{{ number_format($item->price_difference_amount, 2, ',', '.') }}
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('price_differences.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu fiyat farkı hesaplama kaydını silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Sil">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="ri-line-chart-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz Fiyat Farkı Hesaplaması Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan ihale ayı endeksi ($P_0$), hakediş ayı endeksi ($P_n$) ve hakediş tutarını girerek otomatik Yİ-ÜFE fiyat farkını hesaplayabilirsiniz.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
