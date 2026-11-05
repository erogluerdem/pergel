@extends('layouts.master')

@section('title', $salesOption->name)
@section('topbar-title', $salesOption->name)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">{{ $salesOption->name }}</h5>
                <div>
                    <a href="{{ route('sales-options.edit', $salesOption) }}" class="btn btn-sm btn-warning me-2">
                        <i class="fas fa-edit me-1"></i>Düzenle
                    </a>
                    <form action="{{ route('sales-options.destroy', $salesOption) }}" method="POST" class="d-inline" 
                          onsubmit="return confirm('Silmek istediğinizden emin misiniz?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash me-1"></i>Sil
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if($salesOption->description)
                    <div class="mb-4">
                        <h6 class="text-muted">Açıklama</h6>
                        <p>{{ $salesOption->description }}</p>
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Durum</h6>
                        @php
                            $statusColor = match($salesOption->status) {
                                'active' => 'success',
                                'draft' => 'secondary',
                                'archived' => 'warning',
                                'expired' => 'danger',
                                default => 'secondary'
                            };
                            $statusLabel = match($salesOption->status) {
                                'active' => 'Aktif',
                                'draft' => 'Taslak',
                                'archived' => 'Arşiv',
                                'expired' => 'Süresi Dolmuş',
                                default => $salesOption->status
                            };
                        @endphp
                        <p><span class="badge bg-{{ $statusColor }}">{{ $statusLabel }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">İndirim Oranı</h6>
                        <p class="h5 fw-bold text-danger">
                            @if($salesOption->discount_percentage)
                                -{{ $salesOption->discount_percentage }}%
                            @else
                                <span class="text-muted">Uygulanmaz</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Geçerlilik Başlangıcı</h6>
                        <p>{{ \Carbon\Carbon::parse($salesOption->valid_from)->format('d.m.Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Geçerlilik Sonu</h6>
                        <p>{{ \Carbon\Carbon::parse($salesOption->valid_until)->format('d.m.Y') }}</p>
                    </div>
                </div>

                @if($salesOption->extra_features)
                    <div class="mb-3">
                        <h6 class="text-muted">Ek Özellikler</h6>
                        <p>{{ $salesOption->extra_features }}</p>
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
                        <span class="text-muted">Oluşturma Tarihi</span>
                        <p class="mb-0">{{ $salesOption->created_at?->format('d.m.Y H:i') }}</p>
                    </div>
                    <div class="list-group-item px-0">
                        <span class="text-muted">Son Güncelleme</span>
                        <p class="mb-0">{{ $salesOption->updated_at?->format('d.m.Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
