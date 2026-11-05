@extends('layouts.master')

@section('title', isset($purchaseOrder) || request()->get('vendor_id') ? 'Yeni Satınalma Faturası' : 'Yeni Fatura')
@section('topbar-title','Finans')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>{{ (isset($purchaseOrder) || request()->get('vendor_id')) ? 'Yeni Satınalma Faturası' : 'Yeni Satış Faturası' }}</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('finance.invoices.store') }}">
                @csrf
                <div class="row mb-3">
                    @php $isPurchaseMode = request()->get('is_purchase') || request()->get('vendor_id') || isset($purchaseOrder);
                    $prefVendorId = request()->get('vendor_id') ?? ($purchaseOrder->vendor->id ?? null ?? null);
                    @endphp
                    @if($isPurchaseMode)
                        <div class="col-md-6">
                            <label class="form-label">Tedarikçi</label>
                            <select name="vendor_id" class="form-select">
                                <option value="">Tedarikçi seçin</option>
                                @foreach($vendors ?? App\Models\Vendor::all() as $v)
                                    <option value="{{ $v->id }}" {{ $prefVendorId == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="is_purchase_invoice" value="1">
                    @else
                        <div class="col-md-6">
                            <label class="form-label">Müşteri</label>
                            <select name="customer_id" class="form-select">
                                <option value="">Müşteri seçin</option>
                                @foreach(App\Models\Customer::all() as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <label class="form-label">Fatura No</label>
                        <input type="text" name="no" class="form-control" value="INV-{{ time() }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tarih</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                @php $prefPurchaseOrderId = $purchaseOrder->id ?? request()->get('purchase_order_id') ?? null; @endphp
                @if($prefPurchaseOrderId)
                    <input type="hidden" name="purchase_order_id" value="{{ $prefPurchaseOrderId }}">
                @endif
                <div id="itemsContainer"></div>
                <button type="button" class="btn btn-outline-primary mb-3" onclick="addItem()">Malzeme Ekle</button>

                <div>
                    <button class="btn btn-primary" type="submit">{{ (isset($purchaseOrder) || request()->get('vendor_id')) ? 'Satınalma Faturayı Kaydet' : 'Faturayı Kaydet' }}</button>
                    <a class="btn btn-secondary" href="{{ route('finance.invoices.index') }}">İptal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
/* Select2 sizing and prevent selected item wrapping */
.select2-container { width: 100% !important; min-width: 180px; max-width:400px; }
.select2-container .select2-selection { box-sizing: border-box; }
.select2-container--default .select2-selection--single .select2-selection__rendered {
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    display: block;
    max-width: 100%;
}
.select2-dropdown { width: auto !important; min-width: 180px; max-width:400px; }
.select2-search__field { width: 100% !important; min-width: 120px; }
.select2-container .select2-selection__arrow { right: 8px; }
</style>
    <script>
    // If purchaseOrderItems were passed from the controller, import them into the invoice form
    const purchaseOrderItems = {!! json_encode($purchaseOrderItems ?? []) !!};
let itemCount = 0;
function addItem() {
    itemCount++;
    const container = document.getElementById('itemsContainer');
    const html = `
    <div class="item-row border rounded p-4 mb-4 bg-light" data-item="${itemCount}">
        <div class="row gx-3 gy-2 align-items-center flex-nowrap">
            <div class="col-lg-4 col-md-6 col-12">
                <label class="mb-1">Stok / Açıklama</label>
                <select class="form-control stock-select" id="stock_select_${itemCount}" name="items[${itemCount}][stock_card_id]" style="width:100%"></select>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <label class="mb-1">Birim Fiyat (TL)</label>
                <input type="number" step="0.01" class="form-control" id="unit_price_${itemCount}_visible" value="0">
                <input type="hidden" name="items[${itemCount}][unit_price]" id="unit_price_${itemCount}">
            </div>
            <div class="col-lg-2 col-md-2 col-6">
                <label class="mb-1">Miktar</label>
                <input type="number" step="0.01" name="items[${itemCount}][quantity]" id="quantity_${itemCount}" class="form-control" value="1">
            </div>
            <div class="col-lg-1 col-md-2 col-6">
                <label class="mb-1">KDV %</label>
                <select class="form-select" id="tax_rate_${itemCount}" name="items[${itemCount}][tax_rate]">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="10">10</option>
                    <option value="20" selected>20</option>
                </select>
            </div>
            <div class="col-lg-1 col-md-2 col-6 text-end">
                <label class="mb-1">Ara Toplam</label>
                <div id="line_total_${itemCount}" class="fw-bold">0.00</div>
                <input type="hidden" name="items[${itemCount}][total]" id="hidden_line_total_${itemCount}">
            </div>
            <div class="col-lg-2 col-md-2 col-12 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeItem(${itemCount})">Sil</button>
            </div>
        </div>
    </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    try { initStockSelect(itemCount); } catch(e){ console.warn('Select2 not available', e); }

    // wire visible unit price to hidden and recalc on change
    document.getElementById(`unit_price_${itemCount}_visible`).addEventListener('input', function(){
        document.getElementById(`unit_price_${itemCount}`).value = this.value || 0;
        recalcTotals();
    });
    document.getElementById(`quantity_${itemCount}`).addEventListener('input', recalcTotals);
    document.getElementById(`tax_rate_${itemCount}`).addEventListener('change', recalcTotals);
    // initial recalc
    recalcTotals();
}
function removeItem(id){ const el = document.querySelector(`.item-row[data-item="${id}"]`); if(el) el.remove(); }

function initStockSelect(id) {
    const selector = $(`#stock_select_${id}`);
    if (!selector.length || typeof $.fn.select2 !== 'function') return;
        selector.select2({
            placeholder: 'Stok ara...', allowClear: true, minimumInputLength: 1,
            width: 'resolve',
            dropdownAutoWidth: false,
            // ensure dropdown is appended inside the row so it matches the field width and doesn't overflow the page
            dropdownParent: selector.parent(),
            ajax: { url: '/finance/stock/search', dataType: 'json', delay: 250, data: params => ({ q: params.term }), processResults: data => ({ results: data.results }) }
        });
    selector.on('select2:select', function(e){ const item = e.params.data; if(item){ /* description field removed in layout; fill unit price */ $(`#unit_price_${id}_visible`).val(item.unit_price||0); $(`#unit_price_${id}`).val(item.unit_price||0); recalcTotals(); } });
    selector.on('select2:clear', function(){ $(`#unit_price_${id}_visible`).val('0'); $(`#unit_price_${id}`).val('0'); recalcTotals(); });
}

function recalcTotals(){
    let subtotal = 0;
    let taxTotal = 0;
    for(let i=1;i<=itemCount;i++){
        const row = document.querySelector(`.item-row[data-item="${i}"]`);
        if(!row) continue;
        const qty = parseFloat(document.getElementById(`quantity_${i}`).value || 0);
        const unit = parseFloat(document.getElementById(`unit_price_${i}`).value || 0);
        const taxRate = parseFloat(document.getElementById(`tax_rate_${i}`).value || 0);
        const lineNet = qty * unit;
        const lineTax = lineNet * (taxRate/100.0);
        const lineTotal = lineNet + lineTax;
        subtotal += lineNet;
        taxTotal += lineTax;
        document.getElementById(`line_total_${i}`).innerText = lineTotal.toLocaleString('tr-TR', {minimumFractionDigits:2, maximumFractionDigits:2});
        document.getElementById(`hidden_line_total_${i}`).value = lineNet.toFixed(2);
    }
    // update totals UI (create summary if not exists)
    let summary = document.getElementById('invoiceTotals');
    if(!summary){
        const container = document.createElement('div');
        container.id = 'invoiceTotals';
        container.className = 'card mt-3 p-3';
        container.innerHTML = `
            <div class="row">
                <div class="col-md-4"><strong>Ara Toplam:</strong> <span id="summary_subtotal">0.00</span></div>
                <div class="col-md-4"><strong>KDV Toplam:</strong> <span id="summary_tax">0.00</span></div>
                <div class="col-md-4"><strong>Genel Toplam:</strong> <span id="summary_total">0.00</span></div>
            </div>
        `;
        document.querySelector('.card-body form').insertAdjacentElement('beforeend', container);
    }
    document.getElementById('summary_subtotal').innerText = subtotal.toLocaleString('tr-TR', {minimumFractionDigits:2});
    document.getElementById('summary_tax').innerText = taxTotal.toLocaleString('tr-TR', {minimumFractionDigits:2});
    document.getElementById('summary_total').innerText = (subtotal + taxTotal).toLocaleString('tr-TR', {minimumFractionDigits:2});
}

document.addEventListener('DOMContentLoaded', function(){ addItem(); setTimeout(()=>{ try{ initStockSelect(1); }catch(e){} },200); });

// import items if present
if(Array.isArray(purchaseOrderItems) && purchaseOrderItems.length){
    // remove the initial empty item and replace with PO items
    document.querySelectorAll('.item-row').forEach(el => el.remove());
    itemCount = 0;
    purchaseOrderItems.forEach(it => {
        addItem();
        const idx = itemCount;
        // fill values
        try{ document.getElementById(`unit_price_${idx}_visible`).value = it.unit_price || 0; document.getElementById(`unit_price_${idx}`).value = it.unit_price || 0; }catch(e){}
        try{ document.getElementById(`quantity_${idx}`).value = it.quantity || 1; }catch(e){}
        try{ document.getElementById(`tax_rate_${idx}`).value = it.tax_rate || 0; }catch(e){}
        // Attempt to auto-match stock card by SKU/stock_code if present
        if(it.sku){
            // ensure select2 is initialized then query the stock search endpoint
            setTimeout(()=>{
                try{
                    const sel = $(`#stock_select_${idx}`);
                    if(!sel.length) return;
                    // ask the existing search endpoint for the SKU
                    $.getJSON('/finance/stock/search', { q: it.sku }, function(resp){
                        const results = resp && resp.results ? resp.results : resp || [];
                        if(results.length){
                            // try to find an exact code/barcode match in returned items
                            let found = results.find(r => (r.code && r.code === it.sku) || (r.barcode && r.barcode === it.sku) || (r.text && r.text === it.sku));
                            if(!found) found = results[0];
                            if(found){
                                const opt = new Option(found.text || (found.name||found.code), found.id, true, true);
                                sel.append(opt).trigger('change');
                                // also set unit price if returned
                                if(found.unit_price) { $(`#unit_price_${idx}_visible`).val(found.unit_price); $(`#unit_price_${idx}`).val(found.unit_price); }
                            }
                        }
                    }).fail(()=>{});
                }catch(e){console.warn('SKU match failed', e);}
            }, 250);
        }
    });
    recalcTotals();
}
</script>
@endsection
