@extends('layouts.master')

@section('title', $property->property_code . ' - Arsa Detayı')
@section('topbar-title', 'Operasyon')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-map" style="font-size: 1.5rem; color: #0d6efd;"></i>
                <h4 class="mb-0">{{ $property->name }}</h4>
                <span class="badge bg-info">{{ $property->property_code }}</span>
            </div>
        </div>
        <div class="col-md-4 text-end">
            @can('update', $property)
                <a href="{{ route('operations.properties.edit', $property) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit me-2"></i>Düzenle
                </a>
            @endcan
            @can('delete', $property)
                <form method="POST" action="{{ route('operations.properties.destroy', $property) }}" style="display:inline;" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash me-2"></i>Sil
                    </button>
                </form>
            @endcan
        </div>
    </div>

    {{-- Temel Bilgiler --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Temel Bilgiler</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-3">
                    <label class="text-muted">Proje</label>
                    <div class="fw-bold">{{ $property->project?->name ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">İsim</label>
                    <div class="fw-bold">{{ $property->name }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">Durum</label>
                    <div><span class="badge bg-{{ $property->status == 'vacant' ? 'warning' : ($property->status == 'acquired' ? 'info' : ($property->status == 'developed' ? 'success' : 'secondary')) }}">{{ ucfirst(str_replace('_', ' ', $property->status)) }}</span></div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">Risk Seviyesi</label>
                    <div><span class="badge bg-{{ $property->investment_risk_level == 'low' ? 'success' : ($property->investment_risk_level == 'medium' ? 'warning' : 'danger') }}">{{ ucfirst($property->investment_risk_level) }}</span></div>
                </div>
                <div class="col-md-12">
                    <label class="text-muted">Açıklama</label>
                    <div>{{ $property->description ?? '-' }}</div>
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
            <div class="row g-4">
                <div class="col-md-3">
                    <label class="text-muted">Şehir</label>
                    <div class="fw-bold">{{ $property->city ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">İlçe</label>
                    <div class="fw-bold">{{ $property->district ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">Mahalle</label>
                    <div class="fw-bold">{{ $property->neighborhood ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">İmar Durumu</label>
                    <div class="fw-bold">{{ $property->zoning_status ?? '-' }}</div>
                </div>
                <div class="col-md-12">
                    <label class="text-muted">Tam Adres</label>
                    <div class="fw-bold">{{ $property->address }}</div>
                </div>
                @if ($property->latitude && $property->longitude)
                    <div class="col-md-6">
                        <label class="text-muted">Latitude</label>
                        <div class="fw-bold">{{ $property->latitude }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted">Longitude</label>
                        <div class="fw-bold">{{ $property->longitude }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Arsa Özellikleri --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-ruler me-2"></i>Arsa Özellikleri</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-3">
                    <label class="text-muted">Alan (m²)</label>
                    <div class="fw-bold">{{ $property->area ? number_format($property->area, 2, ',', '.') : '-' }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">İnşaat Alanı (m²)</label>
                    <div class="fw-bold">{{ $property->building_area ? number_format($property->building_area, 2, ',', '.') : '-' }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">TAKS</label>
                    <div class="fw-bold">{{ $property->floor_ratio ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">KAKS</label>
                    <div class="fw-bold">{{ $property->coverage_ratio ?? '-' }}</div>
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
            <div class="row g-4">
                <div class="col-md-3">
                    <label class="text-muted">Tapu No</label>
                    <div class="fw-bold">{{ $property->deed_number ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">Blok No</label>
                    <div class="fw-bold">{{ $property->block_number ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">Parsel No</label>
                    <div class="fw-bold">{{ $property->parcel_number ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">Tapu Tarihi</label>
                    <div class="fw-bold">{{ $property->deed_date?->format('d.m.Y') ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted">Mülk Sahibi Adı</label>
                    <div class="fw-bold">{{ $property->owner_name ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted">Yasal Durum</label>
                    <div><span class="badge bg-{{ $property->legal_status == 'clear' ? 'success' : ($property->legal_status == 'disputed' ? 'danger' : 'warning') }}">{{ ucfirst(str_replace('_', ' ', $property->legal_status)) }}</span></div>
                </div>
                @if ($property->deed_description)
                    <div class="col-md-12">
                        <label class="text-muted">Tapu Açıklaması</label>
                        <div>{{ $property->deed_description }}</div>
                    </div>
                @endif
                @if ($property->legal_notes)
                    <div class="col-md-12">
                        <label class="text-muted">Yasal Notlar</label>
                        <div>{{ $property->legal_notes }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Değerlendirme & Risk --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Değerlendirme & Risk</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <label class="text-muted">Arsa Değeri (₺)</label>
                    <div class="fw-bold">{{ $property->land_value ? number_format($property->land_value, 0, ',', '.') : '-' }}</div>
                </div>
                <div class="col-md-4">
                    <label class="text-muted">Geliştirme Potansiyel Değeri (₺)</label>
                    <div class="fw-bold">{{ $property->development_potential_value ? number_format($property->development_potential_value, 0, ',', '.') : '-' }}</div>
                </div>
                <div class="col-md-4">
                    <label class="text-muted">Toplam Değer (₺)</label>
                    <div class="fw-bold h5">{{ number_format(($property->land_value ?? 0) + ($property->development_potential_value ?? 0), 0, ',', '.') }}</div>
                </div>
                @if ($property->potential_notes)
                    <div class="col-md-12">
                        <label class="text-muted">Potansiyel Notları</label>
                        <div>{{ $property->potential_notes }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row mb-4">
        {{-- Belgeleri Yönet --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file me-2"></i>Belgeler ({{ $property->documents->count() }})</h5>
                </div>
                <div class="card-body">
                    @if ($property->documents->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach ($property->documents->take(5) as $doc)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $doc->document_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $doc->document_category }}</small>
                                    </div>
                                    <span class="badge bg-info">{{ $doc->file_type }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @if ($property->documents->count() > 5)
                            <p class="mt-2 text-muted"><small>... ve {{ $property->documents->count() - 5 }} diğer belge</small></p>
                        @endif
                    @else
                        <p class="text-muted">Belge bulunmamaktadır.</p>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('operations.properties.documents', $property) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-folder-open me-2"></i>Belgeleri Yönet
                    </a>
                </div>
            </div>
        </div>

        {{-- Yasal İşlemler --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Yasal İşlemler ({{ $property->legalProcesses->count() }})</h5>
                </div>
                <div class="card-body">
                    @if ($property->legalProcesses->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach ($property->legalProcesses->take(3) as $proc)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $proc->process_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $proc->process_type }}</small>
                                        </div>
                                        <span class="badge bg-{{ $proc->status == 'completed' ? 'success' : ($proc->status == 'delayed' ? 'danger' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $proc->status)) }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @if ($property->legalProcesses->count() > 3)
                            <p class="mt-2 text-muted"><small>... ve {{ $property->legalProcesses->count() - 3 }} diğer işlem</small></p>
                        @endif
                    @else
                        <p class="text-muted">Yasal işlem bulunmamaktadır.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Değerlendirmeler --}}
    @if ($property->evaluations->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Değerlendirmeler ({{ $property->evaluations->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($property->evaluations->take(3) as $eval)
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <h6>{{ $eval->evaluation_name }}</h6>
                                <small class="text-muted">{{ $eval->evaluation_date->format('d.m.Y') }}</small>
                                <hr>
                                <div><small><strong>ROI Projeksiyonu:</strong> %{{ $eval->roi_projection ?? '-' }}</small></div>
                                <div><small><strong>Risk Profili:</strong> {{ ucfirst($eval->market_risk ?? '-') }}</small></div>
                                <div class="mt-2">
                                    @if ($eval->is_final)
                                        <span class="badge bg-success">Son Değerlendirme</span>
                                    @else
                                        <span class="badge bg-warning">Taslak</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Buttons --}}
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('operations.properties.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Geri Dön
            </a>
            @if (auth()->check())
                <a href="{{ route('operations.properties.documents', $property) }}" class="btn btn-info">
                    <i class="fas fa-folder me-2"></i>Belgeleri Yönet
                </a>
                <a href="{{ route('operations.properties.evaluation-report', $property) }}" class="btn btn-primary">
                    <i class="fas fa-file-pdf me-2"></i>Değerlendirme Raporu
                </a>
            @endif
        </div>
    </div>
@endsection
