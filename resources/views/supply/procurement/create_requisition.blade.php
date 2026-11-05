@extends('layouts.master')

@section('title', 'Yeni Talep Oluştur')
@section('topbar-title', 'Satın Almalar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Yeni Satın Alma Talebi</h4>
                </div>
                <div class="card-body">
                    <form id="requisitionForm" method="POST" action="{{ route('supply.procurement.requisitions.store') }}">
                        @csrf

                        <!-- Talep Detayları -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="project_id" class="form-label">Proje <span class="text-danger">*</span></label>
                                    <select class="form-select @error('project_id') is-invalid @enderror" id="project_id" name="project_id" required>
                                        <option value="">Proje Seçin</option>
                                        @foreach(\App\Models\Project::all() as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="required_date" class="form-label">İstenen Teslimat Tarihi <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('required_date') is-invalid @enderror" id="required_date" name="required_date" value="{{ old('required_date') }}" required>
                                    @error('required_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Malzeme/Hizmet Listesi -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Malzeme/Hizmet Listesi</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="itemsContainer">
                                            <!-- Dinamik item'lar buraya eklenecek -->
                                        </div>
                                        <button type="button" class="btn btn-outline-primary" onclick="addItem()">
                                            <i class="mdi mdi-plus"></i> Malzeme Ekle
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Butonlar -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Talebi Kaydet
                                </button>
                                <a href="{{ route('supply.procurement') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Geri
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let itemCount = 0;

function addItem() {
    itemCount++;
    const container = document.getElementById('itemsContainer');

    const itemHtml = `
        <div class="item-row border rounded p-3 mb-3" data-item="${itemCount}">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Stok / Açıklama</label>
                    <select class="form-control stock-select" id="stock_select_${itemCount}" name="items[${itemCount}][stock_card_id]"></select>
                    <input type="hidden" name="items[${itemCount}][unit_price]" id="unit_price_${itemCount}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Açıklama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="description_${itemCount}" name="items[${itemCount}][description]" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Miktar <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" name="items[${itemCount}][quantity]" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Birim <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="unit_${itemCount}" name="items[${itemCount}][unit]" placeholder="adet" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeItem(${itemCount})">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', itemHtml);
    // Initialize Select2 for the newly added stock select (if Select2 is available)
    try {
        initStockSelect(itemCount);
    } catch (e) {
        // if Select2 is not present, ignore silently
        console.warn('Select2 init failed', e);
    }
}

function removeItem(itemId) {
    const itemRow = document.querySelector(`.item-row[data-item="${itemId}"]`);
    if (itemRow) {
        itemRow.remove();
    }
}

function initStockSelect(id) {
    const selector = $(`#stock_select_${id}`);

    if (!selector.length || typeof $.fn.select2 !== 'function') return;

    selector.select2({
        placeholder: 'Stok ara (kod veya ad)...',
        allowClear: true,
        minimumInputLength: 1,
        ajax: {
            url: '/finance/stock/search',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { q: params.term };
            },
            processResults: function(data) {
                return { results: data.results };
            }
        },
        templateResult: function(item) {
            if (!item.id) return item.text;
            return $('<div><strong>' + (item.text || '') + '</strong><div class="text-muted small">₺' + (item.unit_price ? parseFloat(item.unit_price).toLocaleString('tr-TR', {minimumFractionDigits:2}) : '0.00') + '</div></div>');
        },
        templateSelection: function(item) {
            return item.text || item.id;
        }
    });

    selector.on('select2:select', function(e) {
        const item = e.params.data;
        // fill description, unit and unit_price if present
        if (item) {
            $(`#description_${id}`).val(item.text.replace(/\s*\(.*\)$/, ''));
            $(`#unit_${id}`).val(item.unit || 'adet');
            $(`#unit_price_${id}`).val(item.unit_price || '0');
        }
    });

    selector.on('select2:clear', function() {
        $(`#description_${id}`).val('');
        $(`#unit_${id}`).val('');
        $(`#unit_price_${id}`).val('');
    });
}

// Sayfa yüklendiğinde bir item ekle
document.addEventListener('DOMContentLoaded', function() {
    addItem();
    // Initialize select2 for initial item after a short delay (if select2 is loaded)
    setTimeout(function() {
        try { initStockSelect(1); } catch(e) { }
    }, 200);
});
</script>
@endsection
