@extends('layouts.master')

@section('title', 'Yeni İş Makinesi Kaydı')

@section('topbar-title', 'Ekipman - Yeni Kayıt')

@push('styles')
<style>
    .equipment-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #ea580c 50%, #c2410c 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .equipment-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .equipment-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="equipment-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('resources.equipment.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Ekipman Parkı
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Yeni İş Makinesi Kaydı Ekle</h1>
            <p class="text-white-50 mb-0 fs-6">Ağır iş makinesi, kule vinç, loder veya nakliye kamyonu envanter formu.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="equipment-form-card">
            <form action="{{ route('resources.equipment.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-bold small text-dark">Makine / Ekipman Tanımı <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Örn: CAT 336 Paletli Ekskavatör" required>
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label fw-bold small text-dark">Ekipman Tipi <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="Ağır İş Makinesi">Ağır İş Makinesi</option>
                            <option value="Yükleyici & Loder">Yükleyici & Loder</option>
                            <option value="Kule Vinç & Kaldırma">Kule Vinç & Kaldırma</option>
                            <option value="Hafriyat Kamyonu">Hafriyat Kamyonu</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="plate_number" class="form-label fw-bold small text-dark">Plaka No</label>
                        <input type="text" name="plate_number" id="plate_number" class="form-control font-monospace fw-bold" placeholder="Örn: 34 EKS 884">
                    </div>

                    <div class="col-md-4">
                        <label for="serial_number" class="form-label fw-bold small text-dark">Seri Şasi No</label>
                        <input type="text" name="serial_number" id="serial_number" class="form-control font-monospace" placeholder="Örn: CAT-336-99412">
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label fw-bold small text-dark">Durum <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Çalışıyor">Çalışıyor</option>
                            <option value="Boşta">Boşta</option>
                            <option value="Bakımda">Bakımda</option>
                            <option value="Arızalı">Arızalı</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="brand" class="form-label fw-bold small text-dark">Marka</label>
                        <input type="text" name="brand" id="brand" class="form-control" placeholder="Caterpillar, Volvo, Liebherr">
                    </div>

                    <div class="col-md-4">
                        <label for="model" class="form-label fw-bold small text-dark">Model</label>
                        <input type="text" name="model" id="model" class="form-control" placeholder="336 Next Gen">
                    </div>

                    <div class="col-md-4">
                        <label for="ownership" class="form-label fw-bold small text-dark">Mülkiyet Durumu <span class="text-danger">*</span></label>
                        <select name="ownership" id="ownership" class="form-select" required>
                            <option value="Sahip">Özmal (Sahip)</option>
                            <option value="Kiralık">Kiralık</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="operator" class="form-label fw-bold small text-dark">Atanan Operatör</label>
                        <input type="text" name="operator" id="operator" class="form-control" placeholder="Operatör Ad Soyad">
                    </div>

                    <div class="col-md-6">
                        <label for="working_hours" class="form-label fw-bold small text-dark">Çalışma Saati (ÇS)</label>
                        <input type="number" name="working_hours" id="working_hours" class="form-control fw-bold" placeholder="0">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label fw-bold small text-dark">Makine Künye Notları & Şantiye Görev Tanımı</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Saha kullanım amacı ve periyodik kontrol bilgileri..."></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('resources.equipment.index') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm" style="background:#ea580c; color:#fff !important;">
                        <i class="ri-save-line me-1"></i>Ekipmanı Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
