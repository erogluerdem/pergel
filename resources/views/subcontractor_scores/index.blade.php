@extends('layouts.master')

@section('title', 'Taşeron Puanlama & AI Performans Karnesi')

@push('styles')
<style>
    .score-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
    }
    
    .score-badge {
        background: rgba(245, 158, 11, 0.2) !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-score {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-score {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-score {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-score:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 score-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 score-badge">
                            <i class="ri-award-fill text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">AI İMALAT KALİTE & İSG PERFORMANS KARNESİ</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Taşeron Puanlama & Performans Karnesi</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Şantiyelerdeki taşeron yüklenicilerin imalat kalitesi, İSG güvenilirlik puanları ve imalat teslim hızı kriterlerine dayalı yapay zeka tabanlı performans derecelendirme portalı.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-star-smile-line fs-5"></i>Ortalama Taşeron Skoru: {{ $stats['avg_score'] }} / 100
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
            <div class="card custom-kpi-card-score h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">ORTALAMA FİLO SKORU</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['avg_score'] }} / 100</div>
                        <small class="text-warning fw-bold"><i class="ri-star-fill me-1"></i>Genel Taşeron Karnesi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-star-half-s-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-score h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">YILDIZ TAŞERONLAR (A+ & A)</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $stats['grade_a_count'] }} Firma</div>
                        <small class="text-success fw-bold"><i class="ri-medal-fill me-1"></i>Yüksek Performans</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-award-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-score h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">RİSKLİ TAŞERONLAR (C)</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.8rem;">{{ $stats['grade_c_count'] }} Firma</div>
                        <small class="text-danger fw-bold"><i class="ri-error-warning-line me-1"></i>Sözleşme İncelemesinde</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-alarm-warning-line fs-2" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-score h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">PUANLANAN TAŞERON</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_subcontractors'] }} Kayıt</div>
                        <small class="text-primary fw-bold"><i class="ri-building-line me-1"></i>Aktif Yüklenici Portföyü</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-hard-hat-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New Score Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-score shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #fffbeb; color: #d97706;">
                        <i class="ri-star-add-fill fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Taşeron Performans Hesapla</h5>
                </div>

                <form action="{{ route('subcontractor_scores.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">TAŞERON FİRMA ADI <span class="text-danger">*</span></label>
                        <input type="text" name="subcontractor_name" class="form-control rounded-pill custom-input-score" placeholder="Örn: Anadolu Kaba İnşaat Ltd." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">AI GÜVENİLİRLİK & KALİTE (0-100) <span class="text-danger">*</span></label>
                        <input type="number" min="0" max="100" name="ai_reliability_score" class="form-control rounded-pill custom-input-score font-monospace text-primary fw-bold" placeholder="95" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">İSG & İŞ GÜVENLİĞİ PUANI (0-100) <span class="text-danger">*</span></label>
                        <input type="number" min="0" max="100" name="safety_score" class="form-control rounded-pill custom-input-score font-monospace text-success fw-bold" placeholder="90" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">HIZ & İLERLEME PUANI (0-100) <span class="text-danger">*</span></label>
                        <input type="number" min="0" max="100" name="speed_score" class="form-control rounded-pill custom-input-score font-monospace text-warning fw-bold" placeholder="85" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">ÖZEL SİSTEM YORUMU (OPSİYONEL)</label>
                        <textarea name="system_comment" class="form-control rounded-4 custom-input-score" rows="2" placeholder="Boş bırakılırsa puana göre otomatik oluşturulur..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 rounded-pill py-2.5 fw-extrabold text-dark shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="ri-save-3-fill fs-5"></i>Performans Karnesini Kaydet
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-bar-chart-grouped-line text-warning fs-5"></i>Taşeron Performans & Karne Sıralama Cetveli
                    </h5>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace fw-extrabold">{{ $items->total() }} Yüklenici</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Taşeron Firma</th>
                                    <th class="py-3 fw-extrabold text-dark">Kalite / İSG / Hız Skorları</th>
                                    <th class="py-3 fw-extrabold text-dark">Genel Skor</th>
                                    <th class="py-3 fw-extrabold text-dark">Karne Notu</th>
                                    <th class="py-3 fw-extrabold text-dark">Sistem Değerlendirmesi</th>
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
                                        <td style="min-width: 200px;">
                                            <div class="mb-1.5">
                                                <div class="d-flex justify-content-between small fw-bold mb-0.5">
                                                    <span>Kalite</span>
                                                    <span class="text-primary font-monospace">%{{ $item->ai_reliability_score }}</span>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" style="width: {{ $item->ai_reliability_score }}%"></div>
                                                </div>
                                            </div>
                                            <div class="mb-1.5">
                                                <div class="d-flex justify-content-between small fw-bold mb-0.5">
                                                    <span>İSG Güvenlik</span>
                                                    <span class="text-success font-monospace">%{{ $item->safety_score }}</span>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-success" style="width: {{ $item->safety_score }}%"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="d-flex justify-content-between small fw-bold mb-0.5">
                                                    <span>Hız</span>
                                                    <span class="text-warning font-monospace">%{{ $item->speed_score }}</span>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-warning" style="width: {{ $item->speed_score }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="font-monospace fw-extrabold text-dark" style="font-size: 1.25rem;">
                                            {{ $item->total_score }} <small class="fs-6 text-muted">/100</small>
                                        </td>
                                        <td>
                                            @if(in_array($item->grade, ['A+', 'A']))
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-extrabold fs-6" style="background: #16a34a;">
                                                    <i class="ri-award-fill me-1"></i>{{ $item->grade }}
                                                </span>
                                            @elseif($item->grade === 'B+' || $item->grade === 'B')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-extrabold fs-6">
                                                    {{ $item->grade }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fw-extrabold fs-6">
                                                    {{ $item->grade }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-dark fw-medium d-inline-block text-truncate" style="max-width: 220px;" title="{{ $item->system_comment }}">
                                                {{ $item->system_comment }}
                                            </small>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('subcontractor_scores.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu taşeron karne kaydını silmek istediğinize emin misiniz?');">
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
                                            <i class="ri-award-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz Taşeron Performans Karnesi Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan taşeron firma adı, kalite, İSG ve hız skorlarını girerek ilk performans karnesini oluşturabilirsiniz.</p>
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
