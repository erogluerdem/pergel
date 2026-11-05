@extends('layouts.master')

@section('title', 'Yeni Hakediş Cetveli Oluştur')

@section('topbar-title', 'Hakediş Yönetimi - Yeni Cetvel')

@push('styles')
<style>
    .progress-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #4338ca 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .progress-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .progress-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="progress-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('progress-payments.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Hakediş Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Yeni Hakediş Cetveli Oluştur</h1>
            <p class="text-white-50 mb-0 fs-6">Sözleşme ve proje bazlı hakediş tutarı, ilerleme oranı ve imalat satırlarını tanımlayın.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-11">
        <div class="progress-form-card">
            <form action="{{ route('progress-payments.store') }}" method="POST" id="progressForm">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="project_id" class="form-label fw-bold small text-dark">İlgili Proje <span class="text-danger">*</span></label>
                        <select name="project_id" id="project_id" class="form-select" required>
                            <option value="">Proje seçin...</option>
                            @foreach($projects as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="contract_id" class="form-label fw-bold small text-dark">İlgili Sözleşme <span class="text-danger">*</span></label>
                        <select name="contract_id" id="contract_id" class="form-select" required>
                            <option value="">Sözleşme seçin...</option>
                            @foreach($contracts as $id => $number)
                                <option value="{{ $id }}">Sözleşme #{{ $number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="payment_number" class="form-label fw-bold small text-dark">Hakediş Kodu / No <span class="text-danger">*</span></label>
                        <input type="text" name="payment_number" id="payment_number" class="form-control font-monospace fw-bold" value="HKD-{{ date('Y') }}-{{ sprintf('%03d', rand(100, 999)) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="payment_date" class="form-label fw-bold small text-dark">Hakediş Tarihi <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="due_date" class="form-label fw-bold small text-dark">Son Ödeme Vadesi <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" id="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+15 days')) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="amount" class="form-label fw-bold small text-dark">Hakediş Tutarı (KDV Hariç - ₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control fw-bold text-indigo" style="color:#4338ca;" placeholder="0.00" required>
                        <small class="text-muted small">Satır tutarları girildikçe otomatik hesaplanır.</small>
                    </div>

                    <div class="col-md-6">
                        <label for="percentage" class="form-label fw-bold small text-dark">Toplam Proje İlerleme Oranı (%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="percentage" id="percentage" class="form-control" placeholder="Örn: 45.00" value="35.00" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-bold small text-dark">Hakediş Başlığı & Dönem Açıklaması <span class="text-danger">*</span></label>
                    <input type="text" name="description" id="description" class="form-control" placeholder="Örn: Kaba İnşaat & Perde Beton 2. Dönem Hakediş Cetveli" required>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label fw-bold small text-dark">Ek Notlar & İnceleme Açıklamaları</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Saha kontrolör notları..."></textarea>
                </div>

                <!-- Dynamic Item Lines Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="ri-list-check text-indigo me-2" style="color:#4338ca;"></i>Hakediş İmalat Satırları</h5>
                    <button type="button" class="btn btn-sm btn-outline-indigo rounded-pill px-3" onclick="addRow()">
                        <i class="ri-add-line me-1"></i>Yeni Satır Ekle
                    </button>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table align-middle" id="detailsTable">
                        <thead class="table-light">
                            <tr>
                                <th>İmalat / İş Kalemi Açıklaması</th>
                                <th style="width: 140px;">Miktar</th>
                                <th style="width: 160px;">Birim Fiyat (₺)</th>
                                <th style="width: 180px;" class="text-end">Tutar (₺)</th>
                                <th style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="details[0][description]" class="form-control" placeholder="Örn: C40 Beton Dökümü" value="C40 Beton Dökümü ve İşçiliği" required></td>
                                <td><input type="number" step="0.01" name="details[0][quantity]" class="form-control qty-input" placeholder="1.00" value="500" oninput="calcTotals()" required></td>
                                <td><input type="number" step="0.01" name="details[0][unit_price]" class="form-control price-input" placeholder="0.00" value="2500" oninput="calcTotals()" required></td>
                                <td class="text-end"><strong class="row-total text-dark">1.250.000,00 ₺</strong></td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger rounded-circle" onclick="removeRow(this)"><i class="ri-delete-bin-line"></i></button></td>
                            </tr>
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="3" class="text-end">Ara Toplam (KDV Hariç):</td>
                                <td class="text-end"><span id="subtotalDisplay">1.250.000,00 ₺</span></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end">KDV (%20):</td>
                                <td class="text-end text-indigo" style="color:#4338ca;"><span id="kdvDisplay">250.000,00 ₺</span></td>
                                <td></td>
                            </tr>
                            <tr class="fs-6 text-dark">
                                <td colspan="3" class="text-end">Genel Toplam (KDV Dahil):</td>
                                <td class="text-end text-success"><span id="grandTotalDisplay">1.500.000,00 ₺</span></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('progress-payments.index') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-indigo text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#4338ca;">
                        <i class="ri-save-line me-1"></i>Hakedişi Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let rowIndex = 1;

function addRow() {
    const tbody = document.querySelector('#detailsTable tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" name="details[${rowIndex}][description]" class="form-control" placeholder="İmalat kalemi..." required></td>
        <td><input type="number" step="0.01" name="details[${rowIndex}][quantity]" class="form-control qty-input" placeholder="1.00" value="1" oninput="calcTotals()" required></td>
        <td><input type="number" step="0.01" name="details[${rowIndex}][unit_price]" class="form-control price-input" placeholder="0.00" value="0" oninput="calcTotals()" required></td>
        <td class="text-end"><strong class="row-total text-dark">0,00 ₺</strong></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger rounded-circle" onclick="removeRow(this)"><i class="ri-delete-bin-line"></i></button></td>
    `;
    tbody.appendChild(tr);
    rowIndex++;
    calcTotals();
}

function removeRow(btn) {
    const tbody = document.querySelector('#detailsTable tbody');
    if (tbody.querySelectorAll('tr').length > 1) {
        btn.closest('tr').remove();
        calcTotals();
    }
}

function calcTotals() {
    let subtotal = 0;
    document.querySelectorAll('#detailsTable tbody tr').forEach(tr => {
        const qty = parseFloat(tr.querySelector('.qty-input')?.value || 0);
        const price = parseFloat(tr.querySelector('.price-input')?.value || 0);
        const total = qty * price;
        subtotal += total;
        tr.querySelector('.row-total').textContent = total.toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ₺';
    });

    const kdv = subtotal * 0.20;
    const grand = subtotal + kdv;

    document.getElementById('amount').value = subtotal.toFixed(2);
    document.getElementById('subtotalDisplay').textContent = subtotal.toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ₺';
    document.getElementById('kdvDisplay').textContent = kdv.toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ₺';
    document.getElementById('grandTotalDisplay').textContent = grand.toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ₺';
}

document.addEventListener('DOMContentLoaded', calcTotals);
</script>
@endpush
