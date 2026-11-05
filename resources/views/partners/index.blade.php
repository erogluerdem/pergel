@extends('layouts.master')

@section('styles')
{{-- Font Awesome for icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
<style>
    .bg-soft-success { background-color: rgba(40, 199, 111, 0.1); }
    .bg-soft-danger { background-color: rgba(244, 67, 54, 0.1); }
    .text-success { color: #28c76f !important; }
    .text-danger { color: #ea5455 !important; }
    .card-header h5 {
        font-size: 1.1rem;
        font-weight: 600;
    }
    .accordion-button:not(.collapsed) {
        color: #000;
        background-color: #f8f9fa;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Ortaklar Yönetimi</h2>
    </div>

    {{-- Alanı ikiye böl: Ortak Kartları ve Yeni Ortak Ekleme Formu --}}
    <div class="row">
        <div class="col-lg-8">
            <div id="partner-list">
                <div class="row">
                    @foreach($partners as $partner)
                        <div class="col-md-6">
                            <x-partner-card :partner="$partner" :partnerTotals="$partnerTotals" :bankAccounts="$bankAccounts" :lastRates="$lastRates" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="my-0 fw-normal">Yeni Ortak Ekle</h5>
                </div>
                <div class="card-body">
                    <form id="add-partner-form" action="{{ route('partners.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Ortak Adı</label>
                            <input type="text" id="name" name="name" placeholder="Örn: Ahmet Yılmaz" required class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="share" class="form-label">Pay Oranı (%)
                            </label>
                            <input type="number" id="share" name="share" placeholder="Örn: 50" required class="form-control">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Yeni Ortağı Ekle</button>
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
    const lastRates = @json($lastRates ?? []);

    // Function to handle dynamic fields in modals
    function setupModalEventListeners(modal) {
        const sourceSelect = modal.querySelector('select[name="source"]');
        const bankSelectWrapper = modal.querySelector('.bank-select-wrapper');
        const currencySelect = modal.querySelector('select[name="currency"]');
        const exchangeRateWrapper = modal.querySelector('.exchange-rate-wrapper');
        const exchangeRateInput = modal.querySelector('.exchange-rate-input');

        // Source (bank/cash) change
        sourceSelect.addEventListener('change', function() {
            if (this.value === 'bank') {
                bankSelectWrapper.classList.remove('d-none');
            } else {
                bankSelectWrapper.classList.add('d-none');
            }
        });

        // Currency change
        currencySelect.addEventListener('change', function() {
            if (this.value !== 'TRY') {
                exchangeRateWrapper.classList.remove('d-none');
                exchangeRateInput.required = true;
                if (lastRates[this.value]) {
                    exchangeRateInput.value = lastRates[this.value];
                }
            } else {
                exchangeRateWrapper.classList.add('d-none');
                exchangeRateInput.required = false;
            }
        });

        // Initial triggers
        sourceSelect.dispatchEvent(new Event('change'));
        currencySelect.dispatchEvent(new Event('change'));
    }

    // Setup for all existing modals on page load
    document.querySelectorAll('.modal').forEach(setupModalEventListeners);

    // AJAX for Add Partner form
    const addPartnerForm = document.getElementById('add-partner-form');
    addPartnerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.id) {
                // Reloading the page is a simple way to show the new partner.
                // A more advanced implementation would dynamically add the new card.
                location.reload();
            } else {
                // Handle errors
                alert(data.message || 'Bir hata oluştu.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('İstek gönderilirken bir hata oluştu.');
        });
    });

    // AJAX for Add Capital forms (delegated from a parent element)
    document.body.addEventListener('submit', function(e) {
        if (e.target.matches('.add-capital-form')) {
            e.preventDefault();
            const form = e.target;
            const modal = form.closest('.modal');
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Hide modal and reload page to see updated totals
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                    location.reload(); // Simple refresh to update data
                } else {
                    return response.json().then(data => {
                        let errorMsg = data.message || 'Bir hata oluştu.';
                        if(data.errors) {
                            errorMsg = Object.values(data.errors).flat().join('\n');
                        }
                        alert(errorMsg);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Sermaye eklenirken bir hata oluştu.');
            });
        }
    });
});
</script>
@endsection