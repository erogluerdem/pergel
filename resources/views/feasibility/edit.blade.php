@extends('layouts.master')

@section('title', 'Fizibilite Düzenle - ' . $s->name)

@push('styles')
<style>
    .feas-edit-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .feas-edit-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: feasEditGlow 4s linear infinite;
    }

    @keyframes feasEditGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .feas-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
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

    .live-calc-box {
        background: #f8fafc !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 14px !important;
        padding: 18px !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="feas-edit-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill feas-badge-pill">
                        <i class="ri-calculator-fill text-warning fs-5"></i> FİZİBİLİTE & SERMAYE DÜZENLEME
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Senaryo #{{ $s->id }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">{{ $s->name }} — Düzenle</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Finansal bütçe parametrelerini ve metraj varsayımlarını güncelleyerek anlık getiri simülasyonunu yeniden hesaplayın.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('feasibility.show', $s->id) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold">
                        <i class="ri-eye-line me-1"></i>Detaya Dön
                    </a>
                    <a href="{{ route('feasibility.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Listeye Dön
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- LIVE SIMULATION PREVIEW CARDS -->
    <div class="live-calc-box mb-4">
        <h6 class="fw-extrabold text-dark mb-3 d-flex align-items-center gap-2">
            <i class="ri-pulse-line text-warning fs-5"></i>Anlık Finansal Projeksiyon Önizlemesi
        </h6>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="p-3 bg-white rounded-3 border" style="border-color: #cbd5e1 !important;">
                    <small class="text-muted fw-bold d-block mb-1 text-uppercase">TAHMİNİ TOPLAM GELİR</small>
                    <div class="fw-extrabold text-success font-monospace fs-5" id="prev_total_revenue">₺0</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-white rounded-3 border" style="border-color: #cbd5e1 !important;">
                    <small class="text-muted fw-bold d-block mb-1 text-uppercase">TAHMİNİ TOPLAM MALİYET</small>
                    <div class="fw-extrabold text-danger font-monospace fs-5" id="prev_total_cost">₺0</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-white rounded-3 border" style="border-color: #cbd5e1 !important;">
                    <small class="text-muted fw-bold d-block mb-1 text-uppercase">TAHMİNİ NET KAR</small>
                    <div class="fw-extrabold text-dark font-monospace fs-5" id="prev_net_profit">₺0</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-white rounded-3 border" style="border-color: #cbd5e1 !important;">
                    <small class="text-muted fw-bold d-block mb-1 text-uppercase">TAHMİNİ ROI %</small>
                    <div class="fw-extrabold text-warning font-monospace fs-5" id="prev_roi">%0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-feas-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-edit-2-line text-warning fs-5"></i>Fizibilite Parameter Güncelleme Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('feasibility.update', $s->id) }}" method="POST" id="feasEditForm">
                @csrf
                @method('PUT')

                <!-- SECTION 1: GENERAL -->
                <h6 class="fw-extrabold text-primary border-bottom pb-2 mb-3">1. Genel Bilgiler & Fırsat Bağlantısı</h6>
                <div class="row g-4 mb-4">
                    <div class="col-md-5">
                        <label for="name" class="form-label-custom">Senaryo Adı <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control custom-input-feas @error('name') is-invalid @enderror" value="{{ old('name', $s->name) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="opportunity_id" class="form-label-custom">İlişkili CRM Fırsatı</label>
                        <select name="opportunity_id" id="opportunity_id" class="form-select custom-input-feas">
                            <option value="">-- Bağımsız Senaryo --</option>
                            @foreach($opportunities as $opp)
                                <option value="{{ $opp->id }}" {{ old('opportunity_id', $s->opportunity_id) == $opp->id ? 'selected' : '' }}>
                                    🎯 {{ $opp->title }} ({{ $opp->customer ?? 'Müşteri Yok' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="project_type" class="form-label-custom">Proje Tipi</label>
                        <input type="text" name="project_type" id="project_type" class="form-control custom-input-feas" placeholder="Örn: Konut, Rezidans, Karma" value="{{ old('project_type', $s->project_type) }}">
                    </div>
                </div>

                <!-- SECTION 2: METRAJ & REVENUE -->
                <h6 class="fw-extrabold text-primary border-bottom pb-2 mb-3">2. Metraj & Gelir Parametreleri</h6>
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label for="total_area" class="form-label-custom">Toplam İnşaat Alanı (m²) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="total_area" id="total_area" class="form-control custom-input-feas calc-trigger" value="{{ old('total_area', $s->total_area) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="unit_sale_price" class="form-label-custom">Birim Satış Fiyatı (TL / m²) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="unit_sale_price" id="unit_sale_price" class="form-control custom-input-feas calc-trigger" value="{{ old('unit_sale_price', $s->unit_sale_price) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="other_revenue" class="form-label-custom">Diğer Gelirler (TL)</label>
                        <input type="number" step="0.01" name="other_revenue" id="other_revenue" class="form-control custom-input-feas calc-trigger" value="{{ old('other_revenue', $s->other_revenue) }}">
                    </div>
                </div>

                <!-- SECTION 3: COSTS -->
                <h6 class="fw-extrabold text-primary border-bottom pb-2 mb-3">3. İnşaat & Yumuşak Maliyetler</h6>
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label for="land_cost" class="form-label-custom">Arsa Maliyeti (TL) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="land_cost" id="land_cost" class="form-control custom-input-feas calc-trigger" value="{{ old('land_cost', $s->land_cost) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="construction_cost_per_m2" class="form-label-custom">Birim İnşaat Maliyeti (TL / m²) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="construction_cost_per_m2" id="construction_cost_per_m2" class="form-control custom-input-feas calc-trigger" value="{{ old('construction_cost_per_m2', $s->construction_cost_per_m2) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="soft_costs_percent" class="form-label-custom">Yumuşak Maliyetler (%)</label>
                        <input type="number" step="0.01" name="soft_costs_percent" id="soft_costs_percent" class="form-control custom-input-feas calc-trigger" value="{{ old('soft_costs_percent', $s->soft_costs_percent ?? 10) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="construction_months" class="form-label-custom">İnşaat Süresi (Ay)</label>
                        <input type="number" name="construction_months" id="construction_months" class="form-control custom-input-feas" value="{{ old('construction_months', $s->construction_months ?? 12) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="sales_months" class="form-label-custom">Satış Süresi (Ay)</label>
                        <input type="number" name="sales_months" id="sales_months" class="form-control custom-input-feas" value="{{ old('sales_months', $s->sales_months ?? 12) }}">
                    </div>
                </div>

                <!-- SECTION 4: FINANCING -->
                <h6 class="fw-extrabold text-primary border-bottom pb-2 mb-3">4. Finansman & Özkaynak Yapısı</h6>
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label for="equity" class="form-label-custom">Özkaynak Tutarı (TL)</label>
                        <input type="number" step="0.01" name="equity" id="equity" class="form-control custom-input-feas" value="{{ old('equity', $s->equity) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="debt" class="form-label-custom">Kredi Tutarı (TL)</label>
                        <input type="number" step="0.01" name="debt" id="debt" class="form-control custom-input-feas" value="{{ old('debt', $s->debt) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="interest_rate_percent" class="form-label-custom">Yıllık Kredi Faiz Oranı (%)</label>
                        <input type="number" step="0.01" name="interest_rate_percent" id="interest_rate_percent" class="form-control custom-input-feas" value="{{ old('interest_rate_percent', $s->interest_rate_percent ?? 12) }}">
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('feasibility.show', $s->id) }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i>Senaryoyu Güncelle & Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    function recalcPreview() {
        const area = parseFloat(document.getElementById('total_area').value) || 0;
        const unitSale = parseFloat(document.getElementById('unit_sale_price').value) || 0;
        const otherRev = parseFloat(document.getElementById('other_revenue').value) || 0;

        const landCost = parseFloat(document.getElementById('land_cost').value) || 0;
        const constrPerM2 = parseFloat(document.getElementById('construction_cost_per_m2').value) || 0;
        const softPct = parseFloat(document.getElementById('soft_costs_percent').value) || 0;

        const totalRevenue = (area * unitSale) + otherRev;
        const constrCost = area * constrPerM2;
        const softCosts = (landCost + constrCost) * (softPct / 100);
        const totalCost = landCost + constrCost + softCosts;

        const netProfit = totalRevenue - totalCost;
        const roi = totalCost > 0 ? ((netProfit / totalCost) * 100).toFixed(1) : 0;

        document.getElementById('prev_total_revenue').innerText = '₺' + new Intl.NumberFormat('tr-TR').format(totalRevenue.toFixed(0));
        document.getElementById('prev_total_cost').innerText = '₺' + new Intl.NumberFormat('tr-TR').format(totalCost.toFixed(0));
        document.getElementById('prev_net_profit').innerText = '₺' + new Intl.NumberFormat('tr-TR').format(netProfit.toFixed(0));
        document.getElementById('prev_roi').innerText = '%' + roi;
    }

    document.querySelectorAll('.calc-trigger').forEach(el => {
        el.addEventListener('input', recalcPreview);
    });

    recalcPreview();
});
</script>
@endpush
