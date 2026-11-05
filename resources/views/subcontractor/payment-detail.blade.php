@extends('layouts.master')

@section('title', 'Ödeme Detayı')

@section('topbar-title', 'Taşeron Yönetimi')

@section('css')
@endsection

@section('content')
<div class="container-fluid">

    <!-- Geri Dön Butonu -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('subcontractor.payments') }}" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left"></i> Ödeme Listesine Dön
            </a>
        </div>
    </div>

    <!-- Ödeme Bilgileri -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Ödeme Bilgileri</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ödeme Numarası:</label>
                                <p class="mb-0">#{{ $payment->id }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ödeme Tarihi:</label>
                                <p class="mb-0">{{ $payment->payment_date ? $payment->payment_date->format('d.m.Y') : '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ödenen Tutar:</label>
                                <p class="mb-0 fs-5 text-primary">{{ number_format($payment->amount, 2) }} TL</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alıcı Firma:</label>
                                <p class="mb-0">{{ $payment->payee }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Proje:</label>
                                <p class="mb-0">{{ $payment->project_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Banka Hesabı:</label>
                                <p class="mb-0">{{ $payment->bank_account ?: '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ödeme Yapan:</label>
                                <p class="mb-0">{{ $payment->processor ? $payment->processor->name : '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Durum:</label>
                                <span class="badge bg-success">Ödendi</span>
                            </div>
                        </div>
                    </div>

                    @if($payment->notes)
                    <div class="mt-3">
                        <label class="form-label fw-bold">Notlar:</label>
                        <p class="mb-0">{{ $payment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Ödeme Kanıtı -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Ödeme Kanıtı</h4>
                </div>
                <div class="card-body">
                    @if($payment->receipt_path)
                        <div class="text-center">
                            <a href="{{ asset('storage/' . $payment->receipt_path) }}" target="_blank" class="btn btn-primary btn-lg">
                                <i class="mdi mdi-file-document-outline"></i> Dekontu Görüntüle
                            </a>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="mdi mdi-file-document-outline fs-1"></i>
                            <p>Dekont yüklenmemiş</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- İlişkili Kayıtlar -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">İlişkili Kayıtlar</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- İlişkili Hakediş -->
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <i class="mdi mdi-file-chart fs-1 text-primary mb-2"></i>
                                    <h6>İlişkili Hakediş</h6>
                                    @if($reference)
                                        <p class="mb-2">
                                            @if($payment->reference_type === 'progress_claim')
                                                Hakediş #{{ $reference->id }}
                                            @else
                                                Fatura #{{ $reference->id }}
                                            @endif
                                        </p>
                                        <a href="#" class="btn btn-sm btn-outline-primary">Hakediş Detayına Git</a>
                                    @else
                                        <p class="text-muted">İlişkilendirilmemiş</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- İlişkili Sözleşme -->
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <i class="mdi mdi-file-sign fs-1 text-success mb-2"></i>
                                    <h6>İlişkili Sözleşme</h6>
                                    @if($contract)
                                        <p class="mb-2">{{ $contract->contract_name }}</p>
                                        <a href="{{ route('subcontractor.contracts.show', $contract->id) }}" class="btn btn-sm btn-outline-success">Sözleşme Detayına Git</a>
                                    @else
                                        <p class="text-muted">İlişkilendirilmemiş</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Taşeron Profili -->
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <i class="mdi mdi-office-building fs-1 text-info mb-2"></i>
                                    <h6>Taşeron Profili</h6>
                                    @if($subcontractor)
                                        <p class="mb-2">{{ $subcontractor->company_name }}</p>
                                        <a href="{{ route('subcontractor.show', $subcontractor->id) }}" class="btn btn-sm btn-outline-info">Profil Detayına Git</a>
                                    @else
                                        <p class="text-muted">Bulunamadı</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Denetim İzi -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Denetim İzi</h4>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($auditLog as $log)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">{{ $log['action'] }}</h6>
                                <p class="timeline-text">{{ $log['user'] }}</p>
                                <small class="text-muted">{{ $log['timestamp']->format('d.m.Y H:i') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection
