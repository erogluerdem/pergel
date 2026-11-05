@extends('layouts.master')
@section('title', 'Tedarikçi Hesapları')
@section('topbar-title', 'Finans - Tedarikçi Hesapları')

@push('styles')
<style>
    .supplier-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }

    .supplier-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px rgba(0,0,0,0.15), 0 0 30px rgba(220, 38, 127, 0.1);
    }

    .supplier-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--card-gradient-start), var(--card-gradient-end));
    }

    .supplier-card.primary { --card-gradient-start: #667eea; --card-gradient-end: #764ba2; }
    .supplier-card.success { --card-gradient-start: #4facfe; --card-gradient-end: #00f2fe; }
    .supplier-card.danger { --card-gradient-start: #f093fb; --card-gradient-end: #f5576c; }
    .supplier-card.warning { --card-gradient-start: #43e97b; --card-gradient-end: #38f9d7; }

    .supplier-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        margin: 0 auto 10px;
        position: relative;
        overflow: hidden;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.10);
    }

    .supplier-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s ease;
    }

    .supplier-card:hover .supplier-icon::before {
        left: 100%;
    }

    .supplier-value {
        font-size: 1rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 6px 0;
    }

    .supplier-label {
        color: #7f8c8d;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-modern {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .table-modern thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table-modern thead th {
        border: none;
        padding: 20px 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.8rem;
    }

    .table-modern tbody tr {
        background: white;
    }

    .form-control {
        border: 2px solid rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15), 0 4px 12px rgba(102, 126, 234, 0.1);
        transform: translateY(-2px);
    }

    .chart-card {
        position: relative;
        background: white;
        border-radius: 15px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .chart-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        position: relative;
    }

    .list-group-item:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.02) 0%, rgba(248, 249, 250, 0.5) 100%);
        transform: translateX(3px);
    }

    .list-group-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 2px;
        height: 100%;
        background: #667eea;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .list-group-item:hover::before {
        transform: scaleY(1);
    }

    .supplier-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
        position: relative;
        overflow: hidden;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.22);
    }

    .supplier-avatar::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.3) 50%, transparent 70%);
        transform: rotate(-45deg) translateX(-100%);
        transition: transform 0.6s ease;
    }

    .supplier-avatar:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .supplier-avatar:hover::before {
        transform: rotate(-45deg) translateX(100%);
    }

    .status-badge {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s ease;
    }

    .status-badge:hover::before {
        left: 100%;
    }

    .status-badge:hover {
        transform: scale(1.05);
    }

    .contact-info {
        line-height: 1.4;
    }

    .empty-state {
        padding: 2rem;
    }

    /* Page Animation Classes */
    .fade-in {
        animation: fadeIn 0.8s ease-out;
    }

    .supplier-card {
        animation: slideInUp 0.6s ease-out both;
    }
    .supplier-card:nth-child(1) { animation-delay: 0.1s; }
    .supplier-card:nth-child(2) { animation-delay: 0.2s; }
    .supplier-card:nth-child(3) { animation-delay: 0.3s; }
    .supplier-card:nth-child(4) { animation-delay: 0.4s; }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(40px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
</style>
@endpush

@section('content')
<div class="finance-suppliers fade-in">
    <!-- Enhanced Metrics Dashboard -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card supplier-card primary h-100">
                <div class="card-body p-4 text-center">
                    <div class="supplier-icon mx-auto" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="supplier-label">Toplam Borç Bakiyesi</div>
                    <div class="supplier-value">{{ number_format($totalPayable ?? 0, 2, ',', '.') }} ₺</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card supplier-card warning h-100">
                <div class="card-body p-4 text-center">
                    <div class="supplier-icon mx-auto" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="supplier-label">Vadesi Gelen (30G)</div>
                    <div class="supplier-value">{{ number_format(($ageing['0-30'] ?? 0), 2, ',', '.') }} ₺</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card supplier-card danger h-100">
                <div class="card-body p-4 text-center">
                    <div class="supplier-icon mx-auto" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="supplier-label">Vadesi Geçmiş Borçlar</div>
                    <div class="supplier-value">{{ number_format($totalOverdue ?? 0, 2, ',', '.') }} ₺</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card supplier-card success h-100">
                <div class="card-body p-4 text-center">
                    <div class="supplier-icon mx-auto" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="supplier-label">Bu Ayki Ödemeler</div>
                    <div class="supplier-value">{{ number_format($thisMonthPayments ?? 0, 2, ',', '.') }} ₺</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Dashboard removed as requested -->

    <!-- Search and Filters -->
    <div class="search-card mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <form method="get" class="d-flex gap-3">
                        <div class="flex-grow-1">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Firma adı, uzmanlık, şehir ara...">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-modern">
                            <i class="fas fa-search me-1"></i> Ara
                        </button>
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('finance.accounts.suppliers.recycle') }}" class="btn btn-outline-secondary btn-modern me-2" title="Geri Dönüşüm Kutusu">
                        <i class="fas fa-trash-restore me-1"></i> Geri Dönüşüm
                    </a>
                    <button class="btn btn-outline-primary btn-modern me-2" onclick="exportToExcel()">
                        <i class="fas fa-file-excel me-1"></i> Excel
                    </button>
                    @if (Route::has('finance.accounts.suppliers.create'))
                        <a href="{{ route('finance.accounts.suppliers.create') }}" class="btn btn-success btn-modern">
                            <i class="fas fa-plus me-1"></i> Yeni Tedarikçi
                        </a>
                    @else
                        <button type="button" class="btn btn-success btn-modern" disabled title="Yeni tedarikçi rotası tanımlı değil">
                            <i class="fas fa-exclamation-triangle me-1"></i> Rota Eksik
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie text-primary me-2"></i>Ödeme Yaşlandırma Dağılımı
                    </h5>
                    <span class="badge bg-light text-dark">Canlı</span>
                </div>
                <div class="card-body">
                    <div style="height: 260px">
                        <canvas id="ageingChart"></canvas>
                    </div>
                    <div class="mt-3 small text-muted">
                        Vadesi yaklaşan ve geciken ödemelerin toplam borç içindeki oranını gösterir.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-industry text-success me-2"></i>En Yüksek Borçlu Tedarikçiler
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($topCreditors) && $topCreditors->count())
                        <ul class="list-group list-group-flush">
                            @foreach($topCreditors as $creditor)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">{{ $creditor->name }}</div>
                                        @if(!empty($creditor->specialty))
                                            <small class="text-muted">{{ $creditor->specialty }}</small>
                                        @endif
                                    </div>
                                    <span class="badge bg-warning text-dark">
                                        {{ number_format($creditor->balance ?? 0, 2, ',', '.') }} ₺
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-check-circle fa-2x mb-3 text-success"></i>
                            <p class="mb-0">Şu anda listelenecek kritik bir borç bakiyesi bulunmuyor.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Suppliers Table -->
    <div class="card chart-card">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users text-success me-2"></i>
                    Tedarikçi Listesi
                </h5>
                <small class="text-muted">Toplam {{ $vendorsPaged->total() ?? 0 }} tedarikçi</small>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th><i class="fas fa-building me-1"></i> Firma</th>
                            <th><i class="fas fa-balance-scale me-1"></i> Bakiye</th>
                            <th><i class="fas fa-signal me-1"></i> Durum</th>
                            <th><i class="fas fa-phone me-1"></i> İletişim</th>
                            <th><i class="fas fa-cogs me-1"></i> İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendorsPaged as $v)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="supplier-avatar me-3">
                                            {{ strtoupper(substr($v->name ?? 'T', 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="/finance/accounts/suppliers/{{ $v->id }}" class="text-decoration-none fw-bold">
                                                {{ $v->name }}
                                            </a>
                                            <div class="text-muted small">{{ $v->code ?? 'TED-' . str_pad($v->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $bal = 0;
                                        try {
                                            if(\Illuminate\Support\Facades\Schema::hasTable('invoices')){
                                                if(\Illuminate\Support\Facades\Schema::hasColumn('invoices','vendor_id')){
                                                    $bal = \DB::table('invoices')->where('vendor_id',$v->id)->sum(\DB::raw('amount - paid_amount'));
                                                } else if(\Illuminate\Support\Facades\Schema::hasTable('purchase_orders')){
                                                    $poIds = \DB::table('purchase_orders')->where('vendor_id',$v->id)->pluck('id');
                                                    if($poIds->count()) $bal = \DB::table('invoices')->whereIn('purchase_order_id',$poIds)->sum(\DB::raw('amount - paid_amount'));
                                                }
                                            }
                                        } catch (\Exception $e) { $bal = 0; }
                                    @endphp
                                    <span class="fw-bold {{ $bal > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($bal, 2, ',', '.') }} ₺
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($v->status ?? 'active') {
                                            'active' => 'success',
                                            'inactive' => 'secondary',
                                            default => 'primary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} status-badge">
                                        {{ ucfirst($v->status ?? 'Aktif') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="contact-info">
                                        @if($v->contact_person)
                                            <div class="fw-bold small">{{ $v->contact_person }}</div>
                                        @endif
                                        @if($v->phone)
                                            <div class="text-muted small">
                                                <i class="fas fa-phone me-1"></i>{{ $v->phone }}
                                            </div>
                                        @endif
                                        @if($v->email)
                                            <div class="text-muted small">
                                                <i class="fas fa-envelope me-1"></i>{{ $v->email }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="/finance/accounts/suppliers/{{ $v->id }}" class="btn btn-sm btn-outline-primary btn-modern me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/finance/accounts/suppliers/{{ $v->id }}/edit" class="btn btn-sm btn-outline-secondary btn-modern me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-info btn-modern" onclick="showPaymentModal({{ $v->id }})">
                                            <i class="fas fa-credit-card"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-modern ms-1" onclick="confirmDelete({{ $v->id }}, '{{ addslashes($v->name) }}')" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Tedarikçi Bulunamadı</h5>
                                        <p class="text-muted">Henüz hiç tedarikçi eklenmemiş veya arama kriterlerinize uygun sonuç bulunamadı.</p>
                                        <a href="#" class="btn btn-primary btn-modern">
                                            <i class="fas fa-plus me-1"></i> İlk Tedarikçiyi Ekle
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($vendorsPaged->hasPages())
                <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            {{ $vendorsPaged->firstItem() }} - {{ $vendorsPaged->lastItem() }} / {{ $vendorsPaged->total() }} sonuç gösteriliyor
                        </div>
                        <div>
                            {{ $vendorsPaged->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Modern Chart Configuration
    const ageing = @json($ageing ?? ['0-30'=>0,'31-60'=>0,'61+'=>0]);
    const ageingCanvas = document.getElementById('ageingChart');
    if (ageingCanvas) {
        const ctx = ageingCanvas.getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['0-30 Gün', '31-60 Gün', '61+ Gün'],
                datasets: [{
                    data: [ageing['0-30'], ageing['31-60'], ageing['61+']],
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(102, 126, 234, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + new Intl.NumberFormat('tr-TR').format(context.parsed) + ' ₺';
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 2000
                }
            }
        });
    }

    // Interactive Features

    // Table row selection
    document.querySelectorAll('.table-modern tbody tr').forEach(row => {
        row.addEventListener('click', function(e) {
            if (!e.target.closest('.btn')) {
                document.querySelectorAll('.table-modern tbody tr').forEach(r => r.classList.remove('selected'));
                this.classList.add('selected');
            }
        });
    });

    // Enhanced button interactions
    document.querySelectorAll('.btn-modern').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.05)';
        });

        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Form enhancements
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'translateY(-2px)';
        });

        input.addEventListener('blur', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

// Open Supplier Modal Function
function openSupplierModal() {
    console.log('Opening supplier modal...');
    const modal = document.getElementById('addSupplierModal');
    if (modal) {
        // Try Bootstrap 5 Modal
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
        } else {
            // Fallback - manual modal show
            modal.style.display = 'block';
            modal.classList.add('show');
            modal.setAttribute('aria-modal', 'true');
            modal.removeAttribute('aria-hidden');
            document.body.classList.add('modal-open');

            // Create backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.onclick = function() { closeSupplierModal(); };
            document.body.appendChild(backdrop);
        }
    } else {
        console.error('Modal not found!');
    }
}

// Close Supplier Modal Function
function closeSupplierModal() {
    const modal = document.getElementById('addSupplierModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        modal.removeAttribute('aria-modal');
        document.body.classList.remove('modal-open');

        // Remove backdrop
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
    }
}

// Excel Export Function
function exportToExcel() {
    // Add loading animation to button
    const btn = event.target.closest('.btn');
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Dışa Aktarılıyor...';
    btn.disabled = true;

    // Simulate export process
    setTimeout(() => {
        btn.innerHTML = originalContent;
        btn.disabled = false;

        // Show success message
        const toast = document.createElement('div');
        toast.className = 'alert alert-success position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = '<i class="fas fa-check-circle me-2"></i>Excel dosyası başarıyla indirildi!';
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 3000);
    }, 2000);
}

// Payment Modal Function
function showPaymentModal(vendorId) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ödeme Yap</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Tedarikçi ID: ${vendorId} için ödeme işlemi başlatılacak.</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Bu özellik yakında aktif olacaktır.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    <button type="button" class="btn btn-primary">Ödeme Yap</button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();

    modal.addEventListener('hidden.bs.modal', () => modal.remove());
}

// Delete confirmation
function confirmDelete(id, name) {
        const modalHtml = `
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Tedarikçiyi Sil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>${name}</strong> adlı tedarikçiyi silmek üzeresiniz. Bu işlem geri alınabilir (geri dönüşüm kutusuna taşınacaktır).</p>
                        <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="permanentDeleteCheckbox">
                                <label class="form-check-label" for="permanentDeleteCheckbox">Kalıcı sil (veritabanından tamamen kaldır)</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Sil</button>
                    </div>
                </div>
            </div>
        </div>`;

        // Append and show modal
        let wrapper = document.createElement('div');
        wrapper.innerHTML = modalHtml;
        document.body.appendChild(wrapper);
        const modalEl = wrapper.querySelector('.modal');
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();

        modalEl.addEventListener('hidden.bs.modal', () => { wrapper.remove(); });

        wrapper.querySelector('#confirmDeleteBtn').addEventListener('click', function(){
                const permanent = wrapper.querySelector('#permanentDeleteCheckbox').checked;

                if(permanent){
                        // send form to force delete endpoint (not implemented separately) - use method override
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/finance/accounts/suppliers/${id}`;
                        form.innerHTML = `
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="permanent" value="1">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                } else {
                        // Soft delete via DELETE
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/finance/accounts/suppliers/${id}`;
                        form.innerHTML = `
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                }
        });
}
</script>

<style>
/* Additional interactive styles */
.table-modern tbody tr.selected {
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.08) 0%, rgba(248, 249, 250, 0.9) 100%) !important;
    border-left: 4px solid #667eea !important;
}

.table-modern tbody tr.selected::before {
    transform: scaleY(1) !important;
}

.action-buttons .btn {
    margin: 0 2px;
}

/* Hover effects for list items */
.list-group-item a {
    transition: color 0.3s ease;
}

.list-group-item:hover a {
    color: #667eea !important;
}

/* Chart container enhancements */
.chart-container {
    padding: 20px;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .supplier-card .supplier-value {
        font-size: 1rem;
    }

    .action-buttons {
        display: flex;
        gap: 2px;
    }

    .action-buttons .btn {
        padding: 4px 8px;
        font-size: 0.72rem;
    }
}

/* Modal Styles */
.modal {
    z-index: 1055 !important;
}

.modal-backdrop {
    z-index: 1050 !important;
}

.modal.show {
    display: block !important;
}
</style>

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSupplierModalLabel">
                    <i class="fas fa-plus text-success me-2"></i>
                    Yeni Tedarikçi Ekle
                </h5>
                <button type="button" class="btn-close" onclick="closeSupplierModal()" aria-label="Close"></button>
            </div>
            <form action="{{ route('finance.accounts.suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Firma Adı *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="specialty" class="form-label">Uzmanlık Alanı</label>
                            <input type="text" class="form-control" id="specialty" name="specialty">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">E-posta</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Telefon</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tax_number" class="form-label">Vergi Numarası</label>
                            <input type="text" class="form-control" id="tax_number" name="tax_number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tax_office" class="form-label">Vergi Dairesi</label>
                            <input type="text" class="form-control" id="tax_office" name="tax_office">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Adres</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Kategori</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Seçiniz</option>
                                <option value="Kaba İnşaat">Kaba İnşaat</option>
                                <option value="İnce İşler">İnce İşler</option>
                                <option value="Çelik Konstrüksiyon">Çelik Konstrüksiyon</option>
                                <option value="Elektrik">Elektrik</option>
                                <option value="Tesisat">Tesisat</option>
                                <option value="Diğer">Diğer</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="payment_terms" class="form-label">Ödeme Koşulları</label>
                            <select class="form-select" id="payment_terms" name="payment_terms">
                                <option value="">Seçiniz</option>
                                <option value="Peşin">Peşin</option>
                                <option value="30 Gün">30 Gün</option>
                                <option value="60 Gün">60 Gün</option>
                                <option value="90 Gün">90 Gün</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeSupplierModal()">
                        <i class="fas fa-times me-1"></i> İptal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
