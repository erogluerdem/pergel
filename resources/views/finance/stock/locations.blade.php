@extends('layouts.master')
@section('title', 'Stok Lokasyonları')
@section('topbar-title', 'Finans')

@push('styles')
<link href="/libs/fontawesome/css/all.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" rel="stylesheet">
<style>
    .location-card {
        transition: all 0.3s ease;
        border-left: 4px solid #e9ecef;
        height: 100%;
    }
    .location-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .location-card.warehouse { border-left-color: #007bff; }
    .location-card.room { border-left-color: #28a745; }
    .location-card.shelf { border-left-color: #ffc107; }
    .location-card.bin { border-left-color: #dc3545; }
    
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: scale(1.02);
    }
    .stat-card.secondary {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .stat-card.success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .stat-card.warning {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 50px;
    }
    
    .security-badge.low { background-color: #d4edda; color: #155724; }
    .security-badge.standard { background-color: #cce5ff; color: #004085; }
    .security-badge.high { background-color: #fff3cd; color: #856404; }
    .security-badge.restricted { background-color: #f8d7da; color: #721c24; }
    
    .temp-badge.no { background-color: #e9ecef; color: #495057; }
    .temp-badge.yes { background-color: #cce5ff; color: #004085; }
    .temp-badge.cold { background-color: #b3d9ff; color: #003366; }
    .temp-badge.frozen { background-color: #99ccff; color: #002244; }
    
    .location-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        border: 2px dashed #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 2rem;
    }
    
    .capacity-progress {
        height: 8px;
        border-radius: 4px;
        background-color: #e9ecef;
        overflow: hidden;
    }
    
    .capacity-bar {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #20c997);
        border-radius: 4px;
        transition: width 0.3s ease;
    }
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    
    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    #mapModal .modal-dialog {
        max-width: 900px;
    }
    
    #locationMap {
        height: 400px;
        border-radius: 8px;
    }
    
    .working-hours-display {
        font-size: 0.85rem;
        background-color: #f8f9fa;
        padding: 0.5rem;
        border-radius: 6px;
        margin-top: 0.5rem;
    }
    
    .filter-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 1rem;
        }
        .location-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- İstatistik Kartları -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-warehouse fa-2x opacity-75"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h3 class="mb-0">{{ $stats['total_locations'] }}</h3>
                    <p class="mb-0 opacity-75">Toplam Lokasyon</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card secondary">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-boxes fa-2x opacity-75"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h3 class="mb-0">{{ $stats['total_stock_items'] }}</h3>
                    <p class="mb-0 opacity-75">Stok Ürünü</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card success">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-chart-line fa-2x opacity-75"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h3 class="mb-0">₺{{ number_format($stats['total_capacity'] ?? 0, 0, ',', '.') }}</h3>
                    <p class="mb-0 opacity-75">Toplam Kapasite</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card warning">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-shield-alt fa-2x opacity-75"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h3 class="mb-0">{{ $stats['high_security_count'] }}</h3>
                    <p class="mb-0 opacity-75">Yüksek Güvenlik</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtre ve Arama Bölümü -->
<div class="filter-section">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <div class="row g-3">
                <div class="col-md-3">
                    <select class="form-select" id="filterType">
                        <option value="">Tüm Türler</option>
                        @foreach($locationTypes as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterSecurity">
                        <option value="">Tüm Güvenlik Seviyeleri</option>
                        @foreach($securityLevels as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterStatus">
                        <option value="">Tüm Durumlar</option>
                        <option value="1">Aktif</option>
                        <option value="0">Pasif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="searchInput" placeholder="Ara...">
                </div>
            </div>
        </div>
        <div class="col-lg-4 text-end">
            <button class="btn btn-gradient me-2" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                <i class="fas fa-plus"></i> Yeni Lokasyon
            </button>
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#mapModal">
                <i class="fas fa-map"></i> Harita Görünümü
            </button>
        </div>
    </div>
</div>

<!-- Lokasyon Kartları -->
<div class="row" id="locationsContainer">
    @foreach($locations as $location)
    <div class="col-xl-4 col-lg-6 col-md-6 location-item" 
         data-type="{{ $location->type }}" 
         data-security="{{ $location->security_level }}" 
         data-status="{{ $location->is_active ? '1' : '0' }}"
         data-search="{{ strtolower($location->name . ' ' . $location->code . ' ' . $location->city) }}">
        <div class="card location-card {{ $location->type }}">
            <div class="card-body">
                <!-- Lokasyon Başlığı -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">{{ $location->name }}</h5>
                        <span class="badge bg-primary">{{ $location->code }}</span>
                        <span class="badge bg-secondary ms-1">{{ $locationTypes[$location->type] ?? $location->type }}</span>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link text-muted p-1" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="editLocation({{ $location->id }})">
                                <i class="fas fa-edit"></i> Düzenle
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="toggleLocationStatus({{ $location->id }})">
                                <i class="fas fa-{{ $location->is_active ? 'pause' : 'play' }}"></i> 
                                {{ $location->is_active ? 'Devre Dışı Bırak' : 'Aktif Et' }}
                            </a></li>
                            @if($location->latitude && $location->longitude)
                            <li><a class="dropdown-item" href="#" onclick="showLocationOnMap({{ $location->latitude }}, {{ $location->longitude }}, '{{ $location->name }}')">
                                <i class="fas fa-map-marker-alt"></i> Haritada Göster
                            </a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteLocation({{ $location->id }})">
                                <i class="fas fa-trash"></i> Sil
                            </a></li>
                        </ul>
                    </div>
                </div>

                <!-- Lokasyon Resmi/İkonu -->
                <div class="location-image mb-3">
                    @if($location->image_path)
                        <img src="{{ $location->image_path }}" alt="{{ $location->name }}" class="location-image">
                    @else
                        <i class="fas fa-{{ $location->type == 'warehouse' ? 'warehouse' : ($location->type == 'room' ? 'door-open' : ($location->type == 'shelf' ? 'archive' : 'box')) }}"></i>
                    @endif
                </div>

                <!-- Lokasyon Bilgileri -->
                <div class="mb-3">
                    @if($location->description)
                        <p class="text-muted small mb-2">{{ Str::limit($location->description, 80) }}</p>
                    @endif
                    
                    <div class="row text-center mb-2">
                        <div class="col-4">
                            <small class="text-muted d-block">Stok Sayısı</small>
                            <strong>{{ $location->stock_count }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Kapasite</small>
                            <strong>{{ $location->capacity ? '₺' . number_format($location->capacity, 0, ',', '.') : 'Belirsiz' }}</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Durum</small>
                            <span class="status-badge badge bg-{{ $location->is_active ? 'success' : 'secondary' }}">
                                {{ $location->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Kapasite Çubuğu -->
                    @if($location->capacity)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">Kapasite Kullanımı</small>
                            <small class="text-muted">{{ $location->capacity_utilization_percentage }}%</small>
                        </div>
                        <div class="capacity-progress">
                            <div class="capacity-bar" style="width: {{ min($location->capacity_utilization_percentage, 100) }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Alt Bilgiler -->
                <div class="row g-2">
                    <div class="col-6">
                        <small class="text-muted d-block">Güvenlik</small>
                        <span class="badge security-badge {{ $location->security_level }}">
                            {{ $securityLevels[$location->security_level] ?? $location->security_level }}
                        </span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Sıcaklık</small>
                        <span class="badge temp-badge {{ $location->temperature_controlled }}">
                            {{ $temperatureOptions[$location->temperature_controlled] ?? $location->temperature_controlled }}
                        </span>
                    </div>
                </div>

                @if($location->city || $location->manager_name)
                <hr class="my-2">
                <div class="row g-2">
                    @if($location->city)
                    <div class="col-6">
                        <small class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $location->city }}</small>
                    </div>
                    @endif
                    @if($location->manager_name)
                    <div class="col-6">
                        <small class="text-muted"><i class="fas fa-user"></i> {{ $location->manager_name }}</small>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Yeni Lokasyon Modal -->
<div class="modal fade" id="addLocationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Yeni Lokasyon Ekle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addLocationForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Lokasyon Kodu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lokasyon Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tür <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" required>
                                @foreach($locationTypes as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Güvenlik Seviyesi <span class="text-danger">*</span></label>
                            <select class="form-select" name="security_level" required>
                                @foreach($securityLevels as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bina</label>
                            <input type="text" class="form-control" name="building">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kat</label>
                            <input type="text" class="form-control" name="floor">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bölge</label>
                            <input type="text" class="form-control" name="zone">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Adres</label>
                            <input type="text" class="form-control" name="address">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Şehir</label>
                            <input type="text" class="form-control" name="city">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Enlem</label>
                            <input type="number" step="0.00000001" class="form-control" name="latitude">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Boylam</label>
                            <input type="number" step="0.00000001" class="form-control" name="longitude">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sıcaklık Kontrolü</label>
                            <select class="form-select" name="temperature_controlled">
                                @foreach($temperatureOptions as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kapasite (₺)</label>
                            <input type="number" step="0.01" class="form-control" name="capacity">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kapasite Birimi</label>
                            <input type="text" class="form-control" name="capacity_unit" value="TL">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sorumlu Adı</label>
                            <input type="text" class="form-control" name="manager_name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sorumlu E-posta</label>
                            <input type="email" class="form-control" name="manager_email">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sorumlu Telefon</label>
                            <input type="text" class="form-control" name="manager_phone">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notlar</label>
                            <textarea class="form-control" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save"></i> Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Düzenleme Modal -->
<div class="modal fade" id="editLocationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit"></i> Lokasyon Düzenle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editLocationForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editLocationId" name="id">
                <div class="modal-body">
                    <!-- Düzenleme formu alanları addLocationModal ile aynı olacak -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Lokasyon Kodu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" id="edit_code" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lokasyon Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tür <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" id="edit_type" required>
                                @foreach($locationTypes as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Güvenlik Seviyesi <span class="text-danger">*</span></label>
                            <select class="form-select" name="security_level" id="edit_security_level" required>
                                @foreach($securityLevels as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bina</label>
                            <input type="text" class="form-control" name="building" id="edit_building">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kat</label>
                            <input type="text" class="form-control" name="floor" id="edit_floor">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bölge</label>
                            <input type="text" class="form-control" name="zone" id="edit_zone">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Adres</label>
                            <input type="text" class="form-control" name="address" id="edit_address">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Şehir</label>
                            <input type="text" class="form-control" name="city" id="edit_city">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Enlem</label>
                            <input type="number" step="0.00000001" class="form-control" name="latitude" id="edit_latitude">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Boylam</label>
                            <input type="number" step="0.00000001" class="form-control" name="longitude" id="edit_longitude">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sıcaklık Kontrolü</label>
                            <select class="form-select" name="temperature_controlled" id="edit_temperature_controlled">
                                @foreach($temperatureOptions as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kapasite (₺)</label>
                            <input type="number" step="0.01" class="form-control" name="capacity" id="edit_capacity">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kapasite Birimi</label>
                            <input type="text" class="form-control" name="capacity_unit" id="edit_capacity_unit">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sorumlu Adı</label>
                            <input type="text" class="form-control" name="manager_name" id="edit_manager_name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sorumlu E-posta</label>
                            <input type="email" class="form-control" name="manager_email" id="edit_manager_email">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sorumlu Telefon</label>
                            <input type="text" class="form-control" name="manager_phone" id="edit_manager_phone">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notlar</label>
                            <textarea class="form-control" name="notes" id="edit_notes" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" value="1">
                                <label class="form-check-label" for="edit_is_active">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save"></i> Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Harita Modal -->
<div class="modal fade" id="mapModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-map"></i> Lokasyon Haritası
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="locationMap"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<script>
let map;
let locationMarkers = [];

$(document).ready(function() {
    // Filtreler
    $('#filterType, #filterSecurity, #filterStatus').on('change', filterLocations);
    $('#searchInput').on('keyup', filterLocations);
    
    // Form gönderim işlemleri
    $('#addLocationForm').on('submit', function(e) {
        e.preventDefault();
        submitLocationForm(this, '{{ route("finance.stock.locations.store") }}', 'POST');
    });
    
    $('#editLocationForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#editLocationId').val();
        submitLocationForm(this, `/finance/stock/locations/${id}`, 'PUT');
    });
});

function filterLocations() {
    const typeFilter = $('#filterType').val();
    const securityFilter = $('#filterSecurity').val();
    const statusFilter = $('#filterStatus').val();
    const searchTerm = $('#searchInput').val().toLowerCase();
    
    $('.location-item').each(function() {
        const $item = $(this);
        const type = $item.data('type');
        const security = $item.data('security');
        const status = $item.data('status').toString();
        const searchData = $item.data('search');
        
        let show = true;
        
        if (typeFilter && type !== typeFilter) show = false;
        if (securityFilter && security !== securityFilter) show = false;
        if (statusFilter !== '' && status !== statusFilter) show = false;
        if (searchTerm && !searchData.includes(searchTerm)) show = false;
        
        $item.toggle(show);
    });
}

function submitLocationForm(form, url, method) {
    const formData = new FormData(form);
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-HTTP-Method-Override': method
        },
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                $(form).closest('.modal').modal('hide');
                setTimeout(() => location.reload(), 1000);
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    showAlert(errors[key][0], 'error');
                });
            } else {
                showAlert('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
            }
        }
    });
}

function editLocation(id) {
    // AJAX ile lokasyon verilerini getir ve form alanlarını doldur
    $.get(`/finance/stock/locations/${id}`)
        .done(function(location) {
            $('#editLocationId').val(location.id);
            $('#edit_code').val(location.code);
            $('#edit_name').val(location.name);
            $('#edit_description').val(location.description);
            $('#edit_type').val(location.type);
            $('#edit_security_level').val(location.security_level);
            $('#edit_building').val(location.building);
            $('#edit_floor').val(location.floor);
            $('#edit_zone').val(location.zone);
            $('#edit_address').val(location.address);
            $('#edit_city').val(location.city);
            $('#edit_latitude').val(location.latitude);
            $('#edit_longitude').val(location.longitude);
            $('#edit_temperature_controlled').val(location.temperature_controlled);
            $('#edit_capacity').val(location.capacity);
            $('#edit_capacity_unit').val(location.capacity_unit);
            $('#edit_manager_name').val(location.manager_name);
            $('#edit_manager_email').val(location.manager_email);
            $('#edit_manager_phone').val(location.manager_phone);
            $('#edit_notes').val(location.notes);
            $('#edit_is_active').prop('checked', location.is_active);
            
            $('#editLocationModal').modal('show');
        })
        .fail(function() {
            showAlert('Lokasyon bilgileri yüklenemedi.', 'error');
        });
}

function deleteLocation(id) {
    if (confirm('Bu lokasyonu silmek istediğinizden emin misiniz?')) {
        $.ajax({
            url: `/finance/stock/locations/${id}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert(response.message || 'Silme işlemi başarısız.', 'error');
            }
        });
    }
}

function toggleLocationStatus(id) {
    $.ajax({
        url: `/finance/stock/locations/${id}/toggle-status`,
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                setTimeout(() => location.reload(), 1000);
            }
        },
        error: function() {
            showAlert('Durum güncellemesi başarısız.', 'error');
        }
    });
}

function initializeMap() {
    if (map) {
        map.remove();
    }
    
    map = L.map('locationMap').setView([41.0082, 28.9784], 10);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Lokasyonları haritaya ekle
    @foreach($locations as $location)
        @if($location->latitude && $location->longitude)
            const marker{{ $location->id }} = L.marker([{{ $location->latitude }}, {{ $location->longitude }}])
                .addTo(map)
                .bindPopup(`
                    <div>
                        <h6>{{ $location->name }}</h6>
                        <p><strong>Kod:</strong> {{ $location->code }}</p>
                        <p><strong>Tür:</strong> {{ $locationTypes[$location->type] ?? $location->type }}</p>
                        @if($location->manager_name)
                        <p><strong>Sorumlu:</strong> {{ $location->manager_name }}</p>
                        @endif
                    </div>
                `);
            locationMarkers.push(marker{{ $location->id }});
        @endif
    @endforeach
}

function showLocationOnMap(lat, lng, name) {
    $('#mapModal').modal('show');
    setTimeout(() => {
        initializeMap();
        map.setView([lat, lng], 15);
        
        // İlgili marker'ı bul ve popup'ı aç
        locationMarkers.forEach(marker => {
            const markerPos = marker.getLatLng();
            if (Math.abs(markerPos.lat - lat) < 0.001 && Math.abs(markerPos.lng - lng) < 0.001) {
                marker.openPopup();
            }
        });
    }, 500);
}

// Modal açıldığında haritayı başlat
$('#mapModal').on('shown.bs.modal', function() {
    if (!map) {
        initializeMap();
    }
});

function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // En üstteki container'a ekle
    $('.container-fluid:first').prepend(alertHtml);
    
    // 5 saniye sonra otomatik kapat
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endpush
