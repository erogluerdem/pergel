@extends('layouts.master')

@section('title', 'Yeni İhale Paketi Oluşturma Sihirbazı')

@push('styles')
<style>
    .create-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .create-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: createGlow 4s linear infinite;
    }

    @keyframes createGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .create-badge {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .stepper-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        position: relative;
        background: #ffffff;
        padding: 20px 28px;
        border-radius: 18px;
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .stepper-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        z-index: 2;
        cursor: pointer;
    }

    .stepper-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #f1f5f9;
        border: 2px solid #cbd5e1;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.1rem;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }

    .stepper-item.active .stepper-circle {
        background: #d97706;
        border-color: #d97706;
        color: #ffffff;
        box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.2);
    }

    .stepper-item.completed .stepper-circle {
        background: #16a34a;
        border-color: #16a34a;
        color: #ffffff;
    }

    .stepper-title {
        font-size: 0.82rem;
        font-weight: 700;
        color: #475569;
    }

    .stepper-item.active .stepper-title {
        color: #0f172a;
        font-weight: 800;
    }

    .custom-wizard-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;
        overflow: hidden;
    }

    .custom-input-wizard {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-wizard:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .form-label-custom {
        font-weight: 800 !important;
        color: #0f172a !important;
        font-size: 0.88rem !important;
        margin-bottom: 6px !important;
    }

    .poz-scroll-box {
        max-height: 320px;
        overflow-y: auto;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        background: #ffffff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="create-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill create-badge">
                        <i class="ri-magic-line text-warning fs-5"></i> İHALE PAKETİ & ŞARTNAME SİHİRBAZI (VERİTABANI ENTEGRELİ)
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        SQL Canlı Veri Aktif
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Yeni İhale Paketi Oluştur</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Sistem veritabanındaki projeler, Çevre & Şehircilik poz kütüphanesi, kayıtlı taşeronlar ve doküman havuzundan seçim yaparak ihale paketinizi oluşturun.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('tenders.package.prepare') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>İhale Paketlerine Dön
                </a>
            </div>
        </div>
    </div>

    <!-- STEPPER PROGRESS BAR -->
    <div class="stepper-container">
        <div class="stepper-item active" data-step="1" onclick="jumpStep(1)">
            <div class="stepper-circle" id="circle_1">1</div>
            <div class="stepper-title">Temel Bilgiler</div>
        </div>
        <div class="stepper-item" data-step="2" onclick="jumpStep(2)">
            <div class="stepper-circle" id="circle_2">2</div>
            <div class="stepper-title">Dokümanlar & Şartname</div>
        </div>
        <div class="stepper-item" data-step="3" onclick="jumpStep(3)">
            <div class="stepper-circle" id="circle_3">3</div>
            <div class="stepper-title">Keşif / Poz Kütüphanesi (SQL)</div>
        </div>
        <div class="stepper-item" data-step="4" onclick="jumpStep(4)">
            <div class="stepper-circle" id="circle_4">4</div>
            <div class="stepper-title">Davetli Taşeronlar (SQL)</div>
        </div>
        <div class="stepper-item" data-step="5" onclick="jumpStep(5)">
            <div class="stepper-circle" id="circle_5">5</div>
            <div class="stepper-title">Gözden Geçir & Yayınla</div>
        </div>
    </div>

    <!-- FORM CONTAINER -->
    <form method="POST" action="{{ route('tenders.package.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- STEP 1: TEMEL BİLGİLER -->
        <div class="card custom-wizard-card mb-4 wizard-step-panel" id="step_panel_1">
            <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-information-fill text-warning fs-5"></i>Adım 1 — Paket Temel Bilgileri & İhale Şartları (SQL Veritabanı)
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">İlişkili Proje (SQL) <span class="text-danger">*</span></label>
                        <select name="project_id" id="inp_project_id" class="form-select custom-input-wizard" required>
                            <option value="">Kayıtlı Proje Seçiniz...</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}" {{ old('project_id') == $proj->id ? 'selected' : '' }}>
                                    🏢 {{ $proj->name }} ({{ $proj->location ?? 'Şantiye' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">İhale Paket Adı <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="inp_name" class="form-control custom-input-wizard" placeholder="Örn: İnce Yapı & Seramik İşleri Paketi" required value="{{ old('name') }}" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">İhale Yöneticisi (SQL)</label>
                        <select name="responsible_id" class="form-select custom-input-wizard">
                            <option value="">Sorumlu Yönetici Seçin...</option>
                            @foreach($managers as $m)
                                <option value="{{ $m->id }}">👤 {{ $m->name }} ({{ $m->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">İhale Usulü</label>
                        <select name="procurement_type" class="form-select custom-input-wizard">
                            <option value="acik">Açık İhale Usulü</option>
                            <option value="pazarlik">Pazarlık Usulü</option>
                            <option value="dogrudan">Doğrudan Temin</option>
                            <option value="teklif_alma">Teklif Alma / Davetli</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">Yaklaşık Maliyet & Para Birimi</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="estimated_cost" id="inp_estimated_cost" class="form-control custom-input-wizard" placeholder="0.00" value="{{ old('estimated_cost') }}" />
                            <select name="currency" class="form-select custom-input-wizard" style="max-width: 100px;">
                                <option value="TRY">TRY (₺)</option>
                                <option value="EUR">EUR (€)</option>
                                <option value="USD">USD ($)</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">Sözleşme Süresi (Gün)</label>
                        <input type="number" name="contract_duration_days" class="form-control custom-input-wizard" placeholder="Örn: 120" value="{{ old('contract_duration_days', 120) }}" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">Geçici Teminat Oranı (%)</label>
                        <input type="number" step="0.01" name="guarantee_percent" class="form-control custom-input-wizard" placeholder="Örn: 3" value="{{ old('guarantee_percent', 3) }}" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">İhale Numarası / Referans Kodu</label>
                        <input type="text" name="tender_reference" class="form-control custom-input-wizard" placeholder="Örn: PER-2026-IH-04" value="{{ old('tender_reference', 'IH-'.rand(1000,9999)) }}" />
                    </div>

                    <div class="col-12">
                        <label class="form-label-custom">İşin Kapsamı & Açıklaması</label>
                        <textarea name="scope" class="form-control custom-input-wizard" rows="3" placeholder="İhale paketinin kapsadığı imalat kalemleri, teknik standartlar ve şantiye teslim koşulları...">{{ old('scope') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Teklif Başlangıç Tarihi & Saati</label>
                        <input type="datetime-local" name="start_date" class="form-control custom-input-wizard" value="{{ old('start_date', date('Y-m-d\TH:i', strtotime('+1 hour'))) }}" />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Teklif Bitiş Tarihi & Saati</label>
                        <input type="datetime-local" name="end_date" class="form-control custom-input-wizard" value="{{ old('end_date', date('Y-m-d\TH:i', strtotime('+14 days'))) }}" />
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 2: DOKÜMANLAR -->
        <div class="card custom-wizard-card mb-4 wizard-step-panel" id="step_panel_2" style="display: none;">
            <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-file-pdf-fill text-warning fs-5"></i>Adım 2 — İhale Dokümanları & Şartname Havuzu (SQL)
                </h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted fw-semibold mb-3">Veritabanından ihale dosyasına eklenecek resmi doküman ve şartnameleri işaretleyin.</p>

                @if(!empty($documents) && count($documents) > 0)
                    <label class="form-label-custom mb-2">System Document Library (SQL Kayıtları)</label>
                    <div class="poz-scroll-box p-3 mb-4">
                        <div class="row g-2">
                            @foreach($documents as $doc)
                                <div class="col-md-6">
                                    <div class="form-check p-3 bg-white rounded-3 border h-100 d-flex align-items-center">
                                        <input class="form-check-input ms-0 me-2" type="checkbox" name="documents[]" value="{{ $doc->file_path ?? $doc->name }}" id="doc_sql_{{ $doc->id }}">
                                        <label class="form-check-label text-dark fw-bold text-truncate" for="doc_sql_{{ $doc->id }}">
                                            📄 {{ $doc->name }} <small class="text-muted font-monospace d-block">({{ $doc->discipline ?? 'Genel' }})</small>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <label class="form-label-custom mb-2">Zorunlu İhale Şartname Şablonları</label>
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="form-check p-3 bg-white rounded-3 border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" checked name="documents[]" value="Idari_Sartname.pdf" id="doc_std_1">
                            <label class="form-check-label text-dark fw-extrabold" for="doc_std_1">📋 İdari Şartname & İhale Kuralları</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check p-3 bg-white rounded-3 border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" checked name="documents[]" value="Teknik_Sartname.pdf" id="doc_std_2">
                            <label class="form-check-label text-dark fw-extrabold" for="doc_std_2">🛠️ Teknik Şartname & Malzeme Standartları</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check p-3 bg-white rounded-3 border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" checked name="documents[]" value="Metraj_Cetveli_BOQ.xlsx" id="doc_std_3">
                            <label class="form-check-label text-dark fw-extrabold" for="doc_std_3">📊 Birim Fiyat Metraj Cetveli (BOQ)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check p-3 bg-white rounded-3 border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" checked name="documents[]" value="Sozlesme_Taslagi.pdf" id="doc_std_4">
                            <label class="form-check-label text-dark fw-extrabold" for="doc_std_4">⚖️ Sözleşme Taslağı & Teminat Koşulları</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 3: KEŞİF / POZ KÜTÜPHANESİ (SQL) -->
        <div class="card custom-wizard-card mb-4 wizard-step-panel" id="step_panel_3" style="display: none;">
            <div class="card-header bg-dark text-white p-3.5 d-flex justify-content-between align-items-center" style="background-color: #0f172a !important;">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-calculator-line text-warning fs-5"></i>Adım 3 — Çevre & Şehircilik Poz Kütüphanesi (SQL Veritabanı)
                </h5>
                <span class="badge bg-warning text-dark font-monospace fw-bold">{{ count($csbPozs) }} Poz Hazır</span>
            </div>
            <div class="card-body p-4">
                <p class="text-muted fw-semibold mb-3">Çevre ve Şehircilik Bakanlığı poz kütüphanesinden ihale paketine eklenecek imalat kalemlerini seçin veya CSV dosyasından içe aktarın.</p>

                <div class="mb-4">
                    <label class="form-label-custom">SQL Poz Kütüphanesinden Kalem Seçimi</label>
                    <input type="text" id="csbPozSearch" class="form-control custom-input-wizard mb-2" placeholder="Poz numarası veya tanım ara (Örn: 15.120.1001)...">

                    <div class="poz-scroll-box p-3">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="csbPozTable">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">Seç</th>
                                        <th>Poz No</th>
                                        <th>Açıklama / Tanım</th>
                                        <th>Birim</th>
                                        <th class="text-end">Bakanlık Birim Fiyatı</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($csbPozs as $poz)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_pozs[]" value="{{ $poz->id }}" class="form-check-input poz-checkbox" id="poz_{{ $poz->id }}">
                                            </td>
                                            <td>
                                                <label for="poz_{{ $poz->id }}" class="badge bg-dark text-warning font-monospace px-2 py-1 fw-bold">
                                                    {{ $poz->poz_number }}
                                                </label>
                                            </td>
                                            <td>
                                                <label for="poz_{{ $poz->id }}" class="fw-bold text-dark mb-0 d-block" style="cursor: pointer;">
                                                    {{ $poz->title }}
                                                </label>
                                                <small class="text-muted">{{ Str::limit($poz->description ?? '', 80) }}</small>
                                            </td>
                                            <td class="font-monospace fw-bold">{{ $poz->unit }}</td>
                                            <td class="text-end font-monospace fw-extrabold text-success">
                                                ₺{{ number_format($poz->unit_price ?? 0, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">SQL Poz kütüphanesinde henüz veri bulunmuyor. Aşağıdan CSV yükleyebilirsiniz.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-3">
                    <label class="form-label-custom">Veya BOQ Metraj Cetveli (CSV) Yükleyin</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" name="boq_csv" class="form-control custom-input-wizard" accept=".csv, .txt" />
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 4: DAVETLİ TAŞERONLAR (SQL) -->
        <div class="card custom-wizard-card mb-4 wizard-step-panel" id="step_panel_4" style="display: none;">
            <div class="card-header bg-dark text-white p-3.5 d-flex justify-content-between align-items-center" style="background-color: #0f172a !important;">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-user-add-line text-warning fs-5"></i>Adım 4 — Kayıtlı Taşeron & Tedarikçi Veritabanı (SQL)
                </h5>
                <span class="badge bg-success text-white font-monospace fw-bold">{{ count($subcontractors) }} Taşeron Kayıtlı</span>
            </div>
            <div class="card-body p-4">
                <p class="text-muted fw-semibold mb-3">Sistem veritabanındaki kayıtlı taşeron firmalardan ihaleye davet edilecekleri seçin.</p>

                @if(!empty($subcontractors) && count($subcontractors) > 0)
                    <div class="poz-scroll-box p-3 mb-4">
                        <div class="row g-3">
                            @foreach($subcontractors as $sub)
                                <div class="col-md-6">
                                    <div class="card h-100 border p-3 shadow-sm rounded-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="selected_subcontractors[]" value="{{ $sub->id }}" id="sub_{{ $sub->id }}">
                                            <div class="overflow-hidden">
                                                <label for="sub_{{ $sub->id }}" class="fw-extrabold text-dark d-block mb-0 text-truncate" style="cursor: pointer;">
                                                    🏢 {{ $sub->company_name }}
                                                </label>
                                                <small class="text-muted d-block font-monospace">📧 {{ $sub->email ?? 'E-posta Kayıtsız' }}</small>
                                                <span class="badge bg-warning text-dark font-monospace mt-1">Uzmanlık: {{ $sub->expertise_area ?? 'Genel Taşeron' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="border-top pt-3">
                    <label class="form-label-custom">Ekstra E-Posta Adresleri (Her satıra bir e-posta adresi yazın)</label>
                    <textarea name="bidders_text" class="form-control custom-input-wizard" rows="3" placeholder="firma1@example.com&#10;firma2@example.com">{{ old('bidders_text') }}</textarea>
                </div>
            </div>
        </div>

        <!-- STEP 5: GÖZDEN GEÇİR & YAYINLA -->
        <div class="card custom-wizard-card mb-4 wizard-step-panel" id="step_panel_5" style="display: none;">
            <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-send-plane-fill text-warning fs-5"></i>Adım 5 — Gözden Geçir ve İhaleyi Onayla
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <h4 class="fw-extrabold text-dark mb-3">İhale Paketi Özeti</h4>
                        <table class="table table-bordered align-middle mb-0">
                            <tr>
                                <th class="bg-light text-dark fw-bold" style="width: 200px;">İhale Paketi Adı:</th>
                                <td class="fw-extrabold text-primary font-size-15" id="sum_name">—</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark fw-bold">Seçili Proje:</th>
                                <td class="fw-bold text-dark" id="sum_project">—</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark fw-bold">Yaklaşık Maliyet:</th>
                                <td class="fw-extrabold text-success font-monospace" id="sum_cost">—</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark fw-bold">Seçili Poz Sayısı:</th>
                                <td class="fw-bold text-dark" id="sum_poz_count">0 Kalem</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-lg-5 text-center border-start ps-lg-4">
                        <i class="ri-checkbox-circle-fill text-success display-1 mb-2 d-block"></i>
                        <h5 class="fw-extrabold text-dark mb-2">Kaydetmeye Hazır!</h5>
                        <p class="text-muted small mb-3">Taslak olarak kaydedebilir veya doğrudan yayına alabilirsiniz.</p>

                        <div class="d-flex flex-column gap-2">
                            <button type="submit" name="action" value="draft" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold shadow-sm">
                                <i class="ri-draft-line me-1"></i>Taslak Olarak Kaydet
                            </button>
                            <button type="submit" name="action" value="publish" class="btn btn-success text-white rounded-pill px-4 py-2.5 fw-extrabold shadow-sm" style="background: #16a34a !important;">
                                <i class="ri-send-plane-fill me-1"></i>İhaleyi Canlıya Al & Yayınla
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- WIZARD NAVIGATION BAR -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <button type="button" id="btn_prev" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold d-none">
                <i class="ri-arrow-left-line me-1"></i>Önceki Adım
            </button>
            <div class="ms-auto d-flex gap-2">
                <button type="button" id="btn_next" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                    Sonraki Adım <i class="ri-arrow-right-line ms-1"></i>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 5;

    function updateWizardUI() {
        for (let i = 1; i <= totalSteps; i++) {
            const panel = document.getElementById('step_panel_' + i);
            if (panel) panel.style.display = (i === currentStep) ? 'block' : 'none';

            const item = document.querySelector('.stepper-item[data-step="' + i + '"]');
            if (item) {
                item.classList.remove('active', 'completed');
                if (i === currentStep) {
                    item.classList.add('active');
                } else if (i < currentStep) {
                    item.classList.add('completed');
                }
            }
        }

        const prevBtn = document.getElementById('btn_prev');
        const nextBtn = document.getElementById('btn_next');

        if (prevBtn) {
            if (currentStep > 1) prevBtn.classList.remove('d-none');
            else prevBtn.classList.add('d-none');
        }

        if (nextBtn) {
            if (currentStep === totalSteps) {
                nextBtn.classList.add('d-none');
            } else {
                nextBtn.classList.remove('d-none');
            }
        }

        if (currentStep === 5) {
            updateSummaryData();
        }
    }

    function updateSummaryData() {
        const nameInput = document.getElementById('inp_name');
        const projectSelect = document.getElementById('inp_project_id');
        const costInput = document.getElementById('inp_estimated_cost');

        if (nameInput && document.getElementById('sum_name')) {
            document.getElementById('sum_name').textContent = nameInput.value || 'İhalesiz Paket';
        }

        if (projectSelect && document.getElementById('sum_project')) {
            const opt = projectSelect.options[projectSelect.selectedIndex];
            document.getElementById('sum_project').textContent = opt ? opt.text : 'Seçilmedi';
        }

        if (costInput && document.getElementById('sum_cost')) {
            const val = parseFloat(costInput.value) || 0;
            document.getElementById('sum_cost').textContent = '₺' + new Intl.NumberFormat('tr-TR').format(val);
        }

        const checkedPozs = document.querySelectorAll('.poz-checkbox:checked').length;
        if (document.getElementById('sum_poz_count')) {
            document.getElementById('sum_poz_count').textContent = checkedPozs + ' Kalem Poz Seçildi';
        }
    }

    function jumpStep(stepNum) {
        if (stepNum >= 1 && stepNum <= totalSteps) {
            currentStep = stepNum;
            updateWizardUI();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const prevBtn = document.getElementById('btn_prev');
        const nextBtn = document.getElementById('btn_next');

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateWizardUI();
                }
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    updateWizardUI();
                }
            });
        }

        // Filter CSB Poz table
        const csbSearch = document.getElementById('csbPozSearch');
        if (csbSearch) {
            csbSearch.addEventListener('keyup', function() {
                const query = this.value.toLowerCase();
                const rows = document.querySelectorAll('#csbPozTable tbody tr');
                rows.forEach(function(row) {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.indexOf(query) > -1 ? '' : 'none';
                });
            });
        }

        updateWizardUI();
    });
</script>
@endpush
@endsection
