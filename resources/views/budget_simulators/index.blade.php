@extends('layouts.master')

@section('title', 'İnşaat Bütçe, Döviz Kuru & Enflasyon Simülatörü')

@push('styles')
<style>
    .budgetsim-hero-banner {
        background: linear-gradient(135deg, #064e3b 0%, #0f172a 60%, #059669 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        box-shadow: 0 15px 35px rgba(6, 78, 59, 0.4) !important;
    }
    
    .budgetsim-badge {
        background: rgba(16, 185, 129, 0.2) !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        color: #a7f3d0 !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-budgetsim {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-budgetsim {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-budgetsim {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-budgetsim:focus {
        background-color: #ffffff !important;
        border-color: #059669 !important;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 budgetsim-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 budgetsim-badge">
                            <i class="ri-pie-chart-2-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">MAKROEKONOMİK BÜTÇE STRES TESTİ & KUR SİMÜLASYONU</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Bütçe, Kur & Enflasyon Simülatörü</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Makroekonomik döviz kuru (USD/TRY) dalgalanmaları, TÜİK Yİ-ÜFE malzeme enflasyon beklentileri ve şantiye bütçe risk simülasyon robotu.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-alert-line fs-5"></i>Bütçe Aşım Sapması: ₺{{ number_format($stats['total_variance'], 0, ',', '.') }}
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
            <div class="card custom-kpi-card-budgetsim h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">MEVCUT KALAN BÜTÇE</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_remaining_budget'], 2, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-wallet-3-line me-1"></i>Mevcut Baz Bütçe</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-money-dollar-box-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-budgetsim h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">SİMÜLE EDİLEN MALİYET</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_simulated_cost'], 2, ',', '.') }}</div>
                        <small class="text-warning fw-bold"><i class="ri-line-chart-line me-1"></i>Enflasyon Sonrası Maliyet</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-funds-box-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-budgetsim h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TAHMİNİ BÜTÇE AŞIMI</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.6rem;">+₺{{ number_format($stats['total_variance'], 2, ',', '.') }}</div>
                        <small class="text-danger fw-bold"><i class="ri-error-warning-line me-1"></i>Maliyet Artış Sapması</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-pulse-fill fs-2" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-budgetsim h-100 border-start border-success border-4" style="border-left-color: #059669 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KAYITLI SENARYOLAR</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_scenarios'] }} Senaryo</div>
                        <small class="text-success fw-bold"><i class="ri-file-chart-line me-1"></i>Stres Testi Simülasyonu</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #ecfdf5 !important; border: 1px solid #a7f3d0 !important;">
                        <i class="ri-pulse-line fs-2" style="color: #059669 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New Budget Simulation Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-budgetsim shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #ecfdf5; color: #059669;">
                        <i class="ri-calculator-line fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni Bütçe Senaryosu Simüle Et</h5>
                </div>

                <form action="{{ route('budget_simulators.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">SENARYO ADI <span class="text-danger">*</span></label>
                        <input type="text" name="scenario_name" class="form-control rounded-pill custom-input-budgetsim" placeholder="Örn: 2026 Q4 Kötümser Enflasyon Şoku" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">USD KURU (ÖNGÖRÜ) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="usd_exchange_rate" class="form-control rounded-pill custom-input-budgetsim font-monospace text-primary fw-bold" placeholder="42.50" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">ENFLASYON (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="inflation_expectation" class="form-control rounded-pill custom-input-budgetsim font-monospace text-danger fw-bold" placeholder="35.0" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">MEVCUT KALAN BÜTÇE (TL) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="current_remaining_budget" class="form-control rounded-pill custom-input-budgetsim text-success fw-bold font-monospace" placeholder="Örn: 10000000.00" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">RİSK SEVİYESİ SEÇİMİ</label>
                        <select name="risk_level" class="form-select rounded-pill custom-input-budgetsim">
                            <option value="" selected>🤖 Yapay Zeka Otomatik Belirlesin</option>
                            <option value="Dusuk">🟢 Düşük Risk (Kontrollü)</option>
                            <option value="Orta">🟡 Orta Risk (Hassas)</option>
                            <option value="Yuksek">🔴 Yüksek Risk (Bütçe Aşımı)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100 rounded-pill py-2.5 fw-extrabold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #059669; border-color: #059669;">
                        <i class="ri-play-circle-fill fs-5"></i>Senaryoyu Simüle Et
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-pulse-line text-warning fs-5"></i>Makroekonomik Bütçe Stres Test Cetveli
                    </h5>
                    <span class="badge bg-success text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #059669;">{{ $items->total() }} Senaryo</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Senaryo Adı</th>
                                    <th class="py-3 fw-extrabold text-dark">Döviz & Enflasyon</th>
                                    <th class="py-3 fw-extrabold text-dark">Kalan Bütçe</th>
                                    <th class="py-3 fw-extrabold text-dark">Simüle Edilen Maliyet</th>
                                    <th class="py-3 fw-extrabold text-dark">Sapma Tutarı (TL)</th>
                                    <th class="py-3 fw-extrabold text-dark">Risk Seviyesi</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-extrabold text-dark fs-6">{{ $item->scenario_name }}</div>
                                            <small class="text-secondary fw-semibold">Ekonomik Stres Testi</small>
                                        </td>
                                        <td>
                                            <div class="font-monospace fw-bold text-primary" style="font-size: 11.5px;">
                                                USD: ₺{{ number_format($item->usd_exchange_rate, 2, ',', '.') }}
                                            </div>
                                            <div class="font-monospace fw-bold text-danger" style="font-size: 11.5px;">
                                                Enflasyon: %{{ number_format($item->inflation_expectation, 1) }}
                                            </div>
                                        </td>
                                        <td class="font-monospace fw-extrabold text-dark" style="font-size: 1.05rem;">
                                            ₺{{ number_format($item->current_remaining_budget, 2, ',', '.') }}
                                        </td>
                                        <td class="font-monospace fw-bold text-warning" style="font-size: 1.05rem;">
                                            ₺{{ number_format($item->simulated_projected_cost, 2, ',', '.') }}
                                        </td>
                                        <td class="font-monospace fw-extrabold text-danger" style="font-size: 1.1rem;">
                                            +₺{{ number_format($item->variance_amount, 2, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($item->risk_level === 'Yuksek')
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-alert-fill me-1"></i>Yüksek Risk
                                                </span>
                                            @elseif($item->risk_level === 'Orta')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-time-line me-1"></i>Orta Risk
                                                </span>
                                            @else
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a; font-size: 11px;">
                                                    <i class="ri-checkbox-circle-fill me-1"></i>Düşük Risk
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('budget_simulators.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu simülasyon senaryosunu silmek istediğinize emin misiniz?');">
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
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="ri-calculator-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz Bütçe Simülasyon Senaryosu Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan Dolar kuru, enflasyon öngörüsü ve mevcut bütçenizi girerek ilk makroekonomik stres testinizi yapabilirsiniz.</p>
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
