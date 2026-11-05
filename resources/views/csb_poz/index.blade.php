@extends('layouts.master')

@section('title', 'ÇŞB Poz & Birim Fiyat Kütüphanesi')

@push('styles')
<style>
    .csb-hero-banner {
        background: linear-gradient(135deg, #0369a1 0%, #0f172a 60%, #0284c7 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(56, 189, 248, 0.3) !important;
        box-shadow: 0 15px 35px rgba(3, 105, 161, 0.35) !important;
    }
    
    .csb-badge {
        background: rgba(56, 189, 248, 0.2) !important;
        border: 1px solid rgba(56, 189, 248, 0.4) !important;
        color: #bae6fd !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-csb {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-csb {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-csb {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-csb:focus {
        background-color: #ffffff !important;
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 csb-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 csb-badge">
                            <i class="ri-book-read-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">ÇEVRE, ŞEHİRCİLİK VE İKLİM DEĞİŞİKLİĞİ BAKANLIĞI</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">ÇŞB Poz & Resmi Birim Fiyat Kitaplığı</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            İnşaat, tesisat ve imalat metrajlarınızda kullanacağınız resmi ÇŞB poz numaraları, imalat tanımları ve 2026 yılı birim fiyat veri kütüphanesi.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('csb_poz.seed') }}" class="btn btn-warning rounded-pill px-4 py-2.5 fw-extrabold text-dark shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="ri-download-cloud-2-line fs-5"></i>Örnek ÇŞB Pozlarını Yükle
                        </a>
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

    @php
        $totalPozCount = $pozs->total();
        $civilCount = $pozs->filter(fn($p) => str_contains(strtolower($p->work_group ?? ''), 'inşaat') || str_contains(strtolower($p->work_group ?? ''), 'duvar') || str_contains(strtolower($p->work_group ?? ''), 'beton'))->count();
        $finishingCount = $pozs->filter(fn($p) => str_contains(strtolower($p->work_group ?? ''), 'ince') || str_contains(strtolower($p->work_group ?? ''), 'sıva') || str_contains(strtolower($p->work_group ?? ''), 'kaplama'))->count();
    @endphp

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-csb h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KÜTÜPHANEDEKİ ÇŞB POZU</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $totalPozCount }} Poz</div>
                        <small class="text-info fw-bold"><i class="ri-book-line me-1"></i>Resmi Poz Kütüphanesi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-book-3-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-csb h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KABA İNŞAAT POZLARI</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $civilCount }} Kalem</div>
                        <small class="text-primary fw-bold"><i class="ri-building-line me-1"></i>Beton, Demir, Duvar</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-building-4-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-csb h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">İNCE İŞLER POZLARI</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $finishingCount }} Kalem</div>
                        <small class="text-success fw-bold"><i class="ri-brush-line me-1"></i>Sıva, Seramik, Kaplama</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-paint-brush-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-csb h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">BİRİM FİYAT YILI</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">2026 Cetveli</div>
                        <small class="text-warning fw-bold"><i class="ri-government-line me-1"></i>Resmi Rayiç Yılı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-calendar-event-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- New Poz Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-csb shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #e0f2fe; color: #0284c7;">
                        <i class="ri-add-circle-line fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni ÇŞB Poz Kalemi Ekle</h5>
                </div>

                <form action="{{ route('csb_poz.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">POZ NUMARASI <span class="text-danger">*</span></label>
                        <input type="text" name="poz_number" class="form-control rounded-pill custom-input-csb font-monospace" placeholder="Örn: 15.150.1001" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">POZ TANIMI / AÇIKLAMASI <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control rounded-pill custom-input-csb" placeholder="Örn: Gazbeton bloklarla duvar yapılması" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">BİRİM <span class="text-danger">*</span></label>
                            <input type="text" name="unit" class="form-control rounded-pill custom-input-csb" placeholder="m3, m2, ton" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">BİRİM FİYAT (TL) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="unit_price" class="form-control rounded-pill custom-input-csb text-success fw-bold" placeholder="2850.00" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">YILI <span class="text-danger">*</span></label>
                            <input type="number" name="year" class="form-control rounded-pill custom-input-csb" value="2026" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">İŞ GRUBU</label>
                            <input type="text" name="work_group" class="form-control rounded-pill custom-input-csb" placeholder="İnşaat / Duvar">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-info text-white w-100 rounded-pill py-2.5 fw-extrabold shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #0284c7; border-color: #0284c7;">
                        <i class="ri-save-3-fill fs-5"></i>Poz Kalemini Kaydet
                    </button>
                </form>
            </div>
        </div>

        <!-- Poz Library & Search Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <!-- Search Header -->
                <div class="card-header bg-dark text-white p-3.5 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-book-2-fill text-warning fs-5"></i>ÇŞB Poz Kütüphanesi & Arama
                    </h5>
                    <form action="{{ route('csb_poz.index') }}" method="GET" class="d-flex gap-2">
                        <div class="position-relative">
                            <input type="text" name="search" class="form-control form-control-sm rounded-pill text-dark fw-bold ps-4" style="width: 220px;" placeholder="Poz No veya Tanım Ara..." value="{{ request('search') }}">
                            <i class="ri-search-2-line position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%);"></i>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold"><i class="ri-search-line"></i></button>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Poz No</th>
                                    <th class="py-3 fw-extrabold text-dark">İmalat Tanımı</th>
                                    <th class="py-3 fw-extrabold text-dark">İş Grubu</th>
                                    <th class="py-3 fw-extrabold text-dark">Birim</th>
                                    <th class="py-3 fw-extrabold text-dark">Birim Fiyat (2026)</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pozs as $poz)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <span class="badge bg-dark text-warning font-monospace px-3 py-1.5 rounded-pill fw-extrabold" style="font-size: 11.5px;">
                                                {{ $poz->poz_number }}
                                            </span>
                                        </td>
                                        <td class="fw-extrabold text-dark fs-6">{{ $poz->title }}</td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-dark border rounded-pill px-2.5 py-1 font-monospace fw-bold">
                                                {{ $poz->work_group ?? 'Genel' }}
                                            </span>
                                        </td>
                                        <td class="fw-bold text-secondary font-monospace">{{ $poz->unit }}</td>
                                        <td class="text-success fw-extrabold font-monospace" style="font-size: 1.05rem;">₺{{ number_format($poz->unit_price, 2, ',', '.') }}</td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('csb_poz.destroy', $poz->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu Poz kalemini silmek istediğinize emin misiniz?');">
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
                                            <i class="ri-book-open-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">ÇŞB Poz Kalemi Bulunamadı</h6>
                                            <p class="small text-muted mb-3">Sağ üstteki "Örnek ÇŞB Pozlarını Yükle" butonuna tıklayarak resmi poz kütüphanesini otomatik doldurabilirsiniz.</p>
                                            <a href="{{ route('csb_poz.seed') }}" class="btn btn-warning rounded-pill px-4 py-2 fw-bold text-dark shadow-sm">
                                                <i class="ri-download-cloud-2-line me-1"></i>Örnek Pozları Yükle
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $pozs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
