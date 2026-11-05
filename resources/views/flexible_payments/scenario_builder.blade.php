@extends('layouts.master')

@section('title', 'Esnek Ödeme Senaryo Oluşturucu')

@push('styles')
<style>
    .fp-calc-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .fp-calc-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: fpCalcGlow 4s linear infinite;
    }

    @keyframes fpCalcGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .fp-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-fp-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-fp {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-fp:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .form-label-custom {
        font-weight: 800 !important;
        color: #0f172a !important;
        font-size: 0.88rem !important;
        margin-bottom: 6px !important;
    }

    .custom-result-kpi {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="fp-calc-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill fp-badge-pill">
                        <i class="ri-calculator-fill text-warning fs-5"></i> FİNANS & SİMÜLASYON PORTALI
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Taksit & Matrah Modeli
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Esnek Ödeme & Taksit Senaryo Hesaplayıcı</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Gayrimenkul ve satış projeksiyonları için peşinat, vade ve taksit tutarlarını anında simüle edin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('flexible-payments.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Ödeme Planları Listesi
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN CALCULATOR CARD -->
    <div class="card custom-fp-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-calculator-line text-warning fs-5"></i>Senaryo Hesaplama Parametreleri
            </h5>
        </div>

        <div class="card-body p-4">
            <form id="scenario-form">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label-custom">Toplam Satış Tutarı (₺) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="number" step="0.01" name="total_amount" class="form-control custom-input-fp" placeholder="10000000" value="10000000" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Peşinat Yüzdesi (%) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="down_payment_percentage" class="form-control custom-input-fp" value="25" min="0" max="100" required>
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">%</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Taksit Adedi <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="installment_count" class="form-control custom-input-fp" value="12" min="1" max="60" required>
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">Taksit</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Taksit İntervalı (Gün) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="interval_days" class="form-control custom-input-fp" value="30" min="1" required>
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">Gün</span>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('flexible-payments.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-flashlight-fill me-1"></i>Senaryoyu Hesapla & Simüle Et
                    </button>
                </div>
            </form>

            <!-- SIMULATION RESULTS -->
            <div id="scenario-result" class="mt-5 pt-4 border-top" style="display:none;">
                <h5 class="fw-extrabold mb-4 text-dark d-flex align-items-center gap-2">
                    <i class="ri-checkbox-circle-fill text-success fs-4"></i>Simülasyon Hesaplama Sonuçları
                </h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="custom-result-kpi border-start border-success border-4">
                            <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">HESAPLANAN PEŞİNAT</small>
                            <div class="fw-extrabold text-success font-monospace" style="font-size: 1.7rem;" id="res-down">₺0</div>
                            <small class="text-success fw-bold"><i class="ri-wallet-3-line me-1"></i>Peşin Ödenecek Tutar</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="custom-result-kpi border-start border-primary border-4">
                            <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">KALAN TAKSİTLENDİRİLEN TUTAR</small>
                            <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.7rem;" id="res-remaining">₺0</div>
                            <small class="text-primary fw-bold"><i class="ri-bank-card-line me-1"></i>Vadelendirilecek Bakiye</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="custom-result-kpi border-start border-warning border-4">
                            <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">DÖNEMSEL TAKSİT TUTARI</small>
                            <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.7rem;" id="res-installment">₺0</div>
                            <small class="text-warning fw-bold"><i class="ri-calendar-event-line me-1"></i>Aylık Taksit Tutarı</small>
                        </div>
                    </div>
                </div>

                <div class="p-3.5 rounded-3 border font-monospace" style="background: #f8fafc; border-left: 4px solid #0f172a !important; color: #0f172a;">
                    <div class="row text-center font-monospace fw-bold">
                        <div class="col-md-4">
                            <span class="text-muted">Toplam Satış Tutarı:</span> <span class="text-dark fs-6 ms-1" id="res-total">₺0</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted">Taksit Adedi:</span> <span class="text-dark fs-6 ms-1" id="res-count">0</span> Taksit
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted">Vade Periyodu:</span> <span class="text-dark fs-6 ms-1" id="res-interval">0</span> Günde Bir
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('scenario-form');
    if (!form) return;

    const formatter = new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY', maximumFractionDigits: 0 });

    form.addEventListener('submit', async function(e){
        e.preventDefault();
        const data = new FormData(form);
        const payload = Object.fromEntries(data.entries());

        try {
            const res = await fetch('{{ route('flexible-payments.calculate_scenario') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (!res.ok) throw new Error('Hesaplama sırasında hata oluştu.');
            const json = await res.json();

            document.getElementById('res-total').textContent = formatter.format(json.total_amount);
            document.getElementById('res-down').textContent = formatter.format(json.down_payment);
            document.getElementById('res-remaining').textContent = formatter.format(json.remaining_amount);
            document.getElementById('res-count').textContent = json.installment_count;
            document.getElementById('res-installment').textContent = formatter.format(json.installment_amount);
            document.getElementById('res-interval').textContent = json.interval_days;
            
            document.getElementById('scenario-result').style.display = 'block';
            document.getElementById('scenario-result').scrollIntoView({ behavior: 'smooth' });
        } catch (err) {
            alert(err.message || err);
        }
    });
});
</script>
@endpush
