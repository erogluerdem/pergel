@extends('layouts.master')
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="card-body text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold">
                                <i class="fas fa-clock me-2"></i>4D Zaman Simülasyonu
                            </h4>
                            <p class="mb-0 opacity-90">{{ $sim->name }} - İnşaat sırasını görselleştirin</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a class="btn btn-light btn-sm" href="{{ route('bim.simulations.index') }}">
                                <i class="fas fa-arrow-left me-1"></i>Geri
                            </a>
                            <div class="btn-group" role="group">
                                <button id="playBtn" class="btn btn-light btn-sm">
                                    <i class="fas fa-play me-1"></i>Oynat
                                </button>
                                <button id="pauseBtn" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-pause me-1"></i>Duraklat
                                </button>
                                <button id="resetBtn" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-redo me-1"></i>Sıfırla
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Viewer -->
        <div class="col-lg-8">
            <!-- 3D Viewer -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light border-0 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-cube me-2"></i>3D Model Viewer
                        </h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="resetView()">
                                <i class="fas fa-home"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="toggleWireframe()">
                                <i class="fas fa-eye"></i>
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item" onclick="exportScreenshot()">
                                        <i class="fas fa-camera me-2"></i>Ekran Görüntüsü Al
                                    </button></li>
                                    <li><button class="dropdown-item" onclick="toggleMeasure()">
                                        <i class="fas fa-ruler me-2"></i>Ölçüm Modu
                                    </button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="viewerContainer" style="height:520px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); position: relative;">
                        <div id="viewerPlaceholder" class="d-flex align-items-center justify-content-center h-100">
                            <div class="text-center">
                                <div class="spinner-border text-primary mb-3" role="status">
                                    <span class="visually-hidden">Yükleniyor...</span>
                                </div>
                                <p class="text-muted">3D Model yükleniyor...</p>
                            </div>
                        </div>
                        <div id="bimViewer" style="height:100%;width:100%;">
                            <canvas id="ifcCanvas" style="width:100%;height:100%"></canvas>
                        </div>

                        <!-- Viewer Controls Overlay -->
                        <div class="position-absolute top-0 start-0 m-3">
                            <div class="bg-white rounded shadow-sm p-2">
                                <small class="text-muted d-block">Seçili Eleman:</small>
                                <strong id="selectedElement" class="text-primary">—</strong>
                            </div>
                        </div>

                        <!-- Progress Indicator -->
                        <div class="position-absolute bottom-0 end-0 m-3">
                            <div class="bg-white rounded shadow-sm p-2">
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-2">İlerleme:</small>
                                    <div class="progress" style="width: 100px; height: 6px;">
                                        <div class="progress-bar bg-success" id="simulationProgress" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted ms-2" id="progressText">0%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Controls -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-2">
                    <h6 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Zaman Çizelgesi Kontrolü
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline-container mb-3">
                        <input id="timeSlider" type="range" min="0" max="100" value="0" class="form-range timeline-slider">
                        <div class="d-flex justify-content-between mt-2">
                            <div class="text-start">
                                <small class="text-muted">Başlangıç</small>
                                <div class="fw-bold" id="startDate">2025-10-01</div>
                            </div>
                            <div class="text-center">
                                <small class="text-muted">Şu an</small>
                                <div class="fw-bold text-primary" id="currentDate">2025-10-01</div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">Bitiş</small>
                                <div class="fw-bold" id="endDate">2026-01-01</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-secondary btn-sm" onclick="changeSpeed(0.5)">
                                <i class="fas fa-backward"></i> 0.5x
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="changeSpeed(1)" id="normalSpeed">
                                <i class="fas fa-play"></i> 1x
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="changeSpeed(2)">
                                <i class="fas fa-forward"></i> 2x
                            </button>
                        </div>
                        <div class="d-flex align-items-center">
                            <small class="text-muted me-2">Görünürlük:</small>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="showCompleted" checked>
                                <label class="form-check-label" for="showCompleted">
                                    Tamamlanan görevleri göster
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Task Timeline -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>Görev Listesi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="task-list" style="max-height: 300px; overflow-y: auto;">
                        @foreach($tasks as $index => $t)
                        <div class="task-item mb-2 p-2 border rounded" data-task-id="{{ $t['id'] }}" onclick="selectTask({{ $t['id'] }})">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold task-name">{{ $t['name'] }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>{{ $t['start'] }} → {{ $t['end'] }}
                                    </small>
                                </div>
                                <div class="task-status">
                                    <span class="badge bg-secondary task-badge">Beklemede</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="progress" style="height: 4px;">
                                    <div class="progress-bar bg-success task-progress" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Element-Task Mapping -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0">
                        <i class="fas fa-link me-2"></i>Eleman-Görev Eşleme
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">1. 3D modelde eleman seçin</small><br>
                        <small class="text-muted">2. Görev listesinden görev seçin</small><br>
                        <small class="text-muted">3. "Eşle" butonuna tıklayın</small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <small class="text-muted">Seçili Eleman:</small>
                            <strong id="selectedElementInfo" class="text-primary">—</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <small class="text-muted">Seçili Görev:</small>
                            <strong id="selectedTaskInfo" class="text-warning">—</strong>
                        </div>
                    </div>

                    <button id="saveMapping" class="btn btn-success btn-sm w-100 mb-3">
                        <i class="fas fa-save me-2"></i>Eşlemeyi Kaydet
                    </button>

                    <hr>

                    <h6 class="mb-2">Mevcut Eşlemeler</h6>
                    <div id="mappingsList" style="max-height: 200px; overflow-y: auto;">
                        <!-- Eşlemeler buraya gelecek -->
                    </div>
                </div>
            </div>

            <!-- Simulation Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Simülasyon Bilgileri
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Toplam Görev:</small>
                        <strong class="float-end">{{ count($tasks) }}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Eşlenen Eleman:</small>
                        <strong class="float-end" id="mappedElementsCount">0</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Süre:</small>
                        <strong class="float-end">{{ $sim->created_at ? $sim->created_at->diffForHumans() : 'Bilinmiyor' }}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Durum:</small>
                        <span class="badge bg-success float-end">Aktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.task-item {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent !important;
}

