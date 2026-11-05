@extends('layouts.master')

@section('title', 'Yasal Şantiye Günlük Defteri & Saha Kayıtları')

@push('styles')
<style>
    .logbook-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
    }
    
    .logbook-badge {
        background: rgba(245, 158, 11, 0.2) !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-logbook {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-logbook {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-logbook {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-logbook:focus {
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
            <div class="card border-0 logbook-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 logbook-badge">
                            <i class="ri-book-3-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">3194 SAYILI İMAR KANUNU & ŞANTİYE ŞEFİ YASAL DEFTERİ</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Yasal Şantiye Günlük Defteri</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Şantiye şefleri ve sorumlu mühendisler tarafından günlük doldurulması zorunlu yasal defter, hava durumu, çalışan sayısı ve imalat onay tutanakları.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-quill-pen-line fs-5"></i>İmzalı Defter: {{ $stats['signed_count'] }} Kayıt
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
            <div class="card custom-kpi-card-logbook h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM DEFTER KAYDI</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_logs'] }} Günlük</div>
                        <small class="text-warning fw-bold"><i class="ri-book-open-line me-1"></i>Yasal Defter Kayıtları</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-book-read-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-logbook h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KAYITLI SAHA PERSONELİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_personnel'] }} Personel</div>
                        <small class="text-primary fw-bold"><i class="ri-team-line me-1"></i>Öz + Taşeron Mevcudu</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-group-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-logbook h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">İMZALI YASAL DEFTER</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $stats['signed_count'] }} Defter</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Şantiye Şefi İmzalı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-draft-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-logbook h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">İMZA BEKLEYEN</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.8rem;">{{ $stats['pending_sign_count'] }} Kayıt</div>
                        <small class="text-info fw-bold"><i class="ri-time-line me-1"></i>Onay Bekliyor</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-history-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New Daily Log Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-logbook shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #fffbeb; color: #d97706;">
                        <i class="ri-edit-box-line fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni Günlük Defter Kaydı</h5>
                </div>

                <form action="{{ route('site_daily_logs.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">DEFTER TARİHİ <span class="text-danger">*</span></label>
                        <input type="date" name="log_date" class="form-control rounded-pill custom-input-logbook" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">HAVA DURUMU <span class="text-danger">*</span></label>
                            <select name="weather_condition" class="form-select rounded-pill custom-input-logbook" required>
                                <option value="Güneşli">☀️ Güneşli</option>
                                <option value="Parçalı Bulutlu" selected>Partly Bulutlu</option>
                                <option value="Bulutlu">☁️ Bulutlu</option>
                                <option value="Yağmurlu">🌧️ Yağmurlu</option>
                                <option value="Karlı">❄️ Karlı</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">SICAKLIK (°C) <span class="text-danger">*</span></label>
                            <input type="number" name="temperature" class="form-control rounded-pill custom-input-logbook font-monospace" value="24" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">ÖZ İŞÇİ SAYISI <span class="text-danger">*</span></label>
                            <input type="number" name="total_workers" class="form-control rounded-pill custom-input-logbook font-monospace" placeholder="Örn: 12" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">TAŞERON İŞÇİ <span class="text-danger">*</span></label>
                            <input type="number" name="subcontractor_workers" class="form-control rounded-pill custom-input-logbook font-monospace" placeholder="Örn: 45" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">ŞANTİYE ŞEFİ ADI SOYADI <span class="text-danger">*</span></label>
                        <input type="text" name="site_manager_name" class="form-control rounded-pill custom-input-logbook" placeholder="Örn: İnş. Müh. Ahmet Yılmaz" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">YAPILAN İMALATLAR & DEFTER NOTU <span class="text-danger">*</span></label>
                        <textarea name="manufacturing_details" class="form-control rounded-4 custom-input-logbook" rows="3" placeholder="Bugün şantiyede dökülen beton, bağlanan demir ve yapılan işler..." required></textarea>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_signed" id="isSignedCheck" value="1" checked>
                        <label class="form-check-label fw-extrabold text-dark small" for="isSignedCheck">
                            <i class="ri-quill-pen-fill text-success me-1"></i>Şantiye Şefi Tarafından İmzalandı
                        </label>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 rounded-pill py-2.5 fw-extrabold text-dark shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="ri-save-3-fill fs-5"></i>Defter Kaydını Oluştur
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-book-mark-fill text-warning fs-5"></i>Yasal Şantiye Günlük Defter Kayıtları
                    </h5>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace fw-extrabold">{{ $items->total() }} Günlük</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Defter Tarihi</th>
                                    <th class="py-3 fw-extrabold text-dark">Hava Durumu</th>
                                    <th class="py-3 fw-extrabold text-dark">Saha Personeli</th>
                                    <th class="py-3 fw-extrabold text-dark">Yapılan İmalatlar</th>
                                    <th class="py-3 fw-extrabold text-dark">Şantiye Şefi</th>
                                    <th class="py-3 fw-extrabold text-dark">İmza Durumu</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <span class="badge bg-dark text-warning font-monospace px-3 py-1.5 rounded-pill fw-extrabold" style="font-size: 11.5px;">
                                                {{ \Carbon\Carbon::parse($item->log_date)->translatedFormat('d F Y, l') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info-emphasis border rounded-pill px-2.5 py-1 font-monospace fw-bold">
                                                {{ $item->weather_condition }} ({{ $item->temperature }}°C)
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-extrabold text-dark font-monospace" style="font-size: 0.95rem;">
                                                {{ $item->total_workers + $item->subcontractor_workers }} Kişi
                                            </div>
                                            <small class="text-secondary fw-semibold">Öz: {{ $item->total_workers }} | Taşeron: {{ $item->subcontractor_workers }}</small>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-medium d-inline-block text-truncate" style="max-width: 220px;" title="{{ $item->manufacturing_details }}">
                                                {{ $item->manufacturing_details }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->site_manager_name }}</div>
                                            <small class="text-muted">Sorumlu Mühendis</small>
                                        </td>
                                        <td>
                                            @if($item->is_signed)
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a; font-size: 11px;">
                                                    <i class="ri-checkbox-circle-fill me-1"></i>İmzalandı (Mühürlü)
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-time-line me-1"></i>İmza Bekliyor
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('site_daily_logs.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu günlük defter kaydını silmek istediğinize emin misiniz?');">
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
                                            <i class="ri-book-read-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz Yasal Şantiye Günlük Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan günün hava durumu, personel sayıları ve yapılan imalat notlarını girerek ilk yasal defter kaydınızı doldurabilirsiniz.</p>
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
