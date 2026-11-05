@extends('layouts.master')

@section('title', 'AI Destekli Taşeron Puantaj & Hakediş Analizörü')

@push('styles')
<style>
    .aitimesheet-hero-banner {
        background: linear-gradient(135deg, #2e1065 0%, #0f172a 60%, #7c3aed 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(124, 58, 237, 0.3) !important;
        box-shadow: 0 15px 35px rgba(46, 16, 101, 0.4) !important;
    }
    
    .aitimesheet-badge {
        background: rgba(124, 58, 237, 0.2) !important;
        border: 1px solid rgba(124, 58, 237, 0.4) !important;
        color: #ddd6fe !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-aitimesheet {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-aitimesheet {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-aitimesheet {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-aitimesheet:focus {
        background-color: #ffffff !important;
        border-color: #7c3aed !important;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 aitimesheet-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 aitimesheet-badge">
                            <i class="ri-robot-fill text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">YAPAY ZEKA PUANTAJ DENETİMİ & TURNİKE ÇAPRAZ KONTROLÜ</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">AI Destekli Taşeron Puantaj Analizörü</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Yapay zeka algoritmaları ve biyometrik turnike / kamera geçiş verileri ile taşeron puantaj beyanlarının çapraz denetimi, hayalet yevmiye tespiti ve hakediş kontrolü.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-ghost-line fs-5"></i>Hayalet Yevmiye Sapması: {{ $stats['ghost_mandays'] }} Yevmiye
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
            <div class="card custom-kpi-card-aitimesheet h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">HAYALET YEVMİYE FARK</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.6rem;">{{ $stats['ghost_mandays'] }} Yevmiye</div>
                        <small class="text-danger fw-bold"><i class="ri-alert-fill me-1"></i>Turnike Fazlası Beyan</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-ghost-2-fill fs-2" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-aitimesheet h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">BEYAN EDİLEN YEVMİYE</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">{{ number_format($stats['total_claimed'], 0, ',', '.') }} Gün</div>
                        <small class="text-primary fw-bold"><i class="ri-file-user-line me-1"></i>Taşeron Liste Beyanı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-contacts-book-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-aitimesheet h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TURNİKE GERÇEKLEŞEN</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">{{ number_format($stats['total_actual'], 0, ',', '.') }} Gün</div>
                        <small class="text-success fw-bold"><i class="ri-fingerprint-line me-1"></i>Biyometrik Kayıt</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-scan-2-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-aitimesheet h-100 border-start border-warning border-4" style="border-left-color: #7c3aed !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">YÜKSEK RİSKLİ İNCELEME</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">{{ $stats['high_risk_count'] }} İnceleme</div>
                        <small class="text-warning fw-bold"><i class="ri-shield-flash-line me-1"></i>Hakediş Blokesi Önerilen</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f5f3ff !important; border: 1px solid #ddd6fe !important;">
                        <i class="ri-robot-2-line fs-2" style="color: #7c3aed !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New AI Analysis Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-aitimesheet shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #f5f3ff; color: #7c3aed;">
                        <i class="ri-cpu-line fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni AI Puantaj Analizi</h5>
                </div>

                <form action="{{ route('ai_timesheet_analyzers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">TAŞERON FİRMA ADI <span class="text-danger">*</span></label>
                        <input type="text" name="subcontractor_name" class="form-control rounded-pill custom-input-aitimesheet" placeholder="Örn: Anadolu Demircilik Ltd." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">HAKEDİŞ DÖNEMİ <span class="text-danger">*</span></label>
                        <input type="text" name="period" class="form-control rounded-pill custom-input-aitimesheet font-monospace" placeholder="Örn: 2026-08" value="{{ date('Y-m') }}" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">TAŞERON BEYANI <span class="text-danger">*</span></label>
                            <input type="number" name="claimed_mandays" class="form-control rounded-pill custom-input-aitimesheet text-primary font-monospace fw-bold" placeholder="450" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">TURNİKE GERÇEK <span class="text-danger">*</span></label>
                            <input type="number" name="actual_turnstile_mandays" class="form-control rounded-pill custom-input-aitimesheet text-success font-monospace fw-bold" placeholder="380" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">ÖZEL AI RİSK NOTU (OPSİYONEL)</label>
                        <textarea name="ai_risk_assessment" class="form-control rounded-4 custom-input-aitimesheet" rows="3" placeholder="Boş bırakılırsa yapay zeka tarafından otomatik oluşturulur..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">HAKEDİŞ DURUMU <span class="text-danger">*</span></label>
                        <select name="status" class="form-select rounded-pill custom-input-aitimesheet" required>
                            <option value="inceleniyor" selected>🟡 İnceleniyor (AI Tespiti)</option>
                            <option value="onaylandi">🟢 Onaylandı (Şantiye Kabul)</option>
                            <option value="itiraz_var">🔴 İtiraz Var (Bloke Ödeme)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-extrabold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #7c3aed; border-color: #7c3aed;">
                        <i class="ri-sparkles-fill fs-5"></i>AI Analizini Çalıştır
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-robot-2-fill text-warning fs-5"></i>AI Taşeron Puantaj & Turnike Çapraz Kontrol Cetveli
                    </h5>
                    <span class="badge bg-primary text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #7c3aed;">{{ $items->total() }} Analiz</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Taşeron Firma</th>
                                    <th class="py-3 fw-extrabold text-dark">Dönem</th>
                                    <th class="py-3 fw-extrabold text-dark">Beyan vs Gerçek</th>
                                    <th class="py-3 fw-extrabold text-dark">Sapma (%)</th>
                                    <th class="py-3 fw-extrabold text-dark">AI Risk Yorumu</th>
                                    <th class="py-3 fw-extrabold text-dark">Durum</th>
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
                                                {{ $item->period }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="font-monospace fw-bold text-primary" style="font-size: 11px;">
                                                Beyan: {{ $item->claimed_mandays }} Gün
                                            </div>
                                            <div class="font-monospace fw-bold text-success" style="font-size: 11px;">
                                                Turnike: {{ $item->actual_turnstile_mandays }} Gün
                                            </div>
                                        </td>
                                        <td>
                                            @if($item->discrepancy_percentage > 10)
                                                <span class="badge bg-danger text-white rounded-pill px-2.5 py-1 font-monospace fw-bold">
                                                    🚨 %{{ number_format($item->discrepancy_percentage, 1) }}
                                                </span>
                                            @elseif($item->discrepancy_percentage > 0)
                                                <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 font-monospace fw-bold">
                                                    ⚠️ %{{ number_format($item->discrepancy_percentage, 1) }}
                                                </span>
                                            @else
                                                <span class="badge bg-success text-white rounded-pill px-2.5 py-1 font-monospace fw-bold">
                                                    ✅ Uyumlu
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-dark fw-medium d-inline-block text-truncate" style="max-width: 210px;" title="{{ $item->ai_risk_assessment }}">
                                                {{ $item->ai_risk_assessment }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($item->status === 'onaylandi')
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a; font-size: 11px;">
                                                    <i class="ri-checkbox-circle-fill me-1"></i>Onaylandı
                                                </span>
                                            @elseif($item->status === 'itiraz_var')
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-close-circle-fill me-1"></i>İtiraz (Bloke)
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-time-line me-1"></i>İnceleniyor
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('ai_timesheet_analyzers.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu AI analiz kaydını silmek istediğinize emin misiniz?');">
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
                                            <i class="ri-robot-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz AI Puantaj Analiz Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan taşeron firma puantaj beyanı ile turnike verilerini girerek yapay zeka çapraz denetimini başlatabilirsiniz.</p>
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
