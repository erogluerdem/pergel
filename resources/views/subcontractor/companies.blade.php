@extends('layouts.master')

@section('title', 'Taşeron Firmalar')

@section('topbar-title', 'Taşeron Yönetimi')

@section('css')
<style>
    /* Small visual polish for KPI cards and table */
    .kpi-number { font-weight: 700; font-size: 1.5rem; }
    .search-input { max-width: 420px; }
    .table-actions a { margin-right: 0.5rem; }
    /* Fix for charts: keep a predictable height so canvas doesn't push content down */
    .chart-wrapper { height: 260px; min-height: 180px; position: relative; }
    .chart-wrapper canvas { width: 100% !important; height: 100% !important; display: block; }
    .avatar-img { width:48px; height:48px; object-fit:cover; border-radius:50%; }
</style>
@endsection

@section('content')
<div class="container-fluid">

    <!-- KPI cards -->
    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="avatar-title bg-light text-primary rounded-circle p-2">
                                <i class="mdi mdi-check-circle-outline font-size-20"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted">Toplam Onaylı Taşeron</div>
                            <div class="kpi-number">{{ $totalApproved ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="avatar-title bg-light text-success rounded-circle p-2">
                                <i class="mdi mdi-office-building font-size-20"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted">Aktif Saha Firması</div>
                            <div class="kpi-number">{{ $activeCompanies ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="avatar-title bg-light text-warning rounded-circle p-2">
                                <i class="mdi mdi-alert-circle-outline font-size-20"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted">İSG Skoru Düşük</div>
                            <div class="kpi-number">{{ $lowSafetyScore ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="avatar-title bg-light text-danger rounded-circle p-2">
                                <i class="mdi mdi-alert-outline font-size-20"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted">Uygunluk Riski Olan</div>
                            <div class="kpi-number">{{ $riskyCompanies ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action bar: search + actions -->
    <div class="row mb-3">
        <div class="col-lg-6">
            <form method="GET" class="d-flex align-items-center" role="search" aria-label="Taşeron ara">
                <input type="search" name="q" value="{{ request('q') }}" class="form-control me-2 search-input" placeholder="Firma adı, uzmanlık veya kod ile ara..." aria-label="Ara" />
                <select name="status" class="form-select me-2" style="width:170px">
                    <option value="">Tümü</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Onaylı</option>
                    <option value="conditional" {{ request('status') == 'conditional' ? 'selected' : '' }}>Koşullu</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                </select>
                <button class="btn btn-outline-primary me-2" type="submit"><i class="mdi mdi-magnify"></i> Ara</button>
                <a href="{{ route('subcontractor.companies') }}" class="btn btn-light">Filtreleri Temizle</a>
            </form>
        </div>
        <div class="col-lg-6 text-lg-end mt-2 mt-lg-0">
            <a href="{{ route('subcontractor.companies') }}" class="btn btn-outline-secondary me-2"><i class="mdi mdi-view-list"></i> Taşeron Listesi</a>
            <a href="{{ route('subcontractor.companies.export') }}" class="btn btn-outline-success me-2"><i class="mdi mdi-file-export"></i> CSV Dışa Aktar</a>
            @auth
                <a href="{{ route('subcontractor.trashed') }}" class="btn btn-outline-dark me-2"><i class="mdi mdi-delete-restore"></i> Silinenleri Gör</a>
            @endauth
            @if(Route::has('subcontractor.create'))
                <a href="{{ route('subcontractor.create') }}" class="btn btn-primary"><i class="mdi mdi-plus"></i> Yeni Taşeron</a>
            @else
                <button class="btn btn-primary" disabled title="Yeni Taşeron rotası tanımlı değil"><i class="mdi mdi-plus"></i> Yeni Taşeron</button>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Uzmanlık Alanlarına Göre Dağılım</h5>
                </div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <canvas id="expertiseChart" aria-label="Uzmanlık alanları grafiği" role="img"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Son Aktiviteler</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($recentActivities ?? [] as $activity)
                            <li class="mb-3">
                                <div class="small text-muted">{{ $activity['time'] ?? '' }}</div>
                                <div>{{ $activity['title'] ?? '' }}</div>
                                @if(!empty($activity['detail']))
                                    <div class="text-muted small">{{ $activity['detail'] }}</div>
                                @endif
                            </li>
                        @endforeach
                        @if(empty($recentActivities ?? []))
                            <li class="text-muted">Henüz gösterilecek aktivite yok.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Companies grid/profile cards -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="mb-0">Taşeronlar</h5>
                <small class="text-muted">Toplam: {{ $companies->total() ?? (is_countable($companies) ? count($companies) : '-') }}</small>
            </div>

            <div class="row g-3">
                @forelse($companies as $company)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <div class="avatar-sm">
                                        @if(!empty($company->logo))
                                            <img src="{{ asset($company->logo) }}" alt="{{ $company->company_name }}" class="avatar-img" />
                                        @else
                                            <span class="avatar-title rounded-circle bg-primary text-white" style="width:48px; height:48px; display:flex; align-items:center; justify-content:center;">
                                                {{ strtoupper(substr($company->company_name ?? '??',0,1)) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">{{ $company->company_name }}</h5>
                                    <div class="small text-muted">{{ $company->expertise_area ?? 'Genel' }}</div>
                                    <div class="mt-2">
                                        <span class="badge bg-{{ $company->status === 'approved' ? 'success' : ($company->status === 'conditional' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($company->status ?? 'bilinmiyor') }}
                                        </span>
                                        <span class="badge bg-light text-dark ms-2">İSG: {{ isset($company->safety_score) ? number_format($company->safety_score,0) : '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <p class="text-muted mt-3 mb-0" style="flex:1">{{ Str::limit($company->short_description ?? $company->company_name, 120) }}</p>

                            <div class="d-flex align-items-center justify-content-between mt-3">
                                <div>
                                    @if($company->phone)
                                        <a href="tel:{{ $company->phone }}" class="btn btn-sm btn-outline-primary me-1"><i class="mdi mdi-phone"></i></a>
                                    @endif
                                    @if($company->phone)
                                        @php
                                            // normalize phone for whatsapp: remove non-digits
                                            $waNumber = preg_replace('/\D+/', '', $company->phone);
                                        @endphp
                                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn btn-sm btn-success me-1" title="WhatsApp ile mesaj gönder"><i class="mdi mdi-whatsapp"></i></a>
                                    @endif
                                    @if($company->email)
                                        <a href="mailto:{{ $company->email }}" class="btn btn-sm btn-outline-info me-1"><i class="mdi mdi-email"></i></a>
                                    @endif
                                    <button class="btn btn-sm btn-outline-secondary" onclick="window.open('https://www.google.com/maps/search/{{ urlencode($company->address ?? '') }}','_blank')"><i class="mdi mdi-map-marker"></i></button>
                                </div>
                                <div>
                                    @if(Route::has('subcontractor.show'))
                                        <a href="{{ route('subcontractor.show', $company->id) }}" class="btn btn-sm btn-primary">Detay</a>
                                    @endif
                                    @auth
                                        @if(Route::has('subcontractor.edit'))
                                            <a href="{{ route('subcontractor.edit', $company->id) }}" class="btn btn-sm btn-outline-secondary ms-1">Düzenle</a>
                                        @endif
                                        @if(Route::has('subcontractor.destroy'))
                                            <form method="POST" action="{{ route('subcontractor.destroy', $company->id) }}" style="display:inline" class="js-confirm-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger ms-1" type="submit">Sil</button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-12 text-center text-muted">Kayıtlı taşeron bulunamadı.</div>
                @endforelse
            </div>

            <div class="mt-3">
                @if(method_exists($companies, 'links'))
                    <div class="d-flex justify-content-center">{{ $companies->links() }}</div>
                @endif
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const expertiseData = @json($expertiseDistribution ?? []);
    const labels = Object.keys(expertiseData);
    const data = Object.values(expertiseData);
    const ctx = document.getElementById('expertiseChart');
    if (ctx && labels.length) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#4e73df','#1cc88a','#f6c23e','#e74a3b','#858796','#36b9cc','#6f42c1']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});
</script>
<script>
// SweetAlert2 modern delete confirmation for forms with class .js-confirm-delete
(function(){
    function ensureSweetAlert(cb){
        if(window.Swal) return cb();
        var s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js';
        s.onload = cb;
        document.head.appendChild(s);
    }

    ensureSweetAlert(function(){
        document.querySelectorAll('form.js-confirm-delete').forEach(function(form){
            form.addEventListener('submit', function(e){
                e.preventDefault();
                var companyName = form.closest('.card')?.querySelector('h5')?.innerText || 'bu kaydı';
                Swal.fire({
                    title: 'Silmek istediğinize emin misiniz?',
                    html: '<strong>' + companyName + '</strong><br>Bu işlem geri alınamaz.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Evet, sil',
                    cancelButtonText: 'İptal',
                    reverseButtons: true,
                    focusCancel: true
                }).then(function(result){
                    if(result.isConfirmed){
                        // optionally show a small loading toast
                        Swal.fire({title: 'Siliniyor...', didOpen: () => Swal.showLoading(), showConfirmButton: false, allowOutsideClick:false});
                        form.submit();
                    }
                });
            });
        });
    });
})();
</script>
@endsection
