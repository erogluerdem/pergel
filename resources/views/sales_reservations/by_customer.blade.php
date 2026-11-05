@extends('layouts.master')

@section('title', 'Müşteri Satış Opsiyonları — ' . ($customer->name ?? 'Müşteri'))
@section('topbar-title', 'Satış - Müşteri Opsiyonları')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="fw-bold mb-1">
                <i class="ri-bookmark-3-line text-warning me-2"></i>Müşteri Satış Opsiyonları & Rezervasyonları: {{ $customer->name }}
            </h4>
            <p class="text-muted mb-0">
                Müşteriye ait tüm bağımsız bölüm opsiyonları, geçerlilik tarihleri ve fiyat teklifleri.
            </p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('sales-reservations.create', ['customer_id' => $customer->id]) }}" class="btn btn-warning text-dark fw-semibold">
                <i class="ri-add-line me-1"></i>Yeni Opsiyon Oluştur
            </a>
            <a href="{{ route('sales-reservations.index') }}" class="btn btn-outline-secondary ms-1">
                <i class="ri-arrow-left-line me-1"></i>Tüm Opsiyonlar
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ $customer->name }} — Opsiyon Listesi</h5>
            <span class="badge bg-primary">{{ method_exists($reservations, 'total') ? $reservations->total() : count($reservations) }} Kayıt</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Opsiyon No</th>
                            <th>Bağımsız Bölüm</th>
                            <th>Opsiyon Tarihi</th>
                            <th>Bitiş Tarihi</th>
                            <th class="text-end">Opsiyon Fiyatı</th>
                            <th class="text-end">İndirim</th>
                            <th class="text-center">Durum</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $res)
                            <tr>
                                <td class="ps-4 font-monospace fw-bold">{{ $res->option_no }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $res->unit->unit_no ?? ('Birim #' . $res->unit_id) }}</div>
                                    <small class="text-muted">{{ $res->unit->unit_type ?? '' }}</small>
                                </td>
                                <td>{{ $res->option_date ? \Carbon\Carbon::parse($res->option_date)->format('d.m.Y') : '-' }}</td>
                                <td>{{ $res->expiry_date ? \Carbon\Carbon::parse($res->expiry_date)->format('d.m.Y') : '-' }}</td>
                                <td class="text-end font-monospace fw-semibold">₺{{ number_format($res->option_price ?? 0, 2, ',', '.') }}</td>
                                <td class="text-end font-monospace text-danger">₺{{ number_format($res->discount_amount ?? 0, 2, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($res->status === 'confirmed')
                                        <span class="badge bg-success">Onaylandı</span>
                                    @elseif($res->status === 'pending')
                                        <span class="badge bg-warning text-dark">Bekliyor</span>
                                    @elseif($res->status === 'expired')
                                        <span class="badge bg-secondary">Süresi Doldu</span>
                                    @else
                                        <span class="badge bg-danger">İptal</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('sales-reservations.show', $res) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('sales-reservations.edit', $res) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    Bu müşteriye ait kayıtlı satış opsiyonu bulunamadı.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($reservations, 'links'))
            <div class="card-footer">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
