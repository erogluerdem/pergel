@extends('layouts.master')

@section('title', 'Proje Bağımsız Bölümleri — ' . ($project->name ?? 'Proje'))
@section('topbar-title', 'Portföy - Proje Bağımsız Bölümleri')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="fw-bold mb-1">
                <i class="ri-building-2-line text-warning me-2"></i>{{ $project->name }} — Bağımsız Bölümler Listesi
            </h4>
            <p class="text-muted mb-0">Projeye ait daire, villa ve ticari ünitelerin metrekare, fiyat ve satış durumları.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('units.create', ['project_id' => $project->id]) }}" class="btn btn-warning text-dark fw-semibold">
                <i class="ri-add-line me-1"></i>Yeni Birim Ekle
            </a>
            <a href="{{ route('units.index') }}" class="btn btn-outline-secondary ms-1">
                <i class="ri-arrow-left-line me-1"></i>Tüm Birimler
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ $project->name }} Üniteleri</h5>
            <span class="badge bg-primary">{{ method_exists($units, 'total') ? $units->total() : count($units) }} Birim</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Birim No</th>
                            <th>Blok / Kat</th>
                            <th>Tip & Oda</th>
                            <th>Alan (m²)</th>
                            <th class="text-end">Liste Fiyatı</th>
                            <th class="text-end">Satış Fiyatı</th>
                            <th class="text-center">Durum</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($units as $unit)
                            <tr>
                                <td class="ps-4 fw-bold font-monospace">{{ $unit->unit_no }}</td>
                                <td>{{ $unit->block ?? '-' }} / {{ $unit->floor ?? '-' }}. Kat</td>
                                <td>
                                    <span class="fw-semibold">{{ $unit->unit_type }}</span>
                                    <small class="text-muted d-block">{{ $unit->room_count ?? 0 }} Oda, {{ $unit->bathroom_count ?? 0 }} Banyo</small>
                                </td>
                                <td class="font-monospace">{{ number_format($unit->area ?? 0, 1, ',', '.') }} m²</td>
                                <td class="text-end font-monospace text-muted">₺{{ number_format($unit->list_price ?? 0, 0, ',', '.') }}</td>
                                <td class="text-end font-monospace fw-bold text-success">₺{{ number_format($unit->selling_price ?? 0, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($unit->status === 'available')
                                        <span class="badge bg-success">Müsait</span>
                                    @elseif($unit->status === 'reserved')
                                        <span class="badge bg-warning text-dark">Rezerve</span>
                                    @elseif($unit->status === 'sold')
                                        <span class="badge bg-danger">Satıldı</span>
                                    @else
                                        <span class="badge bg-secondary">Bakımda</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('units.show', $unit) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('units.edit', $unit) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    Bu projeye ait kayıtlı bağımsız bölüm bulunamadı.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($units, 'links'))
            <div class="card-footer">
                {{ $units->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
