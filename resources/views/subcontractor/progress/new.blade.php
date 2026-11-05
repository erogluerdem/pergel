@extends('layouts.master')

@section('title', 'Yeni Hakediş Talebi')

@section('topbar-title', 'Taşeron Yönetimi')

@push('styles')
<style>
.wizard-progress .nav-pills .nav-link {
    border-radius: 0.375rem;
    margin: 0 2px;
    background: #f8f9fa;
    color: #6c757d;
    border: 1px solid #dee2e6;
}

.wizard-progress .nav-pills .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
}

.wizard-progress .nav-pills .nav-link .number {
    display: inline-block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    text-align: center;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    margin-right: 8px;
    font-weight: bold;
}

.wizard-progress .nav-pills .nav-link.active .number {
    background: rgba(255,255,255,0.3);
}

.tab-content {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 20px;
    margin-top: 20px;
}

.table-responsive {
    border-radius: 0.375rem;
    overflow: hidden;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

.btn-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border: none;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e86fc7 0%, #e74c5a 100%);
}

.btn-success {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #3a9be6 0%, #00e6e6 100%);
}
</style>
@endpush

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="card-title mb-0">
                            <i class="mdi mdi-file-document-plus me-2"></i>
                            Yeni Hakediş Talebi Oluştur
                        </h4>
                    </div>
                    <div class="card-body">
                    <!-- Wizard Progress -->
                    <div class="wizard-progress mb-4">
                        <ul class="nav nav-pills nav-justified">
                            <li class="nav-item">
                                <a class="nav-link active" id="step1-tab" data-bs-toggle="pill" href="#step1">
                                    <span class="number">1</span> Temel Bilgiler
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="step2-tab" data-bs-toggle="pill" href="#step2">
                                    <span class="number">2</span> İmalat İlerleme
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="step3-tab" data-bs-toggle="pill" href="#step3">
                                    <span class="number">3</span> Destekleyici Dokümanlar
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="step4-tab" data-bs-toggle="pill" href="#step4">
                                    <span class="number">4</span> Finansal Özet
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="step5-tab" data-bs-toggle="pill" href="#step5">
                                    <span class="number">5</span> Gönderim
                                </a>
                            </li>
                        </ul>
                    </div>

                    <form id="progressClaimForm" method="POST" action="{{ route('subcontractor.progress.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Step 1: Temel Bilgiler -->
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="step1">
                                <h5>Adım 1: Temel Bilgiler ve Sözleşme Seçimi</h5>
                                <div class="row">
                                        <div class="form-group mb-3">
                                            <label for="project_id">Proje Seçimi *</label>
                                            <select class="form-select" id="project_id" name="project_id" required>
                                                <option value="">Proje seçiniz...</option>
                                                @foreach($projects as $project)
                                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="subcontractor_contract_id">Taşeron Sözleşmesi *</label>
                                            <select class="form-select" id="subcontractor_contract_id" name="subcontractor_contract_id" required>
                                                <option value="">Önce proje seçiniz...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="period_start">Hakediş Dönemi Başlangıç *</label>
                                            <input type="date" class="form-control" id="period_start" name="period_start" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="period_end">Hakediş Dönemi Bitiş *</label>
                                            <input type="date" class="form-control" id="period_end" name="period_end" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="claim_number">Hakediş Numarası</label>
                                            <input type="text" class="form-control" id="claim_number" name="claim_number" readonly>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary next-step">İleri</button>
                            </div>

                            <!-- Step 2: İmalat İlerleme Tablosu -->
                            <div class="tab-pane fade" id="step2">
                                <h5>Adım 2: İmalat İlerleme Tablosu</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="progressTable">
                                        <thead>
                                            <tr>
                                                <th>Poz No</th>
                                                <th>Açıklama</th>
                                                <th>Birim</th>
                                                <th>Sözleşme Miktarı</th>
                                                <th>Önceki Toplam</th>
                                                <th>Bu Dönem Yapılan</th>
                                                <th>Kümülatif Toplam</th>
                                                <th>Birim Fiyat</th>
                                                <th>Bu Dönem Tutarı</th>
                                            </tr>
                                        </thead>
                                        <tbody id="progressTableBody">
                                            <!-- Dinamik olarak doldurulacak -->
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-secondary prev-step">Geri</button>
                                <button type="button" class="btn btn-primary next-step">İleri</button>
                            </div>

                            <!-- Step 3: Destekleyici Dokümanlar -->
                            <div class="tab-pane fade" id="step3">
                                <h5>Adım 3: Destekleyici Dokümanlar ve Kanıtlar</h5>
                                <p class="text-muted">Lütfen her iş kalemi için yapılan imalatları gösteren en az bir fotoğraf ekleyiniz.</p>
                                <div class="mb-3">
                                    <label for="documents">Dosyalar</label>
                                    <input type="file" class="form-control" id="documents" name="documents[]" multiple accept="image/*,.pdf,.doc,.docx">
                                    <div class="form-text">Birden fazla dosya seçebilirsiniz (Fotoğraf, PDF, DOC)</div>
                                </div>
                                <div id="fileList" class="mt-3"></div>
                                <button type="button" class="btn btn-secondary prev-step">Geri</button>
                                <button type="button" class="btn btn-primary next-step">İleri</button>
                            </div>

                            <!-- Step 4: Finansal Özet -->
                            <div class="tab-pane fade" id="step4">
                                <h5>Adım 4: Finansal Özet ve Onay</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody id="financialSummary">
                                            <tr>
                                                <td>Toplam İmalat Tutarı (Bu Dönem)</td>
                                                <td id="totalCurrentAmount">0.00 TL</td>
                                            </tr>
                                            <tr>
                                                <td>(-) Teminat Kesintisi (%10)</td>
                                                <td id="withholdingAmount">0.00 TL</td>
                                            </tr>
                                            <tr>
                                                <td>(-) Avans Mahsubu</td>
                                                <td id="advanceDeduction">0.00 TL</td>
                                            </tr>
                                            <tr>
                                                <td>Ara Toplam</td>
                                                <td id="subtotal">0.00 TL</td>
                                            </tr>
                                            <tr>
                                                <td>(+) Katma Değer Vergisi (KDV %20)</td>
                                                <td id="vatAmount">0.00 TL</td>
                                            </tr>
                                            <tr class="font-weight-bold">
                                                <td>TOPLAM ÖDENECEK TUTAR</td>
                                                <td id="netAmount">0.00 TL</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-secondary prev-step">Geri</button>
                                <button type="button" class="btn btn-primary next-step">İleri</button>
                            </div>

                            <!-- Step 5: Gönderim -->
                            <div class="tab-pane fade" id="step5">
                                <h5>Adım 5: Gönderim</h5>
                                <div class="alert alert-info">
                                    <strong>Bilgi:</strong> Hakediş talebinizi gönderdikten sonra onay sürecine girecektir.
                                </div>
                                <div class="mb-3">
                                    <label for="notes">Notlar</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="İsteğe bağlı notlar..."></textarea>
                                </div>
                                <button type="button" class="btn btn-secondary prev-step">Geri</button>
                                <button type="button" class="btn btn-warning" id="saveDraft">Taslak Olarak Kaydet</button>
                                <button type="submit" class="btn btn-success" id="submitClaim">Hakedişi Onaya Gönder</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    let currentStep = 1;
    const totalSteps = 5;

    // Proje seçildiğinde sözleşmeleri yükle
    $('#project_id').change(function() {
        const projectId = $(this).val();
        if (projectId) {
            loadContracts(projectId);
        }
    });

    // Sözleşme seçildiğinde claim number ve items yükle
    $('#subcontractor_contract_id').change(function() {
        const contractId = $(this).val();
        if (contractId) {
            generateClaimNumber(contractId);
            loadContractItems(contractId);
        }
    });

    // Wizard navigation
    $('.next-step').click(function() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    });

    $('.prev-step').click(function() {
        currentStep--;
        showStep(currentStep);
    });

    // File upload preview
    $('#documents').change(function() {
        updateFileList();
    });

    // Form submission
    $('#progressClaimForm').submit(function(e) {
        // Validation before submit
        if (!validateStep(5)) {
            e.preventDefault();
            alert('Lütfen tüm adımları tamamlayınız.');
            return false;
        }
        // Add status hidden field
        if (!$('#status').length) {
            $('<input>').attr({
                type: 'hidden',
                id: 'status',
                name: 'status',
                value: 'submitted'
            }).appendTo('#progressClaimForm');
        } else {
            $('#status').val('submitted');
        }
        // Form will submit normally
    });

    $('#saveDraft').click(function() {
        // Add status and submit
        if (!$('#status').length) {
            $('<input>').attr({
                type: 'hidden',
                id: 'status',
                name: 'status',
                value: 'draft'
            }).appendTo('#progressClaimForm');
        } else {
            $('#status').val('draft');
        }
        $('#progressClaimForm').submit();
    });

    function loadContracts(projectId) {
        // AJAX ile sözleşmeleri yükle (şimdilik static)
        const contracts = @json($contracts);
        const filteredContracts = contracts.filter(c => c.project_id == projectId);

        $('#subcontractor_contract_id').html('<option value="">Sözleşme seçiniz...</option>');
        filteredContracts.forEach(contract => {
            $('#subcontractor_contract_id').append(
                `<option value="${contract.id}">${contract.contract_number} - ${contract.subcontractor.company_name}</option>`
            );
        });
    }

    function generateClaimNumber(contractId) {
        // Basit claim number generation
        const now = new Date();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const year = now.getFullYear();
        $('#claim_number').val(`HK-${contractId}-${month}${year}-001`);
    }

    function loadContractItems(contractId) {
        // Mock data - gerçekte AJAX ile yüklenmeli
        const mockItems = [
            {code: '160.01.001', description: 'C30 Beton', unit: 'm³', quantity: 1200, unit_price: 2000.00, previous: 450},
            {code: '160.02.005', description: 'Nervürlü Demir', unit: 'ton', quantity: 85, unit_price: 25000.00, previous: 30}
        ];

        $('#progressTableBody').html('');
        mockItems.forEach(item => {
            $('#progressTableBody').append(`
                <tr>
                    <td>${item.code}</td>
                    <td>${item.description}</td>
                    <td>${item.unit}</td>
                    <td>${item.quantity}</td>
                    <td>${item.previous}</td>
                                    <td><input type="number" class="form-control current-quantity" name="current_quantity[]" step="0.01" min="0" data-unit-price="${item.unit_price}"></td>
                    <td class="cumulative-total">${item.previous}</td>
                    <td>${item.unit_price.toLocaleString('tr-TR')} TL</td>
                    <td class="current-amount">0.00 TL</td>
                </tr>
            `);
        });

        // Input event listeners
        $('.current-quantity').on('input', function() {
            calculateRow($(this).closest('tr'));
            calculateTotals();
        });
    }

    function calculateRow(row) {
        const currentQty = parseFloat(row.find('.current-quantity').val()) || 0;
        const previousQty = parseFloat(row.find('td:nth-child(5)').text()) || 0;
        const unitPrice = parseFloat(row.find('.current-quantity').data('unit-price')) || 0;

        const cumulative = previousQty + currentQty;
        const amount = currentQty * unitPrice;

        row.find('.cumulative-total').text(cumulative.toFixed(2));
        row.find('.current-amount').text(amount.toLocaleString('tr-TR') + ' TL');
    }

    function calculateTotals() {
        let totalCurrent = 0;
        $('.current-amount').each(function() {
            const amount = parseFloat($(this).text().replace(/[^\d,]/g, '').replace(',', '.')) || 0;
            totalCurrent += amount;
        });

        const withholding = totalCurrent * 0.1; // %10 teminat
        const advance = 50000; // Mock avans
        const subtotal = totalCurrent - withholding - advance;
        const vat = subtotal * 0.2; // %20 KDV
        const net = subtotal + vat;

        $('#totalCurrentAmount').text(totalCurrent.toLocaleString('tr-TR') + ' TL');
        $('#withholdingAmount').text(withholding.toLocaleString('tr-TR') + ' TL');
        $('#advanceDeduction').text(advance.toLocaleString('tr-TR') + ' TL');
        $('#subtotal').text(subtotal.toLocaleString('tr-TR') + ' TL');
        $('#vatAmount').text(vat.toLocaleString('tr-TR') + ' TL');
        $('#netAmount').text(net.toLocaleString('tr-TR') + ' TL');
    }

    function updateFileList() {
        const files = $('#documents')[0].files;
        $('#fileList').html('');
        for (let i = 0; i < files.length; i++) {
            $('#fileList').append(`<div class="alert alert-light">${files[i].name}</div>`);
        }
    }

    function validateStep(step) {
        switch(step) {
            case 1:
                return $('#project_id').val() && $('#subcontractor_contract_id').val() &&
                       $('#period_start').val() && $('#period_end').val();
            case 2:
                return true; // Tablo validasyonu eklenebilir
            case 3:
                return $('#documents')[0].files.length > 0;
            case 4:
                return true;
            case 5:
                return true;
            default:
                return false;
        }
    }

    function showStep(step) {
        $('.tab-pane').removeClass('show active');
        $(`#step${step}`).addClass('show active');
        $('.nav-link').removeClass('active');
        $(`#step${step}-tab`).addClass('active');
    }

    function submitClaim(status) {
        // Bu fonksiyon artık kullanılmıyor - form doğrudan submit ediliyor
    }
});
</script>
@endpush
