@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Satın Alma Siparişleri</h5>
                <div>
                    <a href="{{ route('finance.invoices.create') }}?is_purchase=1" class="btn btn-sm btn-success">
                        <i class="mdi mdi-file-plus"></i> Yeni Satınalma Faturası
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filtreler -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Tüm Durumlar</option>
                            <option value="open">Açık</option>
                            <option value="partially_delivered">Kısmen Teslim Alındı</option>
                            <option value="delivered">Teslim Alındı</option>
                            <option value="cancelled">İptal Edildi</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="projectFilter">
                            <option value="">Tüm Projeler</option>
                            <!-- Projeler buraya gelecek -->
                        </select>
                    </div>
                </div>

                <!-- Siparişler Tablosu -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sipariş No</th>
                                <th>Tedarikçi</th>
                                <th>Proje</th>
                                <th>Sipariş Tarihi</th>
                                <th>Toplam Tutar</th>
                                <th>Teslimat Durumu</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchaseOrders ?? [] as $po)
                            <tr>
                                <td>{{ $po->po_number }}</td>
                                <td>{{ $po->vendor->name ?? 'Bilinmiyor' }}</td>
                                <td>{{ $po->project->name ?? 'Bilinmiyor' }}</td>
                                <td>{{ $po->order_date->format('d.m.Y') }}</td>
                                <td>{{ number_format($po->total_amount, 0, ',', '.') }} TL</td>
                                <td>
                                    @if($po->status == 'open')
                                        <span class="badge bg-primary">Açık</span>
                                    @elseif($po->status == 'partially_delivered')
                                        <span class="badge bg-warning text-dark">Kısmen Teslim Alındı</span>
                                    @elseif($po->status == 'delivered')
                                        <span class="badge bg-success">Teslim Alındı</span>
                                    @elseif($po->status == 'cancelled')
                                        <span class="badge bg-danger">İptal Edildi</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewPurchaseOrder({{ $po->id }})">
                                        <i class="mdi mdi-eye"></i> Detay
                                    </button>
                                    <a href="{{ route('finance.invoices.create') }}?vendor_id={{ $po->vendor->id }}&purchase_order_id={{ $po->id }}" class="btn btn-sm btn-outline-success ms-1">
                                        <i class="mdi mdi-file-plus"></i> Satınalma Faturası Oluştur
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Henüz sipariş bulunmuyor.</td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="mt-3">
                                        <p>Eğer yeni bir satınalma faturası oluşturmak istiyorsanız, tedarikçi seçin:</p>
                                        <form method="get" action="{{ route('finance.invoices.create') }}" class="row g-2 justify-content-center">
                                            <div class="col-md-4">
                                                <select name="vendor_id" class="form-select">
                                                    <option value="">-- Tedarikçi Seçin --</option>
                                                    @if(isset($vendors))
                                                        @foreach($vendors as $vendor)
                                                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                                        @endforeach
                                                    @else
                                                        <option value="">(Tedarikçiler yüklenemedi)</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-primary" type="submit">Fatura Oluştur</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Sayfalama -->
                @if(isset($purchaseOrders) && $purchaseOrders->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $purchaseOrders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function viewPurchaseOrder(id) {
    // Sipariş detayını gösterme (şimdilik alert)
    alert('Sipariş detayı: ' + id);
}

document.getElementById('statusFilter').addEventListener('change', function() {
    const url = new URL(window.location);
    if (this.value) {
        url.searchParams.set('status', this.value);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
});

document.getElementById('projectFilter').addEventListener('change', function() {
    const url = new URL(window.location);
    if (this.value) {
        url.searchParams.set('project_id', this.value);
    } else {
        url.searchParams.delete('project_id');
    }
    window.location.href = url.toString();
});
</script>

@endsection
