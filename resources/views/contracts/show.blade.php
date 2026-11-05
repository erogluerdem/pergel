@extends('layouts.master')

@section('title', $contract->name . ' — Sözleşme Detayı')
@section('topbar-title', 'Sözleşme Detayı')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & EMERALD GOLD GLASSMORPHIC THEME */
    .contract-show-banner {
        background: linear-gradient(135deg, #0f172a 0%, #065f46 50%, #047857 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .contract-show-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #f59e0b, #3b82f6, #10b981);
        background-size: 300% 100%;
        animation: contractShowGlow 4s linear infinite;
    }

    @keyframes contractShowGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .contract-badge-pill {
        background: rgba(16, 185, 129, 0.2) !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        color: #a7f3d0 !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-show {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .custom-kpi-card-show:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-card-show {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 20px !important;
        background: #ffffff !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .spec-box {
        border-radius: 14px !important;
        padding: 16px 18px !important;
        background: #f8fafc !important;
        border: 1.5px solid #e2e8f0 !important;
    }

    .progress-bar-custom {
        height: 10px;
        border-radius: 10px;
        background-color: #e2e8f0;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 10px;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="contract-show-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <a href="{{ route('contracts.management') }}" class="btn btn-sm btn-light rounded-pill px-3.5 py-1.5 fw-extrabold text-dark shadow-sm">
                        <i class="ri-arrow-left-line me-1"></i>Sözleşmeler Listesine Dön
                    </a>
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill contract-badge-pill">
                        <i class="ri-barcode-line text-warning fs-5"></i> KOD: {{ $contract->number ?? 'SZ-NO-YOK' }}
                    </span>
                    @php $st = strtolower($contract->status ?? 'active'); @endphp
                    @if($st === 'active' || $st === 'aktif')
                        <span class="badge bg-success text-white rounded-pill px-3 py-2 fw-extrabold">
                            <i class="ri-checkbox-circle-fill me-1"></i>Aktif Kullanımda
                        </span>
                    @elseif($st === 'completed' || $st === 'tamamlandi')
                        <span class="badge bg-primary text-white rounded-pill px-3 py-2 fw-extrabold">
                            <i class="ri-check-double-fill me-1"></i>Tamamlandı
                        </span>
                    @else
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-extrabold">{{ $contract->status }}</span>
                    @endif
                </div>

                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">{{ $contract->name }}</h2>
                <p class="mb-0 fs-6" style="color: #d1fae5; font-weight: 500;">
                    <i class="ri-building-line text-warning me-1"></i><strong>{{ $contract->company ?? 'Firma Belirtilmemiş' }}</strong> — 
                    <i class="ri-building-2-line text-info me-1"></i>{{ $contract->project ? $contract->project->name : 'Genel Proje Portföyü' }}
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('contracts.edit', $contract->id) }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold shadow-lg d-inline-flex align-items-center gap-1.5">
                        <i class="ri-pencil-line fs-5"></i> Sözleşmeyi Düzenle
                    </a>
                    @if(\Illuminate\Support\Facades\Route::has('contracts.documents.index'))
                        <a href="{{ route('contracts.documents.index', $contract->id) }}" class="btn btn-light text-dark rounded-pill px-3.5 py-2.5 fw-extrabold shadow-sm d-inline-flex align-items-center gap-1.5">
                            <i class="ri-folder-open-line fs-5"></i> Dokümanlar & Evraklar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @php
        $cVal = (float) $contract->value;
        $cPaid = (float) $contract->paid;
        $cBal = max(0, $cVal - $cPaid);
        $cPercent = $cVal > 0 ? min(100, round(($cPaid / $cVal) * 100, 1)) : 0;
        $retentionEst = $cVal * 0.05;
    @endphp

    <!-- FINANCIAL SUMMARY CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-show h-100 border-start border-primary border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">TOPLAM SÖZLEŞME BEDELİ</small>
                <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.6rem;">₺{{ number_format($cVal, 2, ',', '.') }}</div>
                <small class="text-primary fw-bold mt-1 d-block"><i class="ri-money-dollar-circle-line me-1"></i>Taahhüt Edilen Tutar</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-show h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">TOPLAM ÖDENEN HAKEDİŞ</small>
                <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">₺{{ number_format($cPaid, 2, ',', '.') }}</div>
                <small class="text-success fw-bold mt-1 d-block"><i class="ri-checkbox-circle-fill me-1"></i>%{{ $cPercent }} İlerleme Tamamlandı</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-show h-100 border-start border-warning border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">KALAN TAAHHÜT BAKİYE</small>
                <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.6rem;">₺{{ number_format($cBal, 2, ',', '.') }}</div>
                <small class="text-warning fw-bold mt-1 d-block"><i class="ri-time-line me-1"></i>Gelecek Hakediş Ödemeleri</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-show h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">TAHMİNİ NAKİT TEMİNAT</small>
                <div class="fw-extrabold text-info font-monospace" style="font-size: 1.6rem;">₺{{ number_format($retentionEst, 2, ',', '.') }}</div>
                <small class="text-info fw-bold mt-1 d-block"><i class="ri-shield-check-line me-1"></i>%5 Kesinti Bloğu</small>
            </div>
        </div>
    </div>

    <!-- MAIN TWO-COLUMN SECTION -->
    <div class="row g-4 mb-4">
        <!-- LEFT COLUMN: SPECIFICATIONS & NOTES & PAYMENTS -->
        <div class="col-lg-8">
            
            <!-- SPECIFICATIONS CARD -->
            <div class="card custom-card-show mb-4">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-information-fill text-warning fs-5"></i> Sözleşme Kimlik & Takvim Parametreleri
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="spec-box">
                                <small class="text-muted fw-extrabold d-block mb-1" style="font-size: 11px;">SÖZLEŞME NUMARASI</small>
                                <strong class="text-dark font-monospace fs-6">{{ $contract->number ?? 'SZ-NO-YOK' }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="spec-box">
                                <small class="text-muted fw-extrabold d-block mb-1" style="font-size: 11px;">SÖZLEŞME TİPİ</small>
                                <span class="badge bg-dark text-warning font-monospace px-3 py-1.5 rounded-pill fw-bold" style="font-size: 12px;">
                                    {{ $contract->type ?? 'Genel Sözleşme' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="spec-box">
                                <small class="text-muted fw-extrabold d-block mb-1" style="font-size: 11px;">BAŞLANGIÇ TARİHİ</small>
                                <strong class="text-dark fs-6"><i class="ri-calendar-line text-primary me-1"></i>{{ $contract->start_date ? $contract->start_date->format('d.m.Y') : 'Belirtilmedi' }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="spec-box">
                                <small class="text-muted fw-extrabold d-block mb-1" style="font-size: 11px;">BİTİŞ TARİHİ</small>
                                <strong class="text-dark fs-6"><i class="ri-calendar-check-line text-danger me-1"></i>{{ $contract->end_date ? $contract->end_date->format('d.m.Y') : 'Belirtilmedi' }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- PROGRESS BAR CONTAINER -->
                    <div class="p-3.5 rounded-4 bg-light border mb-4" style="border-color: #cbd5e1 !important;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-extrabold text-dark fs-6"><i class="ri-line-chart-fill text-success me-1"></i> Finansal İlerleme Durumu:</span>
                            <span class="fw-extrabold text-success font-monospace fs-5">%{{ $cPercent }}</span>
                        </div>
                        <div class="progress progress-bar-custom mb-2">
                            <div class="progress-bar-fill bg-success" style="width: {{ $cPercent }}%;"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center text-muted small fw-bold">
                            <span>Ödenen: ₺{{ number_format($cPaid, 2, ',', '.') }}</span>
                            <span class="text-warning font-monospace">Kalan Bakiye: ₺{{ number_format($cBal, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- NOTES & CLAUSES METİN -->
                    <h6 class="fw-extrabold text-dark mb-2"><i class="ri-file-text-line text-emerald me-1" style="color: #047857;"></i> Notlar, Şartname & Özel Maddeler</h6>
                    <div class="p-3.5 border rounded-4 bg-light text-dark font-monospace fs-6" style="white-space: pre-line; min-height: 120px; line-height: 1.6; border-color: #cbd5e1 !important;">
                        {{ $contract->notes ?: 'Bu sözleşmeye ait henüz özel şartname notu veya ilave madde girilmedi.' }}
                    </div>
                </div>
            </div>

            <!-- PAYMENTS HISTORY TABLE -->
            <div class="card custom-card-show mb-4">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-bank-card-line text-warning fs-5"></i> İlişkili Hakedişler & Ödeme Geçmişi
                    </h5>
                    <span class="badge bg-success text-white rounded-pill px-3 py-1 font-monospace fw-bold">{{ $contract->payments->count() }} Ödeme</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">HAKEDİŞ NO</th>
                                    <th class="py-3 fw-extrabold text-dark">TARİH</th>
                                    <th class="py-3 fw-extrabold text-dark">HAKEDİŞ TUTARI (₺)</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">DURUM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contract->payments as $p)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <strong class="text-dark font-monospace fs-6">#{{ $p->no ?? $p->id }}</strong>
                                        </td>
                                        <td class="font-monospace fw-bold text-muted">
                                            {{ $p->date ?? '-' }}
                                        </td>
                                        <td class="font-monospace fw-extrabold text-success fs-6">
                                            ₺{{ number_format((float)($p->approved ?? $p->requested ?? 0), 2, ',', '.') }}
                                        </td>
                                        <td class="pe-4 text-end">
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                                <i class="ri-checkbox-circle-fill me-1"></i>{{ $p->status ?? 'Ödendi' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="ri-money-dollar-circle-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz Hakediş Ödemesi Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Bu sözleşmeye ait henüz onaylanmış bir hakediş ödemesi yapılmadı.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT SIDEBAR METADATA -->
        <div class="col-lg-4">
            
            <div class="card custom-card-show p-4 mb-4">
                <h6 class="fw-extrabold text-dark border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <i class="ri-user-star-line text-warning fs-4"></i> Sözleşme Taraf Bilgileri
                </h6>

                <div class="mb-3">
                    <small class="text-muted fw-bold d-block mb-1" style="font-size: 11px;">YÜKLENİCİ / TAŞERON FİRMA</small>
                    <strong class="text-dark fs-6 d-block"><i class="ri-building-line text-emerald me-1"></i>{{ $contract->company ?? 'Belirtilmedi' }}</strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted fw-bold d-block mb-1" style="font-size: 11px;">İLGİLİ PROJE</small>
                    <strong class="text-dark fs-6 d-block"><i class="ri-building-2-line text-info me-1"></i>{{ $contract->project ? $contract->project->name : 'Genel Proje' }}</strong>
                </div>

                <div class="mb-0">
                    <small class="text-muted fw-bold d-block mb-1" style="font-size: 11px;">OLUŞTURAN YETKİLİ</small>
                    <strong class="text-dark fs-6 d-block"><i class="ri-user-3-line text-primary me-1"></i>{{ $contract->createdBy ? $contract->createdBy->name : 'Sistem Yöneticisi' }}</strong>
                </div>
            </div>

            <!-- DANGER ZONE CARD -->
            <div class="card custom-card-show p-4 border-danger border-opacity-30">
                <h6 class="fw-extrabold text-danger mb-2 d-flex align-items-center gap-2">
                    <i class="ri-error-warning-line fs-4"></i> Sözleşme Kaydını Sil
                </h6>
                <p class="text-secondary small mb-3" style="line-height: 1.5;">
                    Bu sözleşme kaydını sistemden kalıcı olarak kaldırmak için aşağıdaki silme işlemini onaylayabilirsiniz.
                </p>
                <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" onsubmit="return confirm('Bu sözleşmeyi silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill w-100 py-2 fw-extrabold d-flex align-items-center justify-content-center gap-1">
                        <i class="ri-delete-bin-line fs-5"></i> Sözleşmeyi Kalıcı Olarak Sil
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>

@endsection
