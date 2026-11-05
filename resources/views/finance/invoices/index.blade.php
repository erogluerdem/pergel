@extends('layouts.master')

@section('title')
    Fatura Yönetimi
@endsection

@section('topbar-title')
    Finans
@endsection

@section('content')

<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-2">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="mdi mdi-file-document fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0">{{ $stats['total_invoices'] }}</h4>
                        <p class="mb-0">Toplam Fatura</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="mdi mdi-currency-try fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0">₺{{ number_format($stats['total_amount'], 0, ',', '.') }}</h4>
                        <p class="mb-0 small">Toplam Tutar</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="mdi mdi-check-circle fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0">₺{{ number_format($stats['paid_amount'], 0, ',', '.') }}</h4>
                        <p class="mb-0 small">Tahsil Edilen</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="mdi mdi-clock-outline fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0">₺{{ number_format($stats['outstanding_amount'], 0, ',', '.') }}</h4>
                        <p class="mb-0 small">Bekleyen</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="mdi mdi-alert-circle fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0">{{ $stats['overdue_count'] }}</h4>
                        <p class="mb-0 small">Geciken Fatura</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="mdi mdi-currency-try fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0">₺{{ number_format($stats['overdue_amount'], 0, ',', '.') }}</h4>
                        <p class="mb-0 small">Geciken Tutar</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title mb-0">
                            <i class="mdi mdi-file-document-multiple me-2"></i>
                            Fatura Yönetimi
                        </h4>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex gap-2">
                            <button class="btn btn-info">
                                <i class="mdi mdi-download me-1"></i>
                                Excel Export
                            </button>
                            <a href="{{ route('finance.invoices.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus me-1"></i>
                                Yeni Fatura
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-12">
                        <form method="GET">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">Tüm Durumlar</option>
                                        <option value="pending">Bekleyen</option>
                                        <option value="paid">Ödenen</option>
                                        <option value="partially_paid">Kısmi Ödenen</option>
                                        <option value="overdue">Geciken</option>
                                        <option value="cancelled">İptal</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="customer" class="form-control"
                                           placeholder="Müşteri ara...">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_from" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_to" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="mdi mdi-filter me-1"></i>
                                            Filtrele
                                        </button>
                                        <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary">
                                            <i class="mdi mdi-refresh me-1"></i>
                                            Temizle
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Invoices Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Fatura No</th>
                                <th>Müşteri</th>
                                <th>Tarih</th>
                                <th>Vade Tarihi</th>
                                <th>Tutar</th>
                                <th>Ödenen</th>
                                <th>Kalan</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td>
                                        <strong>{{ $invoice->no }}</strong>
                                        <br><small class="text-muted">{{ $invoice->description }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $invoice->customer_name }}</strong>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->date)->format('d.m.Y') }}</td>
                                    <td>
                                        <span class="{{ \Carbon\Carbon::parse($invoice->due_date) < now() && $invoice->status != 'paid' ? 'text-danger' : '' }}">
                                            {{ \Carbon\Carbon::parse($invoice->due_date)->format('d.m.Y') }}
                                        </span>
                                        @if(\Carbon\Carbon::parse($invoice->due_date) < now() && $invoice->status != 'paid')
                                            <br><small class="text-danger">Gecikmiş</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>₺{{ number_format($invoice->amount, 2, ',', '.') }}</strong>
                                        <br><small class="text-muted">KDV: ₺{{ number_format($invoice->tax_amount, 2, ',', '.') }}</small>
                                    </td>
                                    <td>
                                        <span class="text-success">₺{{ number_format($invoice->paid_amount, 2, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        @if($invoice->balance > 0)
                                            <span class="text-warning">₺{{ number_format($invoice->balance, 2, ',', '.') }}</span>
                                        @else
                                            <span class="text-success">₺0,00</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($invoice->status)
                                            @case('paid')
                                                <span class="badge bg-success">Ödendi</span>
                                                @break
                                            @case('partially_paid')
                                                <span class="badge bg-warning">Kısmi Ödendi</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-secondary">Bekliyor</span>
                                                @break
                                            @case('overdue')
                                                <span class="badge bg-danger">Gecikmiş</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-dark">İptal</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('finance.invoices.show', $invoice->id) }}">
                                                        <i class="mdi mdi-eye me-2"></i>
                                                        Detay
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('finance.invoices.edit', $invoice->id) }}">
                                                        <i class="mdi mdi-pencil me-2"></i>
                                                        Düzenle
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('finance.invoices.print', $invoice->id) }}" target="_blank">
                                                        <i class="mdi mdi-printer me-2"></i>
                                                        Yazdır
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('finance.invoices.email.form', $invoice->id) }}">
                                                        <i class="mdi mdi-email me-2"></i>
                                                        E-posta Gönder
                                                    </a>
                                                </li>
                                                @if($invoice->status != 'paid')
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-success" href="{{ route('finance.invoices.payment.form', $invoice->id) }}">
                                                            <i class="mdi mdi-cash me-2"></i>
                                                            Ödeme Kaydet
                                                        </a>
                                                    </li>
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" action="{{ route('finance.invoices.destroy', $invoice->id) }}" class="js-confirm-delete">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="mdi mdi-delete me-2"></i>
                                                            Sil
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-0">
                                    Toplam {{ $stats['total_invoices'] }} fatura gösteriliyor
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Toplam Tutar:</span>
                                    <strong>₺{{ number_format($stats['total_amount'], 2, ',', '.') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tahsil Edilen:</span>
                                    <span class="text-success">₺{{ number_format($stats['paid_amount'], 2, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-0">
                                    <span>Kalan Bakiye:</span>
                                    <span class="text-warning">₺{{ number_format($stats['outstanding_amount'], 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh every 30 seconds
    setInterval(function() {
        // Check if any new invoices or status changes
        fetch('/finance/invoices/check-updates')
            .then(response => response.json())
            .then(data => {
                if (data.hasUpdates) {
                    // Show notification toast
                    showNotificationToast('Fatura durumlarında değişiklik var', 'info');
                }
            })
            .catch(error => {
                console.error('Error checking updates:', error);
            });
    }, 30000);
});

function showNotificationToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="mdi mdi-${type === 'success' ? 'check-circle' : 'information'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        if (toast.parentNode) {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 150);
        }
    }, 3000);
}
</script>
<script>
// Attach SweetAlert2 for delete dropdowns
(function(){
    function ensureSweetAlert(cb){ if(window.Swal) return cb(); var s=document.createElement('script'); s.src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js'; s.onload=cb; document.head.appendChild(s); }
    ensureSweetAlert(function(){
        document.querySelectorAll('form.js-confirm-delete').forEach(function(form){
            form.addEventListener('submit', function(e){
                e.preventDefault();
                var title = form.closest('tr')?.querySelector('strong')?.innerText || 'bu faturayı';
                Swal.fire({ title: 'Faturayı silmek istediğinize emin misiniz?', html: '<strong>'+title+'</strong><br>Bu işlem geri alınamaz.', icon:'warning', showCancelButton:true, confirmButtonText:'Evet, sil', cancelButtonText:'İptal' }).then(function(r){ if(r.isConfirmed) form.submit(); });
            });
        });
    });
})();
</script>
@endsection
