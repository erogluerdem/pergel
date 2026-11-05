<div class="movement-details">
    <div class="row">
        <div class="col-md-6">
            <div class="detail-card">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-info-circle mr-2"></i>
                    Genel Bilgiler
                </h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="font-weight-bold">Hareket ID:</td>
                        <td>#{{ $movement->id }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Tarih/Saat:</td>
                        <td>{{ $movement->created_at->format('d.m.Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Hareket Tipi:</td>
                        <td>
                            <span class="badge badge-{{ $movement->type == 'in' ? 'success' : ($movement->type == 'out' ? 'danger' : 'warning') }}">
                                @switch($movement->type)
                                    @case('in')
                                        <i class="fas fa-arrow-down mr-1"></i>
                                        Giriş
                                        @break
                                    @case('out')
                                        <i class="fas fa-arrow-up mr-1"></i>
                                        Çıkış
                                        @break
                                    @case('adjustment')
                                        <i class="fas fa-adjust mr-1"></i>
                                        Düzeltme
                                        @break
                                    @case('transfer')
                                        <i class="fas fa-exchange-alt mr-1"></i>
                                        Transfer
                                        @break
                                @endswitch
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Referans No:</td>
                        <td>{{ $movement->reference_no ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">İşlem Yapan:</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2" 
                                     style="width: 25px; height: 25px; font-size: 0.75rem;">
                                    {{ strtoupper(substr($movement->user->name ?? 'N', 0, 1)) }}
                                </div>
                                {{ $movement->user->name ?? 'N/A' }}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="detail-card">
                <h6 class="text-success mb-3">
                    <i class="fas fa-box mr-2"></i>
                    Ürün Bilgileri
                </h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="font-weight-bold">Ürün Kodu:</td>
                        <td>{{ $movement->stockCard->code ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Ürün Adı:</td>
                        <td>{{ $movement->stockCard->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Kategori:</td>
                        <td>{{ $movement->stockCard->category ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Birim:</td>
                        <td>{{ $movement->stockCard->unit ?? 'Adet' }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Mevcut Stok:</td>
                        <td>
                            <span class="badge badge-{{ $movement->stockCard->stock_status == 'critical' ? 'danger' : ($movement->stockCard->stock_status == 'low' ? 'warning' : 'success') }}">
                                {{ number_format($movement->stockCard->current_stock ?? 0, 2) }} {{ $movement->stockCard->unit ?? 'Adet' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="detail-card">
                <h6 class="text-warning mb-3">
                    <i class="fas fa-calculator mr-2"></i>
                    Finansal Bilgiler
                </h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="h4 font-weight-bold text-primary">{{ number_format($movement->quantity, 2) }}</div>
                            <div class="small text-muted">Miktar</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="h4 font-weight-bold text-success">₺{{ number_format($movement->unit_price, 2) }}</div>
                            <div class="small text-muted">Birim Fiyat</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="h4 font-weight-bold text-{{ $movement->type == 'in' ? 'success' : 'danger' }}">
                                {{ $movement->type == 'in' ? '+' : '-' }}₺{{ number_format($movement->total_amount, 2) }}
                            </div>
                            <div class="small text-muted">Toplam Tutar</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="h4 font-weight-bold text-info">
                                {{ $movement->type == 'in' ? '+' : '-' }}{{ number_format($movement->quantity, 2) }}
                            </div>
                            <div class="small text-muted">Stok Değişimi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($movement->description)
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="detail-card">
                <h6 class="text-info mb-3">
                    <i class="fas fa-comment mr-2"></i>
                    Açıklama
                </h6>
                <div class="p-3 bg-light rounded">
                    {{ $movement->description }}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.detail-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    height: 100%;
    border: 1px solid #e3e6f0;
}

.movement-details .table td {
    padding: 0.5rem 0;
    border: none;
}

.movement-details .table td:first-child {
    width: 40%;
    color: #5a5c69;
}
</style>