@extends('layouts.master')

@section('title','ERP Entegrasyonu')
@section('page-title','ERP Entegrasyonu')

@section('content')
<style>
    .erp-kpi { min-height: 90px; }
    .erp-sync-btn .spinner-border{ width:18px;height:18px }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row mb-3">
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card erp-kpi">
            <div class="card-body">
                <small class="text-muted">Son Senkronizasyon</small>
                <div class="h5">{{ $lastSync ?? '-' }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card erp-kpi">
            <div class="card-body">
                <small class="text-muted">Bekleyen Kayıtlar</small>
                <div class="h5 text-warning">{{ $pending ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card erp-kpi">
            <div class="card-body">
                <small class="text-muted">Hatalı (24s)</small>
                <div class="h5 text-danger">{{ $failed24 ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card erp-kpi">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Eşlemeler</small>
                    <div class="h5">{{ isset($mappings) ? (is_countable($mappings) ? count($mappings) : $mappings->count()) : 0 }}</div>
                </div>
                <div>
                    <form id="erp-sync-form" method="post" action="{{ route('cost.erp.sync') }}">
                        @csrf
                        <button id="erpSyncBtn" class="btn btn-primary erp-sync-btn"><span id="erpSyncText">🔄 Senkronize Et</span> <span id="erpSyncSpinner" style="display:none" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Aktivite (son 7 gün)</h5>
                <div id="erp-activity-chart" style="height:320px;"></div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Senkr. Kayıtları</h5>
                <div class="mb-2 d-flex">
                    <input id="erpLogFilter" class="form-control form-control-sm mr-2" placeholder="Ara: mesaj veya durum">
                    <button id="erpRefresh" class="btn btn-sm btn-outline-secondary">Yenile</button>
                </div>
                <div class="table-responsive">
                    @if(\Illuminate\Support\Facades\Schema::hasTable('erp_logs'))
                        @php $logs = \DB::table('erp_logs')->orderBy('created_at','desc')->limit(200)->get(); @endphp
                    @else
                        @php $logs = collect([[ 'created_at'=>now()->subMinutes(5),'direction'=>'platform->erp','data_count'=>5,'status'=>'success','message'=>'Demo başarılı' ]]); @endphp
                    @endif
                    <table class="table table-sm table-striped">
                        <thead><tr><th style="width:160px">Tarih</th><th>Yön</th><th>Adet</th><th>Durum</th><th>Mesaj</th></tr></thead>
                        <tbody id="erpLogsBody">
                            @foreach($logs as $l)
                            <tr>
                                <td>{{ $l->created_at }}</td>
                                <td>{{ $l->direction }}</td>
                                <td>{{ $l->data_count }}</td>
                                <td><span class="badge badge-{{ $l->status=='success' ? 'success' : ($l->status=='failed' ? 'danger' : 'secondary') }}">{{ $l->status }}</span></td>
                                <td>{{ $l->message }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Veri Eşleştirme</h5>
                <form method="post" action="{{ route('cost.erp.mappings.save') }}">
                    @csrf
                    <div class="form-group"><label>Platform Kodu</label><input name="platform_code" class="form-control"></div>
                    <div class="form-group"><label>ERP Hesap</label><input name="erp_account" class="form-control"></div>
                    <button class="btn btn-primary btn-block">Kaydet</button>
                </form>
                <hr>
                <h6>Mevcut Eşlemeler</h6>
                <div style="max-height:200px;overflow:auto">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Platform</th><th>ERP</th></tr></thead>
                        <tbody>
                        @foreach($mappings as $m)
                            <tr><td>{{ $m->platform_code }}</td><td>{{ $m->erp_account }}</td></tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Ayarlar</h5>
                <form method="post" action="{{ route('cost.erp.settings.save') }}">
                    @csrf
                    <div class="form-group"><label>API URL</label><input name="api_url" class="form-control" value="{{ $settings['api_url'] ?? '' }}"></div>
                    <div class="form-group"><label>Kullanıcı</label><input name="username" class="form-control" value="{{ $settings['username'] ?? '' }}"></div>
                    <div class="form-group"><label>Token</label><input name="token" class="form-control" value="{{ $settings['token'] ?? '' }}"></div>
                    <div class="form-group"><label>Senkronizasyon</label>
                        <select name="cron" class="form-control"><option value="hourly" {{ (isset($settings['cron']) && $settings['cron']=='hourly')?'selected':'' }}>Her saat</option><option value="daily" {{ (isset($settings['cron']) && $settings['cron']=='daily')?'selected':'' }}>Günlük</option></select>
                    </div>
                    <button class="btn btn-secondary btn-block">Ayarları Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    // prepare activity data safely
    var rawActivity = @json($activity instanceof \Illuminate\Support\Collection ? $activity->toArray() : (is_array($activity) ? $activity : []));
    var labels = [];
    var successes = [];
    if(Array.isArray(rawActivity) && rawActivity.length){
        // rawActivity expected like [{day:'2025-..', success: X}, ...]
        rawActivity.reverse().forEach(function(r){ labels.push(r.day); successes.push(Number(r.success||0)); });
    }
    // fallback sample
    if(labels.length===0){
        var d = new Date();
        for(var i=6;i>=0;i--){ var day = new Date(d.getTime() - i*24*3600*1000); labels.push(day.toISOString().slice(0,10)); successes.push(0); }
    }

    var options = {
        series: [{ name: 'Başarılı', data: successes }],
        chart: { type: 'bar', height: 320 },
        xaxis: { categories: labels },
        colors: ['#1f8ef1'],
        tooltip: { y: { formatter: function(v){ return v + ' kayıt'; } } }
    };
    var chart = new ApexCharts(document.querySelector('#erp-activity-chart'), options);
    chart.render();

    // Sync button - AJAX submit to avoid full redirect UX when possible
    var syncForm = document.getElementById('erp-sync-form');
    var syncBtn = document.getElementById('erpSyncBtn');
    var syncSpinner = document.getElementById('erpSyncSpinner');
    if(syncForm){
        syncForm.addEventListener('submit', function(e){
            // allow normal POST for non-AJAX (e.g., auth redirect), but try AJAX
            e.preventDefault();
            syncSpinner.style.display = 'inline-block';
            syncBtn.disabled = true;
            var token = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : document.querySelector('input[name="_token"]').value;
            fetch(syncForm.action, { method: 'POST', headers: {'X-CSRF-TOKEN': token, 'Accept':'application/json'}, body: new FormData(syncForm) })
                .then(function(resp){ return resp.json().catch(function(){ return {ok:false}; }); })
                .then(function(json){
                    syncSpinner.style.display='none'; syncBtn.disabled=false;
                    if(json && json.success){ alert(json.success); } else { window.location.reload(); }
                }).catch(function(){ syncSpinner.style.display='none'; syncBtn.disabled=false; window.location.reload(); });
        });
    }

    // simple client-side log filter
    var logFilter = document.getElementById('erpLogFilter');
    if(logFilter){
        logFilter.addEventListener('input', function(){
            var q = this.value.toLowerCase();
            document.querySelectorAll('#erpLogsBody tr').forEach(function(row){
                var text = row.innerText.toLowerCase();
                row.style.display = text.indexOf(q)===-1 ? 'none' : '';
            });
        });
    }

    document.getElementById('erpRefresh').addEventListener('click', function(){ location.reload(); });
});
</script>
@endsection
