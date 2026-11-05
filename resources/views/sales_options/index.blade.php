@extends('layouts.master')

@section('title', 'Satış Opsiyonları')
@section('topbar-title', 'Satış Opsiyonları')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light border-0 py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0 fw-bold">Satış Opsiyonları Listesi</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('sales-options.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Yeni Opsiyon Ekle
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($options->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Adı</th>
                                    <th>Durum</th>
                                    <th>İndirim %</th>
                                    <th>Geçerlilik Tarihleri</th>
                                    <th class="text-end">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($options as $option)
                                    <tr>
                                        <td><strong>{{ $option->name }}</strong></td>
                                        <td>
                                            @php
                                                $statusColor = match($option->status) {
                                                    'active' => 'success',
                                                    'draft' => 'secondary',
                                                    'archived' => 'warning',
                                                    'expired' => 'danger',
                                                    default => 'secondary'
                                                };
                                                $statusLabel = match($option->status) {
                                                    'active' => 'Aktif',
                                                    'draft' => 'Taslak',
                                                    'archived' => 'Arşiv',
                                                    'expired' => 'Süresi Dolmuş',
                                                    default => $option->status
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}">{{ $statusLabel }}</span>
                                        </td>
                                        <td>
                                            @if($option->discount_percentage)
                                                <strong class="text-danger">-{{ $option->discount_percentage }}%</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($option->valid_from)->format('d.m.Y') }} - 
                                            {{ \Carbon\Carbon::parse($option->valid_until)->format('d.m.Y') }}
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('sales-options.show', $option) }}" class="btn btn-sm btn-info" title="Detaylar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('sales-options.edit', $option) }}" class="btn btn-sm btn-warning" title="Düzetle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('sales-options.destroy', $option) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinizden emin misiniz?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Sil">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $options->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Henüz satış opsiyonu eklenmemiştir.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
