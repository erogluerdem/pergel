@extends('layouts.master')

@section('title', $property->property_code . ' - Yasal Durum Raporu')
@section('topbar-title', 'Operasyon')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-file-contract" style="font-size: 1.5rem; color: #0d6efd;"></i>
                <h4 class="mb-0">Yasal Durum Raporu</h4>
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
                <div class="col-md-4">
                    <label class="text-muted">İsim</label>
                    <div class="fw-bold">{{ $property->name }}</div>
                </div>
                <div class="col-md-4">
                    <label class="text-muted">Yasal Durum</label>
                    <div><span class="badge bg-{{ $property->legal_status == 'clear' ? 'success' : ($property->legal_status == 'disputed' ? 'danger' : 'warning') }}">{{ ucfirst(str_replace('_', ' ', $property->legal_status)) }}</span></div>
                </div>
                <div class="col-md-4">
                    <label class="text-muted">Tapu No</label>
                    <div class="fw-bold">{{ $property->deed_number ?? '-' }}</div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label class="text-muted">Yasal Notlar</label>
                    <div>{{ $property->legal_notes ?? 'Not bulunmamaktadır.' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Yasal İşlemler Listesi --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Yasal İşlemler ({{ count($processes ?? []) }})</h5>
        </div>
        @forelse ($processes ?? [] as $process)
            <div class="card-body border-bottom">
                <div class="row">
                    <div class="col-md-8">
                        <h6 class="mb-2">{{ $process->process_name }}</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">İşlem Tipi</label>
                                <div>{{ ucfirst(str_replace('_', ' ', $process->process_type)) }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Sorumlu Kurum</label>
                                <div>{{ $process->authority ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Başlama Tarihi</label>
                                <div>{{ $process->start_date->format('d.m.Y') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Tahmini Bitiş</label>
                                <div>{{ $process->end_date?->format('d.m.Y') ?? '-' }}</div>
                            </div>
                            @if ($process->actual_completion_date)
                                <div class="col-md-6">
                                    <label class="text-muted small">Fiili Bitiş</label>
                                    <div>{{ $process->actual_completion_date->format('d.m.Y') }}</div>
                                </div>
                            @endif
                            @if ($process->reference_number)
                                <div class="col-md-6">
                                    <label class="text-muted small">Referans No</label>
                                    <div>{{ $process->reference_number }}</div>
                                </div>
                            @endif
                            @if ($process->description)
                                <div class="col-md-12">
                                    <label class="text-muted small">Açıklama</label>
                                    <div>{{ $process->description }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small">Durum</label>
                            <div class="mb-3">
                                <span class="badge bg-{{ $process->status == 'completed' ? 'success' : ($process->status == 'delayed' ? 'danger' : ($process->status == 'blocked' ? 'dark' : 'warning')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $process->status)) }}
                                </span>
                            </div>

                            @if ($process->cost)
                                <label class="text-muted small">Tahmini Maliyet</label>
                                <div class="mb-2">₺ {{ number_format($process->cost, 0, ',', '.') }}</div>
                            @endif

                            @if ($process->actual_cost)
                                <label class="text-muted small">Fiili Maliyet</label>
                                <div class="mb-2">₺ {{ number_format($process->actual_cost, 0, ',', '.') }}</div>
                            @endif

                            @if ($process->duration_days)
                                <label class="text-muted small">Süre</label>
                                <div class="mb-2">{{ $process->duration_days }} gün</div>
                            @endif

                            @if ($process->is_delayed)
                                <div class="alert alert-danger py-2 mb-0">
                                    <small><strong>Gecikmiş:</strong> {{ $process->delay_days }} gün</small>
                                    @if ($process->delay_reason)
                                        <br><small>{{ $process->delay_reason }}</small>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-body">
                <p class="text-muted text-center mb-0">Yasal işlem bulunmamaktadır.</p>
            </div>
        @endforelse
    </div>

    {{-- İstatistikler --}}
    @if (count($processes ?? []) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">İstatistikler</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="text-muted small">Tamamlanan</div>
                                <div class="h4">{{ count($processes->filter(fn($p) => $p->status == 'completed') ?? []) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning bg-opacity-10">
                            <div class="card-body">
                                <div class="text-muted small">Devam Eden</div>
                                <div class="h4">{{ count($processes->filter(fn($p) => $p->status == 'in_progress') ?? []) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger bg-opacity-10">
                            <div class="card-body">
                                <div class="text-muted small">Gecikmiş</div>
                                <div class="h4">{{ count($processes->filter(fn($p) => $p->is_delayed) ?? []) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-dark bg-opacity-10">
                            <div class="card-body">
                                <div class="text-muted small">Bloke Edilmiş</div>
                                <div class="h4">{{ count($processes->filter(fn($p) => $p->status == 'blocked') ?? []) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Maliyet Özeti --}}
        @if (count($processes->filter(fn($p) => $p->cost) ?? []) > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Maliyet Özeti</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="text-muted">Toplam Tahmini Maliyet</label>
                            <div class="fw-bold h5">₺ {{ number_format($processes->sum('cost'), 0, ',', '.') }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">Toplam Fiili Maliyet</label>
                            <div class="fw-bold h5">₺ {{ number_format($processes->sum('actual_cost'), 0, ',', '.') }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted">Fark</label>
                            <div class="fw-bold h5 {{ $processes->sum('actual_cost') > $processes->sum('cost') ? 'text-danger' : 'text-success' }}">
                                ₺ {{ number_format($processes->sum('actual_cost') - $processes->sum('cost'), 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

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