.task-item:hover {
    transform: translateX(5px);
    border-color: var(--bs-primary) !important;
    background-color: rgba(var(--bs-primary-rgb), 0.05);
}

.task-item.selected {
    border-color: var(--bs-warning) !important;
    background-color: rgba(var(--bs-warning-rgb), 0.1);
}

.task-item.active {
    border-color: var(--bs-success) !important;
    background-color: rgba(var(--bs-success-rgb), 0.1);
}

.timeline-slider {
    height: 8px;
    background: linear-gradient(to right, #dc3545, #ffc107, #28a745);
    border-radius: 10px;
}

.timeline-slider::-webkit-slider-thumb {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid var(--bs-primary);
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

.timeline-slider::-moz-range-thumb {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid var(--bs-primary);
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

.task-progress {
    transition: width 0.5s ease;
}

.task-badge {
    transition: all 0.3s ease;
}

#bimViewer {
    border-radius: 0 0 8px 8px;
}

.mapping-item {
    background: rgba(var(--bs-info-rgb), 0.1);
    border: 1px solid rgba(var(--bs-info-rgb), 0.2);
    border-radius: 6px;
    padding: 8px;
    margin-bottom: 6px;
}
</style>

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
    let IFCDemo;
    try {
        const mod = await import('/js/dist/index.js');
        IFCDemo = mod.IFCDemo || mod.default?.IFCDemo || mod.default;
    } catch (e) {
        document.getElementById('viewerPlaceholder').innerHTML = '<div class="p-4 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Viewer kütüphanesi yüklenemedi.</div>';
        console.error('Failed to load local web-ifc-viewer', e);
    }

    const simId = {{ $id }};
    let selectedElement = null;
    let selectedTask = null;
    let playing = false;
    let playSpeed = 1;
    let viewer = null;

    // Initialize viewer
    (async function initViewer(){
        const container = document.getElementById('bimViewer');
        const placeholder = document.getElementById('viewerPlaceholder');
        
        try{
            if (!IFCDemo) {
                throw new Error('IFCDemo not available');
            }
            
            viewer = new IFCDemo({ container });
            const url = '{{ optional($model)->file_path ? route('bim.models.file',['id'=>$model->id]) : '' }}';
            
            if (!url) { 
                placeholder.innerHTML = '<div class="p-4 text-muted text-center"><i class="fas fa-cube fa-3x mb-3"></i><p>Model bulunamadı</p></div>'; 
                return; 
            }
            
            placeholder.style.display = 'none';
            await viewer.loadIfcUrl(url);

            // Element selection handler
            viewer.on('pick', (item)=>{
                selectedElement = item?.expressID || item?.id || null;
                document.getElementById('selectedElement').innerText = selectedElement || '—';
                document.getElementById('selectedElementInfo').innerText = selectedElement || '—';
                
                if (selectedElement) {
                    showNotification('Eleman seçildi: ' + selectedElement, 'success');
                }
            });
            
            showNotification('3D Model başarıyla yüklendi', 'success');
        } catch(e){
            placeholder.innerHTML = '<div class="p-4 text-danger text-center"><i class="fas fa-exclamation-triangle fa-3x mb-3"></i><p>Viewer başlatılamadı: '+(e.message||e)+'</p></div>';
            console.error(e);
        }
    })();

    // Player controls
    document.getElementById('playBtn').addEventListener('click', ()=>{ 
        playing = true; 
        updatePlayButton();
        runSimulation(); 
    });
    
    document.getElementById('pauseBtn').addEventListener('click', ()=>{ 
        playing = false; 
        updatePlayButton();
    });
    
    document.getElementById('resetBtn').addEventListener('click', ()=>{ 
        playing = false; 
        document.getElementById('timeSlider').value = 0;
        updateSimulationTime();
        updatePlayButton();
    });

    function updatePlayButton() {
        const playBtn = document.getElementById('playBtn');
        if (playing) {
            playBtn.innerHTML = '<i class="fas fa-pause me-1"></i>Durdur';
            playBtn.className = 'btn btn-warning btn-sm';
        } else {
            playBtn.innerHTML = '<i class="fas fa-play me-1"></i>Oynat';
            playBtn.className = 'btn btn-light btn-sm';
        }
    }

    function runSimulation() { 
        if (!playing) return; 
        
        let slider = document.getElementById('timeSlider');
        let v = parseInt(slider.value) || 0; 
        v = Math.min(100, v + (1 * playSpeed)); 
        slider.value = v; 
        
        updateSimulationTime();
        updateTaskProgress();
        
        if (v < 100) {
            setTimeout(runSimulation, 200 / playSpeed); 
        } else {
            playing = false;
            updatePlayButton();
            showNotification('Simülasyon tamamlandı', 'success');
        }
    }

    function updateSimulationTime() {
        const progress = document.getElementById('timeSlider').value;
        document.getElementById('progressText').innerText = progress + '%';
        document.getElementById('simulationProgress').style.width = progress + '%';
        
        // Update current date (simplified)
        const startDate = new Date('2025-10-01');
        const endDate = new Date('2026-01-01');
        const totalDays = (endDate - startDate) / (1000 * 60 * 60 * 24);
        const currentDays = (progress / 100) * totalDays;
        const currentDate = new Date(startDate.getTime() + currentDays * 24 * 60 * 60 * 1000);
        
        document.getElementById('currentDate').innerText = currentDate.toLocaleDateString('tr-TR');
    }

    function updateTaskProgress() {
        const progress = parseInt(document.getElementById('timeSlider').value);
        const tasks = document.querySelectorAll('.task-item');
        
        tasks.forEach((task, index) => {
            const taskProgress = Math.max(0, Math.min(100, progress - (index * 20)));
            const progressBar = task.querySelector('.task-progress');
            const badge = task.querySelector('.task-badge');
            
            progressBar.style.width = taskProgress + '%';
            
            if (taskProgress === 0) {
                badge.className = 'badge bg-secondary task-badge';
                badge.innerText = 'Beklemede';
                task.classList.remove('active');
            } else if (taskProgress < 100) {
                badge.className = 'badge bg-warning task-badge';
                badge.innerText = 'Devam Ediyor';
                task.classList.add('active');
            } else {
                badge.className = 'badge bg-success task-badge';
                badge.innerText = 'Tamamlandı';
                task.classList.remove('active');
            }
        });
    }

    // Task selection
    window.selectTask = function(taskId) {
        selectedTask = taskId;
        
        // Update UI
        document.querySelectorAll('.task-item').forEach(item => {
            item.classList.remove('selected');
        });
        
        document.querySelector(`[data-task-id="${taskId}"]`).classList.add('selected');
        
        const taskName = document.querySelector(`[data-task-id="${taskId}"] .task-name`).innerText;
        document.getElementById('selectedTaskInfo').innerText = taskName;
        
        showNotification('Görev seçildi: ' + taskName, 'info');
    }

    // Speed controls
    window.changeSpeed = function(speed) {
        playSpeed = speed;
        document.querySelectorAll('[onclick*="changeSpeed"]').forEach(btn => {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-secondary');
        });
        event.target.classList.remove('btn-outline-secondary');
        event.target.classList.add('btn-primary');
    }

    // Save mapping
    document.getElementById('saveMapping').addEventListener('click', async ()=>{
        if (!selectedElement) {
            showNotification('Önce model elemanı seçin', 'warning');
            return;
        }
        
        if (!selectedTask) {
            showNotification('Önce görev seçin', 'warning');
            return;
        }

        const payload = { mappings: { [selectedElement]: selectedTask } };
        
        try {
            const res = await fetch('{{ route('bim.simulations.map_elements',['id'=>$id]) }}', { 
                method:'POST', 
                headers: { 
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':'{{ csrf_token() }}' 
                }, 
                body: JSON.stringify(payload) 
            });
            
            const j = await res.json();
            if (j.ok) {
                showNotification('Eşleme başarıyla kaydedildi', 'success');
                loadMappings();
            } else {
                showNotification('Eşleme kaydedilemedi', 'error');
            }
        } catch (error) {
            showNotification('Bağlantı hatası', 'error');
        }
    });

    // Load existing mappings
    function loadMappings(){
        fetch(window.location.pathname + '/map-elements', { 
            method:'GET', 
            headers:{'Accept':'application/json'} 
        }).then(r=>r.json()).then(j=>{
            const mappings = j.mappings || {};
            const list = document.getElementById('mappingsList');
            list.innerHTML = '';
            
            let count = 0;
            for(const [el, task] of Object.entries(mappings)){
                count++;
                const div = document.createElement('div');
                div.className = 'mapping-item';
                div.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Eleman:</small> <strong>${el}</strong><br>
                            <small class="text-muted">Görev:</small> <strong>${task}</strong>
                        </div>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeMapping('${el}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                list.appendChild(div);
            }
            
            document.getElementById('mappedElementsCount').innerText = count;
            
            if (count === 0) {
                list.innerHTML = '<div class="text-muted text-center py-3"><i class="fas fa-info-circle me-2"></i>Henüz eşleme yapılmamış</div>';
            }
        }).catch(()=>{
            document.getElementById('mappingsList').innerHTML = '<div class="text-danger text-center py-3"><i class="fas fa-exclamation-triangle me-2"></i>Eşlemeler yüklenemedi</div>';
        });
    }

    window.removeMapping = async function(elementId) {
        if (!confirm('Bu eşlemeyi silmek istediğinizden emin misiniz?')) return;
        
        try {
            // First get current mappings, then remove the specified one
            const response = await fetch(window.location.pathname + '/map-elements', { 
                method:'GET', 
                headers:{'Accept':'application/json'} 
            });
            const data = await response.json();
            const mappings = data.mappings || {};
            
            delete mappings[elementId];
            
            await fetch('{{ route('bim.simulations.map_elements',['id'=>$id]) }}', { 
                method:'POST', 
                headers: { 
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':'{{ csrf_token() }}' 
                }, 
                body: JSON.stringify({ mappings }) 
            });
            
            showNotification('Eşleme silindi', 'success');
            loadMappings();
        } catch (error) {
            showNotification('Eşleme silinemedi', 'error');
        }
    }

    // Utility functions
    function showNotification(message, type = 'info') {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 5000);
    }

    window.resetView = function() {
        if (viewer && viewer.resetCamera) {
            viewer.resetCamera();
        }
    }

    window.toggleWireframe = function() {
        if (viewer && viewer.toggleWireframe) {
            viewer.toggleWireframe();
        }
    }

    window.exportScreenshot = function() {
        if (viewer && viewer.exportScreenshot) {
            viewer.exportScreenshot();
        } else {
            showNotification('Ekran görüntüsü özelliği henüz mevcut değil', 'warning');
        }
    }

    window.toggleMeasure = function() {
        showNotification('Ölçüm modu yakında eklenecek', 'info');
    }

    // Initialize
    loadMappings();
    updateSimulationTime();
    
    // Timeline slider handler
    document.getElementById('timeSlider').addEventListener('input', updateSimulationTime);
</script>
@endsection
@endsection