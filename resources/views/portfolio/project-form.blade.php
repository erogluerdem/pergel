@extends('layouts.master')

@section('title', $project->id ? 'Proje Düzenle — ' . $project->name : 'Yeni Proje Oluştur')

@push('styles')
<style>
    .project-form-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .project-form-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: projectFormGlow 4s linear infinite;
    }

    @keyframes projectFormGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .project-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-project-form-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .custom-input-project {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 9px 13px !important;
    }

    .custom-input-project:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .form-label-custom {
        font-weight: 800 !important;
        color: #0f172a !important;
        font-size: 0.85rem !important;
        margin-bottom: 5px !important;
    }

    #attachmentsPreview img {
        max-width: 100px;
        max-height: 80px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
    }
    #attachmentsPreview .file-name {
        font-size: 0.82rem;
        padding: 4px 8px;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="project-form-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill project-badge-pill">
                        <i class="ri-building-4-fill text-warning fs-5"></i> PROJE YÖNETİMİ & ŞANTİYE OLUŞTURMA
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        {{ $project->id ? 'Kayıtlı Proje Düzenleme' : 'Yeni Proje Girişi' }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">
                    {{ $project->id ? 'Proje Düzenle — ' . $project->name : 'Yeni Proje Oluştur' }}
                </h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Proje temel bilgileri, lokasyon/harita seçimi, bütçe, yüklenici/taşeron detayları ve doküman yüklemelerini eksiksiz tanımlayın.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('portfolio.all-projects') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Tüm Projeler Listesi
                </a>
            </div>
        </div>
    </div>

    <!-- VALIDATION ERRORS -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm p-3.5 mb-4 bg-danger text-white" role="alert" style="background: #ef4444 !important;">
            <i class="ri-error-warning-fill fs-4 me-2"></i>
            <strong>Lütfen aşağıdaki form hatalarını düzeltin:</strong>
            <ul class="mb-0 mt-2 fw-semibold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- MAIN FORM CONTAINER -->
    <div class="card custom-project-form-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-edit-box-fill text-warning fs-5"></i>Proje Kayıt & Detay Tanımlama Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="{{ $project->id ? route('projects.update', $project->id) : route('projects.store') }}" enctype="multipart/form-data">
                @csrf
                @if($project->id)
                    @method('PUT')
                @endif

                <!-- SECTION 1: TEMEL PROJE VE ADRES -->
                <div class="border-bottom pb-3 mb-4">
                    <h6 class="fw-extrabold text-primary mb-3 d-flex align-items-center gap-2" style="font-size: 1rem;">
                        <i class="ri-building-line text-warning"></i> 1. Temel Proje & Adres Bilgileri
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Proje İsmi <span class="text-danger">*</span></label>
                            <input name="name" class="form-control custom-input-project" value="{{ old('name', $project->name) }}" placeholder="Örn: Göktürk Konutları Faz II" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Proje Kodu / Referans</label>
                            <input name="project_code" class="form-control custom-input-project font-monospace" value="{{ old('project_code', $project->project_code ?? '') }}" placeholder="Örn: PRJ-2026-001">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-custom">İl (Lokasyon)</label>
                            <select name="location_province" class="form-select custom-input-project">
                                <option value="">İl Seçin</option>
                                @php
                                    $provinces = [
                                        'Adana', 'Adıyaman', 'Afyonkarahisar', 'Ağrı', 'Amasya', 'Ankara', 'Antalya', 'Artvin', 'Aydın', 'Balıkesir',
                                        'Bilecik', 'Bingöl', 'Bitlis', 'Bolu', 'Burdur', 'Bursa', 'Çanakkale', 'Çankırı', 'Çorum', 'Denizli',
                                        'Diyarbakır', 'Edirne', 'Elazığ', 'Erzincan', 'Erzurum', 'Eskişehir', 'Gaziantep', 'Giresun', 'Gümüşhane', 'Hakkari',
                                        'Hatay', 'Isparta', 'Mersin', 'İstanbul', 'İzmir', 'Kars', 'Kastamonu', 'Kayseri', 'Kırklareli', 'Kırşehir',
                                        'Kocaeli', 'Konya', 'Kütahya', 'Malatya', 'Manisa', 'Kahramanmaraş', 'Mardin', 'Muğla', 'Muş', 'Nevşehir',
                                        'Niğde', 'Ordu', 'Rize', 'Sakarya', 'Samsun', 'Siirt', 'Sinop', 'Sivas', 'Tekirdağ', 'Tokat',
                                        'Trabzon', 'Tunceli', 'Şanlıurfa', 'Uşak', 'Van', 'Yozgat', 'Zonguldak', 'Aksaray', 'Bayburt', 'Karaman',
                                        'Kırıkkale', 'Batman', 'Şırnak', 'Bartın', 'Ardahan', 'Iğdır', 'Yalova', 'Karabük', 'Kilis', 'Osmaniye', 'Düzce'
                                    ];
                                    sort($provinces);
                                @endphp
                                @foreach($provinces as $province)
                                    <option value="{{ $province }}" {{ old('location_province', $project->location_province ?? '') == $province ? 'selected' : '' }}>{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">İlçe (Lokasyon)</label>
                            <select name="location_district" class="form-select custom-input-project" id="location-district-select">
                                <option value="">Önce il seçin</option>
                                @if(old('location_district', $project->location_district ?? ''))
                                    <option value="{{ old('location_district', $project->location_district) }}" selected>{{ old('location_district', $project->location_district) }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Cadde / Sokak</label>
                            <input name="cadde_sokak" class="form-control custom-input-project" value="{{ old('cadde_sokak', $project->cadde_sokak ?? '') }}" placeholder="Detaylı adres bilgisi">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-custom">Durum <span class="text-danger">*</span></label>
                            <select name="status" class="form-select custom-input-project" required>
                                @foreach(['planning'=>'Planlama','bidding'=>'İhale','contracted'=>'Sözleşmeli','construction'=>'İnşaat / Devam Ediyor','completed'=>'Tamamlandı'] as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $project->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Proje Tipi</label>
                            <select name="type" class="form-select custom-input-project">
                                <option value="">Seçiniz</option>
                                @foreach(['residential' => 'Konut Projesi', 'commercial' => 'Ticari Proje', 'infrastructure' => 'Altyapı & Taahhüt', 'industrial' => 'Endüstriyel Tesis'] as $k => $t)
                                    <option value="{{ $k }}" {{ old('type', $project->type) == $k ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Program / Grup ID</label>
                            <input name="program_id" class="form-control custom-input-project" value="{{ old('program_id', $project->program_id) }}" placeholder="Opsiyonel program ismi">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Bölge / Bölge Kodu</label>
                            <input name="region" class="form-control custom-input-project" value="{{ old('region', $project->region) }}" placeholder="Örn: Marmara, Ege, Ankara">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Belediye / İlçe</label>
                            <input name="municipality" class="form-control custom-input-project" value="{{ old('municipality', $project->municipality ?? '') }}" placeholder="İlgili belediye adı">
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: BÜTÇE VE İLERLEME -->
                <div class="border-bottom pb-3 mb-4">
                    <h6 class="fw-extrabold text-primary mb-3 d-flex align-items-center gap-2" style="font-size: 1rem;">
                        <i class="ri-money-dollar-circle-line text-warning"></i> 2. Bütçe, İlerleme & Zaman Çizelgesi
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label-custom">Toplam Proje Bütçesi (TL)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-warning fw-extrabold">₺</span>
                                <input name="budget" type="number" step="0.01" class="form-control custom-input-project text-success fw-bold font-monospace" value="{{ old('budget', $project->budget) }}" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Harcanan Toplam Tutar (TL)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-warning fw-extrabold">₺</span>
                                <input name="spent" type="number" step="0.01" class="form-control custom-input-project font-monospace" value="{{ $calculatedSpent ?? old('spent', $project->spent) }}" placeholder="Harcanan miktar" readonly>
                            </div>
                            <small class="text-muted fw-bold">Gerçek verilerden hesaplanan: ₺{{ number_format($calculatedSpent ?? 0, 2) }}</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom d-flex justify-content-between">
                                Fiziki İlerleme (%) 
                                <small id="progressLabel" class="text-primary font-monospace fw-extrabold">{{ $calculatedProgress ?? old('progress_percent', $project->progress_percent ?? 0) }}%</small>
                            </label>
                            <input id="progressRange" name="progress_percent" type="range" min="0" max="100" class="form-range mt-2" value="{{ $calculatedProgress ?? old('progress_percent', $project->progress_percent ?? 0) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Başlangıç Tarihi</label>
                            <input name="start_date" type="date" class="form-control custom-input-project" value="{{ old('start_date', optional($project->start_date)->format('Y-m-d') ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Bitiş Tarihi</label>
                            <input name="end_date" type="date" class="form-control custom-input-project" value="{{ old('end_date', optional($project->end_date)->format('Y-m-d') ?? '') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Proje Açıklaması & Notlar</label>
                            <textarea name="description" rows="4" class="form-control custom-input-project" placeholder="Projenin kapsamı, hedefleri ve genel açıklamaları...">{{ old('description', $project->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: HARİTA VE COĞRAFİ KONUM -->
                <div class="border-bottom pb-3 mb-4">
                    <h6 class="fw-extrabold text-primary mb-3 d-flex align-items-center gap-2" style="font-size: 1rem;">
                        <i class="ri-map-pin-line text-warning"></i> 3. Harita Konumu & Coğrafi Koordinatlar (GIS)
                    </h6>
                    @php
                        $initialLat = null; $initialLng = null;
                        $locVal = old('location', $project->location ?? '');
                        if (is_string($locVal) && $locVal) {
                            $decoded = json_decode($locVal, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $initialLat = $decoded['lat'] ?? $decoded['latitude'] ?? null;
                                $initialLng = $decoded['lng'] ?? $decoded['longitude'] ?? null;
                            }
                        } elseif (is_array($locVal)) {
                            $initialLat = $locVal['lat'] ?? $locVal['latitude'] ?? null;
                            $initialLng = $locVal['lng'] ?? $locVal['longitude'] ?? null;
                        }
                    @endphp
                    <div class="row g-3">
                        <div class="col-12">
                            <div id="project-map" style="width:100%; height:360px; border-radius:14px; overflow:hidden; border:1.5px solid #94a3b8;"></div>
                            <div class="mt-2 d-flex gap-2 align-items-center">
                                <button type="button" id="btnLocate" class="btn btn-sm btn-warning text-dark rounded-pill px-3.5 fw-extrabold shadow-sm">
                                    <i class="ri-navigation-fill me-1"></i>Konumumu Bul
                                </button>
                                <small class="text-muted fw-semibold">Harita üzerinde tıklayarak veya pin sürükleyerek tam koordinat seçebilirsiniz.</small>
                            </div>
                            <input type="hidden" name="location" id="location_input" value='{{ old('location', is_string($project->location) ? $project->location : (is_array($project->location) ? json_encode($project->location) : '')) }}'>
                            <div class="row g-3 mt-1">
                                <div class="col-md-3">
                                    <label class="form-label-custom">Enlem (Latitude)</label>
                                    <input id="location_lat" name="latitude" type="text" class="form-control custom-input-project font-monospace" readonly value="{{ $initialLat ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-custom">Boylam (Longitude)</label>
                                    <input id="location_lng" name="longitude" type="text" class="form-control custom-input-project font-monospace" readonly value="{{ $initialLng ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: KADASTRO TAPU VE RUHSAT -->
                <div class="border-bottom pb-3 mb-4">
                    <h6 class="fw-extrabold text-primary mb-3 d-flex align-items-center gap-2" style="font-size: 1rem;">
                        <i class="ri-shield-keyhole-line text-warning"></i> 4. Kadastro / Tapu / Ruhsat Bilgileri
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Yapı Ruhsatı Numarası</label>
                            <input name="building_permit_number" class="form-control custom-input-project font-monospace" value="{{ old('building_permit_number', $project->building_permit_number ?? '') }}" placeholder="Ruhsat numarası">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Ruhsat Veriliş Tarihi</label>
                            <input name="permit_issue_date" type="date" class="form-control custom-input-project" value="{{ old('permit_issue_date', optional($project->permit_issue_date)->format('Y-m-d') ?? '') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label-custom">Pafta</label>
                            <input name="tapu_pafta" class="form-control custom-input-project" value="{{ old('tapu_pafta', $project->tapu_pafta ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Ada</label>
                            <input name="tapu_ada" class="form-control custom-input-project" value="{{ old('tapu_ada', $project->tapu_ada ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Parsel</label>
                            <input name="tapu_parsel" class="form-control custom-input-project" value="{{ old('tapu_parsel', $project->tapu_parsel ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Cilt (Opsiyonel)</label>
                            <input name="tapu_volume" class="form-control custom-input-project" value="{{ old('tapu_volume', $project->tapu_volume ?? '') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Tapu Referansı (Tam Metin)</label>
                            <input name="land_registry_reference" class="form-control custom-input-project" value="{{ old('land_registry_reference', $project->land_registry_reference ?? '') }}" placeholder="Tapunun tam referans bilgisi">
                        </div>
                    </div>
                </div>

                <!-- SECTION 5: SÖZLEŞME VE YÜKLENİCİ -->
                <div class="border-bottom pb-3 mb-4">
                    <h6 class="fw-extrabold text-primary mb-3 d-flex align-items-center gap-2" style="font-size: 1rem;">
                        <i class="ri-user-star-line text-warning"></i> 5. Sözleşme, İşveren & Yüklenici Detayları
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label-custom">Sözleşme / Protokol No</label>
                            <input name="contract_number" class="form-control custom-input-project font-monospace" value="{{ old('contract_number', $project->contract_number ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">İşveren / Müvekkil Unvanı</label>
                            <input name="client_name" class="form-control custom-input-project" value="{{ old('client_name', $project->client_name ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Ayrı İşveren Adı</label>
                            <input name="employer_name" class="form-control custom-input-project" value="{{ old('employer_name', $project->employer_name ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Yüklenici / Taşeron Adı</label>
                            <input name="contractor_name" class="form-control custom-input-project" value="{{ old('contractor_name', $project->contractor_name ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Taşeron Firma (Kayıtlı)</label>
                            <select name="subcontractor_id" class="form-select custom-input-project">
                                <option value="">-- Seçiniz --</option>
                                @if(!empty($subcontractors) && $subcontractors->count())
                                    @foreach($subcontractors as $sc)
                                        <option value="{{ $sc->id }}" {{ (string)old('subcontractor_id', $project->subcontractor_id ?? '') === (string)$sc->id ? 'selected' : '' }}>🏢 {{ $sc->company_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Yüklenici VKN</label>
                            <input name="contractor_vkn" class="form-control custom-input-project font-monospace" value="{{ old('contractor_vkn', $project->contractor_vkn ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Yüklenici Ruhsat Numarası</label>
                            <input name="contractor_license_number" class="form-control custom-input-project font-monospace" value="{{ old('contractor_license_number', $project->contractor_license_number ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Sorumlu Mühendis Adı & Sicil</label>
                            <input name="responsible_engineer_name" class="form-control custom-input-project" value="{{ old('responsible_engineer_name', $project->responsible_engineer_name ?? '') }}" placeholder="Mühendis Adı">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Sorumlu Mimar Adı & Sicil</label>
                            <input name="responsible_architect_name" class="form-control custom-input-project" value="{{ old('responsible_architect_name', $project->responsible_architect_name ?? '') }}" placeholder="Mimar Adı">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-custom">Proje Yöneticisi</label>
                            <input name="project_manager" class="form-control custom-input-project" value="{{ old('project_manager', $project->project_manager) }}" placeholder="Ad Soyad">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Vergi No / TCKN</label>
                            <input name="tax_number" class="form-control custom-input-project font-monospace" value="{{ old('tax_number', $project->tax_number ?? '') }}" placeholder="10 haneli VKN / TCKN">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Proje Sağlığı</label>
                            <select name="health_status" class="form-select custom-input-project">
                                <option value="">Seçiniz</option>
                                <option value="healthy" {{ old('health_status', $project->health_status) == 'healthy' ? 'selected' : '' }}>🟢 Sağlıklı</option>
                                <option value="risky" {{ old('health_status', $project->health_status) == 'risky' ? 'selected' : '' }}>🟡 Riskli</option>
                                <option value="critical" {{ old('health_status', $project->health_status) == 'critical' ? 'selected' : '' }}>🔴 Kritik</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SECTION 6: TEKNİK YAPI FİZİKSEL BİLGİLER -->
                <div class="border-bottom pb-3 mb-4">
                    <h6 class="fw-extrabold text-primary mb-3 d-flex align-items-center gap-2" style="font-size: 1rem;">
                        <i class="ri-ruler-2-line text-warning"></i> 6. Teknik Yapı & Şantiye Özellikleri
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label-custom">Brüt Alan (m²)</label>
                            <input name="gross_area" type="number" step="0.01" class="form-control custom-input-project font-monospace" value="{{ old('gross_area', $project->gross_area ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Net Alan (m²)</label>
                            <input name="net_area" type="number" step="0.01" class="form-control custom-input-project font-monospace" value="{{ old('net_area', $project->net_area ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Kat Sayısı</label>
                            <input name="floor_count" type="number" class="form-control custom-input-project font-monospace" value="{{ old('floor_count', $project->floor_count ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Bina Kullanımı</label>
                            <select name="building_usage" class="form-select custom-input-project">
                                <option value="">Seçiniz</option>
                                <option value="residential" {{ old('building_usage', $project->building_usage) == 'residential' ? 'selected' : '' }}>Konut</option>
                                <option value="commercial" {{ old('building_usage', $project->building_usage) == 'commercial' ? 'selected' : '' }}>Ticari</option>
                                <option value="mixed" {{ old('building_usage', $project->building_usage) == 'mixed' ? 'selected' : '' }}>Karma</option>
                                <option value="industrial" {{ old('building_usage', $project->building_usage) == 'industrial' ? 'selected' : '' }}>Endüstriyel</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Yapı Sistemi</label>
                            <select name="structure_system" class="form-select custom-input-project">
                                <option value="">Seçiniz</option>
                                <option value="concrete" {{ old('structure_system', $project->structure_system) == 'concrete' ? 'selected' : '' }}>Betonarme</option>
                                <option value="steel" {{ old('structure_system', $project->structure_system) == 'steel' ? 'selected' : '' }}>Çelik</option>
                                <option value="mixed" {{ old('structure_system', $project->structure_system) == 'mixed' ? 'selected' : '' }}>Karma</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SECTION 7: SİGORTA VE DOKÜMAN YÜKLEMELERİ -->
                <div class="mb-4">
                    <h6 class="fw-extrabold text-primary mb-3 d-flex align-items-center gap-2" style="font-size: 1rem;">
                        <i class="ri-file-upload-line text-warning"></i> 7. Görsel & Dijital Doküman Yüklemeleri
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Kapak Görseli (Opsiyonel)</label>
                            <input id="coverImageInput" type="file" name="cover_image" class="form-control custom-input-project" accept="image/*">
                            <div id="coverImagePreview" class="mt-2">
                                @if(!empty($project->cover_image))
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ asset('storage/' . $project->cover_image) }}" alt="Kapak" style="max-width:160px; max-height:120px; object-fit:cover; border-radius:8px; border:1.5px solid #cbd5e1;" />
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remove_cover" id="removeCoverCheckbox" value="1">
                                            <label class="form-check-label fw-bold text-danger" for="removeCoverCheckbox">Kapağı Kaldır</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Yapı Ruhsatı Dosyası (PDF/Görsel)</label>
                            <input type="file" name="building_permit_file" class="form-control custom-input-project" accept=".pdf,.jpg,.jpeg,.png">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Yüklenici Ruhsat Dosyası (PDF/Görsel)</label>
                            <input type="file" name="contractor_license_file" class="form-control custom-input-project" accept=".pdf,.jpg,.jpeg,.png">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Sigorta Sertifikası (PDF/Görsel)</label>
                            <input type="file" name="insurance_certificate_file" class="form-control custom-input-project" accept=".pdf,.jpg,.jpeg,.png">
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Ek Şantiye Dokümanları</label>
                            <input id="attachmentsInput" type="file" name="attachments[]" class="form-control custom-input-project" multiple>
                            <div id="attachmentsPreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('portfolio.all-projects') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i>Projeyi Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let districtsByProvince = {};

    async function loadDistricts() {
        try {
            const response = await fetch('/api/locations/provinces');
            const data = await response.json();
            const provinces = data.provinces || [];

            for (const province of provinces) {
                try {
                    const districtResponse = await fetch(`/api/locations/districts?province=${encodeURIComponent(province)}`);
                    const districtData = await districtResponse.json();
                    districtsByProvince[province] = districtData.districts || [];
                } catch (error) {
                    districtsByProvince[province] = [];
                }
            }
        } catch (error) {
            districtsByProvince = {
                'İstanbul': ['Kadıköy', 'Beşiktaş', 'Şişli'],
                'Ankara': ['Çankaya', 'Keçiören', 'Mamak']
            };
        }
    }

    document.addEventListener('DOMContentLoaded', async function() {
        await loadDistricts();

        const locationProvinceSelect = document.querySelector('select[name="location_province"]');
        const locationDistrictSelect = document.getElementById('location-district-select');

        if (locationProvinceSelect && locationDistrictSelect) {
            locationProvinceSelect.addEventListener('change', function() {
                updateDistricts(this.value, locationDistrictSelect);
            });

            const currentLocationProvince = locationProvinceSelect.value;
            if (currentLocationProvince && districtsByProvince[currentLocationProvince]) {
                updateDistricts(currentLocationProvince, locationDistrictSelect);
            }
        }
    });

    function updateDistricts(selectedProvince, districtSelect) {
        const districts = districtsByProvince[selectedProvince] || [];
        districtSelect.innerHTML = '<option value="">İlçe Seçin</option>';
        districts.forEach(function(district) {
            const option = document.createElement('option');
            option.value = district;
            option.textContent = district;
            districtSelect.appendChild(option);
        });
    }

    (function(){
        var range = document.getElementById('progressRange');
        var label = document.getElementById('progressLabel');
        if(range && label){
            range.addEventListener('input', function(){
                label.textContent = range.value + '%';
            });
        }

        var attachmentsInput = document.getElementById('attachmentsInput');
        var preview = document.getElementById('attachmentsPreview');
        if(attachmentsInput && preview){
            attachmentsInput.addEventListener('change', function(e){
                preview.innerHTML = '';
                Array.from(e.target.files).forEach(function(file){
                    var container = document.createElement('div');
                    container.className = 'd-flex flex-column align-items-center';

                    if(file.type.startsWith('image/')){
                        var img = document.createElement('img');
                        img.alt = file.name;
                        img.title = file.name;
                        var reader = new FileReader();
                        reader.onload = function(evt){ img.src = evt.target.result; };
                        reader.readAsDataURL(file);
                        container.appendChild(img);
                    }

                    var name = document.createElement('div');
                    name.className = 'file-name mt-1 text-truncate';
                    name.style.maxWidth = '120px';
                    name.textContent = file.name;
                    container.appendChild(name);

                    preview.appendChild(container);
                });
            });
        }

        var coverInput = document.getElementById('coverImageInput');
        var coverPreview = document.getElementById('coverImagePreview');
        if(coverInput && coverPreview){
            coverInput.addEventListener('change', function(e){
                var file = e.target.files[0];
                if(!file) return;
                coverPreview.innerHTML = '';
                var container = document.createElement('div');
                container.className = 'd-flex flex-column';
                if(file.type.startsWith('image/')){
                    var img = document.createElement('img');
                    img.style.maxWidth = '160px';
                    img.style.maxHeight = '120px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '8px';
                    img.style.border = '1.5px solid #cbd5e1';
                    var reader = new FileReader();
                    reader.onload = function(evt){ img.src = evt.target.result; };
                    reader.readAsDataURL(file);
                    container.appendChild(img);
                }
                coverPreview.appendChild(container);
            });
        }
    })();
</script>

<link rel="stylesheet" href="{{ URL::asset('build/libs/leaflet/dist/leaflet.css') }}">
<script src="{{ URL::asset('build/libs/leaflet/dist/leaflet.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const mapEl = document.getElementById('project-map');
    if (!mapEl) return;

    const latInput = document.getElementById('location_lat');
    const lngInput = document.getElementById('location_lng');
    const hiddenInput = document.getElementById('location_input');

    const initialLat = parseFloat(latInput && latInput.value) || null;
    const initialLng = parseFloat(lngInput && lngInput.value) || null;
    const defaultCenter = (initialLat && initialLng) ? [initialLat, initialLng] : [39.0, 35.0];
    const map = L.map('project-map', { center: defaultCenter, zoom: initialLat ? 14 : 6 });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = null;
    function setMarker(lat, lng, pan=true) {
        if (marker) { marker.setLatLng([lat,lng]); }
        else {
            marker = L.marker([lat,lng], { draggable: true }).addTo(map);
            marker.on('dragend', function(e){ const p = e.target.getLatLng(); onPlaceSelected(p.lat, p.lng); });
        }
        if (pan) map.panTo([lat,lng]);
        onPlaceSelected(lat, lng);
    }

    function onPlaceSelected(lat, lng) {
        if (latInput) latInput.value = parseFloat(lat).toFixed(6);
        if (lngInput) lngInput.value = parseFloat(lng).toFixed(6);
        const obj = { lat: parseFloat(parseFloat(lat).toFixed(6)), lng: parseFloat(parseFloat(lng).toFixed(6)) };
        if (hiddenInput) hiddenInput.value = JSON.stringify(obj);
    }

    map.on('click', function(e){ setMarker(e.latlng.lat, e.latlng.lng, false); });

    const btnLocate = document.getElementById('btnLocate');
    if (btnLocate) btnLocate.addEventListener('click', function(){
        if (!navigator.geolocation) return alert('Tarayıcınız konum API desteğine sahip değil.');
        navigator.geolocation.getCurrentPosition(function(pos){ setMarker(pos.coords.latitude, pos.coords.longitude); map.setView([pos.coords.latitude, pos.coords.longitude], 14); }, function(){ alert('Konum alınamadı.'); });
    });

    if (initialLat && initialLng) setMarker(initialLat, initialLng, false);
});
</script>
@endpush
