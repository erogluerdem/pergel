@extends('layouts.master')

@section('title', 'SPK Gayrimenkul Değerleme & Portföy Ekspertizi')

@push('styles')
<style>
    .spk-hero-banner {
        background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 60%, #4f46e5 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(79, 70, 229, 0.3) !important;
        box-shadow: 0 15px 35px rgba(30, 27, 75, 0.4) !important;
    }
    
    .spk-badge {
        background: rgba(79, 70, 229, 0.2) !important;
        border: 1px solid rgba(79, 70, 229, 0.4) !important;
        color: #c7d2fe !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-spk {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-spk {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-spk {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-spk:focus {
        background-color: #ffffff !important;
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 spk-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 spk-badge">
                            <i class="ri-government-fill text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">SPK LİSANSLI BAĞIMSIZ GAYRİMENKUL DEĞERLEME & EKSPERTİZ</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">SPK Gayrimenkul Değerleme Raporları</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Sermaye Piyasası Kurulu (SPK) lisanslı değerleme şirketlerince hazırlanan bağımsız bölüm ekspertiz raporları, gayrimenkul portföy değerleme revizyonları ve değer artış analizi.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-building-line fs-5"></i>Portföy Ekspertiz Değeri: ₺{{ number_format($stats['total_current_value'], 0, ',', '.') }}
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
            <div class="card custom-kpi-card-spk h-100 border-start border-primary border-4" style="border-left-color: #4f46e5 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">GÜNCEL SPK EKSPERTİZ DEĞERİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_current_value'], 2, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-cash-line me-1"></i>Portföy Piyasa Değeri</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eef2ff !important; border: 1px solid #c7d2fe !important;">
                        <i class="ri-money-dollar-box-fill fs-2" style="color: #4f46e5 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-spk h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">EKSPERTİZ DEĞER ARTIŞI</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">+₺{{ number_format($stats['growth_amount'], 2, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-arrow-up-line me-1"></i>Artış Oranı: +%{{ number_format($stats['growth_rate'], 1, ',', '.') }}</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-funds-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-spk h-100 border-start border-secondary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">ÖNCEKİ EKSPERTİZ TUTARI</small>
                        <div class="fw-extrabold text-secondary font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_previous_value'], 2, ',', '.') }}</div>
                        <small class="text-secondary fw-bold"><i class="ri-history-line me-1"></i>Önceki Rapor Dönemi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f8fafc !important; border: 1px solid #e2e8f0 !important;">
                        <i class="ri-time-line fs-2 text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-spk h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KAYITLI RAPOR SAYISI</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_count'] }} Rapor</div>
                        <small class="text-warning fw-bold"><i class="ri-file-seal-line me-1"></i>SPK Lisanslı Tasdik</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-verified-badge-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New SPK Valuation Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-spk shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #eef2ff; color: #4f46e5;">
                        <i class="ri-file-add-fill fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni SPK Değerleme Ekle</h5>
                </div>

                <form action="{{ route('real_estate_valuations.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">VARLIK / BAĞIMSIZ BÖLÜM ADI <span class="text-danger">*</span></label>
                        <input type="text" name="asset_name" class="form-control rounded-pill custom-input-spk" placeholder="Örn: Çukurova Kuleleri A Blok #14 Daire" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">SPK DEĞERLEME FİRMASI ADI <span class="text-danger">*</span></label>
                        <input type="text" name="valuation_company" class="form-control rounded-pill custom-input-spk" placeholder="Örn: TSKB Gayrimenkul Değerleme A.Ş." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">SPK LİSANS NO</label>
                        <input type="text" name="spk_license_number" class="form-control rounded-pill custom-input-spk font-monospace" placeholder="Örn: SPK-LIS-98402">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">EKSPERTİZ RAPOR TARİHİ <span class="text-danger">*</span></label>
                        <input type="date" name="report_date" class="form-control rounded-pill custom-input-spk" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">ÖNCEKİ DEĞER (TL)</label>
                            <input type="number" step="0.01" name="previous_value" class="form-control rounded-pill custom-input-spk font-monospace" placeholder="Örn: 4500000.00">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">GÜNCEL DEĞER (TL) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="current_value" class="form-control rounded-pill custom-input-spk text-success fw-bold font-monospace" placeholder="Örn: 6200000.00" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-extrabold shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #4f46e5; border-color: #4f46e5;">
                        <i class="ri-save-3-fill fs-5"></i>SPK Raporunu Kaydet
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-table-line text-warning fs-5"></i>SPK Gayrimenkul Ekspertiz & Değerleme Cetveli
                    </h5>
                    <span class="badge bg-primary text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #4f46e5;">{{ $items->total() }} Rapor</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Varlık / Gayrimenkul</th>
                                    <th class="py-3 fw-extrabold text-dark">SPK Değerleme Şirketi</th>
                                    <th class="py-3 fw-extrabold text-dark">Rapor Tarihi</th>
                                    <th class="py-3 fw-extrabold text-dark">Önceki Değer</th>
                                    <th class="py-3 fw-extrabold text-dark">Güncel SPK Değeri (₺)</th>
                                    <th class="py-3 fw-extrabold text-dark">Değer Artışı</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    @php
                                        $prev = floatval($item->previous_value ?? 0);
                                        $curr = floatval($item->current_value ?? 0);
                                        $diff = $curr - $prev;
                                        $growth = $prev > 0 ? (($curr / $prev) - 1) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-extrabold text-dark fs-6">{{ $item->asset_name }}</div>
                                            <small class="text-secondary fw-semibold">Portföy Varlığı</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->valuation_company }}</div>
                                            @if($item->spk_license_number)
                                                <span class="badge bg-secondary-subtle text-dark border font-monospace px-2 py-0.5 rounded-pill" style="font-size: 10.5px;">
                                                    {{ $item->spk_license_number }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="font-monospace fw-bold text-dark" style="font-size: 11.5px;">
                                            {{ \Carbon\Carbon::parse($item->report_date)->format('d.m.Y') }}
                                        </td>
                                        <td class="font-monospace fw-bold text-muted" style="font-size: 11px;">
                                            ₺{{ number_format($prev, 2, ',', '.') }}
                                        </td>
                                        <td class="text-success fw-extrabold font-monospace" style="font-size: 1.15rem;">
                                            ₺{{ number_format($curr, 2, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($prev > 0)
                                                <span class="badge {{ $diff >= 0 ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger' }} rounded-pill px-2.5 py-1 font-monospace fw-bold">
                                                    {{ $diff >= 0 ? '+' : '' }}%{{ number_format($growth, 1) }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 font-monospace">İlk Ekspertiz</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('real_estate_valuations.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu SPK değerleme raporunu silmek istediğinize emin misiniz?');">
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
                                            <i class="ri-building-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz SPK Gayrimenkul Değerleme Raporu Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan gayrimenkul bağımsız bölüm adı, SPK değerleme şirketi ve ekspertiz değerini girerek ilk değerleme raporunuzu kaydedebilirsiniz.</p>
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
