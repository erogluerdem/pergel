@extends('layouts.master')

@section('title', 'Fırsatı Düzenle - ' . $opp->title)

@push('styles')
<style>
    .edit-opp-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .edit-opp-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: editGlow 4s linear infinite;
    }

    @keyframes editGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .edit-opp-badge {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-opp-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-opp {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-opp:focus {
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
    <div class="edit-opp-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill edit-opp-badge">
                        <i class="ri-pencil-fill text-warning fs-5"></i> CRM & POTANSİYEL PROJE DÜZENLEME
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Fırsat #{{ $opp->id }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">{{ $opp->title }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Potansiyel ihale bütçesini, KDV hesaplamasını, kazanma olasılığını ve sorumlu yöneticiyi güncelleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('opportunities.show', $opp->id) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold">
                        <i class="ri-eye-line me-1"></i>Detaya Dön
                    </a>
                    <a href="{{ route('opportunities.list') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Fırsat Listesi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-opp-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-edit-2-line text-warning fs-5"></i>Fırsat Düzenleme Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="{{ route('opportunities.update', $opp->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Title -->
                    <div class="col-md-6">
                        <label class="form-label-custom">Fırsat / Proje Adı <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control custom-input-opp" required value="{{ old('title', $opp->title) }}" />
                    </div>

                    <!-- Customer -->
                    <div class="col-md-6">
                        <label class="form-label-custom">Müşteri / Yatırımcı Firma</label>
                        <input type="text" name="customer" list="customersList" class="form-control custom-input-opp" placeholder="Firma adı veya müşteri temsilcisi..." value="{{ old('customer', $opp->customer) }}" />
                        <datalist id="customersList">
                            @foreach($customers as $c)
                                <option value="{{ $c }}">
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Estimated Value & KDV -->
                    <div class="col-md-4">
                        <label class="form-label-custom">Tahmini Potansiyel Değer (₺)</label>
                        <input type="number" step="0.01" id="estimated_value" name="estimated_value" class="form-control custom-input-opp" placeholder="0.00" value="{{ old('estimated_value', $opp->estimated_value) }}" oninput="calcKdv()" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">KDV Oranı (%)</label>
                        @php $currentKdv = old('kdv_percent', $opp->kdv_percent ?? 20); @endphp
                        <select name="kdv_percent" id="kdv_percent" class="form-select custom-input-opp" onchange="calcKdv()">
                            <option value="20" {{ $currentKdv == 20 ? 'selected' : '' }}>%20 KDV</option>
                            <option value="10" {{ $currentKdv == 10 ? 'selected' : '' }}>%10 KDV</option>
                            <option value="1" {{ $currentKdv == 1 ? 'selected' : '' }}>%1 KDV</option>
                            <option value="0" {{ $currentKdv == 0 ? 'selected' : '' }}>%0 (KDV Muaf)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">KDV Dahil Toplam Tutar (Hesaplanan)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="text" id="kdv_total_preview" class="form-control custom-input-opp text-success fw-extrabold" readonly placeholder="0,00 ₺">
                        </div>
                    </div>

                    <!-- Owner & Source -->
                    <div class="col-md-6">
                        <label class="form-label-custom">Sorumlu Yönetici (SQL)</label>
                        <select name="owner_id" class="form-select custom-input-opp">
                            <option value="">Sorumlu Yönetici Seçin...</option>
                            @foreach($owners as $o)
                                <option value="{{ $o->id }}" {{ old('owner_id', $opp->owner_id) == $o->id ? 'selected' : '' }}>
                                    👤 {{ $o->name }} ({{ $o->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Kayıt Kaynağı</label>
                        @php $src = old('source', $opp->source); @endphp
                        <select name="source" class="form-select custom-input-opp">
                            <option value="Referans" {{ $src == 'Referans' ? 'selected' : '' }}>Referans / Tavsiye</option>
                            <option value="Web" {{ $src == 'Web' ? 'selected' : '' }}>Web Sitesi / Dijital</option>
                            <option value="İhale İlanı" {{ $src == 'İhale İlanı' ? 'selected' : '' }}>Resmi İhale İlanı</option>
                            <option value="Doğrudan Görüşme" {{ $src == 'Doğrudan Görüşme' ? 'selected' : '' }}>Doğrudan Müşteri Görüşmesi</option>
                            <option value="Etkinlik" {{ $src == 'Etkinlik' ? 'selected' : '' }}>Fuar / Etkinlik</option>
                        </select>
                    </div>

                    <!-- Probability & Dates -->
                    <div class="col-md-4">
                        <label class="form-label-custom">Kazanma Olasılığı (%)</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="range" class="form-range" id="probRange" min="0" max="100" value="{{ old('probability', $opp->probability ?? 50) }}" oninput="document.getElementById('probInput').value = this.value">
                            <input type="number" name="probability" id="probInput" class="form-control custom-input-opp text-center font-monospace" min="0" max="100" value="{{ old('probability', $opp->probability ?? 50) }}" style="max-width: 80px;" oninput="document.getElementById('probRange').value = this.value">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">Tahmini Kapanış Tarihi</label>
                        <input type="date" name="expected_close_date" class="form-control custom-input-opp" value="{{ old('expected_close_date', $opp->expected_close_date) }}" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">Son İletişim Tarihi</label>
                        <input type="date" name="last_contacted_at" class="form-control custom-input-opp" value="{{ old('last_contacted_at', $opp->last_contacted_at) }}" />
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label class="form-label-custom">Fırsat Açıklaması & Notlar</label>
                        <textarea name="description" class="form-control custom-input-opp" rows="4" placeholder="Fırsat detayları, müşteri beklentileri ve sonraki aksiyon adımları...">{{ old('description', $opp->description) }}</textarea>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('opportunities.show', $opp->id) }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i>Değişiklikleri Kaydet & Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
function calcKdv() {
    const valInput = document.getElementById('estimated_value');
    const pctInput = document.getElementById('kdv_percent');
    const previewInput = document.getElementById('kdv_total_preview');

    if (!valInput || !previewInput) return;

    const val = parseFloat(valInput.value) || 0;
    const pct = parseFloat(pctInput ? pctInput.value : 20) || 0;

    const totalKdv = val * (1 + (pct / 100));

    previewInput.value = new Intl.NumberFormat('tr-TR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(totalKdv) + ' ₺';
}

document.addEventListener('DOMContentLoaded', function() {
    calcKdv();
});
</script>
@endpush
@endsection
