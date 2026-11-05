@extends('layouts.master')

@section('title', 'Potansiyel Projeler - Kanban Görünümü')

@push('styles')
<style>
    .kanban-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .kanban-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: kanbanGlow 4s linear infinite;
    }

    @keyframes kanbanGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .kanban-badge {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-kanban {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-kanban:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-input-kanban {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.88rem !important;
    }
    .custom-input-kanban:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    /* KANBAN BOARD CONTAINER */
    .kanban-board-container {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 16px;
        scroll-behavior: smooth;
    }

    .kanban-column {
        flex: 0 0 320px;
        max-width: 320px;
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        max-height: 800px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        transition: border-color 0.2s ease;
    }

    .kanban-column.drag-over {
        border-color: #d97706 !important;
        background: #ffffec !important;
    }

    .kanban-column-header {
        background: #0f172a;
        color: #ffffff;
        padding: 14px 16px;
        border-top-left-radius: 14px;
        border-top-right-radius: 14px;
        border-bottom: 2px solid #d97706;
    }

    .kanban-cards-list {
        padding: 12px;
        overflow-y: auto;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-height: 200px;
    }

    .kanban-card {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 14px;
        padding: 16px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
        cursor: grab;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        position: relative;
    }

    .kanban-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: #d97706;
    }

    .kanban-card.overdue {
        border-left: 4px solid #ef4444 !important;
    }

    .kanban-card.upcoming {
        border-left: 4px solid #f59e0b !important;
    }

    .owner-avatar-pill {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #0f172a;
        color: #fef08a;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        border: 2px solid #d97706;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="kanban-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill kanban-badge">
                        <i class="ri-kanban-view text-warning fs-5"></i> CRM & POTANSİYEL PROJELER (KANBAN BOARD)
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $totalOpportunities ?? 0 }} Aktif Fırsat
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Potansiyel Projeler - Kanban Görünümü</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Satış ve ihale fırsatlarınızı aşama aşama sürükleyip bırakarak (Drag & Drop) takip edin, kazanma olasılıklarını anlık yönetin.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <button class="btn btn-outline-light rounded-pill px-3 py-2 fw-extrabold" id="refreshBtn" onclick="location.reload();">
                        <i class="ri-refresh-line me-1"></i>Yenile
                    </button>
                    <a href="{{ route('opportunities.list') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-list-check me-1"></i>Liste Görünümü
                    </a>
                    <a href="{{ route('opportunities.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Fırsat Ekle
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-kanban h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM FIRSAT SAYISI</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $totalOpportunities ?? 0 }} Fırsat</div>
                        <small class="text-warning fw-bold"><i class="ri-rocket-fill me-1"></i>CRM Takibindeki Projeler</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-lightbulb-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-kanban h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM POTANSİYEL DEĞER</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.5rem;">₺{{ number_format($totalValue ?? 0, 0, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>KDV Dahil: ₺{{ number_format($totalValueKdv ?? 0, 0, ',', '.') }}</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-coins-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-kanban h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KAZANILAN PROJELER</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $wonOpportunities ?? 0 }} Proje</div>
                        <small class="text-info fw-bold"><i class="ri-trophy-fill me-1"></i>Sözleşmeye Dönüşen</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-trophy-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-kanban h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">ORTALAMA OLASILIK</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">%{{ number_format($avgProbability ?? 0, 1) }}</div>
                        <small class="text-primary fw-bold"><i class="ri-percent-fill me-1"></i>Kazanma İhtimali</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-pie-chart-2-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK FILTERS CARD -->
    <div class="card custom-kpi-card-kanban mb-4 p-3">
        <div class="row align-items-center g-2">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-dark text-warning border-0"><i class="ri-search-line"></i></span>
                    <input type="text" class="form-control custom-input-kanban" id="quickSearch" placeholder="Fırsat adı veya müşteri ara...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select custom-input-kanban" id="ownerFilter">
                    <option value="">Tüm Sorumlu Yöneticiler</option>
                    @foreach(\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select custom-input-kanban" id="probabilityFilter">
                    <option value="">Tüm Olasılıklar</option>
                    <option value="75">%75 ve Üzeri</option>
                    <option value="50">%50 ve Üzeri</option>
                    <option value="25">%25 ve Üzeri</option>
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-outline-dark rounded-pill w-100 fw-bold" id="clearFilters">
                    <i class="ri-close-line me-1"></i>Filtreleri Temizle
                </button>
            </div>
        </div>
    </div>

    <!-- KANBAN BOARD CONTAINER -->
    <div class="kanban-board-container">
        @php
            $stageOrder = ['lead', 'qualification', 'proposal', 'negotiation', 'won', 'lost'];
        @endphp

        @foreach($stageOrder as $key)
            @if(isset($stages[$key]))
                <div class="kanban-column" id="col-{{ $key }}" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <!-- Column Header -->
                    <div class="kanban-column-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 fw-extrabold text-white d-flex align-items-center gap-1.5">
                                {{ $stages[$key] }}
                            </h6>
                            <small class="text-warning font-monospace fw-bold" style="font-size: 11px;">
                                @php $stageValue = isset($columns[$key]) ? $columns[$key]->sum('estimated_value') : 0; @endphp
                                ₺{{ number_format($stageValue, 0, ',', '.') }}
                            </small>
                        </div>
                        <span class="badge bg-warning text-dark font-monospace fw-extrabold rounded-pill px-2.5 py-1" style="font-size: 12px;">
                            {{ isset($columns[$key]) ? count($columns[$key]) : 0 }}
                        </span>
                    </div>

                    <!-- Cards Container -->
                    <div class="kanban-cards-list" data-stage="{{ $key }}">
                        @if(empty($columns[$key]) || $columns[$key]->isEmpty())
                            <div class="text-center text-muted py-4">
                                <i class="ri-inbox-archive-line fs-3 text-warning mb-1 d-block"></i>
                                <small class="fw-bold">Bu aşamada fırsat yok</small>
                            </div>
                        @else
                            @foreach($columns[$key] as $c)
                                @php
                                    $owner = $c->owner ?? null;
                                    $daysUntilClose = $c->expected_close_date ? \Carbon\Carbon::parse($c->expected_close_date)->diffInDays(now(), false) : null;
                                    $isOverdue = $daysUntilClose !== null && $daysUntilClose < 0;
                                    $isUpcoming = $daysUntilClose !== null && $daysUntilClose >= 0 && $daysUntilClose <= 7;
                                    $prob = intval($c->probability ?? 0);
                                @endphp

                                <div class="kanban-card {{ $isOverdue ? 'overdue' : ($isUpcoming ? 'upcoming' : '') }}"
                                     draggable="true" ondragstart="drag(event)"
                                     data-id="{{ $c->id }}" data-stage="{{ $c->stage }}"
                                     data-title="{{ $c->title }}" data-customer="{{ $c->customer }}"
                                     data-value="{{ $c->estimated_value }}" data-probability="{{ $c->probability }}">

                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-extrabold text-dark mb-0 fs-6" style="line-height: 1.3;">
                                            <a href="{{ route('opportunities.show', $c->id) }}" class="text-dark text-decoration-none">
                                                {{ Str::limit($c->title, 42) }}
                                            </a>
                                        </h6>
                                        <span class="badge {{ $prob >= 70 ? 'bg-success' : ($prob >= 40 ? 'bg-warning text-dark' : 'bg-secondary') }} rounded-pill font-monospace" style="font-size: 10px;">
                                            %{{ $prob }}
                                        </span>
                                    </div>

                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark border fw-bold text-truncate" style="max-width: 100%; font-size: 11px;">
                                            🏢 {{ $c->customer ?? 'Müşteri Belirtilmemiş' }}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3 pt-2 border-top">
                                        <div>
                                            <small class="text-muted d-block font-size-11 font-uppercase fw-bold">Tahmini Değer</small>
                                            <strong class="text-success font-monospace fs-6">₺{{ number_format($c->estimated_value ?? 0, 0, ',', '.') }}</strong>
                                        </div>
                                        @if($c->expected_close_date)
                                            <div class="text-end">
                                                <small class="text-muted d-block font-size-11 font-uppercase fw-bold">Kapanış</small>
                                                <span class="font-monospace fw-bold {{ $isOverdue ? 'text-danger' : 'text-dark' }}" style="font-size: 12px;">
                                                    {{ \Carbon\Carbon::parse($c->expected_close_date)->format('d.m.Y') }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between pt-1">
                                        <div class="d-flex align-items-center gap-1.5">
                                            <div class="owner-avatar-pill">
                                                {{ $owner ? strtoupper(substr($owner->name, 0, 1)) : '?' }}
                                            </div>
                                            <small class="fw-bold text-dark font-size-12">{{ $owner->name ?? 'Atanmamış' }}</small>
                                        </div>

                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('opportunities.show', $c->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-1.5" title="Detay">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('opportunities.edit', $c->id) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-1.5" title="Düzenle">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </div>

</div>
@endsection

@push('scripts')
<script>
function allowDrop(ev) {
    ev.preventDefault();
    const column = ev.target.closest('.kanban-column');
    if (column) {
        column.classList.add('drag-over');
    }
}

function drag(ev) {
    const el = ev.target.closest('[data-id]');
    if (!el) return;
    ev.dataTransfer.setData('text/plain', el.dataset.id);
    el.style.opacity = '0.5';
}

function dragEnd(ev) {
    const el = ev.target.closest('[data-id]');
    if (el) {
        el.style.opacity = '1';
    }
    document.querySelectorAll('.kanban-column').forEach(col => {
        col.classList.remove('drag-over');
    });
}

async function drop(ev) {
    ev.preventDefault();
    const id = ev.dataTransfer.getData('text/plain');
    const target = ev.target.closest('[id^="col-"]');
    if (!id || !target) return;
    const stage = target.id.replace('col-','');

    try {
        const res = await fetch('/opportunities/'+id+'/move', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ stage })
        });
        if (res.ok) {
            location.reload();
        }
    } catch (err) {
        console.error(err);
    }
    dragEnd(ev);
}

document.addEventListener('DOMContentLoaded', function() {
    const quickSearch = document.getElementById('quickSearch');
    if (quickSearch) {
        quickSearch.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            const cards = document.querySelectorAll('.kanban-card');
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.indexOf(query) > -1 ? 'block' : 'none';
            });
        });
    }

    const clearFilters = document.getElementById('clearFilters');
    if (clearFilters) {
        clearFilters.addEventListener('click', function() {
            if (quickSearch) quickSearch.value = '';
            document.querySelectorAll('.kanban-card').forEach(card => card.style.display = 'block');
        });
    }
});
</script>
@endpush
