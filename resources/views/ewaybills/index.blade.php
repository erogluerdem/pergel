@extends('layouts.master')

@section('title', 'E-İrsaliye & Şantiye Malzeme Giriş Portalı')

@push('styles')
<style>
    .ewaybill-hero-banner {
        background: linear-gradient(135deg, #064e3b 0%, #0f172a 60%, #059669 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        box-shadow: 0 15px 35px rgba(6, 78, 59, 0.4) !important;
    }
    
    .ewaybill-badge {
        background: rgba(16, 185, 129, 0.2) !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        color: #a7f3d0 !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-ewaybill {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-ewaybill {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-ewaybill {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-ewaybill:focus {
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
            <div class="card border-0 ewaybill-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 ewaybill-badge">
                            <i class="ri-file-paper-2-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">GİB E-İRSALİYE MEVZUATI & ŞANTİYE SEVKİYAT KAPISI</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">E-İrsaliye & Şantiye Malzeme Girişi</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Tedarikçilerden şantiyelere sevk edilen hazır beton, demir, çimento ve yapı malzemelerinin e-irsaliye onayları, VKN takipleri ve e-fatuaya dönüştürme kontrolü.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-truck-line fs-5"></i>Toplam Sevk: {{ $stats['total_waybills'] }} İrsaliye
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
            <div class="card custom-kpi-card-ewaybill h-100 border-start border-success border-4" style="border-left-color: #059669 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM E-İRSALİYE</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_waybills'] }} İrsaliye</div>
                        <small class="text-success fw-bold"><i class="ri-file-list-3-line me-1"></i>Şantiye Sevkiyatı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #ecfdf5 !important; border: 1px solid #a7f3d0 !important;">
                        <i class="ri-file-paper-fill fs-2" style="color: #059669 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-ewaybill h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">ONAYLANAN SEVKLER</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['approved_count'] }} Kalem</div>
                        <small class="text-primary fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Şantiyeye Kabul Edildi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-truck-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-ewaybill h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">ONAY BEKLEYENLER</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">{{ $stats['pending_count'] }} İrsaliye</div>
                        <small class="text-warning fw-bold"><i class="ri-time-line me-1"></i>Kabul İncelemesinde</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-history-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-ewaybill h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">FATURAYA DÖNÜŞENLER</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.8rem;">{{ $stats['invoiced_count'] }} Kayıt</div>
                        <small class="text-info fw-bold"><i class="ri-bill-line me-1"></i>E-Faturaya Bağlandı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-receipt-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New Waybill Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-ewaybill shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #ecfdf5; color: #059669;">
                        <i class="ri-file-add-line fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni E-İrsaliye Girişi</h5>
                </div>

                <form action="{{ route('ewaybills.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">E-İRSALİYE NO <span class="text-danger">*</span></label>
                        <input type="text" name="waybill_number" class="form-control rounded-pill custom-input-ewaybill font-monospace" placeholder="Örn: IRS2026000001" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">DÜZENLEME TARİHİ <span class="text-danger">*</span></label>
                        <input type="date" name="issue_date" class="form-control rounded-pill custom-input-ewaybill" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">TEDARİKCİ FİRMA ADI <span class="text-danger">*</span></label>
                        <input type="text" name="supplier_name" class="form-control rounded-pill custom-input-ewaybill" placeholder="Örn: OYAK Hazır Beton A.Ş." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">TEDARİKÇİ VKN / TCKN</label>
                        <input type="text" name="supplier_vkn_tckn" class="form-control rounded-pill custom-input-ewaybill font-monospace" placeholder="Örn: 1234567890">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">SEVK EDİLEN MALZEME <span class="text-danger">*</span></label>
                        <input type="text" name="material_description" class="form-control rounded-pill custom-input-ewaybill" placeholder="Örn: C30/37 Standart Hazır Beton" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">MİKTAR <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="quantity" class="form-control rounded-pill custom-input-ewaybill text-success fw-bold" placeholder="120" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">BİRİM <span class="text-danger">*</span></label>
                            <input type="text" name="unit" class="form-control rounded-pill custom-input-ewaybill" placeholder="m3, ton, adet" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">ONAY / KABUL DURUMU <span class="text-danger">*</span></label>
                        <select name="status" class="form-select rounded-pill custom-input-ewaybill" required>
                            <option value="bekliyor" selected>🟡 Bekliyor (Kabul İncelemesinde)</option>
                            <option value="onaylandi">🟢 Onaylandı (Şantiyeye Kabul Edildi)</option>
                            <option value="faturaya_donustu">🔵 Faturaya Dönüştü</option>
                            <option value="reddedildi">🔴 Reddedildi (İade)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100 rounded-pill py-2.5 fw-extrabold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #059669; border-color: #059669;">
                        <i class="ri-save-3-fill fs-5"></i>İrsaliyeyi Kaydet
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-file-list-3-fill text-warning fs-5"></i>E-İrsaliye Şantiye Giriş Listesi
                    </h5>
                    <span class="badge bg-success text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #059669;">{{ $items->total() }} İrsaliye</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Düzenleme Tarihi</th>
                                    <th class="py-3 fw-extrabold text-dark">İrsaliye No</th>
                                    <th class="py-3 fw-extrabold text-dark">Tedarikçi</th>
                                    <th class="py-3 fw-extrabold text-dark">Sevk Malzemesi</th>
                                    <th class="py-3 fw-extrabold text-dark">Miktar</th>
                                    <th class="py-3 fw-extrabold text-dark">Durum</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td class="ps-4 py-3 font-monospace fw-bold text-dark">{{ \Carbon\Carbon::parse($item->issue_date)->format('d.m.Y') }}</td>
                                        <td>
                                            <span class="badge bg-dark text-warning font-monospace px-3 py-1.5 rounded-pill fw-extrabold" style="font-size: 11.5px;">
                                                {{ $item->waybill_number }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-extrabold text-dark fs-6">{{ $item->supplier_name }}</div>
                                            @if($item->supplier_vkn_tckn)
                                                <small class="text-secondary font-monospace fw-semibold">VKN: {{ $item->supplier_vkn_tckn }}</small>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-dark">{{ $item->material_description }}</td>
                                        <td class="text-success fw-extrabold font-monospace" style="font-size: 1.05rem;">
                                            {{ number_format($item->quantity, 2, ',', '.') }} {{ $item->unit }}
                                        </td>
                                        <td>
                                            @if($item->status === 'onaylandi')
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a; font-size: 11px;">
                                                    <i class="ri-checkbox-circle-fill me-1"></i>Onaylandı (Şantiyede)
                                                </span>
                                            @elseif($item->status === 'bekliyor')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-time-line me-1"></i>Bekliyor
                                                </span>
                                            @elseif($item->status === 'faturaya_donustu')
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #0284c7; font-size: 11px;">
                                                    <i class="ri-receipt-line me-1"></i>Faturaya Dönüştü
                                                </span>
                                            @else
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-close-circle-fill me-1"></i>Reddedildi (İade)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('ewaybills.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu e-irsaliye kaydını silmek istediğinize emin misiniz?');">
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
                                            <i class="ri-file-paper-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz E-İrsaliye Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan tedarikçi firmanın sevk ettiği malzeme ve irsaliye bilgilerini girerek ilk e-irsaliye kaydınızı oluşturabilirsiniz.</p>
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
