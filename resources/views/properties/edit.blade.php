@extends('layouts.master')

@section('title', 'Arsa Düzenle')
@section('topbar-title', 'Operasyon')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-map-pin" style="font-size: 1.5rem; color: #0d6efd;"></i>
                <h4 class="mb-0">Arsa Düzenle: {{ $property->property_code }}</h4>
            </div>
        </div>
    </div>

    <form action="{{ route('operations.properties.update', $property) }}" method="POST" class="needs-validation">
        @csrf
        @method('PUT')

        {{-- Temel Bilgiler --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Temel Bilgiler</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Proje *</label>
                        <select name="project_id" class="form-select @error('project_id') is-invalid @enderror" required>
                            <option value="">Proje Seçiniz</option>
                            @foreach ($projects ?? [] as $projectId => $projectName)
                                <option value="{{ $projectId }}" {{ old('project_id', $property->project_id) == $projectId ? 'selected' : '' }}>{{ $projectName }}</option>
                            @endforeach
                        </select>
                        @error('project_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Arsa Kodu</label>
                        <input type="text" class="form-control" value="{{ $property->property_code }}" disabled>
                        <small class="text-muted">Arsa kodu değiştirilemez</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Arsa Adı *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Arsa adı" value="{{ old('name', $property->name) }}" required>
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Açıklama</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Açıklama">{{ old('description', $property->description) }}</textarea>
                        @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Lokasyon Bilgileri --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-location-dot me-2"></i>Lokasyon Bilgileri</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Şehir *</label>
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" placeholder="İstanbul" value="{{ old('city', $property->city) }}" required>
                        @error('city') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">İlçe</label>
                        <input type="text" name="district" class="form-control @error('district') is-invalid @enderror" placeholder="Beşiktaş" value="{{ old('district', $property->district) }}">
                        @error('district') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Mahalle</label>
                        <input type="text" name="neighborhood" class="form-control @error('neighborhood') is-invalid @enderror" placeholder="Mahalle" value="{{ old('neighborhood', $property->neighborhood) }}">
                        @error('neighborhood') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">İmar Durumu</label>
                        <input type="text" name="zoning_status" class="form-control @error('zoning_status') is-invalid @enderror" placeholder="Konut, Ticari, vb" value="{{ old('zoning_status', $property->zoning_status) }}">
                        @error('zoning_status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Tam Adres *</label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Tam adres" value="{{ old('address', $property->address) }}" required>
                        @error('address') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Latitude</label>
                        <input type="number" name="latitude" step="0.000001" class="form-control @error('latitude') is-invalid @enderror" placeholder="Latitude" value="{{ old('latitude', $property->latitude) }}">
                        @error('latitude') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Longitude</label>
                        <input type="number" name="longitude" step="0.000001" class="form-control @error('longitude') is-invalid @enderror" placeholder="Longitude" value="{{ old('longitude', $property->longitude) }}">
                        @error('longitude') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Arsa Özellikleri --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-ruler me-2"></i>Arsa Özellikleri</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Alan (m²)</label>
                        <input type="number" name="area" step="0.01" class="form-control @error('area') is-invalid @enderror" placeholder="0.00" value="{{ old('area', $property->area) }}">
                        @error('area') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">İnşaat Alanı (m²)</label>
                        <input type="number" name="building_area" step="0.01" class="form-control @error('building_area') is-invalid @enderror" placeholder="0.00" value="{{ old('building_area', $property->building_area) }}">
                        @error('building_area') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">TAKS</label>
                        <input type="number" name="floor_ratio" step="0.01" class="form-control @error('floor_ratio') is-invalid @enderror" placeholder="0.00" value="{{ old('floor_ratio', $property->floor_ratio) }}">
                        @error('floor_ratio') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">KAKS</label>
                        <input type="number" name="coverage_ratio" step="0.01" class="form-control @error('coverage_ratio') is-invalid @enderror" placeholder="0.00" value="{{ old('coverage_ratio', $property->coverage_ratio) }}">
                        @error('coverage_ratio') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Tapu & Yasal Bilgiler --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Tapu & Yasal Bilgiler</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tapu No</label>
                        <input type="text" name="deed_number" class="form-control @error('deed_number') is-invalid @enderror" placeholder="Tapu No" value="{{ old('deed_number', $property->deed_number) }}">
                        @error('deed_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Blok-Mahalle No</label>
                        <input type="text" name="block_number" class="form-control @error('block_number') is-invalid @enderror" placeholder="Blok No" value="{{ old('block_number', $property->block_number) }}">
                        @error('block_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Parsel No</label>
                        <input type="text" name="parcel_number" class="form-control @error('parcel_number') is-invalid @enderror" placeholder="Parsel No" value="{{ old('parcel_number', $property->parcel_number) }}">
                        @error('parcel_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tapu Tarihi</label>
                        <input type="date" name="deed_date" class="form-control @error('deed_date') is-invalid @enderror" value="{{ old('deed_date', $property->deed_date) }}">
                        @error('deed_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mülk Sahibi Adı</label>
                        <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror" placeholder="Mülk Sahibi Adı" value="{{ old('owner_name', $property->owner_name) }}">
                        @error('owner_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Yasal Durum *</label>
                        <select name="legal_status" class="form-select @error('legal_status') is-invalid @enderror" required>
                            <option value="clear" {{ old('legal_status', $property->legal_status) == 'clear' ? 'selected' : '' }}>Temiz</option>
                            <option value="disputed" {{ old('legal_status', $property->legal_status) == 'disputed' ? 'selected' : '' }}>Anlaşmazlık Var</option>
                            <option value="under_process" {{ old('legal_status', $property->legal_status) == 'under_process' ? 'selected' : '' }}>İşlem Altında</option>
                            <option value="restricted" {{ old('legal_status', $property->legal_status) == 'restricted' ? 'selected' : '' }}>Kısıtlı</option>
                        </select>
                        @error('legal_status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Tapu Açıklaması</label>
                        <textarea name="deed_description" class="form-control @error('deed_description') is-invalid @enderror" rows="2" placeholder="Tapu ile ilgili açıklama">{{ old('deed_description', $property->deed_description) }}</textarea>
                        @error('deed_description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Yasal Notlar</label>
                        <textarea name="legal_notes" class="form-control @error('legal_notes') is-invalid @enderror" rows="2" placeholder="Yasal durumu ile ilgili notlar">{{ old('legal_notes', $property->legal_notes) }}</textarea>
                        @error('legal_notes') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Değerlendirme & Risk --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Değerlendirme & Risk</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Arsa Değeri (₺)</label>
                        <input type="number" name="land_value" step="0.01" class="form-control @error('land_value') is-invalid @enderror" placeholder="0.00" value="{{ old('land_value', $property->land_value) }}">
                        @error('land_value') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Geliştirme Potansiyel Değeri (₺)</label>
                        <input type="number" name="development_potential_value" step="0.01" class="form-control @error('development_potential_value') is-invalid @enderror" placeholder="0.00" value="{{ old('development_potential_value', $property->development_potential_value) }}">
                        @error('development_potential_value') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Yatırım Risk Seviyesi *</label>
                        <select name="investment_risk_level" class="form-select @error('investment_risk_level') is-invalid @enderror" required>
                            <option value="low" {{ old('investment_risk_level', $property->investment_risk_level) == 'low' ? 'selected' : '' }}>Düşük</option>
                            <option value="medium" {{ old('investment_risk_level', $property->investment_risk_level) == 'medium' ? 'selected' : '' }}>Orta</option>
                            <option value="high" {{ old('investment_risk_level', $property->investment_risk_level) == 'high' ? 'selected' : '' }}>Yüksek</option>
                        </select>
                        @error('investment_risk_level') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Potansiyel Notları</label>
                        <textarea name="potential_notes" class="form-control @error('potential_notes') is-invalid @enderror" rows="2" placeholder="Geliştirme potansiyeli hakkında notlar">{{ old('potential_notes', $property->potential_notes) }}</textarea>
                        @error('potential_notes') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Durum --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Durum</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Durum *</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="vacant" {{ old('status', $property->status) == 'vacant' ? 'selected' : '' }}>Boş</option>
                            <option value="acquired" {{ old('status', $property->status) == 'acquired' ? 'selected' : '' }}>Satın Alındı</option>
                            <option value="in_development" {{ old('status', $property->status) == 'in_development' ? 'selected' : '' }}>Geliştirme Aşamasında</option>
                            <option value="developed" {{ old('status', $property->status) == 'developed' ? 'selected' : '' }}>Geliştirildi</option>
                            <option value="inactive" {{ old('status', $property->status) == 'inactive' ? 'selected' : '' }}>İnaktif</option>
                        </select>
                        @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Güncelle
                </button>
                <a href="{{ route('operations.properties.show', $property) }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>İptal
                </a>
            </div>
        </div>
    </form>
@endsection
