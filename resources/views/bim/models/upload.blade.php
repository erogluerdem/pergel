@php($isAjax = request()->ajax())

@if(!$isAjax)
@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/bim-models.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('content')
<div class="bim-models-container">
    <div class="container-fluid">
        <!-- Upload Header -->
        <div class="bim-dashboard-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="bim-dashboard-title">
                        <i class="fas fa-cloud-upload-alt"></i> BIM Model Yükle
                    </h1>
                    <p class="bim-dashboard-subtitle">
                        IFC, RVT, DWG veya PDF formatında 3D model yükleyin
                    </p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('bim.models.index') }}" class="bim-btn-secondary">
                        <i class="fas fa-arrow-left"></i> Geri Dön
                    </a>
                </div>
            </div>
        </div>
@endif

        <!-- Upload Form -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="bim-filters-card">
                    <form method="POST" action="{{ route('bim.models.store') }}" enctype="multipart/form-data" id="uploadForm">
                        @csrf

                        <!-- File Upload Zone -->
                        <div class="bim-file-upload-zone" id="uploadZone">
                            <div class="bim-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <h4 id="dropText">Dosyayı buraya sürükleyin</h4>
                            <p class="text-muted mb-3">veya tıklayarak dosya seçin</p>
                            <input type="file" name="file" id="fileInput" accept=".ifc,.rvt,.dwg,.pdf" required style="display: none;">
                            <button type="button" class="bim-btn-primary" onclick="document.getElementById('fileInput').click()">
                                <i class="fas fa-folder-open"></i> Dosya Seç
                            </button>
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Desteklenen formatlar: IFC, RVT, DWG, PDF (Maksimum 500MB)
                                </small>
                            </div>
                        </div>

                        <!-- Selected File Info -->
                        <div id="fileInfo" class="mt-4" style="display: none;">
                            <div class="alert alert-info">
                                <div class="row align-items-center">
                                    <div class="col-md-1">
                                        <i class="fas fa-file-alt fa-2x"></i>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="mb-1" id="fileName"></h6>
                                        <small class="text-muted" id="fileSize"></small>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFile()">
                                            <i class="fas fa-times"></i> Kaldır
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Model Information -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">
                                        <i class="fas fa-tag"></i> Model Adı *
                                    </label>
                                    <input type="text" name="name" id="name" class="form-control" required
                                           placeholder="Model için açıklayıcı bir ad girin">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discipline">
                                        <i class="fas fa-layer-group"></i> Disiplin *
                                    </label>
                                    <select name="discipline" id="discipline" class="form-control" required>
                                        <option value="">Disiplin seçin</option>
                                        <option value="Mimari">Mimari</option>
                                        <option value="Statik">Statik</option>
                                        <option value="Mekanik">Mekanik</option>
                                        <option value="Elektrik">Elektrik</option>
                                        <option value="Peyzaj">Peyzaj</option>
                                        <option value="Altyapı">Altyapı</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="version_notes">
                                <i class="fas fa-sticky-note"></i> Sürüm Notları
                            </label>
                            <textarea name="version_notes" id="version_notes" class="form-control" rows="3"
                                      placeholder="Bu sürümle ilgili önemli değişiklikler, notlar..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center mt-4">
                            <button type="submit" class="bim-btn-primary" id="submitBtn">
                                <i class="fas fa-upload"></i> Modeli Yükle ve İşle
                            </button>
                            <button type="button" class="bim-btn-secondary ml-3" onclick="window.history.back()">
                                <i class="fas fa-times"></i> İptal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Upload Progress -->
        <div id="uploadProgress" class="row justify-content-center" style="display: none;">
            <div class="col-md-8">
                <div class="bim-filters-card">
                    <div class="text-center">
                        <h5>Model Yükleniyor...</h5>
                        <div class="bim-loading-progress mt-4">
                            <div class="bim-progress-bar">
                                <div class="bim-progress-fill" id="uploadProgressBar" style="width: 0%"></div>
                            </div>
                            <small class="bim-progress-text mt-2" id="uploadProgressText">Dosya hazırlanıyor...</small>
                        </div>
                        <div class="mt-3">
                            <button type="button" class="bim-btn-secondary" id="cancelUpload" style="display: none;">
                                <i class="fas fa-times"></i> İptal Et
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
<div id="loadingOverlay" class="bim-loading-overlay">
    <div class="text-center text-white">
        <div class="bim-spinner"></div>
        <h5 class="mt-3">Model yükleniyor...</h5>
        <p>Dosya boyutuna göre bu işlem birkaç dakika sürebilir.</p>
    </div>
