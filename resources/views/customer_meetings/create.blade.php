@extends('layouts.master')

@section('title', 'Yeni Müşteri Görüşmesi Ekle')

@push('styles')
<style>
    .cm-create-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .cm-create-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: cmCreateGlow 4s linear infinite;
    }

    @keyframes cmCreateGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .cm-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-cm-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-cm {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-cm:focus {
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
    <div class="cm-create-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill cm-badge-pill">
                        <i class="ri-phone-find-line text-warning fs-5"></i> CRM & GÖRÜŞME YÖNETİMİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Yeni Görüşme Kaydı
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Yeni Müşteri Görüşmesi Kaydet</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Görüşme detaylarını, potansiyel satış tutarını ve sonraki aksiyon planını kaydedin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('customer_meetings.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Görüşmeler Listesine Dön
                </a>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-cm-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-phone-fill text-warning fs-5"></i>Görüşme Kayıt Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('customer_meetings.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <!-- Customer Select -->
                    <div class="col-md-6">
                        <label for="customer_id" class="form-label-custom">Müşteri Seçimi <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-cm @error('customer_id') is-invalid @enderror" name="customer_id" id="customer_id" required>
                            <option value="">-- Müşteri Seçiniz --</option>
                            @foreach($customers as $cust)
                                <option value="{{ $cust->id }}" {{ old('customer_id', $customer->id ?? '') == $cust->id ? 'selected' : '' }}>
                                    👤 {{ $cust->name }} ({{ $cust->phone ?? 'Telefon Yok' }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Subject -->
                    <div class="col-md-6">
                        <label for="subject" class="form-label-custom">Görüşme Konusu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control custom-input-cm @error('subject') is-invalid @enderror" name="subject" id="subject" 
                               placeholder="Örn: Mavi Kule Penthouse Sunumu & Fiyat Görüşmesi" value="{{ old('subject') }}" required>
                        @error('subject')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Meeting Type -->
                    <div class="col-md-4">
                        <label for="meeting_type" class="form-label-custom">Görüşme Türü <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-cm @error('meeting_type') is-invalid @enderror" name="meeting_type" id="meeting_type" required>
                            <option value="visit" {{ old('meeting_type', 'visit') == 'visit' ? 'selected' : '' }}>👤 Yüz Yüze Ziyaret</option>
                            <option value="call" {{ old('meeting_type') == 'call' ? 'selected' : '' }}>☎️ Telefon Görüşmesi</option>
                            <option value="online" {{ old('meeting_type') == 'online' ? 'selected' : '' }}>💻 Online Toplantı</option>
                            <option value="email" {{ old('meeting_type') == 'email' ? 'selected' : '' }}>✉️ E-Posta İletişimi</option>
                        </select>
                    </div>

                    <!-- Meeting Date -->
                    <div class="col-md-4">
                        <label for="meeting_date" class="form-label-custom">Görüşme Tarihi & Saati <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control custom-input-cm @error('meeting_date') is-invalid @enderror" 
                               name="meeting_date" id="meeting_date" value="{{ old('meeting_date', date('Y-m-d\TH:i')) }}" required>
                    </div>

                    <!-- Location -->
                    <div class="col-md-4">
                        <label for="location" class="form-label-custom">Görüşme Yeri / Adresi</label>
                        <input type="text" class="form-control custom-input-cm" name="location" id="location" 
                               placeholder="Örn: Vadistanbul Satış Ofisi" value="{{ old('location') }}">
                    </div>

                    <!-- Outcome -->
                    <div class="col-md-6">
                        <label for="outcome" class="form-label-custom">Görüşme Sonucu <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-cm @error('outcome') is-invalid @enderror" name="outcome" id="outcome" required>
                            <option value="interested" {{ old('outcome', 'interested') == 'interested' ? 'selected' : '' }}>✓ Sıcak / İlgilendi</option>
                            <option value="converted_to_sale" {{ old('outcome') == 'converted_to_sale' ? 'selected' : '' }}>💰 Satışa Dönüştü</option>
                            <option value="pending" {{ old('outcome') == 'pending' ? 'selected' : '' }}>⏳ Beklemede</option>
                            <option value="not_interested" {{ old('outcome') == 'not_interested' ? 'selected' : '' }}>✗ İlgilenmedi</option>
                        </select>
                    </div>

                    <!-- Potential Amount -->
                    <div class="col-md-6">
                        <label for="potential_sale_amount" class="form-label-custom">Potansiyel Satış Tutarı (₺)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="number" class="form-control custom-input-cm" name="potential_sale_amount" id="potential_sale_amount" 
                                   min="0" step="0.01" placeholder="Örn: 12500000" value="{{ old('potential_sale_amount') }}">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label for="notes" class="form-label-custom">Görüşme Notları & Görüşülen Konular</label>
                        <textarea class="form-control custom-input-cm" name="notes" id="notes" rows="4" placeholder="Müşterinin talepleri, ilgilendiği üniteler, takas/özel ödeme beklentileri ve görüşme detayları...">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Followup Section -->
                    <div class="col-12">
                        <div class="p-4 rounded-3 border" style="background: #f8fafc; border-left: 4px solid #d97706 !important;">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label for="next_followup" class="form-label-custom">Sonraki Takip & Aksiyon Planı</label>
                                    <input type="text" class="form-control custom-input-cm" name="next_followup" id="next_followup" 
                                           placeholder="Örn: Örnek daire gezisi organize edilecek ve revize teklif sunulacak." value="{{ old('next_followup') }}">
                                </div>

                                <div class="col-md-4">
                                    <label for="next_followup_date" class="form-label-custom">Hedef Takip Tarihi</label>
                                    <input type="date" class="form-control custom-input-cm" name="next_followup_date" id="next_followup_date" value="{{ old('next_followup_date') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('customer_meetings.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i>Görüşmeyi Kaydet & Yayınla
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
