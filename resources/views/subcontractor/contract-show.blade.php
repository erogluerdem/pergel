@extends('layouts.master')

@section('title', 'Sözleşme Detayları - ' . $contract->contract_number)

@section('topbar-title', 'Taşeron Yönetimi')

@section('css')
@endsection

@section('content')
<div class="container-fluid">
    <!-- Sözleşme Özet Kartı -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title">{{ $contract->contract_number }} - {{ $contract->contract_name }}</h5>
                            <p class="card-text text-muted">
                                {{ $contract->subcontractor->company_name ?? 'N/A' }} |
                                {{ $contract->project->name ?? 'N/A' }}
                            </p>
                            <div class="d-flex gap-3">
                                <div>
                                    <strong>Sözleşme Bedeli:</strong>
                                    {{ number_format($contract->total_amount, 0, ',', '.') }} TL
                                </div>
                                <div>
                                    <strong>Ödenen Tutar:</strong>
                                    {{ number_format($contract->paid_amount, 0, ',', '.') }} TL
                                </div>
                                <div>
                                    <strong>Kalan Bakiye:</strong>
                                    {{ number_format($contract->remaining_amount, 0, ',', '.') }} TL
                                </div>
                                <div>
                                    <strong>Durum:</strong>
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
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('subcontractor.contracts.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="mdi mdi-arrow-left"></i> Listeye Dön
                            </a>
                            <button class="btn btn-primary">
                                <i class="mdi mdi-pencil"></i> Düzenle
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sekmeli İçerik -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#summary" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-info"></i></span>
                                <span class="d-none d-sm-block">Özet ve Finansal Durum</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#specifications" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-list"></i></span>
                                <span class="d-none d-sm-block">Keşif Özeti ve Hakedişler</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#changes" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-edit"></i></span>
                                <span class="d-none d-sm-block">Ek Talepler ve Zeyilnameler</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#obligations" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-file"></i></span>
                                <span class="d-none d-sm-block">Yükümlülükler ve Dokümanlar</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content p-3">
                        <!-- Özet ve Finansal Durum Sekmesi -->
                        <div class="tab-pane active" id="summary" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Sözleşme Künyesi</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Sözleşme Numarası:</strong></td>
                                            <td>{{ $contract->contract_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sözleşme Adı:</strong></td>
                                            <td>{{ $contract->contract_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Taşeron Firma:</strong></td>
                                            <td>{{ $contract->subcontractor->company_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Proje:</strong></td>
                                            <td>{{ $contract->project->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sözleşme Tipi:</strong></td>
                                            <td>
                                                @if($contract->contract_type == 'unit_price')
                                                    Birim Fiyat
                                                @else
                                                    Götürü Bedel
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Başlangıç Tarihi:</strong></td>
                                            <td>{{ $contract->start_date ? $contract->start_date->format('d.m.Y') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Bitiş Tarihi:</strong></td>
                                            <td>{{ $contract->end_date ? $contract->end_date->format('d.m.Y') : '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>Finansal İlerleme</h5>
                                    <div class="mb-3">
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ $contract->progress_percentage }}%"
                                                 aria-valuenow="{{ $contract->progress_percentage }}"
                                                 aria-valuemin="0" aria-valuemax="100">
                                                %{{ $contract->progress_percentage }}
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            {{ number_format($contract->paid_amount, 0, ',', '.') }} TL /
                                            {{ number_format($contract->total_amount, 0, ',', '.') }} TL
                                        </small>
                                    </div>

                                    <h6>Yaklaşan Yükümlülükler</h6>
                                    @if(count($expiringInsurances) > 0)
                                        @foreach($expiringInsurances as $insurance => $details)
                                            <div class="alert alert-{{ $details['status'] == 'expired' ? 'danger' : ($details['status'] == 'critical' ? 'danger' : 'warning') }}">
                                                <i class="mdi mdi-alert-circle-outline"></i>
                                                <strong>{{ ucfirst($insurance) }}:</strong>
                                                @if($details['days_remaining'] < 0)
                                                    Süresi {{ abs($details['days_remaining']) }} gün önce dolmuş
                                                @else
                                                    {{ $details['days_remaining'] }} gün kaldı
                                                @endif
                                                ({{ $details['expiry_date']->format('d.m.Y') }})
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="alert alert-success">
                                            <i class="mdi mdi-check-circle-outline"></i>
                                            Tüm sigorta poliçeleri güncel
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Keşif Özeti ve Hakedişler Sekmesi -->
                        <div class="tab-pane" id="specifications" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5>Keşif Özeti</h5>
                                    @if($contract->specifications)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Poz No</th>
                                                        <th>Açıklama</th>
                                                        <th>Birim</th>
                                                        <th>Miktar</th>
                                                        <th>Birim Fiyat</th>
                                                        <th>Toplam</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Keşif pozları burada listelenecek -->
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="mdi mdi-file-chart font-size-48 text-muted mb-2"></i>
                                            <h6>Keşif özeti henüz yüklenmemiş</h6>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h5>Hakediş Geçmişi</h5>
                                    @if($paymentHistory->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Hakediş No</th>
                                                        <th>Tarih</th>
                                                        <th>Tutar</th>
                                                        <th>Durum</th>
                                                        <th>İşlem</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($paymentHistory as $payment)
                                                    <tr>
                                                        <td>{{ $payment->no }}</td>
                                                        <td>{{ $payment->date ? \Carbon\Carbon::parse($payment->date)->format('d.m.Y') : '-' }}</td>
                                                        <td>{{ number_format($payment->approved ?? $payment->requested, 0, ',', '.') }} TL</td>
                                                        <td>
                                                            @switch($payment->status)
                                                                @case('approved')
                                                                    <span class="badge bg-success">Onaylandı</span>
                                                                    @break
                                                                @case('pending')
                                                                    <span class="badge bg-warning">Bekliyor</span>
                                                                    @break
                                                                @case('rejected')
                                                                    <span class="badge bg-danger">Reddedildi</span>
                                                                    @break
                                                                @default
                                                                    <span class="badge bg-secondary">{{ $payment->status }}</span>
                                                            @endswitch
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary" onclick="showPaymentDetails({{ $payment->id }})">
                                                                <i class="mdi mdi-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="mdi mdi-cash-multiple font-size-48 text-muted mb-2"></i>
                                            <h6>Henüz hakediş ödemesi yapılmamış</h6>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Ek Talepler ve Zeyilnameler Sekmesi -->
                        <div class="tab-pane" id="changes" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5>Ek Talepler (Change Orders)</h5>
                                    @if($changeOrders->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Konu</th>
                                                        <th>Maliyet</th>
                                                        <th>Durum</th>
                                                        <th>Onay Tarihi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($changeOrders as $changeOrder)
                                                    <tr>
                                                        <td>{{ $changeOrder->no }}</td>
                                                        <td>{{ $changeOrder->subject }}</td>
                                                        <td>{{ number_format($changeOrder->cost, 0, ',', '.') }} TL</td>
                                                        <td>
                                                            @switch($changeOrder->status)
                                                                @case('approved')
                                                                    <span class="badge bg-success">Onaylandı</span>
                                                                    @break
                                                                @case('pending')
                                                                    <span class="badge bg-warning">Bekliyor</span>
                                                                    @break
                                                                @case('rejected')
                                                                    <span class="badge bg-danger">Reddedildi</span>
                                                                    @break
                                                                @default
                                                                    <span class="badge bg-secondary">{{ $changeOrder->status }}</span>
                                                            @endswitch
                                                        </td>
                                                        <td>{{ $changeOrder->approved_at ? $changeOrder->approved_at->format('d.m.Y') : '-' }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="mdi mdi-file-edit-outline font-size-48 text-muted mb-2"></i>
                                            <h6>Henüz ek talep bulunmuyor</h6>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h5>Zeyilnameler</h5>
                                    <div class="text-center py-4">
                                        <i class="mdi mdi-file-document-outline font-size-48 text-muted mb-2"></i>
                                        <h6>Sözleşme ekleri yakında eklenecek</h6>
                                        <p class="text-muted small">Sözleşmedeki değişiklikleri belgeleyen zeyilnameler burada listelenecektir.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Yükümlülükler ve Dokümanlar Sekmesi -->
                        <div class="tab-pane" id="obligations" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Ana Sözleşme Dosyası</h5>
                                    @php
                                        $contractDocuments = $contract->documents()->where('status', 'active')->get();
                                        $mainContract = $contractDocuments->where('name', 'like', '%ana sözleşme%')->first();
                                    @endphp
                                    @if($mainContract)
                                        <div class="list-group">
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $mainContract->name }}
                                                <div>
                                                    <button class="btn btn-sm btn-outline-primary me-1">
                                                        <i class="mdi mdi-eye"></i> Görüntüle
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary">
                                                        <i class="mdi mdi-download"></i> İndir
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="mdi mdi-information-outline"></i>
                                            Ana sözleşme dosyası henüz yüklenmemiş.
                                        </div>
                                    @endif

                                    <h5 class="mt-4">Teminatlar</h5>
                                    @php
                                        $guarantees = $contractDocuments->where('name', 'like', '%teminat%');
                                    @endphp
                                    @if($guarantees->count() > 0)
                                        <div class="list-group">
                                            @foreach($guarantees as $guarantee)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $guarantee->name }}
                                                <span class="badge bg-success">Geçerli</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="mdi mdi-alert-circle-outline"></i>
                                            Teminat belgeleri eksik.
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h5>Sigorta Poliçeleri</h5>
                                    @if($contract->insurance_expiry_dates)
                                        <div class="list-group">
                                            @foreach($contract->insurance_expiry_dates as $insurance => $expiryDate)
                                                @php
                                                    $expiry = \Carbon\Carbon::parse($expiryDate);
                                                    $daysRemaining = now()->diffInDays($expiry, false);
                                                    $status = $daysRemaining < 0 ? 'expired' : ($daysRemaining <= 30 ? 'warning' : 'success');
                                                @endphp
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ ucfirst($insurance) }}
                                                <span class="badge bg-{{ $status == 'expired' ? 'danger' : ($status == 'warning' ? 'warning' : 'success') }}">
                                                    @if($daysRemaining < 0)
                                                        Süresi Dolmuş
                                                    @else
                                                        {{ $daysRemaining }} gün
                                                    @endif
                                                </span>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="mdi mdi-alert-circle-outline"></i>
                                            Sigorta bilgileri henüz girilmemiş.
                                        </div>
                                    @endif

                                    <h5 class="mt-4">Diğer Dokümanlar</h5>
                                    @php
                                        $otherDocs = $contractDocuments->whereNotIn('name', ['ana sözleşme', 'teminat']);
                                    @endphp
                                    @if($otherDocs->count() > 0)
                                        <div class="list-group">
                                            @foreach($otherDocs as $doc)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $doc->name }}
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="mdi mdi-download"></i>
                                                </button>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="mdi mdi-information-outline"></i>
                                            Ek doküman bulunmuyor.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