</div>

@if(!$isAjax)
    </div>
</div>
@endsection
@endif

@if($isAjax)
<!-- When requested via AJAX we return inline script so the panel can execute it immediately -->
<script>
// Immediately-invoked block so handlers run when HTML is injected via AJAX
(function(){
    try {
        const uploadZone = document.getElementById('uploadZone');
        const fileInput = document.getElementById('fileInput');
        const fileInfo = document.getElementById('fileInfo');
        const form = document.getElementById('uploadForm');
        const nameInput = document.getElementById('name');

        if (!uploadZone || !fileInput || !fileInfo || !form) return; // nothing to do

        function preventDefaults(e) { e.preventDefault(); e.stopPropagation(); }
        ['dragenter','dragover','dragleave','drop'].forEach(ev => uploadZone.addEventListener(ev, preventDefaults));
        ['dragenter','dragover'].forEach(ev => uploadZone.addEventListener(ev, () => uploadZone.classList.add('dragover')));
        ['dragleave','drop'].forEach(ev => uploadZone.addEventListener(ev, () => uploadZone.classList.remove('dragover')));

        uploadZone.addEventListener('drop', handleDrop);
        uploadZone.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', handleFileSelect);

        function handleDrop(e) { const files = e.dataTransfer.files; if (files.length) { fileInput.files = files; handleFileSelect(); } }
        function handleFileSelect() { const file = fileInput.files[0]; if (file) { displayFileInfo(file); if (!nameInput.value) nameInput.value = file.name.replace(/\.[^/.]+$/, ''); } }
        function displayFileInfo(file) { const fn = document.getElementById('fileName'); const fs = document.getElementById('fileSize'); if(fn) fn.textContent = file.name; if(fs) fs.textContent = formatFileSize(file.size); fileInfo.style.display = 'block'; uploadZone.style.display = 'none'; }
        window.clearFile = function() { fileInput.value = ''; fileInfo.style.display = 'none'; uploadZone.style.display = 'block'; }
        function formatFileSize(bytes) { if (bytes === 0) return '0 Bytes'; const k = 1024; const sizes = ['Bytes','KB','MB','GB']; const i = Math.floor(Math.log(bytes)/Math.log(k)); return parseFloat((bytes/Math.pow(k,i)).toFixed(2)) + ' ' + sizes[i]; }

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); if (!fileInput.files[0]) { alert('Lütfen bir dosya seçin.'); return; }
                const formData = new FormData(form); const xhr = new XMLHttpRequest(); const prog = document.getElementById('uploadProgress'); if(prog) prog.style.display = 'block'; const card = document.querySelector('.bim-filters-card'); if(card) card.style.display = 'none';
                xhr.upload.addEventListener('progress', function(e) { if (e.lengthComputable) { const percent = Math.round((e.loaded/e.total)*100); const bar = document.getElementById('uploadProgressBar'); const text = document.getElementById('uploadProgressText'); if(bar) bar.style.width = percent + '%'; if(text) text.textContent = `Yükleniyor... ${percent}% (${formatFileSize(e.loaded)} / ${formatFileSize(e.total)})`; } });
                xhr.addEventListener('load', function() { if (xhr.status===200) { const text = document.getElementById('uploadProgressText'); const bar = document.getElementById('uploadProgressBar'); if(text) text.textContent='İşleniyor...'; if(bar) bar.style.width='100%'; setTimeout(()=>{ window.location.href='{{ route('bim.models.index') }}'; },800); } else { alert('Yükleme başarısız: '+xhr.responseText); const prog = document.getElementById('uploadProgress'); if(prog) prog.style.display='none'; if(card) card.style.display='block'; } });
                xhr.addEventListener('error', function(){ alert('Yükleme hatası oluştu.'); const prog = document.getElementById('uploadProgress'); if(prog) prog.style.display='none'; if(card) card.style.display='block'; });
                xhr.open('POST', form.action); xhr.send(formData);
            });
        }
    } catch (e) {
        console && console.error && console.error('Upload partial init error', e);
    }
})();
</script>
@else
@push('scripts')
<script>
// Non-AJAX rendering: attach same handlers when the page is rendered normally
document.addEventListener('DOMContentLoaded', function() {
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const fileInfo = document.getElementById('fileInfo');
    const form = document.getElementById('uploadForm');
    const nameInput = document.getElementById('name');

    // If any of the core elements are missing, skip attaching handlers
    if (!uploadZone || !fileInput || !fileInfo || !form) return;

    function preventDefaults(e) { e.preventDefault(); e.stopPropagation(); }
    ['dragenter','dragover','dragleave','drop'].forEach(ev => uploadZone.addEventListener(ev, preventDefaults));
    ['dragenter','dragover'].forEach(ev => uploadZone.addEventListener(ev, () => uploadZone.classList.add('dragover')));
    ['dragleave','drop'].forEach(ev => uploadZone.addEventListener(ev, () => uploadZone.classList.remove('dragover')));

    uploadZone.addEventListener('drop', handleDrop);
    uploadZone.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', handleFileSelect);

    function handleDrop(e) { const files = e.dataTransfer.files; if (files.length) { fileInput.files = files; handleFileSelect(); } }
    function handleFileSelect() { const file = fileInput.files[0]; if (file) { displayFileInfo(file); if (!nameInput.value) nameInput.value = file.name.replace(/\.[^/.]+$/, ''); } }
    function displayFileInfo(file) { const fn = document.getElementById('fileName'); const fs = document.getElementById('fileSize'); if(fn) fn.textContent = file.name; if(fs) fs.textContent = formatFileSize(file.size); fileInfo.style.display = 'block'; uploadZone.style.display = 'none'; }
    window.clearFile = function() { fileInput.value = ''; fileInfo.style.display = 'none'; uploadZone.style.display = 'block'; }
    function formatFileSize(bytes) { if (bytes === 0) return '0 Bytes'; const k = 1024; const sizes = ['Bytes','KB','MB','GB']; const i = Math.floor(Math.log(bytes)/Math.log(k)); return parseFloat((bytes/Math.pow(k,i)).toFixed(2)) + ' ' + sizes[i]; }

    form.addEventListener('submit', function(e) {
        e.preventDefault(); if (!fileInput.files[0]) { alert('Lütfen bir dosya seçin.'); return; }
        const formData = new FormData(form); const xhr = new XMLHttpRequest(); const prog = document.getElementById('uploadProgress'); if(prog) prog.style.display = 'block'; const card = document.querySelector('.bim-filters-card'); if(card) card.style.display = 'none';
        xhr.upload.addEventListener('progress', function(e) { if (e.lengthComputable) { const percent = Math.round((e.loaded/e.total)*100); const bar = document.getElementById('uploadProgressBar'); const text = document.getElementById('uploadProgressText'); if(bar) bar.style.width = percent + '%'; if(text) text.textContent = `Yükleniyor... ${percent}% (${formatFileSize(e.loaded)} / ${formatFileSize(e.total)})`; } });
        xhr.addEventListener('load', function() { if (xhr.status===200) { const text = document.getElementById('uploadProgressText'); const bar = document.getElementById('uploadProgressBar'); if(text) text.textContent='İşleniyor...'; if(bar) bar.style.width='100%'; setTimeout(()=>{ window.location.href='{{ route('bim.models.index') }}'; },2000); } else { alert('Yükleme başarısız: '+xhr.responseText); const prog = document.getElementById('uploadProgress'); if(prog) prog.style.display='none'; if(card) card.style.display='block'; } });
        xhr.addEventListener('error', function(){ alert('Yükleme hatası oluştu.'); const prog = document.getElementById('uploadProgress'); if(prog) prog.style.display='none'; if(card) card.style.display='block'; });
        xhr.open('POST', form.action); xhr.send(formData);
    });
});
</script>
@endpush
@endif

