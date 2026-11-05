@extends('layouts.master')

@section('title', 'Kapanış Kontrol Listesi Düzenle — ' . ($closeout->project->name ?? 'Proje'))

@section('topbar-title', 'Proje Yönetimi - Kontrol Listesi Düzenle')

@push('styles')
<style>
    .closeout-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #f59e0b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .closeout-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .closeout-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .progress-bar-custom {
        height: 10px;
        border-radius: 10px;
        background-color: #e2e8f0;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.4s ease;
    }
</style>
@endpush

@section('content')

@php
    $totalItems = $closeout->items->count();
    $completedItems = $closeout->items->where('status', 'completed')->count();
    $completionPercentage = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
@endphp

<!-- Hero Banner -->
<div class="closeout-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('closeout.show', $closeout) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Detay Sayfası
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Kapanış Kontrol Listesini Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">{{ $closeout->project->name ?? 'Proje' }} — Teslimat Öğelerini Güncelleyin</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="closeout-form-card">
            <!-- Dynamic Progress Tracker -->
            <div class="p-3 bg-light rounded-4 border mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-dark"><i class="ri-checkbox-line text-warning me-1"></i>Teslimat İlerleme Durumu: <span id="progressText">{{ $completedItems }}/{{ $totalItems }} Tamamlandı</span></span>
                    <span class="fw-extrabold text-warning fs-5" id="progressPercentage">{{ $completionPercentage }}%</span>
                </div>
                <div class="progress progress-bar-custom">
                    <div class="progress-bar-fill bg-warning" id="progressBarFill" style="width: {{ $completionPercentage }}%"></div>
                </div>
            </div>

            <!-- Toolbar Buttons -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div class="btn-group gap-2">
                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" onclick="checkAll()">
                        <i class="ri-checkbox-circle-line me-1"></i>Tümünü Tamamla
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="uncheckAll()">
                        <i class="ri-close-circle-line me-1"></i>Tümünü Temizle
                    </button>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <select id="categoryFilter" class="form-select form-select-sm rounded-pill">
                        <option value="">Tüm Kategoriler</option>
                        @foreach($closeout->items->pluck('category')->unique() as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('closeout.update', $closeout) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="table-responsive mb-4">
                    <table class="table align-middle mb-0" id="closeoutEditTable">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Kategori</th>
                                <th>Teslimat Maddesi</th>
                                <th>Tamamlandı mı?</th>
                                <th>Doküman Adları (Virgülle Ayrılmış)</th>
                                <th>Notlar & Açıklama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($closeout->items as $item)
                                <tr data-category="{{ $item->category }}">
                                    <td class="ps-3">
                                        <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold">
                                            {{ $item->category }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-dark d-block">{{ $item->item }}</strong>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input item-checkbox" type="checkbox"
                                                   name="items[{{ $item->id }}][status]"
                                                   value="completed"
                                                   {{ $item->status === 'completed' ? 'checked' : '' }}
                                                   onchange="updateStatus(this)">
                                            <label class="form-check-label fw-bold small text-dark">Tamamlandı</label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                               name="items[{{ $item->id }}][documents]"
                                               value="{{ $item->documents ? implode(', ', $item->documents) : '' }}"
                                               placeholder="Örn: mimari_as_built.pdf">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                               name="items[{{ $item->id }}][notes]"
                                               value="{{ $item->notes }}"
                                               placeholder="Not ekleyin...">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Kontrol listesi maddesi bulunamadı.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('closeout.show', $closeout) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Kontrol Listesini Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function updateStatus(checkbox) {
    updateProgress();
}

function checkAll() {
    document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.checked = true;
    });
    updateProgress();
}

function uncheckAll() {
    document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.checked = false;
    });
    updateProgress();
}

document.getElementById('categoryFilter')?.addEventListener('change', function() {
    const selectedCategory = this.value;
    document.querySelectorAll('#closeoutEditTable tbody tr').forEach(row => {
        if (!selectedCategory || row.getAttribute('data-category') === selectedCategory) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

function updateProgress() {
    const total = document.querySelectorAll('#closeoutEditTable tbody tr').length;
    const completed = document.querySelectorAll('.item-checkbox:checked').length;
    const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;

    document.getElementById('progressBarFill').style.width = percentage + '%';
    document.getElementById('progressText').textContent = `${completed}/${total} Tamamlandı`;
    document.getElementById('progressPercentage').textContent = percentage + '%';
}
</script>
@endpush
