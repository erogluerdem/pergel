@extends('layouts.master')

@section('title', $program->name)

@section('styles')
<style>
/* Modern Color Palette */
:root {
    --primary-blue: #1e3a8a;
    --success-green: #059669;
    --warning-orange: #f59e0b;
    --danger-red: #dc2626;
    --light-bg: #f8fafc;
    --card-shadow: 0 10px 25px rgba(0,0,0,0.1);
    --border-radius: 12px;
}

/* KPI header with modern design */
.program-kpi {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}
.program-kpi.sticky {
    position: -webkit-sticky;
    position: sticky;
    top: 72px;
    z-index: 1050;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    padding: 1rem;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    border: 1px solid rgba(0,0,0,0.05);
}
.program-kpi .kpi {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    border: 1px solid rgba(0,0,0,0.08);
    padding: 1rem;
    border-radius: var(--border-radius);
    min-width: 160px;
    text-align: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.program-kpi .kpi:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
.program-kpi .kpi .value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-blue);
}

/* Enhanced Cards */
.card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    overflow: hidden;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}
.card-header {
    background: linear-gradient(135deg, var(--primary-blue), #3b82f6);
    color: white;
    border-bottom: none;
    padding: 1.5rem;
}
.card-body {
    padding: 1.5rem;
}

/* Milestone timeline with icons */
.timeline li {
    position: relative;
    padding-left: 2rem;
    margin-bottom: 1rem;
}
.timeline li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 8px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--primary-blue);
    border: 3px solid white;
    box-shadow: 0 0 0 2px var(--primary-blue);
}
.timeline .ms-complete::before {
    background: var(--success-green);
    box-shadow: 0 0 0 2px var(--success-green);
}
.timeline .ms-late::before {
    background: var(--danger-red);
    box-shadow: 0 0 0 2px var(--danger-red);
}
.timeline li::after {
    content: '';
    position: absolute;
    left: 5px;
    top: 20px;
    width: 2px;
    height: calc(100% - 20px);
    background: linear-gradient(to bottom, var(--primary-blue), transparent);
}
.timeline li:last-child::after {
    display: none;
}
.milestone-date {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
}

/* Risk list enhancements */
.risk-item {
    display: flex;
    justify-content: space-between;
    gap: 0.5rem;
    align-items: center;
    padding: 0.75rem;
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    border-radius: 8px;
    margin-bottom: 0.5rem;
    border-left: 4px solid var(--danger-red);
}
.risk-severity {
    min-width: 90px;
    text-align: center;
}

