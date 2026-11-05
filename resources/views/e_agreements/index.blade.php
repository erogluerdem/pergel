@extends('layouts.master')

@section('title', 'Cari Bakiye & BA/BS E-Mutabakat Portalı')

@push('styles')
<style>
    .agreement-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
    }
    
    .agreement-badge {
        background: rgba(245, 158, 11, 0.2) !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-agreement {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-agreement {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-agreement {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-agreement:focus {
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
            <div class="card border-0 agreement-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 agreement-badge">
                            <i class="ri-handshake-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">T.C. MALİYE VUK MEVZUATI & CANLI E-MUTABAKAT SERVİSİ</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Cari Bakiye & BA/BS E-Mutabakat</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Tedarikçiler, taşeronlar ve müşteriler ile dönem sonu cari hesap bakiyelerinin ve BA/BS mutabakat formlarının elektronik onay takibi.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-checkbox-circle-line fs-5"></i>Mutabık Hesaplar: {{ $stats['agreed_count'] }} Cari
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
            <div class="card custom-kpi-card-agreement h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM CARİ BAKİYE</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_our_balance'], 2, ',', '.') }}</div>
                        <small class="text-warning fw-bold"><i class="ri-wallet-3-line me-1"></i>Mutabakattaki Toplam Tutar</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-scales-3-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-agreement h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">MUTABIK KALINAN</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $stats['agreed_count'] }} Firma</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Bakiye Eşleşti</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-handshake-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-agreement h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">UYUŞMAZLIK (FARK VAR)</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.8rem;">{{ $stats['conflict_count'] }} Firma</div>
                        <small class="text-danger fw-bold"><i class="ri-close-circle-line me-1"></i>İnceleme Gerekiyor</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-error-warning-line fs-2" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-agreement h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">CEVAP BEKLEYENLER</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $stats['pending_count'] }} Mektup</div>
                        <small class="text-primary fw-bold"><i class="ri-mail-send-line me-1"></i>Onay Bekliyor</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-mail-unread-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New Agreement Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-agreement shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #fffbeb; color: #d97706;">
                        <i class="ri-mail-add-fill fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni E-Mutabakat Gönder</h5>
                </div>

                <form action="{{ route('e_agreements.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">CARİ FİRMA ADI <span class="text-danger">*</span></label>
                        <input type="text" name="partner_name" class="form-control rounded-pill custom-input-agreement" placeholder="Örn: OYAK Hazır Beton A.Ş." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">VERGİ KİMLİK NO (VKN / TCKN) <span class="text-danger">*</span></label>
                        <input type="text" name="partner_tax_no" class="form-control rounded-pill custom-input-agreement font-monospace" placeholder="Örn: 9840284019" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">MUTABAKAT DÖNEMİ <span class="text-danger">*</span></label>
                        <input type="text" name="period_month" class="form-control rounded-pill custom-input-agreement font-monospace" placeholder="Örn: 2026-08" value="{{ date('Y-m') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">MUTABAKAT TÜRÜ <span class="text-danger">*</span></label>
                        <select name="mutabakat_type" class="form-select rounded-pill custom-input-agreement" required>
                            <option value="Cari Bakiye Mutabakatı" selected>💰 Cari Bakiye Mutabakatı</option>
                            <option value="BA/BS Form Mutabakatı">📄 BA/BS Form Mutabakatı</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">BİZİM BAKİYE (TL) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="our_balance" class="form-control rounded-pill custom-input-agreement text-primary font-monospace fw-bold" placeholder="150000.00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">KARŞI TAFAF (TL)</label>
                            <input type="number" step="0.01" name="partner_balance" class="form-control rounded-pill custom-input-agreement font-monospace" placeholder="150000.00">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">MUTABAKAT DURUMU <span class="text-danger">*</span></label>
                        <select name="status" class="form-select rounded-pill custom-input-agreement" required>
                            <option value="gonderildi" selected>🟡 Gönderildi (Cevap Bekliyor)</option>
                            <option value="mutabik_kalindi">🟢 Mutabık Kalındı (Onaylandı)</option>
                            <option value="uyusmazlik">🔴 Uyuşmazlık Var (Farklı Bakiye)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 rounded-pill py-2.5 fw-extrabold text-dark shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="ri-send-plane-fill fs-5"></i>Mutabakat Mektubunu Gönder
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-list-check-2 text-warning fs-5"></i>Cari E-Mutabakat & BA/BS Onay Cetveli
                    </h5>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace fw-extrabold">{{ $items->total() }} Mutabakat</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Cari Firma</th>
                                    <th class="py-3 fw-extrabold text-dark">VKN / TCKN</th>
                                    <th class="py-3 fw-extrabold text-dark">Dönem & Tür</th>
                                    <th class="py-3 fw-extrabold text-dark">Bizim Bakiye (₺)</th>
                                    <th class="py-3 fw-extrabold text-dark">Karşı Taraf (₺)</th>
                                    <th class="py-3 fw-extrabold text-dark">Durum</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    @php
                                        $our = floatval($item->our_balance);
                                        $partner = $item->partner_balance !== null ? floatval($item->partner_balance) : null;
                                        $diff = $partner !== null ? abs($our - $partner) : 0;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-extrabold text-dark fs-6">{{ $item->partner_name }}</div>
                                            <small class="text-secondary fw-semibold">Cari Hesap Partneri</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark font-monospace text-warning px-2.5 py-1 rounded-pill" style="font-size: 11px;">
                                                {{ $item->partner_tax_no }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-dark border font-monospace px-2.5 py-1 rounded-pill fw-bold mb-1 d-inline-block">
                                                {{ $item->period_month }}
                                            </span>
                                            <div class="small text-muted fw-semibold">{{ $item->mutabakat_type }}</div>
                                        </td>
                                        <td class="font-monospace fw-extrabold text-primary" style="font-size: 1.05rem;">
                                            ₺{{ number_format($our, 2, ',', '.') }}
                                        </td>
                                        <td class="font-monospace fw-bold text-dark" style="font-size: 1.05rem;">
                                            @if($partner !== null)
                                                ₺{{ number_format($partner, 2, ',', '.') }}
                                            @else
                                                <span class="text-muted fw-normal">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status === 'mutabik_kalindi')
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a; font-size: 11px;">
                                                    <i class="ri-checkbox-circle-fill me-1"></i>Mutabık (Onaylandı)
                                                </span>
                                            @elseif($item->status === 'uyusmazlik')
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-close-circle-fill me-1"></i>Uyuşmazlık (Fark: ₺{{ number_format($diff, 2, ',', '.') }})
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-time-line me-1"></i>Cevap Bekliyor
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('e_agreements.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu e-mutabakat kaydını silmek istediğinize emin misiniz?');">
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
                                            <i class="ri-handshake-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz E-Mutabakat Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan cari firma adı, VKN ve bakiye tutarını girerek ilk e-mutabakat mektubunuzu oluşturabilirsiniz.</p>
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
