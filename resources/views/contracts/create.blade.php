@extends('layouts.master')

@section('title', 'Yeni Sözleşme Oluştur')
@section('topbar-title', 'Sözleşme Yönetimi - Yeni Sözleşme')

@push('styles')
<style>
    /* HIGH-CONTRAST ROYAL DARK SLATE & EMERALD THEME */
    .contract-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #047857 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .contract-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #f59e0b, #3b82f6, #10b981);
        background-size: 300% 100%;
        animation: contractGlow 4s linear infinite;
    }

    @keyframes contractGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .contract-hero-title {
        color: #ffffff !important;
        font-weight: 800 !important;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4) !important;
    }

    .contract-hero-subtitle {
        color: #e2e8f0 !important;
        font-size: 1rem !important;
        font-weight: 500 !important;
        line-height: 1.6 !important;
    }

    /* KPI Summary Cards */
    .contract-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
    }

    .contract-kpi-title {
        color: #475569 !important;
        font-weight: 800 !important;
        font-size: 0.75rem !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase;
    }

    .contract-kpi-value {
        color: #0f172a !important;
        font-weight: 800 !important;
        font-size: 1.5rem !important;
    }

    /* Form Container & Section Headers */
    .contract-form-card {
        border-radius: 20px !important;
        padding: 2.2rem !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        margin-bottom: 2rem;
    }

    .form-section-header {
        background: #f8fafc !important;
        border-left: 5px solid #047857 !important;
        border-top: 1px solid #e2e8f0 !important;
        border-right: 1px solid #e2e8f0 !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 14px 18px !important;
        border-radius: 10px !important;
        margin-bottom: 22px !important;
        color: #0f172a !important;
        font-weight: 800 !important;
        font-size: 1.1rem !important;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Inputs */
    .custom-input-contract {
        background-color: #ffffff !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        border-radius: 10px !important;
    }

    .custom-input-contract:focus {
        background-color: #ffffff !important;
        border-color: #047857 !important;
        box-shadow: 0 0 0 3px rgba(4, 120, 87, 0.15) !important;
    }

    .custom-group-text {
        background-color: #f1f5f9 !important;
        border: 1.5px solid #64748b !important;
        color: #047857 !important;
        font-weight: 800 !important;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="contract-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <a href="{{ route('contracts.management') }}" class="btn btn-sm btn-light rounded-pill px-3.5 py-1.5 fw-extrabold text-dark shadow-sm">
                        <i class="ri-arrow-left-line me-1"></i>Sözleşmeler Listesine Dön
                    </a>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace" style="font-size: 11px;">
                        YENİ SÖZLEŞME MODÜLÜ
                    </span>
                </div>
                <h2 class="display-6 contract-hero-title mb-2">Yeni Sözleşme & Hukuki Şartname Oluştur</h2>
                <p class="contract-hero-subtitle mb-0" style="max-width: 820px;">
                    Saha taşeronluk, tedarik ve hizmet anlaşmalarının maliyet kalemlerini, teminat kesintilerini ve hazır hukuki maddelerini sisteme kaydedin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="p-3 rounded-4 border text-start" style="background: rgba(15, 23, 42, 0.6) !important; border-color: rgba(255, 255, 255, 0.2) !important;">
                    <small class="text-warning fw-extrabold d-block mb-1 fs-6"><i class="ri-information-fill me-1"></i>Otomatik İşlem Rehberi:</small>
                    <span class="text-white small d-block" style="line-height: 1.5; font-weight: 500;">
                        Sözleşme kaydedildikten sonra doğrudan doküman yükleme panosuna yönlendirilecek ve dijital imzalı evraklar eklenebilecektir.
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- LIVE COMPUTED SUMMARY CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card contract-kpi-card h-100 border-start border-primary border-4">
                <span class="contract-kpi-title mb-1 d-block">TAHMİNİ İMALAT SÜRESİ</span>
                <div class="contract-kpi-value font-monospace text-primary" id="live_duration">— Ay</div>
                <small class="text-secondary fw-bold mt-1 d-block"><i class="ri-calendar-line me-1"></i>Başlangıç & Bitiş Hesabı</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card contract-kpi-card h-100 border-start border-success border-4">
                <span class="contract-kpi-title mb-1 d-block">AYLIK TAHMİNİ HAKEDİŞ BÜTÇESİ</span>
                <div class="contract-kpi-value font-monospace text-success" id="live_monthly_budget">₺0,00</div>
                <small class="text-success fw-bold mt-1 d-block"><i class="ri-money-dollar-circle-line me-1"></i>Aylık Tahmini Dağılım</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card contract-kpi-card h-100 border-start border-warning border-4">
                <span class="contract-kpi-title mb-1 d-block">TAHMİNİ NAKİT TEMİNAT KESİNTİSİ (%5)</span>
                <div class="contract-kpi-value font-monospace text-warning" id="live_retention_est">₺0,00</div>
                <small class="text-warning fw-bold mt-1 d-block"><i class="ri-shield-check-line me-1"></i>Garanti / Hakediş Kesintisi</small>
            </div>
        </div>
    </div>

    <!-- MAIN FORM CONTAINER -->
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="contract-form-card">
                <form method="POST" action="{{ route('contracts.store') }}">
                    @csrf

                    <!-- BÖLÜM 1: TEMEL KİMLİK & TARAFLAR -->
                    <div class="form-section-header">
                        <i class="ri-building-line text-emerald fs-4" style="color: #047857;"></i> Bölüm 1: Temel Sözleşme Kimliği & Taraflar
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <label for="name" class="form-label fw-extrabold text-dark fs-6">SÖZLEŞME ADI / KONUSU <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text custom-group-text border-end-0" style="border-radius: 10px 0 0 10px !important;">
                                    <i class="ri-file-text-line fs-5"></i>
                                </span>
                                <input type="text" name="name" id="name" class="form-control custom-input-contract border-start-0 py-2.5 @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" placeholder="Örn: A-Blok İnce Yapı Taşeronluk Anlaşması" style="border-radius: 0 10px 10px 0 !important;" required>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1 fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-5">
                            <label for="number" class="form-label fw-extrabold text-dark fs-6">SÖZLEŞME NO / KODU</label>
                            <div class="input-group">
                                <span class="input-group-text custom-group-text border-end-0" style="border-radius: 10px 0 0 10px !important;">
                                    <i class="ri-barcode-line fs-5"></i>
                                </span>
                                <input type="text" name="number" id="number" class="form-control custom-input-contract border-start-0 py-2.5 font-monospace"
                                       value="{{ old('number') }}" placeholder="Örn: SZ-2026-004" style="border-radius: 0 10px 10px 0 !important;">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-5">
                            <label for="company" class="form-label fw-extrabold text-dark fs-6">YÜKLENİCİ / TAŞERON FİRMA <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text custom-group-text border-end-0" style="border-radius: 10px 0 0 10px !important;">
                                    <i class="ri-user-star-line fs-5"></i>
                                </span>
                                <input type="text" name="company" id="company" class="form-control custom-input-contract border-start-0 py-2.5"
                                       value="{{ old('company') }}" placeholder="Örn: Özkan Yapı & İnşaat Ltd. Şti." style="border-radius: 0 10px 10px 0 !important;" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="project_id" class="form-label fw-extrabold text-dark fs-6">İLGİLİ PROJE</label>
                            <div class="input-group">
                                <span class="input-group-text custom-group-text border-end-0" style="border-radius: 10px 0 0 10px !important;">
                                    <i class="ri-building-2-line fs-5"></i>
                                </span>
                                <select name="project_id" id="project_id" class="form-select custom-input-contract border-start-0 py-2.5" style="border-radius: 0 10px 10px 0 !important;">
                                    <option value="">Tüm Projeler (Genel Portföy)</option>
                                    @foreach($projects as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="type" class="form-label fw-extrabold text-dark fs-6">SÖZLEŞME TÜRÜ</label>
                            <div class="input-group">
                                <span class="input-group-text custom-group-text border-end-0" style="border-radius: 10px 0 0 10px !important;">
                                    <i class="ri-price-tag-3-line fs-5"></i>
                                </span>
                                <select name="type" id="type" class="form-select custom-input-contract border-start-0 py-2.5" style="border-radius: 0 10px 10px 0 !important;">
                                    <option value="Taşeron" selected>🔨 Taşeron Yüklenici</option>
                                    <option value="Tedarik">📦 Malzeme & Tedarik</option>
                                    <option value="Hizmet">📐 Hizmet & Müşavirlik</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- BÖLÜM 2: FİNANSAL BÜTÇE & SÜRE -->
                    <div class="form-section-header mt-4">
                        <i class="ri-coins-line text-warning fs-4" style="color: #d97706 !important;"></i> Bölüm 2: Finansal Bütçe & Süreç Takvimi
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="value" class="form-label fw-extrabold text-dark fs-6">SÖZLEŞME BEDELİ (TL) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text custom-group-text border-end-0" style="border-radius: 10px 0 0 10px !important;">₺</span>
                                <input type="number" step="0.01" name="value" id="value" class="form-control custom-input-contract border-start-0 py-2.5 font-monospace text-primary fw-bold fs-5"
                                       value="{{ old('value') }}" placeholder="Örn: 2500000.00" oninput="updateCreateFormCalc()" style="border-radius: 0 10px 10px 0 !important;" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="start_date" class="form-label fw-extrabold text-dark fs-6">BAŞLANGIÇ TARİHİ</label>
                            <div class="input-group">
                                <span class="input-group-text custom-group-text border-end-0" style="border-radius: 10px 0 0 10px !important;">
                                    <i class="ri-calendar-line fs-5"></i>
                                </span>
                                <input type="date" name="start_date" id="start_date" class="form-control custom-input-contract border-start-0 py-2.5"
                                       value="{{ old('start_date', date('Y-m-d')) }}" onchange="updateCreateFormCalc()" style="border-radius: 0 10px 10px 0 !important;">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="end_date" class="form-label fw-extrabold text-dark fs-6">BİTİŞ TARİHİ</label>
                            <div class="input-group">
                                <span class="input-group-text custom-group-text border-end-0" style="border-radius: 10px 0 0 10px !important;">
                                    <i class="ri-calendar-check-line fs-5"></i>
                                </span>
                                <input type="date" name="end_date" id="end_date" class="form-control custom-input-contract border-start-0 py-2.5"
                                       value="{{ old('end_date') }}" onchange="updateCreateFormCalc()" style="border-radius: 0 10px 10px 0 !important;">
                            </div>
                        </div>
                    </div>

                    <!-- BÖLÜM 3: HUKUKİ ŞABLON & MADDE KÜTÜPHANESİ -->
                    <div class="form-section-header mt-4">
                        <i class="ri-book-3-line text-primary fs-4"></i> Bölüm 3: Hazır Şablon & Madde Seçimi
                    </div>

                    <div class="mb-4">
                        <label for="template-select" class="form-label fw-extrabold text-dark fs-6">HAZIR SÖZLEŞME ŞABLONU SEÇİN (OPSİYONEL)</label>
                        <select id="template-select" name="template_id" class="form-select custom-input-contract py-2.5">
                            <option value="">— Bir şablon seçerek metni otomatik doldurabilirsiniz —</option>
                            @foreach($templates as $t)
                                <option data-body="{{ is_object($t) ? htmlentities($t->body, ENT_QUOTES, 'UTF-8') : '' }}" value="{{ is_object($t) ? $t->id : $t }}">
                                    {{ is_object($t) ? $t->title : $t }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark fs-6">MADDE KÜTÜPHANESİNDEN DAHİL EDİLECEK HÜKÜMLER</label>
                        <div class="p-3 border rounded-4 bg-light" style="border-color: #cbd5e1 !important; max-height: 200px; overflow-y: auto;">
                            <div class="row g-2">
                                @forelse($clauses as $cl)
                                    <div class="col-md-6">
                                        <div class="form-check p-2 rounded bg-white border mb-1">
                                            <input class="form-check-input ms-1 me-2" type="checkbox" name="clauses[]" value="{{ is_object($cl) ? $cl->id : $cl }}" id="clause-{{ is_object($cl) ? $cl->id : $loop->index }}" style="cursor: pointer;">
                                            <label class="form-check-label fw-bold text-dark small ms-1" for="clause-{{ is_object($cl) ? $cl->id : $loop->index }}" style="cursor: pointer;">
                                                <i class="ri-checkbox-circle-fill text-success me-1"></i>{{ is_object($cl) ? $cl->title : $cl }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted small py-2">Kütüphanede henüz kayıtlı madde bulunmuyor.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- BÖLÜM 4: ÖZEL ŞARTLAR & METİN -->
                    <div class="form-section-header mt-4">
                        <i class="ri-file-paper-line text-dark fs-4"></i> Bölüm 4: Şartname Metni & Özel Koşullar
                    </div>

                    <div class="mb-4">
                        <label for="notes-field" class="form-label fw-extrabold text-dark fs-6">SÖZLEŞME METNİ, CEZAİ ŞARTLAR & TEKNİK ŞARTNAME</label>
                        <textarea id="notes-field" name="notes" class="form-control custom-input-contract" rows="7" placeholder="Sözleşme özel şartları, ödeme takvimi, gecikme cezası (günlük %0.1 vb.) ve iş teslim kurallarını yazınız...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('contracts.management') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">İptal</a>
                        <button type="submit" class="btn btn-emerald text-white rounded-pill px-5 py-2.5 fw-extrabold shadow-lg d-inline-flex align-items-center gap-2" style="background: #047857; border: none;">
                            <i class="ri-save-3-fill fs-5"></i> Sözleşmeyi Sistem Kaydına Al
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
// DYNAMIC TEMPLATE INSERTER
document.getElementById('template-select')?.addEventListener('change', function(){
    const opt = this.selectedOptions[0];
    const body = opt ? (opt.dataset.body || '') : '';
    if (!body) return;
    try {
        const txt = document.createElement('textarea');
        txt.innerHTML = body;
        const notesField = document.getElementById('notes-field');
        notesField.value = txt.value + '\n\n' + (notesField.value || '');
    } catch(e){}
});

// REAL-TIME FINANCIAL & DURATION PREVIEW
function updateCreateFormCalc() {
    const val = parseFloat(document.getElementById('value').value) || 0;
    const startStr = document.getElementById('start_date').value;
    const endStr = document.getElementById('end_date').value;

    const fmt = new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' });

    // Retention estimate (%5)
    const retentionEst = val * 0.05;
    document.getElementById('live_retention_est').textContent = fmt.format(retentionEst);

    // Duration calculation
    if (startStr && endStr) {
        const startDate = new Date(startStr);
        const endDate = new Date(endStr);
        if (endDate > startDate) {
            const diffDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
            const diffMonths = (diffDays / 30.44).toFixed(1);
            document.getElementById('live_duration').textContent = `${diffMonths} Ay (${diffDays} Gün)`;

            if (diffMonths > 0 && val > 0) {
                const monthlyBudget = val / (diffDays / 30.44);
                document.getElementById('live_monthly_budget').textContent = fmt.format(monthlyBudget);
            } else {
                document.getElementById('live_monthly_budget').textContent = '₺0,00';
            }
            return;
        }
    }

    document.getElementById('live_duration').textContent = '— Ay';
    document.getElementById('live_monthly_budget').textContent = '₺0,00';
}

document.addEventListener('DOMContentLoaded', function() {
    updateCreateFormCalc();
});
</script>
@endpush
