@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/bim-viewer.css') }}">
<link rel="stylesheet" href="/libs/fontawesome/css/all.min.css">
@endpush

@section('content')
<div class="bim-viewer-container">
    <div class="container-fluid">
        <!-- Viewer Header -->
        <div class="bim-viewer-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="bim-viewer-title">
                        <i class="fas fa-cube"></i>
                        {{ $m->name }}
                    </h1>
                    <div class="bim-viewer-subtitle">
                        <span class="bim-info-badge">
                            <i class="fas fa-folder"></i>
                            {{ basename($m->file_path) }}
                        </span>
                        <span class="bim-info-badge">
                            <i class="fas fa-layer-group"></i>
                            {{ $m->discipline }}
                        </span>
                        <span class="bim-info-badge">
                            <i class="fas fa-code-branch"></i>
                            {{ $m->version }}
                        </span>
                        <span class="bim-info-badge">
                            <i class="fas fa-file-alt"></i>
                            {{ strtoupper($m->file_type) }}
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('bim.models.index') }}" class="bim-tool-btn">
                        <i class="fas fa-arrow-left"></i> Model Listesi
                    </a>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- RVT Conversion Alert -->
        @if(strtolower($m->file_type) === 'rvt')
            <div class="bim-conversion-alert">
                <div class="bim-conversion-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="bim-conversion-content">
                    <h6>RVT Dosya Dönüşümü</h6>
                    <p>Bu Revit dosyasını daha iyi 3D görüntüleme için IFC formatına dönüştürebilirsiniz.</p>
                    <form method="POST" action="{{ route('bim.models.convert_rvt', ['id'=>$m->id]) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="bim-convert-btn">
                            <i class="fas fa-magic"></i> IFC'ye Dönüştür
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Main Viewer Layout -->
        <div class="bim-viewer-layout">
            <!-- Main Viewer -->
            <div class="bim-viewer-main">
                <!-- Toolbar -->
                <div class="bim-viewer-toolbar">
                    <!-- Navigation Tools -->
                    <div class="bim-tool-group">
                        <button id="btnHome" class="bim-tool-btn" title="Ana görünüm (H)">
                            <i class="fas fa-home"></i> Ana Görünüm
                        </button>
                        <button id="btnZoomIn" class="bim-tool-btn" title="Yakınlaştır">
                            <i class="fas fa-search-plus"></i>
                        </button>
                        <button id="btnZoomOut" class="bim-tool-btn" title="Uzaklaştır">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <button id="btnPan" class="bim-tool-btn" title="Kaydırma modu">
                            <i class="fas fa-arrows-alt"></i>
                        </button>
                        <button id="btnRotate" class="bim-tool-btn" title="Döndürme modu">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>

                    <!-- Analysis Tools -->
                    <div class="bim-tool-group">
                        <button id="btnMeasure" class="bim-tool-btn" title="Ölçüm modu (M)">
                            <i class="fas fa-ruler"></i> Ölçüm
                        </button>
                        <button id="btnSection" class="bim-tool-btn" title="Kesit alma">
                            <i class="fas fa-cut"></i> Kesit
                        </button>
                    </div>

                    <!-- Selection Tools -->
                    <div class="bim-tool-group">
                        <button id="btnIsolate" class="bim-tool-btn" title="Seçimi izole et (I)" disabled>
                            <i class="fas fa-eye"></i> İzole Et
                        </button>
                        <button id="btnHide" class="bim-tool-btn" title="Seçimi gizle" disabled>
                            <i class="fas fa-eye-slash"></i> Gizle
                        </button>
                        <button id="btnShow" class="bim-tool-btn" title="Tümünü göster">
                            <i class="fas fa-eye"></i> Tümünü Göster
                        </button>
                        <button id="btnZoom" class="bim-tool-btn" title="Seçime odaklan (Z)" disabled>
                            <i class="fas fa-crosshairs"></i> Odaklan
                        </button>
                    </div>

                    <!-- Display Modes -->
                    <div class="bim-tool-group">
                        <button id="btnWireframe" class="bim-tool-btn" title="Wireframe modu (W)">
                            <i class="fas fa-project-diagram"></i> Wireframe
                        </button>
                        <button id="btnXRay" class="bim-tool-btn" title="X-Ray modu (X)">
                            <i class="fas fa-x-ray"></i> X-Ray
                        </button>
                    </div>

                    <!-- Export Tools -->
                    <div class="bim-tool-group">
                        <button id="btnSnapshot" class="bim-tool-btn" title="Ekran görüntüsü al (S)">
                            <i class="fas fa-camera"></i> Görüntü Al
                        </button>
                        <button id="btnFullscreen" class="bim-tool-btn" title="Tam ekran (F)">
                            <i class="fas fa-expand"></i> Tam Ekran
                        </button>
                    </div>

                    <!-- Status -->
                    <div class="ml-auto">
                        <div id="viewerStatus" class="bim-viewer-status">
                            <i class="fas fa-spinner fa-spin"></i>
                            Hazırlanıyor...
                        </div>
                    </div>
                </div>

                <!-- 3D Canvas -->
                <div class="bim-viewer-canvas-container">
                    <!-- Loading Overlay -->
                    <div id="viewerLoading" class="bim-viewer-loading">
                        <div class="bim-loading-spinner"></div>
                        <div class="bim-loading-text">
                            <h5>3D Model Yükleniyor...</h5>
                            <p>Model geometrisi işleniyor, lütfen bekleyin.</p>
                            <div class="bim-loading-progress">
                                <div class="bim-progress-bar">
                                    <div class="bim-progress-fill" id="loadingProgress" style="width: 0%"></div>
                                </div>
                                <small class="bim-progress-text" id="loadingStatus">Dosya indiriliyor...</small>
                            </div>
                        </div>
                    </div>

                    <div id="bimViewer" style="width: 100%; height: 100%; position: relative;">
                        <canvas id="ifcCanvas" class="bim-viewer-canvas"></canvas>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="bim-viewer-sidebar">
                <!-- Properties Panel -->
                <div class="bim-panel">
                    <div class="bim-panel-header">
                        <h6 class="bim-panel-title">
                            <i class="fas fa-info-circle"></i>
                            Element Özellikleri
                        </h6>
                        <button id="btnExportProps" class="bim-tool-btn" style="padding: 5px 10px; font-size: 0.75rem;" disabled>
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                    <div class="bim-panel-body">
                        <div id="propsPanel">
                            <div class="bim-properties-empty">
                                <i class="fas fa-mouse-pointer"></i>
                                <span>Bir element seçin</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Measurement Panel -->
                <div class="bim-panel">
                    <div class="bim-panel-header">
                        <h6 class="bim-panel-title">
                            <i class="fas fa-ruler-combined"></i>
                            Ölçüm
                        </h6>
                    </div>
                    <div class="bim-panel-body">
                        <div id="measureInfo" class="bim-measure-info">
                            Ölçüm modu: Pasif
                        </div>

                        <!-- Keyboard Shortcuts -->
                        <div class="bim-keyboard-shortcuts mt-3">
                            <h6 style="font-size: 0.9rem; margin-bottom: 10px;">
                                <i class="fas fa-keyboard"></i> Kısayollar
                            </h6>
                            <div class="bim-shortcut-item">
                                <span>Ana görünüm</span>
                                <span class="bim-shortcut-key">H</span>
                            </div>
                            <div class="bim-shortcut-item">
                                <span>Ölçüm modu</span>
                                <span class="bim-shortcut-key">M</span>
                            </div>
                            <div class="bim-shortcut-item">
                                <span>İzole et</span>
                                <span class="bim-shortcut-key">I</span>
                            </div>
                            <div class="bim-shortcut-item">
                                <span>Odaklan</span>
                                <span class="bim-shortcut-key">Z</span>
                            </div>
                            <div class="bim-shortcut-item">
                                <span>Temizle</span>
                                <span class="bim-shortcut-key">C</span>
                            </div>
                            <div class="bim-shortcut-item">
                                <span>Tam ekran</span>
                                <span class="bim-shortcut-key">F</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Model Tree Panel -->
                <div class="bim-panel" style="flex: 1;">
                    <div class="bim-panel-header">
                        <h6 class="bim-panel-title">
                            <i class="fas fa-sitemap"></i>
                            Model Ağacı
                        </h6>
                    </div>
                    <div class="bim-panel-body">
                        <input id="treeSearch" class="bim-tree-search"
                               placeholder="Ağaçta ara (element tipi, ID...)">
                        <div id="modelTree" class="bim-model-tree">
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                                <div>Model ağacı yükleniyor...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="importmap">
{
    "imports": {
        "three": "/js/three.module.js",
        "three-mesh-bvh": "/js/three-mesh-bvh.module.js"
    }
}
</script>
<script src="{{ asset('js/bim-viewer.js') }}"></script>
<script type="module">
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the professional BIM viewer
    const viewer = window.initBimViewer({
        container: document.getElementById('bimViewer'),
        model: @json($m),
        signedUrl: '{{ $signed ?? route('bim.models.file',['id'=>$m->id]) }}'
    });

    // Make viewer available globally for debugging
    window.bimViewer = viewer;

    // Handle page unload
    window.addEventListener('beforeunload', function() {
        if (viewer && viewer.viewer) {
            try {
                viewer.viewer.dispose();
            } catch (error) {
                console.warn('Viewer disposal failed:', error);
            }
        }
    });

    // Handle alerts auto-dismiss
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            if (alert.querySelector('.close')) {
                alert.querySelector('.close').click();
            }
        });
    }, 5000);

    // Add fullscreen change handler
    document.addEventListener('fullscreenchange', function() {
        const isFullscreen = !!document.fullscreenElement;
        const btn = document.getElementById('btnFullscreen');
        if (btn) {
            btn.innerHTML = isFullscreen
                ? '<i class="fas fa-compress"></i> Çıkış'
                : '<i class="fas fa-expand"></i> Tam Ekran';
        }

        // Resize viewer on fullscreen toggle
        if (viewer && viewer.handleResize) {
            setTimeout(() => viewer.handleResize(), 100);
        }
    });
});

