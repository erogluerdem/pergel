@extends('layouts.master')

@section('title', '7/24 Canlı Destek & Teknik Bilet Merkezi')
@section('topbar-title', 'Destek - Canlı Destek & Bilet Oluştur')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & EMERALD/SAPPHIRE GLASSMORPHIC THEME */
    .support-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #064e3b 50%, #047857 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .support-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #f59e0b, #3b82f6, #10b981);
        background-size: 300% 100%;
        animation: supportGlow 4s linear infinite;
    }

    @keyframes supportGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .support-badge-pill {
        background: rgba(16, 185, 129, 0.25) !important;
        border: 1px solid rgba(16, 185, 129, 0.5) !important;
        color: #a7f3d0 !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Summary Cards */
    .support-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .support-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .support-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .support-glass-card {
        border-radius: 20px !important;
        padding: 2.2rem !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        margin-bottom: 2rem;
    }

    .custom-input-support {
        background-color: #ffffff !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        border-radius: 10px !important;
    }

    .custom-input-support:focus {
        background-color: #ffffff !important;
        border-color: #047857 !important;
        box-shadow: 0 0 0 3px rgba(4, 120, 87, 0.15) !important;
    }

    .contact-info-card {
        border-radius: 16px !important;
        border: 1.5px solid #cbd5e1 !important;
        background: #ffffff !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
        margin-bottom: 16px;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="support-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <a href="{{ route('help.index') }}" class="btn btn-sm btn-light rounded-pill px-3.5 py-1.5 fw-extrabold text-dark shadow-sm">
                        <i class="ri-arrow-left-line me-1"></i>Yardım Merkezine Dön
                    </a>
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill support-badge-pill">
                        <i class="ri-headset-fill text-warning fs-5"></i> 7/24 CANLI DESTEK MERKEZİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace" style="font-size: 11px;">
                        ORTALAMA YANIT SÜRESİ: 12 DK
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">7/24 Canlı Destek & Bilet Oluşturucu</h2>
                <p class="mb-0 fs-6" style="color: #a7f3d0; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    Şantiye operasyonları, hakediş kesintileri, GİB e-fatura entegrasyonu veya sistem hataları için doğrudan kıdemli yazılım ve şantiye mühendislerimize ulaşın.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="p-3.5 rounded-4 border text-start" style="background: rgba(15, 23, 42, 0.6) !important; border-color: rgba(255, 255, 255, 0.2) !important;">
                    <small class="text-warning fw-extrabold d-block mb-1 fs-6"><i class="ri-alarm-warning-fill me-1"></i>Kritik Saha Kesintisi mi Var?</small>
                    <span class="text-white small d-block" style="line-height: 1.5; font-weight: 500;">
                        Acil durum biletleri öncelikli nöbetçi şantiye mühendisine otomatik iletilir.
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- SUCCESS ALERT -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-3 bg-success text-white" role="alert">
            <i class="ri-checkbox-circle-fill fs-3"></i>
            <div>
                <strong class="d-block fs-6">Destek Bileti Başarıyla İletildi!</strong>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- 4 SUMMARY KPI CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card support-kpi-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">ORTALAMA YANIT SÜRESİ</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace">12 <small class="fs-6 text-muted">Dakika</small></h2>
                    </div>
                    <div class="support-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-timer-flash-line fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-check-double-line me-1"></i>Hızlı Dönüş Garantisi</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Nöbetçi Ekip</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card support-kpi-card h-100 border-start border-primary border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">ÇÖZÜMLENEN TALEPLER</span>
                        <h2 class="fw-extrabold mb-0 text-primary font-monospace">%99.4</h2>
                    </div>
                    <div class="support-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-checkbox-circle-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-shield-check-line me-1"></i>Kalıcı Çözüm</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">Uzman Kadro</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card support-kpi-card h-100 border-start border-danger border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">ACİL SAHA KESİNTİSİ</span>
                        <h2 class="fw-extrabold mb-0 text-danger font-monospace">7/24 <small class="fs-6 text-muted">Hat</small></h2>
                    </div>
                    <div class="support-stat-icon bg-danger-subtle text-danger" style="background: rgba(239, 68, 68, 0.12);">
                        <i class="ri-phone-find-line fs-2" style="color: #ef4444 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-alarm-warning-line me-1"></i>Kritik Öncelik</small>
                    <span class="badge bg-danger-subtle text-danger fw-bold">Canlı Mühendis</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card support-kpi-card h-100 border-start border-warning border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">MÜŞTERİ MEMNUNİYETİ</span>
                        <h2 class="fw-extrabold mb-0 text-warning font-monospace">4.9 / 5.0</h2>
                    </div>
                    <div class="support-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-star-smile-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-heart-3-line me-1"></i>Kullanıcı Değerlendirmesi</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">★★★★★</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TWO-COLUMN CONTAINER -->
    <div class="row g-4">
        <!-- LEFT COLUMN: TICKET FORM -->
        <div class="col-lg-8">
            <div class="support-glass-card">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                    <div>
                        <h4 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2">
                            <i class="ri-mail-add-fill text-emerald fs-4" style="color: #047857;"></i> Yeni Destek Bilet Kaydı Oluştur
                        </h4>
                        <p class="text-muted small mb-0">Formu doldurarak yaşadığınız teknik durumu tarafımıza iletin.</p>
                    </div>
                    <span class="badge bg-dark text-warning font-monospace px-3 py-1.5 rounded-pill fw-bold">SSL 256-Bit Şifreli</span>
                </div>

                <form method="POST" action="{{ route('help.support.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-extrabold text-dark fs-6">ADINIZ SOYADINIZ <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control custom-input-support py-2.5"
                                   value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="Örn: Erdem Eroğlu" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-extrabold text-dark fs-6">E-POSTA ADRESİ <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control custom-input-support py-2.5"
                                   value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="destek@firma.com" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-extrabold text-dark fs-6">DESTEK KATEGORİSİ <span class="text-danger">*</span></label>
                            <select name="category" class="form-select custom-input-support py-2.5" required>
                                <option value="">Kategori Seçiniz...</option>
                                @foreach($supportCategories as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-extrabold text-dark fs-6">ÖNCELİK DERECESİ <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select custom-input-support py-2.5" required>
                                <option value="low">🟢 Düşük (Genel Soru / Bilgi Talebi)</option>
                                <option value="medium" selected>🔵 Orta (Standart Kullanım / İşlem Desteği)</option>
                                <option value="high">🟠 Yüksek (Hakediş / E-Fatura Aksaması)</option>
                                <option value="urgent">🔴 ACİL (Kritik Saha / Veri Kesintisi)</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-extrabold text-dark fs-6">DESTEK KONUSU / BAŞLIK <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control custom-input-support py-2.5"
                                   placeholder="Örn: Hakediş #4 Stopaj kesintisi raporunda KDV tutarı tutarsızlığı" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-extrabold text-dark fs-6">TALEBİNİZ / MESAJ DETAYLARI <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control custom-input-support py-2" rows="5"
                                      placeholder="Karşılaştığınız hata mesajını, tarayıcı türünü ve adım adım ne yaparken bu durumun oluştuğunu yazınız..." required></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-extrabold text-dark fs-6">EKRAN GÖRÜNTÜSÜ VEYA BELGE EKLEYİN (OPSİYONEL)</label>
                            <input type="file" name="attachment" class="form-control custom-input-support py-2" accept="image/*,.pdf,.doc,.docx,.xlsx">
                            <small class="text-muted mt-1 d-block">Desteklenen formatlar: PNG, JPG, PDF, XLSX (Maks: 10MB)</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('help.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">İptal</a>
                        <button type="submit" class="btn btn-emerald text-white rounded-pill px-5 py-2.5 fw-extrabold shadow-lg d-inline-flex align-items-center gap-2" style="background: #047857; border: none;">
                            <i class="ri-send-plane-fill fs-5"></i> Destek Biletini İlet
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- RIGHT COLUMN: CONTACT & DIRECT HELPLINES -->
        <div class="col-lg-4">
            <!-- CANLI TELEFON VE WHATSAPP HATTI -->
            <div class="contact-info-card border-start border-warning border-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 rounded-4 bg-warning bg-opacity-15 text-warning flex-shrink-0">
                        <i class="ri-phone-fill fs-3" style="color: #d97706 !important;"></i>
                    </div>
                    <div>
                        <h6 class="fw-extrabold text-dark mb-0 fs-6">Canlı Çağrı & Çağrı Merkezi</h6>
                        <small class="text-muted fw-bold">Hafta İçi: 08:30 - 18:30</small>
                    </div>
                </div>
                <h4 class="fw-extrabold text-dark font-monospace mb-1">+90 (850) 800-PERGEL</h4>
                <p class="text-secondary small mb-3">Santral üzerinden doğrudan şantiye yazılım uzmanına bağlanın.</p>
                <a href="tel:+908508007374" class="btn btn-sm btn-outline-warning w-100 rounded-pill fw-bold text-dark">
                    <i class="ri-phone-line me-1"></i> Hemen Arayın
                </a>
            </div>

            <!-- WHATSAPP DESTEK HATTI -->
            <div class="contact-info-card border-start border-success border-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 rounded-4 bg-success bg-opacity-15 text-success flex-shrink-0">
                        <i class="ri-whatsapp-fill fs-3" style="color: #047857 !important;"></i>
                    </div>
                    <div>
                        <h6 class="fw-extrabold text-dark mb-0 fs-6">WhatsApp Saha Destek Hatı</h6>
                        <small class="text-muted fw-bold">Fotoğraf / Ekran Görüntüsü İletimi</small>
                    </div>
                </div>
                <h4 class="fw-extrabold text-dark font-monospace mb-1">+90 (530) 000-0000</h4>
                <p class="text-secondary small mb-3">Şantiye sahasından anlık fotoğraf ve video gönderin.</p>
                <a href="https://wa.me/905300000000" target="_blank" class="btn btn-sm btn-success text-white w-100 rounded-pill fw-bold" style="background: #047857;">
                    <i class="ri-whatsapp-line me-1"></i> WhatsApp'tan Yazın
                </a>
            </div>

            <!-- E-POSTA VE TEKNİK DESTEK -->
            <div class="contact-info-card border-start border-primary border-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 rounded-4 bg-primary bg-opacity-15 text-primary flex-shrink-0">
                        <i class="ri-mail-fill fs-3" style="color: #2563eb !important;"></i>
                    </div>
                    <div>
                        <h6 class="fw-extrabold text-dark mb-0 fs-6">E-Posta Desteği</h6>
                        <small class="text-muted fw-bold">Bilet İnceleme Ekibi</small>
                    </div>
                </div>
                <span class="d-block fw-extrabold text-dark font-monospace mb-1 fs-6">destek@pergel.app</span>
                <p class="text-secondary small mb-3">İhale, sözleşme ve veritabanı talepleriniz için.</p>
                <a href="mailto:destek@pergel.app" class="btn btn-sm btn-outline-primary w-100 rounded-pill fw-bold">
                    <i class="ri-mail-send-line me-1"></i> E-Posta Gönder
                </a>
            </div>

            <!-- SSS VE REHBER HIZLI LİNKLER -->
            <div class="card border rounded-4 p-4" style="border-color: #cbd5e1 !important; background: #f8fafc;">
                <h6 class="fw-extrabold text-dark mb-3"><i class="ri-links-line me-1 text-warning"></i> Hızlı Kısayollar</h6>
                <ul class="list-unstyled mb-0 d-grid gap-2">
                    <li>
                        <a href="{{ route('help.faq') }}" class="text-dark fw-bold text-decoration-none d-flex align-items-center justify-content-between p-2 rounded bg-white border">
                            <span><i class="ri-questionnaire-line me-2 text-warning"></i> Sıkça Sorulan Sorular (SSS)</span>
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('help.videos') }}" class="text-dark fw-bold text-decoration-none d-flex align-items-center justify-content-between p-2 rounded bg-white border">
                            <span><i class="ri-video-line me-2 text-primary"></i> Video Eğitim Kütüphanesi</span>
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('help.guide') }}" class="text-dark fw-bold text-decoration-none d-flex align-items-center justify-content-between p-2 rounded bg-white border">
                            <span><i class="ri-book-read-line me-2 text-success"></i> Adım Adım Başlangıç Rehberi</span>
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>

@endsection