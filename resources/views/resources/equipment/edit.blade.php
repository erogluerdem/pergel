@extends('layouts.master')

@section('title', 'İş Makinesi Düzenle')

@section('topbar-title', 'Ekipman - Düzenle')

@push('styles')
<style>
    .equipment-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #f59e0b 100%);
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
                <a href="{{ route('resources.equipment.show', $equipment->id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Künye Detayı
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">İş Makinesi Bilgilerini Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">Operatör ataması, çalışma saati, yakıt seviyesi ve durum güncellemesi.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="equipment-form-card">
            <form action="{{ route('resources.equipment.update', $equipment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-bold small text-dark">Makine / Ekipman Tanımı <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $equipment->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold small text-dark">Durum <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Çalışıyor" {{ $equipment->status === 'Çalışıyor' ? 'selected' : '' }}>Çalışıyor</option>
                            <option value="Boşta" {{ $equipment->status === 'Boşta' ? 'selected' : '' }}>Boşta</option>
                            <option value="Bakımda" {{ $equipment->status === 'Bakımda' ? 'selected' : '' }}>Bakımda</option>
                            <option value="Arızalı" {{ $equipment->status === 'Arızalı' ? 'selected' : '' }}>Arızalı</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="operator" class="form-label fw-bold small text-dark">Atanan Operatör</label>
                        <input type="text" name="operator" id="operator" class="form-control" value="{{ old('operator', $equipment->operator) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="working_hours" class="form-label fw-bold small text-dark">Çalışma Saati (ÇS)</label>
                        <input type="number" name="working_hours" id="working_hours" class="form-control fw-bold" value="{{ old('working_hours', $equipment->working_hours) }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label fw-bold small text-dark">Makine Künye Notları</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $equipment->notes) }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('resources.equipment.show', $equipment->id) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
