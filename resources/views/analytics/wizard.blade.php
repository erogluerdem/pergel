@extends('layouts.master')

@section('title','Rapor Sihirbazı')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>🧙‍♂️ Rapor Sihirbazı</h4>
            <p class="text-muted">Kişiselleştirilebilir raporlarınızı adım adım oluşturun</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('analytics.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left"></i> Geri
            </a>
            <button class="btn btn-outline-primary" onclick="resetWizard()">
                <i class="mdi mdi-refresh"></i> Yeniden Başla
            </button>
        </div>
    </div>

    <!-- Wizard Progress -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="wizard-progress">
                <div class="step" id="step1">
                    <div class="step-circle">1</div>
                    <div class="step-title">Veri Kaynağı</div>
                </div>
                <div class="step-line"></div>
                <div class="step" id="step2">
                    <div class="step-circle">2</div>
                    <div class="step-title">Alanlar & Filtreler</div>
                </div>
                <div class="step-line"></div>
                <div class="step" id="step3">
                    <div class="step-circle">3</div>
                    <div class="step-title">Görselleştirme</div>
                </div>
                <div class="step-line"></div>
                <div class="step" id="step4">
                    <div class="step-circle">4</div>
                    <div class="step-title">Kaydet & Zamanla</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wizard Content -->
    <div class="card">
        <div class="card-body">
            <!-- Adım 1: Veri Kaynağı Seçimi -->
            <div id="wizard-step-1" class="wizard-step">
                <h5>📊 Adım 1: Veri Kaynağını Seçin</h5>
                <p class="text-muted">Raporunuzun temel alacağı veri kümesini belirleyin</p>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 data-source-card" data-source="projects" onclick="selectDataSource('projects')">
                            <div class="card-body text-center">
                                <i class="mdi mdi-folder-multiple-outline mdi-36px text-primary mb-2"></i>
                                <h6>Proje Listesi</h6>
                                <small class="text-muted">Aktif ve tamamlanmış projeler</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 data-source-card" data-source="cost_items" onclick="selectDataSource('cost_items')">
                            <div class="card-body text-center">
                                <i class="mdi mdi-cash-multiple mdi-36px text-success mb-2"></i>
                                <h6>Maliyet Kalemleri</h6>
                                <small class="text-muted">Bütçe ve gerçekleşen maliyetler</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 data-source-card" data-source="payments" onclick="selectDataSource('payments')">
                            <div class="card-body text-center">
                                <i class="mdi mdi-receipt mdi-36px text-info mb-2"></i>
                                <h6>Hakedişler</h6>
                                <small class="text-muted">Ödemeler ve hakediş bilgileri</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 data-source-card" data-source="incidents" onclick="selectDataSource('incidents')">
                            <div class="card-body text-center">
                                <i class="mdi mdi-alert-circle mdi-36px text-danger mb-2"></i>
                                <h6>İSG Olayları</h6>
                                <small class="text-muted">Güvenlik olayları ve kazalar</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-primary" onclick="nextStep(1)">İleri <i class="mdi mdi-arrow-right"></i></button>
                </div>
            </div>

            <!-- Adım 2: Alanlar ve Filtreler -->
            <div id="wizard-step-2" class="wizard-step d-none">
                <h5>🔍 Adım 2: Alanları Seçin ve Filtreleyin</h5>
                <p class="text-muted">Raporunuzda görmek istediğiniz bilgileri ve filtreleri belirleyin</p>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Gösterilecek Alanlar</h6>
                        <div id="fields-container" class="border p-3" style="min-height: 200px;">
                            <p class="text-muted">Önce veri kaynağını seçin</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Filtreler</h6>
                        <div id="filters-container" class="border p-3" style="min-height: 200px;">
                            <div class="mb-3">
                                <label class="form-label">Durum</label>
                                <select class="form-select filter-select" data-field="status">
                                    <option value="">Tümü</option>
                                    <option value="active">Aktif</option>
                                    <option value="completed">Tamamlandı</option>
                                    <option value="on-hold">Beklemede</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tarih Aralığı</label>
                                <input type="date" class="form-control filter-input" data-field="start_date" placeholder="Başlangıç tarihi">
                                <input type="date" class="form-control filter-input mt-2" data-field="end_date" placeholder="Bitiş tarihi">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-outline-secondary me-2" onclick="prevStep(2)"><i class="mdi mdi-arrow-left"></i> Geri</button>
                    <button class="btn btn-primary" onclick="nextStep(2)">İleri <i class="mdi mdi-arrow-right"></i></button>
                </div>
            </div>

            <!-- Adım 3: Görselleştirme -->
            <div id="wizard-step-3" class="wizard-step d-none">
                <h5>📈 Adım 3: Görselleştirin ve Gruplandırın</h5>
                <p class="text-muted">Verilerinizi nasıl görüntülemek istediğinizi seçin</p>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Grafik Türü</h6>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="card chart-type-card" data-chart="table" onclick="selectChartType('table')">
                                    <div class="card-body text-center p-2">
                                        <i class="mdi mdi-table mdi-24px text-primary mb-1"></i>
                                        <div class="small">Tablo</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="card chart-type-card" data-chart="bar" onclick="selectChartType('bar')">
                                    <div class="card-body text-center p-2">
                                        <i class="mdi mdi-chart-bar mdi-24px text-success mb-1"></i>
                                        <div class="small">Çubuk Grafik</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="card chart-type-card" data-chart="pie" onclick="selectChartType('pie')">
                                    <div class="card-body text-center p-2">
                                        <i class="mdi mdi-chart-pie mdi-24px text-info mb-1"></i>
                                        <div class="small">Pasta Grafik</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="card chart-type-card" data-chart="line" onclick="selectChartType('line')">
                                    <div class="card-body text-center p-2">
                                        <i class="mdi mdi-chart-line mdi-24px text-warning mb-1"></i>
                                        <div class="small">Çizgi Grafik</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Gruplandırma</h6>
                        <div class="mb-3">
                            <label class="form-label">Gruplandırma Alanı</label>
                            <select class="form-select" id="groupBy">
                                <option value="">Gruplandırma Yok</option>
                                <option value="status">Durum</option>
                                <option value="project_manager">Proje Müdürü</option>
                                <option value="contractor">Taşeron</option>
                                <option value="month">Ay</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sıralama</label>
                            <select class="form-select" id="sortBy">
                                <option value="name">İsim</option>
                                <option value="date">Tarih</option>
                                <option value="amount">Miktar</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Önizleme -->
                <div class="mt-4">
                    <h6>Önizleme</h6>
                    <div id="preview-container" class="border p-3 bg-light" style="min-height: 200px;">
                        <p class="text-muted text-center">Rapor ayarlarınızı tamamladığınızda önizleme burada görünecek</p>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-outline-secondary me-2" onclick="prevStep(3)"><i class="mdi mdi-arrow-left"></i> Geri</button>
                    <button class="btn btn-success" onclick="generatePreview()">Önizleme Oluştur</button>
                    <button class="btn btn-primary ms-2" onclick="nextStep(3)">İleri <i class="mdi mdi-arrow-right"></i></button>
                </div>
            </div>

            <!-- Adım 4: Kaydet ve Zamanla -->
            <div id="wizard-step-4" class="wizard-step d-none">
                <h5>💾 Adım 4: Kaydedin ve Zamanlayın</h5>
                <p class="text-muted">Raporunuzu kaydedin ve otomatik gönderim ayarları yapın</p>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Rapor Bilgileri</h6>
                        <div class="mb-3">
                            <label class="form-label">Rapor Adı *</label>
                            <input type="text" class="form-control" id="reportName" placeholder="Örn: Aylık Maliyet Raporu">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" id="reportDescription" rows="3" placeholder="Rapor hakkında kısa açıklama"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" id="reportCategory">
                                <option value="financial">Finansal</option>
                                <option value="operational">Operasyonel</option>
                                <option value="safety">Güvenlik</option>
                                <option value="performance">Performans</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Otomatik Gönderim</h6>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="enableScheduling">
                                <label class="form-check-label" for="enableScheduling">
                                    Otomatik rapor gönderimi aktif et
                                </label>
                            </div>
                        </div>
                        <div id="scheduling-options" class="d-none">
                            <div class="mb-3">
                                <label class="form-label">Gönderim Sıklığı</label>
                                <select class="form-select" id="scheduleFrequency">
                                    <option value="daily">Günlük</option>
                                    <option value="weekly">Haftalık</option>
                                    <option value="monthly">Aylık</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alıcılar</label>
                                <input type="text" class="form-control" id="recipients" placeholder="E-posta adreslerini virgülle ayırın">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Başlangıç Tarihi</label>
                                <input type="date" class="form-control" id="scheduleStartDate">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-outline-secondary me-2" onclick="prevStep(4)"><i class="mdi mdi-arrow-left"></i> Geri</button>
                    <button class="btn btn-success" onclick="saveReport()">
                        <i class="mdi mdi-content-save"></i> Raporu Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.wizard-progress {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 2rem;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
}

.step.active .step-circle {
    background-color: #007bff;
    color: white;
}

.step.completed .step-circle {
    background-color: #28a745;
    color: white;
}

.step-line {
    width: 100px;
    height: 2px;
    background-color: #e9ecef;
    margin: 0 1rem;
}

.step.completed + .step-line {
    background-color: #28a745;
}

.step-title {
    font-size: 0.875rem;
    color: #6c757d;
    text-align: center;
}

.step.active .step-title {
    color: #007bff;
    font-weight: bold;
}

.data-source-card, .chart-type-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.data-source-card:hover, .chart-type-card:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.data-source-card.selected, .chart-type-card.selected {
    border-color: #007bff;
    background-color: #f8f9fa;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    transform: scale(1.05);
    position: relative;
}

.data-source-card.selected::after, .chart-type-card.selected::after {
    content: '✓';
    position: absolute;
    top: 5px;
    right: 5px;
    background-color: #28a745;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}
</style>

<script>
let currentStep = 1;
let wizardData = {
    dataSource: null,
    fields: [],
    filters: {},
    chartType: 'table',
    groupBy: null,
    sortBy: 'name'
};

document.addEventListener('DOMContentLoaded', function() {
    initializeWizard();
});

function initializeWizard() {
    console.log('Wizard başlatılıyor...');

    const dataSourceCards = document.querySelectorAll('.data-source-card');
    console.log('Veri kaynağı kartları bulundu:', dataSourceCards.length);

    // Veri kaynağı kartlarına tıklama eventi
    dataSourceCards.forEach(card => {
        card.addEventListener('click', function() {
            console.log('Kart tıklandı:', this.dataset.source);
            selectDataSource(this.dataset.source);
        });
    });

    const chartTypeCards = document.querySelectorAll('.chart-type-card');
    console.log('Grafik türü kartları bulundu:', chartTypeCards.length);

    // Grafik türü kartlarına tıklama eventi
    chartTypeCards.forEach(card => {
        card.addEventListener('click', function() {
            console.log('Grafik kartı tıklandı:', this.dataset.chart);
            selectChartType(this.dataset.chart);
        });
    });

    // Zamanlama checkbox eventi
    document.getElementById('enableScheduling').addEventListener('change', function() {
        document.getElementById('scheduling-options').classList.toggle('d-none', !this.checked);
    });
}

function selectDataSource(source) {
    console.log('Veri kaynağı seçildi:', source);
    wizardData.dataSource = source;

    // Seçili kartı işaretle
    document.querySelectorAll('.data-source-card').forEach(card => {
        card.classList.remove('selected');
    });
    document.querySelector(`[data-source="${source}"]`).classList.add('selected');

    // Alanları yükle
    loadFieldsForDataSource(source);
}

function selectChartType(chartType) {
    console.log('Grafik türü seçildi:', chartType);
    wizardData.chartType = chartType;

    // Seçili kartı işaretle
    document.querySelectorAll('.chart-type-card').forEach(card => {
        card.classList.remove('selected');
    });
    document.querySelector(`[data-chart="${chartType}"]`).classList.add('selected');
}

function loadFieldsForDataSource(source) {
    const fieldsContainer = document.getElementById('fields-container');
    const fieldOptions = {
        projects: ['name', 'status', 'start_date', 'end_date', 'budget', 'project_manager'],
        cost_items: ['description', 'budget_amount', 'actual_amount', 'category', 'project_name'],
        payments: ['amount', 'date', 'description', 'project_name', 'contractor'],
        incidents: ['title', 'date', 'severity', 'status', 'project_name', 'injuries']
    };

    const fields = fieldOptions[source] || [];
    fieldsContainer.innerHTML = '';

    fields.forEach(field => {
        const fieldDiv = document.createElement('div');
        fieldDiv.className = 'form-check';
        fieldDiv.innerHTML = `
            <input class="form-check-input field-checkbox" type="checkbox" value="${field}" id="field_${field}">
            <label class="form-check-label" for="field_${field}">
                ${field.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}
            </label>
        `;
        fieldsContainer.appendChild(fieldDiv);
    });
}

function nextStep(fromStep) {
    if (fromStep === 1 && !wizardData.dataSource) {
        Swal.fire({
            icon: 'warning',
            title: 'Veri Kaynağı Seçin',
            text: 'Devam etmek için lütfen bir veri kaynağı seçin',
            confirmButtonText: 'Tamam',
            confirmButtonColor: '#007bff'
        });
        return;
    }

    if (fromStep === 2) {
        // Seçili alanları kaydet
        wizardData.fields = Array.from(document.querySelectorAll('.field-checkbox:checked')).map(cb => cb.value);
        wizardData.filters = {};
        document.querySelectorAll('.filter-select, .filter-input').forEach(el => {
            if (el.value) {
                wizardData.filters[el.dataset.field] = el.value;
            }
        });
    }

    if (fromStep === 3) {
        wizardData.groupBy = document.getElementById('groupBy').value;
        wizardData.sortBy = document.getElementById('sortBy').value;
    }

    // Adım geçişi
    document.getElementById(`wizard-step-${fromStep}`).classList.add('d-none');
    document.getElementById(`wizard-step-${fromStep + 1}`).classList.remove('d-none');

    // Progress güncelle
    document.getElementById(`step${fromStep}`).classList.add('completed');
    document.getElementById(`step${fromStep + 1}`).classList.add('active');

    currentStep = fromStep + 1;
}

function prevStep(fromStep) {
    document.getElementById(`wizard-step-${fromStep}`).classList.add('d-none');
    document.getElementById(`wizard-step-${fromStep - 1}`).classList.remove('d-none');

    document.getElementById(`step${fromStep}`).classList.remove('active');
    document.getElementById(`step${fromStep - 1}`).classList.remove('completed');
    document.getElementById(`step${fromStep - 1}`).classList.add('active');

    currentStep = fromStep - 1;
}

function generatePreview() {
    const previewContainer = document.getElementById('preview-container');

    if (!wizardData.fields.length) {
        Swal.fire({
            icon: 'info',
            title: 'Alan Seçin',
            text: 'Önizleme için en az bir alan seçmelisiniz',
            confirmButtonText: 'Tamam',
            confirmButtonColor: '#17a2b8'
        });
        return;
    }

    // Basit önizleme (gerçek uygulamada AJAX çağrısı yapılacak)
    previewContainer.innerHTML = `
        <div class="text-center">
            <i class="mdi mdi-chart-${wizardData.chartType} mdi-48px text-primary mb-3"></i>
            <h6>${wizardData.dataSource} verilerinin ${wizardData.chartType} grafiği</h6>
            <p class="small text-muted">Seçili alanlar: ${wizardData.fields.join(', ')}</p>
            <div class="bg-white border p-3 mt-3">
                <canvas id="previewChart" width="400" height="200"></canvas>
            </div>
        </div>
    `;

    // Basit örnek grafik oluştur
    setTimeout(() => {
        const ctx = document.getElementById('previewChart');
        if (ctx) {
            new Chart(ctx, {
                type: wizardData.chartType === 'table' ? 'bar' : wizardData.chartType,
                data: {
                    labels: ['Örnek 1', 'Örnek 2', 'Örnek 3'],
                    datasets: [{
                        label: 'Örnek Veri',
                        data: [10, 20, 30],
                        backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 205, 86, 0.2)'],
                        borderColor: ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }, 100);
}

function saveReport() {
    const reportName = document.getElementById('reportName').value;
    if (!reportName) {
        Swal.fire({
            icon: 'error',
            title: 'Rapor Adı Gerekli',
            text: 'Lütfen rapor için bir ad girin',
            confirmButtonText: 'Tamam',
            confirmButtonColor: '#dc3545'
        });
        return;
    }

    const reportData = {
        ...wizardData,
        name: reportName,
        description: document.getElementById('reportDescription').value,
        category: document.getElementById('reportCategory').value,
        scheduling: {
            enabled: document.getElementById('enableScheduling').checked,
            frequency: document.getElementById('scheduleFrequency').value,
            recipients: document.getElementById('recipients').value,
            startDate: document.getElementById('scheduleStartDate').value
        }
    };

    // AJAX çağrısı ile kaydet (gerçek uygulamada)
    console.log('Rapor kaydediliyor:', reportData);

    Swal.fire({
        icon: 'success',
        title: 'Rapor Kaydedildi!',
        text: 'Raporunuz başarıyla kaydedildi',
        confirmButtonText: 'Tamam',
        confirmButtonColor: '#28a745'
    }).then(() => {
        window.location.href = '{{ route("analytics.index") }}';
    });
}

function resetWizard() {
    if (confirm('Sihirbazı sıfırlamak istediğinizden emin misiniz?')) {
        currentStep = 1;
        wizardData = {
            dataSource: null,
            fields: [],
            filters: {},
            chartType: 'table',
            groupBy: null,
            sortBy: 'name'
        };

        // Tüm adımları gizle, ilk adımı göster
        document.querySelectorAll('.wizard-step').forEach(step => step.classList.add('d-none'));
        document.getElementById('wizard-step-1').classList.remove('d-none');

        // Progress sıfırla
        document.querySelectorAll('.step').forEach(step => {
            step.classList.remove('active', 'completed');
        });
        document.getElementById('step1').classList.add('active');

        // Formları sıfırla
        document.getElementById('scenarioForm').reset();
        document.querySelectorAll('.selected').forEach(el => el.classList.remove('selected'));
    }
}
</script>

<!-- Chart.js for wizard visualizations -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<!-- SweetAlert2 for modern alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
@endsection
