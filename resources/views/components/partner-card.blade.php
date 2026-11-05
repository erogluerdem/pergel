
@props(['partner', 'partnerTotals', 'bankAccounts', 'lastRates'])

<div class="card mb-4 shadow-sm">
    <div class="card-header">
        <h5 class="my-0 fw-normal">{{ $partner->name }}</h5>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="card-title mb-0">Pay: <span class="fw-bold">%{{ $partner->share }}</span></h6>
            <div>
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCapitalModal-{{ $partner->id }}">
                    <i class="fas fa-plus me-1"></i> Sermaye Ekle
                </button>
                <a href="{{ route('partners.transactions', ['partner_id' => $partner->id]) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-history me-1"></i> İşlem Geçmişi
                </a>
            </div>
        </div>

        <div class="row text-center mb-3">
            <div class="col">
                <div class="fs-5 text-success">{{ number_format($partnerTotals[$partner->id]['in'] ?? 0, 2, ',', '.') }}</div>
                <small class="text-muted">ALDIKLARI (TL)</small>
            </div>
            <div class="col">
                <div class="fs-5 text-danger">{{ number_format($partnerTotals[$partner->id]['out'] ?? 0, 2, ',', '.') }}</div>
                <small class="text-muted">VERDİKLERİ (TL)</small>
            </div>
            <div class="col">
                @php
                    $balance = ($partnerTotals[$partner->id]['in'] ?? 0) - ($partnerTotals[$partner->id]['out'] ?? 0);
                @endphp
                <div class="fs-5 fw-bold {{ $balance >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($balance, 2, ',', '.') }}</div>
                <small class="text-muted">BAKİYE (TL)</small>
            </div>
        </div>

        <div class="accordion" id="partnerAccordion-{{ $partner->id }}">
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-{{ $partner->id }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $partner->id }}" aria-expanded="false" aria-controls="collapse-{{ $partner->id }}">
                        Son 5 İşlemi Göster/Gizle
                    </button>
                </h2>
                <div id="collapse-{{ $partner->id }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $partner->id }}" data-bs-parent="#partnerAccordion-{{ $partner->id }}">
                    <div class="accordion-body p-0">
                        @if($partner->capitalTransactions->isEmpty())
                            <div class="p-3">Henüz sermaye hareketi yok.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Tarih</th>
                                            <th>Tutar</th>
                                            <th>Kaynak</th>
                                            <th>Yön</th>
                                            <th>Açıklama</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($partner->capitalTransactions->take(5) as $ct)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse(optional($ct->cashBankTransaction)->transaction_date ?? $ct->transaction_date)->format('d.m.Y') }}</td>
                                                <td class="text-end">{{ number_format($ct->amount, 2, ',', '.') }}</td>
                                                <td>{{ ucfirst($ct->source) }}</td>
                                                <td>
                                                    @php $dir = optional($ct->cashBankTransaction)->direction ?? 'in'; @endphp
                                                    @if($dir === 'in')
                                                        <span class="badge bg-soft-success text-success">Geldi</span>
                                                    @else
                                                        <span class="badge bg-soft-danger text-danger">Gitti</span>
                                                    @endif
                                                </td>
                                                <td>{{ Str::limit($ct->description, 30) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Capital Modal -->
<x-add-capital-modal :partner="$partner" :bankAccounts="$bankAccounts" :lastRates="$lastRates" />
