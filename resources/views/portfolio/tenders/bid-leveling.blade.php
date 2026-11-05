@extends('layouts.master')
@section('title','Teklif Karşılaştırma — '.$p->name)
@section('content')
<div class="container-fluid mt-4">
    <h4>Teklif Karşılaştırma — {{ $p->name }}</h4>
    <div class="card mt-3">
        <div class="card-body">
            <div class="mb-2">Toplam teklif: {{ $bids->count() }} — En düşük: — — Ortalama: —</div>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Poz No</th>
                            <th>Açıklama</th>
                            <th>Birim</th>
                            <th>Miktar</th>
                            @foreach($bids as $b)
                                <th>{{ $b->bidder_email }}<br><small>{{ number_format($b->price,2) }}</small></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($boq as $row)
                            <tr>
                                <td>{{ $row['no'] ?? '' }}</td>
                                <td>{{ $row['description'] ?? '' }}</td>
                                <td>{{ $row['unit'] ?? '' }}</td>
                                <td>{{ $row['quantity'] ?? '' }}</td>
                                @foreach($bids as $b)
                                    @php
                                        $item = $b->items->firstWhere('poz_no', $row['no']);
                                        $price = $item ? $item->unit_price : null;
                                    @endphp
                                    <td class="text-end @if($price && collect($bids->map(fn($x)=>($x->items->firstWhere('poz_no',$row['no'])?->unit_price)))->filter()->min() == $price) table-success @endif">{{ $price ? number_format($price,2) : '—' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <form method="post" action="{{ route('tenders.package.bids.compare_process', $p->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label>CSV Yükle (opsiyonel) — format: email,poz_no,unit_price</label>
                            <input type="file" name="offers_csv" class="form-control" accept=".csv" />
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-primary mt-4">Karşılaştır ve Raporla</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="mt-3">
                <button id="auto-match" class="btn btn-outline-secondary">Otomatik Eşleştir</button>
                <button id="save-mappings" class="btn btn-primary ms-2">Eşlemeleri Kaydet</button>
                <div id="normalize-result" class="mt-2" style="display:none;">
                    <div class="alert alert-light p-2" id="normalize-message">Hazır</div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="match-table">
                            <thead>
                                <tr>
                                    <th>Poz</th>
                                    <th>Bidder</th>
                                    <th>Matched Item Poz</th>
                                    <th>Score</th>
                                    <th>Accepted</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('auto-match')?.addEventListener('click', async function(){
    const url = '{{ route('tenders.bid.normalize', $p->id) }}';
    const box = document.getElementById('normalize-result');
    box.style.display='block';
    document.getElementById('normalize-message').textContent = 'Eşleştiriliyor...';
    try{
        const res = await fetch(url, { method:'POST', headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'} });
        const data = await res.json();
        renderMatches(data.matches);
        document.getElementById('normalize-message').textContent = 'Eşleştirme tamamlandı. Düzenleyip kaydedebilirsiniz.';
    }catch(e){
        document.getElementById('normalize-message').textContent = 'Hata: '+e.message;
    }
});

function renderMatches(matches){
    const tbody = document.querySelector('#match-table tbody');
    tbody.innerHTML = '';
    // matches: { poz: { rows: [ {bidder, item, score}, ... ] } }
    Object.keys(matches).forEach(poz => {
        const m = matches[poz];
        m.rows.forEach(r => {
            const tr = document.createElement('tr');
            const itemPoz = r.item ? (r.item.poz_no ?? '') : '';
            const score = r.score ?? 0;
            tr.innerHTML = `
                <td>${poz}</td>
                <td>${r.bidder}</td>
                <td><input type="text" class="form-control form-control-sm match-item-poz" value="${itemPoz}" /></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm match-score" value="${score}" /></td>
                <td class="text-center"><input type="checkbox" class="match-accepted" ${r.item? 'checked':''} /></td>
            `;
            // store bidder id in dataset by looking up from page (we need to map email->id)
            tr.dataset.bidderEmail = r.bidder;
            tr.dataset.poz = poz;
            tbody.appendChild(tr);
        });
    });
}

document.getElementById('save-mappings')?.addEventListener('click', async function(){
    const rows = Array.from(document.querySelectorAll('#match-table tbody tr'));
    const mappings = rows.map(r=>{
        return {
            poz_no: r.dataset.poz,
            bidder_email: r.dataset.bidderEmail,
            // attempt to find tender_bid_id by scanning page's bids
            tender_bid_id: findBidIdByEmail(r.dataset.bidderEmail),
            tender_bid_item_id: null, // no stable id from client currently
            score: parseFloat(r.querySelector('.match-score').value || 0),
            accepted: !!r.querySelector('.match-accepted').checked,
        };
    }).filter(x=>x.tender_bid_id);

    const url = '{{ route('tenders.bid.save_mappings', $p->id) }}';
    try{
        const res = await fetch(url, { method:'POST', headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json','Accept':'application/json'}, body: JSON.stringify({ mappings }) });
        const data = await res.json();
        document.getElementById('normalize-message').textContent = 'Kaydedildi: '+(data.saved?.length || 0)+' kayıt.';
    }catch(e){
        document.getElementById('normalize-message').textContent = 'Kaydetme hatası: '+e.message;
    }
});

function findBidIdByEmail(email){
    // extract bids from page via a hidden JSON blob or the table headers
    try{
        const blob = JSON.parse(document.getElementById('bids-json')?.textContent || '[]');
        const found = blob.find(b=>b.bidder_email === email || b.bidder_email === (email+'') );
        return found ? found.id : null;
    }catch(e){
        return null;
    }
}
</script>
<script type="application/json" id="bids-json">{!! json_encode($bids->map(fn($x)=>['id'=>$x->id,'bidder_email'=>$x->bidder_email])) !!}</script>
@endsection
