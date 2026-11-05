@extends('layouts.master')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3>4D Zaman Simülasyonu - #{{ $id }}</h3>
        <div>
            <a class="btn btn-outline-secondary" href="{{ route('bim.simulations.index') }}">Geri</a>
            <button id="playBtn" class="btn btn-primary">▶️ Oynat</button>
            <button id="pauseBtn" class="btn btn-secondary">⏸️ Duraklat</button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body" style="height:520px; background:#f8f9fa; display:flex; align-items:center; justify-content:center">
                    <div id="viewerPlaceholder">
                        <div id="bimViewer" style="height:100%;width:100%"><canvas id="ifcCanvas" style="width:100%;height:100%"></canvas></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <input id="timeSlider" type="range" min="0" max="100" value="0" style="width:100%">
                    <div class="d-flex justify-content-between"><small id="currentDate">2025-10-01</small><small id="endDate">2026-01-01</small></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Planlama (Gantt)</h5>
                    <ul>
                        @foreach($tasks as $t)
                            <li data-task-id="{{ $t['id'] }}">{{ $t['name'] }} ({{ $t['start'] }} → {{ $t['end'] }})</li>
                        @endforeach
                    </ul>
                    <p>Sürükle-bırak ile model elemanlarını görevlerle eşleyin (MVP: manuel seçim)</p>
                    <div class="mt-2">
                        <div>Seçili Model Elemanı: <strong id="selectedElement">—</strong></div>
                        <button id="saveMapping" class="btn btn-sm btn-success mt-2">Eşlemeyi Kaydet</button>
                    </div>
                    <hr>
                    <h6>Mevcut Eşlemeler</h6>
                    <ul id="mappingsList">
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@section('scripts')
<script type="importmap">
{
    "imports": {
        "three": "/js/three.module.js",
        "three-mesh-bvh": "/js/three-mesh-bvh.module.js"
    }
}
</script>
<script type="module">
    // local-only import for debugging
    let IFCDemo;
    try {
        const mod = await import('/js/dist/index.js');
        IFCDemo = mod.IFCDemo || mod.default?.IFCDemo || mod.default;
    } catch (e) {
        document.getElementById('viewerPlaceholder').innerHTML = '<div class="p-4 text-danger">Viewer kütüphanesi yüklenemedi (local).</div>';
        console.error('Failed to load local web-ifc-viewer', e);
    }

    const simId = {{ $id }};
let selectedElement = null;

// player controls
let playing=false; let slider=document.getElementById('timeSlider');
document.getElementById('playBtn').addEventListener('click', ()=>{ playing=true; run(); });
document.getElementById('pauseBtn').addEventListener('click', ()=>{ playing=false; });
function run(){ if(!playing) return; let v=parseInt(slider.value)||0; v=Math.min(100,v+1); slider.value=v; document.getElementById('currentDate').innerText = '...'; if(v<100) setTimeout(run,150); }

// viewer + click-to-select setup
(async function(){
    const container = document.getElementById('bimViewer');
    try{
        const viewer = new IFCDemo({ container });
        const url = '{{ optional($model)->file_path ? route('bim.models.file',['id'=>$model->id]) : '' }}';
        if (!url) { container.innerHTML = '<div class="p-4 text-muted">Model yok</div>'; return; }
        await viewer.loadIfcUrl(url);

        // on pick
        viewer.on('pick', (item)=>{
            // item contains ifcExpressID and modelID depending on viewer version
            selectedElement = item?.expressID || item?.id || null;
            document.getElementById('selectedElement').innerText = selectedElement || '—';
        });
    } catch(e){
        container.innerHTML = '<div class="p-4 text-danger">Viewer başlatılamadı: '+(e.message||e)+'</div>';
        console.error(e);
    }
    })();

// load existing mappings
(function loadMappings(){
    fetch(window.location.pathname.replace('/4d','') + '/map-elements', { method:'GET', headers:{'Accept':'application/json'} }).then(r=>r.json()).then(j=>{
        const mappings = j.mappings || {};
        const list = document.getElementById('mappingsList');
        list.innerHTML = '';
        for(const [el,task] of Object.entries(mappings)){
            const li = document.createElement('li');
            li.innerText = `Eleman ${el} → Görev ${task}`;
            const btn = document.createElement('button'); btn.className='btn btn-sm btn-link text-danger'; btn.innerText='Kaldır';
            btn.addEventListener('click', async ()=>{
                // remove mapping locally and save
                delete mappings[el];
                await fetch('{{ url("/bim/simulations/") }}'+simId+'/map-elements', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body:JSON.stringify({ mappings }) });
                loadMappings();
            });
            li.appendChild(btn);
            list.appendChild(li);
        }
    }).catch(()=>{});
})();

// Save mapping: send { mappings: { elementId: taskId } }
document.getElementById('saveMapping').addEventListener('click', async ()=>{
    if (!selectedElement) return alert('Önce model elemanı seçin');
    // find selected task
    const taskItems = document.querySelectorAll('[data-task-id]');
    let selectedTask = null;
    for (const it of taskItems){ if (it.classList.contains('selected-task')) selectedTask = it.getAttribute('data-task-id'); }
    if (!selectedTask) {
        // if none selected, pick first
        selectedTask = taskItems[0]?.getAttribute('data-task-id');
    }
    if (!selectedTask) return alert('Görev seçili değil');

    const payload = { mappings: { [selectedElement]: selectedTask } };
    const res = await fetch('{{ route('bim.simulations.map_elements',['id'=>$id]) }}', { method:'POST', headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' }, body: JSON.stringify(payload) });
    const j = await res.json();
    if (j.ok) alert('Eşleme kaydedildi'); else alert('Hata');
});

// simple task click to select
document.querySelectorAll('[data-task-id]').forEach((el)=>{
    el.addEventListener('click', ()=>{
        document.querySelectorAll('[data-task-id]').forEach(x=>x.classList.remove('selected-task'));
        el.classList.add('selected-task');
    });
});
</script>
@endsection
@endsection
