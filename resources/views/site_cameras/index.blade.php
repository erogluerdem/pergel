@extends('layouts.master')

@section('title', 'Şantiye CCTV Canlı Kamera & Video Analitik Portalı')

@push('styles')
<style>
    .camera-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #be123c 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(225, 29, 72, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
    }
    
    .camera-badge {
        background: rgba(225, 29, 72, 0.2) !important;
        border: 1px solid rgba(225, 29, 72, 0.4) !important;
        color: #fecdd3 !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-camera {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-camera {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-camera {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-camera:focus {
        background-color: #ffffff !important;
        border-color: #e11d48 !important;
        box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.15) !important;
    }

    .camera-feed-container {
        background: #020617;
        position: relative;
        height: 220px;
        border-radius: 16px 16px 0 0;
        overflow: hidden;
        border-bottom: 2px solid #1e293b;
    }

    .blink-red {
        animation: pulse-red 1.5s infinite;
    }
    @keyframes pulse-red {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.05); }
        100% { opacity: 1; transform: scale(1); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 camera-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 camera-badge">
                            <i class="ri-video-chat-fill text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">4K RTSP IP CANLI KAMERA YAYINI & AI İSG ALGILAMA</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Şantiye CCTV Canlı Kamera İzleme</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            4K IP kameralar, kule vinç tepe kameraları, İSG baret/yelek yapay zeka video analitiği ve 7/24 şantiye sahası canlı yayın kontrol merkezi.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-danger text-white px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm blink-red">
                            <i class="ri-record-circle-line fs-5"></i>Canlı Yayın: {{ $stats['online_count'] }} Kamera Online
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
            <div class="card custom-kpi-card-camera h-100 border-start border-danger border-4" style="border-left-color: #e11d48 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">CANLI ONLINE KAMERALAR</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.8rem;">{{ $stats['online_count'] }} Kamera</div>
                        <small class="text-danger fw-bold"><i class="ri-broadcast-line me-1"></i>7/24 Kesintisiz Yayın</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-vidicon-fill fs-2" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-camera h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM İZLEME SENSÖRÜ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_cameras'] }} Cihaz</div>
                        <small class="text-primary fw-bold"><i class="ri-camera-lens-line me-1"></i>Kule Vinç & Turnike</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-camera-3-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-camera h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">BAKIMDAKİ KAMERALAR</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">{{ $stats['maintenance_count'] }} Kamera</div>
                        <small class="text-warning fw-bold"><i class="ri-tools-line me-1"></i>Teknik Servis Kontrolü</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-settings-4-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-camera h-100 border-start border-secondary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">ÇEVRİMDİŞİ (OFFLINE)</small>
                        <div class="fw-extrabold text-secondary font-monospace" style="font-size: 1.8rem;">{{ $stats['offline_count'] }} Kamera</div>
                        <small class="text-secondary fw-bold"><i class="ri-wifi-off-line me-1"></i>Bağlantı Bekliyor</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f8fafc !important; border: 1px solid #e2e8f0 !important;">
                        <i class="ri-vidicon-off-line fs-2 text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New Camera Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-camera shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #fff1f2; color: #e11d48;">
                        <i class="ri-video-add-fill fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni IP Kamera Ekle</h5>
                </div>

                <form action="{{ route('site_cameras.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">KAMERA ADI <span class="text-danger">*</span></label>
                        <input type="text" name="camera_name" class="form-control rounded-pill custom-input-camera" placeholder="Örn: Kule Vinç Tepe Kamerası - 4K" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">ŞANTİYE SAHASI / KONUM <span class="text-danger">*</span></label>
                        <input type="text" name="location" class="form-control rounded-pill custom-input-camera" placeholder="Örn: Çukurova Kuleleri A Blok Giriş" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">RTSP / HLS CANLI YAYIN ADRESİ <span class="text-danger">*</span></label>
                        <input type="text" name="stream_url" class="form-control rounded-pill custom-input-camera font-monospace" placeholder="rtsp://192.168.1.100:554/live" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">YAYIN DURUMU <span class="text-danger">*</span></label>
                        <select name="status" class="form-select rounded-pill custom-input-camera" required>
                            <option value="online" selected>🔴 Online (Canlı Yayın)</option>
                            <option value="bakimda">🟡 Bakımda (Teknik Servis)</option>
                            <option value="offline">⚪ Offline (Çevrimdışı)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 rounded-pill py-2.5 fw-extrabold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #e11d48; border-color: #e11d48;">
                        <i class="ri-save-3-fill fs-5"></i>Kamerayı Kaydet
                    </button>
                </form>
            </div>
        </div>

        <!-- Live Camera Grid Column -->
        <div class="col-lg-8">
            <div class="row g-3">
                @forelse($items as $item)
                    <div class="col-md-6">
                        <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                            <!-- Simulated 4K Video Screen Container -->
                            <div class="camera-feed-container d-flex align-items-center justify-content-center text-white">
                                @if($item->status === 'online')
                                    <div class="text-center">
                                        <i class="ri-play-circle-line display-3 text-white-50"></i>
                                        <div class="small font-monospace text-white-50 mt-1">4K RTSP STREAM LIVE</div>
                                    </div>
                                    <span class="position-absolute top-0 start-0 m-3 badge bg-danger text-white px-2.5 py-1.5 rounded-pill font-monospace fw-bold blink-red" style="font-size: 11px;">
                                        🔴 CANLI HD 4K
                                    </span>
                                    <span class="position-absolute top-0 end-0 m-3 badge bg-dark bg-opacity-75 text-warning font-monospace px-2.5 py-1 rounded-pill" style="font-size: 10.5px;">
                                        {{ date('H:i:s') }} LIVE
                                    </span>
                                    <span class="position-absolute bottom-0 start-0 m-3 badge bg-success bg-opacity-90 text-white rounded-pill px-2.5 py-1 font-monospace" style="font-size: 10.5px;">
                                        <i class="ri-shield-user-line me-1"></i>AI İSG Baret Aktif
                                    </span>
                                @elseif($item->status === 'bakimda')
                                    <div class="text-center">
                                        <i class="ri-tools-fill display-3 text-warning"></i>
                                        <div class="small font-monospace text-warning mt-1">SERVIS BAKIMINDA</div>
                                    </div>
                                    <span class="position-absolute top-0 start-0 m-3 badge bg-warning text-dark px-2.5 py-1.5 rounded-pill font-monospace fw-bold" style="font-size: 11px;">
                                        🔧 BAKIMDA
                                    </span>
                                @else
                                    <div class="text-center">
                                        <i class="ri-vidicon-off-fill display-3 text-secondary"></i>
                                        <div class="small font-monospace text-secondary mt-1">YAYIN YOK (OFFLINE)</div>
                                    </div>
                                    <span class="position-absolute top-0 start-0 m-3 badge bg-secondary text-white px-2.5 py-1.5 rounded-pill font-monospace fw-bold" style="font-size: 11px;">
                                        ⚠️ OFFLINE
                                    </span>
                                @endif
                            </div>

                            <div class="card-body p-3.5">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <h6 class="fw-extrabold text-dark mb-0 fs-6">{{ $item->camera_name }}</h6>
                                    <form action="{{ route('site_cameras.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Bu IP kamerayı silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Sil">
                                            <i class="ri-delete-bin-line fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                                <small class="text-secondary fw-semibold d-block mb-2">
                                    <i class="ri-map-pin-2-fill text-danger me-1"></i>{{ $item->location }}
                                </small>
                                <div class="bg-light p-2 rounded-3 border d-flex align-items-center justify-content-between" style="font-size: 11px;">
                                    <span class="font-monospace text-muted text-truncate" style="max-width: 200px;">{{ $item->stream_url }}</span>
                                    <span class="badge bg-dark text-warning font-monospace">RTSP/HLS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-5">
                        <i class="ri-vidicon-line display-4 text-secondary mb-2 d-block"></i>
                        <h6 class="fw-bold text-dark">Henüz IP Şantiye Kamerası Bulunmuyor</h6>
                        <p class="small text-muted mb-0">Sol taraftaki formdan kamera adı ve RTSP yayın adresini girerek canlı video izleme akışını ekleyebilirsiniz.</p>
                    </div>
                @endforelse

                @if($items->count() > 0)
                    <div class="col-12 p-3">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