/* Consolidated progress visuals */
.prog-summary-card {
    background: linear-gradient(180deg, #ffffff, #f8fafc);
    border: 1px solid rgba(0,0,0,0.04);
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
}
.prog-summary-card .progress {
    border-radius: 10px;
    overflow: hidden;
    height: 20px;
    background: #e5e7eb;
}
.prog-summary-card .progress-bar {
    font-weight: 600;
    background: linear-gradient(90deg, var(--primary-blue), var(--success-green));
}
.mini-project {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 0.5rem;
    padding: 0.75rem;
    background: rgba(255,255,255,0.8);
    border-radius: 8px;
    border: 1px solid rgba(0,0,0,0.05);
}
.mini-project .meta {
    flex: 1;
}
.mini-progress {
    height: 10px;
    background: #f1f3f5;
    border-radius: 6px;
    overflow: hidden;
}
.mini-progress > div {
    height: 100%;
    border-radius: 6px;
    background: linear-gradient(90deg, var(--primary-blue), var(--success-green));
}
.money {
    font-weight: 700;
    color: var(--primary-blue);
}

/* Buttons */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.btn-outline-primary {
    border-color: var(--primary-blue);
    color: var(--primary-blue);
}
.btn-outline-primary:hover {
    background: var(--primary-blue);
    border-color: var(--primary-blue);
}

/* Badges */
.badge {
    font-weight: 500;
    border-radius: 6px;
}

/* Responsive improvements */
@media (max-width: 767px) {
    .program-kpi {
        flex-direction: column;
        align-items: stretch;
    }
    .program-kpi .kpi {
        min-width: auto;
    }
    .card-body {
        padding: 1rem;
    }
    .timeline li {
        padding-left: 1.5rem;
    }
}

/* Improve contrast for light placeholder boxes inside cards */
.card .alert-light {
    background: linear-gradient(135deg, #f9fafb, #f3f4f6);
    color: #374151;
    border: 1px solid rgba(55,65,81,0.1);
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
}

/* Animation for page load */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.card {
    animation: fadeInUp 0.5s ease-out;
}
</style>
@endsection

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h2 mb-2 d-flex align-items-center">
                <i class="mdi mdi-folder-multiple-outline me-2 text-primary"></i>
                {{ $program->name }}
            </h1>
            <p class="mb-0"><strong>Yönetici:</strong> {{ $program->manager ?? '-' }}</p>
            <p class="mb-0"><strong>Durum:</strong> {{ ucfirst($program->status) }}</p>
            @if(!empty($program->strategic_objective))
                <p class="mb-0 text-muted small">{{ $program->strategic_objective }}</p>
            @endif
        </div>            <!-- Prominent KPI strip moved from top-right to under the header for better visibility -->
            <div class="program-kpi sticky mt-3" style="flex-wrap:wrap; gap:1rem;">
                <div class="kpi">
                    <div class="text-muted small d-flex align-items-center justify-content-center mb-1">
                        <i class="mdi mdi-chart-line me-1"></i>Genel İlerleme
                    </div>
                    <div class="value">{{ $programMeta['progress_percent'] ?? 0 }}%</div>
                </div>
                <div class="kpi">
                    <div class="text-muted small d-flex align-items-center justify-content-center mb-1">
                        <i class="mdi mdi-cash-multiple me-1"></i>Toplam Bütçe
                    </div>
                    <div class="value">{{ isset($programMeta['total_budget']) ? number_format($programMeta['total_budget'],0,',','.') . ' ₺' : '-' }}</div>
                    <div class="small text-muted">Harcanan: {{ isset($programMeta['total_spent']) ? number_format($programMeta['total_spent'],0,',','.') . ' ₺' : '-' }}</div>
                </div>
                <div class="kpi">
                    <div class="text-muted small d-flex align-items-center justify-content-center mb-1">
                        <i class="mdi mdi-heart-pulse me-1"></i>Genel Sağlık
                    </div>
                    <div class="value"><span class="badge bg-{{ $programMeta['health']=='green' ? 'success' : ($programMeta['health']=='yellow' ? 'warning' : 'danger') }}">{{ $programMeta['health']=='green' ? 'İyi' : ($programMeta['health']=='yellow' ? 'Dikkat' : 'Kritik') }}</span></div>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <a href="{{ route('portfolio.management.edit', $program->id) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="mdi mdi-pencil me-1"></i>Düzenle
                    </a>
                    <form method="post" action="{{ route('portfolio.management.destroy', $program->id) }}" onsubmit="return confirm('Bu programı silmek istediğinizden emin misiniz?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="mdi mdi-delete me-1"></i>Sil
                        </button>
                    </form>
                </div>
            </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-chart-areaspline me-2 text-primary"></i>
                        Konsolide Finansal Akış (Planlanan vs Gerçekleşen)
                    </h5>
                    @if(!empty($financialSeries['labels']))
                        <canvas id="financialChart" height="120"></canvas>
                    @else
                        <div class="alert alert-info">Zaman serisi verisi yok.</div>
                    @endif
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-timeline-clock me-2 text-primary"></i>
                        Kilometre Taşı Zaman Tüneli
                    </h5>
                    @if(!empty($program->milestones))
                        <ul class="timeline list-unstyled mb-0">
                            @foreach($program->milestones as $ms)
                                @php
                                    $msDate = isset($ms->date) ? \Carbon\Carbon::parse($ms->date)->format('d.m.Y') : (isset($ms->due_date) ? \Carbon\Carbon::parse($ms->due_date)->format('d.m.Y') : null);
                                    $msStatus = strtolower($ms->status ?? '');
                                @endphp
                                <li class="mb-2 {{ $msStatus == 'completed' ? 'ms-complete' : ($msStatus == 'late' ? 'ms-late' : '') }}"
                                    data-milestone-id="{{ $ms->id }}"
                                    data-milestone-title="{{ $ms->title ?? $ms->name ?? '' }}"
                                    data-milestone-due_date="{{ $ms->date ?? $ms->due_date ?? '' }}"
                                    data-milestone-status="{{ $ms->status ?? '' }}"
                                    data-milestone-description="{{ $ms->description ?? '' }}">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>{{ $ms->title ?? $ms->name ?? 'Kilometre Taşı' }}</strong>
                                            <div class="milestone-date">{{ $msDate ?? 'Tarih yok' }}</div>
                                        </div>
                                        <div class="text-end">
                                            @if($msStatus == 'completed')
                                                <span class="badge bg-success"><i class="mdi mdi-check me-1"></i>Tamamlandı</span>
                                            @elseif($msStatus == 'late')
                                                <span class="badge bg-danger"><i class="mdi mdi-alert me-1"></i>Gecikmeli</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($ms->status ?? 'Planlandı') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-secondary">Kilometre taşı bulunmamaktadır.</div>
                    @endif
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-alert-circle me-2 text-warning"></i>
                        Risk Haritası
                    </h5>
                    @if(!empty($programMeta['risks']))
                        <ul>
                            @foreach($programMeta['risks'] as $r)
                                <li><strong>{{ $r['project'] }}</strong>: {{ $r['issue'] }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-secondary">Önemli risk raporu bulunamadı.</div>
                    @endif
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-package-variant me-2 text-primary"></i>
                        Bağlı Projeler
                    </h5>
                    <ul class="list-group list-group-flush">
                        @foreach($projects as $p)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div><strong>{{ $p->name }}</strong></div>
                                        <small class="text-muted">Durum: {{ ucfirst($p->status) }}</small>
                                        <div><small>İlerleme: {{ isset($p->progress_percent) ? $p->progress_percent . '%' : (isset($p->status) ? ($p->status ? ucfirst($p->status) : '-') : '-') }}</small></div>
                                    </div>
                                    <div class="text-end">
                                        <small>{{ $p->budget ? number_format($p->budget,2) . ' ₺' : '-' }}</small>
                                        <div><small>Harcanan: {{ isset($p->spent) ? number_format($p->spent,2) . ' ₺' : '-' }}</small></div>
                                        <div class="mt-1"><a href="{{ url('portfolio/projects/' . $p->id) }}" class="btn btn-sm btn-outline-primary">Projeye Git</a></div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-cog-outline me-2 text-info"></i>
                        Kaynak Havuzu Kullanımı
                    </h5>
                    <div class="alert alert-light">Ortak kullanılan kritik kaynakların kullanım özeti (placeholder). Burada kaynak takvimi veya heatmap gösterilebilir.</div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-alert-circle-outline me-2 text-warning"></i>
                        Risk Haritası (Öne Çıkan Riskler)
                    </h5>
                    @if(!empty($programMeta['risks']))
                        <ul class="list-unstyled mb-0">
                            @foreach($programMeta['risks'] as $r)
                                <li class="mb-2 risk-item">
                                    <div>
                                        <strong>{{ $r['project'] }}</strong>
                                        <div class="small text-muted">{{ $r['issue'] }}</div>
                                    </div>
                                    <div class="risk-severity">
                                        <span class="badge bg-danger">Yüksek</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-secondary">Önemli risk raporu bulunamadı.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <a href="{{ url('portfolio/program-management') }}" class="btn btn-secondary mt-3">
        <i class="mdi mdi-arrow-left me-1"></i>Geri
    </a>
</div>

<!-- Milestone Modal -->
<div class="modal fade" id="milestoneModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center" id="milestoneModalLabel">
                    <i class="mdi mdi-plus-circle me-2 text-primary"></i>
                    Kilometre Taşı Ekle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <form id="milestoneForm">
                    <input type="hidden" name="milestone_id" id="milestone_id" />
                    <div class="mb-3">
                        <label class="form-label">Başlık</label>
                        <input type="text" class="form-control" name="title" id="milestone_title" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tarih</label>
                        <input type="date" class="form-control" name="due_date" id="milestone_due_date" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durum</label>
                        <select class="form-select" name="status" id="milestone_status">
                            <option value="planned">Planlandı</option>
                            <option value="in_progress">Devam Ediyor</option>
                            <option value="completed">Tamamlandı</option>
                            <option value="late">Gecikmeli</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea class="form-control" name="description" id="milestone_description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i>İptal
                </button>
                <button type="button" class="btn btn-danger d-none" id="milestoneDeleteBtn">
                    <i class="mdi mdi-delete me-1"></i>Sil
                </button>
                <button type="button" class="btn btn-primary" id="milestoneSaveBtn">
                    <i class="mdi mdi-content-save me-1"></i>Kaydet
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
        const programId = {{ $program->id }};
        const modalEl = document.getElementById('milestoneModal');
        const milestoneModal = new bootstrap.Modal(modalEl);

        const openCreate = function(){
                document.getElementById('milestoneModalLabel').textContent = 'Kilometre Taşı Ekle';
                document.getElementById('milestoneForm').reset();
                document.getElementById('milestone_id').value = '';
                document.getElementById('milestoneDeleteBtn').classList.add('d-none');
                milestoneModal.show();
        };

        const openEdit = function(m){
                document.getElementById('milestoneModalLabel').textContent = 'Kilometre Taşı Düzenle';
                document.getElementById('milestone_id').value = m.id;
                document.getElementById('milestone_title').value = m.title || '';
                document.getElementById('milestone_due_date').value = m.due_date ? m.due_date : '';
                document.getElementById('milestone_status').value = m.status || 'planned';
                document.getElementById('milestone_description').value = m.description || '';
                document.getElementById('milestoneDeleteBtn').classList.remove('d-none');
                milestoneModal.show();
        };

        // Attach create button to page (insert near milestones header)
        const milestonesCard = document.querySelector('.card .card-body h5');
        if (milestonesCard && milestonesCard.textContent.includes('Kilometre Taşı')){
                const btn = document.createElement('button');
                btn.className = 'btn btn-sm btn-outline-primary ms-2';
                btn.innerHTML = '<i class="mdi mdi-plus me-1"></i>Kilometre Taşı Ekle';
                btn.addEventListener('click', openCreate);
                milestonesCard.insertAdjacentElement('afterend', btn);
        }

        // Save (create/update)
        document.getElementById('milestoneSaveBtn').addEventListener('click', function(){
                const id = document.getElementById('milestone_id').value;
                const payload = {
                        title: document.getElementById('milestone_title').value,
                        due_date: document.getElementById('milestone_due_date').value,
                        status: document.getElementById('milestone_status').value,
                        description: document.getElementById('milestone_description').value
                };

                const url = id ? `/portfolio/programs/${programId}/milestones/${id}` : `/portfolio/programs/${programId}/milestones`;
                const method = id ? 'PUT' : 'POST';

                fetch(url, {
                        method: method,
                        headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(payload)
                }).then(r=>r.json()).then(data=>{
                        if(data.success){
                                location.reload();
                        } else {
                                alert('Kaydetme sırasında hata oluştu');
                        }
                }).catch(()=>alert('Sunucu hatası'));
        });

        // Delete
        document.getElementById('milestoneDeleteBtn').addEventListener('click', function(){
                if(!confirm('Bu kilometre taşını silmek istediğinize emin misiniz?')) return;
                const id = document.getElementById('milestone_id').value;
                if(!id) return;
                const url = `/portfolio/programs/${programId}/milestones/${id}`;
                fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
                        .then(r=>r.json()).then(data=>{ if(data.success) location.reload(); else alert('Silme sırasında hata oluştu'); }).catch(()=>alert('Sunucu hatası'));
        });

        // Make milestone edit buttons clickable (delegate)
        document.addEventListener('click', function(e){
                const el = e.target.closest('[data-milestone-id]');
                if(el){
                        const m = {
                                id: el.getAttribute('data-milestone-id'),
                                title: el.getAttribute('data-milestone-title'),
                                due_date: el.getAttribute('data-milestone-due_date'),
                                status: el.getAttribute('data-milestone-status'),
                                description: el.getAttribute('data-milestone-description')
                        };
                        openEdit(m);
                }
        });
});
</script>
@endpush

@if(!empty($financialSeries['labels']))
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('financialChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($financialSeries['labels']) !!},
                datasets: [
                    { label: 'Planlanan', data: {!! json_encode($financialSeries['planned']) !!}, borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,0.05)', fill: true },
                    { label: 'Gerçekleşen', data: {!! json_encode($financialSeries['actual']) !!}, borderColor: '#198754', backgroundColor: 'rgba(25,135,84,0.05)', fill: true }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.parsed.y || 0;
                                return context.dataset.label + ': ' + value.toLocaleString('tr-TR', { style: 'currency', currency: 'TRY' });
                            }
                        }
                    },
                    legend: { labels: { boxWidth:12 } }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) { return value.toLocaleString('tr-TR') + ' ₺'; }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
@endif

@endsection
