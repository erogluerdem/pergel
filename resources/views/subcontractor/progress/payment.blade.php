@extends('layouts.master')

@section('topbar-title', 'Taşeron Yönetimi')

@section('title', 'Ödeme Talimatları')

@section('content')
<div class="container-fluid">
    <!-- Özet Kartları -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Ödeme Bekleyen Toplam</h5>
                    <h3>{{ number_format($totalPending, 0, ',', '.') }} TL</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Bu Hafta Vadesi Gelen</h5>
                    <h3>{{ number_format($thisWeekDue, 0, ',', '.') }} TL</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Vadesi Geçmiş</h5>
                    <h3>{{ $overdue }} adet</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Ana Panel -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Ödeme Bekleyenler</h4>
                    <button class="btn btn-success" id="bulkPaymentBtn" disabled>
                        <i class="fas fa-money-bill-wave"></i> Seçilenleri Ödemeye Gönder
                    </button>
                </div>
                <div class="card-body">
                    @if($pendingPayments->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5>Ödeme bekleyen talimat bulunmuyor</h5>
                            <p class="text-muted">Tüm ödemeler güncel durumda.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Alıcı Firma</th>
                                        <th>Kaynak Belge No</th>
                                        <th>Vade Tarihi</th>
                                        <th>Ödenecek Tutar</th>
                                        <th>Proje</th>
                                        <th>Öncelik</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingPayments as $payment)
                                    <tr>
                                        <td><input type="checkbox" class="payment-checkbox" value="{{ $payment['id'] }}"></td>
                                        <td>{{ $payment['payee'] }}</td>
                                        <td>{{ $payment['reference_no'] }}</td>
                                        <td>{{ $payment['due_date'] }}</td>
                                        <td>{{ $payment['amount'] }}</td>
                                        <td>{{ $payment['project'] }}</td>
                                        <td>{{ $payment['priority'] }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewDetails({{ $payment['id'] }})">
                                                <i class="fas fa-eye"></i> Detay
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toplu Ödeme Modal -->
<div class="modal fade" id="bulkPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Toplu Ödeme Talimatı Oluştur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="paymentSummary"></div>
                <form id="bulkPaymentForm">
                    <div class="mb-3">
                        <label class="form-label">Ödeme Yapılacak Banka Hesabı</label>
                        <select class="form-select" name="bank_account" required>
                            <option value="">Seçiniz...</option>
                            <option value="TR123456789">Garanti Bankası - Ana Hesap</option>
                            <option value="TR987654321">İş Bankası - Proje Hesabı</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ödeme Tarihi</label>
                        <input type="date" class="form-control" name="payment_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Banka işlemlerinde görünecek açıklama"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-success" id="createInstructionsBtn">
                    <i class="fas fa-file-invoice-dollar"></i> EFT/Havale Listesi Oluştur
                </button>
                <button type="button" class="btn btn-primary" id="markAsPaidBtn">
                    <i class="fas fa-check"></i> Manuel Olarak Ödendi İşaretle
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const paymentCheckboxes = document.querySelectorAll('.payment-checkbox');
    const bulkPaymentBtn = document.getElementById('bulkPaymentBtn');
    const bulkPaymentModal = new bootstrap.Modal(document.getElementById('bulkPaymentModal'));

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        paymentCheckboxes.forEach(cb => cb.checked = this.checked);
        updateBulkButton();
    });

    paymentCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkButton);
    });

    function updateBulkButton() {
        const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
        bulkPaymentBtn.disabled = checkedBoxes.length === 0;
    }

    // Bulk payment button
    bulkPaymentBtn.addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.payment-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length > 0) {
            showBulkPaymentModal(selectedIds);
        }
    });

    function showBulkPaymentModal(selectedIds) {
        // Calculate summary
        const selectedPayments = @json($pendingPayments).filter(p => selectedIds.includes(p.id.toString()));
        const totalAmount = selectedPayments.reduce((sum, p) => sum + parseFloat(p.amount.replace(/[.,\sTL]/g, '').replace(',', '.')), 0);

        document.getElementById('paymentSummary').innerHTML = `
            <div class="alert alert-info">
                <strong>Toplam ${selectedIds.length} adet talimat seçildi. Toplam Ödeme Tutarı: ${totalAmount.toLocaleString('tr-TR', {minimumFractionDigits: 2})} TL</strong>
            </div>
        `;

        bulkPaymentModal.show();
    }

    // Create instructions
    document.getElementById('createInstructionsBtn').addEventListener('click', function() {
        // Implementation for creating EFT/Havale list
        alert('EFT/Havale listesi oluşturuluyor... (Demo)');
    });

    // Mark as paid
    document.getElementById('markAsPaidBtn').addEventListener('click', function() {
        // Implementation for marking as paid
        alert('Ödemeler ödendi olarak işaretleniyor... (Demo)');
    });
});

function viewDetails(id) {
    // Implementation for viewing payment details
    alert('Detay sayfası açılıyor... (Demo - ID: ' + id + ')');
}
</script>
@endsection
