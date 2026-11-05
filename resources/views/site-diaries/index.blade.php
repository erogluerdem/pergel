@extends('layouts.master')

@section('title', 'Mobil Şantiye Günlüğü & Saha Takibi')

@push('styles')
<style>
    .diary-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
    }
    
    .diary-badge {
        background: rgba(245, 158, 11, 0.2) !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-diary {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-input-diary {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-diary:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .diary-feed-card {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        transition: all 0.3s ease !important;
    }
    .diary-feed-card:hover {
        border-color: #d97706 !important;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.1) !important;
    }

    .photo-thumb-wrapper {
        width: 140px;
        height: 100px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #cbd5e1;
        position: relative;
        transition: transform 0.2s ease;
    }
    .photo-thumb-wrapper:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 diary-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 diary-badge">
                            <i class="ri-camera-3-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">CANLI SAHA RAPORLAMASI & SAHA GÜNLÜĞÜ</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Şantiye İlerleme & Saha Günlüğü</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Şantiyelerinizden gün an gün fotoğraf yükleyin, usta/çalışan sayısını, yapılan imalatları, iş makinesi kullanımını ve İSG tedbirlerini fotoğraflı günlük akışında takip edin.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-warning rounded-pill px-4 py-2.5 fw-extrabold text-dark shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addSiteDiaryModal">
                            <i class="ri-add-circle-fill fs-5"></i>+ Yeni Günlük Ekle
                        </button>
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
        $totalWorkersToday = $diaries->sum('worker_count');
        $photoCount = $diaries->pluck('photo_urls')->flatten()->count();
    @endphp

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-diary h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM SAHA RAPORU</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $diaries->total() }} Günlük</div>
                        <small class="text-warning fw-bold"><i class="ri-file-list-3-line me-1"></i>Saha Kaydı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-book-read-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-diary h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KAYITLI USTA / ÇALIŞAN</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $totalWorkersToday }} Kişi</div>
                        <small class="text-primary fw-bold"><i class="ri-team-line me-1"></i>Saha Personel Yoğunluğu</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-group-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-diary h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">YÜKLENEN SAHA FOTOĞRAFI</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.8rem;">{{ $photoCount }} Fotoğraf</div>
                        <small class="text-info fw-bold"><i class="ri-image-line me-1"></i>Görsel İmalat Arşivi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-camera-lens-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-diary h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">CANLI HAVA DURUMU</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.5rem;">Uygun</div>
                        <small class="text-success fw-bold"><i class="ri-sun-cloudy-line me-1"></i>Saha Şartları Elverişli</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-sun-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border shadow-sm rounded-4 p-3 bg-white">
                <form action="{{ route('site_diaries.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <select name="project_id" class="form-select rounded-pill custom-input-diary" onchange="this.form.submit()">
                            <option value="">Tüm Projeler</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <div class="position-relative">
                            <input type="text" name="search" class="form-control rounded-pill custom-input-diary" style="padding-left: 44px !important;" placeholder="İmalat notu, iş makinesi veya İSG ara..." value="{{ request('search') }}">
                            <i class="ri-search-2-line position-absolute text-dark" style="left: 16px; top: 50%; transform: translateY(-50%); font-size: 1.1rem;"></i>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill flex-fill fw-extrabold py-2 shadow-sm">
                            <i class="ri-filter-3-line me-1"></i>Filtrele
                        </button>
                        <a href="{{ route('site_diaries.index') }}" class="btn btn-outline-dark rounded-pill py-2 px-3 fw-bold" title="Sıfırla">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Diary Feed Cards -->
    <div class="row">
        <div class="col-12">
            @forelse($diaries as $diary)
                <div class="card diary-feed-card shadow-sm mb-4 overflow-hidden">
                    <!-- Top Bar -->
                    <div class="p-3.5 px-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2" style="background: #1e293b; border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-extrabold" style="font-size: 12px;">
                                <i class="ri-building-2-fill me-1"></i>{{ $diary->project->name ?? 'Proje' }}
                            </span>
                            <span class="text-white-50 font-monospace fw-bold" style="font-size: 0.9rem;">
                                <i class="ri-calendar-event-fill me-1 text-warning"></i>{{ \Carbon\Carbon::parse($diary->entry_date)->translatedFormat('d F Y, l') }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge text-white rounded-pill px-3 py-1.5 fw-extrabold shadow-sm" style="background: #0284c7 !important; font-size: 11.5px;">
                                <i class="ri-sun-cloudy-line me-1"></i>{{ $diary->weather_condition ?? 'Güneşli' }}
                            </span>
                            <span class="badge text-white rounded-pill px-3 py-1.5 fw-extrabold shadow-sm" style="background: #16a34a !important; font-size: 11.5px;">
                                <i class="ri-team-line me-1"></i>{{ $diary->worker_count }} Personel
                            </span>
                            <form action="{{ route('site_diaries.destroy', $diary->id) }}" method="POST" onsubmit="return confirm('Bu günlük kaydını tamamen silmek istediğinize emin misiniz?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger text-white rounded-circle p-1.5 ms-2 shadow-sm" title="Sil" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4">
                        <h6 class="fw-extrabold text-dark mb-2 d-flex align-items-center gap-2" style="font-size: 1.05rem;">
                            <i class="ri-tools-fill text-warning"></i>Yapılan İmalatlar & Saha İlerlemesi:
                        </h6>
                        <p class="text-dark fw-medium fs-6 mb-3 p-3 bg-light rounded-3 border" style="line-height: 1.65; background: #f8fafc;">
                            {{ $diary->work_done_notes }}
                        </p>

                        @if($diary->equipment_notes || $diary->safety_notes)
                            <div class="row g-2 mb-3">
                                @if($diary->equipment_notes)
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-3 border" style="background: #fffbeb; border-color: #fde68a !important;">
                                            <strong class="text-dark d-block mb-1 fw-extrabold" style="font-size: 12px;">
                                                <i class="ri-truck-fill text-warning me-1"></i>EKİPMAN & İŞ MAKİNESİ
                                            </strong>
                                            <span class="text-dark fw-semibold small">{{ $diary->equipment_notes }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if($diary->safety_notes)
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-3 border" style="background: #f0fdf4; border-color: #bbf7d0 !important;">
                                            <strong class="text-dark d-block mb-1 fw-extrabold" style="font-size: 12px;">
                                                <i class="ri-shield-check-fill text-success me-1"></i>İSG & GÜVENLİK
                                            </strong>
                                            <span class="text-dark fw-semibold small">{{ $diary->safety_notes }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($diary->photo_urls && count($diary->photo_urls) > 0)
                            <h6 class="fw-extrabold text-dark mb-2.5 d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                                <i class="ri-image-2-fill text-primary"></i>Saha Fotoğrafları ({{ count($diary->photo_urls) }} Görsel):
                            </h6>
                            <div class="d-flex flex-wrap gap-2.5">
                                @foreach($diary->photo_urls as $photo)
                                    <a href="{{ $photo }}" target="_blank" class="photo-thumb-wrapper d-block">
                                        <img src="{{ $photo }}" alt="Şantiye Fotoğrafı" loading="lazy" class="w-100 h-100 object-fit-cover">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-white rounded-4 shadow-sm border">
                    <i class="ri-camera-off-line display-2 text-secondary mb-3 d-block"></i>
                    <h5 class="fw-extrabold text-dark">Henüz Saha Günlüğü Eklenmedi</h5>
                    <p class="text-secondary mb-3">Şantiye sahasından fotoğraflı günlük raporu eklemek için butonu kullanabilirsiniz.</p>
                    <button type="button" class="btn btn-warning rounded-pill px-4 py-2.5 fw-extrabold text-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#addSiteDiaryModal">
                        <i class="ri-add-line me-1"></i>İlk Günlük Kaydını Oluştur
                    </button>
                </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $diaries->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add Site Diary -->
<div class="modal fade" id="addSiteDiaryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header bg-dark text-white p-4">
                <h5 class="modal-title fw-extrabold text-white d-flex align-items-center gap-2">
                    <i class="ri-camera-3-fill text-warning fs-4"></i>Yeni Mobil Şantiye Günlüğü Ekle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('site_diaries.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-extrabold text-dark small">PROJE SEÇİNİZ <span class="text-danger">*</span></label>
                            <select name="project_id" class="form-select rounded-pill custom-input-diary" required>
                                <option value="">Proje Seçiniz</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-extrabold text-dark small">TARİH <span class="text-danger">*</span></label>
                            <input type="date" name="entry_date" class="form-control rounded-pill custom-input-diary" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-extrabold text-dark small">HAVA DURUMU</label>
                            <select name="weather_condition" class="form-select rounded-pill custom-input-diary">
                                <option value="Güneşli 25°C">Güneşli 25°C</option>
                                <option value="Parçalı Bulutlu 20°C">Parçalı Bulutlu 20°C</option>
                                <option value="Bulutlu 18°C">Bulutlu 18°C</option>
                                <option value="Yağmurlu 15°C">Yağmurlu 15°C</option>
                                <option value="Rüzgarlı 17°C">Rüzgarlı 17°C</option>
                                <option value="Karlı 2°C">Karlı 2°C</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-extrabold text-dark small">SAHA ÇALIŞAN / USTA SAYISI <span class="text-danger">*</span></label>
                            <input type="number" name="worker_count" class="form-control rounded-pill custom-input-diary" placeholder="Örn: 35" min="0" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-extrabold text-dark small">YAPILAN İMALATLAR & İLERLEME NOTLARI <span class="text-danger">*</span></label>
                            <textarea name="work_done_notes" class="form-control rounded-4 custom-input-diary" rows="3" placeholder="Bugün şantiyede hangi kalıplar döküldü, demir bağlandı veya altyapı yapıldı?" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-extrabold text-dark small">EKİPMAN & İŞ MAKİNESİ NOTLARI</label>
                            <input type="text" name="equipment_notes" class="form-control rounded-pill custom-input-diary" placeholder="Örn: 1 Kule Vinç, 2 Beton Pompası çalıştı">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-extrabold text-dark small">İSG & GÜVENLİK TEDBİRLERİ</label>
                            <input type="text" name="safety_notes" class="form-control rounded-pill custom-input-diary" placeholder="Örn: Baret ve emniyet kemeri kontrol edildi">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-extrabold text-dark small"><i class="ri-image-add-line me-1 text-primary"></i>SAHA FOTOĞRAFLARI YÜKLE</label>
                            <input type="file" name="photos[]" class="form-control rounded-4 custom-input-diary" multiple accept="image/*">
                            <small class="text-muted d-block mt-1">Birden fazla fotoğraf yükleyebilirsiniz (JPG, PNG, WEBP).</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-outline-dark rounded-pill px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-extrabold text-dark shadow-sm"><i class="ri-check-line me-1"></i>Günlüğü Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
