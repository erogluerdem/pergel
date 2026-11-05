@extends('layouts.master')

@section('title', $salesTarget->title)
@section('topbar-title', $salesTarget->title)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">{{ $salesTarget->title }}</h5>
                <div>
                    <a href="{{ route('sales-targets.edit', $salesTarget) }}" class="btn btn-sm btn-warning me-2">
                        <i class="fas fa-edit me-1"></i>Düzenle
                    </a>
                    <form action="{{ route('sales-targets.destroy', $salesTarget) }}" method="POST" class="d-inline" 
                          onsubmit="return confirm('Silmek istediğinizden emin misiniz?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash me-1"></i>Sil
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Hedef Tutarı</h6>
                        <p class="h5 fw-bold text-primary">{{ number_format($salesTarget->target_amount, 2, ',', '.') }} ₺</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Gerçekleşme Oranı</h6>
                        <p class="h5 fw-bold">{{ number_format($percentage, 2, ',', '.') }}%</p>
                        <div class="progress">
                            <div class="progress-bar @if($percentage >= 100) bg-success @elseif($percentage >= 75) bg-info @else bg-warning @endif" 
                                 style="width: {{ min($percentage, 100) }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Gerçekleşen Tutar</h6>
                        <p class="h5 fw-bold text-success">{{ number_format($actual, 2, ',', '.') }} ₺</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Atanan Kişi</h6>
                        <p class="h5">
                            @if($salesTarget->assigned_to)
                                <span class="badge bg-info">{{ $salesTarget->assignedTo?->name ?? 'N/A' }}</span>
                            @else
                                <span class="badge bg-secondary">Atanmamış</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Başlangıç Tarihi</h6>
                        <p>{{ \Carbon\Carbon::parse($salesTarget->start_date)->format('d.m.Y') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Bitiş Tarihi</h6>
                        <p>{{ \Carbon\Carbon::parse($salesTarget->end_date)->format('d.m.Y') }}</p>
                    </div>
                </div>

                @if($salesTarget->notes)
                    <div class="mb-3">
                        <h6 class="text-muted">Notlar</h6>
                        <p class="text-secondary">{{ $salesTarget->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light border-0 py-3">
                <h5 class="mb-0 fw-bold">Özet</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0">
                        <span class="text-muted">Kalan Süre</span>
                        <p class="mb-0 fw-bold">
                            {{ \Carbon\Carbon::parse($salesTarget->end_date)->diffForHumans() }}
                        </p>
                    </div>
                    <div class="list-group-item px-0">
                        <span class="text-muted">Kalan Hedef</span>
                        <p class="mb-0 fw-bold text-warning">
                            {{ number_format($salesTarget->target_amount - $actual, 2, ',', '.') }} ₺
                        </p>
                    </div>
                    <div class="list-group-item px-0">
                        <span class="text-muted">Oluşturma Tarihi</span>
                        <p class="mb-0">{{ $salesTarget->created_at?->format('d.m.Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
