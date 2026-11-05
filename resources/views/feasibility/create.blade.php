@extends('layouts.master')

@section('title', 'Yeni Fizibilite & Sermaye Simülasyonu')

@push('styles')
<style>
    .feas-create-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .feas-create-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: feasCreateGlow 4s linear infinite;
    }

    @keyframes feasCreateGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .feas-badge {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-calc {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-feas-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-feas {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-feas:focus {
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
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="feas-create-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill feas-badge">
                        <i class="ri-calculator-fill text-warning fs-5"></i> FİZİBİLİTE & SERMAYE METRİK SİMÜLATÖRÜ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Yeni Analiz Çalışması
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Yeni Fizibilite & Sermaye Çalışması</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Arsa maliyeti, inşaat birim bedelleri, yumuşak maliyetler ve finansman yapısıyla projenizin ROI, NPV ve Brüt Kar simülasyonunu oluşturun.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('feasibility.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Çalışma Listesi
                    </a>
                    <a href="{{ route('feasibility.compare') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-scales-3-line me-1"></i>Karşılaştırma
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($prefillOpportunityId))
        <div class="alert bg-dark text-white border border-warning rounded-4 p-3 mb-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="ri-lightbulb-fill text-warning fs-4"></i>
                <span class="fw-bold">Bu fizibilite çalışması <strong>CRM Fırsat #{{ $prefillOpportunityId }}</strong> üzerinden başlatıldı. İlişkili fırsat seçimi otomatik eşleştirildi.</span>
            </div>
            <a href="{{ route('opportunities.show', $prefillOpportunityId) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-bold">Fırsatı İncele</a>
        </div>
    @endif

    <!-- LIVE COMPUTED FINANCIAL PREVIEW CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-calc h-100 border-start border-success border-4">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">HESAPLANAN TOPLAM GELİR</small>
                <div class="fw-extrabold text-success font-monospace" id="prev_revenue" style="font-size: 1.5rem;">₺0</div>
                <small class="text-muted fw-bold">(İnşaat Alanı × Birim Satış + Diğer)</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-calc h-100 border-start border-danger border-4">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">HESAPLANAN TOPLAM MALİYET</small>
                <div class="fw-extrabold text-danger font-monospace" id="prev_cost" style="font-size: 1.5rem;">₺0</div>
                <small class="text-muted fw-bold">(Arsa + İnşaat + Yumuşak Maliyetler)</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-calc h-100 border-start border-warning border-4">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TAHMİNİ BRÜT KAR</small>
                <div class="fw-extrabold text-dark font-monospace" id="prev_profit" style="font-size: 1.5rem;">₺0</div>
                <small class="text-warning fw-bold">(Toplam Gelir - Toplam Maliyet)</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-calc h-100 border-start border-primary border-4">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TAHMİNİ ROI (YATIRIM GETİRİSİ)</small>
                <div class="fw-extrabold text-primary font-monospace" id="prev_roi" style="font-size: 1.5rem;">%0,0</div>
                <small class="text-primary fw-bold">(Brüt Kar / Toplam Maliyet)</small>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-feas-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-edit-circle-line text-warning fs-5"></i>Fizibilite & Sermaye Parametreleri Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="{{ route('feasibility.store') }}">
                @csrf

                <div class="row g-4">
                    <!-- SECTION 1: GENEL & ALAN -->
                    <div class="col-12">
                        <h6 class="fw-extrabold text-dark border-bottom pb-2 mb-3">
                            <i class="ri-building-2-line text-warning me-1"></i>1. Genel Bilgiler & Metrajlar
                        </h6>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Çalışma / Senaryo Adı <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control custom-input-feas" placeholder="Örn: Vadi Konutları Fizibilite Senaryosu A" required value="{{ old('name') }}" />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">İlişkili CRM Fırsatı</label>
                        <select name="opportunity_id" class="form-select custom-input-feas">
                            <option value="">İlişkili Fırsat Seçin (Opsiyonel)...</option>
                            @foreach($opportunities as $o)
                                <option value="{{ $o->id }}" {{ (!empty($prefillOpportunityId) && $prefillOpportunityId == $o->id) ? 'selected' : '' }}>
                                    💡 {{ $o->title }} ({{ $o->customer ?? 'Müşteri Yok' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Proje Tipi</label>
                        <select name="project_type" class="form-select custom-input-feas">
                            <option value="Konut">Konut Projesi</option>
                            <option value="Ticari / Ofis">Ticari / Ofis Projesi</option>
                            <option value="Karma Kullanım">Karma Kullanım (Konut + Avm)</option>
                            <option value="Endüstriyel / Tesis">Endüstriyel Tesis / Depo</option>
                            <option value="Turizm / Otel">Turizm / Otel</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Toplam İnşaat Alanı (m²)</label>
                        <input type="number" step="0.01" id="total_area" name="total_area" class="form-control custom-input-feas" placeholder="Örn: 15000" value="{{ old('total_area', 10000) }}" oninput="calcFeas()" />
                    </div>

                    <!-- SECTION 2: GELİR & MALİYET -->
                    <div class="col-12 mt-4">
                        <h6 class="fw-extrabold text-dark border-bottom pb-2 mb-3">
                            <i class="ri-money-dollar-circle-line text-success me-1"></i>2. Gelir & Fiyatlandırma Parametreleri
                        </h6>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Birim Satış Fiyatı (₺/m²)</label>
                        <input type="number" step="0.01" id="unit_sale_price" name="unit_sale_price" class="form-control custom-input-feas" placeholder="Örn: 45000" value="{{ old('unit_sale_price', 35000) }}" oninput="calcFeas()" />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Diğer Gelirler (Ticari/Otopark vs.) (₺)</label>
                        <input type="number" step="0.01" id="other_revenue" name="other_revenue" class="form-control custom-input-feas" placeholder="0.00" value="{{ old('other_revenue', 0) }}" oninput="calcFeas()" />
                    </div>

                    <div class="col-12 mt-4">
                        <h6 class="fw-extrabold text-dark border-bottom pb-2 mb-3">
                            <i class="ri-calculator-line text-danger me-1"></i>3. Arsa & İnşaat Maliyetleri
                        </h6>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">Arsa Maliyeti (₺)</label>
                        <input type="number" step="0.01" id="land_cost" name="land_cost" class="form-control custom-input-feas" placeholder="Örn: 50000000" value="{{ old('land_cost', 40000000) }}" oninput="calcFeas()" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">Birim İnşaat Maliyeti (₺/m²)</label>
                        <input type="number" step="0.01" id="construction_cost_per_m2" name="construction_cost_per_m2" class="form-control custom-input-feas" placeholder="Örn: 18000" value="{{ old('construction_cost_per_m2', 15000) }}" oninput="calcFeas()" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">Yumuşak Maliyetler (%) (Mimari, İzin vs.)</label>
                        <input type="number" step="0.01" id="soft_costs_percent" name="soft_costs_percent" class="form-control custom-input-feas" value="{{ old('soft_costs_percent', 10) }}" oninput="calcFeas()" />
                    </div>

                    <!-- SECTION 3: ZAMAN & FİNANSMAN -->
                    <div class="col-12 mt-4">
                        <h6 class="fw-extrabold text-dark border-bottom pb-2 mb-3">
                            <i class="ri-time-line text-info me-1"></i>4. Takvim & Finansman Yapısı
                        </h6>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">İnşaat Süresi (Ay)</label>
                        <input type="number" name="construction_months" class="form-control custom-input-feas" value="{{ old('construction_months', 18) }}" />
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">Satış Süresi (Ay)</label>
                        <input type="number" name="sales_months" class="form-control custom-input-feas" value="{{ old('sales_months', 12) }}" />
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">Özkaynak (₺)</label>
                        <input type="number" step="0.01" name="equity" class="form-control custom-input-feas" placeholder="0.00" value="{{ old('equity') }}" />
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">Kredi / Banka Finansmanı (₺)</label>
                        <input type="number" step="0.01" name="debt" class="form-control custom-input-feas" placeholder="0.00" value="{{ old('debt') }}" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">Yıllık Faiz Oranı (%)</label>
                        <input type="number" step="0.01" name="interest_rate_percent" class="form-control custom-input-feas" value="{{ old('interest_rate_percent', 12) }}" />
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('feasibility.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-pulse-line me-1"></i>Simüle Et & Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
function calcFeas() {
    const area = parseFloat(document.getElementById('total_area').value) || 0;
    const salePrice = parseFloat(document.getElementById('unit_sale_price').value) || 0;
    const otherRev = parseFloat(document.getElementById('other_revenue').value) || 0;
    const land = parseFloat(document.getElementById('land_cost').value) || 0;
    const constrPrice = parseFloat(document.getElementById('construction_cost_per_m2').value) || 0;
    const softPct = parseFloat(document.getElementById('soft_costs_percent').value) || 0;

    const totalRevenue = (area * salePrice) + otherRev;
    const constrCost = area * constrPrice;
    const softCost = constrCost * (softPct / 100);
    const totalCost = land + constrCost + softCost;

    const grossProfit = totalRevenue - totalCost;
    const roi = totalCost > 0 ? ((grossProfit / totalCost) * 100) : 0;

    const fmt = new Intl.NumberFormat('tr-TR', { maximumFractionDigits: 0 });

    document.getElementById('prev_revenue').textContent = '₺' + fmt.format(totalRevenue);
    document.getElementById('prev_cost').textContent = '₺' + fmt.format(totalCost);
    document.getElementById('prev_profit').textContent = '₺' + fmt.format(grossProfit);
    document.getElementById('prev_roi').textContent = '%' + roi.toFixed(1);
}

document.addEventListener('DOMContentLoaded', function() {
    calcFeas();
});
</script>
@endpush
@endsection
