@extends('layouts.master')

@section('title', 'Taşeron Yasal Kesintiler & Hakediş Matrisi')

@push('styles')
<style>
    .deduction-hero-banner {
        background: linear-gradient(135deg, #881337 0%, #0f172a 60%, #e11d48 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(225, 29, 72, 0.3) !important;
        box-shadow: 0 15px 35px rgba(136, 19, 55, 0.35) !important;
    }
    
    .deduction-badge {
        background: rgba(225, 29, 72, 0.2) !important;
        border: 1px solid rgba(225, 29, 72, 0.4) !important;
        color: #fecdd3 !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-deduction {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-deduction {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-deduction {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-deduction:focus {
        background-color: #ffffff !important;
        border-color: #e11d48 !important;
        box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 deduction-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 deduction-badge">
                            <i class="ri-percent-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">T.C. MALİYE VERGİ MEVZUATI & YASAL KESİNTİ ROBOTU</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Taşeron Yasal Kesintiler & Hakediş Matrisi</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Taşeron hakedişlerinden yapılan %5 Teminat Kesintisi, Binde 9.48 Damga Vergisi ve Gelir Stopaj Vergisinin resmi mevzuata uygun otomatik hesaplanması ve net ödeme matrisi.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-calculator-line fs-5"></i>Net Ödenecek Hakediş: ₺{{ number_format($stats['total_net'], 0, ',', '.') }}
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
            <div class="card custom-kpi-card-deduction h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM BRÜT HAKEDİŞ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_gross'], 2, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>Hakediş İmalat Hacmi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-wallet-3-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-deduction h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">NET ÖDENECEK HAKEDİŞ</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_net'], 2, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Taşerona Ödenecek Net</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-hand-coin-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-deduction h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TEMİNAT KESİNTİSİ (%5)</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_teminat'], 2, ',', '.') }}</div>
                        <small class="text-warning fw-bold"><i class="ri-safe-2-line me-1"></i>Garanti / Teminat Tutarı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-shield-keyhole-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-deduction h-100 border-start border-danger border-4" style="border-left-color: #e11d48 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">DAMGA & STOPAJ VERGİSİ</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_damga'] + $stats['total_stopaj'], 2, ',', '.') }}</div>
                        <small class="text-danger fw-bold"><i class="ri-government-line me-1"></i>Vergi Dairesi Ödemesi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-government-fill fs-2" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New Deduction Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-deduction shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #fff1f2; color: #e11d48;">
                        <i class="ri-calculator-fill fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Hakediş Kesintisi Hesapla</h5>
                </div>

                <form action="{{ route('subcontractor_deductions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">TAŞERON FİRMA ADI <span class="text-danger">*</span></label>
                        <input type="text" name="subcontractor_name" class="form-control rounded-pill custom-input-deduction" placeholder="Örn: Anadolu Kaba İnşaat Ltd." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">HAKEDİŞ DÖNEMİ / NO <span class="text-danger">*</span></label>
                        <input type="text" name="hakedis_period" class="form-control rounded-pill custom-input-deduction" placeholder="Örn: 2026-08 Hakediş No: 3" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">BRÜT HAKEDİŞ TUTARI (TL) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="gross_amount" class="form-control rounded-pill custom-input-deduction text-primary fw-bold" placeholder="Örn: 500000.00" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">TEMİNAT (%)</label>
                            <input type="number" step="0.1" name="teminat_kesintisi_rate" class="form-control rounded-pill custom-input-deduction font-monospace" value="5.00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">STOPAJ (%)</label>
                            <input type="number" step="0.1" name="stopaj_rate" class="form-control rounded-pill custom-input-deduction font-monospace" value="0.00">
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border mb-4" style="background: #f8fafc;">
                        <small class="text-secondary d-block fw-bold mb-1"><i class="ri-information-fill text-warning me-1"></i>YASAL KESİNTİ ORANLARI:</small>
                        <ul class="mb-0 ps-3 small text-muted font-monospace" style="font-size: 11px;">
                            <li>Damga Vergisi: <strong>‰9.48</strong> (Binde 9.48 otomatik)</li>
                            <li>Teminat Kesintisi: <strong>%5.00</strong> (İmalat Teminatı)</li>
                        </ul>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 rounded-pill py-2.5 fw-extrabold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #e11d48; border-color: #e11d48;">
                        <i class="ri-save-3-fill fs-5"></i>Hesapla & Kaydet
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-table-line text-warning fs-5"></i>Taşeron Hakediş Yasal Kesinti Matrisi
                    </h5>
                    <span class="badge bg-danger text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #e11d48;">{{ $items->total() }} Hakediş</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Taşeron Firma</th>
                                    <th class="py-3 fw-extrabold text-dark">Hakediş Dönemi</th>
                                    <th class="py-3 fw-extrabold text-dark">Brüt (₺)</th>
                                    <th class="py-3 fw-extrabold text-dark">Teminat (%5)</th>
                                    <th class="py-3 fw-extrabold text-dark">Damga Vergisi (‰9.48)</th>
                                    <th class="py-3 fw-extrabold text-dark">Net Ödenecek (₺)</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-extrabold text-dark fs-6">{{ $item->subcontractor_name }}</div>
                                            <small class="text-secondary fw-semibold">Taşeron Yüklenici</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark text-warning font-monospace px-3 py-1.5 rounded-pill fw-extrabold" style="font-size: 11.5px;">
                                                {{ $item->hakedis_period }}
                                            </span>
                                        </td>
                                        <td class="font-monospace fw-extrabold text-dark" style="font-size: 1.05rem;">
                                            ₺{{ number_format($item->gross_amount, 2, ',', '.') }}
                                        </td>
                                        <td class="font-monospace fw-bold text-warning">
                                            ₺{{ number_format($item->teminat_amount, 2, ',', '.') }}
                                        </td>
                                        <td class="font-monospace fw-bold text-danger">
                                            ₺{{ number_format($item->damga_vergisi_amount, 2, ',', '.') }}
                                        </td>
                                        <td class="text-success fw-extrabold font-monospace" style="font-size: 1.15rem;">
                                            ₺{{ number_format($item->net_amount, 2, ',', '.') }}
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('subcontractor_deductions.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu hakediş kesinti kaydını silmek istediğinize emin misiniz?');">
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
                                            <h6 class="fw-bold text-dark">Henüz Taşeron Kesinti Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan taşeron firma adı ve brüt hakediş tutarını girerek yasal teminat ve damga vergisi kesintisini otomatik hesaplayabilirsiniz.</p>
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
