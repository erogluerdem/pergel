@extends('layouts.master')

@section('title', 'Dijital Sözleşme İmzalama — ' . ($contract->title ?? 'Sözleşme'))
@section('topbar-title', 'Dijital Sözleşme İmza Onayı')

@section('content')
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">
            <!-- Başlık Kartı -->
            <div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <span class="badge bg-warning text-dark mb-2">
                                <i class="ri-quill-pen-line me-1"></i>Güvenli Elektronik İmza
                            </span>
                            <h3 class="text-white fw-bold mb-1">{{ $contract->title ?? 'Dijital Sözleşme' }}</h3>
                            <p class="text-white-50 mb-0">
                                Proje: {{ $contract->project->name ?? 'Genel Proje' }} |
                                İmzalayan: <strong>{{ $signature->signer_name }}</strong> ({{ $signature->signer_role }})
                            </p>
                        </div>
                        <div class="text-md-end">
                            <span class="badge bg-info px-3 py-2">
                                Yürürlük: {{ $contract->effective_date ? \Carbon\Carbon::parse($contract->effective_date)->format('d.m.Y') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sözleşme Metni -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="ri-file-text-line me-2 text-primary"></i>Sözleşme İçeriği</h5>
                    <small class="text-muted">Lütfen imzalamadan önce sözleşme metnini dikkatlice okuyunuz.</small>
                </div>
                <div class="card-body">
                    <div class="p-3 rounded border" style="max-height: 420px; overflow-y: auto; line-height: 1.7;">
                        {!! nl2br(e($contract->content)) !!}
                    </div>
                </div>
            </div>

            <!-- İmza Alanı -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-edit-2-line me-2 text-warning"></i>Dijital İmza Paneli</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('digital-contracts.confirm_signature', [$contract, $signature->token]) }}" id="signatureForm">
                        @csrf
                        <input type="hidden" name="signature_image" id="signatureImageInput" required>

                        <div class="mb-3">
                            <label class="form-label">İmzanızı aşağıdaki alana çizin:</label>
                            <div class="border rounded-3 bg-white p-2 text-center">
                                <canvas id="signatureCanvas" width="600" height="200" style="max-width: 100%; touch-action: none; cursor: crosshair; border: 1px dashed #cbd5e1;"></canvas>
                            </div>
                            @error('signature_image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-secondary" id="clearSignatureBtn">
                                <i class="ri-eraser-line me-1"></i>İmzayı Temizle
                            </button>
                            <div class="d-flex gap-2">
                                <a href="{{ route('digital-contracts.show', $contract) }}" class="btn btn-light">İptal</a>
                                <button type="submit" class="btn btn-success px-4 fw-bold">
                                    <i class="ri-check-double-line me-1"></i>Sözleşmeyi Onayla ve İmzala
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('signatureCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const input = document.getElementById('signatureImageInput');
    const clearBtn = document.getElementById('clearSignatureBtn');
    const form = document.getElementById('signatureForm');

    let drawing = false;
    let hasSignature = false;

    ctx.strokeStyle = '#0f172a';
    ctx.lineWidth = 2.5;
    ctx.lineCap = 'round';

    function getPos(e) {
        const rect = canvas.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return {
            x: (clientX - rect.left) * (canvas.width / rect.width),
            y: (clientY - rect.top) * (canvas.height / rect.height)
        };
    }

    function startDraw(e) {
        drawing = true;
        hasSignature = true;
        const pos = getPos(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    }

    function draw(e) {
        if (!drawing) return;
        e.preventDefault();
        const pos = getPos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
    }

    function endDraw() {
        drawing = false;
        if (hasSignature) {
            input.value = canvas.toDataURL('image/png');
        }
    }

    canvas.addEventListener('mousedown', startDraw);
    canvas.addEventListener('mousemove', draw);
    window.addEventListener('mouseup', endDraw);

    canvas.addEventListener('touchstart', startDraw, { passive: false });
    canvas.addEventListener('touchmove', draw, { passive: false });
    window.addEventListener('touchend', endDraw);

    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            input.value = '';
            hasSignature = false;
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            if (!input.value) {
                e.preventDefault();
                alert('Lütfen sözleşmeyi onaylamadan önce imza alanını doldurun.');
            }
        });
    }
});
</script>
@endsection
