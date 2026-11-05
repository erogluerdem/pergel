@extends('layouts.master')
@section('title', 'Yeni Tedarikçi')
@section('topbar-title', 'Finans - Tedarikçi Hesapları')

@section('content')
<div class="row g-4">
    <div class="col-xl-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="mb-3">
                    <i class="fas fa-user-plus text-success me-2"></i>
                    Yeni Tedarikçi Ekle
                </h4>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <h6 class="mb-2">Lütfen formu kontrol edin:</h6>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('finance.accounts.suppliers.store') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Firma Adı *</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Vergi Numarası</label>
                            <input type="text" name="tax_number" value="{{ old('tax_number') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Uzmanlık Alanı</label>
                            <input type="text" name="specialty" value="{{ old('specialty') }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Durum</label>
                            <select name="status" class="form-select">
                                <option value="">Seçiniz</option>
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Performans (0-5)</label>
                            <input type="number" name="performance_score" value="{{ old('performance_score') }}" min="0" max="5" step="0.1" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">İletişim Kişisi</label>
                            <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Telefon</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">E-posta</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Adres</label>
                            <textarea name="address" rows="3" class="form-control">{{ old('address') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Şehir</label>
                            <input type="text" name="city" value="{{ old('city') }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ülke</label>
                            <input type="text" name="country" value="{{ old('country') }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IBAN</label>
                            <input type="text" name="iban" value="{{ old('iban') }}" class="form-control" placeholder="TR">
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-4">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-save me-1"></i> Kaydet
                        </button>
                        <a href="{{ route('finance.accounts.suppliers') }}" class="btn btn-outline-secondary">
                            İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h5 class="text-muted mb-3">Hızlı Notlar</h5>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><i class="fas fa-info-circle text-success me-2"></i>Firma adı zorunlu ve benzersiz olmalıdır.</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-success me-2"></i>Durum seçimi onay sürecini izlemeyi kolaylaştırır.</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-success me-2"></i>Performans skoru 0-5 arasında olmalıdır.</li>
                    <li><i class="fas fa-lock text-success me-2"></i>Kayıt sonrası bilgiler Cari Hesaplar > Tedarikçi Hesapları listesinde görüntülenir.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
