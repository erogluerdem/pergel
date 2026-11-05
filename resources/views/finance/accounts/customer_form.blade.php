@extends('layouts.master')

@section('title', isset($customer->id) ? ('Müşteri Düzenle — ' . $customer->name) : 'Yeni Müşteri Ekle')

@push('styles')
<style>
    .cf-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .cf-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: cfGlow 4s linear infinite;
    }

    @keyframes cfGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .cf-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-cf-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-cf {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-cf:focus {
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
    <div class="cf-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill cf-badge-pill">
                        <i class="ri-user-star-fill text-warning fs-5"></i> CRM & FİNANS
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        {{ $mode=='create' ? 'Yeni Müşteri Kaydı' : 'Müşteri Düzenleme' }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">
                    {{ $mode=='create' ? 'Yeni Müşteri Kaydı Oluştur' : ('Müşteri Düzenle — ' . $customer->name) }}
                </h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Müşteri kurumsal bilgilerini, iletişim detaylarını, adres ve finansal risk limit ayarlarını güncelleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    @if($mode=='edit')
                        <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                            <i class="ri-eye-line me-1"></i>360° Müşteri Profili
                        </a>
                    @endif
                    <a href="{{ route('finance.accounts.customers') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Müşteriler Listesi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-cf-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-edit-box-fill text-warning fs-5"></i>Müşteri Bilgi Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="{{ $mode=='create' ? route('finance.accounts.customers.store') : route('finance.accounts.customers.update', ['id'=>$customer->id]) }}">
                @csrf
                @if($mode=='edit')
                    @method('PUT')
                @endif

                <div class="row g-4">
                    <!-- SECTION 1: BASIC CORPORATE INFO -->
                    <div class="col-12">
                        <div class="p-3 rounded-3 border mb-2" style="background: #f8fafc; border-left: 4px solid #0f172a !important;">
                            <h6 class="fw-extrabold text-dark mb-0"><i class="ri-building-line text-primary me-1"></i>1. Kurumsal & Temel Kimlik Bilgileri</h6>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Müşteri Unvanı / Adı Soyadı <span class="text-danger">*</span></label>
                        <input name="name" class="form-control custom-input-cf" value="{{ old('name', $customer->name) }}" placeholder="Örn: Özçelik Gayrimenkul A.Ş." required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">Vergi No / TCKN</label>
                        <input name="tax_number" class="form-control custom-input-cf font-monospace" value="{{ old('tax_number', $customer->tax_number) }}" placeholder="Örn: 9876543210">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">Vergi Dairesi</label>
                        <input name="tax_office" class="form-control custom-input-cf" value="{{ old('tax_office', $customer->tax_office) }}" placeholder="Örn: Zincirlikuyu VD">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">MERSİS / Şirket Sicil No</label>
                        <input name="mersis" class="form-control custom-input-cf font-monospace" value="{{ old('mersis', $customer->mersis) }}" placeholder="Örn: 012345678900001">
                    </div>

                    <!-- SECTION 2: CONTACT INFO -->
                    <div class="col-12 mt-4">
                        <div class="p-3 rounded-3 border mb-2" style="background: #f8fafc; border-left: 4px solid #2563eb !important;">
                            <h6 class="fw-extrabold text-dark mb-0"><i class="ri-user-follow-line text-primary me-1"></i>2. İletişim & Yetkili Kişi Bilgileri</h6>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">İletişim Kişisi (Yetkili)</label>
                        <input name="contact_person" class="form-control custom-input-cf" value="{{ old('contact_person', $customer->contact_person) }}" placeholder="Örn: Ahmet Yılmaz">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">Telefon Numarası</label>
                        <input name="phone" class="form-control custom-input-cf font-monospace" value="{{ old('phone', $customer->phone) }}" placeholder="Örn: +90 532 000 00 00">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">E-Posta Adresi</label>
                        <input name="email" type="email" class="form-control custom-input-cf font-monospace" value="{{ old('email', $customer->email) }}" placeholder="ahmet@firma.com">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">TC Kimlik No (Bireysel)</label>
                        <input name="tc_id" class="form-control custom-input-cf font-monospace" value="{{ old('tc_id', $customer->tc_id) }}" placeholder="11 Haneli TCKN">
                    </div>

                    <!-- SECTION 3: ADDRESS INFO -->
                    <div class="col-12 mt-4">
                        <div class="p-3 rounded-3 border mb-2" style="background: #f8fafc; border-left: 4px solid #16a34a !important;">
                            <h6 class="fw-extrabold text-dark mb-0"><i class="ri-map-pin-line text-success me-1"></i>3. Adres & Durum Bilgileri</h6>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label-custom">Açık Adres</label>
                        <input name="address" class="form-control custom-input-cf" value="{{ old('address', $customer->address) }}" placeholder="Mahalle, Cadde, Sokak, No...">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">İl / Şehir</label>
                        <input name="city" class="form-control custom-input-cf" value="{{ old('city', $customer->city) }}" placeholder="Örn: İstanbul">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">İlçe</label>
                        <input name="district" class="form-control custom-input-cf" value="{{ old('district', $customer->district) }}" placeholder="Örn: Beşiktaş">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">Posta Kodu</label>
                        <input name="postcode" class="form-control custom-input-cf font-monospace" value="{{ old('postcode', $customer->postcode) }}" placeholder="Örn: 34340">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">Müşteri Durumu</label>
                        <select name="status" class="form-select custom-input-cf">
                            <option value="active" {{ old('status', $customer->status)=='active' ? 'selected' : '' }}>🟢 Aktif Müşteri</option>
                            <option value="inactive" {{ old('status', $customer->status)=='inactive' ? 'selected' : '' }}>🔴 Pasif Müşteri</option>
                        </select>
                    </div>

                    <!-- SECTION 4: BANK & FINANCIAL LIMITS -->
                    <div class="col-12 mt-4">
                        <div class="p-3 rounded-3 border mb-2" style="background: #f8fafc; border-left: 4px solid #d97706 !important;">
                            <h6 class="fw-extrabold text-dark mb-0"><i class="ri-bank-card-line text-warning me-1"></i>4. Banka & Finansal Risk Limitleri</h6>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">Banka Adı</label>
                        <input name="bank" class="form-control custom-input-cf" placeholder="Örn: Garanti BBVA" value="{{ old('bank', $customer->bank) }}">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label-custom">IBAN Numarası</label>
                        <input name="iban" class="form-control custom-input-cf font-monospace" placeholder="TR00 0000 0000 0000 0000 0000 00" value="{{ old('iban', $customer->iban) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Kredi Limiti (₺)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input name="credit_limit" type="number" step="0.01" class="form-control custom-input-cf" value="{{ old('credit_limit', $customer->credit_limit) }}" placeholder="Örn: 500000">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Risk Skoru (0 - 100)</label>
                        <input name="risk_score" type="number" min="0" max="100" class="form-control custom-input-cf font-monospace" value="{{ old('risk_score', $customer->risk_score) }}" placeholder="Örn: 25">
                    </div>
                </div>

                <!-- SUBMIT & DELETE BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                    <a href="{{ route('finance.accounts.customers') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <div class="d-flex align-items-center gap-2">
                        @if($mode=='edit')
                            <button form="deleteForm" type="submit" class="btn btn-outline-danger rounded-pill px-4 py-2.5 fw-extrabold" onclick="return confirmDelete(this, 'Müşteri Kaydını Sil?', 'Bu müşteri kaydı geri alınamayacak şekilde silinecektir.')">
                                <i class="ri-delete-bin-line me-1"></i>Müşteriyi Sil
                            </button>
                        @endif
                        <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                            <i class="ri-save-fill me-1"></i>{{ $mode=='create' ? 'Müşteriyi Kaydet & Oluştur' : 'Değişiklikleri Kaydet & Güncelle' }}
                        </button>
                    </div>
                </div>
            </form>

            @if($mode=='edit')
                <form id="deleteForm" method="POST" action="{{ route('finance.accounts.customers.destroy', ['id'=>$customer->id]) }}">
                    @csrf
                    @method('DELETE')
                </form>
            @endif

        </div>
    </div>

</div>
@endsection