// Legacy fallback for old viewer code
if (!window.initBimViewer) {
    console.warn('Professional BIM viewer not loaded, falling back to basic viewer');

    (async function(){
        const container = document.getElementById('bimViewer');
        const statusEl = document.getElementById('viewerStatus');

        try {
            const mod = await import('/js/dist/index.js');
            const IFCDemo = mod.IFCDemo || mod.default?.IFCDemo || mod.default;
            const Color = mod.Color || mod.default?.Color;

            if (!IFCDemo) {
                throw new Error('IFC viewer not found in module');
            }

            const viewer = new IFCDemo({ container });
            const url = '{{ $signed ?? route('bim.models.file',['id'=>$m->id]) }}';

            statusEl.textContent = 'Model yükleniyor...';
            await viewer.loadIfcUrl(url);
            statusEl.textContent = 'Yüklendi';
            statusEl.classList.remove('loading');

        } catch (e) {
            container.innerHTML = `
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <h5>3D Viewer Hatası</h5>
                        <p>Viewer kütüphanesi yüklenemedi.</p>
                        <button class="btn btn-outline-danger" onclick="location.reload()">
                            <i class="fas fa-redo"></i> Yeniden Dene
                        </button>
                    </div>
                </div>
            `;
            console.error('Fallback viewer failed:', e);
            statusEl.textContent = 'Viewer Hatası';
            statusEl.classList.remove('loading');
        }
    })();
}
</script>
@endpush
