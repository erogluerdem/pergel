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
                        <i class="fas fa-balance-scale"></i>
                        Model Karşılaştırma
                    </h1>
                    <div class="bim-viewer-subtitle">
                        <span class="bim-info-badge">
                            <i class="fas fa-cube"></i>
                            {{ $m1->name }} vs {{ $m2->name }}
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

        <!-- Comparison Layout -->
        <div class="bim-comparison-layout">
            <!-- Model A Viewer -->
            <div class="bim-comparison-panel">
                <div class="bim-comparison-header">
                    <h5>{{ $m1->name }}</h5>
                    <small class="text-muted">{{ $m1->discipline }} - {{ $m1->version }}</small>
                </div>
                <div class="bim-comparison-viewer">
                    <div id="viewerA" style="width: 100%; height: 100%; position: relative;">
                        <canvas id="canvasA" class="bim-viewer-canvas"></canvas>
                    </div>
                </div>
            </div>

            <!-- Model B Viewer -->
            <div class="bim-comparison-panel">
                <div class="bim-comparison-header">
                    <h5>{{ $m2->name }}</h5>
                    <small class="text-muted">{{ $m2->discipline }} - {{ $m2->version }}</small>
                </div>
                <div class="bim-comparison-viewer">
                    <div id="viewerB" style="width: 100%; height: 100%; position: relative;">
                        <canvas id="canvasB" class="bim-viewer-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparison Controls -->
        <div class="bim-comparison-controls">
            <div class="row">
                <div class="col-md-6">
                    <button id="syncViewsBtn" class="bim-btn-secondary">
                        <i class="fas fa-sync"></i> Görünümleri Senkronize Et
                    </button>
                    <button id="toggleDifferencesBtn" class="bim-btn-primary ml-2">
                        <i class="fas fa-eye"></i> Farkları Göster
                    </button>
                </div>
                <div class="col-md-6 text-right">
                    <button id="exportComparisonBtn" class="bim-btn-secondary">
                        <i class="fas fa-download"></i> Karşılaştırmayı Dışa Aktar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/bim-viewer.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize comparison viewers
    initComparisonViewers();
});

async function initComparisonViewers() {
    const containerA = document.getElementById('viewerA');
    const containerB = document.getElementById('viewerB');

    try {
        // Load IFC viewer module
        const mod = await import('/js/dist/index.js');
        const IFCDemo = mod.IFCDemo || mod.default?.IFCDemo || mod.default;

        if (!IFCDemo) {
            throw new Error('IFC viewer not found');
        }

        // Initialize viewer A
        const viewerA = new IFCDemo({ container: containerA });
        const urlA = '{{ $signed1 ?? route('bim.models.file',['id'=>$m1->id]) }}';
        await viewerA.loadIfcUrl(urlA);

        // Initialize viewer B
        const viewerB = new IFCDemo({ container: containerB });
        const urlB = '{{ $signed2 ?? route('bim.models.file',['id'=>$m2->id]) }}';
        await viewerB.loadIfcUrl(urlB);

        // Store viewers for controls
        window.viewerA = viewerA;
        window.viewerB = viewerB;

        // Enable controls
        setupComparisonControls(viewerA, viewerB);

    } catch (e) {
        console.error('Comparison viewer failed:', e);
        showComparisonError();
    }
}

function setupComparisonControls(viewerA, viewerB) {
    // Sync views button
    document.getElementById('syncViewsBtn').addEventListener('click', function() {
        // Copy camera from A to B
        if (viewerA.getCamera && viewerB.setCamera) {
            const camera = viewerA.getCamera();
            viewerB.setCamera(camera);
        }
    });

    // Toggle differences (placeholder - would need advanced IFC comparison)
    document.getElementById('toggleDifferencesBtn').addEventListener('click', function() {
        alert('Fark analizi özelliği yakında eklenecek.');
    });

    // Export comparison
    document.getElementById('exportComparisonBtn').addEventListener('click', function() {
        alert('Karşılaştırma raporu dışa aktarma yakında eklenecek.');
    });
}

function showComparisonError() {
    const containerA = document.getElementById('viewerA');
    const containerB = document.getElementById('viewerB');

    const errorHtml = `
        <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center text-danger">
                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                <h5>Karşılaştırma Hatası</h5>
                <p>Model görüntüleyicileri yüklenemedi.</p>
                <button class="btn btn-outline-danger" onclick="location.reload()">
                    <i class="fas fa-redo"></i> Yeniden Dene
                </button>
            </div>
        </div>
    `;

    containerA.innerHTML = errorHtml;
    containerB.innerHTML = errorHtml;
}
</script>
@endpush
