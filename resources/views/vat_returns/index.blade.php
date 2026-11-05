@extends('layouts.master')

@section('title', 'KDV İade & YMM Teşvik Yönetimi')

@push('styles')
<style>
    .vat-hero-banner {
        background: linear-gradient(135deg, #064e3b 0%, #0f172a 60%, #059669 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        box-shadow: 0 15px 35px rgba(6, 78, 59, 0.4) !important;
    }
    
    .vat-badge {
        background: rgba(16, 185, 129, 0.2) !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        color: #a7f3d0 !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-vat {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-vat {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-vat {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-vat:focus {
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
            <div class="card border-0 vat-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 vat-badge">
                            <i class="ri-file-signature-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">3065 SAYILI KDV KANUNU MADDE 29/2 İNDİRİMLİ ORAN KDV İADESİ</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">KDV İade & YMM Teşvik Yönetimi</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Kentsel dönüşüm (%1 KDV) ve 150m² altı konut teslimlerinden doğan indirimli orana tabi KDV iade alacaklarının YMM tasdik raporları ve Maliye iade takibi.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-refund-2-line fs-5"></i>Hesaplanan KDV Alacağı: ₺{{ number_format($stats['total_return_amount'], 0, ',', '.') }}
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
            <div class="card custom-kpi-card-vat h-100 border-start border-success border-4" style="border-left-color: #059669 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KDV İADE ALACAĞI</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_return_amount'], 2, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-hand-coin-line me-1"></i>Devletten Alınacak KDV</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #ecfdf5 !important; border: 1px solid #a7f3d0 !important;">
                        <i class="ri-coins-line fs-2" style="color: #059669 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-vat h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">YÜKLENİLEN %20 KDV ALIMLARI</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_kdv20_purchases'], 2, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-shopping-cart-2-line me-1"></i>İnşaat Malzeme Alımları</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-shopping-bag-3-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-vat h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">MALİYE ONAYLI İADELER</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">{{ $stats['approved_count'] }} Dönem</div>
                        <small class="text-warning fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Kasaya Nakit Yattı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-bank-card-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-vat h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">VERGİ DAİRESİNE SUNULAN</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.8rem;">{{ $stats['submitted_count'] }} Rapor</div>
                        <small class="text-info fw-bold"><i class="ri-file-text-line me-1"></i>YMM Tasdik Raporu</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-file-search-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New VAT Return Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-vat shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #ecfdf5; color: #059669;">
                        <i class="ri-file-add-fill fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni KDV İade Raporu Ekle</h5>
                </div>

                <form action="{{ route('vat_returns.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">DÖNEM (AY/YIL) <span class="text-danger">*</span></label>
                        <input type="text" name="period" class="form-control rounded-pill custom-input-vat font-monospace" placeholder="Örn: 2026-08" value="{{ date('Y-m') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">PROJE / KAPSAM TÜRÜ <span class="text-danger">*</span></label>
                        <select name="project_type" class="form-select rounded-pill custom-input-vat" required>
                            <option value="Kentsel Dönüşüm (%1 KDV)" selected>🏠 Kentsel Dönüşüm (%1 KDV Teslimi)</option>
                            <option value="150m2 Altı Konut (%10 KDV)">🏢 150m² Altı Konut Satışı (%10 KDV)</option>
                            <option value="Ticari / Karma Proje (%20 KDV)">🏪 Ticari & Karma Proje (%20 KDV)</option>
                            <option value="Diğer Teşvik İadesi">📋 Diğer İndirimli Oran KDV İadesi</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">%1 KDV'Lİ SATIŞ HACMİ (TL)</label>
                        <input type="number" step="0.01" name="total_kdv1_sales" class="form-control rounded-pill custom-input-vat font-monospace" placeholder="Örn: 5000000.00">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">%10 KDV'Lİ SATIŞ HACMİ (TL)</label>
                        <input type="number" step="0.01" name="total_kdv10_sales" class="form-control rounded-pill custom-input-vat font-monospace" placeholder="Örn: 2000000.00">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">%20 YÜKLENİLEN KDV ALIMLARI (TL) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="total_kdv20_purchases" class="form-control rounded-pill custom-input-vat text-primary fw-bold font-monospace" placeholder="Örn: 8000000.00" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">YMM RAPOR DURUMU <span class="text-danger">*</span></label>
                        <select name="ymm_report_status" class="form-select rounded-pill custom-input-vat" required>
                            <option value="hazirlaniyor" selected>🟡 YMM Raporu Hazırlanıyor</option>
                            <option value="sunuldu">🔵 Vergi Dairesine Sunuldu</option>
                            <option value="onaylandi">🟢 Onaylandı (Kasaya Yattı)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100 rounded-pill py-2.5 fw-extrabold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #059669; border-color: #059669;">
                        <i class="ri-save-3-fill fs-5"></i>KDV İade Kaydını Oluştur
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-file-list-3-fill text-warning fs-5"></i>KDV İade & YMM Tasdik Rapor Listesi
                    </h5>
                    <span class="badge bg-success text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #059669;">{{ $items->total() }} Dönem</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Dönem</th>
                                    <th class="py-3 fw-extrabold text-dark">Kapsam Türü</th>
                                    <th class="py-3 fw-extrabold text-dark">%1 & %10 Satışlar</th>
                                    <th class="py-3 fw-extrabold text-dark">%20 Yüklenimler</th>
                                    <th class="py-3 fw-extrabold text-dark">İade Edilecek KDV (₺)</th>
                                    <th class="py-3 fw-extrabold text-dark">YMM Rapor Durumu</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <span class="badge bg-dark text-warning font-monospace px-3 py-1.5 rounded-pill fw-extrabold" style="font-size: 11.5px;">
                                                {{ $item->period }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-extrabold text-dark fs-6">{{ $item->project_type }}</div>
                                            <small class="text-secondary fw-semibold">İndirimli Oran KDV</small>
                                        </td>
                                        <td>
                                            <div class="font-monospace fw-bold text-dark" style="font-size: 11px;">
                                                %1: ₺{{ number_format($item->total_kdv1_sales, 0, ',', '.') }}
                                            </div>
                                            <div class="font-monospace fw-bold text-muted" style="font-size: 11px;">
                                                %10: ₺{{ number_format($item->total_kdv10_sales, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="font-monospace fw-bold text-primary">
                                            ₺{{ number_format($item->total_kdv20_purchases, 2, ',', '.') }}
                                        </td>
                                        <td class="text-success fw-extrabold font-monospace" style="font-size: 1.15rem;">
                                            ₺{{ number_format($item->calculated_return_amount, 2, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($item->ymm_report_status === 'onaylandi')
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a; font-size: 11px;">
                                                    <i class="ri-checkbox-circle-fill me-1"></i>Onaylandı (Kasada)
                                                </span>
                                            @elseif($item->ymm_report_status === 'sunuldu')
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #0284c7; font-size: 11px;">
                                                    <i class="ri-file-text-line me-1"></i>Vergi Dairesinde
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-time-line me-1"></i>YMM Hazırlığında
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('vat_returns.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu KDV iade kaydını silmek istediğinize emin misiniz?');">
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
                                            <i class="ri-file-signature-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz KDV İade Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan dönemsel satış ve yüklenilen KDV alımlarını girerek otomatik KDV iade alacağınızı hesaplayabilirsiniz.</p>
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
