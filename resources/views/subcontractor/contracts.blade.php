@extends('layouts.master')

@section('title', 'Taşeron Sözleşmeleri')

@section('topbar-title', 'Taşeron Yönetimi')

@section('css')
@endsection

@section('content')
<div class="container-fluid">
    <!-- KPI Kartları -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light text-primary rounded">
                                <i class="mdi mdi-file-document-outline font-size-24"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Aktif Sözleşme Sayısı</p>
                            <h4 class="mb-0">{{ $activeContracts }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light text-success rounded">
                                <i class="mdi mdi-cash-multiple font-size-24"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Toplam Taahhüt Tutarı</p>
                            <h4 class="mb-0">{{ number_format($totalCommitment, 0, ',', '.') }} TL</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light text-info rounded">
                                <i class="mdi mdi-cash-check font-size-24"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Toplam Hakediş Ödemesi</p>
                            <h4 class="mb-0">{{ number_format($totalPaid, 0, ',', '.') }} TL</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light text-warning rounded">
                                <i class="mdi mdi-alert-circle-outline font-size-24"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Onay Bekleyen Ek Talepler</p>
                            <h4 class="mb-0">{{ $pendingChangeOrders }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aksiyon Butonları -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Hızlı Aksiyonlar</h5>
                        <div>
                            <a href="{{ route('subcontractor.contracts.index') }}" class="btn btn-primary me-2">
                                <i class="mdi mdi-view-list"></i> Sözleşme Listesi
                            </a>
                            <a href="{{ route('contracts.create') }}" class="btn btn-success">
                                <i class="mdi mdi-plus"></i> Yeni Sözleşme Oluştur
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtre: Taşeron seçimi -->
    <div class="row mb-3">
        <div class="col-12">
            <form method="GET" class="d-flex align-items-center">
                <label class="me-2 mb-0">Taşeron:</label>
                <select name="subcontractor_id" class="form-select me-2" style="width:360px">
                    <option value="">Tümü (Genel Özet)</option>
                    @foreach($subcontractors ?? [] as $s)
                        <option value="{{ $s->id }}" {{ isset($subcontractorId) && $subcontractorId == $s->id ? 'selected' : '' }}>{{ $s->company_name }}</option>
                    @endforeach
                </select>
                <label class="me-2 mb-0">Tarih Aralığı:</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control me-2" style="width:150px">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control me-2" style="width:150px">
                <button class="btn btn-primary me-2">Filtrele</button>
                <a href="{{ route('subcontractor.contracts') }}" class="btn btn-outline-secondary">Temizle</a>
            </form>
        </div>
    </div>

    <!-- Sözleşme Özeti Tablosu -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Son Sözleşmeler</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sözleşme No / Adı</th>
                                    <th>Taşeron Firma</th>
                                    <th>Sözleşme Bedeli</th>
                                    <th>Ödenen Tutar</th>
                                    <th>Kalan Bakiye</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($contracts) && $contracts->count())
                                    @foreach($contracts as $contract)
                                <tr>
                                    <td>
                                        <a href="{{ route('subcontractor.contracts.show', $contract->id) }}" class="text-decoration-none">
                                            {{ $contract->contract_number }}<br>
                                            <small class="text-muted">{{ $contract->contract_name }}</small>
                                        </a>
                                    </td>
                                    <td>{{ $contract->subcontractor->company_name ?? ($contract->subcontractor_id ? 'Taşeron #' . $contract->subcontractor_id : 'N/A') }}</td>
                                    <td>{{ number_format($contract->total_amount, 0, ',', '.') }} TL</td>
                                    <td>{{ number_format($contract->paid_amount, 0, ',', '.') }} TL</td>
                                    <td>{{ number_format($contract->remaining_amount, 0, ',', '.') }} TL</td>
                                    <td>
                                        @switch($contract->status)
                                            @case('draft')
                                                <span class="badge bg-secondary">Taslak</span>
                                                @break
                                            @case('active')
                                                <span class="badge bg-success">Aktif</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-info">Tamamlandı</span>
                                                @break
                                            @case('terminated')
                                                <span class="badge bg-danger">Feshedildi</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('subcontractor.contracts.show', $contract->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-eye"></i> Detay
                                        </a>
                                    </td>
                                </tr>
                                    @endforeach
                                @else
                                @forelse(\App\Models\SubcontractorContract::with('subcontractor')->latest()->take(5)->get() as $contract)
                                <tr>
                                    <td>
                                        <a href="{{ route('subcontractor.contracts.show', $contract->id) }}" class="text-decoration-none">
                                            {{ $contract->contract_number }}<br>
                                            <small class="text-muted">{{ $contract->contract_name }}</small>
                                        </a>
                                    </td>
                                    <td>{{ $contract->subcontractor->company_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($contract->total_amount, 0, ',', '.') }} TL</td>
                                    <td>{{ number_format($contract->paid_amount, 0, ',', '.') }} TL</td>
                                    <td>{{ number_format($contract->remaining_amount, 0, ',', '.') }} TL</td>
                                    <td>
                                        @switch($contract->status)
                                            @case('draft')
                                                <span class="badge bg-secondary">Taslak</span>
                                                @break
                                            @case('active')
                                                <span class="badge bg-success">Aktif</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-info">Tamamlandı</span>
                                                @break
                                            @case('terminated')
                                                <span class="badge bg-danger">Feshedildi</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('subcontractor.contracts.show', $contract->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-eye"></i> Detay
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="mdi mdi-file-document-outline font-size-48 text-muted mb-2"></i>
                                        <h5>Henüz sözleşme bulunmuyor</h5>
                                        <p class="text-muted">Yeni taşeron sözleşmeleri eklemek için yukarıdaki butonu kullanabilirsiniz.</p>
                                    </td>
                                </tr>
                                @endforelse
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(!empty($subcontractorId))
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ödeme Hareketleri (Hakediş/Faturalar)</h5>
                </div>
                <div class="card-body">
                    @if($payments->count())
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tarih</th>
                                        <th>No</th>
                                        <th>Referans</th>
                                        <th>Tutar</th>
                                        <th>Durum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $p)
                                    <tr>
                                        <td>{{ optional($p->date)->format('d.m.Y') ?? '-' }}</td>
                                        <td>{{ $p->no ?? '-' }}</td>
                                        <td>{{ $p->contract ?? ($p->subcontractor_contract_id ? 'Sözleşme #' . $p->subcontractor_contract_id : '-') }}</td>
                                        <td>{{ number_format($p->approved ?? $p->requested ?? 0, 2, ',', '.') }} TL</td>
                                        <td>{{ ucfirst($p->status ?? 'bilinmiyor') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2">{{ $payments->appends(request()->except('payments_page'))->links() }}</div>
                    @else
                        <div class="text-muted">Bu taşerona ait ödeme hareketi bulunamadı.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Kasa / Banka Hareketleri (Taşeron ilişkili)</h5>
                </div>
                <div class="card-body">
                    @if($cashBank->count())
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tarih</th>
                                        <th>Hesap</th>
                                        <th>Açıklama</th>
                                        <th>Miktar</th>
                                        <th>Yön</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cashBank as $tx)
                                    <tr>
                                        <td>{{ optional($tx->transaction_date)->format('d.m.Y') ?? '-' }}</td>
                                        <td>{{ $tx->account->name ?? 'Hesap #' . ($tx->account_id ?? '') }}</td>
                                        <td>{{ Str::limit($tx->description, 80) }}</td>
                                        <td>{{ number_format($tx->amount_tl ?? $tx->amount, 2, ',', '.') }}</td>
                                        <td>{{ $tx->direction ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2">{{ $cashBank->appends(request()->except('cashbank_page'))->links() }}</div>
                    @else
                        <div class="text-muted">Bu taşerona ait kasa/banka hareketi bulunamadı.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(!empty($timeline) && $timeline->count())
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Birleşik Finansal Zaman Çizgisi (son işlemler)</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($timeline as $entry)
                            @php $d = $entry['date']; $type = $entry['type']; $data = $entry['data']; @endphp
                            <li class="mb-3">
                                <div class="small text-muted">{{
                                    (is_object($d) && method_exists($d,'format')) ? $d->format('d.m.Y') : (is_string($d) ? \Carbon\Carbon::parse($d)->format('d.m.Y') : '-')
                                }} - <strong>{{ strtoupper($type) }}</strong></div>
                                <div>
                                    @if($type === 'payment')
                                        <strong>Ödeme:</strong> {{ $data->no ?? 'N/A' }} — {{ number_format($data->approved ?? $data->requested ?? 0,2,',','.') }} TL
                                    @elseif($type === 'capital')
                                        <strong>Para Hareketi:</strong> {{ Str::limit($data->description, 120) }} — {{ number_format($data->amount ?? 0,2,',','.') }}
                                    @elseif($type === 'instruction')
                                        <strong>Ödeme Talimatı:</strong> {{ $data->payee ?? '-' }} — {{ number_format($data->amount ?? 0,2,',','.') }}
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-2">{{ $timeline->appends(request()->except('timeline_page'))->links() }}</div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
@parent
<script>
document.addEventListener('DOMContentLoaded', function(){
    const trigger = document.getElementById('open-new-contract-modal');
    if (trigger) {
        trigger.addEventListener('click', function(e){
            e.preventDefault();
            // create modal container if missing
            let modalEl = document.getElementById('newContractAjaxModal');
            if (!modalEl) {
                modalEl = document.createElement('div');
                modalEl.id = 'newContractAjaxModalContainer';
                modalEl.innerHTML = `
                <div class="modal fade" id="newContractAjaxModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Yeni Sözleşme Oluştur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                            </div>
                            <div class="modal-body" id="new-contract-modal-body">
                                <div class="text-center py-4">Yükleniyor...</div>
                            </div>
                        </div>
                    </div>
                </div>`;
                document.body.appendChild(modalEl);
            }

            fetch("{{ route('contracts.create') }}", { headers: { 'X-Requested-With':'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => {
                    const body = document.getElementById('new-contract-modal-body');
                    if (body) body.innerHTML = html;
                    const modal = new bootstrap.Modal(document.getElementById('newContractAjaxModal'));
                    modal.show();
                })
                .catch(() => {
                    const body = document.getElementById('new-contract-modal-body');
                    if (body) body.innerHTML = '<div class="text-danger p-3">Form yüklenirken hata oluştu.</div>';
                });
        });
    }
});
</script>
@endsection
