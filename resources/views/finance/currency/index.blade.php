@extends('layouts.master')

@section('title', 'Döviz Yönetimi')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-money-bill-wave text-success"></i>
                Döviz Yönetimi
            </h2>
            <small class="text-muted">Para birimleri arası dönüştürme ve kur yönetimi</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('finance.currency.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Kur Ekle
            </a>
        </div>
    </div>

    <!-- Currency Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Kayıtlı Para Birimleri</p>
                            <h3 class="mb-0">{{ $stats['total_currencies'] ?? 0 }}</h3>
                        </div>
                        <div class="icon-bg bg-success rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-coins text-white" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Toplam Kurlar</p>
                            <h3 class="mb-0">{{ $stats['total_rates'] ?? 0 }}</h3>
                        </div>
                        <div class="icon-bg bg-primary rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-exchange-alt text-white" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Son Güncelleme</p>
                            <h3 class="mb-0 small">
                                @if($lastUpdate)
                                    {{ $lastUpdate->diffForHumans() }}
                                @else
                                    Belirsiz
                                @endif
                            </h3>
                        </div>
                        <div class="icon-bg bg-info rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-sync-alt text-white" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Kaynak Dağılımı</p>
                            <small>
                                <span class="badge bg-warning">Manual: {{ $stats['manual_count'] ?? 0 }}</span>
                                <span class="badge bg-info">API: {{ $stats['api_count'] ?? 0 }}</span>
                            </small>
                        </div>
                        <div class="icon-bg bg-warning rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-database text-white" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Currency Rates Table -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <div class="row">
                        <div class="col">
                            <h5 class="mb-0">Döviz Kurları</h5>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-sm btn-outline-primary" onclick="location.reload();">
                                <i class="fas fa-sync"></i> Yenile
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($rates && $rates->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr class="table-light">
                                        <th>Kaynak Para</th>
                                        <th>Hedef Para</th>
                                        <th>Oran</th>
                                        <th>Kaynak</th>
                                        <th>Tarih</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rates as $rate)
                                        <tr>
                                            <td class="fw-500">
                                                <span class="badge bg-light text-dark" style="font-size: 12px;">
                                                    {{ $rate->from_currency }}
                                                </span>
                                            </td>
                                            <td class="fw-500">
                                                <span class="badge bg-primary" style="font-size: 12px;">
                                                    {{ $rate->to_currency }}
                                                </span>
                                            </td>
                                            <td class="fw-500">
                                                {{ number_format($rate->rate, 6, ',', '.') }}
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $rate->source === 'manual' ? 'warning' : 'info' }}">
                                                    {{ ucfirst($rate->source) }}
                                                </span>
                                            </td>
                                            <td class="small">
                                                {{ $rate->effective_date->format('d.m.Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('finance.currency.show', $rate) }}" class="btn btn-outline-primary" title="Görüntüle">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('finance.currency.edit', $rate) }}" class="btn btn-outline-primary" title="Düzenle">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('finance.currency.destroy', $rate) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Sil" onclick="return confirm('Silmek istediğinize emin misiniz?');">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> Döviz kuru kaydı bulunmamaktadır.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Tools -->
        <div class="col-lg-4">
            <!-- Converter Tool -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Döviz Dönüştürücü</h5>
                </div>
                <div class="card-body">
                    <form id="converterForm">
                        <div class="mb-3">
                            <label class="form-label">Tutar</label>
                            <input type="number" class="form-control" id="amount" placeholder="0.00" step="0.01" min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kaynak Para</label>
                            <select class="form-select" id="fromCurrency">
                                <option value="">Seçiniz</option>
                                <option value="TRY">₺ Türk Lirası</option>
                                <option value="USD">$ ABD Doları</option>
                                <option value="EUR">€ Euro</option>
                                <option value="GBP">£ İngiliz Sterlini</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hedef Para</label>
                            <select class="form-select" id="toCurrency">
                                <option value="">Seçiniz</option>
                                <option value="TRY">₺ Türk Lirası</option>
                                <option value="USD">$ ABD Doları</option>
                                <option value="EUR">€ Euro</option>
                                <option value="GBP">£ İngiliz Sterlini</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary w-100" onclick="convertCurrency()">
                            <i class="fas fa-exchange-alt"></i> Dönüştür
                        </button>
                    </form>
                    <div id="conversionResult" class="mt-3" style="display: none;">
                        <div class="alert alert-success">
                            <small>
                                <span id="resultAmount"></span> <span id="resultCurrency"></span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">İşlemler</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('finance.currency.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Yeni Kur Ekle
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Döviz Yönetimi Hakkında</h6>
                    <p class="small mb-0">
                        Bu modül, multi-para projelerinde kullanılacak döviz kurlarını yönetir. 
                        Kurları manuel giriş veya API entegrasyonu ile güncelleyebilirsiniz.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function convertCurrency() {
        const amount = document.getElementById('amount').value;
        const from = document.getElementById('fromCurrency').value;
        const to = document.getElementById('toCurrency').value;

        if (!amount || !from || !to) {
            alert('Lütfen tüm alanları doldurunuz');
            return;
        }

        // API çağrısı yapılacak
        // Şimdilik placeholder olarak kalabilir
        alert('Dönüştürme işlemi yapılacak');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Currency management specific scripts
    });
</script>
@endsection
