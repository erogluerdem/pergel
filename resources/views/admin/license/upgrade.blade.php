@extends('layouts.master')

@section('title', 'PERGEL Pro\'ya Yükselt')

@section('topbar-title', 'Yükseltme — PERGEL Pro')

@push('styles')
<style>
    .upgrade-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #d97706 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.25);
    }
    .upgrade-hero::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.25) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .upgrade-card {
        border-radius: 18px;
        transition: all 0.3s ease;
        border: 1px solid rgba(226, 232, 240, 0.8);
        background: #ffffff;
    }
    html[data-bs-theme="dark"] .upgrade-card {
        background: rgba(30, 41, 59, 0.85);
        border-color: rgba(255, 255, 255, 0.08);
    }
    .feature-check {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        margin-right: 10px;
        flex-shrink: 0;
    }
    .feature-pro-card {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.08) 0%, rgba(217, 119, 6, 0.03) 100%);
        border: 2px solid rgba(245, 158, 11, 0.35) !important;
        position: relative;
    }
    .pro-ribbon {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        padding: 4px 12px;
        border-radius: 50px;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }
</style>
@endpush

@section('content')

@if(session('info'))
    <div class="alert alert-warning alert-dismissible fade show rounded-pill px-4 shadow-sm" role="alert">
        <i class="ri-vip-crown-fill me-2"></i>{{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-pill px-4" role="alert">
        <i class="ri-error-warning-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(request('reason') === 'project_quota')
    <div class="alert alert-warning border-warning border-opacity-25 bg-warning bg-opacity-10 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center gap-3">
        <div class="fs-2 text-warning"><i class="ri-alert-fill"></i></div>
        <div>
            <h6 class="fw-bold mb-1 text-dark">Proje Kotası Ulaşıldı (Maksimum 2 Proje)</h6>
            <p class="mb-0 small text-muted">Ücretsiz Topluluk (Community) sürümünde eşzamanlı maksimum 2 aktif proje açılabilir. Sınırsız proje açmak ve projelerinizi büyütmek için Pro pakete geçiş yapın.</p>
        </div>
    </div>
@elseif(request('reason') === 'user_quota')
    <div class="alert alert-warning border-warning border-opacity-25 bg-warning bg-opacity-10 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center gap-3">
        <div class="fs-2 text-warning"><i class="ri-team-fill"></i></div>
        <div>
            <h6 class="fw-bold mb-1 text-dark">Kullanıcı Kotası Ulaşıldı (Maksimum 2 Kullanıcı)</h6>
            <p class="mb-0 small text-muted">Ücretsiz Topluluk sürümünde maksimum 2 kullanıcı tanımlanabilir. Ekibinizle sınır olmadan ortak çalışabilmek için paketinizi yükseltin.</p>
        </div>
    </div>
@elseif(request('reason') === 'asset_quota')
    <div class="alert alert-warning border-warning border-opacity-25 bg-warning bg-opacity-10 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center gap-3">
        <div class="fs-2 text-warning"><i class="ri-archive-stack-fill"></i></div>
        <div>
            <h6 class="fw-bold mb-1 text-dark">Varlık / Ekipman Kotası Ulaşıldı (Maksimum 25 Varlık)</h6>
            <p class="mb-0 small text-muted">Topluluk sürümünde maksimum 25 varlık kaydı yapılabilir. Sınırsız varlık yönetimi için paketinizi yükseltin.</p>
        </div>
    </div>
@endif

<!-- Hero Header -->
<div class="upgrade-hero">
    <div class="row align-items-center position-relative" style="z-index: 2;">
        <div class="col-lg-8">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-20 text-white mb-3">
                <i class="ri-vip-crown-2-fill text-warning"></i>
                <span class="fw-bold small">KURUMSAL PRO EKLENTİSİ</span>
            </div>
            @if($moduleMeta)
                <h1 class="fw-extrabold text-white mb-2 display-6">
                    <i class="{{ $moduleMeta['icon'] ?? 'fas fa-cube' }} me-2 text-warning"></i>{{ $moduleMeta['name'] }}
                </h1>
                <p class="text-white-50 fs-6 mb-3">
                    {{ $moduleMeta['description'] }}
                </p>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @foreach($moduleMeta['features'] ?? [] as $feat)
                        <span class="badge bg-white bg-opacity-15 text-white py-2 px-3 rounded-pill fw-normal">
                            <i class="ri-checkbox-circle-fill text-warning me-1"></i> {{ $feat }}
                        </span>
                    @endforeach
                </div>
            @else
                <h1 class="fw-extrabold text-white mb-2 display-6">PERGEL Pro İle Şantiyenizin Gücünü Katlayın</h1>
                <p class="text-white-50 fs-6 mb-3">
                    Taşeron yönetimi, resmi ÇŞB pozları, hakedişler, İSG denetimleri ve yapay zeka araçlarıyla projelerinizi profesyonelce yönetin.
                </p>
            @endif
        </div>
        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
            <a href="{{ $renewUrl }}" target="_blank" rel="noopener" class="btn btn-warning btn-lg rounded-pill px-4 fw-bold shadow-lg text-dark">
                <i class="ri-shopping-cart-2-fill me-2"></i>Pro Lisansı Satın Al
            </a>
            <div class="mt-2 text-white-50 small">
                <i class="ri-shield-check-fill text-success me-1"></i>7/24 Kesintisiz Lisanslama & Destek
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Karşılaştırma Kartı -->
    <div class="col-lg-8">
        <div class="card upgrade-card h-100 p-4 shadow-sm">
            <h4 class="fw-bold mb-4 d-flex align-items-center gap-2">
                <i class="ri-scales-3-line text-primary"></i>
                Paket Karşılaştırması: Topluluk vs. PERGEL Pro
            </h4>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">Özellik / Modül</th>
                            <th class="text-center py-3" style="width: 150px;">Topluluk (Ücretsiz)</th>
                            <th class="text-center py-3 text-warning fw-bold" style="width: 170px;">PERGEL Pro 👑</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-light">
                            <td class="fw-bold"><i class="fas fa-folder text-primary me-2"></i>Maksimum Aktif Proje</td>
                            <td class="text-center fw-semibold text-muted">2 Proje</td>
                            <td class="text-center text-success fw-bold"><i class="ri-infinity-fill me-1"></i>Sınırsız</td>
                        </tr>
                        <tr class="table-light">
                            <td class="fw-bold"><i class="fas fa-users text-info me-2"></i>Kullanıcı / Ekip Limiti</td>
                            <td class="text-center fw-semibold text-muted">2 Kullanıcı</td>
                            <td class="text-center text-success fw-bold"><i class="ri-infinity-fill me-1"></i>Sınırsız</td>
                        </tr>
                        <tr class="table-light">
                            <td class="fw-bold"><i class="fas fa-cubes text-secondary me-2"></i>Tesis & Ekipman Varlık Limiti</td>
                            <td class="text-center fw-semibold text-muted">25 Varlık</td>
                            <td class="text-center text-success fw-bold"><i class="ri-infinity-fill me-1"></i>Sınırsız</td>
                        </tr>
                        <tr class="table-light">
                            <td class="fw-bold"><i class="fas fa-file-export text-warning me-2"></i>Gelişmiş Excel / PDF Dışa Aktarım</td>
                            <td class="text-center text-muted"><i class="ri-close-circle-fill text-secondary fs-5"></i></td>
                            <td class="text-center text-success fw-bold"><i class="ri-checkbox-circle-fill text-success fs-5"></i> Dahil</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Temel Proje & Görev Yönetimi (Kanban & Takvim)</td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i></td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tesis Varlıkları & Temel Arıza Bildirimi</td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i></td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Temel CRM & Müşteri Görüşme Takibi</td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i></td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Progresif Web Uygulaması (PWA Mobil Uyum)</td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i></td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i></td>
                        </tr>
                        <tr class="table-warning table-opacity-10">
                            <td class="fw-bold text-dark">
                                <i class="fas fa-flag text-danger me-1"></i> Türkiye İnşaat Mevzuatı (ÇŞB Pozları, SGK, Yİ-ÜFE)
                            </td>
                            <td class="text-center text-muted"><i class="ri-close-circle-fill text-secondary fs-5"></i></td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i> <span class="badge bg-warning text-dark ms-1">PRO</span></td>
                        </tr>
                        <tr class="table-warning table-opacity-10">
                            <td class="fw-bold text-dark">
                                <i class="fas fa-user-friends text-primary me-1"></i> Taşeron Portalı (Dış Paydaş Girişi, Evrak & Avans)
                            </td>
                            <td class="text-center text-muted"><i class="ri-close-circle-fill text-secondary fs-5"></i></td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i> <span class="badge bg-warning text-dark ms-1">PRO</span></td>
                        </tr>
                        <tr class="table-warning table-opacity-10">
                            <td class="fw-bold text-dark">
                                <i class="fas fa-coins text-warning me-1"></i> Resmi Hakediş & Yeşil Defter & Nakit Akışı
                            </td>
                            <td class="text-center text-muted"><i class="ri-close-circle-fill text-secondary fs-5"></i></td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i> <span class="badge bg-warning text-dark ms-1">PRO</span></td>
                        </tr>
                        <tr class="table-warning table-opacity-10">
                            <td class="fw-bold text-dark">
                                <i class="fas fa-file-signature text-info me-1"></i> İhale Paketi Hazırlama & Teklif Toplama
                            </td>
                            <td class="text-center text-muted"><i class="ri-close-circle-fill text-secondary fs-5"></i></td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i> <span class="badge bg-warning text-dark ms-1">PRO</span></td>
                        </tr>
                        <tr class="table-warning table-opacity-10">
                            <td class="fw-bold text-dark">
                                <i class="fas fa-hard-hat text-secondary me-1"></i> İş Sağlığı (İSG) & Saha Kalite / Beton Kırım
                            </td>
                            <td class="text-center text-muted"><i class="ri-close-circle-fill text-secondary fs-5"></i></td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i> <span class="badge bg-warning text-dark ms-1">PRO</span></td>
                        </tr>
                        <tr class="table-warning table-opacity-10">
                            <td class="fw-bold text-dark">
                                <i class="fas fa-qrcode text-success me-1"></i> Kat Planı QR Kodları & SLA Servis Yönetimi
                            </td>
                            <td class="text-center text-muted"><i class="ri-close-circle-fill text-secondary fs-5"></i></td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i> <span class="badge bg-warning text-dark ms-1">PRO</span></td>
                        </tr>
                        <tr class="table-warning table-opacity-10">
                            <td class="fw-bold text-dark">
                                <i class="fas fa-robot text-purple me-1"></i> Yapay Zeka Asistanı & BIM 3D Entegrasyonu
                            </td>
                            <td class="text-center text-muted"><i class="ri-close-circle-fill text-secondary fs-5"></i></td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i> <span class="badge bg-warning text-dark ms-1">PRO</span></td>
                        </tr>
                        <tr class="table-warning table-opacity-10">
                            <td class="fw-bold text-dark">
                                <i class="fas fa-chart-line text-info me-1"></i> Executive BI Dashboard & Dinamik Raporlar
                            </td>
                            <td class="text-center text-muted"><i class="ri-close-circle-fill text-secondary fs-5"></i></td>
                            <td class="text-center"><i class="ri-checkbox-circle-fill text-success fs-5"></i> <span class="badge bg-warning text-dark ms-1">PRO</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Aktivasyon & Bilgi Kartı -->
    <div class="col-lg-4">
        <!-- Hızlı Aktivasyon -->
        <div class="card upgrade-card feature-pro-card p-4 mb-4 shadow-sm">
            <span class="pro-ribbon"><i class="ri-vip-crown-fill me-1"></i>PRO AKTİVASYON</span>
            <h5 class="fw-bold text-dark mb-3 mt-1">Lisans Anahtarını Etkinleştir</h5>
            <p class="text-muted small mb-3">
                Satın almış olduğunuz PERGEL Pro lisans anahtarını aşağıdaki alana girerek kilitli tüm modülleri hemen aktif edebilirsiniz.
            </p>

            <form action="{{ route('admin.license.activate') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Lisans Anahtarı</label>
                    <input type="text" name="license_key" class="form-control rounded-3 font-monospace text-uppercase" placeholder="PRGL-XXXX-XXXX-XXXX" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Kurulum Kimliği (UUID)</label>
                    <input type="text" class="form-control rounded-3 font-monospace text-muted small bg-light" value="{{ $installationId }}" readonly>
                </div>
                <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold py-2 shadow-sm text-dark">
                    <i class="ri-key-fill me-1"></i>Lisansı Aktifleştir
                </button>
            </form>
        </div>

        <!-- Kurumsal Destek & Bilgi -->
        <div class="card upgrade-card p-4 shadow-sm">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="ri-customer-service-2-fill text-primary"></i> Kurumsal Satış & İletişim
            </h5>
            <p class="text-muted small mb-3">
                Çoklu şantiye lisansı, sunucu kurulumu (on-premise) veya özel ERP entegrasyonu ihtiyaçlarınız için bize ulaşabilirsiniz.
            </p>
            <ul class="list-unstyled small mb-4">
                <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Tüm Pro modülleri sınırsız kullanım</li>
                <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Öncelikli SLA teknik destek</li>
                <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Ücretsiz sürüm güncellemeleri</li>
            </ul>
            <a href="https://erdemeroglu.com.tr/iletisim" target="_blank" rel="noopener" class="btn btn-outline-primary rounded-pill w-100 fw-bold">
                <i class="ri-mail-send-line me-1"></i>Teklif & Bilgi Al
            </a>
        </div>
    </div>
</div>

@endsection
