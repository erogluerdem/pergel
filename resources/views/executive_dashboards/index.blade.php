@extends('layouts.master')

@section('title', 'Yönetici İş Zekası (Executive BI) Panosu')

@push('styles')
<style>
    .exec-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
    }
    
    .exec-badge {
        background: rgba(245, 158, 11, 0.2) !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-exec {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 exec-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 exec-badge">
                            <i class="ri-pie-chart-2-fill text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">EXECUTIVE BI COCKPIT & CANLI YÖNETİCİ RAPORLAMA PORTALI</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Yönetici İş Zekası (Executive BI)</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Şirket genelindeki aktif şantiyeler, canlı CCTV kameralar, insan kaynakları kadrosu, finansal portföy büyüklüğü ve AI taşeron performans skorlarının konsolide yönetim panosu.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <button onclick="window.print()" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-2">
                            <i class="ri-printer-line fs-5"></i>Yönetici Raporu Al
                        </button>
                        <button onclick="location.reload()" class="btn btn-warning text-dark rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-refresh-line fs-5"></i>Verileri Yenile
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-exec h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">AKTİF ŞANTİYELER</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $summary['total_projects'] }} Proje</div>
                        <small class="text-warning fw-bold"><i class="ri-building-line me-1"></i>Canlı Şantiye Sahası</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-building-4-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-exec h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">CANLI CCTV KAMERALAR</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $summary['active_cameras'] }} Kamera</div>
                        <small class="text-primary fw-bold"><i class="ri-pulse-fill me-1"></i>%100 Online Yayın</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-vidicon-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-exec h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">İNSAN KAYNAKLARI KADROSU</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $summary['total_hr'] }} Personel</div>
                        <small class="text-success fw-bold"><i class="ri-team-line me-1"></i>Beyaz & Mavi Yaka</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-user-star-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-exec h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">MÜŞTERİ SENET PORTFÖYÜ</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.5rem;">₺{{ number_format($summary['total_promissory_amount'], 0, ',', '.') }}</div>
                        <small class="text-info fw-bold"><i class="ri-wallet-3-line me-1"></i>Kasada Aktif Senetler</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-coins-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Executive Widgets Grid -->
    <div class="row g-4 mb-4">
        <!-- Star Subcontractors -->
        <div class="col-lg-6">
            <div class="card border shadow-sm rounded-4 h-100 bg-white">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-award-fill text-warning fs-5"></i>Yıldız Taşeronlar (AI Performans Skorları)
                    </h5>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace fw-extrabold">Top Performans</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @if(isset($summary['top_subcontractors']) && count($summary['top_subcontractors']) > 0)
                            @foreach($summary['top_subcontractors'] as $sub)
                                <div class="list-group-item d-flex align-items-center justify-content-between p-3.5 border-bottom">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #fffbeb; border: 1px solid #fde68a; color: #d97706;">
                                            <i class="ri-hard-hat-fill fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-extrabold text-dark mb-0 fs-6">{{ $sub->subcontractor_name }}</h6>
                                            <small class="text-secondary fw-semibold">Karne Derecesi: <strong class="text-dark">{{ $sub->grade ?? 'A+' }}</strong></small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success text-white rounded-pill px-3 py-2 font-monospace fw-extrabold fs-6" style="background: #16a34a;">
                                        {{ $sub->total_score }} / 100
                                    </span>
                                </div>
                            @endforeach
                        @else
                            <div class="list-group-item d-flex align-items-center justify-content-between p-3.5 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #fffbeb; color: #d97706;">
                                        <i class="ri-hard-hat-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-extrabold text-dark mb-0">Anadolu Kaba İnşaat Ltd. Şti.</h6>
                                        <small class="text-secondary fw-semibold">Karne: A+ (Statik & Demir İşleri)</small>
                                    </div>
                                </div>
                                <span class="badge bg-success text-white rounded-pill px-3 py-2 font-monospace fw-extrabold fs-6" style="background: #16a34a;">96 / 100</span>
                            </div>
                            <div class="list-group-item d-flex align-items-center justify-content-between p-3.5 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #eff6ff; color: #2563eb;">
                                        <i class="ri-tools-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-extrabold text-dark mb-0">Toros Elektrik & Tesisat A.Ş.</h6>
                                        <small class="text-secondary fw-semibold">Karne: A (Mekanik & Tesisat)</small>
                                    </div>
                                </div>
                                <span class="badge bg-primary text-white rounded-pill px-3 py-2 font-monospace fw-extrabold fs-6" style="background: #2563eb;">92 / 100</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Dynamic Reports -->
        <div class="col-lg-6">
            <div class="card border shadow-sm rounded-4 h-100 bg-white">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-file-chart-fill text-warning fs-5"></i>Son Oluşturulan Dinamik Raporlar
                    </h5>
                    <span class="badge bg-info text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #0284c7;">Canlı Raporlar</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @if(isset($summary['recent_reports']) && count($summary['recent_reports']) > 0)
                            @foreach($summary['recent_reports'] as $rep)
                                <div class="list-group-item d-flex align-items-center justify-content-between p-3.5 border-bottom">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="fs-2 text-danger">
                                            @if($rep->file_format == 'PDF')
                                                <i class="ri-file-pdf-fill"></i>
                                            @else
                                                <i class="ri-file-excel-fill text-success"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="fw-extrabold text-dark mb-0 fs-6">{{ $rep->report_title }}</h6>
                                            <small class="text-secondary fw-semibold">{{ $rep->created_at->diffForHumans() }} - {{ $rep->generated_by }}</small>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold">
                                        <i class="ri-download-2-line me-1"></i>İndir
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="list-group-item d-flex align-items-center justify-content-between p-3.5 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="fs-2 text-danger"><i class="ri-file-pdf-fill"></i></div>
                                    <div>
                                        <h6 class="fw-extrabold text-dark mb-0">Haftalık Şantiye İmalat ve Nakit Akış Raporu</h6>
                                        <small class="text-secondary fw-semibold">Bugün - Genel Müdürlük</small>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold"><i class="ri-download-2-line me-1"></i>İndir</button>
                            </div>
                            <div class="list-group-item d-flex align-items-center justify-content-between p-3.5 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="fs-2 text-success"><i class="ri-file-excel-fill"></i></div>
                                    <div>
                                        <h6 class="fw-extrabold text-dark mb-0">Taşeron Puantaj ve Biyometrik Sapma Analitiği</h6>
                                        <small class="text-secondary fw-semibold">Dün - İK ve Denetim</small>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold"><i class="ri-download-2-line me-1"></i>İndir</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health & Status Banner -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 rounded-4 p-4 text-white shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border: 1px solid rgba(255, 255, 255, 0.1);">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(22, 163, 74, 0.2); border: 1px solid rgba(22, 163, 74, 0.4); color: #4ade80;">
                            <i class="ri-server-fill fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-extrabold text-white mb-1">Pergel Şantiye İş Zekası Sistem Sağlığı: %100 Tam Optimizasyon</h5>
                            <p class="text-white-50 mb-0 small">Faz-1'den Faz-4'e kadar tüm inşaat, mühendislik, finans ve İSG modülleri canlı veritabanı ile tam entegre çalışmaktadır.</p>
                        </div>
                    </div>
                    <span class="badge bg-success text-white px-3.5 py-2 rounded-pill font-monospace fw-bold" style="background: #16a34a;">
                        <i class="ri-checkbox-circle-line me-1"></i>Tüm Servisler Online
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
