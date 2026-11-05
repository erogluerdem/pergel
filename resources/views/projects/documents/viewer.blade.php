@extends('layouts.master')

@section('title', 'Doküman Önizleme — ' . $doc->name)

@section('topbar-title', 'Projeler - Doküman Görüntüleyici')

@push('styles')
<style>
    .doc-viewer-hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 24px 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .doc-viewer-hero-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: docViewerGlow 4s linear infinite;
    }

    @keyframes docViewerGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .custom-pdf-frame {
        height: 78vh;
        border-radius: 18px;
        border: 2px solid #0f172a;
        background: #1e293b;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2rem;
        box-shadow: inset 0 4px 15px rgba(0, 0, 0, 0.5);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER TOOLBAR -->
    <div class="doc-viewer-hero-card text-white">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-extrabold flex-shrink-0" style="width: 52px; height: 52px; font-size: 1.6rem; border: 2px solid #ffffff;">
                    <i class="ri-file-pdf-fill"></i>
                </div>
                <div>
                    <h4 class="fw-extrabold text-white mb-1" style="text-shadow: 0 2px 4px rgba(0,0,0,0.6);">{{ $doc->name }}</h4>
                    <div class="d-flex align-items-center gap-2 text-white-50 flex-wrap">
                        <span class="badge bg-dark text-warning border border-warning font-monospace fw-bold px-2.5 py-1" style="font-size: 11px;">
                            {{ $doc->version ?? 'V1.0' }}
                        </span>
                        <span>•</span>
                        <span class="badge bg-dark text-white font-monospace fw-bold px-2.5 py-1" style="font-size: 11px;">
                            KOD: {{ $doc->code ?? 'KODSUZ' }}
                        </span>
                        <span>•</span>
                        <span class="badge bg-primary text-white font-monospace fw-bold px-2.5 py-1" style="font-size: 11px;">
                            {{ $doc->discipline ?? 'Genel Belge' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('projects.documents.show', [$projectId, $doc->id]) }}" target="_blank" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="ri-download-2-fill fs-5"></i>Dosyayı İndir
                </a>
                <a href="{{ route('projects.documents.index', $projectId) }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Kapat
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN PDF CANVAS VIEWER -->
    <div class="row mb-4">
        <div class="col-12">
            <div id="pdf-viewer-frame" class="custom-pdf-frame">
                <div class="text-white text-center my-auto" id="loading-spinner">
                    <div class="spinner-border text-warning mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
                    <h6 class="fw-extrabold text-white">Yüksek Çözünürlüklü PDF Dokümanı Yükleniyor...</h6>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.10.88/pdf.min.js"></script>
<script>
const fullUrl = '{{ route('projects.documents.show', [$projectId, $doc->id]) }}';
(async ()=>{
    try {
        const pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.10.88/pdf.worker.min.js';
        const loadingTask = pdfjsLib.getDocument(fullUrl);
        const pdf = await loadingTask.promise;
        const container = document.getElementById('pdf-viewer-frame');
        container.innerHTML = ''; // Clear loading spinner
        
        for (let i=1; i<=pdf.numPages; i++){
            const page = await pdf.getPage(i);
            const viewport = page.getViewport({scale: 1.4});
            const canvas = document.createElement('canvas');
            canvas.style.display = 'block';
            canvas.style.marginBottom = '24px';
            canvas.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.5)';
            canvas.style.borderRadius = '10px';
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            container.appendChild(canvas);
            const ctx = canvas.getContext('2d');
            await page.render({canvasContext:ctx, viewport}).promise;
        }
    } catch(e){
        console.error(e);
        document.getElementById('pdf-viewer-frame').innerHTML = '<div class="text-white text-center my-auto"><i class="ri-error-warning-fill text-warning fs-1 mb-2 d-block"></i><h5 class="fw-bold text-white">PDF Önizlemesi yüklenirken bir hata oluştu veya dosya tarayıcıda doğrudan önizlenemiyor.</h5><a href="' + fullUrl + '" class="btn btn-warning text-dark px-4 py-2 mt-3 rounded-pill fw-extrabold">Dosyayı Doğrudan İndir &rarr;</a></div>';
    }
})();
</script>
@endpush
