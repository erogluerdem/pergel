@extends('layouts.master')

@section('title', 'Satış Hedefleri')
@section('topbar-title', 'Satış Hedefleri')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light border-0 py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0 fw-bold">Satış Hedefleri Listesi</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('sales-targets.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Yeni Hedef Ekle
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($targets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Başlık</th>
                                    <th>Hedef Tutarı</th>
                                    <th>Atanan Kişi</th>
                                    <th>Başlangıç Tarihi</th>
                                    <th>Bitiş Tarihi</th>
                                    <th class="text-end">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($targets as $target)
                                    <tr>
                                        <td><strong>{{ $target->title }}</strong></td>
                                        <td>{{ number_format($target->target_amount, 2, ',', '.') }} ₺</td>
                                        <td>
                                            @if($target->assigned_to)
                                                <span class="badge bg-info">{{ $target->assignedTo?->name ?? 'N/A' }}</span>
                                            @else
                                                <span class="badge bg-secondary">Atanmamış</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($target->start_date)->format('d.m.Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($target->end_date)->format('d.m.Y') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('sales-targets.show', $target) }}" class="btn btn-sm btn-info" title="Detaylar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('sales-targets.edit', $target) }}" class="btn btn-sm btn-warning" title="Düzenle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('sales-targets.destroy', $target) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinizden emin misiniz?')">
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
                        {{ $targets->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Henüz satış hedefi eklenmemiştir.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
