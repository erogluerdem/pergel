@extends('layouts.master')

@section('title', 'Tedarikçi Düzenle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Tedarikçi Düzenle</h5>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('finance.accounts.suppliers.update', $vendor->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Firma Adı</label>
                <input name="name" value="{{ old('name', $vendor->name) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Vergi No</label>
                <input name="tax_number" value="{{ old('tax_number', $vendor->tax_number) }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Uzmanlık</label>
                <input name="specialty" value="{{ old('specialty', $vendor->specialty) }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Durum</label>
                <select name="status" class="form-select">
                    <option value="">Seçiniz</option>
                    <option value="approved" {{ old('status', $vendor->status) == 'approved' ? 'selected' : '' }}>Onaylı</option>
                    <option value="pending" {{ old('status', $vendor->status) == 'pending' ? 'selected' : '' }}>Onay Bekliyor</option>
                    <option value="conditional" {{ old('status', $vendor->status) == 'conditional' ? 'selected' : '' }}>Şartlı</option>
                    <option value="blacklisted" {{ old('status', $vendor->status) == 'blacklisted' ? 'selected' : '' }}>Kara Liste</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Performans (0-5)</label>
                <input name="performance_score" type="number" step="0.1" min="0" max="5" value="{{ old('performance_score', $vendor->performance_score) }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">İletişim Kişisi</label>
                <input name="contact_person" value="{{ old('contact_person', $vendor->contact_person) }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Telefon</label>
                <input name="phone" value="{{ old('phone', $vendor->phone) }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input name="email" type="email" value="{{ old('email', $vendor->email) }}" class="form-control">
            </div>

            <button class="btn btn-primary">Güncelle</button>
            <a href="{{ route('finance.accounts.suppliers.show', $vendor->id) }}" class="btn btn-outline-secondary ms-2">İptal</a>
        </form>
    </div>
</div>
@endsection
