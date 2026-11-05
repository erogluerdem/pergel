@extends('layouts.master')

@section('title', 'EBİS & Yapı Denetim Beton Kırım Takibi')

@push('styles')
<style>
    .concrete-hero-banner {
        background: linear-gradient(135deg, #881337 0%, #4c0519 60%, #e11d48 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(225, 29, 72, 0.3) !important;
        box-shadow: 0 15px 35px rgba(136, 19, 55, 0.35) !important;
    }
    
    .concrete-badge {
        background: rgba(225, 29, 72, 0.2) !important;
        border: 1px solid rgba(225, 29, 72, 0.4) !important;
        color: #fecdd3 !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-concrete {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-concrete {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-concrete {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-concrete:focus {
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
            <div class="card border-0 concrete-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 concrete-badge">
                            <i class="ri-flask-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">ÇŞB EBİS ÇİP TAKİBİ & DEPREM YÖNETMELİĞİ</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">EBİS & Yapı Denetim Beton Kırım Takibi</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Şantiyelerdeki beton dökümlerinin 7 günlük ve 28 günlük EBİS kırım basınç dayanımı (MPa) sonuçlarını, çip numaralarını ve Yapı Denetim onay raporlarını takip edin.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-shield-check-line fs-5"></i>TBDY 2026 Uyumlu
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

    @php
        $totalQuantity = $tests->sum('quantity_m3');
        $passedCount = $tests->where('status', 'passed')->count();
        $warningCount = $tests->whereIn('status', ['warning', 'failed'])->count();
    @endphp

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-concrete h-100 border-start border-danger border-4" style="border-left-color: #e11d48 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KAYITLI TEST SAYISI</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $tests->total() }} Numune</div>
                        <small class="text-danger fw-bold"><i class="ri-flask-line me-1"></i>EBİS Kırım Testleri</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-test-tube-line fs-2" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-concrete h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM DÖKÜLEN BETON</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">{{ number_format($totalQuantity, 1, ',', '.') }} m³</div>
                        <small class="text-primary fw-bold"><i class="ri-truck-line me-1"></i>Hazır Beton Hacmi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-building-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-concrete h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">DAYANIMI UYGUN DÖKÜM</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $passedCount }} Test</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>MPa Hedefi Yakalandı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-shield-check-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-concrete h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TAKİPTE / RİSKLİ DÖKÜM</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">{{ $warningCount }} Test</div>
                        <small class="text-warning fw-bold"><i class="ri-alert-line me-1"></i>Karot / İnceleme Gerekebilir</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-alarm-warning-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New Concrete Record Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-concrete shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #fff1f2; color: #e11d48;">
                        <i class="ri-flask-fill fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni EBİS Beton Kırım Kaydı</h5>
                </div>

                <form action="{{ route('concrete_tests.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">PROJE SEÇİNİZ</label>
                        <select name="project_id" class="form-select rounded-pill custom-input-concrete" required>
                            <option value="">Proje Seçiniz...</option>
                            @foreach($projects as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">BETON DÖKÜM TARİHİ</label>
                        <input type="date" name="pouring_date" class="form-control rounded-pill custom-input-concrete" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">HAZIR BETON SINIFI</label>
                        <select name="concrete_class" class="form-select rounded-pill custom-input-concrete" required>
                            <option value="C25/30">C25/30</option>
                            <option value="C30/37" selected>C30/37 (Standart TBDY)</option>
                            <option value="C35/45">C35/45 (Yüksek Dayanım)</option>
                            <option value="C40/50">C40/50 (Özel Dayanım)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">YAPI ELEMANI / BLOK / KAT</label>
                        <input type="text" name="location_element" class="form-control rounded-pill custom-input-concrete" placeholder="Örn: B Blok 3. Kat Tabliye / Kolon" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">DÖKÜLEN BETON MİKTARI (m³)</label>
                        <input type="number" step="0.1" name="quantity_m3" class="form-control rounded-pill custom-input-concrete" placeholder="Örn: 120" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">İRSALİYE / MİKSER NO</label>
                        <input type="text" name="waybill_number" class="form-control rounded-pill custom-input-concrete" placeholder="İrsaliye Kodu">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">YAPI DENETİM & EBİS ÇİP NO</label>
                        <input type="text" name="building_inspection_company" class="form-control rounded-pill custom-input-concrete" placeholder="Örn: Ebis Çip #98234-A">
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">7 GÜN KIRIM (MPa)</label>
                            <input type="number" step="0.1" name="day_7_strength_mpa" class="form-control rounded-pill custom-input-concrete" placeholder="Örn: 22.5">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">28 GÜN KIRIM (MPa)</label>
                            <input type="number" step="0.1" name="day_28_strength_mpa" class="form-control rounded-pill custom-input-concrete text-primary fw-bold" placeholder="Örn: 32.8">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 rounded-pill py-2.5 fw-extrabold shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #e11d48; border-color: #e11d48;">
                        <i class="ri-save-3-fill fs-5"></i>EBİS Kaydını Ekle
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-list-check-2 text-warning fs-5"></i>Beton Kırım Sonuçları & Dayanım Raporu
                    </h5>
                    <span class="badge bg-danger text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #e11d48;">{{ $tests->total() }} Kayıt</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Döküm Tarihi</th>
                                    <th class="py-3 fw-extrabold text-dark">Proje & Eleman</th>
                                    <th class="py-3 fw-extrabold text-dark">Sınıf</th>
                                    <th class="py-3 fw-extrabold text-dark">Miktar</th>
                                    <th class="py-3 fw-extrabold text-dark">7. Gün (MPa)</th>
                                    <th class="py-3 fw-extrabold text-dark">28. Gün (MPa)</th>
                                    <th class="py-3 fw-extrabold text-dark">Dayanım Durumu</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tests as $test)
                                    <tr>
                                        <td class="ps-4 py-3 font-monospace fw-bold text-dark">{{ $test->pouring_date ? $test->pouring_date->format('d.m.Y') : '-' }}</td>
                                        <td>
                                            <div class="fw-extrabold text-dark">{{ $test->project->name ?? '-' }}</div>
                                            <small class="text-secondary fw-semibold">{{ $test->location_element }}</small>
                                        </td>
                                        <td><span class="badge bg-secondary-subtle text-dark border rounded-pill px-2.5 py-1 font-monospace fw-bold">{{ $test->concrete_class }}</span></td>
                                        <td class="font-monospace fw-bold text-dark">{{ $test->quantity_m3 }} m³</td>
                                        <td class="font-monospace fw-bold text-dark">{{ $test->day_7_strength_mpa ? $test->day_7_strength_mpa . ' MPa' : '-' }}</td>
                                        <td class="font-monospace fw-extrabold text-primary" style="font-size: 1.05rem;">{{ $test->day_28_strength_mpa ? $test->day_28_strength_mpa . ' MPa' : '-' }}</td>
                                        <td>
                                            @if($test->status === 'passed')
                                                <span class="badge bg-success text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-checkbox-circle-fill me-1"></i>Dayanım Uygun
                                                </span>
                                            @elseif($test->status === 'warning')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-alert-fill me-1"></i>Sınırda / Takipte
                                                </span>
                                            @else
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-close-circle-fill me-1"></i>Yetersiz (Karot Gerekli)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('concrete_tests.destroy', $test->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu kırım test kaydını silmek istediğinize emin misiniz?');">
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
                                        <td colspan="8" class="text-center text-muted py-5">
                                            <i class="ri-flask-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz EBİS Beton Kırım Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan dökülen beton sınıfını ve MPa kırım değerlerini girerek ilk kırım test kaydınızı oluşturabilirsiniz.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $tests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
