@extends('layouts.master')

@section('title', 'Hakediş Onay Detayı')

@section('topbar-title', 'Taşeron Yönetimi')

@section('css')
<style>
    .workflow-step {
        position: relative;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }
    .workflow-step.active {
        border-color: #007bff;
        background-color: #f8f9fa;
    }
    .workflow-step.completed {
        border-color: #28a745;
        background-color: #d4edda;
    }
    .workflow-step.rejected {
        border-color: #dc3545;
        background-color: #f8d7da;
    }
    .comparison-table th, .comparison-table td {
        padding: 8px 12px;
        text-align: center;
    }
    .quantity-input {
        width: 80px;
        text-align: center;
    }
    .approval-actions {
        position: sticky;
        bottom: 0;
        background: white;
        border-top: 1px solid #dee2e6;
        padding: 15px 0;
        margin-top: 20px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Sayfa Başlığı -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Hakediş Onay Detayı</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/">Ana Sayfa</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('subcontractor.companies') }}">Taşeron Yönetimi</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('subcontractor.progress.approval') }}">Hakediş Onayları</a></li>
                        <li class="breadcrumb-item active">{{ $claim->claim_number }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Hakediş Bilgileri -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Hakediş Bilgileri</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Hakediş Numarası:</label>
                                <p class="mb-0">{{ $claim->claim_number }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Proje:</label>
                                <p class="mb-0">{{ $claim->project->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Taşeron Firma:</label>
                                <p class="mb-0">{{ $claim->subcontractorContract->subcontractor->company_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Sözleşme Numarası:</label>
                                <p class="mb-0">{{ $claim->subcontractorContract->contract_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Gönderim Tarihi:</label>
                                <p class="mb-0">
                                    @if($claim->submitted_at)
                                        {{ $claim->submitted_at->format('d.m.Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Toplam Tutar:</label>
                                <p class="mb-0 fw-bold text-primary">{{ number_format($claim->total_amount, 0, ',', '.') }} TL</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Onay Süreci -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Onay Süreci</h4>
                </div>
                <div class="card-body">
                    <div class="workflow-step active">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="mdi mdi-account-check font-size-24 text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Saha Onayı</h6>
                                <p class="mb-0 text-muted">Mühendis tarafından inceleniyor</p>
                            </div>
                        </div>
                    </div>

                    <div class="workflow-step">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="mdi mdi-account-tie font-size-24 text-muted"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Proje Yöneticisi Onayı</h6>
                                <p class="mb-0 text-muted">Bekleniyor</p>
                            </div>
                        </div>
                    </div>

                    <div class="workflow-step">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="mdi mdi-office-building font-size-24 text-muted"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Muhasebe Onayı</h6>
                                <p class="mb-0 text-muted">Bekleniyor</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kalem Karşılaştırması -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Kalem Karşılaştırması</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered comparison-table">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="align-middle">Kalem</th>
                                    <th rowspan="2" class="align-middle">Birim</th>
                                    <th colspan="3" class="text-center">Sözleşmeye Göre</th>
                                    <th colspan="3" class="text-center">Önceki Hakediş</th>
                                    <th colspan="3" class="text-center">Bu Hakediş</th>
                                    <th colspan="3" class="text-center">Onaylanan</th>
                                </tr>
                                <tr>
                                    <th>Miktar</th>
                                    <th>Birim Fiyat</th>
                                    <th>Toplam</th>
                                    <th>Miktar</th>
                                    <th>Birim Fiyat</th>
                                    <th>Toplam</th>
                                    <th>Miktar</th>
                                    <th>Birim Fiyat</th>
                                    <th>Toplam</th>
                                    <th>Miktar</th>
                                    <th>Birim Fiyat</th>
                                    <th>Toplam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($claim->items as $item)
                                <tr>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->unit }}</td>
                                    <td>{{ number_format($item->contract_quantity, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->contract_quantity * $item->unit_price, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->previous_quantity ?? 0, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                    <td>{{ number_format(($item->previous_quantity ?? 0) * $item->unit_price, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->current_quantity, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->current_quantity * $item->unit_price, 2, ',', '.') }}</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm quantity-input"
                                               name="approved_quantity[{{ $item->id }}]"
                                               value="{{ $item->current_quantity }}"
                                               step="0.01" min="0">
                                    </td>
                                    <td>{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                    <td class="approved-total">{{ number_format($item->current_quantity * $item->unit_price, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="11" class="text-end">TOPLAM:</th>
                                    <th id="grand-total">{{ number_format($claim->total_amount, 2, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Onay Formu -->
    <form id="approval-form" method="POST" action="{{ route('subcontractor.progress.approval.process', $claim->id) }}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Karar ve Yorum</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="comments" class="form-label">Yorumlar <span class="text-muted">(İsteğe bağlı)</span></label>
                                    <textarea class="form-control" id="comments" name="comments" rows="4"
                                              placeholder="Onay/red kararı ile ilgili açıklamalarınızı yazın..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Karar</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="action" id="approve" value="approved" checked>
                                        <label class="form-check-label" for="approve">
                                            <span class="badge bg-success">Onayla</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="action" id="reject" value="rejected">
                                        <label class="form-check-label" for="reject">
                                            <span class="badge bg-danger">Reddet</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="action" id="revision" value="revision_requested">
                                        <label class="form-check-label" for="revision">
                                            <span class="badge bg-warning">Düzeltme İste</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Onay İşlemleri -->
        <div class="approval-actions">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('subcontractor.progress.approval') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Geri Dön
                                </a>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" onclick="calculateTotals()">
                                    <i class="mdi mdi-calculator"></i> Hesapla
                                </button>
                                <button type="submit" class="btn btn-success me-2" name="action" value="approved">
                                    <i class="mdi mdi-check"></i> Onayla
                                </button>
                                <button type="submit" class="btn btn-danger me-2" name="action" value="rejected">
                                    <i class="mdi mdi-close"></i> Reddet
                                </button>
                                <button type="submit" class="btn btn-warning" name="action" value="revision_requested">
                                    <i class="mdi mdi-pencil"></i> Düzeltme İste
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // Miktar değişikliğinde otomatik hesaplama
    $('.quantity-input').on('input', function() {
        calculateTotals();
    });

    // Form gönderimi öncesi validasyon
    $('#approval-form').on('submit', function(e) {
        const approvedQuantities = {};
        let hasChanges = false;

        $('.quantity-input').each(function() {
            const itemId = $(this).attr('name').match(/\[(\d+)\]/)[1];
            const approvedQty = parseFloat($(this).val()) || 0;
            const claimedQty = parseFloat($(this).data('original') || $(this).val());

            approvedQuantities[itemId] = approvedQty;

            if (approvedQty !== claimedQty) {
                hasChanges = true;
            }
        });

        // Gizli input olarak ekle
        if (Object.keys(approvedQuantities).length > 0) {
            $('<input>').attr({
                type: 'hidden',
                name: 'approved_quantities',
                value: JSON.stringify(approvedQuantities)
            }).appendTo($(this));
        }

        // Onay uyarısı
        const action = $('input[name="action"]:checked').val();
        if (action === 'rejected' && !confirm('Bu hakedişi reddetmek istediğinizden emin misiniz?')) {
            e.preventDefault();
            return false;
        }

        if (action === 'revision_requested' && !confirm('Düzeltme isteği göndermek istediğinizden emin misiniz?')) {
            e.preventDefault();
            return false;
        }
    });
});

function calculateTotals() {
    let grandTotal = 0;

    $('.quantity-input').each(function() {
        const row = $(this).closest('tr');
        const unitPrice = parseFloat(row.find('td').eq(9).text().replace(/\./g, '').replace(',', '.')) || 0;
        const approvedQty = parseFloat($(this).val()) || 0;
        const total = approvedQty * unitPrice;

        row.find('.approved-total').text(total.toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        grandTotal += total;
    });

    $('#grand-total').text(grandTotal.toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
}
</script>
@endsection
