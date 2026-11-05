@extends('layouts.master')

@section('title', 'Tedarikçiler')
@section('topbar-title', 'Tedarik & Satın Alma')

@section('content')
<div>
        <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Tedarikçiler</h2>
            <p class="text-muted mb-0">Tedarikçi havuzunuzu yönetin ve performanslarını takip edin</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.accounts.suppliers.recycle') }}" class="btn btn-outline-secondary btn-lg">
                <i class="mdi mdi-trash-can-arrow-up me-2"></i>Geri Dönüşüm
            </a>
            <a href="{{ route('supply.vendors.create') }}" class="btn btn-primary btn-lg">
                <i class="mdi mdi-plus me-2"></i>Yeni Tedarikçi Ekle
            </a>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl bg-primary-subtle rounded-circle mx-auto mb-3">
                        <i class="mdi mdi-account-group text-primary fs-1"></i>
                    </div>
                    <h3 class="text-primary mb-1">{{ $kpis['total'] }}</h3>
                    <p class="text-muted mb-0">Toplam Tedarikçi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl bg-success-subtle rounded-circle mx-auto mb-3">
                        <i class="mdi mdi-check-circle text-success fs-1"></i>
                    </div>
                    <h3 class="text-success mb-1">{{ $kpis['approved'] }}</h3>
                    <p class="text-muted mb-0">Onaylı</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl bg-warning-subtle rounded-circle mx-auto mb-3">
                        <i class="mdi mdi-clock-outline text-warning fs-1"></i>
                    </div>
                    <h3 class="text-warning mb-1">{{ $kpis['pending'] }}</h3>
                    <p class="text-muted mb-0">Onay Bekleyen</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl bg-danger-subtle rounded-circle mx-auto mb-3">
                        <i class="mdi mdi-alert-circle text-danger fs-1"></i>
                    </div>
                    <h3 class="text-danger mb-1">{{ $kpis['low_performance'] }}</h3>
                    <p class="text-muted mb-0">Performansı Düşük</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts removed as requested -->

    <!-- Search and Filter -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="get" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Arama</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                        <input id="searchInput" name="q" value="{{ request('q') }}" placeholder="Firma adı veya vergi no" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Durum</label>
                    <select name="status" class="form-select">
                        <option value="">Tümü</option>
                        <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Onaylı</option>
                        <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Onay Bekliyor</option>
                        <option value="conditional" {{ request('status')=='conditional' ? 'selected' : '' }}>Şartlı Onaylı</option>
                        <option value="blacklisted" {{ request('status')=='blacklisted' ? 'selected' : '' }}>Kara Liste</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="mdi mdi-filter me-1"></i>Ara
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                        <i class="mdi mdi-refresh me-1"></i>Temizle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Vendors Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Tedarikçi Listesi</h5>
            <span class="badge bg-primary">{{ $vendors->total() }} kayıt</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><i class="mdi mdi-office-building me-1"></i>Firma Adı</th>
                        <th><i class="mdi mdi-wrench me-1"></i>Uzmanlık</th>
                        <th><i class="mdi mdi-star me-1"></i>Performans</th>
                        <th><i class="mdi mdi-flag me-1"></i>Durum</th>
                        <th><i class="mdi mdi-phone me-1"></i>İletişim</th>
                        <th width="100">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $v)
                    <tr class="table-row-hover">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-primary-subtle rounded-circle me-3">
                                    <span class="avatar-title text-primary">{{ substr($v->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <a href="{{ route('finance.accounts.suppliers.show', $v->id) }}" class="text-decoration-none fw-semibold">{{ $v->name }}</a>
                                    <br><small class="text-muted">{{ $v->tax_number }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($v->specialty)
                                <span class="badge bg-info-subtle text-info">{{ $v->specialty }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rating-stars me-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="mdi mdi-star{{ $i <= round($v->performance_score) ? '' : '-outline' }} text-warning"></i>
                                    @endfor
                                </div>
                                <span class="fw-semibold">{{ number_format($v->performance_score,1) }}</span>
                            </div>
                        </td>
                        <td>
                            @switch($v->status)
                                @case('approved')
                                    <span class="badge bg-success">Onaylı</span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning">Onay Bekliyor</span>
                                    @break
                                @case('conditional')
                                    <span class="badge bg-info">Şartlı Onaylı</span>
                                    @break
                                @case('blacklisted')
                                    <span class="badge bg-danger">Kara Liste</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $v->status }}</span>
                            @endswitch
                        </td>
                        <td>
                            @if($v->contact_person)
                                <div><i class="mdi mdi-account me-1"></i>{{ $v->contact_person }}</div>
                            @endif
                            @if($v->phone)
                                <div><i class="mdi mdi-phone me-1"></i>{{ $v->phone }}</div>
                            @endif
                            @if($v->email)
                                <div><i class="mdi mdi-email me-1"></i><small>{{ $v->email }}</small></div>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('finance.accounts.suppliers.show', $v->id) }}" class="btn btn-sm btn-outline-primary" title="Detay">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                                <a href="{{ route('finance.accounts.suppliers.edit', $v->id) }}" class="btn btn-sm btn-outline-secondary" title="D fczenle">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                                <form action="{{ route('finance.accounts.suppliers.destroy', $v->id) }}" method="post" style="display:inline-block;" id="delete-form-{{ $v->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Sil" onclick="openDeleteModal({{ $v->id }}, '{{ addslashes($v->name) }}')">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="mdi mdi-account-group-outline fs-1 d-block mb-2"></i>
                                <p>Henüz tedarikçi bulunamadı.</p>
                                <a href="#" class="btn btn-primary btn-sm">İlk tedarikçiyi ekle</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vendors->hasPages())
        <div class="card-footer bg-light">
            {{ $vendors->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // AJAX search: when typing, query API and replace table body (simple)
    let timeout = null;
    document.getElementById('searchInput').addEventListener('input', function(e){
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            const q = e.target.value;
            fetch(`{{ route('api.finance.suppliers') }}?q=${encodeURIComponent(q)}`)
                .then(r=>r.json())
                .then(data => {
                    const tbody = document.querySelector('table tbody');
                    tbody.innerHTML = data.map(v => `
                        <tr class="table-row-hover">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-primary-subtle rounded-circle me-3">
                                        <span class="avatar-title text-primary">${v.name.charAt(0)}</span>
                                    </div>
                                    <div>
                                        <a href="{{ url('finance/accounts/suppliers') }}/${v.id}" class="text-decoration-none fw-semibold">${v.name}</a>
                                        <br><small class="text-muted">${v.tax_number || ''}</small>
                                    </div>
                                </div>
                            </td>
                            <td>${v.specialty ? `<span class="badge bg-info-subtle text-info">${v.specialty}</span>` : '-'}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rating-stars me-2">
                                        ${Array.from({length: 5}, (_, i) => `<i class="mdi mdi-star${i < Math.round(v.performance_score) ? '' : '-outline'} text-warning"></i>`).join('')}
                                    </div>
                                    <span class="fw-semibold">${(parseFloat(v.performance_score)||0).toFixed(1)}</span>
                                </div>
                            </td>
                            <td>
                                ${v.status === 'approved' ? '<span class="badge bg-success">Onaylı</span>' :
                                  v.status === 'pending' ? '<span class="badge bg-warning">Onay Bekliyor</span>' :
                                  v.status === 'conditional' ? '<span class="badge bg-info">Şartlı Onaylı</span>' :
                                  v.status === 'blacklisted' ? '<span class="badge bg-danger">Kara Liste</span>' :
                                  `<span class="badge bg-secondary">${v.status}</span>`}
                            </td>
                            <td>
                                ${v.contact_person ? `<div><i class="mdi mdi-account me-1"></i>${v.contact_person}</div>` : ''}
                                ${v.phone ? `<div><i class="mdi mdi-phone me-1"></i>${v.phone}</div>` : ''}
                                ${v.email ? `<div><i class="mdi mdi-email me-1"></i><small>${v.email}</small></div>` : ''}
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ url('finance/accounts/suppliers') }}/${v.id}" class="btn btn-sm btn-outline-primary" title="Detay">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary" title="Düzenle">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                });
        }, 400);
    });

    // Clear filters function
    function clearFilters() {
        document.querySelector('input[name="q"]').value = '';
        document.querySelector('select[name="status"]').value = '';
    window.location.href = '{{ route("index", ["any" => "finance/accounts/suppliers"]) }}';
    }

        // Modern delete modal
        function openDeleteModal(id, name) {
                // remove old modal if exists
                const existing = document.getElementById('confirmDeleteModal');
                if (existing) existing.remove();

                const modalHtml = `
                <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger"><i class="mdi mdi-alert-circle-outline me-2"></i>Kaydı Sil</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>${name}</strong> adlı tedarikçiyi silmek üzeresiniz.</p>
                                <p class="text-muted">Bu işlem geri dönüşüm kutusuna taşıyacaktır. Kalıcı silmek için kutuyu işaretleyin.</p>
                                <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="permanentDeleteCheckbox">
                                        <label class="form-check-label" for="permanentDeleteCheckbox">Kalıcı sil (veritabanından tamamen kaldır)</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">İptal</button>
                                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Sil</button>
                            </div>
                        </div>
                    </div>
                </div>`;

                document.body.insertAdjacentHTML('beforeend', modalHtml);
                const modalEl = document.getElementById('confirmDeleteModal');
                const bsModal = new bootstrap.Modal(modalEl);
                bsModal.show();

                modalEl.addEventListener('hidden.bs.modal', () => modalEl.remove());

                document.getElementById('confirmDeleteBtn').addEventListener('click', function(){
                        const permanent = document.getElementById('permanentDeleteCheckbox').checked ? '1' : '';
                        const form = document.getElementById('delete-form-' + id);
                        if (permanent) {
                                // append hidden permanent input
                                let inp = form.querySelector('input[name="permanent"]');
                                if (!inp) {
                                        inp = document.createElement('input');
                                        inp.type = 'hidden';
                                        inp.name = 'permanent';
                                        form.appendChild(inp);
                                }
                                inp.value = '1';
                        }
                        form.submit();
                });
        }

    // Load specialty pie chart
    fetch(`{{ route('api.finance.suppliers.stats') }}`).then(r=>r.json()).then(data=>{
        const labels = Object.keys(data);
        const values = Object.values(data);
        const ctx = document.getElementById('specialtyPieChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: [
                        '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1',
                        '#e83e8c', '#fd7e14', '#20c997', '#6c757d', '#17a2b8'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    });

    // Load performance bar chart
    fetch(`{{ route('api.finance.suppliers') }}`).then(r=>r.json()).then(data=>{
        const performanceCounts = [0,0,0,0,0,0]; // 0-5 stars
        data.forEach(v => {
            const score = Math.round(parseFloat(v.performance_score) || 0);
            if (score >= 0 && score <= 5) performanceCounts[score]++;
        });

        const ctx = document.getElementById('performanceBarChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['0 Yıldız', '1 Yıldız', '2 Yıldız', '3 Yıldız', '4 Yıldız', '5 Yıldız'],
                datasets: [{
                    label: 'Tedarikçi Sayısı',
                    data: performanceCounts,
                    backgroundColor: [
                        '#6c757d', '#dc3545', '#fd7e14', '#ffc107', '#20c997', '#28a745'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    });
</script>
@endpush

<style>
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

.table-row-hover:hover {
    background-color: rgba(0,123,255,0.05);
}

.rating-stars i {
    font-size: 0.9em;
}

.avatar-xl {
    width: 3rem;
    height: 3rem;
}

@media (max-width: 768px) {
    .d-flex.justify-content-between.mb-4 {
        flex-direction: column;
        gap: 1rem;
    }

    .btn-group {
        flex-direction: column;
    }

    .btn-group .btn {
        border-radius: 0.25rem !important;
    }
}
</style>
@endsection
