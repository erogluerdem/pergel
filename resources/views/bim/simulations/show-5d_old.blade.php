@extends('layouts.master')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3>5D Maliyet Simülasyonu - #{{ $id }}</h3>
        <div>
            <a class="btn btn-outline-secondary" href="{{ route('bim.simulations.index') }}">Geri</a>
            <button id="playBtn" class="btn btn-primary">▶️ Oynat</button>
            <button id="recomputeBtn" class="btn btn-sm btn-warning">Yeniden Hesapla</button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body" style="height:420px; background:#f8f9fa; display:flex; align-items:center; justify-content:center">
                    <div id="viewerPlaceholder">4D Viewer (üst)</div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5>Canlı Finans Grafiği</h5>
                    <canvas id="costChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Görevler</h5>
                    <ul>
                        @foreach($tasks as $t)
                            <li>{{ $t['name'] }} ({{ $t['start'] }} → {{ $t['end'] }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const costs = @json($costs);
    const ctx = document.getElementById('costChart').getContext('2d');
    const labels = costs.map(c=>c.date);
    const planned = costs.map(c=>c.planned);
    const actual = costs.map(c=>c.actual);
    // keep chart instance so we can update it without recreating
    let costChart = new Chart(ctx,{type:'line',data:{labels:labels,datasets:[{label:'Planlanan Maliyet',data:planned,borderColor:'blue',fill:false},{label:'Gerçekleşen Maliyet',data:actual,borderColor:'red',fill:false}]},options:{responsive:true}});

    const recomputeBtn = document.getElementById('recomputeBtn');
    const alertBox = document.createElement('div');
    alertBox.id = 'recomputeAlert';
    alertBox.className = 'mt-2';
    recomputeBtn.parentNode.insertBefore(alertBox, recomputeBtn.nextSibling);

    recomputeBtn.addEventListener('click', async ()=>{
        recomputeBtn.disabled = true;
        const originalText = recomputeBtn.innerText;
        recomputeBtn.innerText = 'Hesaplanıyor...';
        alertBox.innerHTML = '';
        try{
            const res = await fetch('{{ route('bim.simulations.recompute_costs',['id'=>$id]) }}', { method:'POST', headers: { 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json' } });
            if (!res.ok){ throw new Error('HTTP ' + res.status); }
            const j = await res.json();
            if (j.ok){
                const s = j.series;
                chartUpdate(s);
                alertBox.innerHTML = '<div class="alert alert-success">Maliyetler yeniden hesaplandı.</div>';
            } else {
                alertBox.innerHTML = '<div class="alert alert-danger">Sunucudan hata döndü.</div>';
            }
        } catch (err){
            alertBox.innerHTML = '<div class="alert alert-danger">Hata: ' + (err.message||'bilinmeyen') + '</div>';
        } finally {
            recomputeBtn.disabled = false;
            recomputeBtn.innerText = originalText;
        }
    });

    function chartUpdate(series){
        const labels = series.map(x=>x.date);
        const planned = series.map(x=>x.planned);
        const actual = series.map(x=>x.actual);
        // update existing chart datasets and labels
        costChart.data.labels = labels;
        if (costChart.data.datasets.length < 2){
            costChart.data.datasets = [
                {label:'Planlanan Maliyet', data: planned, borderColor:'blue', fill:false},
                {label:'Gerçekleşen Maliyet', data: actual, borderColor:'red', fill:false}
            ];
        } else {
            costChart.data.datasets[0].data = planned;
            costChart.data.datasets[1].data = actual;
        }
        costChart.update();
    }
</script>
@endsection
@endsection
