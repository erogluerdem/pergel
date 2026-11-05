@extends('layouts.master')

@section('title','What-If Senaryo Analizi')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>❓ What-If Senaryo Analizi</h4>
            <p class="text-muted">Farklı senaryoların projelere ve portföye etkilerini simüle edin</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('analytics.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left"></i> Geri
            </a>
            <button class="btn btn-outline-primary" onclick="resetForm()">
                <i class="mdi mdi-refresh"></i> Sıfırla
            </button>
        </div>
    </div>

    <!-- Senaryo Oluşturma Formu -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Senaryo Parametreleri</h5>
        </div>
        <div class="card-body">
            <form id="scenarioForm">
                <div class="row">
                    <div class="col-md-6">
                        <h6>🔧 Dış Faktörler</h6>
                        <div class="mb-3">
                            <label class="form-label">Demir Fiyatı Değişimi (%)</label>
                            <input type="number" class="form-control" id="steelPriceChange" step="0.1" placeholder="Örn: 15.5">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Çimento Fiyatı Değişimi (%)</label>
                            <input type="number" class="form-control" id="cementPriceChange" step="0.1" placeholder="Örn: -5.2">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">İşçilik Maliyeti Değişimi (%)</label>
                            <input type="number" class="form-control" id="laborCostChange" step="0.1" placeholder="Örn: 8.0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kur Değişimi (USD/TRY)</label>
                            <input type="number" class="form-control" id="exchangeRateChange" step="0.01" placeholder="Örn: 32.50">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>📅 Proje Bazlı Değişiklikler</h6>
                            <div class="mb-3">
                                <label class="form-label">Proje Seçin</label>
                                <select class="form-select" id="selectedProject">
                                    <option value="">Tüm projeler için</option>
                                    @foreach($activeProjects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        <div class="mb-3">
                            <label class="form-label">Zaman Kayması (Ay)</label>
                            <input type="number" class="form-control" id="timeDelay" placeholder="Örn: 2">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ek Maliyet (%)</label>
                            <input type="number" class="form-control" id="additionalCost" step="0.1" placeholder="Örn: 12.5">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kapsam Azaltma (%)</label>
                            <input type="number" class="form-control" id="scopeReduction" step="0.1" placeholder="Örn: -8.0">
                        </div>
                    </div>
                </div>
                    <div class="text-end">
                    <button id="runScenarioBtn" type="button" class="btn btn-success" onclick="runScenario()" disabled>
                        <i class="mdi mdi-play"></i> Senaryoyu Çalıştır
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Senaryo Sonuçları -->
    <div id="scenarioResults" class="d-none">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">📊 Senaryo Sonuçları</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h6>Portföy Kârlılığı</h6>
                            <h4 id="portfolioProfit" class="text-success">₺0</h4>
                            <small class="text-muted">Değişim: <span id="portfolioProfitChange">0%</span></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h6>Ortalama Proje Gecikmesi</h6>
                            <h4 id="avgDelay" class="text-warning">0 gün</h4>
                            <small class="text-muted">Değişim: <span id="avgDelayChange">0%</span></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h6>Riskli Proje Sayısı</h6>
                            <h4 id="riskyProjects" class="text-danger">0</h4>
                            <small class="text-muted">Değişim: <span id="riskyProjectsChange">0</span></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h6>Nakit Akışı Etkisi</h6>
                            <h4 id="cashFlowImpact" class="text-info">₺0</h4>
                            <small class="text-muted">Değişim: <span id="cashFlowImpactChange">0%</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Etkilenen Projeler -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">🎯 Etkilenen Projeler</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Proje</th>
                                <th>Orijinal Kâr</th>
                                <th>Senaryo Kârı</th>
                                <th>Fark</th>
                                <th>Zaman Etkisi</th>
                                <th>Risk Değişimi</th>
                            </tr>
                        </thead>
                        <tbody id="affectedProjectsTable">
                            <!-- JavaScript ile doldurulacak -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Hazır Senaryolar -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">🚀 Hazır Senaryolar</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <button class="btn btn-outline-primary w-100 mb-2" onclick="loadScenario('steel_crisis')">
                        <i class="mdi mdi-trending-up"></i><br>
                        Demir Krizi<br>
                        <small>+25% demir fiyatı</small>
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-primary w-100 mb-2" onclick="loadScenario('pandemic')">
                        <i class="mdi mdi-account-group"></i><br>
                        Pandemi Etkisi<br>
                        <small>+15% işçilik, -10% verimlilik</small>
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-primary w-100 mb-2" onclick="loadScenario('economic_crisis')">
                        <i class="mdi mdi-chart-line-variant"></i><br>
                        Ekonomik Kriz<br>
                        <small>+30% döviz kuru</small>
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-primary w-100 mb-2" onclick="loadScenario('supply_chain')">
                        <i class="mdi mdi-package-variant"></i><br>
                        Tedarik Zinciri<br>
                        <small>+20% malzeme, +3 ay gecikme</small>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let baselineData = null;

document.addEventListener('DOMContentLoaded', function() {
    // Baseline verilerini API'den asenkron yükle
    loadBaselineData();
});

async function loadBaselineData() {
    try {
        const res = await fetch('/api/analytics/baseline');
        if (!res.ok) throw new Error('HTTP ' + res.status);
        baselineData = await res.json();
        if (!baselineData) throw new Error('Empty baseline data');
    } catch (err) {
        console.error('baseline load failed', err);
        // fallback to safe defaults
        baselineData = { portfolioProfit:0, avgDelay:0, riskyProjects:0, cashFlowImpact:0, projects: [] };
    }
    // enable run button after baseline is ready
    const btn = document.getElementById('runScenarioBtn');
    if (btn) btn.disabled = false;
}

function runScenario() {
    const scenario = {
        steelPriceChange: parseFloat(document.getElementById('steelPriceChange').value) || 0,
        cementPriceChange: parseFloat(document.getElementById('cementPriceChange').value) || 0,
        laborCostChange: parseFloat(document.getElementById('laborCostChange').value) || 0,
        exchangeRateChange: parseFloat(document.getElementById('exchangeRateChange').value) || 0,
        selectedProject: document.getElementById('selectedProject').value,
        timeDelay: parseInt(document.getElementById('timeDelay').value) || 0,
        additionalCost: parseFloat(document.getElementById('additionalCost').value) || 0,
        scopeReduction: parseFloat(document.getElementById('scopeReduction').value) || 0
    };

    // Senaryo hesaplaması (simplified)
    const results = calculateScenario(scenario);

    displayResults(results);
}

function calculateScenario(scenario) {
    // Basit simülasyon - gerçek uygulamada daha karmaşık hesaplamalar yapılacak
    let portfolioProfit = baselineData.portfolioProfit;
    let totalImpact = 0;

    // Dış faktör etkileri
    const externalImpact = (scenario.steelPriceChange * 0.15) +
                          (scenario.cementPriceChange * 0.10) +
                          (scenario.laborCostChange * 0.20) +
                          ((scenario.exchangeRateChange - 30) * 0.05); // 30 varsayılan kur

    // Proje bazlı etkiler
    const projectImpact = scenario.additionalCost - scenario.scopeReduction;

    totalImpact = externalImpact + projectImpact;

    portfolioProfit = portfolioProfit * (1 - totalImpact / 100);

    const portfolioProfitChange = baselineData && baselineData.portfolioProfit ? ((portfolioProfit - baselineData.portfolioProfit) / baselineData.portfolioProfit * 100) : 0;

    const results = {
        portfolioProfit: portfolioProfit,
        portfolioProfitChange: portfolioProfitChange.toFixed ? portfolioProfitChange.toFixed(1) : Number(portfolioProfitChange).toFixed(1),
        avgDelay: baselineData.avgDelay + scenario.timeDelay,
        avgDelayChange: scenario.timeDelay,
        riskyProjects: Math.max(0, baselineData.riskyProjects + Math.floor(totalImpact / 10)),
        riskyProjectsChange: Math.floor(totalImpact / 10),
    cashFlowImpact: -portfolioProfit * 0.1,
    cashFlowImpactChange: (-10).toFixed(1),
        projects: baselineData.projects.map(project => ({
            ...project,
            scenarioProfit: project.originalProfit * (1 - totalImpact / 100),
            difference: project.originalProfit * (1 - totalImpact / 100) - project.originalProfit,
            timeImpact: scenario.timeDelay,
            riskChange: Math.floor(totalImpact / 5)
        }))
    };

    return results;
}

function displayResults(results) {
    document.getElementById('scenarioResults').classList.remove('d-none');

    document.getElementById('portfolioProfit').textContent = new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(results.portfolioProfit);
    document.getElementById('portfolioProfitChange').textContent = results.portfolioProfitChange + '%';
    document.getElementById('portfolioProfitChange').className = results.portfolioProfitChange < 0 ? 'text-danger' : 'text-success';

    document.getElementById('avgDelay').textContent = results.avgDelay + ' gün';
    document.getElementById('avgDelayChange').textContent = '+' + results.avgDelayChange;

    document.getElementById('riskyProjects').textContent = results.riskyProjects;
    document.getElementById('riskyProjectsChange').textContent = results.riskyProjectsChange > 0 ? '+' + results.riskyProjectsChange : results.riskyProjectsChange;

    document.getElementById('cashFlowImpact').textContent = new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(results.cashFlowImpact);
    document.getElementById('cashFlowImpactChange').textContent = results.cashFlowImpactChange + '%';

    // Proje tablosunu doldur
    const tbody = document.getElementById('affectedProjectsTable');
    tbody.innerHTML = '';

    results.projects.forEach(project => {
        const row = `
            <tr>
                <td>${project.name}</td>
                <td>${new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(project.originalProfit)}</td>
                <td>${new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(project.scenarioProfit)}</td>
                <td class="${project.difference < 0 ? 'text-danger' : 'text-success'}">
                    ${new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(project.difference)}
                </td>
                <td>+${project.timeImpact} ay</td>
                <td>${project.riskChange > 0 ? '+' + project.riskChange : project.riskChange}</td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function loadScenario(scenarioType) {
    const scenarios = {
        steel_crisis: { steelPriceChange: 25 },
        pandemic: { laborCostChange: 15, scopeReduction: -10 },
        economic_crisis: { exchangeRateChange: 35 },
        supply_chain: { steelPriceChange: 20, cementPriceChange: 15, timeDelay: 3 }
    };

    const scenario = scenarios[scenarioType];
    Object.keys(scenario).forEach(key => {
        const element = document.getElementById(key);
        if (element) element.value = scenario[key];
    });

    runScenario();
}

function resetForm() {
    document.getElementById('scenarioForm').reset();
    document.getElementById('scenarioResults').classList.add('d-none');
}
</script>

<!-- Chart.js for graphs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
@endsection
