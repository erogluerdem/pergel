@extends('layouts.master')

@section('title', 'ÇED & MoTAT Şantiye Atık Yönetimi')

@push('styles')
<style>
    .waste-hero-banner {
        background: linear-gradient(135deg, #064e3b 0%, #0f172a 60%, #059669 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        box-shadow: 0 15px 35px rgba(6, 78, 59, 0.4) !important;
    }
    
    .waste-badge {
        background: rgba(16, 185, 129, 0.2) !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        color: #a7f3d0 !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-waste {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-waste {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-waste {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-waste:focus {
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
            <div class="card border-0 waste-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 waste-badge">
                            <i class="ri-recycle-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">T.C. ÇEVRE BAKANLIĞI MOTAT İNŞAAT ATIK TAKİBİ</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">ÇED & MoTAT Şantiye Atık Yönetimi</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Şantiye hafriyat toprağı, tehlikeli atık, asbest ve geri dönüştürülebilir hurda demir/metal dökümlerinin Bakanlık MoTAT makbuzları ile sertifikalandırılması.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-leaf-line fs-5"></i>Toplam Atık Dökümü: {{ number_format($stats['total_waste_tons'], 1, ',', '.') }} Ton
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
            <div class="card custom-kpi-card-waste h-100 border-start border-success border-4" style="border-left-color: #059669 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">BERTARAF EDİLEN ATIK</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">{{ number_format($stats['total_waste_tons'], 1, ',', '.') }} Ton</div>
                        <small class="text-success fw-bold"><i class="ri-truck-line me-1"></i>Hafriyat & Yıkıntı Dökümü</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #ecfdf5 !important; border: 1px solid #a7f3d0 !important;">
                        <i class="ri-recycle-fill fs-2" style="color: #059669 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-waste h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">GERİ DÖNÜŞEN HURDA/METAL</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.6rem;">{{ number_format($stats['recyclable_waste_tons'], 1, ',', '.') }} Ton</div>
                        <small class="text-primary fw-bold"><i class="ri-loop-left-line me-1"></i>Ekonomiye Kazandırılan</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-refresh-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-waste h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TEHLİKELİ & ASBEST ATIK</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.6rem;">{{ number_format($stats['hazardous_waste_tons'], 1, ',', '.') }} Ton</div>
                        <small class="text-danger fw-bold"><i class="ri-shield-cross-line me-1"></i>Özel Lisanslı Bertaraf</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-biohazard-fill fs-2" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-waste h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">MOTAT BAKANLIK MAKBUZU</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['motat_receipts_count'] }} Makbuz</div>
                        <small class="text-warning fw-bold"><i class="ri-file-shield-line me-1"></i>Çevre Bakanlığı Onaylı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-file-paper-2-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New Waste Record Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-waste shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #ecfdf5; color: #059669;">
                        <i class="ri-add-circle-fill fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni Atık Beyanı Ekle</h5>
                </div>

                <form action="{{ route('waste_managements.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">BERTARAF TARİHİ <span class="text-danger">*</span></label>
                        <input type="date" name="disposal_date" class="form-control rounded-pill custom-input-waste" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">ATIK TÜRÜ <span class="text-danger">*</span></label>
                        <select name="waste_type" class="form-select rounded-pill custom-input-waste" required>
                            <option value="Hafriyat Toprağı" selected>🚜 Hafriyat Toprağı & Taş</option>
                            <option value="İnşaat Yıkıntı Atığı">🏗️ İnşaat Yıkıntı Atığı (Moloz)</option>
                            <option value="Hurda Demir / Metal">♻️ Hurda Demir / Metal (Geri Dönüşüm)</option>
                            <option value="Ambalaj & Plastik Atık">📦 Ambalaj & Plastik Atık</option>
                            <option value="Tehlikeli Atık (Asbest / Yağ)">☣️ Tehlikeli Atık (Asbest / Atık Yağ)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">MİKTAR (TON) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="quantity_tons" class="form-control rounded-pill custom-input-waste text-success font-monospace fw-bold" placeholder="Örn: 245.50" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">DÖKÜM / BERTARAF TESİSİ ADI <span class="text-danger">*</span></label>
                        <input type="text" name="disposal_facility" class="form-control rounded-pill custom-input-waste" placeholder="Örn: İSTAÇ Odayeri Hafriyat Döküm Sahası" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">MOTAT MAKBUZ NO (BAKANLIK)</label>
                        <input type="text" name="motat_receipt_no" class="form-control rounded-pill custom-input-waste font-monospace" placeholder="Örn: MOTAT-2026-84920">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">ÇEVRE GÖREVLİSİ / MÜHENDİSİ <span class="text-danger">*</span></label>
                        <input type="text" name="environmental_officer" class="form-control rounded-pill custom-input-waste" placeholder="Örn: Çevre Müh. Selin Demir" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">SEVK / BERTARAF DURUMU <span class="text-danger">*</span></label>
                        <select name="status" class="form-select rounded-pill custom-input-waste" required>
                            <option value="teslim_edildi" selected>🔵 Teslim Edildi (Makbuz Alındı)</option>
                            <option value="onaylandi">🟢 Onaylandı (Tesis Kabulü)</option>
                            <option value="tasinmada">🟡 Taşınmada (Yolda)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100 rounded-pill py-2.5 fw-extrabold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #059669; border-color: #059669;">
                        <i class="ri-save-3-fill fs-5"></i>Atık Beyanını Kaydet
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-list-check-2 text-warning fs-5"></i>Şantiye Atık Beyan & MoTAT Döküm Cetveli
                    </h5>
                    <span class="badge bg-success text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #059669;">{{ $items->total() }} Kayıt</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Tarih</th>
                                    <th class="py-3 fw-extrabold text-dark">Atık Türü</th>
                                    <th class="py-3 fw-extrabold text-dark">Miktar (Ton)</th>
                                    <th class="py-3 fw-extrabold text-dark">Bertaraf Tesisi / MoTAT</th>
                                    <th class="py-3 fw-extrabold text-dark">Çevre Görevlisi</th>
                                    <th class="py-3 fw-extrabold text-dark">Durum</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td class="ps-4 py-3 font-monospace fw-bold text-dark">
                                            {{ \Carbon\Carbon::parse($item->disposal_date)->format('d.m.Y') }}
                                        </td>
                                        <td>
                                            <div class="fw-extrabold text-dark fs-6">{{ $item->waste_type }}</div>
                                            <small class="text-secondary fw-semibold">Şantiye Atığı</small>
                                        </td>
                                        <td class="font-monospace fw-extrabold text-success" style="font-size: 1.1rem;">
                                            {{ number_format($item->quantity_tons, 2, ',', '.') }} Ton
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->disposal_facility }}</div>
                                            @if($item->motat_receipt_no)
                                                <span class="badge bg-dark text-warning font-monospace px-2 py-0.5 rounded-pill" style="font-size: 10.5px;">
                                                    {{ $item->motat_receipt_no }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->environmental_officer }}</div>
                                            <small class="text-muted">Sorumlu Mühendis</small>
                                        </td>
                                        <td>
                                            @if($item->status === 'onaylandi')
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a; font-size: 11px;">
                                                    <i class="ri-checkbox-circle-fill me-1"></i>Tesis Kabul Etti
                                                </span>
                                            @elseif($item->status === 'tasinmada')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-truck-line me-1"></i>Taşınmada
                                                </span>
                                            @else
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #0284c7; font-size: 11px;">
                                                    <i class="ri-file-paper-line me-1"></i>MoTAT Makbuzlu
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('waste_managements.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu atık beyan kaydını silmek istediğinize emin misiniz?');">
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
                                            <i class="ri-recycle-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz Atık Beyan Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan hafriyat döküm miktarı, bertaraf tesisi ve MoTAT makbuz nosunu girerek atık kaydınızı oluşturabilirsiniz.</p>
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
