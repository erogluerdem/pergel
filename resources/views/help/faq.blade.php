@extends('layouts.master')

@section('title', 'Sıkça Sorulan Sorular (SSS) & Bilgi Bankası')
@section('topbar-title', 'Destek - Sıkça Sorulan Sorular')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & AMBER GLASSMORPHIC THEME */
    .faq-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #78350f 50%, #d97706 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .faq-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #10b981, #3b82f6, #f59e0b);
        background-size: 300% 100%;
        animation: faqGlow 4s linear infinite;
    }

    @keyframes faqGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .faq-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Summary Cards */
    .faq-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .faq-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .faq-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Accordion Styling */
    .custom-faq-accordion .accordion-item {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 14px !important;
        margin-bottom: 14px !important;
        background: #ffffff !important;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
    }

    .custom-faq-accordion .accordion-button {
        background-color: #ffffff !important;
        color: #0f172a !important;
        font-weight: 800 !important;
        font-size: 1rem !important;
        padding: 18px 22px !important;
        border: none !important;
    }

    .custom-faq-accordion .accordion-button:not(.collapsed) {
        background: #f8fafc !important;
        color: #d97706 !important;
        box-shadow: inset 0 -1px 0 rgba(0,0,0,0.08);
    }

    .custom-faq-accordion .accordion-body {
        background-color: #ffffff !important;
        color: #334155 !important;
        font-weight: 500 !important;
        font-size: 0.95rem !important;
        line-height: 1.7 !important;
        padding: 20px 24px !important;
    }

    .custom-input-faq {
        background-color: #ffffff !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        border-radius: 10px !important;
    }

    .custom-input-faq:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="faq-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <a href="{{ route('help.index') }}" class="btn btn-sm btn-light rounded-pill px-3.5 py-1.5 fw-extrabold text-dark shadow-sm">
                        <i class="ri-arrow-left-line me-1"></i>Yardım Merkezine Dön
                    </a>
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill faq-badge-pill">
                        <i class="ri-questionnaire-fill text-warning fs-5"></i> SIKÇA SORULAN SORULAR
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace" style="font-size: 11px;">
                        12 MODÜL BİLGİ BANKASI
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">Sıkça Sorulan Sorular & Çözüm Bankası</h2>
                <p class="mb-0 fs-6" style="color: #fef08a; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    Şantiye ve gayrimenkul platformunun kullanımı, hakediş stopajı, GİB e-fatura, EBİS beton testi ve SGK asgari işçilik konularında anlık yanıtlar.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="p-3.5 rounded-4 border text-start" style="background: rgba(15, 23, 42, 0.6) !important; border-color: rgba(255, 255, 255, 0.2) !important;">
                    <small class="text-warning fw-extrabold d-block mb-1 fs-6"><i class="ri-search-eye-line me-1"></i>Anlık Arama Özelliği:</small>
                    <span class="text-white small d-block" style="line-height: 1.5; font-weight: 500;">
                        Aşağıdaki arama çubuğunu kullanarak sorunuzu yazabilir ve doğrudan çözüme ulaşabilirsiniz.
                    </span>
                </div>
            </div>
        </div>
    </div>

    @php
        $totalFaqCount = 0;
        foreach($faqCategories as $cat) {
            $totalFaqCount += count($cat->faqs);
        }
    @endphp

    <!-- 4 SUMMARY KPI CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card faq-kpi-card h-100 border-start border-warning border-4 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TOPLAM SSS YANITI</span>
                        <h2 class="fw-extrabold mb-0 text-warning font-monospace">{{ $totalFaqCount }} <small class="fs-6 text-muted">Soru & Cevap</small></h2>
                    </div>
                    <div class="faq-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-question-answer-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-shield-check-line me-1"></i>Doğrulanmış Bilgiler</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">Kapsamlı Arşiv</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card faq-kpi-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">KAPSANAN MODÜLLER</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace">{{ count($faqCategories) }} <small class="fs-6 text-muted">Kategori</small></h2>
                    </div>
                    <div class="faq-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-apps-2-line fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-building-line me-1"></i>Tüm Sektörel Alanlar</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Modüler Sistem</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card faq-kpi-card h-100 border-start border-primary border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">ANLIK ÇÖZÜM ORANI</span>
                        <h2 class="fw-extrabold mb-0 text-primary font-monospace">%98.5</h2>
                    </div>
                    <div class="faq-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-thumb-up-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-user-smile-line me-1"></i>Müşteri Memnuniyeti</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">Yüksek Başarı</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card faq-kpi-card h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">CANLI DESTEK SERVİSİ</span>
                        <h2 class="fw-extrabold mb-0 text-info">7/24</h2>
                    </div>
                    <div class="faq-stat-icon bg-info-subtle text-info" style="background: rgba(2, 132, 199, 0.12);">
                        <i class="ri-customer-service-2-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-headset-line me-1"></i>Teknik Uzman Ekip</small>
                    <span class="badge bg-info-subtle text-info fw-bold">Aktif Destek</span>
                </div>
            </div>
        </div>
    </div>

    <!-- LIVE SEARCH & CATEGORY FILTER -->
    <div class="card border rounded-4 p-4 mb-4" style="border-color: #cbd5e1 !important;">
        <div class="row g-3 align-items-center">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-text custom-input-faq border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                        <i class="ri-search-2-line text-muted fs-5"></i>
                    </span>
                    <input type="text" id="faqSearchInput" class="form-control custom-input-faq border-start-0 py-2.5" placeholder="Sıkça sorulan bir soru veya kelime yazarak arayın..." style="border-radius: 0 10px 10px 0 !important;">
                </div>
            </div>

            <div class="col-lg-6 text-lg-end">
                <a href="{{ route('help.contact') }}" class="btn btn-emerald text-white rounded-pill px-4 py-2.5 fw-extrabold shadow-sm d-inline-flex align-items-center gap-2" style="background: #047857; border: none;">
                    <i class="ri-mail-send-fill fs-5"></i> Sorunuz Yanıtlanmadı mı? Destek Talebi Açın
                </a>
            </div>
        </div>
    </div>

    <!-- FAQ CATEGORIES ACCORDION -->
    <div class="row" id="faqContainer">
        @php $globalIndex = 0; @endphp
        @foreach($faqCategories as $catIndex => $cat)
            <div class="col-12 mb-4 faq-category-block" data-category="{{ $cat->name }}">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h4 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="ri-folder-open-fill text-warning fs-4"></i> {{ $cat->name }}
                    </h4>
                    <span class="badge bg-dark font-monospace text-warning px-3 py-1 rounded-pill fw-bold">{{ count($cat->faqs) }} Soru</span>
                </div>

                <div class="accordion custom-faq-accordion" id="accordionCat{{ $catIndex }}">
                    @foreach($cat->faqs as $faqIndex => $faq)
                        @php $globalIndex++; @endphp
                        <div class="accordion-item faq-item-card">
                            <h2 class="accordion-header" id="heading{{ $globalIndex }}">
                                <button class="accordion-button {{ $globalIndex === 1 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $globalIndex }}" aria-expanded="{{ $globalIndex === 1 ? 'true' : 'false' }}" aria-controls="collapse{{ $globalIndex }}">
                                    <i class="ri-question-fill text-warning me-2 fs-5"></i>
                                    <span class="faq-question-text">{{ $faq->question }}</span>
                                </button>
                            </h2>
                            <div id="collapse{{ $globalIndex }}" class="accordion-collapse collapse {{ $globalIndex === 1 ? 'show' : '' }}" aria-labelledby="heading{{ $globalIndex }}" data-bs-parent="#accordionCat{{ $catIndex }}">
                                <div class="accordion-body">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="p-2 rounded-3 bg-emerald-subtle text-emerald flex-shrink-0" style="background: rgba(16, 185, 129, 0.12); color: #047857;">
                                            <i class="ri-checkbox-circle-fill fs-4"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block text-dark mb-1">Çözüm ve Açıklama:</strong>
                                            <p class="mb-0 text-secondary faq-answer-text">{{ $faq->answer }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- CALL TO ACTION BANNER -->
    <div class="card border-0 rounded-4 p-4 text-white mt-3 shadow-lg" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h4 class="fw-extrabold text-white mb-2">Aradığınız Cevabı Bulamadınız mı?</h4>
                <p class="text-white-50 mb-0">Teknik destek ekibimiz ve şantiye uzmanlarımız sorularınızı 15 dakika içinde yanıtlamak üzere hazır bekliyor.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('help.contact') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold shadow-sm">
                    <i class="ri-customer-service-2-fill me-1"></i> Destek Ekibine Ulaşın
                </a>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('faqSearchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('.faq-item-card').forEach(item => {
                const question = item.querySelector('.faq-question-text')?.textContent.toLowerCase() || '';
                const answer = item.querySelector('.faq-answer-text')?.textContent.toLowerCase() || '';
                if (question.indexOf(filter) > -1 || answer.indexOf(filter) > -1) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endpush