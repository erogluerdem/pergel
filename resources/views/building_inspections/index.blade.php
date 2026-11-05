@extends('layouts.master')

@section('title', 'Yapı Denetim (YDK) Süreç & Seviye Tespit Takibi')

@push('styles')
<style>
    .ydk-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
    }
    
    .ydk-badge {
        background: rgba(245, 158, 11, 0.2) !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-ydk {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-ydk {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-ydk {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-ydk:focus {
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
            <div class="card border-0 ydk-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 ydk-badge">
                            <i class="ri-government-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">4708 SAYILI YAPI DENETİMİ KANUNU MEVZUATI</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Yapı Denetim (YDK) Süreç & Seviye Tespit</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Şantiyelerinizin YDK hakedişleri, inşaat fiziki tamamlanma seviye tespit tutanakları, bakanlık onayları ve ruhsat harç ödemelerinin takibi.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-shield-check-line fs-5"></i>YDK Hakediş Hacmi: ₺{{ number_format($stats['total_amount'], 0, ',', '.') }}
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
            <div class="card custom-kpi-card-ydk h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM YDK HAKEDİŞİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_amount'], 2, ',', '.') }}</div>
                        <small class="text-warning fw-bold"><i class="ri-money-dollar-box-line me-1"></i>Hakediş & Seviye Harcı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-file-shield-2-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-ydk h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">ONAYLANAN SEVİYELER</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $stats['approved_count'] }} Tutanak</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Bakanlık Onayı Alındı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-checkbox-circle-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-ydk h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">İNCELEMEDEKİ KAYITLAR</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $stats['pending_count'] }} Tutanak</div>
                        <small class="text-primary fw-bold"><i class="ri-time-line me-1"></i>YDK İncelemesinde</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-history-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-ydk h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM SÜREÇ KAYDI</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_inspections'] }} Kayıt</div>
                        <small class="text-info fw-bold"><i class="ri-building-4-line me-1"></i>Aktif Şantiye Denetimi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-community-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New YDK Record Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-ydk shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #fffbeb; color: #d97706;">
                        <i class="ri-government-line fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni YDK Seviye Kaydı Ekle</h5>
                </div>

                <form action="{{ route('building_inspections.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">PROJE SEÇİNİZ VEYA ADI <span class="text-danger">*</span></label>
                        <input type="text" name="project_name" class="form-control rounded-pill custom-input-ydk" placeholder="Örn: Çukurova Kuleleri A Blok" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">YAPISAL DENETİM (YDK) FİRMASI <span class="text-danger">*</span></label>
                        <input type="text" name="ydk_company_name" class="form-control rounded-pill custom-input-ydk" placeholder="Örn: Toros Yapı Denetim Ltd. Şti." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">EVRAK / TUTANAK TİPİ <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-select rounded-pill custom-input-ydk" required>
                            <option value="Seviye Tespit Tutanağı" selected>📋 Seviye Tespit Tutanağı (% Seviye)</option>
                            <option value="YDK Hakedişi">💰 YDK Hakediş Ödemesi</option>
                            <option value="Ruhsat Vize Harcı">🏛️ Ruhsat & Vize Harç Belgesi</option>
                            <option value="Demir & Beton Vizesi">🏗️ Demir & Beton Onay Vizesi</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">SEVİYE (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="progress_percentage" class="form-control rounded-pill custom-input-ydk font-monospace text-primary fw-bold" placeholder="75.00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">TUTAR (TL)</label>
                            <input type="number" step="0.01" name="amount" class="form-control rounded-pill custom-input-ydk text-success fw-bold" placeholder="45000">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">SON ÖDEME / VADE TARİHİ</label>
                        <input type="date" name="due_date" class="form-control rounded-pill custom-input-ydk" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">YDK ONAY DURUMU <span class="text-danger">*</span></label>
                        <select name="status" class="form-select rounded-pill custom-input-ydk" required>
                            <option value="beklemede" selected>🟡 Beklemede (İncelemede)</option>
                            <option value="onaylandi">🟢 Onaylandı (Bakanlık Onaylı)</option>
                            <option value="odendi">🔵 Hakediş Ödendi</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 rounded-pill py-2.5 fw-extrabold text-dark shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="ri-save-3-fill fs-5"></i>YDK Kaydını Ekle
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-list-check-2 text-warning fs-5"></i>Yapı Denetim Seviye Tespit & Hakediş Listesi
                    </h5>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace fw-extrabold">{{ $items->total() }} Kayıt</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Proje</th>
                                    <th class="py-3 fw-extrabold text-dark">YDK Firması</th>
                                    <th class="py-3 fw-extrabold text-dark">Evrak Tipi</th>
                                    <th class="py-3 fw-extrabold text-dark">Seviye (%)</th>
                                    <th class="py-3 fw-extrabold text-dark">Hakediş Tutarı (TL)</th>
                                    <th class="py-3 fw-extrabold text-dark">Durum</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-extrabold text-dark fs-6">{{ $item->project_name }}</div>
                                            <small class="text-secondary fw-semibold">Ruhsatlı Şantiye</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->ydk_company_name }}</div>
                                            <small class="text-muted">Bakanlık Yetkili YDK</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-dark border rounded-pill px-2.5 py-1 font-monospace fw-bold">
                                                {{ $item->document_type }}
                                            </span>
                                        </td>
                                        <td class="font-monospace fw-extrabold text-primary" style="font-size: 1.05rem;">
                                            %{{ number_format($item->progress_percentage ?? 0, 2) }}
                                        </td>
                                        <td class="text-success fw-extrabold font-monospace" style="font-size: 1.05rem;">
                                            ₺{{ number_format($item->amount ?? 0, 2, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($item->status === 'onaylandi')
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a; font-size: 11px;">
                                                    <i class="ri-checkbox-circle-fill me-1"></i>Bakanlık Onaylı
                                                </span>
                                            @elseif($item->status === 'odendi')
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #2563eb; font-size: 11px;">
                                                    <i class="ri-wallet-3-fill me-1"></i>Hakediş Ödendi
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-time-line me-1"></i>Beklemede
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('building_inspections.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu YDK kaydını silmek istediğinize emin misiniz?');">
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
                                            <i class="ri-government-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz YDK Seviye Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan Yapı Denetim hakedişi veya seviye tespit tutanağı bilgilerini girerek ilk YDK kaydınızı oluşturabilirsiniz.</p>
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
