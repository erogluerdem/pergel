@extends('layouts.master')

@section('title', 'Hakediş Detay')
@section('page-title', 'Hakediş Detay')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Hakediş {{ $payment->no }}</h4>
                <div>
                    <a href="{{ route('cost.payments.index') }}" class="btn btn-sm btn-outline-secondary">Geri</a>
                </div>
            </div>
            <div class="card-body">
                <!-- Workflow visualization -->
                <div class="mb-4">
                    @php
                        $steps = ['Taslak','Saha Onayında','PM Onayında','Muhasebe Onayı','Ödendi'];
                        // infer current step from status if possible
                        $currentIndex = array_search($payment->status ?? 'Saha Onayında', $steps);
                        if ($currentIndex === false) $currentIndex = 1;
                    @endphp
                    <div class="d-flex align-items-center">
                        @foreach($steps as $i => $s)
                            @php
                                $class = 'bg-secondary text-white';
                                if ($i < $currentIndex) $class = 'bg-success text-white';
                                if ($i == $currentIndex) $class = 'bg-warning text-dark';
                            @endphp
                            <div class="p-2 mr-2" style="border-radius:6px; min-width:140px; text-align:center;" class="{{ $class }}">
                                <div class="{{ $class }}">{{ $s }}</div>
                            </div>
                            @if($i < count($steps)-1)
                                <div style="flex:0 0 30px; text-align:center;">→</div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <h5>Hakediş İcmali</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Poz No</th>
                                        <th>Açıklama</th>
                                        <th>Birim</th>
                                        <th>Sözl. Miktarı</th>
                                        <th>Önceki</th>
                                        <th>Bu Dönem</th>
                                        <th>Toplam</th>
                                        <th>Birim Fiyat</th>
                                        <th>Bu Dönem Tutarı</th>
                                        <th>Kanıtlar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $itemsLoop = $payment->items ?? []; @endphp
                                    @if(!empty($itemsLoop))
                                        @foreach($itemsLoop as $it)
                                        <tr>
                                            <td>{{ is_array($it) ? ($it['pos'] ?? '') : ($it->pos ?? '') }}</td>
                                            <td>{{ is_array($it) ? ($it['desc'] ?? '') : ($it->description ?? '') }}</td>
                                            <td>{{ is_array($it) ? ($it['unit'] ?? '') : ($it->unit ?? '') }}</td>
                                            <td>{{ number_format(is_array($it) ? ($it['contract_qty'] ?? 0) : ($it->contract_qty ?? 0),2,',','.') }}</td>
                                            <td>{{ number_format(is_array($it) ? ($it['prev_qty'] ?? 0) : ($it->prev_qty ?? 0),2,',','.') }}</td>
                                            <td>{{ number_format(is_array($it) ? ($it['this_qty'] ?? 0) : ($it->this_qty ?? 0),2,',','.') }}</td>
                                            <td>{{ number_format(is_array($it) ? ($it['total_qty'] ?? 0) : ($it->total_qty ?? 0),2,',','.') }}</td>
                                            <td>{{ number_format(is_array($it) ? ($it['unit_price'] ?? 0) : ($it->unit_price ?? 0),0,',','.') }} ₺</td>
                                            <td>{{ number_format(is_array($it) ? ($it['this_amount'] ?? 0) : ($it->this_amount ?? 0),0,',','.') }} ₺</td>
                                            <td>{{ is_array($it) ? ($it['evidence_count'] ?? 0) : (isset($it->evidences) ? count($it->evidences) : 0) }} Fotoğraf</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="10" class="text-center text-muted">Henüz kalem yok.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5>Finansal Özet</h5>
                        @php
                            $totalThis = collect($payment->items)->sum('this_amount');
                            $deductions = collect($payment->deductions ?? []);
                            $dedTotal = $deductions->sum('amount');
                            $subTotal = $totalThis + $dedTotal;
                            $vat = round($subTotal * (($payment->vat_percent ?? 0)/100));
                            $toPay = $subTotal + $vat;
                        @endphp
                        <table class="table table-sm">
                            <tr><td>Toplam İmalat (Bu Dönem)</td><td class="text-right">{{ number_format($totalThis,0,',','.') }} ₺</td></tr>
                            @foreach($deductions as $d)
                            <tr><td>{{ $d['label'] }}</td><td class="text-right">{{ number_format($d['amount'],0,',','.') }} ₺</td></tr>
                            @endforeach
                            <tr><td>Ara Toplam</td><td class="text-right">{{ number_format($subTotal,0,',','.') }} ₺</td></tr>
                            <tr><td>KDV ({{ $payment->vat_percent }}%)</td><td class="text-right">{{ number_format($vat,0,',','.') }} ₺</td></tr>
                            <tr class="font-weight-bold"><td>Ödenecek Toplam</td><td class="text-right">{{ number_format($toPay,0,',','.') }} ₺</td></tr>
                        </table>

                        <h6>Onay Aksiyonları</h6>
                        <div class="mb-2">
                            <form method="post" action="{{ route('cost.payments.approve', $payment->id) }}" style="display:inline">
                                @csrf
                                <button class="btn btn-sm btn-success">Onayla (Tüm Hakediş)</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h5>Onay Geçmişi ve Yorumlar</h5>
                    <ul class="list-group">
                        @foreach($payment->history ?? [] as $h)
                        <li class="list-group-item">
                            <div><strong>[{{ $h['when'] }}] {{ $h['who'] }}</strong></div>
                            <div>{{ $h['note'] }}</div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Attach upload handlers for each item if upload route exists
    document.querySelectorAll('table.table tbody tr').forEach(function(row){
        // add file input cell
        var lastTd = row.querySelector('td:last-child');
        if (!lastTd) return;
        var itemId = row.querySelector('td:first-child') ? row.querySelector('td:first-child').textContent.trim() : null;
    });

    // Build upload UI below the items table
    var table = document.querySelector('.table.table-sm');
    if (!table) return;
    var tbody = table.querySelector('tbody');
    // For each item row, enhance with upload input
    Array.from(tbody.querySelectorAll('tr')).forEach(function(tr, idx){
        var cells = tr.querySelectorAll('td');
        if (!cells.length) return;
        // assume items are in the same order as payment->items
        var itemIndex = idx; // indices
        var uploadCell = document.createElement('td');
        uploadCell.colSpan = 10;
        uploadCell.innerHTML = '\n            <div class="d-flex align-items-center">\n                <input type="file" accept="image/*" class="evidence-input mr-2" data-item-index="'+itemIndex+'">\n                <div class="evidence-preview d-flex"></div>\n            </div>';
        tr.parentNode.insertBefore(uploadCell, tr.nextSibling);
    });

    // handle change events
    document.body.addEventListener('change', function(e){
        if (!e.target.classList.contains('evidence-input')) return;
        var file = e.target.files[0];
        if (!file) return;
        var itemIndex = e.target.getAttribute('data-item-index');
        // find payment item id from server-rendered data
    var items = @json($payment->items ?? []);
        var item = items[itemIndex];
        if (!item) return alert('Item not found');
        var form = new FormData();
        form.append('file', file);
        fetch('{{ url('/cost/payments/items') }}/' + (item.id || itemIndex) + '/evidence', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: form
        }).then(r=>r.json()).then(function(resp){
            if (resp.ok && resp.path) {
                var preview = e.target.parentNode.querySelector('.evidence-preview');
                var img = document.createElement('img'); img.src = resp.path; img.style.width='80px'; img.style.marginRight='6px'; preview.appendChild(img);
            } else {
                alert('Yükleme başarısız');
            }
        }).catch(function(){ alert('Yükleme hatası'); });
    });
});
</script>
@endsection
