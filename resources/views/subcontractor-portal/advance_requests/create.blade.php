@extends('subcontractor-portal.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Yeni Avans Talebi</h2>
    <a href="{{ route('subcontractor.advance_requests.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i>Geri Dön
    </a>
</div>

<div class="card shadow-sm border-0" style="max-width:600px;">
    <div class="card-body">
        <form action="{{ route('subcontractor.advance_requests.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Talep Edilen Tutar (TRY)</label>
                <div class="input-group">
                    <span class="input-group-text">₺</span>
                    <input type="number" step="0.01" class="form-control" name="amount" required placeholder="Örn: 50000.00">
                </div>
                <div class="form-text">Mevcut sözleşme limitleriniz dahilinde talepte bulununuz.</div>
            </div>

            <div class="mb-4">
                <label class="form-label">Gerekçe / Açıklama</label>
                <textarea class="form-control" name="reason" rows="4" required placeholder="Malzeme alımı, personel maaşı ödemesi vb."></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Talebi Merkeze İlet</button>
        </form>
    </div>
</div>
@endsection
