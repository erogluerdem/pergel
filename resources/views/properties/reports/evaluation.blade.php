@extends('layouts.master')

@section('title', $property->property_code . ' - Değerlendirme Raporu')
@section('topbar-title', 'Operasyon')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-chart-bar" style="font-size: 1.5rem; color: #0d6efd;"></i>
                <h4 class="mb-0">Değerlendirme Raporu</h4>
                <span class="badge bg-info">{{ $property->property_code }}</span>
            </div>
        </div>
    </div>

    {{-- Arsa Özeti --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Arsa Özeti</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="text-muted">İsim</label>
                    <div class="fw-bold">{{ $property->name }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">Lokasyon</label>
                    <div class="fw-bold">{{ $property->city }}, {{ $property->district }}</div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">Alan</label>
                    <div class="fw-bold">{{ number_format($property->area, 2, ',', '.') }} m²</div>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">Arsa Değeri</label>
                    <div class="fw-bold">₺ {{ number_format($property->land_value, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Değerlendirmeler --}}
    @forelse ($evaluations ?? [] as $eval)
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="mb-0">{{ $eval->evaluation_name }}</h5>
                        <small class="text-muted">Değerlendirme Tarihi: {{ $eval->evaluation_date->format('d.m.Y') }}</small>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="badge bg-{{ $eval->is_final ? 'success' : 'warning' }}">
                            {{ $eval->is_final ? 'Son Değerlendirme' : 'Taslak' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{-- Puanlama --}}
                <h6 class="mb-3">Puanlama (0-100)</h6>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="text-muted">Arazi Uygunluğu</label>
                        <div class="progress mb-2" style="height: 25px;">
                            <div class="progress-bar bg-success" style="width: {{ $eval->terrain_suitability }}%">
                                {{ $eval->terrain_suitability }}%
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted">Erişilebilirlik</label>
                        <div class="progress mb-2" style="height: 25px;">
                            <div class="progress-bar bg-info" style="width: {{ $eval->accessibility_score }}%">
                                {{ $eval->accessibility_score }}%
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted">Altyapı Hazırlığı</label>
                        <div class="progress mb-2" style="height: 25px;">
                            <div class="progress-bar bg-primary" style="width: {{ $eval->infrastructure_readiness }}%">
                                {{ $eval->infrastructure_readiness }}%
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Finansal Analizler --}}
                <h6 class="mb-3">Finansal Analizler</h6>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="text-muted">Pazar Değeri (m² başına)</label>
                        <div class="fw-bold">₺ {{ number_format($eval->market_value_per_sqm, 0, ',', '.') ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted">Toplam Pazar Değeri</label>
                        <div class="fw-bold">₺ {{ number_format($eval->total_market_value, 0, ',', '.') ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted">Takdir Potansiyeli</label>
                        <div class="fw-bold">{{ $eval->appreciation_potential_percent ?? '-' }}%</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted">ROI Projeksiyonu</label>
                        <div class="fw-bold">{{ $eval->roi_projection ?? '-' }}%</div>
                    </div>
                </div>

                {{-- Yatırım Metrikleri --}}
                <h6 class="mb-3">Yatırım Metrikleri</h6>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="text-muted">Geri Ödeme Dönemi</label>
                        <div class="fw-bold">{{ $eval->payback_period_months ?? '-' }} ay</div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted">Yatırım Önerisi</label>
                        <div><span class="badge bg-{{ $eval->investment_recommendation == 'highly_recommended' ? 'success' : ($eval->investment_recommendation == 'recommended' ? 'info' : ($eval->investment_recommendation == 'neutral' ? 'warning' : 'danger')) }}">{{ ucfirst(str_replace('_', ' ', $eval->investment_recommendation)) }}</span></div>
                    </div>
                </div>

                {{-- Risk Analizi --}}
                <h6 class="mb-3">Risk Analizi</h6>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="text-muted">Pazar Riski</label>
                        <div><span class="badge bg-{{ $eval->market_risk == 'low' ? 'success' : ($eval->market_risk == 'medium' ? 'warning' : 'danger') }}">{{ ucfirst($eval->market_risk) }}</span></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted">Yasal Riski</label>
                        <div><span class="badge bg-{{ $eval->legal_risk == 'low' ? 'success' : ($eval->legal_risk == 'medium' ? 'warning' : 'danger') }}">{{ ucfirst($eval->legal_risk) }}</span></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted">Geliştirme Riski</label>
                        <div><span class="badge bg-{{ $eval->development_risk == 'low' ? 'success' : ($eval->development_risk == 'medium' ? 'warning' : 'danger') }}">{{ ucfirst($eval->development_risk) }}</span></div>
                    </div>
                </div>

                {{-- SWOT Analizi --}}
                <h6 class="mb-3">SWOT Analizi</h6>
                <div class="row">
                    @if ($eval->strengths)
                        <div class="col-md-3">
                            <h6 class="text-success">Güçlü Yönler</h6>
                            <p>{{ $eval->strengths }}</p>
                        </div>
                    @endif
                    @if ($eval->weaknesses)
                        <div class="col-md-3">
                            <h6 class="text-danger">Zayıf Yönler</h6>
                            <p>{{ $eval->weaknesses }}</p>
                        </div>
                    @endif
                    @if ($eval->opportunities)
                        <div class="col-md-3">
                            <h6 class="text-info">Fırsatlar</h6>
                            <p>{{ $eval->opportunities }}</p>
                        </div>
                    @endif
                    @if ($eval->threats)
                        <div class="col-md-3">
                            <h6 class="text-warning">Tehditler</h6>
                            <p>{{ $eval->threats }}</p>
                        </div>
                    @endif
                </div>

                {{-- Öneriler --}}
                @if ($eval->recommendations)
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6>Öneriler</h6>
                        <p>{{ $eval->recommendations }}</p>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center">
                <p class="text-muted">Bu arsa için henüz değerlendirme bulunmamaktadır.</p>
            </div>
        </div>
    @endforelse

    {{-- Buttons --}}
    <div class="mt-4">
        <a href="{{ route('operations.properties.show', $property) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Geri Dön
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print me-2"></i>Yazdır
        </button>
    </div>
@endsection
