@extends('layouts.master')
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
                <div class="card-body text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold">
                                <i class="fas fa-dollar-sign me-2"></i>5D Maliyet Simülasyonu
                            </h4>
                            <p class="mb-0 opacity-90">{{ $sim->name }} - Nakit akışı ve maliyet analizi</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a class="btn btn-light btn-sm" href="{{ route('bim.simulations.index') }}">
                                <i class="fas fa-arrow-left me-1"></i>Geri
                            </a>
                            <div class="btn-group" role="group">
                                <button id="playBtn" class="btn btn-light btn-sm">
                                    <i class="fas fa-play me-1"></i>Oynat
                                </button>
                                <button id="recomputeBtn" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-calculator me-1"></i>Yeniden Hesapla
                                </button>
                                <button class="btn btn-outline-light btn-sm" onclick="exportReport()">
                                    <i class="fas fa-download me-1"></i>Rapor
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- 3D Viewer with Cost Data -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light border-0 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-cube me-2"></i>3D Model - Maliyet Görselleştirme
                        </h6>
                        <div class="d-flex gap-2">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-palette me-1"></i>Renk Şeması
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item" onclick="changeColorScheme('cost')">
                                        <i class="fas fa-dollar-sign me-2"></i>Maliyet Bazlı
                                    </button></li>
                                    <li><button class="dropdown-item" onclick="changeColorScheme('progress')">
                                        <i class="fas fa-chart-line me-2"></i>İlerleme Bazlı
                                    </button></li>
                                    <li><button class="dropdown-item" onclick="changeColorScheme('status')">
                                        <i class="fas fa-info-circle me-2"></i>Durum Bazlı
                                    </button></li>
                                </ul>
                            </div>
                            <button class="btn btn-sm btn-outline-primary" onclick="resetView()">
                                <i class="fas fa-home"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="viewerContainer" style="height:400px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); position: relative;">
                        <div id="viewerPlaceholder" class="d-flex align-items-center justify-content-center h-100">
                            <div class="text-center">
                                <div class="spinner-border text-warning mb-3" role="status">
                                    <span class="visually-hidden">Yükleniyor...</span>
                                </div>
                                <p class="text-muted">Maliyet verileri hazırlanıyor...</p>
                            </div>
                        </div>
                        <div id="bimViewer" style="height:100%;width:100%;">
                            <canvas id="ifcCanvas" style="width:100%;height:100%"></canvas>
                        </div>

                        <!-- Cost Legend -->
                        <div class="position-absolute top-0 end-0 m-3">
                            <div class="bg-white rounded shadow-sm p-2">
                                <small class="text-muted d-block mb-2">Maliyet Aralığı</small>
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-1">
                                        <div style="width: 12px; height: 12px; background: #dc3545; margin-right: 6px; border-radius: 2px;"></div>
                                        <small>Yüksek (>100K)</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <div style="width: 12px; height: 12px; background: #ffc107; margin-right: 6px; border-radius: 2px;"></div>
                                        <small>Orta (50K-100K)</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div style="width: 12px; height: 12px; background: #28a745; margin-right: 6px; border-radius: 2px;"></div>
                                        <small>Düşük (<50K)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Element Cost Info -->
                        <div class="position-absolute bottom-0 start-0 m-3">
                            <div class="bg-white rounded shadow-sm p-2" id="elementCostInfo" style="display: none;">
                                <small class="text-muted d-block">Seçili Eleman</small>
                                <strong id="selectedElementId" class="text-primary">—</strong>
                                <div class="mt-1">
                                    <small class="text-muted">Tahmini Maliyet:</small>
                                    <strong id="selectedElementCost" class="text-success">—</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Chart -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i>Maliyet Analizi ve S-Eğrisi
                        </h6>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="chartType" id="scurve" value="scurve" checked>
                            <label class="btn btn-outline-secondary" for="scurve">S-Eğrisi</label>
                            
                            <input type="radio" class="btn-check" name="chartType" id="cashflow" value="cashflow">
                            <label class="btn btn-outline-secondary" for="cashflow">Nakit Akışı</label>
                            
                            <input type="radio" class="btn-check" name="chartType" id="variance" value="variance">
                            <label class="btn btn-outline-secondary" for="variance">Sapma Analizi</label>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="costChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="col-lg-4">
            <!-- Cost Summary -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>Maliyet Özeti
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-2 bg-light rounded">
                                <div class="text-primary mb-1">
                                    <i class="fas fa-chart-pie fa-lg"></i>
                                </div>
                                <small class="text-muted d-block">Toplam Bütçe</small>
                                <strong class="text-dark" id="totalBudget">{{ number_format($sim->project->budget ?? 0, 0, ',', '.') }} ₺</strong>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-2 bg-light rounded">
                                <div class="text-success mb-1">
                                    <i class="fas fa-money-bill-wave fa-lg"></i>
                                </div>
                                <small class="text-muted d-block">Harcanmış</small>
                                <strong class="text-dark" id="spentAmount">{{ number_format($sim->project->spent ?? 0, 0, ',', '.') }} ₺</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <div class="text-warning mb-1">
                                    <i class="fas fa-clock fa-lg"></i>
                                </div>
                                <small class="text-muted d-block">Bu Ayki</small>
                                <strong class="text-dark" id="monthlySpent">— ₺</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <div class="text-info mb-1">
                                    <i class="fas fa-percentage fa-lg"></i>
                                </div>
                                <small class="text-muted d-block">Tamamlanma</small>
                                <strong class="text-dark" id="completionRate">— %</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">Bütçe Kullanımı</small>
                            <small class="text-muted" id="budgetUsagePercent">0%</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-gradient" id="budgetUsageBar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Cost Breakdown -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>Görev Maliyetleri
                    </h6>
                </div>
                <div class="card-body">
                    <div class="task-cost-list" style="max-height: 250px; overflow-y: auto;">
                        @foreach($tasks as $index => $t)
                        <div class="task-cost-item mb-2 p-2 border rounded">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $t['name'] }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>{{ $t['start'] }} → {{ $t['end'] }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success task-cost" data-cost="{{ rand(10000, 100000) }}">
                                        {{ number_format(rand(10000, 100000), 0, ',', '.') }} ₺
                                    </div>
                                    <div class="progress mt-1" style="height: 4px; width: 80px;">
                                        <div class="progress-bar bg-warning task-cost-progress" style="width: {{ rand(0, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Cost Alerts -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Maliyet Uyarıları
                    </h6>
                </div>
                <div class="card-body">
                    <div id="costAlerts">
                        <div class="alert alert-warning alert-sm mb-2">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Bütçe Aşımı Riski</strong><br>
                            <small>Görev "Kolon Montajı" bütçesinin %85'ini kullandı.</small>
                        </div>
                        <div class="alert alert-info alert-sm mb-2">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Nakit Akış Uyarısı</strong><br>
                            <small>Gelecek ay {{ number_format(rand(50000, 150000), 0, ',', '.') }} ₺ ödeme planlanıyor.</small>
                        </div>
                        <div class="alert alert-success alert-sm mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Tasarruf Fırsatı</strong><br>
                            <small>3 görev beklenenden erken tamamlandı.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Options -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0">
                        <i class="fas fa-file-export me-2"></i>Raporlar
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="exportCostReport()">
                            <i class="fas fa-file-pdf me-2"></i>Maliyet Raporu (PDF)
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="exportCashFlow()">
                            <i class="fas fa-file-excel me-2"></i>Nakit Akışı (Excel)
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="exportBudgetAnalysis()">
                            <i class="fas fa-chart-bar me-2"></i>Bütçe Analizi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.task-cost-item {
    transition: all 0.3s ease;
    border: 1px solid #dee2e6 !important;
}

.task-cost-item:hover {
    transform: translateX(3px);
    border-color: var(--bs-warning) !important;
    background-color: rgba(var(--bs-warning-rgb), 0.05);
}

.alert-sm {
    padding: 0.5rem 0.75rem;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.task-cost-progress {
    transition: width 0.5s ease;
}

#costChart {
    max-height: 300px;
}

.progress {
    border-radius: 10px;
    background-color: #f8f9fa;
}

.progress-bar {
    border-radius: 10px;
}
</style>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="importmap">
{
    "imports": {
        "three": "/js/three.module.js",
        "three-mesh-bvh": "/js/three-mesh-bvh.module.js"
    }
}
</script>
<script type="module">
    const costs = @json($costs);
    const simId = {{ $id }};
    let chart = null;
    let viewer = null;
    let IFCDemo;

    // Try to load IFC viewer
    try {
        const mod = await import('/js/dist/index.js');
        IFCDemo = mod.IFCDemo || mod.default?.IFCDemo || mod.default;
    } catch (e) {
        console.log('IFC viewer not available');
    }

    // Initialize viewer
    (async function initViewer(){
        const container = document.getElementById('bimViewer');
        const placeholder = document.getElementById('viewerPlaceholder');
        
        try{
            if (IFCDemo) {
                viewer = new IFCDemo({ container });
                const url = '{{ optional($model)->file_path ? route('bim.models.file',['id'=>$model->id]) : '' }}';
                
                if (url) {
                    placeholder.style.display = 'none';
                    await viewer.loadIfcUrl(url);

                    // Element selection for cost info
                    viewer.on('pick', (item)=>{
                        const elementId = item?.expressID || item?.id;
                        if (elementId) {
                            showElementCostInfo(elementId);
                        }
                    });
                } else {
                    placeholder.innerHTML = '<div class="p-4 text-muted text-center"><i class="fas fa-cube fa-3x mb-3"></i><p>Model bulunamadı</p></div>';
                }
            } else {
                placeholder.innerHTML = '<div class="p-4 text-info text-center"><i class="fas fa-chart-line fa-3x mb-3"></i><p>Maliyet verileri hazır</p></div>';
            }
        } catch(e){
            placeholder.innerHTML = '<div class="p-4 text-danger text-center"><i class="fas fa-exclamation-triangle fa-3x mb-3"></i><p>Viewer başlatılamadı</p></div>';
            console.error(e);
        }
    })();

    // Initialize chart
    function initChart() {
        const ctx = document.getElementById('costChart').getContext('2d');
        const labels = costs.map(c => {
            const date = new Date(c.date);
            return date.toLocaleDateString('tr-TR', { month: 'short', year: '2-digit' });
        });
        const planned = costs.map(c => c.planned);
        const actual = costs.map(c => c.actual);

        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Planlanan Maliyet',
                    data: planned,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Gerçekleşen Maliyet',
                    data: actual,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Kümülatif Maliyet Analizi (S-Eğrisi)'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('tr-TR', {
                                    style: 'currency',
                                    currency: 'TRY',
                                    minimumFractionDigits: 0
                                }).format(value);
                            }
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }

    // Chart type change handlers
    document.querySelectorAll('input[name="chartType"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updateChart(this.value);
        });
    });

    function updateChart(type) {
        if (!chart) return;

        const labels = costs.map(c => {
            const date = new Date(c.date);
            return date.toLocaleDateString('tr-TR', { month: 'short', year: '2-digit' });
        });

        let datasets = [];
        let title = '';

        switch(type) {
            case 'scurve':
                datasets = [{
                    label: 'Planlanan Kümülatif',
                    data: costs.map(c => c.planned),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Gerçekleşen Kümülatif',
                    data: costs.map(c => c.actual),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    fill: true,
                    tension: 0.4
                }];
                title = 'Kümülatif Maliyet Analizi (S-Eğrisi)';
                break;
            
            case 'cashflow':
                // Calculate monthly differences for cash flow
                const plannedMonthly = costs.map((c, i) => i === 0 ? c.planned : c.planned - costs[i-1].planned);
                const actualMonthly = costs.map((c, i) => i === 0 ? c.actual : c.actual - costs[i-1].actual);
                
                datasets = [{
                    label: 'Planlanan Aylık',
                    data: plannedMonthly,
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.2)',
                    type: 'bar'
                }, {
                    label: 'Gerçekleşen Aylık',
                    data: actualMonthly,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.2)',
                    type: 'bar'
                }];
                title = 'Aylık Nakit Akışı';
                break;
            
            case 'variance':
                datasets = [{
                    label: 'Sapma (Gerçek - Plan)',
                    data: costs.map(c => c.actual - c.planned),
                    borderColor: '#6f42c1',
                    backgroundColor: function(context) {
                        const value = context.parsed.y;
                        return value >= 0 ? 'rgba(40, 167, 69, 0.2)' : 'rgba(220, 53, 69, 0.2)';
                    },
                    type: 'bar'
                }];
                title = 'Maliyet Sapma Analizi';
                break;
        }

        chart.data.datasets = datasets;
        chart.options.plugins.title.text = title;
        chart.update();
    }

    // Show element cost info
    function showElementCostInfo(elementId) {
        const info = document.getElementById('elementCostInfo');
        const estimatedCost = Math.floor(Math.random() * 50000) + 10000; // Random cost for demo
        
        document.getElementById('selectedElementId').textContent = elementId;
        document.getElementById('selectedElementCost').textContent = 
            new Intl.NumberFormat('tr-TR').format(estimatedCost) + ' ₺';
        
        info.style.display = 'block';
        
        // Hide after 5 seconds
        setTimeout(() => {
            info.style.display = 'none';
        }, 5000);
    }

    // Control handlers
    document.getElementById('playBtn').addEventListener('click', function() {
        // Animate the chart data
        animateChart();
    });

    document.getElementById('recomputeBtn').addEventListener('click', async function() {
        const btn = this;
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Hesaplanıyor...';
        btn.disabled = true;
        
        try {
            const response = await fetch(`/bim/simulations/${simId}/compute-costs`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            if (data.ok) {
                // Update chart with new data
                location.reload(); // Simple reload for demo
            }
        } catch (error) {
            console.error('Recompute failed:', error);
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });

    function animateChart() {
        if (!chart) return;
        
        chart.data.datasets.forEach(dataset => {
            dataset.data = [...dataset.data]; // Clone to trigger animation
        });
        chart.update('active');
    }

    // Export functions
    window.exportReport = function() {
        alert('Rapor indirme özelliği yakında eklenecek!');
    }

    window.exportCostReport = function() {
        alert('PDF raporu yakında hazırlanacak!');
    }

    window.exportCashFlow = function() {
        alert('Excel dosyası yakında hazırlanacak!');
    }

    window.exportBudgetAnalysis = function() {
        alert('Bütçe analizi yakında hazırlanacak!');
    }

    window.changeColorScheme = function(scheme) {
        alert(`Renk şeması "${scheme}" yakında uygulanacak!`);
    }

    window.resetView = function() {
        if (viewer && viewer.resetCamera) {
            viewer.resetCamera();
        }
    }

    // Update cost summary
    function updateCostSummary() {
        const budget = {{ $sim->project->budget ?? 0 }};
        const spent = {{ $sim->project->spent ?? 0 }};
        
        if (budget > 0) {
            const usagePercent = Math.round((spent / budget) * 100);
            document.getElementById('budgetUsagePercent').textContent = usagePercent + '%';
            document.getElementById('budgetUsageBar').style.width = usagePercent + '%';
            
            // Change color based on usage
            const bar = document.getElementById('budgetUsageBar');
            if (usagePercent > 90) {
                bar.className = 'progress-bar bg-danger';
            } else if (usagePercent > 75) {
                bar.className = 'progress-bar bg-warning';
            } else {
                bar.className = 'progress-bar bg-success';
            }
        }
        
        // Calculate monthly spent (simplified)
        const monthlySpent = Math.floor(spent / 6); // Assume 6 months
        document.getElementById('monthlySpent').textContent = 
            new Intl.NumberFormat('tr-TR').format(monthlySpent) + ' ₺';
        
        // Calculate completion rate
        const completionRate = Math.min(100, Math.round((spent / budget) * 100));
        document.getElementById('completionRate').textContent = completionRate + '%';
    }

    // Initialize everything
    initChart();
    updateCostSummary();
    
    // Show success message
    setTimeout(() => {
        const toast = document.createElement('div');
        toast.className = 'alert alert-success alert-dismissible fade show position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>5D Maliyet simülasyonu başarıyla yüklendi
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 5000);
    }, 1000);
</script>
@endsection
@endsection