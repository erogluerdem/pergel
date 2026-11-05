
@props(['partner', 'bankAccounts', 'lastRates'])

<div class="modal fade" id="addCapitalModal-{{ $partner->id }}" tabindex="-1" aria-labelledby="addCapitalModalLabel-{{ $partner->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCapitalModalLabel-{{ $partner->id }}">{{ $partner->name }} için Sermaye Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('partners.capital', $partner->id) }}" method="POST" class="add-capital-form">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="amount-{{ $partner->id }}" class="form-label">Tutar</label>
                            <input type="number" step="0.01" name="amount" id="amount-{{ $partner->id }}" placeholder="Tutar" required class="form-control amount-input">
                        </div>
                        <div class="col-md-6">
                            <label for="currency-{{ $partner->id }}" class="form-label">Para Birimi</label>
                            <select name="currency" id="currency-{{ $partner->id }}" class="form-select">
                                <option value="TRY">TL</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                            </select>
                        </div>
                        <div class="col-md-6 exchange-rate-wrapper d-none">
                            <label for="exchange_rate-{{ $partner->id }}" class="form-label">Kur</label>
                            <input type="number" step="0.000001" name="exchange_rate" id="exchange_rate-{{ $partner->id }}" class="form-control exchange-rate-input" placeholder="Kur">
                        </div>
                        <div class="col-md-6">
                            <label for="source-{{ $partner->id }}" class="form-label">Kaynak</label>
                            <select name="source" id="source-{{ $partner->id }}" class="form-select">
                                <option value="cash">Kasa</option>
                                <option value="bank">Banka</option>
                            </select>
                        </div>
                        <div class="col-12 bank-select-wrapper d-none">
                            <label for="bank_account_id-{{ $partner->id }}" class="form-label">Banka Hesabı</label>
                            <select name="bank_account_id" id="bank_account_id-{{ $partner->id }}" class="form-select bank-select">
                                <option value="">Banka hesabı seçin...</option>
                                @foreach($bankAccounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->bank_name ? $acc->bank_name . ' - ' . $acc->name : $acc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="description-{{ $partner->id }}" class="form-label">Açıklama</label>
                            <input type="text" name="description" id="description-{{ $partner->id }}" placeholder="Açıklama" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="transaction_date-{{ $partner->id }}" class="form-label">Tarih</label>
                            <input type="date" name="transaction_date" id="transaction_date-{{ $partner->id }}" value="{{ date('Y-m-d') }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
